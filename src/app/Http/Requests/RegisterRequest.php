<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Override;
use Illuminate\Support\Str;


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
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required'],
            'password_confirmed' => ['required', 'same:password'],
            'login_id' => ['required', 'string', 'max:30', 'unique:users,login_id'],
            'name' => ['required', 'string', 'max:20']
        ];
    }

    #[Override]
    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors()->toArray();

        $camelErrors = [];

        foreach ($errors as $key => $messages) {
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
            'password_confirmed' => 'パスワード確認欄が一致しません',
        ];
    }

    #[Override]
    public function attributes(): array
    {
        return [
            'login_id' => 'ログインID'
        ];
    }
}
