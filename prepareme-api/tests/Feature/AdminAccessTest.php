<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_user_cannot_access_admin_endpoints(): void
    {
        $response = $this->getJson('/api/v1/admin/dashboard');

        $response->assertStatus(401);
    }

    public function test_normal_user_cannot_access_admin_endpoints(): void
    {
        $user = User::factory()->create([
            'role' => UserRole::USER,
            'status' => UserStatus::ACTIVE,
        ]);

        $response = $this->actingAs($user)->getJson('/api/v1/admin/dashboard');

        $response->assertStatus(403)
            ->assertJson([
                'message' => 'Access denied. Administrative privileges required.',
            ]);
    }

    public function test_admin_can_access_dashboard(): void
    {
        $admin = User::factory()->create([
            'role' => UserRole::ADMIN,
            'status' => UserStatus::ACTIVE,
        ]);

        $response = $this->actingAs($admin)->getJson('/api/v1/admin/dashboard');

        $response->assertOk()
            ->assertJsonStructure([
                'data' => ['users', 'content', 'ocr'],
            ]);
    }

    public function test_admin_can_change_user_status(): void
    {
        $admin = User::factory()->create([
            'role' => UserRole::ADMIN,
            'status' => UserStatus::ACTIVE,
        ]);

        $targetUser = User::factory()->create([
            'role' => UserRole::USER,
            'status' => UserStatus::ACTIVE,
        ]);

        $response = $this->actingAs($admin)->putJson("/api/v1/admin/users/{$targetUser->id}/status", [
            'status' => 'blocked',
        ]);

        $response->assertOk()
            ->assertJsonPath('data.status', 'blocked');

        $this->assertDatabaseHas('users', [
            'id' => $targetUser->id,
            'status' => 'blocked',
        ]);
    }

    public function test_admin_cannot_block_own_account(): void
    {
        $admin = User::factory()->create([
            'role' => UserRole::ADMIN,
            'status' => UserStatus::ACTIVE,
        ]);

        $response = $this->actingAs($admin)->putJson("/api/v1/admin/users/{$admin->id}/status", [
            'status' => 'blocked',
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'message' => 'You cannot modify your own administrative account status.',
            ]);
    }
}
