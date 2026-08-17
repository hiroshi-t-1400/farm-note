<?php

namespace Tests\Feature\Http\Controller\Auth;

use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class RegisterControllerTest extends TestCase
{

    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_success_try_register_and_email_verifing(): void
    {
        // メール通知をモック化
        Notification::fake();

        // アカウント登録APIの実行Json Post
        $response = $this->postJson('/register', [
            'name' => 'トマト 太郎',
            'email' => 'tomatotaro@example.org',
            'login_id' => 'tomatotaro',
            'password' => 'tomatot@ro',
            'password_confirmation' => 'tomatot@ro',
        ]);

        // APIレスポンスの検証
        $response->assertStatus(200)
            ->assertJson([
            'status' => 'verification-link-sent',
            'message' => '登録されたメールを確認して、アカウント登録を完了してください。',
            // 'user' => $user,
        ]);


        // データベースに登録したユーザーが存在するか
        $this->assertDatabaseHas('users', [
            'email' => 'tomatotaro@example.org',
            'login_id' => 'tomatotaro',
            'email_verified_at' => null,
        ]);

        // 登録したユーザー情報を取得し、VerifyEmail::classによる通知が送信されたかを確認
        $user = User::where('email', 'tomatotaro@example.org')->first();
        Notification::assertSentTo($user, VerifyEmail::class);

        // 認証済みログインユーザーとして'/email/verify'を開けるか確認
        $this->actingAs($user)
            ->get('/email/verify')
            ->assertStatus(200)
            ->assertViewIs('auth.verify-email');

        // メール認証用の署名付きURL
        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            [
                'id' => $user->getKey(),
                'hash' => sha1($user->getEmailForVerification()),
            ]
        );

        // 作成したユーザーとして署名付きURLをクリックさせる
        $response = $this->actingAs($user)->get($verificationUrl);

        // 設定した画面へリダイレクトされたか
        $response->assertRedirect('/dashboard?verified=1');

        // ユーザーのメール認証フラグ email_verified_atが更新されたか
        $this->assertTrue($user->fresh()->hasVerifiedEmail());

        $response->assertStatus(302);
    }

    // ユーザー操作による認証メールの再送信ロジック
    public function test_resend_verification_email(): void
    {
        Notification::fake();

        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/email/verification-notification');

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'verification-link-sent',
                'message' => 'メールを送信しました。確認してください。'
            ]);

        Notification::assertSentTo($user, VerifyEmail::class);

    }

    // 認証用メールの承認URLが改ざんされている場合
    public function test_user_cannot_verify_email_with_invalid_hash(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        // 不正なハッシュを生成
        $invalidVerificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            [
                'id' => $user->getKey(),
                'hash' => sha1('invalid-email@example.com'),
            ]
        );

        // 認証を試行
        $this->actingAs($user)->get($invalidVerificationUrl);

        // ユーザーのメール認証フラグを確認
        $this->assertFalse($user->fresh()->hasVerifiedEmail());
    }
}
