<?php
namespace App\Http\Requests\Manage;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\AlphaNumCustom;

class CommonDeleteAlphaNumRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $select_no_validate = [
            'required',
            new AlphaNumCustom('削除項目の選択値が不正です。',10)
        ];

        return [
            'select_no' => 'required|array',
            'select_no.*' => $select_no_validate
        ];
    }

    /**
     * エラーメッセージ定義
     *
     * @return array
     */
    public function messages()
    {
        return [
            'select_no.required' => '削除項目が選択されていません。',
            'select_no.array' => '削除項目が所定のフォーマットではありません。',
            'select_no.*.required' => '削除項目の選択値が不正です。',
        ];
    }
}
