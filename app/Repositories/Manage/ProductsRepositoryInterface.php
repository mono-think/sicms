<?php
namespace App\Repositories\Manage;

interface ProductsRepositoryInterface
{

    /**
     * 製品の一覧情報ページネーション取得インタフェース
     *
     * @access public
     * @param string $company_no
     * @param string $free_word
     * @param int $limit
     */
    public function getProductsPagenationList($company_no, $free_word, $limit = null);

    /**
     * 製品の情報取得インタフェース
     *
     * @access public
     * @param string $company_no
     * @param string $products_no
     */
    public function getProductsData($company_no, $products_no);

    /**
     * 製品の情報登録インタフェース
     *
     * @access public
     * @param array $insert_data
     */
    public function insertProductsData($insert_data);

    /**
     * 製品の情報更新インタフェース
     *
     * @access public
     * @param array $update_data
     * @param string $company_no
     * @param string $products_no
     */
    public function updateProductsData($update_data, $company_no, $products_no);

    /**
     * 製品の情報削除インタフェース
     *
     * @access public
     * @param array $delete_data
     * @param string $company_no
     */
    public function deleteProductsData($delete_data, $company_no);
}