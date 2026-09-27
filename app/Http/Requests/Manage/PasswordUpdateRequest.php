<?php
namespace App\Http\Requests\Manage;

use Illuminate\Foundation\Http\FormRequest;

class PasswordUpdateRequest extends FormRequest
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
            'token' => 'required',
            'password' => 'required|string|min:8|max:255|confirmed'
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
            'token.required' => 'トークンが不正です。',
            'password.required' => '入力されていません。',
            'password.string' => 'テキストではありません。',
            'password.min' => '8文字以上で入力して下さい。',
            'password.max' => '255文字以内で入力して下さい。',
            'password.confirmed' => 'パスワード（確認）と一致しません。'
        ];
    }
}
