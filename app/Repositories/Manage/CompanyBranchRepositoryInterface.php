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

interface CompanyBranchRepositoryInterface
{

    /**
     * 企業支店テーブルの一覧情報ページネーション取得インタフェース
     *
     * @access public
     * @param string $company_no
     * @param string $free_word
     * @param int $limit
     */
    public function getCompanyBranchPagenationList($company_no, $free_word, $limit = null);

    /**
     * 企業支店テーブルの一覧情報取得インタフェース
     *
     * @access public
     * @param string $company_no
     * @param string $free_word
     * @param int $start
     * @param int $limit
     */
    public function getCompanyBranchList($company_no, $free_word, $start = 0, $limit = null);

    /**
     * 企業支店テーブルの情報取得インタフェース
     *
     * @access public
     * @param string $company_no
     * @param string $company_branch_no
     * @param int $delete_flg
     */
    public function getCompanyBranchData($company_no, $company_branch_no, $delete_flg);

    /**
     * 企業支店テーブルへの追加インタフェース
     *
     * @access public
     * @param array $insert_data
     */
    public function insertCompanyBranchData($insert_data);

    /**
     * 企業支店テーブルへの更新インタフェース
     *
     * @access public
     * @param array $update_data
     * @param string $company_no
     * @param string $company_branch_no
     */
    public function updateCompanyBranchData($update_data, $company_no, $company_branch_no);

    /**
     * 企業支店テーブルへの削除インタフェース
     *
     * @access public
     * @param array $delete_data
     * @param string $company_no
     * @param string $acnt_no
     */
    public function deleteCompanyBranchData($delete_data, $company_no, $acnt_no);
}