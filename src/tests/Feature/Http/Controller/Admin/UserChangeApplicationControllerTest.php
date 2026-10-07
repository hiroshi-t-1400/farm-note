<?php

namespace Tests\Feature\Http\Controller\Admin;

use App\Models\Admin\UserChange\UserChangeApplication;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class UserChangeApplicationControllerTest extends TestCase
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

    public function test_access_user_change_index(): void
    {
        $response = $this->actingAs($this->manager)
            ->get(route('admin.applications.users.index'));

        $response->assertStatus(200);
    }

    public function test_manager_can_edit_own_user_change_application(): void
    {
        $changeApplication = UserChangeApplication::factory()->actionCreate()->create([
            'status' => 'pending',
            'payload' => [
                'name' => '新規 太郎',
                'email' => 'new_user@example.com',
                'login_id' => 'sinki.taroh',
                'role' => 'worker',
                'password' => 'passowrd',
                ],
            // 'requester' => ['id' => $this->manager->id],
            'appied_by' => $this->manager->id,
        ]);

        $changeApplication = [
            'name' => 'テスト 太郎',
            'login_id' => 'test.taroh',
            'email' => 'testtaro@example.org',
            'role' => 'worker',
        ];

        $response = $this->actingAs($this->manager)
            ->patchJson(route('admin.applications.users.update', $changeApplication));

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'ユーザー登録の申請を送信しました。'
            ]);

        $this->assertDatabaseHas('user_change_application', [
            'name' => 'テスト 太郎',
            'email' => 'testtaro@example.org',
        ]);

    }

    public function test_manager_can_create_application(): void
    {
        $response = $this->actingAs($this->manager)
            ->get('/admin/applications/users');

        $actionType = 'create';

        $applicationData = [
            'name' => 'tomatotaro',
            'login_id' => 'tomatotaro',
            'email' => 'tomato1234@example.org',
            'password' => 'tomato@@tomato',
            'role' => 'worker',
        ];

        $url = route('admin.applications.users.store-create');

        $response = $this->actingAs($this->manager)
            ->postJson($url, $applicationData);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'ユーザー登録の申請を送信しました。'
            ]);

        $data = UserChangeApplication::find(1);
        dump($data);

        $this->assertDatabaseHas('user_change_applications', [
            'id' => 1,
            'payload' => [
                'name' => 'tomatotaro',
                'email' => 'tomato1234@example.org',
            ],
        ]);

    }
}
