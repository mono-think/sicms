<?php
/**
 * [DB]comp_tテーブルのRepositoryInterfaceクラス
 *
 * comp_tテーブルをメインとする検索、登録、更新、削除処理のインタフェースを定義
 *
 * @access public
 * @author Seiji Ishikawa（メールアドレス）
 * @copyright Webじゃ軒 All Rights Reserved
 * @package repository
 */
namespace App\Repositories;

interface CompanyRepositoryInterface
{

    /**
     * 企業テーブルの一覧情報ページネーション取得インタフェース
     *
     * @access public
     * @param $search_tag
     * @param $limit
     */
    public function getCompanyPagenationList($search_tag = null, $limit = null);
    
    /**
     * [検索]企業情報一覧取得インタフェース
     *
     * @access public
     * @param string $search_tag
     * @param int $start
     * @param int $limit
     */
    public function getCompanyList($search_tag = null, $start = 0, $limit = null);

    /**
     * 企業テーブルの情報取得インタフェース
     *
     * @access public
     * @param string $company_no
     */
    public function getCompanyData($company_no);

    /**
     * 企業新着情報取得インタフェース
     *
     * @access public
     * @param int $limit
     */
    public function getCompanyNewUpdateList($limit);
}