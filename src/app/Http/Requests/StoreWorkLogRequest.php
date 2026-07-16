<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreWorkLogRequest extends FormRequest
{
    protected $errorBag = 'workCreation';

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
            'crop_season_id' => ['required', 'numeric', 'exists:crop_seasons,id'],
            'created_by' => ['required', 'numeric', 'exists:users,id'],
            'performed_by' => ['required', 'numeric', 'exists:users,id'],
            'work_date' => ['required', 'date'],
            'status' => ['sometimes', 'in:plan,completed,draft'],
            // 'status' => ['required', 'boolean'],
            // 'status' => ['nullable', 'boolean'],
            'title' => ['required', 'string', 'max:50'],
            'content' => ['nullable', 'string', 'max:200'],
            'updated_by' => ['nullable', 'date'],

            // 使用した資材がある場合のみmaterial_on_workを受け取るので条件付きでバリデーションを行う
            'material_on_work' => ['sometimes','required', 'array'],

            'material_on_work.*.material_id' => ['required_with:material_on_work', 'numeric', 'exists:materials,id'],
            'material_on_work.*.quantity' => ['required_with:material_on_work', 'string', 'max:10'],
            'material_on_work.*.dilution_rate' => ['present_with:material_on_work', 'nullable', 'numeric', 'max:10000'],
            'material_on_work.*.material_amount' => ['present_with:material_on_work', 'nullable', 'string', 'max:10000'],
        ];
    }
}
