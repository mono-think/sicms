<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Cookie;
use App\Rules\Captcha;

class ContactRegistFrontRequest extends FormRequest
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
            'contact_name' => 'required|string|max:32',
            'contact_mail' => 'required|string|max:255|email|confirmed',
            'contact_mail_confirmation' => 'required|string|max:255|email',
            'contact_text' => 'required|string|max:255',
            'captcha_auth' => [
                'required',
                'string',
                new Captcha(Cookie::get('CpNum'))
            ]
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
            'contact_name.required' => '入力されていません。',
            'contact_name.string' => 'テキストではありません。',
            'contact_name.max' => '32文字以内で入力して下さい。',
            'contact_mail.required' => '入力されていません。',
            'contact_mail.string' => 'テキストではありません。',
            'contact_mail.max' => '255文字以内で入力して下さい。',
            'contact_mail.email' => '有効なメールアドレスを入力して下さい。',
            'contact_mail.confirmed' => '確認用メールアドレスと一致しません。',
            'contact_mail_confirmation.required' => '入力されていません。',
            'contact_mail_confirmation.string' => 'テキストではありません。',
            'contact_mail_confirmation.max' => '255文字以内で入力して下さい。',
            'contact_mail_confirmation.email' => '有効なメールアドレスを入力して下さい。',
            'contact_text.required' => '入力されていません。',
            'contact_text.string' => 'テキストではありません。',
            'contact_text.max' => '255文字以内で入力して下さい。',
            'captcha_auth.required' => '入力されていません。',
            'captcha_auth.string' => 'テキストではありません。',
        ];
    }
}