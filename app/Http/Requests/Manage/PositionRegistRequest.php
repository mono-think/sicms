<?php
namespace App\Http\Requests\Manage;

use Illuminate\Foundation\Http\FormRequest;

class PositionRegistRequest extends FormRequest
{

    protected $upsert_session_name;

    /**
     * コンストラクタ
     */
    public function __construct()
    {
        $this->upsert_session_name = config('consts.session_info.upsert_auth');
    }

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
        $validate_pattern['input_name'] = 'required|string|max:32';
        if ($this->session()->get($this->upsert_session_name) !== 'create') {
            $validate_pattern['input_no'] = 'required|numeric';
        }

        return $validate_pattern;
    }

    /**
     * エラーメッセージ定義
     *
     * @return array
     */
    public function messages()
    {
        return [
            'input_no.required' => 'パラメータが不正です。管理者へお問い合わせ下さい。',
            'input_no.numeric' => 'パラメータが不正です。管理者へお問い合わせ下さい。',
            'input_name.required' => '名称が入力されていません。',
            'input_name.string' => '名称がテキストではありません。',
            'input_name.max' => '名称は32文字以内で入力して下さい。'
        ];
    }
}