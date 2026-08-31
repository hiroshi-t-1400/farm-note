<?php

namespace App\Http\Requests\Admin\UserChange;

use App\Models\Admin\UserChange\UserChangeApplication;
use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Override;

class UpdateRequest extends FormRequest
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
        // route()でRequestのrouteパラメータを参照
        // (User $targetUser)としてルートモデルバインディングされているためuserモデルが取得される
        $targetUser = $this->route('targetUser');

        return [
            'name' => ['required', 'string', 'max:255'],

            'login_id' => [
                'required',
                'string',
                'min:4',
                'max:30',
                'regex:/^[a-zA-Z][a-zA-Z0-9_.-]+$/',
                Rule::unique('users', 'login_id')
                    ->ignore($targetUser),

                Rule::unique(
                    'user_change_applications',
                    'payload->login_id',
                )
                    ->where(function (Builder $query) {
                        $query->where(
                            'status',
                            UserChangeApplication::STATUS_PENDING,
                        );
                }),
            ],

            'email' => [
                'required',
                'string',
                // 'email:frc,dns',  // 本番用
                'email', // 開発用 @example.orgの許容
                'max:255',
                Rule::unique('users', 'email')
                    ->ignore($targetUser),

                Rule::unique(
                    'user_change_applications',
                    'payload->email',
                )
                    ->where(function (Builder $query) {
                        $query->where(
                            'status',
                            UserChangeApplication::STATUS_PENDING,
                        );
                }),
            ],

            // 更新時はnullを許可、nullであればレコードカラム変更をしない
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
                'exists:roles,name'
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
