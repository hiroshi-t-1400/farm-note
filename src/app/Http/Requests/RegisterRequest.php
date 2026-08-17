<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Override;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            //
            'name' => ['required', 'string', 'max:2'],

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
                'required',
                'string',
                'confirmed',
                Password::min(10)
                    ->uncompromised(3),
                'regex:/^[a-zA-Z0-9!@#$%&*\-_.]+$/',
            ],

            'password_confirmation' => [
                'required_with:password',
                'string',
            ],

            // 閲覧ソート用に読み仮名を組み込むときに
            // 'kana' => [
            //     'nullable',
            //     'string',
            //     'regex:/^[ァ-ヶー]+$/u', // 全角カタカナのみ許可
            //     'max:255',
            // ],
        ];
    }

    #[Override]
    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors()->toArray();

        $camelErrors = [];

        foreach ($errors as $key => $messages) {
            if ($key == 'name') $key = 'username';
            $camelKey = Str::camel($key);
            $camelErrors[$camelKey] = $messages;
        }

        throw new HttpResponseException(
            response()->json([
                'message' => '無効な値が入力されています。',
                'errors' => $camelErrors,
            ], 422)
        );
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
