<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\UserChangeRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class UserApprovalControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $owner;
    private User $manager;

    protected function setUp(): void
    {
        parent::setUp();

        // ロールの準備
        $ownerRole = Role::create(['name' => 'owner']);
        $managerRole = Role::create(['name' => 'manager']);
        $workerRole = Role::create(['name' => 'worker']);

        // オーナーと管理者の作成
        $this->owner = User::factory()->create();
        $this->owner->assignRole($ownerRole);

        $this->manager = User::factory()->create();
        $this->manager->assignRole($managerRole);
    }

    public function test_owner_approve_user_create_request(): void
    {
        $changeRequest = UserChangeRequest::factory()->actionCreate()->create([
            'status' => 'pending',
            'payload' => [
                'name' => '新規 太郎',
                'email' => 'new_user@example.com',
                'login_id' => 'sinki.taroh',
                'role' => 'worker',
                'password' => 'passowrd',
            ],
        ]);

        $response = $this->actingAs($this->owner)
            ->get(route('admin.approvals.users.show', $changeRequest));

        $response->assertStatus(200);

        $response = $this->actingAs($this->owner)
            ->patchJson(route('admin.approvals.users.approve', $changeRequest));

        $response->assertStatus(200)
            ->assertJson([
                'message' => '変更申請を承認し、ユーザー情報を反映しました。'
            ]);

        $this->assertDatabaseHas('users', [
            'name' => '新規 太郎',
            'email' => 'new_user@example.com',
        ]);

        // user_change_requests テーブルの状態が「approved」に更新されているか
        $this->assertDatabaseHas('user_change_requests', [
            'id' => $changeRequest->id,
            'status' => 'approved',
            'approved_by' => $this->owner->id,
        ]);
    }


    public function test_owner_reject_user_create_request(): void
    {
        $changeRequest = UserChangeRequest::factory()->actionCreate()->create([
            'status' => 'pending',
            'payload' => [
                'name' => '新規 太郎',
                'email' => 'new_user@example.com',
                'login_id' => 'sinki.taroh',
                'role' => 'worker',
                'password' => 'passowrd',
            ],
        ]);

        $response = $this->actingAs($this->owner)
            ->get(route('admin.approvals.users.show', $changeRequest));

        $response->assertStatus(200);

        $response = $this->actingAs($this->owner)
            ->patchJson(route('admin.approvals.users.reject', $changeRequest), [
                'rejection_reason' => '入力内容に不備があります。',
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'message' => '変更申請を却下しました。'
            ]);

        $this->assertDatabaseMissing('users', [
            'name' => '新規 太郎',
            'email' => 'new_user@example.com',
        ]);

        // 申請テーブルの状態が変更されているか
        $this->assertDatabaseHas('user_change_requests', [
            'id' => $changeRequest->id,
            'status' => 'rejected',
            'approved_by' => $this->owner->id,
            'rejection_reason' => '入力内容に不備があります。',
        ]);
    }
}
