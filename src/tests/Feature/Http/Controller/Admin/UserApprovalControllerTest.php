<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\UserChangeRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class UserApprovalControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_approve_user_create_request(): void
    {
        $changeRequest = UserChangeRequest::factory()->actionCreate()->create();

        $role = Role::create(['name' => 'owner']);
        $user = User::factory()->create();
        $user->assignRole($role);

        $response = $this->actingAs($user)
            ->get(route('admin.approvals.users.show', $changeRequest));

        $response->assertStatus(200);

        // 画面に申請内容が描画されているかチェック
        $response->assertSee($changeRequest->payload['name']);
        $response->assertSee($changeRequest->payload['email']);

        // ビューに渡されたデータ（Model）の不整合がないか確認
        $response->assertViewHas('changeRequest', function ($viewChangeRequest) use ($changeRequest) {
            return $viewChangeRequest->id === $changeRequest->id
                && $viewChangeRequest->action_type === 'create';
        });
    }

}
