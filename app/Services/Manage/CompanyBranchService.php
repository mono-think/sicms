<?php
/**
 * 企業支店情報処理のServiceクラス
 * 検索、登録、更新、削除処理
 *
 * @access public
 * @author Seiji Ishikawa（メールアドレス）
 * @copyright Webじゃ軒 All Rights Reserved
 * @package service
 */
namespace App\Services\Manage;

use App\Repositories\Manage\CompanyBranchRepositoryInterface;
use Carbon\Carbon;

class CompanyBranchService
{

    protected $company_branch_repository;

    protected $class_name;

    /**
     * コンストラクタ
     *
     * @access public
     * @param CompanyBranchRepositoryInterface $company_repository
     *
     */
    public function __construct(CompanyBranchRepositoryInterface $company_branch_repository)
    {
        $this->company_branch_repository = $company_branch_repository;
        $this->class_name = get_class($this);
        islog($this->class_name, __FUNCTION__);
    }

    /**
     * 企業支店情報一覧ページネーション取得
     * 検索条件により全ての企業支店情報を取得
     *
     * @access public
     * @param string $company_no
     * @param string $free_word
     * @return \Illuminate\Database\Eloquent\Collection
     * @see CompanyBranchService
     * @throws
     * @todo
     */
    public function getCompanyBranchPagenationList($company_no, $free_word)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'company_no' => $company_no,
            'free_word' => $free_word
        ]);

        // データ取得
        islog($this->class_name, __FUNCTION__, 'm10');
        $free_word = hscQuotes($free_word); //HTMLエンティティ変換
        $ret = $this->company_branch_repository->getCompanyBranchPagenationList($company_no, $free_word);

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * 企業支店情報取得
     * 企業支店管理番号により企業支店情報を取得
     *
     * @access public
     * @param string $company_no
     * @param string $company_branch_no
     * @param int $delete_flg
     * @return array
     * @see CompanyBranchService
     * @throws
     * @todo
     */
    public function getCompanyBranchData($company_no, $company_branch_no, $delete_flg = 0)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'company_no' => $company_no,
            'company_branch_no' => $company_branch_no,
            'delete_flg' => $delete_flg
        ]);

        // データ取得
        islog($this->class_name, __FUNCTION__, 'm10');
        $ret = $this->company_branch_repository->getCompanyBranchData($company_no, $company_branch_no, $delete_flg);
        $form_data = [];
        if (! empty($ret)) {
            // 配列化
            $form_data['input_no'] = $ret->comp_branch_no; // 企業支店番号
            $form_data['input_name'] = $ret->comp_branch_name; // 支店名
            $form_data['input_zip'] = $ret->comp_branch_zip_code; // 郵便番号
            $form_data['input_pref'] = $ret->pref_name; // 住所 - 都道府県
            $form_data['input_pref_cd'] = $ret->comp_branch_pref_code; // 住所 - 都道府県コード
            $form_data['input_city'] = $ret->comp_branch_city; // 住所 - 市区町村
            $form_data['input_town'] = $ret->comp_branch_town; // 住所 - 町・丁目・番地
            $form_data['input_address'] = $ret->comp_branch_address; // 住所 - その他
            $form_data['input_order'] = $ret->comp_branch_order; // 支店順
            $form_data['input_suppoter'] = $ret->suppoter_flg; // 協賛フラグ
        }

        dslog($this->class_name, __FUNCTION__, 'm2', $form_data);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $form_data;
    }

    /**
     * 企業支店情報登録
     * 入力した企業支店情報を登録
     *
     * @access public
     * @param array $form_data
     * @param string $company_no
     * @param string $acnt_no
     * @return Boolean
     * @see CompanyBranchService
     * @throws
     * @todo
     */
    public function insertCompanyBranchData($form_data, $company_no, $acnt_no)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'form_data' => $form_data,
            'company_no' => $company_no,
            'acnt_no' => $acnt_no
        ]);

        // 新規登録
        // 登録データを設定
        islog($this->class_name, __FUNCTION__, 'm14');
        $insert_data['comp_branch_no'] = $form_data['input_no']; // 企業支店番号
        $insert_data['comp_no'] = $company_no; // 企業管理番号
        $insert_data['comp_branch_name'] = $form_data['input_name']; // 支店名
        $insert_data['comp_branch_zip_code'] = $form_data['input_zip']; // 郵便番号
        $insert_data['comp_branch_pref_code'] = (int)$form_data['input_pref_cd']; // 住所 - 都道府県コード
        $insert_data['comp_branch_city'] = $form_data['input_city']; // 住所 - 市区町村
        $insert_data['comp_branch_town'] = $form_data['input_town']; // 住所 - 町・丁目・番地
        $insert_data['comp_branch_address'] = $form_data['input_address']; // 住所 - その他
        $insert_data['comp_branch_order'] = (int)$form_data['input_order']; // 支店順
        $insert_data['suppoter_flg'] = (int)$form_data['input_suppoter']; // 協賛フラグ
        $insert_data['created_at'] = Carbon::now(); // 登録日
        $insert_data['updated_at'] = Carbon::now(); // 更新日
        $insert_data['last_acnt_no'] = $acnt_no; // 最終更新者NO
        dslog($this->class_name, __FUNCTION__, 'm13', $insert_data);

        islog($this->class_name, __FUNCTION__, 'm18');
        $ret = $this->company_branch_repository->insertCompanyBranchData($insert_data);

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * 企業支店情報更新
     * 入力した企業支店情報を更新
     *
     * @access public
     * @param array $form_data
     * @param string $input_no
     * @param string $company_no
     * @param string $acnt_no
     * @return Boolean
     * @see CompanyBranchService
     * @throws
     * @todo
     */
    public function updateCompanyBranchData($form_data, $input_no, $company_no, $acnt_no)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'form_data' => $form_data,
            'input_no' => $input_no,
            'company_no' => $company_no,
            'acnt_no' => $acnt_no
        ]);

        // 既存更新
        // 更新データを設定
        islog($this->class_name, __FUNCTION__, 'm15');
        $update_data['comp_branch_name'] = $form_data['input_name']; // 支店名
        $update_data['comp_branch_zip_code'] = $form_data['input_zip']; // 郵便番号
        $update_data['comp_branch_pref_code'] = (int)$form_data['input_pref_cd']; // 住所 - 都道府県コード
        $update_data['comp_branch_city'] = $form_data['input_city']; // 住所 - 市区町村
        $update_data['comp_branch_town'] = $form_data['input_town']; // 住所 - 町・丁目・番地
        $update_data['comp_branch_address'] = $form_data['input_address']; // 住所 - その他
        $update_data['comp_branch_order'] = (int)$form_data['input_order']; // 支店順
        $update_data['suppoter_flg'] = (int)$form_data['input_suppoter']; // 協賛フラグ
        $update_data['updated_at'] = Carbon::now(); // 更新日
        $update_data['last_acnt_no'] = $acnt_no; // 最終更新者NO
        dslog($this->class_name, __FUNCTION__, 'm14', $update_data);

        islog($this->class_name, __FUNCTION__, 'm19');
        $ret = $this->company_branch_repository->updateCompanyBranchData($update_data, $company_no, $input_no);

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * 企業支店情報削除
     * 選択した企業支店情報を削除
     *
     * @access public
     * @param string $company_no
     * @param array $company_branch_no
     * @param string $acnt_no
     * @return Boolean
     * @see CompanyBranchService
     * @throws
     * @todo
     */
    public function deleteCompanyBranchData($company_no, $company_branch_no, $acnt_no)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'company_no' => $company_no,
            'company_branch_no' => $company_branch_no,
            'acnt_no' => $acnt_no
        ]);

        // 削除開始
        islog($this->class_name, __FUNCTION__, 'm20');
        $ret = $this->company_branch_repository->deleteCompanyBranchData($company_branch_no, $company_no, $acnt_no);

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * ログイン情報設定
     * 選択した企業支店情報をログイン情報として設定
     *
     * @access public
     * @param array $login_info
     * @param array $form_data
     * @return array
     * @see CompanyBranchService
     * @throws
     * @todo
     */
    public function setCompanyBranchLoginInfo($login_info, $form_data)
    {
        $login_info['company_branch_no'] = $form_data['input_no'];
        $login_info['company_branch_name'] = $form_data['input_name'];
        $login_info['company_department_no'] = '';
        $login_info['company_department_name'] = '';

        return $login_info;
    }
}
