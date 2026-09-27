<?php
/**
 * [DB]comp_branch_tテーブルのRepositoryInterfaceクラス
 *
 * comp_branch_tテーブルをメインとする検索、登録、更新、削除処理のインタフェースを定義
 *
 * @access public
 * @author Seiji Ishikawa（メールアドレス）
 * @copyright Webじゃ軒 All Rights Reserved
 * @package repository
 */
namespace App\Repositories\Manage;

interface CompanyDepartmentRepositoryInterface
{

    /**
     * 企業部署テーブルの一覧情報ページネーション取得インタフェース
     *
     * @access public
     * @param string $company_no
     * @param string $company_branch_no
     * @param string $free_word
     * @param int $limit
     */
    public function getCompanyDepartmentPagenationList($company_no, $company_branch_no, $free_word, $limit = null);

    /**
     * 企業部署テーブルの一覧情報取得インタフェース
     *
     * @access public
     * @param string $company_no
     * @param string $company_branch_no
     * @param string $free_word
     * @param int $start
     * @param int $limit
     */
    public function getCompanyDepartmentList($company_no, $company_branch_no, $free_word, $start = 0, $limit = null);

    /**
     * 企業部署テーブルの情報取得インタフェース
     *
     * @access public
     * @param string $company_no
     * @param string $company_branch_no
     * @param string $company_department_no
     * @param int $delete_flg
     */
    public function getCompanyDepartmentData($company_no, $company_branch_no, $company_department_no, $delete_flg);

    /**
     * 企業部署テーブルへの追加インタフェース
     *
     * @access public
     * @param array $insert_data
     */
    public function insertCompanyDepartmentData($insert_data);

    /**
     * 企業部署テーブルへの更新インタフェース
     *
     * @access public
     * @param array $update_data
     * @param string $company_no
     * @param string $company_branch_no
     * @param string $company_department_no
     */
    public function updateCompanyDepartmentData($update_data, $company_no, $company_branch_no, $company_department_no);

    /**
     * 企業部署テーブルへの削除インタフェース
     *
     * @access public
     * @param array $delete_data
     * @param string $company_no
     * @param string $company_branch_no
     * @param string $acnt_no
     */
    public function deleteCompanyDepartmentData($delete_data, $company_no, $company_branch_no, $acnt_no);
}