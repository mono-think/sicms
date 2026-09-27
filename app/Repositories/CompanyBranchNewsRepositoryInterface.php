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
namespace App\Repositories;

interface CompanyBranchNewsRepositoryInterface
{

    /**
     * 企業支店ニューステーブルの一覧ページネーション取得インタフェース
     *
     * @access public
     * @param string $company_no
     * @param int $limit
     */
    public function getCompanyBranchNewsPagenationList($company_no, $limit = null);

    /**
     * 企業支店ニューステーブルの一覧情報取得インタフェース（条件指定）
     *
     * @access public
     * @param string $company_no
     * @param int $start
     * @param int $limit
     */
    public function getCompanyBranchNewsList($company_no, $start = 0, $limit = null);

    /**
     * 企業支店ニューステーブルの情報取得インタフェース
     *
     * @access public
     * @param string $company_no
     * @param int $company_branch_news_no
     * @param string $company_branch_no
     */
    public function getCompanyBranchNewsData($company_no, $company_branch_news_no, $company_branch_no);
}