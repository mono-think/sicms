<?php
namespace App\Http\Requests\Manage;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\AlphaNumCustom;

class ProductsCategoryRegistRequest extends FormRequest
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
        // 登録、更新時でのinput_noバリデーション変更
        if ($this->session()->get($this->upsert_session_name) === 'create') {
            $input_no_validate = [
                'required',
                'string',
                'unique:App\Models\ProductsCategory,products_category_no',
                new AlphaNumCustom(null, 10)
            ];
        } else {
            $input_no_validate = [
                'required',
                'string',
                new AlphaNumCustom('パラメータが不正です。管理者へお問い合わせ下さい。', 10)
            ];
        }

        return [
            'input_no' => $input_no_validate,
            'input_name' => 'required|string|max:32'
        ];
    }

    /**
     * エラーメッセージ定義
     *
     * @return array
     */
    public function messages()
    {
        $validate_message = [
            'input_name.required' => '名称が入力されていません。',
            'input_name.string' => '名称がテキストではありません。',
            'input_name.max' => '名称は32文字以内で入力して下さい。'
        ];

        if ($this->session()->get($this->upsert_session_name) === 'create') {
            $validate_message['input_no.required'] = '番号が入力されていません。';
            $validate_message['input_no.string'] = '番号がテキストではありません。';
            $validate_message['input_no.unique'] = '番号は既に使われています。';
        } else {
            $validate_message['input_no.required'] = 'パラメータが不正です。管理者へお問い合わせ下さい。';
            $validate_message['input_no.string'] = 'パラメータが不正です。管理者へお問い合わせ下さい。';
        }

        return $validate_message;
    }
}