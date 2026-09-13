<?php

use App\Http\Controllers\Api\V1\Admin\AdminDashboardController;
use App\Http\Controllers\Api\V1\Admin\AdminOcrController;
use App\Http\Controllers\Api\V1\Admin\AdminPublicQuestionController;
use App\Http\Controllers\Api\V1\Admin\AdminStudyGuideController;
use App\Http\Controllers\Api\V1\Admin\AdminSubjectController;
use App\Http\Controllers\Api\V1\Admin\AdminTopicController;
use App\Http\Controllers\Api\V1\Admin\AdminUserController;
use App\Http\Controllers\Api\V1\Auth\AuthController;
use App\Http\Controllers\Api\V1\OCR\OcrController;
use App\Http\Controllers\Api\V1\Study\PublicQuestionController;
use App\Http\Controllers\Api\V1\Study\StudyGuideController;
use App\Http\Controllers\Api\V1\Study\SubjectController;
use App\Http\Controllers\Api\V1\Study\TopicController;
use App\Http\Controllers\Api\V1\User\BookmarkController;
use App\Http\Controllers\Api\V1\User\PersonalQuestionController;
use App\Http\Controllers\Api\V1\User\ProfileController;
use App\Http\Controllers\Api\V1\User\ProgressController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    // ==========================================
    // Authentication (Rate limited)
    // ==========================================
    Route::prefix('auth')->group(function () {
        Route::post('register', [AuthController::class, 'register'])->middleware('throttle:10,1');
        Route::post('login', [AuthController::class, 'login'])->middleware('throttle:10,1');
        Route::post('forgot-password', [AuthController::class, 'forgotPassword'])->middleware('throttle:5,1');
        Route::post('reset-password', [AuthController::class, 'resetPassword'])->middleware('throttle:5,1');

        Route::middleware(['auth:sanctum', 'active'])->group(function () {
            Route::post('logout', [AuthController::class, 'logout']);
            Route::post('logout-all', [AuthController::class, 'logoutAll']);
        });
    });

    // ==========================================
    // Public Educational Browsing
    // ==========================================
    Route::get('subjects', [SubjectController::class, 'index']);
    Route::get('subjects/{subject}/topics', [SubjectController::class, 'topics']);
    Route::get('topics/{topic}', [TopicController::class, 'show']);
    Route::get('study-guides', [StudyGuideController::class, 'index']);
    Route::get('study-guides/{studyGuide}', [StudyGuideController::class, 'show']);
    Route::get('public-questions', [PublicQuestionController::class, 'index']);
    Route::get('public-questions/{question}', [PublicQuestionController::class, 'show']);

    // ==========================================
    // Authenticated User Endpoints
    // ==========================================
    Route::middleware(['auth:sanctum', 'active'])->group(function () {
        // User Profile
        Route::get('me', [ProfileController::class, 'show']);
        Route::put('me', [ProfileController::class, 'update']);

        // Personal Notebook
        Route::prefix('my')->group(function () {
            // Personal Questions
            Route::get('questions', [PersonalQuestionController::class, 'index']);
            Route::post('questions', [PersonalQuestionController::class, 'store']);
            Route::get('questions/{question}', [PersonalQuestionController::class, 'show']);
            Route::put('questions/{question}', [PersonalQuestionController::class, 'update']);
            Route::delete('questions/{question}', [PersonalQuestionController::class, 'destroy']);
            Route::post('questions/{question}/archive', [PersonalQuestionController::class, 'archive']);

            // Bookmarks
            Route::get('bookmarks', [BookmarkController::class, 'index']);
            Route::post('bookmarks', [BookmarkController::class, 'store']);
            Route::delete('bookmarks/{studyGuide}', [BookmarkController::class, 'destroy']);

            // Reading Progress
            Route::get('progress', [ProgressController::class, 'index']);
            Route::put('progress/{studyGuide}', [ProgressController::class, 'update']);

            // OCR Digitization Studio
            Route::get('ocr-documents', [OcrController::class, 'index']);
            Route::post('ocr-documents', [OcrController::class, 'upload'])->middleware('throttle:15,1');
            Route::get('ocr-documents/{document}', [OcrController::class, 'show']);
            Route::post('ocr-documents/{document}/convert', [OcrController::class, 'convert']);
            Route::post('ocr-documents/{document}/retry', [OcrController::class, 'retry']);
            Route::get('ocr-documents/{document}/result', [OcrController::class, 'result']);
            Route::post('ocr-documents/{document}/save-as-question', [OcrController::class, 'saveAsQuestion']);
            Route::delete('ocr-documents/{document}', [OcrController::class, 'destroy']);
        });
    });

    // ==========================================
    // Administrative Endpoints (Admin role only)
    // ==========================================
    Route::prefix('admin')->middleware(['auth:sanctum', 'admin'])->group(function () {
        Route::get('dashboard', [AdminDashboardController::class, 'dashboard']);

        // User Management
        Route::get('users', [AdminUserController::class, 'index']);
        Route::get('users/{user}', [AdminUserController::class, 'show']);
        Route::put('users/{user}/status', [AdminUserController::class, 'updateStatus']);

        // Subjects Management
        Route::apiResource('subjects', AdminSubjectController::class);

        // Topics Management
        Route::apiResource('topics', AdminTopicController::class)->except(['show']);

        // Study Guides Management
        Route::apiResource('study-guides', AdminStudyGuideController::class);
        Route::post('study-guides/{studyGuide}/publish', [AdminStudyGuideController::class, 'publish']);
        Route::post('study-guides/{studyGuide}/archive', [AdminStudyGuideController::class, 'archive']);

        // Public Questions Management
        Route::apiResource('public-questions', AdminPublicQuestionController::class);
        Route::post('public-questions/{question}/publish', [AdminPublicQuestionController::class, 'publish']);
        Route::post('public-questions/{question}/archive', [AdminPublicQuestionController::class, 'archive']);

        // OCR Monitoring
        Route::get('ocr-documents', [AdminOcrController::class, 'index']);
        Route::get('ocr-documents/{document}', [AdminOcrController::class, 'show']);
    });
});
