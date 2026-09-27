<?php
namespace App\Http\Requests\Manage;

use Illuminate\Foundation\Http\FormRequest;

class TwoStepVerificationRequest extends FormRequest
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
            'verify_id' => 'required|numeric|digits:6'
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
            'verify_id.required' => '入力されていません。',
            'verify_id.numeric' => '数値6桁で入力して下さい。',
            'verify_id.digits' => '数値6桁で入力して下さい。'
        ];
    }
}
