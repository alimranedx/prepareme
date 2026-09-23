<?php

namespace App\Http\Controllers\Api\V1\Study;

use App\Http\Controllers\Controller;
use App\Http\Resources\ModelTestResource;
use App\Http\Resources\TestAttemptResource;
use App\Models\ModelTest;
use App\Models\PublicQuestion;
use App\Models\TestAnswer;
use App\Models\TestAttempt;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ModelTestController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = ModelTest::published()
            ->with(['exam', 'subject'])
            ->withCount('questions');

        if ($request->filled('exam_id')) {
            $query->where('exam_id', $request->exam_id);
        }

        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        if ($request->filled('model_test_type')) {
            $query->where('model_test_type', $request->model_test_type);
        }

        $modelTests = $query->latest('id')->paginate(12);

        return ModelTestResource::collection($modelTests)->response();
    }

    public function show(string $modelTestIdentifier): JsonResponse
    {
        $modelTest = ModelTest::published()
            ->where(function ($q) use ($modelTestIdentifier) {
                if (is_numeric($modelTestIdentifier)) {
                    $q->where('id', $modelTestIdentifier);
                } else {
                    $q->where('slug', $modelTestIdentifier);
                }
            })
            ->with(['exam', 'subject'])
            ->withCount('questions')
            ->firstOrFail();

        return response()->json([
            'data' => new ModelTestResource($modelTest),
        ]);
    }

    public function start(Request $request, string $modelTestIdentifier): JsonResponse
    {
        $modelTest = ModelTest::published()
            ->where(function ($q) use ($modelTestIdentifier) {
                if (is_numeric($modelTestIdentifier)) {
                    $q->where('id', $modelTestIdentifier);
                } else {
                    $q->where('slug', $modelTestIdentifier);
                }
            })
            ->with(['questions' => function ($q) {
                $q->published()->with(['optionsList', 'subject', 'chapter', 'topic']);
            }])
            ->firstOrFail();

        $userId = $request->user('sanctum')?->id;

        $attempt = TestAttempt::create([
            'model_test_id' => $modelTest->id,
            'user_id' => $userId,
            'total_questions' => $modelTest->questions->count(),
            'total_answered' => 0,
            'total_correct' => 0,
            'total_wrong' => 0,
            'total_skipped' => $modelTest->questions->count(),
            'score' => 0,
            'percentage' => 0,
            'is_passed' => false,
            'status' => 'in_progress',
            'started_at' => now(),
        ]);

        // Strip correct answers to ensure exam integrity
        $questionsPayload = $modelTest->questions->map(function ($q, $index) {
            $options = [];
            if ($q->optionsList && $q->optionsList->isNotEmpty()) {
                foreach ($q->optionsList as $opt) {
                    $options[] = [
                        'key' => $opt->option_key,
                        'text' => $opt->option_text,
                    ];
                }
            } elseif (is_array($q->options)) {
                foreach ($q->options as $k => $val) {
                    $options[] = [
                        'key' => (string) $k,
                        'text' => (string) $val,
                    ];
                }
            }

            return [
                'serial' => $index + 1,
                'id' => $q->id,
                'question' => $q->question,
                'subject_name' => $q->subject?->name,
                'chapter_name' => $q->chapter?->name,
                'topic_name' => $q->topic?->name,
                'marks' => (float) ($q->marks ?? 1.00),
                'options' => $options,
            ];
        });

        return response()->json([
            'attempt_id' => $attempt->id,
            'model_test' => [
                'id' => $modelTest->id,
                'title' => $modelTest->title,
                'slug' => $modelTest->slug,
                'duration_minutes' => $modelTest->duration_minutes,
                'total_questions' => $modelTest->questions->count(),
                'total_marks' => (float) $modelTest->total_marks,
                'negative_marking_rate' => (float) $modelTest->negative_marking_rate,
            ],
            'questions' => $questionsPayload,
        ]);
    }

    public function submit(Request $request, int $attemptId): JsonResponse
    {
        $attempt = TestAttempt::with('modelTest.questions')->findOrFail($attemptId);

        if ($attempt->submitted_at !== null) {
            return response()->json([
                'message' => 'এই মডেল টেস্টটি ইতিমধ্যে জমা দেওয়া হয়েছে।',
                'attempt_id' => $attempt->id,
            ], 422);
        }

        $validated = $request->validate([
            'answers' => 'required|array',
            'answers.*.question_id' => 'required|integer|exists:public_questions,id',
            'answers.*.selected_option' => 'nullable|string',
            'answers.*.time_spent_seconds' => 'nullable|integer',
            'time_taken_seconds' => 'nullable|integer',
        ]);

        $modelTest = $attempt->modelTest;
        $negativeRate = (float) $modelTest->negative_marking_rate;
        $allTestQuestions = $modelTest->questions->keyBy('id');

        $answersMap = collect($validated['answers'])->keyBy('question_id');

        $totalQuestions = $allTestQuestions->count();
        $totalAnswered = 0;
        $totalCorrect = 0;
        $totalWrong = 0;
        $totalSkipped = 0;
        $earnedMarks = 0.00;
        $negativeDeducted = 0.00;

        DB::beginTransaction();
        try {
            foreach ($allTestQuestions as $qId => $question) {
                $userAns = $answersMap->get($qId);
                $selectedOption = $userAns['selected_option'] ?? null;
                $timeSpent = $userAns['time_spent_seconds'] ?? 0;

                $qMarks = (float) ($question->marks ?? 1.00);

                if (empty($selectedOption)) {
                    $totalSkipped++;
                    $isCorrect = false;
                    $marksObtained = 0.00;
                } else {
                    $totalAnswered++;
                    if ($selectedOption === $question->correct_option) {
                        $totalCorrect++;
                        $isCorrect = true;
                        $marksObtained = $qMarks;
                        $earnedMarks += $qMarks;
                    } else {
                        $totalWrong++;
                        $isCorrect = false;
                        $penalty = $negativeRate;
                        $marksObtained = -$penalty;
                        $negativeDeducted += $penalty;
                    }
                }

                TestAnswer::create([
                    'attempt_id' => $attempt->id,
                    'public_question_id' => $qId,
                    'selected_option' => $selectedOption,
                    'is_correct' => $isCorrect,
                    'marks_awarded' => $marksObtained,
                    'time_spent_seconds' => $timeSpent,
                ]);
            }

            $finalScore = max(0.00, $earnedMarks - $negativeDeducted);
            $accuracy = $totalAnswered > 0 ? round(($totalCorrect / $totalAnswered) * 100, 2) : 0.00;
            $isPassed = $finalScore >= (float) $modelTest->pass_marks;

            $attempt->update([
                'total_questions' => $totalQuestions,
                'total_answered' => $totalAnswered,
                'total_correct' => $totalCorrect,
                'total_wrong' => $totalWrong,
                'total_skipped' => $totalSkipped,
                'score' => $finalScore,
                'percentage' => $accuracy,
                'is_passed' => $isPassed,
                'duration_seconds' => $validated['time_taken_seconds'] ?? null,
                'submitted_at' => now(),
                'status' => 'completed',
            ]);

            DB::commit();

            return response()->json([
                'message' => 'মডেল টেস্ট সফলভাবে জমা দেওয়া হয়েছে।',
                'attempt_id' => $attempt->id,
                'score' => $finalScore,
                'total_correct' => $totalCorrect,
                'total_wrong' => $totalWrong,
                'is_passed' => $isPassed,
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'ফলাফল প্রক্রিয়াকরণে ত্রুটি হয়েছে। ' . $e->getMessage(),
            ], 500);
        }
    }

    public function result(int $attemptId): JsonResponse
    {
        $attempt = TestAttempt::with([
            'modelTest.exam',
            'modelTest.subject',
            'answers.question.optionsList',
            'answers.question.subject',
            'answers.question.chapter',
            'answers.question.topic',
            'answers.question.source',
        ])->findOrFail($attemptId);

        return response()->json([
            'data' => new TestAttemptResource($attempt),
        ]);
    }

    public function mistakes(int $attemptId): JsonResponse
    {
        $attempt = TestAttempt::with([
            'modelTest',
            'answers' => function ($q) {
                $q->where('is_correct', false)
                  ->whereNotNull('selected_option')
                  ->with(['question.optionsList', 'question.subject', 'question.chapter', 'question.topic', 'question.source']);
            },
        ])->findOrFail($attemptId);

        return response()->json([
            'attempt_id' => $attempt->id,
            'model_test_title' => $attempt->modelTest->title,
            'total_mistakes' => $attempt->answers->count(),
            'mistakes' => $attempt->answers->map(function ($ans) {
                return [
                    'answer_id' => $ans->id,
                    'selected_option' => $ans->selected_option,
                    'correct_option' => $ans->question->correct_option,
                    'question' => new \App\Http\Resources\PublicQuestionResource($ans->question),
                ];
            }),
        ]);
    }
}
