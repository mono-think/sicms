<?php
/**
 * アカウント情報処理のServiceクラス
 * 検索、登録、更新、削除処理
 *
 * @access public
 * @author Seiji Ishikawa（メールアドレス）
 * @copyright Webじゃ軒 All Rights Reserved
 * @package service
 */
namespace App\Services\Manage;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Repositories\Manage\AcntRepositoryInterface;
use Carbon\Carbon;

class AcntService
{

    protected $acnt_repository;

    protected $class_name;

    /**
     * コンストラクタ
     *
     * @access public
     * @param AcntRepositoryInterface $acnt_repository
     *
     */
    public function __construct(AcntRepositoryInterface $acnt_repository)
    {
        $this->acnt_repository = $acnt_repository;
        $this->class_name = get_class($this);
        islog($this->class_name, __FUNCTION__);
    }

    /**
     * アカウント情報ページネーション一覧取得
     * 検索条件により全てのアカウント情報を取得
     *
     * @access public
     * @param string $company_no
     * @param string $company_branch_no
     * @param string $company_department_no
     * @param string $free_word
     * @return \Illuminate\Database\Eloquent\Collection
     * @see AcntService
     * @throws
     * @todo
     */
    public function getAcntPagenationList($company_no, $company_branch_no, $company_department_no, $free_word)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'company_no' => $company_no,
            'company_branch_no' => $company_branch_no,
            'company_department_no' => $company_department_no,
            'free_word' => $free_word
        ]);

        // データ取得
        islog($this->class_name, __FUNCTION__, 'm10');
        $free_word = hscQuotes($free_word); //HTMLエンティティ変換
        $ret = $this->acnt_repository->getAcntPagenationList($company_no, $company_branch_no, $company_department_no, $free_word);

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * アカウント情報取得
     * アカウント番号によりアカウント情報を取得
     *
     * @access public
     * @param string $company_no
     * @param string $acnt_no
     * @param int $delete_flg
     * @return array
     * @see AcntService
     * @throws
     * @todo
     */
    public function getAcntData($company_no, $acnt_no, $delete_flg = 0)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'company_no' => $company_no,
            'acnt_no' => $acnt_no,
            'delete_flg' => $delete_flg
        ]);

        // データ取得
        islog($this->class_name, __FUNCTION__, 'm10');
        $ret = $this->acnt_repository->getAcntData($company_no, $acnt_no, null, $delete_flg);
        $form_data = [];
        if (! empty($ret)) {
            // 配列化
            $form_data['input_mgr_id'] = $ret->id; // アカウント管理番号
            $form_data['input_cpb_no'] = $ret->comp_branch_no; // 企業支店番号
            $form_data['input_cpb_name'] = $ret->comp_branch_name; // 企業支店名
            $form_data['input_cpd_no'] = $ret->comp_department_no; // 企業部署番号
            $form_data['input_cpd_name'] = $ret->comp_department_name; // 企業部署名
            $form_data['input_no'] = $ret->acnt_no; // アカウント番号
            $form_data['input_sei'] = $ret->acnt_sei; // アカウント氏名 - 姓
            $form_data['input_mei'] = $ret->acnt_mei; // アカウント氏名 - 名
            $form_data['input_name'] = $ret->acnt_name; // アカウント氏名 - 姓名
            $form_data['input_kana_sei'] = $ret->acnt_kana_sei; // アカウント氏名 - カナ姓
            $form_data['input_kana_mei'] = $ret->acnt_kana_mei; // アカウント氏名 - カナ名
            $form_data['input_kananame'] = $ret->acnt_kananame; // アカウント氏名 - カナ姓名
            $form_data['input_id'] = $ret->acnt_id; // アカウントID
            $form_data['input_tel'] = $ret->acnt_tel1; // アカウントTEL1
            $form_data['input_tel1'] = $ret->acnt_tel2; // アカウントTEL2
            $form_data['input_email'] = $ret->email; // アカウントEメール1
            $form_data['input_email1'] = $ret->acnt_email2; // アカウントEメール2
            $form_data['input_posno'] = $ret->acnt_position_no; // アカウント役職番号
            $form_data['input_posother_name'] = $ret->acnt_position_other_name; // アカウント役職その他
            $form_data['input_authno'] = $ret->acnt_auth_no; // アカウント権限番号
            $form_data['input_authother_name'] = $ret->acnt_auth_other_name; // アカウント権限その他
            $form_data['input_zip'] = $ret->acnt_detail_zip_code; // アカウント郵便番号
            $form_data['input_pref_cd'] = $ret->acnt_detail_pref_code; // アカウント都道府県コード
            $form_data['input_pref_name'] = $ret->pref_name; // アカウント都道府県
            $form_data['input_city'] = $ret->acnt_detail_city; // アカウント市区町村
            $form_data['input_town'] = $ret->acnt_detail_town; // アカウント丁目・番地
            $form_data['input_address'] = $ret->acnt_detail_address; // アカウントマンション・アパート
            $form_data['input_posname'] = $ret->position_name; // 役職名
        }

        dslog($this->class_name, __FUNCTION__, 'm2', $form_data);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $form_data;
    }

    /**
     * ログインアカウント情報取得
     * ログインアカウントIDによりアカウント情報を取得
     *
     * @access public
     * @param string $acnt_id
     * @param int $delete_flg
     * @return object
     * @see AcntService
     * @throws
     * @todo
     */
    public function getLoginAcntData($acnt_id, $delete_flg = 0)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'acnt_id' => $acnt_id,
            'delete_flg' => $delete_flg
        ]);

        // データ取得
        islog($this->class_name, __FUNCTION__, 'm10');
        $ret = $this->acnt_repository->getAcntData(null, null, $acnt_id, $delete_flg);

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * ログインアカウント情報を設定
     * セッションへ設定するログインアカウント情報を構築
     *
     * @access public
     * @param object $acnt_data
     * @param array $prev_login_info
     * @param boolean $result_intended_url
     * @return array
     * @see AcntService
     * @throws
     * @todo
     */
    public function setLoginAcntData($acnt_data, $prev_login_info = null, $result_intended_url = false)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'acnt_data' => $acnt_data,
            'prev_login_info' => $prev_login_info,
            'result_intended_url' => $result_intended_url
        ]);

        // データ取得
        islog($this->class_name, __FUNCTION__, 'm28');
        // セッションタイムアウト直前のログイン情報の有無判定
        if ($result_intended_url && ! empty($prev_login_info)) {
            // 前回のログイン情報へ今回のログイン者情報のみ上書きして返す
            $login_data = $prev_login_info;
            // 配列化
            // ログインアカウント
            $login_data['acnt_no'] = $acnt_data->acnt_no;
            $login_data['acnt_name'] = $acnt_data->acnt_name . "（" . $acnt_data->acnt_kananame . "）";
            $login_data['acnt_auth_no'] = $acnt_data->acnt_auth_no;
        } else {
            // 配列化
            // ログインアカウント
            $login_data['acnt_no'] = $acnt_data->acnt_no;
            $login_data['acnt_name'] = $acnt_data->acnt_name . "（" . $acnt_data->acnt_kananame . "）";
            $login_data['acnt_auth_no'] = $acnt_data->acnt_auth_no;
            $login_data['company_type'] = $acnt_data->comp_type; //ログイン者所属企業の種別
            // 選択企業
            $login_data['company_no'] = $acnt_data->comp_no;
            $login_data['company_name'] = $acnt_data->comp_name;
            // 選択支店
            $login_data['company_branch_no'] = $acnt_data->comp_branch_no;
            $login_data['company_branch_name'] = $acnt_data->comp_branch_name;
            // 選択部署
            $login_data['company_department_no'] = $acnt_data->comp_department_no;
            $login_data['company_department_name'] = $acnt_data->comp_department_name;
            // 選択製品
            $login_data['products_no'] = "";
            $login_data['products_name'] = "";
            $login_data['products_detail_no'] = "";
            $login_data['products_detail_name'] = "";
        }

        dslog($this->class_name, __FUNCTION__, 'm2', $login_data);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $login_data;
    }

    /**
     * ログイン情報バリデーション
     * 入力ログイン情報のバリデーション実行
     *
     * @access public
     * @param array $credentials
     * @return object
     * @see AcntService
     * @throws
     * @todo
     */
    public function validationtLoginInfo($credentials)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'credentials' => $credentials,
        ]);

        // バリデート
        $rules = [
            'acnt_id' => 'required|string',
            'password' => 'required|string|confirmed',
            'password_confirmation' => 'required|string'
        ];
        $messages = [
            'acnt_id.required' => 'ログインIDが入力されていません。',
            'acnt_id.string' => 'ログインに失敗しました。２',
            'password.required' => 'パスワードが入力されていません。',
            'password.string' => 'ログインに失敗しました。３',
            'password.confirmed' => 'パスワードがパスワード（確認）と一致しません。',
            'password_confirmation.required' => 'パスワード（確認）が入力されていません。',
            'password_confirmation.string' => 'ログインに失敗しました。４'
        ];
        $validator = Validator::make($credentials, $rules, $messages);

        $ret = true;
        if (! $validator->passes()) {
            // バリデーションエラー
            $errors = $validator->errors();
            $ret_txt = '';
            foreach ($errors->all() as $message) {
                $ret_txt .= $message . '<br />';
            }

            $ret = [
                'result' => false,
                'data' => $ret_txt
            ];
        }

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * アカウント情報登録
     * 入力したアカウント情報を登録
     *
     * @access public
     * @param array $form_data
     * @param string $company_no
     * @param string $acnt_no
     * @return Boolean
     * @see AcntService
     * @throws
     * @todo
     */
    public function insertAcntData($form_data, $company_no, $acnt_no)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'form_data' => $form_data,
            'company_no' => $company_no,
            'acnt_no' => $acnt_no
        ]);

        // 新規登録
        // 登録データを設定（アカウント）
        islog($this->class_name, __FUNCTION__, 'm14');
        $insert_data['comp_no'] = $company_no; // 企業番号
        $insert_data['comp_branch_no'] = $form_data['input_cpb_no']; // 企業支店番号
        $insert_data['comp_department_no'] = $form_data['input_cpd_no']; // 企業部署番号
        $insert_data['acnt_no'] = $form_data['input_no']; // アカウント番号
        $insert_data['acnt_sei'] = $form_data['input_sei']; // アカウント氏名 - 姓
        $insert_data['acnt_mei'] = $form_data['input_mei']; // アカウント氏名 - 名
        $insert_data['acnt_kana_sei'] = $form_data['input_kana_sei']; // アカウント氏名 - カナ姓
        $insert_data['acnt_kana_mei'] = $form_data['input_kana_mei']; // アカウント氏名 - カナ名
        $insert_data['acnt_id'] = $form_data['input_id']; // アカウントID
        $insert_data['password'] = Hash::make($form_data['input_pass']); // アカウントパスワード
        $insert_data['acnt_tel1'] = $form_data['input_tel']; // アカウントTEL1
        $insert_data['acnt_tel2'] = $form_data['input_tel1']; // アカウントTEL2
        $insert_data['email'] = $form_data['input_email']; // アカウントEメール1
        $insert_data['acnt_email2'] = $form_data['input_email1']; // アカウントEメール2
        $insert_data['acnt_position_no'] = (int)$form_data['input_posno']; // アカウント役職番号
        $insert_data['acnt_position_other_name'] = $form_data['input_posother_name']; // アカウント役職その他
        $insert_data['acnt_auth_no'] = $form_data['input_authno']; // アカウント権限番号
        $insert_data['acnt_auth_other_name'] = $form_data['input_authother_name']; // アカウント権限その他
        $insert_data['created_at'] = Carbon::now(); // 登録日
        $insert_data['updated_at'] = Carbon::now(); // 更新日
        $insert_data['last_acnt_no'] = $acnt_no; // 最終更新者NO
        dslog($this->class_name, __FUNCTION__, 'm13', $insert_data);

        // 登録データを設定（アカウント住所）
        islog($this->class_name, __FUNCTION__, 'm14');
        $insert_detail_data['acnt_no'] = $form_data['input_no']; // アカウント番号
        $insert_detail_data['comp_no'] = $company_no; // 企業番号
        $insert_detail_data['acnt_detail_zip_code'] = $form_data['input_zip']; // アカウント郵便番号
        $insert_detail_data['acnt_detail_pref_code'] = (int)$form_data['input_pref_cd']; // アカウント都道府県名コード
        $insert_detail_data['acnt_detail_city'] = $form_data['input_city']; // アカウント市区町村
        $insert_detail_data['acnt_detail_town'] = $form_data['input_town']; // アカウント丁目・番地
        $insert_detail_data['acnt_detail_address'] = $form_data['input_address']; // アカウントマンション・アパート
        $insert_detail_data['created_at'] = Carbon::now(); // 登録日
        $insert_detail_data['updated_at'] = Carbon::now(); // 更新日
        $insert_detail_data['last_acnt_no'] = $acnt_no; // 最終更新者NO
        dslog($this->class_name, __FUNCTION__, 'm13', $insert_detail_data);

        islog($this->class_name, __FUNCTION__, 'm18');
        $ret = $this->acnt_repository->insertAcntData($insert_data, $insert_detail_data);

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * アカウント情報更新
     * 入力したアカウント情報を更新
     *
     * @access public
     * @param array $form_data
     * @param string $update_acnt_no
     * @param string $company_no
     * @param string $acnt_no
     * @return Boolean
     * @see AcntService
     * @throws
     * @todo
     */
    public function updateAcntData($form_data, $update_acnt_no, $company_no, $acnt_no)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'form_data' => $form_data,
            'company_no' => $company_no,
            'update_acnt_no' => $update_acnt_no,
            'acnt_no' => $acnt_no
        ]);

        // 既存更新
        // 更新データを設定（アカウント）
        islog($this->class_name, __FUNCTION__, 'm15');
        $update_data['comp_branch_no'] = $form_data['input_cpb_no']; // 企業支店管理番号
        $update_data['comp_department_no'] = $form_data['input_cpd_no']; // 企業部署番号
        $update_data['acnt_sei'] = $form_data['input_sei']; // アカウント氏名 - 姓
        $update_data['acnt_mei'] = $form_data['input_mei']; // アカウント氏名 - 名
        $update_data['acnt_kana_sei'] = $form_data['input_kana_sei']; // アカウント氏名 - カナ姓
        $update_data['acnt_kana_mei'] = $form_data['input_kana_mei']; // アカウント氏名 - カナ名
        $update_data['password'] = Hash::make($form_data['input_pass']); // アカウントパスワード
        $update_data['acnt_tel1'] = $form_data['input_tel']; // アカウントTEL1
        $update_data['acnt_tel2'] = $form_data['input_tel1']; // アカウントTEL2
        $update_data['email'] = $form_data['input_email']; // アカウントEメール1
        $update_data['acnt_email2'] = $form_data['input_email1']; // アカウントEメール2
        $update_data['acnt_position_no'] = (int)$form_data['input_posno']; // アカウント役職番号
        $update_data['acnt_position_other_name'] = $form_data['input_posother_name']; // アカウント役職その他
        $update_data['acnt_auth_no'] = $form_data['input_authno']; // アカウント権限番号
        $update_data['acnt_auth_other_name'] = $form_data['input_authother_name']; // アカウント権限その他
        $update_data['updated_at'] = Carbon::now(); // 更新日
        $update_data['last_acnt_no'] = $acnt_no; // 最終更新者NO
        dslog($this->class_name, __FUNCTION__, 'm14', $update_data);

        // 更新データを設定（アカウント住所）
        $update_detail_data['acnt_no'] = $form_data['input_no']; // アカウント番号
        $update_detail_data['acnt_detail_zip_code'] = $form_data['input_zip']; // アカウント郵便番号
        $update_detail_data['acnt_detail_pref_code'] = (int)$form_data['input_pref_cd']; // アカウント都道府県名コード
        $update_detail_data['acnt_detail_city'] = $form_data['input_city']; // アカウント市区町村
        $update_detail_data['acnt_detail_town'] = $form_data['input_town']; // アカウント丁目・番地
        $update_detail_data['acnt_detail_address'] = $form_data['input_address']; // アカウントマンション・アパート
        $update_detail_data['updated_at'] = Carbon::now(); // 更新日
        $update_detail_data['last_acnt_no'] = $acnt_no; // 最終更新者NO
        dslog($this->class_name, __FUNCTION__, 'm14', $update_detail_data);

        islog($this->class_name, __FUNCTION__, 'm19');
        $ret = $this->acnt_repository->updateAcntData($update_data, $update_detail_data, $company_no, $update_acnt_no, $form_data['input_id']);

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * アカウント情報削除
     * 選択したアカウント情報を削除
     *
     * @access public
     * @param string $company_no
     * @param array $del_acnt_no
     * @param string $acnt_no
     * @return Boolean
     * @see AcntService
     * @throws
     * @todo
     */
    public function deleteAcntData($company_no, $del_acnt_no, $acnt_no)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'del_acnt_no' => $del_acnt_no,
            'company_no' => $company_no,
            'acnt_no' => $acnt_no
        ]);

        // 削除開始
        islog($this->class_name, __FUNCTION__, 'm20');
        $ret = $this->acnt_repository->deleteAcntData($del_acnt_no, $company_no, $acnt_no);

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }
}
