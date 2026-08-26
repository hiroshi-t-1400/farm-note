<?php

namespace Tests\Feature\Http\Controller\Admin;

use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Database\Seeders\initUserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_cannot_access_user_create_page(): void
    {
        $role = Role::create(['name' => 'owner']);
        $owner = User::factory()->create();
        $owner->assignRole($role);

        $response = $this
            ->actingAs($owner)
            ->get('/admin/users/create');

        $response->assertStatus(403);

        $this->assertEquals(
            'User does not have the right roles.',
            $response->exception?->getMessage()
        );
    }

    public function test_worker_cannot_access_user_create_page(): void
    {
        $role = Role::create(['name' => 'woker']);
        $owner = User::factory()->create();
        $owner->assignRole($role);

        $response = $this
            ->actingAs($owner)
            ->get('/admin/users/create');

        $response->assertStatus(403);

        $this->assertEquals(
            'User does not have the right roles.',
            $response->exception?->getMessage()
        );
    }

    public function test_manager_can_access_user_create_page(): void
    {
        $role = Role::create(['name' => 'manager']);
        $manager = User::factory()->create();
        $manager->assignRole($role);

        $response = $this
            ->actingAs($manager)
            ->get('/admin/users/create');

        $response->assertStatus(200);
    }

    public function test_manager_request_create_user(): void
    {
        $role = Role::create(['name' => 'manager']);
        // $role = Role::create(['name' => 'owner']);
        $user = User::factory()->create();
        $user->assignRole($role);

        $response = $this
            ->actingAs($user)
            ->get('/admin/users/create');

        $response = $this->postJson('/admin/users/create', [
            'name' => 'トマト 太郎',
            'email' => 'tomato@example.org',
            'login_id' => 'tomatotaro',
            'password' => 'tomato@@1010',
            'role' => 'worker',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'pending',
                'message' => 'ユーザー登録の申請を送信しました。'
            ]);
    }
}
