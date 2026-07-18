<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Override;

class StoreWorkLogRequest extends FormRequest
{
    // protected $errorBag = 'workCreation';

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
            'title' => ['required', 'string', 'max:50'],
            'content' => ['nullable', 'string', 'max:200'],
            'updated_by' => ['nullable', 'date'],

            // 使用した資材がある場合のみmaterial_on_workを受け取るので条件付きでバリデーションを行う
            'material_on_work' => ['sometimes', 'required', 'array'],

            'material_logs.*.material_id' => ['required_with:material_on_work', 'numeric', 'exists:materials,id'],
            'material_logs.*.quantity' => ['required_with:material_on_work', 'string', 'max:10000'],
            'material_logs.*.dilution_rate' => ['present_with:material_on_work', 'nullable', 'numeric', 'max:10000'],
            'material_logs.*.material_amount' => ['present_with:material_on_work', 'nullable', 'string', 'max:10000'],
        ];
    }

    #[Override]
    public function messages(): array
    {
        return [
            'crop_season_id.required' => ':attributeを選択してください。',
            'created_by.required' => 'attributeを選択してください。',
            'performed_by.required' => ':attributeを選択してください。',
            'material_logs.dilution_rate.numeric' => '希釈倍率は『倍』を付けず、倍率の数値だけを半角で入力してください。',
        ];
    }

    #[Override]
    public function attributes(): array
    {
        return [
            'crop_season_id' => '対象の作物',
            'created_by' => '記入者',
            'performed_by' => '作業実施者',
            'work_date' => '作業日',
            'status' => '作業の完了状況（完了or予定）',
            'title' => '作業名（ひとこと）',
            'content' => '作業詳細',
            'updated_by' => '記録編集者',
            'material_logs' => '使用した資材',
            'material_logs.*.material_id' => '資材のマスターID',
            'material_logs.*.quantity' => '資材の使用量',
            'material_logs.*.dilution_rate' => '農薬・肥料の希釈倍率',
            'material_logs.*.material_amount' => '原液の分量',
        ];
    }
}
