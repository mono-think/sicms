<?php
namespace App\Http\Requests\Manage;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Rules\TelNumber;
use App\Rules\AlphaNumCustom;
use App\Rules\MultiByte;
use App\Rules\SnsUrl;

class CompanyDepartmentRegistRequest extends FormRequest
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
                Rule::unique('App\Models\CompanyDepartment', 'comp_department_no')->where('comp_no', $login_info['company_no'])->where('comp_branch_no', $login_info['company_branch_no']),
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
        $sns_array = getConstsType('sns');
        $sns_min = array_key_first($sns_array); // SNS最小値
        $sns_max = array_key_last($sns_array); // SNS最大値
        $sns_length = strlen($sns_max); // SNS桁数

        return [
            'input_no' => $input_no_validate,
            'input_name' => 'required|string|max:191',
            'input_tel' => [
                'required',
                'string',
                new TelNumber()
            ],
            'input_fax' => [
                'nullable',
                'string',
                new TelNumber()
            ],
            'input_email' => [
                'nullable',
                'string',
                'max:191',
                'email',
                new MultiByte('メールアドレスに全角文字は使用できません。')
            ],
            'input_order' => 'required|numeric|digits_between:1,3|between:1,127',
            'input_public' => 'nullable|numeric|digits:1|max:1',
            'input_sns' => [new SnsUrl($this->input('input_snsno'))],
            'input_snsno.*' => "nullable|numeric|digits_between:1,{$sns_length}|between:{$sns_min},{$sns_max}",
            'input_sns.*' => 'nullable|string|url|max:255'
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
            'input_tel.required' => '電話番号が入力されていません。',
            'input_tel.string' => '電話番号がテキストではありません。',
            'input_fax.nullable' => 'FAX番号が空欄ではありません。',
            'input_fax.string' => 'FAX番号がテキストではありません。',
            'input_email.nullable' => 'メールアドレスが空欄ではありません。',
            'input_email.string' => 'メールアドレスがテキストではありません。',
            'input_email.max' => 'メールアドレスは191文字以内で入力して下さい。',
            'input_email.email' => 'メールアドレスの書式が正しくありません。',
            'input_order.required' => '並び順が入力されていません。',
            'input_order.numeric' => '並び順が数値ではありません。',
            'input_order.digits_between' => '並び順は3桁以内で入力して下さい。',
            'input_order.between' => '並び順は1～127の範囲で入力して下さい。',
            'input_public.nullable' => '公開フラグの選択値が不正です。',
            'input_public.numeric' => '公開フラグの選択値が不正です。',
            'input_public.digits' => '公開フラグの選択値が不正です。',
            'input_public.max' => '公開フラグの選択値が不正です。',
            'input_snsno.*.nullable' => 'SNS種別の選択値が不正な箇所があります。',
            'input_snsno.*.numeric' => 'SNS種別の選択値が不正な箇所があります。',
            'input_snsno.*.digits_between' => 'SNS種別の選択値が不正な箇所があります。',
            'input_snsno.*.between' => 'SNS種別の選択値が不正な箇所があります。',
            'input_sns.*.nullable' => 'SNS入力欄で空欄でない箇所があります。',
            'input_sns.*.string' => 'SNS入力欄でテキストでない箇所があります。',
            'input_sns.*.url' => 'SNS入力欄で無効なURLの箇所があります。',
            'input_sns.*.max' => 'SNS入力欄で255文字を超えている箇所があります。'
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