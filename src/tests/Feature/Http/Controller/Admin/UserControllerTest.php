<?php

namespace Tests\Feature\Http\Controller\Admin;

use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Database\Seeders\initUserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;
    // 全部seedしてから開始
    protected bool $seed = true;

    /**
     * A basic feature test example.
     */
    public function test_admin_request_create_user(): void
    {
        $user = User::where('login_id', 'kosaku1010')->first();

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

        $response->assertStatus(200);
    }
}
