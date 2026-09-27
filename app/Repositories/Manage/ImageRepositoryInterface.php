<?php
/**
 * [DB]image_tテーブルのRepositoryInterfaceクラス
 *
 * image_tテーブルをメインとする検索、登録、更新、削除処理のインタフェースを定義
 *
 * @access public
 * @author Seiji Ishikawa（メールアドレス）
 * @copyright Webじゃ軒 All Rights Reserved
 * @package repository
 */
namespace App\Repositories\Manage;

interface ImageRepositoryInterface
{
    
    /**
     * [検索]画像情報ページネーション一覧取得インタフェース
     *
     * @access public
     * @param string $company_no
     * @param string $free_word
     * @param int $limit
     */
    public function getImagePagenationList($company_no, $free_word, $limit = null);

    /**
     * 画像テーブルの一覧情報取得インタフェース
     *
     * @access public
     * @param string $company_no
     * @param int $image_category
     * @param string $free_word
     * @param int $start
     */
    public function getImageList($company_no, $image_category, $free_word, $start = 0, $limit = null);

    /**
     * 画像テーブルへの追加インタフェース
     *
     * @access public
     * @param array $insert_data
     */
    public function insertImageData($insert_data);

    /**
     * 画像テーブルへの情報更新インタフェース
     *
     * @access public
     * @param array $update_data
     * @param int $image_no
     */
    public function updateImageData($update_data, $image_no);

    /**
     * 画像テーブルへの削除インタフェース
     *
     * @access public
     * @param array $delete_data
     * @param string $company_no
     */
    public function deleteImageData($delete_data, $company_no);
}