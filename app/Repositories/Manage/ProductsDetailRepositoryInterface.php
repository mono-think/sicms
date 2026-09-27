<?php
namespace App\Repositories\Manage;

interface ProductsDetailRepositoryInterface
{
    
    /**
     * 製品詳細の一覧情報ページネーション取得インタフェース
     *
     * @access public
     * @param string $company_no
     * @param string $products_no
     * @param string $free_word
     * @param int $limit
     */
    public function getProductsDetailPagenationList($company_no, $products_no, $free_word, $limit = null);

    /**
     * 製品詳細の情報取得インタフェース
     *
     * @access public
     * @param string $company_no
     * @param string $products_no
     * @param string $products_detail_no
     */
    public function getProductsDetailData($company_no, $products_no, $products_detail_no);

    /**
     * 製品詳細の情報登録インタフェース
     *
     * @access public
     * @param array $insert_data
     */
    public function insertProductsDetailData($insert_data);

    /**
     * 製品詳細の情報更新インタフェース
     *
     * @access public
     * @param array $update_data
     * @param string $products_no
     * @param string $products_detail_no
     */
    public function updateProductsDetailData($update_data, $company_no, $products_no, $products_detail_no);

    /**
     * 製品詳細の情報削除インタフェース
     *
     * @access public
     * @param array $delete_data
     * @param string $products_no
     * @param string $company_no
     */
    public function deleteProductsDetailData($delete_data, $products_no, $company_no);
}