<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Enums\ContentStatus;
use App\Enums\OcrDocumentStatus;
use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Http\Controllers\Controller;
use App\Models\OcrDocument;
use App\Models\PublicQuestion;
use App\Models\StudyGuide;
use App\Models\Subject;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class AdminDashboardController extends Controller
{
    public function dashboard(): JsonResponse
    {
        $stats = [
            'users' => [
                'total' => User::where('role', UserRole::USER)->count(),
                'active' => User::where('role', UserRole::USER)->where('status', UserStatus::ACTIVE)->count(),
                'blocked' => User::where('role', UserRole::USER)->where('status', UserStatus::BLOCKED)->count(),
                'admins' => User::where('role', UserRole::ADMIN)->count(),
            ],
            'content' => [
                'subjects' => Subject::count(),
                'topics' => Topic::count(),
                'study_guides' => [
                    'total' => StudyGuide::count(),
                    'published' => StudyGuide::where('status', ContentStatus::PUBLISHED)->count(),
                    'draft' => StudyGuide::where('status', ContentStatus::DRAFT)->count(),
                    'archived' => StudyGuide::where('status', ContentStatus::ARCHIVED)->count(),
                ],
                'public_questions' => [
                    'total' => PublicQuestion::count(),
                    'published' => PublicQuestion::where('status', ContentStatus::PUBLISHED)->count(),
                    'draft' => PublicQuestion::where('status', ContentStatus::DRAFT)->count(),
                ],
            ],
            'ocr' => [
                'total' => OcrDocument::count(),
                'completed' => OcrDocument::where('status', OcrDocumentStatus::COMPLETED)->count(),
                'processing' => OcrDocument::where('status', OcrDocumentStatus::PROCESSING)->count(),
                'failed' => OcrDocument::where('status', OcrDocumentStatus::FAILED)->count(),
            ],
        ];

        return response()->json([
            'data' => $stats,
        ]);
    }
}
