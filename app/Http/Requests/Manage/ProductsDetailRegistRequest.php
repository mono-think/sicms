<?php
namespace App\Http\Requests\Manage;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Rules\AlphaNumCustom;
use App\Rules\TagOmitStrMax;

class ProductsDetailRegistRequest extends FormRequest
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
                Rule::unique('App\Models\ProductsDetail', 'products_detail_no')->where('comp_no', $login_info['company_no'])->where('products_no', $login_info['products_no']),
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
            'input_name' => 'required|string|max:191',
            'input_description' => 'required|string|max:191',
            'input_image_no' => 'nullable|numeric|digits_between:1,10|between:0,4294967295',
            'input_text' => [
                'required',
                'string',
                new TagOmitStrMax(30720)
            ],
            'input_search_tag' => 'nullable|string|max:191',
            'input_release_start' => 'required|date',
            'input_release_end' => 'required|date'
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
            'input_description.required' => '紹介が入力されていません。',
            'input_description.string' => '紹介がテキストではありません。',
            'input_description.max' => '紹介は191文字以内で入力して下さい。',
            'input_image_no.nullable' => 'パラメータが不正です。管理者へお問い合わせ下さい。',
            'input_image_no.numeric' => 'パラメータが不正です。管理者へお問い合わせ下さい。',
            'input_image_no.digits_between' => 'パラメータが不正です。管理者へお問い合わせ下さい。',
            'input_image_no.between' => 'パラメータが不正です。管理者へお問い合わせ下さい。',
            'input_text.required' => '詳細が入力されていません。',
            'input_text.string' => '詳細がテキストではありません。',
            'input_search_tag.nullable' => 'タグが空欄ではありません。',
            'input_search_tag.string' => 'タグがテキストではありません。',
            'input_search_tag.max' => 'タグはカンマ込みで191文字以内で入力して下さい。',
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