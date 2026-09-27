<?php
/**
 * [DB]comp_branch_news_tテーブルのRepositoryInterfaceクラス
 *
 * comp_branch_news_tテーブルをメインとする検索、登録、更新、削除処理のインタフェースを定義
 *
 * @access public
 * @author Seiji Ishikawa（メールアドレス）
 * @copyright Webじゃ軒 All Rights Reserved
 * @package repository
 */
namespace App\Repositories\Manage;

interface CompanyBranchNewsRepositoryInterface
{

    /**
     * 企業支店ニューステーブルの一覧情報ページネーション取得インタフェース
     *
     * @access public
     * @param string $company_no
     * @param string $company_branch_no
     * @param string $free_word
     * @param int $limit
     */
    public function getCompanyBranchNewsPagenationList($company_no, $company_branch_no, $free_word, $limit = null);

    /**
     * 企業支店ニューステーブルの情報取得インタフェース
     *
     * @access public
     * @param string $company_no
     * @param string $company_branch_no
     * @param string $company_branch_news_no
     */
    public function getCompanyBranchNewsData($company_no, $company_branch_no, $company_branch_news_no);

    /**
     * 企業支店ニューステーブルへの追加インタフェース
     *
     * @access public
     * @param array $insert_data
     */
    public function insertCompanyBranchNewsData($insert_data);

    /**
     * 企業支店ニューステーブルへの更新インタフェース
     *
     * @access public
     * @param array $update_data
     * @param string $company_no
     * @param string $company_branch_no
     * @param int $company_branch_news_no
     */
    public function updateCompanyBranchNewsData($update_data, $company_no, $company_branch_no, $company_branch_news_no);

    /**
     * 企業支店ニューステーブルへの削除インタフェース
     *
     * @access public
     * @param array $delete_data
     * @param string $company_no
     * @param string $company_branch_no
     */
    public function deleteCompanyBranchNewsData($delete_data, $company_no, $company_branch_no);
}