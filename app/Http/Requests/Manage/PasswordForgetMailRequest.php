<?php
namespace App\Http\Requests\Manage;

use Illuminate\Foundation\Http\FormRequest;

class PasswordForgetMailRequest extends FormRequest
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
            'email' => 'required|string|max:255|email'
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
            'email.required' => '入力されていません。',
            'email.string' => 'テキストではありません。',
            'email.max' => '255文字以内で入力して下さい。',
            'email.email' => '有効なメールアドレスを入力して下さい。'
        ];
    }
}
