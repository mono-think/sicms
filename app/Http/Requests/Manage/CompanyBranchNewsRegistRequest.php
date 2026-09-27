<?php
namespace App\Http\Requests\Manage;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\TagOmitStrMax;

class CompanyBranchNewsRegistRequest extends FormRequest
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
     * @todo 【MySQL暫定対応】MySQLバージョンを5.7以上にあげたら、DB対応後に191を255へ変更する
     * @return array
     */
    public function rules()
    {
        if ($this->session()->get($this->upsert_session_name) === 'create') {
            return [
                'input_name' => 'required|string|max:191',
                'input_text' => [
                    'required',
                    'string',
                    new TagOmitStrMax(30720)
                ]
            ];
        } else {
            return [
                'input_no' => 'required|numeric|digits_between:1,10|between:1,4294967295',
                'input_name' => 'required|string|max:191',
                'input_text' => [
                    'required',
                    'string',
                    new TagOmitStrMax(30720)
                ]
            ];
        }
    }

    /**
     * エラーメッセージ定義
     * @todo 【MySQL暫定対応】MySQLバージョンを5.7以上にあげたら、DB対応後に191を255へ変更する
     * @return array
     */
    public function messages()
    {
        return [
            'input_no.required' => 'パラメータが不正です。管理者へお問い合わせ下さい。',
            'input_no.numeric' => 'パラメータが不正です。管理者へお問い合わせ下さい。',
            'input_no.digits_between' => 'パラメータが不正です。管理者へお問い合わせ下さい。',
            'input_no.between' => 'パラメータが不正です。管理者へお問い合わせ下さい。',
            'input_name.required' => '名称が入力されていません。',
            'input_name.string' => '名称がテキストではありません。',
            'input_name.max' => '名称は191文字以内で入力して下さい。',
            'input_text.required' => '詳細が入力されていません。',
            'input_text.string' => '詳細がテキストではありません。'
        ];
    }
}