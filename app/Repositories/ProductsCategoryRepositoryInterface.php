<?php
namespace App\Repositories;

interface ProductsCategoryRepositoryInterface
{

    /**
     * 製品カテゴリの一覧情報ページネーション取得インタフェース
     *
     * @access public
     * @param string $company_no
     */
    public function getProductsCategoryPagenationList($company_no);

    /**
     * [検索]製品カテゴリ一覧取得インタフェース
     *
     * @access public
     * @param string $company_no
     * @param int $start
     * @param int $limit
     */
    public function getProductsCategoryList($company_no, $start = 0, $limit = null);
}