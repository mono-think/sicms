<?php
namespace App\Repositories\Manage;

interface ProductsReviewRepositoryInterface
{

    /**
     * 製品レビューの一覧情報ページネーション取得インタフェース
     *
     * @access public
     * @param string $company_no
     * @param string $products_no
     * @param string $products_detail_no
     * @param string $free_word
     * @param int $limit
     */
    public function getProductsReviewPagenationList($company_no, $products_no, $products_detail_no, $free_word, $limit = null);

    /**
     * 製品レビューの情報取得インタフェース
     *
     * @access public
     * @param string $company_no
     * @param string $products_no
     * @param string $products_detail_no
     * @param string $products_review_no
     */
    public function getProductsReviewData($company_no, $products_no, $products_detail_no, $products_review_no);

    /**
     * 製品レビューの情報登録インタフェース
     *
     * @access public
     * @param array $insert_data
     */
    public function insertProductsReviewData($insert_data);

    /**
     * 製品レビューの情報更新インタフェース
     *
     * @access public
     * @param array $update_data
     * @param string $company_no
     * @param string $products_no
     * @param string $products_detail_no
     * @param string $products_review_no
     */
    public function updateProductsReviewData($update_data, $company_no, $products_no, $products_detail_no, $products_review_no);

    /**
     * 製品レビューの情報削除インタフェース
     *
     * @access public
     * @param array $delete_data
     * @param string $products_no
     * @param string $products_detail_no
     * @param string $company_no
     */
    public function deleteProductsReviewData($delete_data, $products_no, $products_detail_no, $company_no);
}