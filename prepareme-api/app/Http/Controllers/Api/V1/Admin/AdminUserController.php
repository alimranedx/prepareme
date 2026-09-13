<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Enums\UserStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminUserController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = User::query();

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = '%' . $request->search . '%';
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', $search)
                  ->orWhere('email', 'like', $search);
            });
        }

        $users = $query->latest('id')->paginate(20);

        return UserResource::collection($users)->response();
    }

    public function show(User $user): JsonResponse
    {
        return response()->json([
            'data' => new UserResource($user),
        ]);
    }

    public function updateStatus(Request $request, User $user): JsonResponse
    {
        $request->validate([
            'status' => 'required|in:active,blocked',
        ]);

        if ((int) $request->user()->id === (int) $user->id) {
            return response()->json([
                'message' => 'You cannot modify your own administrative account status.',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $user->update([
            'status' => UserStatus::from($request->status),
        ]);

        // If blocked, immediately revoke active tokens
        if ($user->status === UserStatus::BLOCKED) {
            $user->tokens()->delete();
        }

        return response()->json([
            'message' => "User status updated to {$user->status->value}.",
            'data' => new UserResource($user),
        ]);
    }
}
