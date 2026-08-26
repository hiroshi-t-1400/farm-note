<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Override;

class UpdateUserChangeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],

            'login_id' => [
                'required',
                'string',
                'min:4',
                'max:30',
                'regex:/^[a-zA-Z][a-zA-Z0-9_.-]+$/',
                Rule::unique('users', 'login_id')
                ->ignore($this->user), // 更新時自分を判定から除外
            ],

            'email' => [
                'required',
                'string',
                // 'email:frc,dns',  // 本番用
                'email', // 開発用 @example.orgの許容
                'max:255',
                Rule::unique('users', 'email')
                ->ignore($this->user),
            ],

            'password' => [
                'nullable',
                'string',
                Password::min(10)
                ->uncompromised(3),
                'regex:/^[a-zA-Z0-9!@#$%&*\-_.]+$/',
            ],

            'role' => [
                'required',
                'string',
            ],
        ];
    }

    #[Override]
    function messages()
    {
        return [
            'password.uncompromised' => '非常に漏洩しやすい:attributeが入力されています。より複雑なパスワードを入力してください。（文字種を増やす。同じ字を連続しない。等）',
            'password.regex' => ':attributeに、使用できない記号等が含まれています。',
            'password.confirmed' => '確認用パスワードと一致していません。'
        ];
    }


    #[Override]
    public function attributes(): array
    {
        return [
            'name' => 'お名前',
            'login_id' => 'ログインID',
            'email' => 'メールアドレス',
            'password' => 'パスワード',
            'password_confirmation' => '確認用パスワード'
        ];
    }
}
