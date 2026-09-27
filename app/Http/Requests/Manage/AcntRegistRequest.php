<?php
namespace App\Http\Requests\Manage;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Rules\AddressNumber;
use App\Rules\AlphaNumCustom;
use App\Rules\TelNumber;
use App\Rules\MultiByte;

class AcntRegistRequest extends FormRequest
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

        // 登録、更新時でのinput_no、input_idのバリデーション変更
        if ($this->session()->get($this->upsert_session_name) === 'create') {
            $input_no_validate = [
                'required',
                'string',
                Rule::unique('App\Models\Acnt', 'acnt_no')->where('comp_no', $login_info['company_no']),
                new AlphaNumCustom(null, 10)
            ];
            $input_id_validate = [
                'required',
                'string',
                'unique:App\Models\Acnt,acnt_id',
                new AlphaNumCustom('ログインIDは191文字以下の半角英数字で入力して下さい。', 191)
            ];
        } else {
            $input_no_validate = [
                'required',
                'string',
                new AlphaNumCustom('パラメータが不正です。管理者へお問い合わせ下さい。', 10)
            ];
            $input_id_validate = [
                'required',
                'string',
                new AlphaNumCustom('パラメータが不正です。管理者へお問い合わせ下さい。', 191)
            ];
        }

        // 支店バリデーション
        $input_cpb_no_validate = [
            'nullable',
            'string',
            new AlphaNumCustom('パラメータが不正です。管理者へお問い合わせ下さい。', 10)
        ];

        // 部署バリデーション
        $input_cpd_no_validate = [
            'nullable',
            'string',
            new AlphaNumCustom('パラメータが不正です。管理者へお問い合わせ下さい。', 10)
        ];

        // 定数設定
        $auth_array = getConstsType('auth');
        $auth_min = array_key_first($auth_array); // 権限最小値
        $auth_max = array_key_last($auth_array); // 権限最大値
        $auth_length = strlen($auth_max); // 権限桁数

        return [
            'input_cpb_no' => $input_cpb_no_validate,
            'input_cpd_no' => $input_cpd_no_validate,
            'input_no' => $input_no_validate,
            'input_sei' => 'required|string|max:10',
            'input_mei' => 'required|string|max:10',
            'input_kana_sei' => 'required|string|max:50',
            'input_kana_mei' => 'required|string|max:50',
            'input_zip' => [
                'nullable',
                'string',
                new AddressNumber()
            ],
            'input_pref_cd' => "nullable|numeric|digits_between:1,2|between:1,47",
            'input_city' => 'nullable|string|max:20',
            'input_town' => 'nullable|string|max:20',
            'input_address' => 'nullable|string|max:20',
            'input_tel' => [
                'nullable',
                'string',
                new TelNumber('電話番号１は正しい書式で入力して下さい。')
            ],
            'input_tel1' => [
                'nullable',
                'string',
                new TelNumber('電話番号２は正しい書式で入力して下さい。')
            ],
            'input_email' => [
                'required',
                'string',
                'email',
                'max:191',
                new MultiByte('メールアドレス１に全角文字は使用できません。')
            ],
            'input_email1' => [
                'nullable',
                'string',
                'email',
                'max:191',
                new MultiByte('メールアドレス２に全角文字は使用できません。')
            ],
            'input_posno' => 'required|numeric|digits_between:1,3|between:1,127',
            'input_posother_name' => 'nullable|string|max:32',
            'input_authno' => "required|numeric|digits_between:1,{$auth_length}|between:{$auth_min},{$auth_max}",
            'input_authother_name' => 'nullable|string|max:32',
            'input_id' => $input_id_validate,
            'input_pass' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                new AlphaNumCustom('パスワードは255文字以下の半角英数字で入力して下さい。', 255)
            ],
            'input_pass_confirmation' => [
                'required',
                'string',
                'min:8',
                new AlphaNumCustom('（確認）は255文字以下の半角英数字で入力して下さい。', 255)
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
        $validate_message = [
            'input_cpb_no.nullable' => 'パラメータが不正です。管理者へお問い合わせ下さい。',
            'input_cpb_no.string' => 'パラメータが不正です。管理者へお問い合わせ下さい。',
            'input_cpd_no.nullable' => 'パラメータが不正です。管理者へお問い合わせ下さい。',
            'input_cpd_no.string' => 'パラメータが不正です。管理者へお問い合わせ下さい。',
            'input_sei.required' => '姓が入力されていません。',
            'input_sei.string' => '姓がテキストではありません。',
            'input_sei.max' => '姓は10文字以内で入力して下さい。',
            'input_mei.required' => '名が入力されていません。',
            'input_mei.string' => '名がテキストではありません。',
            'input_mei.max' => '名は10文字以内で入力して下さい。',
            'input_kana_sei.required' => '性（かな）が入力されていません。',
            'input_kana_sei.string' => '性（かな）がテキストではありません。',
            'input_kana_sei.max' => '性（かな）は50文字以内で入力して下さい。',
            'input_kana_mei.required' => '名（かな）が入力されていません。',
            'input_kana_mei.string' => '名（かな）がテキストではありません。',
            'input_kana_mei.max' => '名（かな）は50文字以内で入力して下さい。',
            'input_zip.nullable' => '郵便番号が空欄ではありません。',
            'input_zip.string' => '郵便番号がテキストではありません。',
            'input_pref_cd.nullable' => '住所 - 都道府県の選択値が不正です。',
            'input_pref_cd.numeric' => '住所 - 都道府県の選択値が不正です。',
            'input_pref_cd.digits_between' => '住所 - 都道府県の選択値が不正です。',
            'input_pref_cd.between' => '住所 - 都道府県の選択値が不正です。',
            'input_city.nullable' => '住所 - 市区町村が空欄ではありません。',
            'input_city.string' => '住所 - 市区町村がテキストではありません。',
            'input_city.max' => '住所 - 市区町村は20文字以内で入力して下さい。',
            'input_town.nullable' => '住所 - 町・丁目・番地が空欄ではありません。',
            'input_town.string' => '住所 - 町・丁目・番地がテキストではありません。',
            'input_town.max' => '住所 - 町・丁目・番地は20文字以内で入力して下さい。',
            'input_address.nullable' => '住所 - その他が空欄ではありません。',
            'input_address.string' => '住所 - その他がテキストではありません。',
            'input_address.max' => '住所 - その他は20文字以内で入力して下さい。',
            'input_tel.nullable' => '電話番号１が空欄ではありません。',
            'input_tel.string' => '電話番号１がテキストではありません。',
            'input_tel1.nullable' => '電話番号２が空欄ではありません。',
            'input_tel1.string' => '電話番号２がテキストではありません。',
            'input_email.required' => 'メールアドレス１が入力されていません。',
            'input_email.string' => 'メールアドレス１がテキストではありません。',
            'input_email.max' => 'メールアドレス１は191文字以内で入力して下さい。',
            'input_email.email' => 'メールアドレス１の書式が正しくありません。',
            'input_email1.nullable' => 'メールアドレス２が空欄ではありません。',
            'input_email1.string' => 'メールアドレス２がテキストではありません。',
            'input_email1.max' => 'メールアドレス２は191文字以内で入力して下さい。',
            'input_email1.email' => 'メールアドレス２の書式が正しくありません。',
            'input_posno.required' => '役職が選択されていません。',
            'input_posno.numeric' => '役職の選択値が不正です。',
            'input_posno.digits_between' => '役職の選択値が不正です。',
            'input_posno.between' => '役職の選択値が不正です。',
            'input_posother_name.nullable' => '役職 - 別名が空欄ではありません。',
            'input_posother_name.string' => '役職 - 別名がテキストではありません。',
            'input_posother_name.max' => '役職 - 別名は32文字以内で入力して下さい。',
            'input_authno.required' => '権限が選択されていません。',
            'input_authno.numeric' => '権限の選択値が不正です。',
            'input_authno.digits_between' => '権限の選択値が不正です。',
            'input_authno.between' => '権限の選択値が不正です。',
            'input_authother_name.nullable' => '権限 - 別名が空欄ではありません。',
            'input_authother_name.string' => '権限 - 別名がテキストではありません。',
            'input_authother_name.max' => '権限 - 別名は32文字以内で入力して下さい。',
            'input_pass.required' => 'パスワードが入力されていません。',
            'input_pass.string' => 'パスワードがテキストではありません。',
            'input_pass.min' => 'パスワードは8文字以上の半角英数字で入力して下さい。',
            'input_pass.confirmed' => 'パスワードは（確認）と一致しません。同じ値を入力して下さい。',
            'input_pass_confirmation.required' => '（確認）が入力されていません。',
            'input_pass_confirmation.string' => '（確認）はテキストではありません。',
            'input_pass_confirmation.min' => '（確認）は8文字以上の半角英数字で入力して下さい。'
        ];

        if ($this->session()->get($this->upsert_session_name) === 'create') {
            $validate_message['input_no.required'] = '番号が入力されていません。';
            $validate_message['input_no.string'] = '番号がテキストではありません。';
            $validate_message['input_no.unique'] = '番号は既に使われています。';
            $validate_message['input_id.required'] = 'ログインIDが入力されていません。';
            $validate_message['input_id.string'] = 'ログインIDがテキストではありません。';
            $validate_message['input_id.unique'] = 'ログインIDは既に使われています。';
        } else {
            $validate_message['input_no.required'] = 'パラメータが不正です。管理者へお問い合わせ下さい。';
            $validate_message['input_no.string'] = 'パラメータが不正です。管理者へお問い合わせ下さい。';
            $validate_message['input_id.required'] = 'パラメータが不正です。管理者へお問い合わせ下さい。';
            $validate_message['input_id.string'] = 'パラメータが不正です。管理者へお問い合わせ下さい。';
        }

        return $validate_message;
    }
}
