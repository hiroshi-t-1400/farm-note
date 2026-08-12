<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class LoginController extends Controller
{
    //
        public function __construct()
    {

    }

    public function showLogin() {
        return response()->view('login');
    }

    public function authenticate(LoginRequest $request): JsonResponse
    {
        $credentials = $request->validated();

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return response()->json([
                'message' => 'ログインにしました。',
                'user' => Auth::user(),
            ]);
        }

        return response()->json([
            'email' => '認証情報が正しくありません。',
            'errors' => [
                // 'email' =>['メールアドレスまたはログインID、パスワードが正しくありません。']
                'email' =>['メールアドレスまたはパスワードが正しくありません。']
            ]
        ], 422);
    }

    /**
     */
    public function logout(Request $request)
    {
        // guard()メソッドのログアウトを呼び出す,responseインスタンスに'/login'
        Auth::guard('web')->logout();

        $request->session()->invalidate(); // セッションを無効に
        $request->session()->regenerateToken(); // CSRFトークンを再生成・リセット

        return response()->json([
            'message' => 'ログアウトしました。'
        ], 200);
    }
}
