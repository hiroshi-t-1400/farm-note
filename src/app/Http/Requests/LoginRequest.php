<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Support\Str;
use Illuminate\Http\Exceptions\HttpResponseException;
use Override;

class LoginRequest extends FormRequest
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
            //
            'email' => ['required', 'email'],
            'password' => ['required'],
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
    public function attributes(): array
    {
        return [
            'login_id' => 'ログインID'
        ];
    }
}
