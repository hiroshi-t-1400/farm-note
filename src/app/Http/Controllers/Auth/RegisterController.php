<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    //

    public function show()
    {
        return response()->view('auth.register');
    }

    public function create(RegisterRequest $request): JsonResponse
    {

        $validated = $request->validated();

        $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'login_id' => $validated['login_id'],
                'password' => Hash::make($validated['password'])
            ]);

        // 1,メール認証発火
        event(new Registered($user));

        Auth::login($user);

        // メール送信がされているものとしてフラグをstatusに持たせる
        return response()->json([
            'status' => 'verification-link-sent',
            'message' => '登録されたメールを確認して、アカウント登録を完了してください。',
            'user' => $user,
        ]);
    }
}
