<?php
namespace App\Repositories;

interface ProductsRepositoryInterface
{

    /**
     * 製品新着情報取得インタフェース
     *
     * @access public
     * @param int $limit
     */
    public function getProductsNewUpdateList($limit);

    /**
     * 製品詳細情報も含めた最新の製品一覧ページネーション取得インタフェース
     *
     * @access public
     * @param string $products_category_no
     * @param string $company_no
     * @param string $search_tag
     * @param int $limit
     */
    public function getProductsAndDetailNewPagenationList($products_category_no, $company_no, $search_tag = null, $limit = null);

    /**
     * [検索]製品詳細情報も含めた最新の製品一覧取得インタフェース
     *
     * @access public
     * @param string $products_category_no
     * @param string $company_no
     * @param string $search_tag
     * @param int $start
     * @param int $limit
     */
    public function getProductsAndDetailNewList($products_category_no, $company_no, $search_tag = null, $start = 0, $limit = null);

    
    /**
     * [検索]製品詳細情報リスト取得
     *
     * 製品番号により製品詳細情報を取得
     *
     * @access public
     * @param string $products_category_no
     * @param string $products_no
     * @param string $company_no;
     */
    public function getProductsList($products_category_no, $products_no, $company_no, $start = 0, $limit = null);

    /**
     * 製品詳細の情報取得インタフェース
     *
     * @access public
     * @param string $products_category_no
     * @param string $products_no
     * @param string $products_detail_no
     * @param string $company_no;
     */
    public function getProductsDetailData($products_category_no, $products_no, $products_detail_no, $company_no);
}