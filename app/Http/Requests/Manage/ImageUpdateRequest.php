<?php
namespace App\Http\Requests\Manage;

use Illuminate\Foundation\Http\FormRequest;

class ImageUpdateRequest extends FormRequest
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
     * @todo 【MySQL暫定対応】MySQLバージョンを5.7以上にあげたら、DB対応後に191を255へ変更する
     * @return array
     */
    public function rules()
    {
        return [
            'input_image_alt' => 'nullable|string|max:191',
            'input_image_text' => 'nullable|string|max:191'
        ];
    }

    /**
     * エラーメッセージ定義
     * @todo 【MySQL暫定対応】MySQLバージョンを5.7以上にあげたら、DB対応後に191を255へ変更する
     * @return array
     */
    public function messages()
    {
        return [
            'input_image_alt.nullable' => '空欄ではありません。',
            'input_image_alt.string' => 'テキストではありません。',
            'input_image_alt.max' => '引用テキストは191文字以内で入力して下さい。',
            'input_image_text.nullable' => '空欄ではありません。',
            'input_image_text.string' => 'テキストではありません。',
            'input_image_text.max' => 'キャプションは191文字以内で入力して下さい。'
        ];
    }
}
