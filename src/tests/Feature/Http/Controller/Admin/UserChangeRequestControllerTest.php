<?php

namespace Tests\Feature\Http\Controller\Admin;

use App\Models\User;
use App\Models\UserChangeRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class UserChangeRequestControllerTest extends TestCase
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

    public function test_manager_can_edit_own_user_change_request(): void
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
            // 'requester' => ['id' => $this->manager->id],
            'requested_by' => $this->manager->id,
        ]);

        $changeRequest = [
            'name' => 'テスト 太郎',
            'login_id' => 'test.taroh',
            'email' => 'testtaro@example.org',
            'role' => 'worker',
        ];

        $response = $this->actingAs($this->manager)
            ->patchJson(route('admin.users.update', $changeRequest));

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'ユーザー登録の申請を送信しました。'
            ]);

        $this->assertDatabaseHas('user_change_request', [
            'name' => 'テスト 太郎',
            'email' => 'testtaro@example.org',
        ]);

        // // user_change_requests テーブルの状態が「approved」に更新されているか
        // $this->assertDatabaseHas('user_change_requests', [
        //     'id' => $changeRequest->id,
        //     'status' => 'approved',
        //     'approved_by' => $this->owner->id,
        // ]);
    }
}
