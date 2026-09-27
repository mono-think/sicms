<?php
namespace App\Repositories\Manage;

interface ProductsCategoryRepositoryInterface
{

    /**
     * 製品カテゴリの一覧情報ページネーション取得インタフェース
     *
     * @access public
     * @param string $free_word
     * @param int $limit
     */
    public function getProductsCategoryPagenationList($free_word, $limit = null);

    /**
     * 製品カテゴリの一覧情報取得インタフェース
     *
     * @access public
     * @param string $free_word
     * @param int $start
     * @param int $limit
     */
    public function getProductsCategoryList($free_word, $start = 0, $limit = null);

    /**
     * 製品カテゴリの情報取得インタフェース
     *
     * @access public
     * @param string $products_category_no
     */
    public function getProductsCategoryData($products_category_no);

    /**
     * 製品カテゴリの情報登録インタフェース
     *
     * @access public
     * @param array $insert_data
     */
    public function insertProductsCategoryData($insert_data);

    /**
     * 製品カテゴリの情報更新インタフェース
     *
     * @access public
     * @param array $update_data
     * @param int $products_category_no
     */
    public function updateProductsCategoryData($update_data, $products_category_no);

    /**
     * 製品カテゴリの情報削除インタフェース
     *
     * @access public
     * @param array $delete_data
     */
    public function deleteProductsCategoryData($delete_data);
}