<?php
namespace App\Http\Requests\Manage;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\TagOmitStrMax;

class EventRegistRequest extends FormRequest
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
        // 定数設定
        $weather_array = getConstsType('weather');
        $weather_min = array_key_first($weather_array); // 天気種別最小値
        $weather_max = array_key_last($weather_array); // 天気種別最大値
        $weather_length = strlen($weather_max); // 天気種別桁数

        $validate_pattern['input_name'] = 'required|string|max:191';
        $validate_pattern['input_description'] = 'required|string|max:191';
        $validate_pattern['input_date'] = 'required|date';
        $validate_pattern['input_stage'] = 'required|string|max:64';
        $validate_pattern['input_weather'] = "required|numeric|digits_between:1,{$weather_length}|between:{$weather_min},{$weather_max}";
        $validate_pattern['input_organizer'] = 'required|string|max:64';
        $validate_pattern['input_text'] = [
            'required',
            'string',
            new TagOmitStrMax(30720)
        ];
        $validate_pattern['input_image_no'] = 'nullable|numeric|digits_between:1,10|between:0,4294967295';
        $validate_pattern['input_search_tag'] = 'nullable|string|max:191';
        $validate_pattern['input_release_start'] = 'required|date';
        $validate_pattern['input_release_end'] = 'required|date';
        if ($this->session()->get($this->upsert_session_name) !== 'create') {
            $validate_pattern['input_no'] = 'required|numeric|digits_between:1,10|between:1,4294967295';
        }

        return $validate_pattern;
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
            'input_description.required' => '紹介が入力されていません。',
            'input_description.string' => '紹介がテキストではありません。',
            'input_description.max' => '紹介は191文字以内で入力して下さい。',
            'input_date.required' => '開催日が入力されていません。',
            'input_date.date' => '開催日の書式が正しくありません。',
            'input_stage.required' => '開催場所が入力されていません。',
            'input_stage.string' => '開催場所がテキストではありません。',
            'input_stage.max' => '開催場所は64文字以内で入力して下さい。',
            'input_weather.required' => '天気が選択されていません。',
            'input_weather.numeric' => '天気の選択値が不正です。',
            'input_weather.digits_between' => '天気の選択値が不正です。',
            'input_weather.between' => '天気の選択値が不正です。',
            'input_organizer.required' => '主催者が入力されていません。',
            'input_organizer.string' => '主催者がテキストではありません。',
            'input_organizer.max' => '主催者は64文字以内で入力して下さい。',
            'input_text.required' => '詳細が入力されていません。',
            'input_text.string' => '詳細がテキストではありません。',
            'input_image_no.nullable' => 'パラメータが不正です。管理者へお問い合わせ下さい。',
            'input_image_no.numeric' => 'パラメータが不正です。管理者へお問い合わせ下さい。',
            'input_image_no.digits_between' => 'パラメータが不正です。管理者へお問い合わせ下さい。',
            'input_image_no.between' => 'パラメータが不正です。管理者へお問い合わせ下さい。',
            'input_search_tag.nullable' => 'タグが空欄ではありません。',
            'input_search_tag.string' => 'タグがテキストではありません。',
            'input_search_tag.max' => 'タグはカンマ込みで191文字以内で入力して下さい。',
            'input_release_start.required' => '掲載開始日が入力されていません。',
            'input_release_start.date' => '掲載開始日の書式が正しくありません。',
            'input_release_end.required' => '掲載終了日が入力されていません。',
            'input_release_end.date' => '掲載終了日の書式が正しくありません。'
        ];
    }
}