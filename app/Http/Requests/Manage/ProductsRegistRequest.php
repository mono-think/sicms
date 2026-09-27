<?php
namespace App\Http\Requests\Manage;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Rules\AlphaNumCustom;

class ProductsRegistRequest extends FormRequest
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
        // セッション情報取得
        $login_info = getLoginInfo($this);

        // 登録、更新時でのinput_noバリデーション変更
        if ($this->session()->get($this->upsert_session_name) === 'create') {
            $input_no_validate = [
                'required',
                'string',
                Rule::unique('App\Models\Products', 'products_no')->where('comp_no', $login_info['company_no']),
                new AlphaNumCustom(null, 10)
            ];
        } else {
            $input_no_validate = [
                'required',
                'string',
                new AlphaNumCustom('パラメータが不正です。管理者へお問い合わせ下さい。', 10)
            ];
        }

        // カテゴリバリデーション
        $input_ctg_no_validate = [
            'required',
            'string',
            new AlphaNumCustom('カテゴリの選択値が不正です。', 10)
        ];

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

        // 担当バリデーション
        $input_acnt_no_validate = [
            'nullable',
            'string',
            new AlphaNumCustom('パラメータが不正です。管理者へお問い合わせ下さい。', 10)
        ];
        
        //定数設定
        $customer_array = getConstsType('customer');
        $customer_min = array_key_first($customer_array); //お客様種別最小値
        $customer_max = array_key_last($customer_array); //お客様種別最大値
        $customer_length = strlen($customer_max); //お客様種別桁数
        
        $service_array = getConstsType('service');
        $service_min = array_key_first($service_array); //サービス種別最小値
        $service_max = array_key_last($service_array); //サービス種別最大値
        $service_length = strlen($service_max); //サービス種別桁数
        
        return [
            'input_ct' => "required|numeric|digits_between:1,{$customer_length}|between:{$customer_min},{$customer_max}",
            'input_st' => "required|numeric|digits_between:1,{$service_length}|between:{$service_min},{$service_max}",
            'input_ctg_no' => $input_ctg_no_validate,
            'input_no' => $input_no_validate,
            'input_name' => 'required|string|max:64',
            'input_cpb_no' => $input_cpb_no_validate,
            'input_cpd_no' => $input_cpd_no_validate,
            'input_acnt_no' => $input_acnt_no_validate,
            'input_release_start' => 'required|date',
            'input_release_end' => 'required|date'
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
            'input_ct.required' => '対象種別が選択されていません。',
            'input_ct.numeric' => '対象種別の選択値が不正です。',
            'input_ct.digits_between' => '対象種別の選択値が不正です。',
            'input_ct.between' => '対象種別の選択値が不正です。',
            'input_st.required' => 'サービス種別が選択されていません。',
            'input_st.numeric' => 'サービス種別の選択値が不正です。',
            'input_st.digits_between' => 'サービス種別の選択値が不正です。',
            'input_st.between' => 'サービス種別の選択値が不正です。',
            'input_ctg_no.required' => 'カテゴリが選択されていません。',
            'input_ctg_no.string' => 'カテゴリが選択されていません。',
            'input_name.required' => '名称が入力されていません。',
            'input_name.string' => '名称がテキストではありません。',
            'input_name.max' => '名称は64文字以内で入力して下さい。',
            'input_cpb_no.nullable' => 'パラメータが不正です。管理者へお問い合わせ下さい。',
            'input_cpb_no.string' => 'パラメータが不正です。管理者へお問い合わせ下さい。',
            'input_cpd_no.nullable' => 'パラメータが不正です。管理者へお問い合わせ下さい。',
            'input_cpd_no.string' => 'パラメータが不正です。管理者へお問い合わせ下さい。',
            'input_acnt_no.nullable' => 'パラメータが不正です。管理者へお問い合わせ下さい。',
            'input_acnt_no.string' => 'パラメータが不正です。管理者へお問い合わせ下さい。',
            'input_release_start.required' => '掲載開始日が入力されていません。',
            'input_release_start.date' => '掲載開始日の書式が正しくありません。',
            'input_release_end.required' => '掲載終了日が入力されていません。',
            'input_release_end.date' => '掲載終了日の書式が正しくありません。'
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