<?php
namespace App\Http\Requests\Manage;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\TagOmitStrMax;
use App\Rules\AlphaNumCustom;

class CompanyRegistRequest extends FormRequest
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
        // セッション情報取得
        $login_info = getLoginInfo($this);

        // 登録、更新時でのinput_noバリデーション変更
        if ($this->session()->get($this->upsert_session_name) === 'create') {
            $input_no_validate = [
                'required',
                'string',
                'unique:App\Models\Company,comp_no',
                new AlphaNumCustom(null, 10)
            ];
        } else {
            $input_no_validate = [
                'required',
                'string',
                new AlphaNumCustom('パラメータが不正です。管理者へお問い合わせ下さい。', 10)
            ];
        }

        //定数設定
        if (checkMasterAcnt($login_info['acnt_no'])){
            $cp_type = getAuthCompanyTypeAdmin();
        } else {
            $cp_type = getAuthCompanyTypeCustomer();
        }

        return [
            'input_no' => $input_no_validate,
            'input_name' => 'required|string|max:191',
            'input_text' => [
                'nullable',
                'string',
                new TagOmitStrMax(30720)
            ],
            'input_image_no' => 'nullable|numeric|digits_between:1,10|between:0,4294967295',
            'input_bfno' => 'nullable|numeric|digits_between:1,3|between:1,127',
            'input_indno' => 'nullable|numeric|digits_between:1,3|between:1,127',
            'input_title' => 'nullable|string|max:255',
            'input_search_tag' => 'nullable|string|max:255',
            'input_url' => 'nullable|string|url|max:255',
            'input_cptype' => "required|integer|size:{$cp_type}"
        ];
    }

    /**
     * エラーメッセージ定義
     * @todo 【MySQL暫定対応】MySQLバージョンを5.7以上にあげたら、DB対応後に191を255へ変更する
     * @return array
     */
    public function messages()
    {
        $validate_message = [
            'input_name.required' => '名称が入力されていません。',
            'input_name.string' => '名称がテキストではありません。',
            'input_name.max' => '名称は191文字以内で入力して下さい。',
            'input_text.nullable' => '詳細が空欄ではありません。',
            'input_text.string' => '詳細がテキストではありません。',
            'input_image_no.nullable' => 'パラメータが不正です。管理者へお問い合わせ下さい。',
            'input_image_no.numeric' => 'パラメータが不正です。管理者へお問い合わせ下さい。',
            'input_image_no.digits_between' => 'パラメータが不正です。管理者へお問い合わせ下さい。',
            'input_image_no.between' => 'パラメータが不正です。管理者へお問い合わせ下さい。',
            'input_bfno.nullable' => '業態が空欄ではありません。',
            'input_bfno.numeric' => '業態の選択値が不正です。',
            'input_bfno.digits_between' => '業態の選択値が不正です。',
            'input_bfno.between' => '業態の選択値が不正です。',
            'input_indno.nullable' => '業種が空欄ではありません。',
            'input_indno.numeric' => '業種の選択値が不正です。',
            'input_indno.digits_between' => '業種の選択値が不正です。',
            'input_indno.between' => '業種の選択値が不正です。',
            'input_title.nullable' => 'ページタイトルが空欄ではありません。',
            'input_title.string' => 'ページタイトルがテキストではありません。',
            'input_title.max' => 'ページタイトルは255文字以内で入力して下さい。',
            'input_search_tag.nullable' => 'タグが空欄ではありません。',
            'input_search_tag.string' => 'タグがテキストではありません。',
            'input_search_tag.max' => 'タグはカンマ込みで255文字以内で入力して下さい。',
            'input_url.nullable' => 'URLが空欄ではありません。',
            'input_url.string' => 'URLはテキストではありません。',
            'input_url.url' => 'URLが有効な書式ではありません。',
            'input_url.max' => 'URLは255文字以内で入力して下さい。',
            'input_cptype.required' => 'パラメータが不正です。管理者へお問い合わせ下さい。',
            'input_cptype.integer' => 'パラメータが不正です。管理者へお問い合わせ下さい。',
            'input_cptype.size' => 'パラメータが不正です。管理者へお問い合わせ下さい。',
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
