<?php
namespace App\Http\Requests\Manage;

use Illuminate\Foundation\Http\FormRequest;

class CommonMbDeleteRequest extends FormRequest
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
        return [
            'select_no' => 'required|array',
            'select_no.*' => 'required|numeric',
            'select_rno' => 'required|array',
            'select_rno.*' => 'required|numeric'
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
            'select_no.*.numeric' => '削除項目の選択値が不正です。',
            'select_rno.required' => '削除項目が選択されていません。',
            'select_rno.array' => '削除項目が所定のフォーマットではありません。',
            'select_rno.*.required' => '削除項目の選択値が不正です。',
            'select_rno.*.numeric' => '削除項目の選択値が不正です。'
        ];
    }
}
