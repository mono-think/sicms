<?php
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use GuzzleHttp\Psr7\UploadedFile;

if (! function_exists('getLoginInfo')) {

    /**
     * ログイン情報の取得
     *
     * @param
     *            Illuminate\Http\Request
     * @return array
     *
     */
    function getLoginInfo($request)
    {
        if ($request->session()->has(config('consts.session_info.login_auth'))) {
            return $request->session()->get(config('consts.session_info.login_auth'));
        } else {
            return null;
        }
    }
}

if (! function_exists('setLoginInfo')) {

    /**
     * ログイン情報の再設定
     *
     * @param
     *            Illuminate\Http\Request
     * @param array $login_info
     *
     */
    function setLoginInfo($request, $login_info)
    {
        $request->session()->put(config('consts.session_info.login_auth'), $login_info);
        $request->session()->regenerate();
    }
}

if (! function_exists('chkIntendedUrl')) {

    /**
     * セッションタイムアウト直前のURLを維持しているか判定
     *
     * @param
     *            Illuminate\Http\Request
     * @return Boolean
     *
     */
    function chkIntendedUrl($request)
    {
        if (empty($request->session()->get('url.intended'))) {
            return false;
        }
        return true;
    }
}

if (! function_exists('getNumRandom')) {

    /**
     * ランダムな数値を指定桁数で取得
     *
     * @param int $number
     * @return int $ret
     *        
     */
    function getNumRandom($number)
    {
        $max_number = pow(10, $number) - 1; // 桁数取得
        $randaom_num = random_int(0, $max_number); // 乱数生成
        $ret = sprintf('%0' . $number . 'd', $randaom_num); // ゼロ埋め

        return $ret;
    }
}

if (! function_exists('islog')) {

    /**
     * インフォメーションログ出力
     *
     * @param string $c_name
     * @param string $f_name
     * @param string $m_mode
     * @param array $param
     * @param string $param
     *
     */
    function islog($c_name, $f_name, $m_mode = null, $param = null)
    {
        $message_data = config('consts.message_info.log');
        $message_text = isset($message_data[config('consts.message_info.log_language_mode')]['info'][$m_mode]) ? $message_data[config('consts.message_info.log_language_mode')]['info'][$m_mode] : "";
        $log_txt = "[{$c_name}::{$f_name}]{$message_text}";
        if (is_null($param)) {
            Log::info($log_txt);
        } else if (is_array($param)) {
            Log::info($log_txt . ' => ', $param);
        } else {
            Log::info($log_txt . ' => ', [
                $param
            ]);
        }
    }
}

if (! function_exists('dslog')) {

    /**
     * デバッグログ出力
     *
     * @param string $c_name
     * @param string $f_name
     * @param string $m_mode
     * @param array $param
     * @param string $param
     */
    function dslog($c_name, $f_name, $m_mode, $param = null)
    {
        $message_data = config('consts.message_info.log');
        $message_text = isset($message_data[config('consts.message_info.log_language_mode')]['debug'][$m_mode]) ? $message_data[config('consts.message_info.log_language_mode')]['debug'][$m_mode] : "";
        $log_txt = "[{$c_name}::{$f_name}]{$message_text}";
        if (is_null($param)) {
            Log::debug($log_txt);
        } else if (is_array($param)) {
            Log::debug($log_txt . ' => ', $param);
        } else {
            Log::debug($log_txt . ' => ', [
                $param
            ]);
        }
    }
}

if (! function_exists('eslog')) {

    /**
     * エラーログ出力
     *
     * @param string $c_name
     * @param string $f_name
     * @param string $m_mode
     * @param array $param
     * @param string $param
     */
    function eslog($c_name, $f_name, $m_mode, $param = null)
    {
        $message_data = config('consts.message_info.log');
        $message_text = isset($message_data[config('consts.message_info.log_language_mode')]['error'][$m_mode]) ? $message_data[config('consts.message_info.log_language_mode')]['error'][$m_mode] : "";
        $log_txt = "[{$c_name}::{$f_name}]{$message_text}";
        if (is_null($param)) {
            Log::error($log_txt);
        } else if (is_array($param)) {
            Log::error($log_txt . ' => ', $param);
        } else {
            Log::error($log_txt . ' => ', [
                $param
            ]);
        }
    }
}

if (! function_exists('aslog')) {

    /**
     * 警告ログ出力
     *
     * @param string $c_name
     * @param string $f_name
     * @param string $m_mode
     * @param array $param
     * @param string $param
     */
    function aslog($c_name, $f_name, $m_mode, $param = null)
    {
        $message_data = config('consts.message_info.log');
        $message_text = isset($message_data[config('consts.message_info.log_language_mode')]['alert'][$m_mode]) ? $message_data[config('consts.message_info.log_language_mode')]['alert'][$m_mode] : "";
        $log_txt = "[{$c_name}::{$f_name}]{$message_text}";
        if (is_null($param)) {
            Log::alert($log_txt);
        } else if (is_array($param)) {
            Log::alert($log_txt . ' => ', $param);
        } else {
            Log::alert($log_txt . ' => ', [
                $param
            ]);
        }
    }
}

if (! function_exists('getSiteRobots')) {

    /**
     * サイトをクローラによるインデックス登録を許可するか判定
     *
     * @return string
     */
    function getSiteRobots($name)
    {
        $none_data = config('consts.base_info.setting.robots_none');
        if (in_array($name, $none_data, true) == true) {
            return 'none';
        } else {
            return 'all';
        }
    }
}

if (! function_exists('getSiteName')) {

    /**
     * サイトのオーナー（ユーザー）名を取得
     *
     * @return string
     */
    function getSiteName()
    {
        return config('consts.base_info.jp.base_name');
    }
}

if (! function_exists('getSiteTitle')) {

    /**
     * サイトのタイトルテキストを取得
     *
     * @param array $param
     * @return string
     */
    function getSiteTitle($param = null)
    {
        $txt = config('consts.base_info.jp.base_seo_title');
        $omit_txt = config('consts.base_info.jp.base_omit_title');
        if (! empty($param)) {
            return isset($param['title']) ? "{$param['title']}【{$omit_txt}】" : $txt;
        } else {
            return $txt;
        }
    }
}

if (! function_exists('getSiteOmitTitle')) {

    /**
     * サイトの簡略タイトルテキストを取得
     *
     * @return string
     */
    function getSiteOmitTitle()
    {
        $txt = config('consts.base_info.jp.base_omit_title');
        return $txt;
    }
}

if (! function_exists('getTitle')) {

    /**
     * タイトルテキストを取得
     *
     * @return string
     */
    function getTitle()
    {
        return config('consts.base_info.jp.base_title');
    }
}

if (! function_exists('getTitleKana')) {

    /**
     * タイトルテキスト（カナ文字）を取得
     *
     * @return string
     */
    function getTitleKana()
    {
        return config('consts.base_info.jp.base_title_kana');
    }
}

if (! function_exists('getSiteDescription')) {

    /**
     * サイトの説明テキストを取得
     * 130文字以上は3点リーダー対応
     *
     * @param array $param
     * @return string
     */
    function getSiteDescription($param = null)
    {
        $txt = config('consts.base_info.jp.base_description');
        $description_txt = isset($param['description']) ? $param['description'] : $txt;
        return Str::limit($description_txt, 260, '…');
    }
}

if (! function_exists('getSiteWord')) {

    /**
     * サイトのキーワードを取得
     *
     * @param array $param
     * @return string
     */
    function getSiteWord($param = null)
    {
        $txt = config('consts.base_info.jp.base_keyword');
        return isset($param['keywords']) ? $param['keywords'] : $txt;
    }
}
if (! function_exists('getSiteCanonical')) {

    /**
     * サイトの正規URLを取得
     *
     * @param array $param
     * @return string
     */
    function getSiteCanonical($param = null)
    {
        $txt = env('APP_URL');
        return isset($param['url']) ? $param['url'] : $txt;
    }
}
if (! function_exists('getSiteTwitterCard')) {

    /**
     * サイトのTwitter CARDタイプを取得
     *
     * @return string
     */
    function getSiteTwitterCard()
    {
        return config('consts.base_info.setting.twitter_card');
    }
}

if (! function_exists('getSiteFooterTitle')) {

    /**
     * サイトのフッタータイトルを取得
     *
     * @return string
     */
    function getSiteFooterTitle()
    {
        return config('consts.base_info.en.base_footer_title');
    }
}

if (! function_exists('getFbAppId')) {

    /**
     * facebookのアプリIDを取得
     *
     * @return string
     */
    function getFbAppId()
    {
        return config('consts.base_info.setting.fb_appid');
    }
}

if (! function_exists('getOgpType')) {

    /**
     * OGPタイプを取得
     *
     * @param array $param
     * @return string
     */
    function getOgpType($param = null)
    {
        if ($param) {
            return 'article';
        } else {
            return 'website';
        }
    }
}

if (! function_exists('getSiteProductsCategory')) {

    /**
     * サイトの製品カテゴリ情報を取得
     *
     * @return array
     */
    function getSiteProductsCategory()
    {
        return [
            config('consts.base_info.jp.products_category_title'),
            config('consts.base_info.jp.products_category_description'),
            config('consts.base_info.jp.products_category_word')
        ];
    }
}

if (! function_exists('getSiteProductsList')) {

    /**
     * サイトの製品リスト情報を取得
     *
     * @return array
     */
    function getSiteProductsList()
    {
        return [
            config('consts.base_info.jp.products_list_title'),
            config('consts.base_info.jp.products_list_description')
        ];
    }
}

if (! function_exists('getSiteCompanyList')) {

    /**
     * サイトの企業リスト情報を取得
     *
     * @return array
     */
    function getSiteCompanyList()
    {
        return [
            config('consts.base_info.jp.company_list_title'),
            config('consts.base_info.jp.company_list_description'),
            config('consts.base_info.jp.company_list_word')
        ];
    }
}

if (! function_exists('getSiteCompanyNewsList')) {

    /**
     * サイトの企業ニュースリスト情報を取得
     *
     * @return array
     */
    function getSiteCompanyNewsList()
    {
        return [
            config('consts.base_info.jp.company_news_list_title'),
            config('consts.base_info.jp.company_news_list_description'),
            config('consts.base_info.jp.company_news_list_word')
        ];
    }
}

if (! function_exists('getSiteCompanyNews')) {

    /**
     * サイトの企業ニュース情報を取得
     *
     * @return string
     */
    function getSiteCompanyNews()
    {
        return config('consts.base_info.jp.company_news_word');
    }
}

if (! function_exists('getSiteEventList')) {

    /**
     * サイトのイベントリスト情報を取得
     *
     * @return array
     */
    function getSiteEventList()
    {
        return [
            config('consts.base_info.jp.event_list_title'),
            config('consts.base_info.jp.event_list_description'),
            config('consts.base_info.jp.event_list_word')
        ];
    }
}

if (! function_exists('getSiteContentList')) {

    /**
     * サイトのコンテンツリスト情報を取得
     *
     * @return array
     */
    function getSiteContentList()
    {
        return [
            config('consts.base_info.jp.content_list_title'),
            config('consts.base_info.jp.content_list_description'),
            config('consts.base_info.jp.content_list_word')
        ];
    }
}

if (! function_exists('getDefaultPagenation')) {

    /**
     * デフォルトページネーション数を取得
     *
     * @return int
     */
    function getDefaultPagenation()
    {
        return config('consts.base_info.setting.default_pagenation');
    }
}

if (! function_exists('getTokenName')) {

    /**
     * デフォルトトークン名称を取得
     *
     * @return int
     */
    function getTokenName()
    {
        return config('consts.base_info.setting.token_name');
    }
}

if (! function_exists('getTwostepValue')) {

    /**
     * 二段階認証情報を取得
     *
     * @return int
     */
    function getTwostepValue()
    {
        return array(
            config('consts.base_info.setting.twostep_name'),
            config('consts.base_info.setting.twostep_value')
        );
    }
}

if (! function_exists('dispInfoMessage')) {

    /**
     * 画面表示メッセージ
     *
     * @param string $m_mode
     * @param array $param
     * @param string $param
     * @return string $message_text
     */
    function dispInfoMessage($m_mode, $param = null)
    {
        $message_data = config('consts.message_info.disp');
        $message_text = isset($message_data[config('consts.message_info.disp_language_mode')]['info'][$m_mode]) ? $message_data[config('consts.message_info.disp_language_mode')]['info'][$m_mode] : "";

        if (is_null($param)) {
            return $message_text;
        } else if (is_array($param)) {
            return $message_text . ' => ' . implode(',', $param);
        } else {
            return $message_text . ' => ' . $param;
        }
    }
}

if (! function_exists('dispErrorMessage')) {

    /**
     * 画面表示エラーメッセージ
     *
     * @param string $m_mode
     * @param array $param
     * @param string $param
     * @return string $message_text
     */
    function dispErrorMessage($m_mode, $param = null)
    {
        $message_data = config('consts.message_info.disp');
        $message_text = isset($message_data[config('consts.message_info.disp_language_mode')]['error'][$m_mode]) ? $message_data[config('consts.message_info.disp_language_mode')]['error'][$m_mode] : "";

        if (is_null($param)) {
            return $message_text;
        } else if (is_array($param)) {
            return $message_text . ' => ' . implode(',', $param);
        } else {
            return $message_text . ' => ' . $param;
        }
    }
}

if (! function_exists('dispExceptionMessage')) {

    /**
     * 画面表示障害メッセージ
     *
     * @param string $m_mode
     * @param array $param
     * @param string $param
     * @return string $message_text
     */
    function dispExceptionMessage($m_mode, $param = null)
    {
        $message_data = config('consts.message_info.disp');
        $message_text = isset($message_data[config('consts.message_info.disp_language_mode')]['exception'][$m_mode]) ? $message_data[config('consts.message_info.disp_language_mode')]['exception'][$m_mode] : "";
        if (is_null($param)) {
            return $message_text;
        } else if (is_array($param)) {
            return $message_text . ' => ' . implode(',', $param);
        } else {
            return $message_text . ' => ' . $param;
        }
    }
}

if (! function_exists('checkAuthCompanyTypeDefault')) {

    /**
     * 企業種別が初期アカウントかの判定
     *
     * @param integer $type
     *
     * @return boolean
     */
    function checkAuthCompanyTypeDefault($type)
    {
        if ($type === config('consts.auth_info.company_type_default')) {
            return true;
        } else {
            return false;
        }
    }
}

if (! function_exists('checkAuthCompanyTypeAdmin')) {

    /**
     * 企業種別がアドミニストレータかの判定
     *
     * @param integer $type
     *
     * @return boolean
     */
    function checkAuthCompanyTypeAdmin($type)
    {
        if ($type === config('consts.auth_info.company_type_admin')) {
            return true;
        } else {
            return false;
        }
    }
}

if (! function_exists('getAuthCompanyTypeAdmin')) {

    /**
     * 企業種別の運営権限値を取得
     *
     * @return int
     */
    function getAuthCompanyTypeAdmin()
    {
        return config('consts.auth_info.company_type_admin');
    }
}

if (! function_exists('checkAuthCompanyTypeCustomer')) {

    /**
     * 企業種別がカスタマーかの判定
     *
     * @param integer $type
     *
     * @return boolean
     */
    function checkAuthCompanyTypeCustomer($type)
    {
        if ($type === config('consts.auth_info.company_type_customer')) {
            return true;
        } else {
            return false;
        }
    }
}

if (! function_exists('getAuthCompanyTypeCustomer')) {

    /**
     * 企業種別のカスタマー権限値を取得
     *
     * @return boolean
     */
    function getAuthCompanyTypeCustomer()
    {
        return config('consts.auth_info.company_type_customer');
    }
}

if (! function_exists('checkMasterAcnt')) {

    /**
     * マスターアカウントの判定
     *
     * @param string $auth_no
     *
     * @return boolean
     */
    function checkMasterAcnt($auth_no)
    {
        if ($auth_no === 'master') {
            return true;
        } else {
            return false;
        }
    }
}

if (! function_exists('checkAuthAcntAdmin')) {

    /**
     * アドミニストレータの判定
     *
     * @param integer $auth_no
     *
     * @return boolean
     */
    function checkAuthAcntAdmin($auth_no)
    {
        if ($auth_no == config('consts.auth_info.auth_category_admin')) {
            return true;
        } else {
            return false;
        }
    }
}

if (! function_exists('checkAuthAcntBranch')) {

    /**
     * 支店管理者の判定
     *
     * @param integer $auth_no
     *
     * @return boolean
     */
    function checkAuthAcntBranch($auth_no)
    {
        if ($auth_no == config('consts.auth_info.auth_category_branch')) {
            return true;
        } else {
            return false;
        }
    }
}

if (! function_exists('checkAuthAcntDepartment')) {

    /**
     * 部署管理者の判定
     *
     * @param integer $auth_no
     *
     * @return boolean
     */
    function checkAuthAcntDepartment($auth_no)
    {
        if ($auth_no == config('consts.auth_info.auth_category_department')) {
            return true;
        } else {
            return false;
        }
    }
}

if (! function_exists('checkAuthAcntViewer')) {

    /**
     * 閲覧者の判定
     *
     * @param integer $auth_no
     *
     * @return boolean
     */
    function checkAuthAcntViewer($auth_no)
    {
        if ($auth_no == config('consts.auth_info.auth_category_view')) {
            return true;
        } else {
            return false;
        }
    }
}

if (! function_exists('chkCompanyLink')) {

    /**
     * 企業リンク先変更判定
     *
     * @param string $type
     * @param mixed $id
     * @return string $url
     */
    function chkCompanyLink($type, $id = null)
    {
        if (($type === config('consts.auth_info.company_type_default')) || ($type === config('consts.auth_info.company_type_admin'))) {
            return route('company.index');
        } else if ($type === config('consts.auth_info.company_type_customer')) {
            return route('company.show', $id);
        } else {
            return route('login'); // 強制的にログイン画面へ
        }
    }
}

if (! function_exists('chkUploadImage')) {

    /**
     * アップロードを許可する画像かを判定
     *
     * @param UploadedFile $obj
     * @return boolean
     */
    function chkUploadImage($obj)
    {
        // 存在チェック
        if (! $obj->isValid()) {
            return false;
        }

        // アップロード画像情報を取得
        $info = getimagesize($obj->path());
        if (! $info) {
            return false;
        }
        // mimeタイプチェック
        if (array_search(image_type_to_mime_type($info[2]), config('consts.image_info.upload_auth_type')) === false) {
            return false;
        }
        // 最小画像幅チェック
        if (config('consts.image_info.upload_min_width') > $info[0]) {
            return false;
        }

        return true;
    }
}

if (! function_exists('getImageConvertPattern')) {

    /**
     * 画像変換パターンを取得
     *
     * @return array
     */
    function getImageConvertPattern()
    {
        return config('consts.image_info.convert_pattern');
    }
}

if (! function_exists('getImageProductsCategory')) {

    /**
     * 画像の製品カテゴリNoを取得 または指定値との一致判定
     *
     * @param int $type
     * @return string or boolean
     */
    function getImageProductsCategory($type = null)
    {
        $category_data = config('consts.image_info.image_products_category');
        if (is_null($type)) {
            return $category_data;
        } else {
            if ($category_data == $type) {
                return true;
            } else {
                return false;
            }
        }
    }
}

if (! function_exists('getImageCompanyCategory')) {

    /**
     * 画像の企業カテゴリNoを取得 または指定値との一致判定
     *
     * @param int $type
     * @return string or boolean
     */
    function getImageCompanyCategory($type = null)
    {
        $category_data = config('consts.image_info.image_company_category');
        if (is_null($type)) {
            return $category_data;
        } else {
            if ($category_data == $type) {
                return true;
            } else {
                return false;
            }
        }
    }
}

if (! function_exists('getImageEventCategory')) {

    /**
     * 画像のイベントカテゴリNoを取得 または指定値との一致判定
     *
     * @param int $type
     * @return string or boolean
     */
    function getImageEventCategory($type = null)
    {
        $category_data = config('consts.image_info.image_event_category');
        if (is_null($type)) {
            return $category_data;
        } else {
            if ($category_data == $type) {
                return true;
            } else {
                return false;
            }
        }
    }
}

if (! function_exists('getImageContentsCategory')) {

    /**
     * 画像のコンテンツカテゴリNoを取得 または指定値との一致判定
     *
     * @param int $type
     * @return string or boolean
     */
    function getImageContentsCategory($type = null)
    {
        $category_data = config('consts.image_info.image_content_category');
        if (is_null($type)) {
            return $category_data;
        } else {
            if ($category_data == $type) {
                return true;
            } else {
                return false;
            }
        }
    }
}

if (! function_exists('getCaptchaSavePath')) {

    /**
     * Captcha画像の保存パスを取得
     *
     * @return string
     */
    function getCaptchaSavePath()
    {
        return storage_path('app/' . config('consts.image_info.tmp_save_folder') . '/' . config('consts.image_info.captcha_file'));
    }
}

if (! function_exists('getCaptchaPath')) {

    /**
     * Captcha画像の出力パスを取得
     *
     * @return string
     */
    function getCaptchaPath()
    {
        return url('') . "/" . config('consts.image_info.tmp_folder') . '/' . config('consts.image_info.captcha_file') . '?t=' . time();
    }
}

if (! function_exists('getImageBaseFolder')) {

    /**
     * アップロード画像のベースフォルダを取得
     *
     * @return string
     */
    function getImageBaseFolder()
    {
        return config('consts.image_info.save_folder');
    }
}

if (! function_exists('getImagePublicFolder')) {

    /**
     * 公開画像のベースフォルダを取得
     *
     * @return string
     */
    function getImagePublicFolder()
    {
        return config('consts.image_info.public_image_folder');
    }
}

if (! function_exists('getImagePublicFullpath')) {

    /**
     * 公開画像のベースパスフォルダを取得
     *
     * @return string
     */
    function getImagePublicFullpath()
    {
        return url('') . "/" . config('consts.image_info.public_image_folder');
    }
}

if (! function_exists('convertImageFilename')) {

    /**
     * 画像ファイル名を変換
     *
     * @param string $name
     * @param int $width
     * @param int $height
     * @param string $type
     * @param boolean $rename_flg
     * @param string $dname
     * @return string
     */
    function convertImageFilename($name, $width, $height, $type, $rename_flg, $dname)
    {
        // 画像名確定
        $image_dsc_name = ! empty($name) ? $name : $dname;

        // 変換指示あり
        $image_file_name = "";
        if ($rename_flg) {
            if (! empty($height)) {
                $image_file_name = "{$image_dsc_name}_{$width}x{$height}.{$type}";
            } else {
                $image_file_name = "{$image_dsc_name}_{$width}.{$type}";
            }
        } else {
            $image_file_name = "{$image_dsc_name}.{$type}";
        }

        return $image_file_name;
    }
}

if (! function_exists('getUploadImagePath')) {

    /**
     * アップロード画像パスを取得
     *
     * @param string $folder
     * @param string $name
     * @param string $type
     * @return string
     */
    function getUploadImagePath($folder, $name = null, $type = null)
    {
        $base_folder = config('consts.image_info.save_folder');
        if (empty($name)) {
            $image_path_base = "{$base_folder}/{$folder}";
        } else {
            if (empty($type)) {
                $image_path_base = "{$base_folder}/{$folder}/{$name}";
            } else {
                $image_path_base = "{$base_folder}/{$folder}/{$name}.{$type}";
            }
        }
        return $image_path_base;
    }
}

if (! function_exists('getUploadImageFullPath')) {

    /**
     * アップロード画像フルパスを取得
     *
     * @param string $folder
     * @param string $name
     * @param string $type
     * @return string
     */
    function getUploadImageFullPath($folder, $name, $type = null)
    {
        $base_folder = storage_path('app/' . config('consts.image_info.save_folder'));
        if (empty($type)) {
            $image_path_base = "{$base_folder}/{$folder}/{$name}";
        } else {
            $image_path_base = "{$base_folder}/{$folder}/{$name}.{$type}";
        }
        return $image_path_base;
    }
}

if (! function_exists('getNoimagePath')) {

    /**
     * 登録なし用画像パスを取得
     *
     * @string $disp
     * @string $type
     * @return string
     */
    function getNoimagePath($disp = null, $type = null)
    {
        if (is_null($type)) {
            $image_type = config('consts.image_info.public_noimage_type');
        } else {
            $image_type = $type;
        }

        $image_name = config('consts.image_info.public_noimage_path');
        if (! is_null($disp)) {
            $image_name = $image_name . '_' . $disp;
        }

        $image_path_base = url('/') . "/" . $image_name . '.' . $image_type;
        return $image_path_base;
    }
}

if (! function_exists('getImagePath')) {

    /**
     * 画像パスを取得
     *
     * @param string $folder
     * @param string $name
     * @param string $type
     * @return string
     */
    function getImagePath($folder, $name, $type = null)
    {
        $base_folder = url('') . "/" . config('consts.image_info.public_image_folder');
        if (strlen($name) > 0) {
            // 画像設定有り
            if (! is_null($type)) {
                // 拡張子指定あり
                $image_path_base = "{$base_folder}/{$folder}/{$name}.{$type}";
            } else {
                // 拡張子指定なし
                $image_path_base = "{$base_folder}/{$folder}/{$name}";
            }
        } else {
            // 画像設定なし
            $image_path_base = url('') . "/" . config('consts.image_info.public_noimage_path') . '.' . config('consts.image_info.public_noimage_type');
        }
        return $image_path_base;
    }
}

if (! function_exists('getImageSrcset')) {

    /**
     * 画像パス(srcset)を取得
     *
     * @param string $folder
     * @param string $name
     * @param string $type
     * @param int $idx
     * @return mixed
     */
    function getImageSrcset($folder, $name, $type, $idx = 0)
    {
        $base_folder = url('') . "/" . config('consts.image_info.public_image_folder');
        $image_type = config('consts.image_info.image_type');
        $image_base_path = (strlen($name) > 0) ? "{$base_folder}/{$folder}/{$name}" : "";
        $type = (strlen($type) > 0) ? $type : config('consts.image_info.public_noimage_type');

        if (strlen($image_base_path) <= 0) {
            // 画像設定無し
            $image_base_path = url('') . "/" . config('consts.image_info.public_noimage_path');
        }
        // 変換パターン
        $disp_image = config('consts.image_info.disp_image');

        $ret_srcset_path = [];
        if ($idx == 0) {
            $srcset_path = [];
            foreach ($disp_image as $disp) {
                $srcset_path[] = $image_base_path . "_{$disp}.{$type} {$disp}w";
            }
            $ret_srcset_path = implode(",", $srcset_path);
        } else if (isset($image_type[$idx])) {
            $srcset_path = [];
            foreach ($disp_image as $disp) {
                $srcset_path[] = $image_base_path . "_{$disp}.{$image_type[$idx]} {$disp}w";
            }
            $ret_srcset_path = [
                'data' => implode(",", $srcset_path),
                'type' => $image_type[$idx]
            ];
        }
        return $ret_srcset_path;
    }
}

if (! function_exists('getHeaderImageNo')) {

    /**
     * ヘッダー設定の画像番号を取得
     *
     * @param string $param
     * @return string
     */
    function getHeaderImageNo($param = null)
    {
        return isset($param['image_no']) ? $param['image_no'] : null;
    }
}

if (! function_exists('getHeaderImagePath')) {

    /**
     * ヘッダー設定の画像パスを取得
     *
     * @param string $param
     * @return string
     */
    function getHeaderImagePath($param = null)
    {
        $txt = url('') . "/" . config('consts.image_info.image_folder') . "/" . config('consts.base_info.setting.default_image');
        return isset($param['image_path']) ? $param['image_path'] : $txt;
    }
}

if (! function_exists('getHeaderImageWidth')) {

    /**
     * ヘッダー設定の画像幅を取得
     *
     * @param string $param
     * @return int
     */
    function getHeaderImageWidth($param = null)
    {
        return ! empty($param['image_width']) ? $param['image_width'] : config('consts.base_info.setting.default_image_width');
    }
}

if (! function_exists('getHeaderImageHeight')) {

    /**
     * ヘッダー設定の画像高を取得
     *
     * @param string $param
     * @return int
     */
    function getHeaderImageHeight($param = null)
    {
        return ! empty($param['image_height']) ? $param['image_height'] : config('consts.base_info.setting.default_image_height');
    }
}

if (! function_exists('outputValue')) {

    /**
     * セッション状況により出力する値を指定
     *
     * @param array $form_data;
     * @param string $attr;
     * @param int $idx;
     *
     * @return string
     */
    function outputValue($form_data, $attr, $idx = null)
    {
        $param = old($attr);
        if (is_array($param)) {
            // oldセッションが配列
            if (! is_null($idx)) {
                // インデックス指定あり
                return data_get($param, $idx, '');
            }
        } else {
            // oldセッションが配列以外
            if (strlen($param) === 0) {
                // oldセッションが空なのでフォーム値を設定
                $param = data_get($form_data, $attr, '');
                if ((is_array($param)) && (! is_null($idx))) {
                    // 配列でインデックス指定あり
                    return data_get($param, $idx, '');
                }
            }
        }

        return $param;
    }
}

if (! function_exists('convertDateType')) {

    /**
     * 日時文字列を日付のみに変換する
     *
     * @param string $date_str;
     *
     * @return string
     */
    function convertDateType($date_str, $mode = null)
    {
        $date_obj = new DateTime($date_str);
        $ret_str = "";
        switch ($mode) {
            case 'j':
                $ret_str = $date_obj->format('Y年m月d日');
                break;
            case 'al':
                $ret_str = $date_obj->format('Y/m/d H:i:s');
                break;
            case 'a':
                $ret_str = $date_obj->format('Y/m/d');
                break;
            default:
                $ret_str = $date_obj->format('Y-m-d');
                break;
        }

        return $ret_str;
    }
}

if (! function_exists('getConstsType')) {

    /**
     * 指定consts定義の取得
     *
     * @param string $mode
     * @param integer $type
     *
     * @return string
     */
    function getConstsType($mode, $type = null)
    {
        // consts定義の選択
        $master_type = null;
        switch ($mode) {
            case 'auth':
                $master_type = config('consts.auth_info.auth_category');
                break;
            case 'customer':
                $master_type = config('consts.customer_info.customer');
                break;
            case 'service':
                $master_type = config('consts.products_info.service');
                break;
            case 'weather':
                $master_type = config('consts.weather_info.weather');
                break;
            case 'content':
                $master_type = config('consts.content_info.content');
                break;
            case 'company':
                $master_type = config('consts.auth_info.company_type');
                break;
            case 'branch_support':
                $master_type = config('consts.company_info.company_support');
                break;
            case 'sns':
                $master_type = config('consts.sns_info.sns');
                break;
            case 'image_category':
                $master_type = config('consts.image_info.image_category');
                break;
            case 'image_disp':
                $master_type = config('consts.image_info.disp_image');
                break;
            case 'image_type':
                $master_type = config('consts.image_info.image_type');
                break;
        }

        // type指定により戻り値を決定
        if (is_null($type)) {
            return $master_type;
        } else {
            if (isset($master_type[$type])) {
                return $master_type[$type];
            } else {
                return "";
            }
        }
    }
}

if (! function_exists('convertHtmlText')) {

    /**
     * 許可されたタグ以外を削除
     *
     * @param string $str;
     * @param boolean $insert_ptag
     *
     * @return string
     */
    function convertHtmlText($str, $insert_ptag = false)
    {
        // 許可情報を取得
        $auth_tag = config('consts.html_info.auth_tag');
        $auth_css = config('consts.html_info.auth_css');

        $config = HTMLPurifier_Config::createDefault();
        $config->loadArray([
            'Core.Encoding' => 'UTF-8',
            'HTML.Doctype' => 'XHTML 1.0 Strict',
            'HTML.Allowed' => $auth_tag,
            'CSS.AllowedProperties' => $auth_css,
            'AutoFormat.AutoParagraph' => $insert_ptag,
            'AutoFormat.RemoveEmpty' => true
            // ,'Cache.DefinitionImpl' => null
        ]);
        $def = $config->getHTMLDefinition(true);
        $def->addAttribute('a', 'target', new HTMLPurifier_AttrDef_Enum(array(
            '_blank',
            '_self',
            '_target',
            '_top'
        )));
        $def->addAttribute('img', 'loading', new HTMLPurifier_AttrDef_Enum(array(
            'lazy'
        )));
        $def->addAttribute('img', 'srcset', 'Text');
        $def->addElement('figure', 'Block', 'Optional: figcaption, picture, source, Flow', 'Common');
        $def->addElement('figcaption', 'Inline', 'Flow', 'Common');
        $def->addElement('picture', 'Inline', 'Flow', 'Common');
        $def->addElement('source', 'Inline', 'Empty', 'Common', array(
            'type' => 'Text',
            'srcset' => 'Text'
        ));
        $def->addElement('iframe', 'Block', 'Flow', 'Common', array(
            'loading' => 'Text',
            'title' => 'Text',
            'src' => 'URI',
            'frameborder' => "Enum",
            'allowfullscreen' => "Text"
        ));

        // 変換
        $purifier = new HTMLPurifier($config);
        return $purifier->purify($str);
    }
}

if (! function_exists('insertPtag')) {

    /**
     * 指定テキストの改行単位にPタグを挿入
     *
     * @param string $str;
     *
     * @return string
     */
    function insertPtag($str)
    {
        $str_array = explode("\n\n", $str);
        $str_array = array_map('trim', $str_array);
        $str_array = array_filter($str_array, 'strlen');

        $ret_txt = "";
        foreach ($str_array as $txt) {
            $str_sub_array = explode("\n", $txt);
            $str_sub_array = array_map('trim', $str_sub_array);
            $str_sub_array = array_filter($str_sub_array, 'strlen');
            $sub_txt = "";
            foreach ($str_sub_array as $stxt) {
                if (strlen($sub_txt) > 0) {
                    $sub_txt .= '<br />';
                }
                $sub_txt .= $stxt;
            }
            $ret_txt .= '<p>' . $sub_txt . '</p>';
            // $ret_txt .= $txt . '<br />';
        }

        return $ret_txt;
    }
}

if (! function_exists('dispPublic')) {

    /**
     * 公開 / 非公開テキストを返す
     *
     * @param array $type;
     *
     * @return string
     */
    function dispPublic($type)
    {
        if ($type == 1) {
            return "公開中";
        } else {
            return "非公開";
        }
    }
}

if (! function_exists('hscQuotes')) {

    /**
     * 指定パラメータをサニタイズ
     *
     * @param string $param;
     *
     * @return string
     */
    function hscQuotes($param)
    {
        return htmlspecialchars($param, ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401, 'utf-8');
    }
}

if (! function_exists('getTwitterUrl')) {

    /**
     * TwitterのURLを取得
     *
     * @return string
     */
    function getTwitterUrl()
    {
        return config('consts.base_info.setting.twitter_url');
    }
}

if (! function_exists('getFacebookUrl')) {

    /**
     * facebookのURLを取得
     *
     * @return string
     */
    function getFacebookUrl()
    {
        return config('consts.base_info.setting.facebook_url');
    }
}

if (! function_exists('getYoutubeUrl')) {

    /**
     * YoutubeのURLを取得
     *
     * @return string
     */
    function getYoutubeUrl()
    {
        return config('consts.base_info.setting.youtube_url');
    }
}

if (! function_exists('getInstagramUrl')) {

    /**
     * InstagramのURLを取得
     *
     * @return string
     */
    function getInstagramUrl()
    {
        return config('consts.base_info.setting.instagram_url');
    }
}
