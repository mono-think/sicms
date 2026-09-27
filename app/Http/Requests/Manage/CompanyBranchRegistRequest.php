<?php
namespace App\Http\Requests\Manage;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Rules\AddressNumber;
use App\Rules\AlphaNumCustom;

class CompanyBranchRegistRequest extends FormRequest
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
                Rule::unique('App\Models\CompanyBranch', 'comp_branch_no')->where('comp_no', $login_info['company_no']),
                new AlphaNumCustom(null, 10)
            ];
        } else {
            $input_no_validate = [
                'required',
                'string',
                new AlphaNumCustom('パラメータが不正です。管理者へお問い合わせ下さい。', 10)
            ];
        }

        // 定数設定
        $customer_array = getConstsType('customer'); // お客様種別
        $customer_min = array_key_first($customer_array); // お客様種別最小値
        $customer_max = array_key_last($customer_array); // お客様種別最大値
        $customer_length = strlen($customer_max); // お客様種別桁数

        return [
            'input_no' => $input_no_validate,
            'input_name' => 'required|string|max:191',
            'input_zip' => [
                'nullable',
                'string',
                new AddressNumber()
            ],
            'input_pref_cd' => "nullable|numeric|digits_between:1,2|between:1,47",
            'input_city' => 'nullable|string|max:20',
            'input_town' => 'nullable|string|max:20',
            'input_address' => 'nullable|string|max:20',
            'input_order' => 'required|numeric|digits_between:1,3|between:1,127',
            'input_suppoter' => "required|numeric|digits:{$customer_length}|between:{$customer_min},{$customer_max}"
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
            'input_order.required' => '並び順が入力されていません。',
            'input_order.numeric' => '並び順が数値ではありません。',
            'input_order.digits_between' => '並び順は3桁以内で入力して下さい。',
            'input_order.between' => '並び順は1～127の範囲で入力して下さい。',
            'input_suppoter.required' => '協賛の選択値が不正です。',
            'input_suppoter.numeric' => '協賛の選択値が不正です。',
            'input_suppoter.digits' => '協賛の選択値が不正です。',
            'input_suppoter.between' => '協賛の選択値が不正です。'
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
