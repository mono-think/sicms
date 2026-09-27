<?php
/**
 * [DB]industry_mテーブルのRepositoryInterfaceクラス
 *
 * industry_mテーブルをメインとする検索、登録、更新、削除処理のインタフェースを定義
 *
 * @access public
 * @author Seiji Ishikawa（メールアドレス）
 * @copyright Webじゃ軒 All Rights Reserved
 * @package repository
 */
namespace App\Repositories\Manage;

interface IndustryRepositoryInterface
{

    /**
     * [検索]業種マスタ情報ページネーション一覧取得インタフェース
     *
     * @access public
     * @param string $free_word
     * @param int $limit
     */
    public function getIndustryPagenationList($free_word, $limit = null);

    /**
     * [検索]業種マスタ情報一覧取得インタフェース
     *
     * @access public
     * @param string $free_word
     * @param int $start
     * @param int $limit
     */
    public function getIndustryList($free_word, $start = 0, $limit = null);

    /**
     * 業種マスタ取得インタフェース
     *
     * @access public
     * @param int $industry_no
     */
    public function getIndustryData($industry_no);

    /**
     * [登録]業種マスタ情報登録インタフェース
     *
     * @access public
     * @param array $insert_data
     */
    public function insertIndustryData($insert_data);

    /**
     * [更新]業種マスタ情報更新インタフェース
     *
     * @access public
     * @param array $update_data
     * @param int $industry_no
     */
    public function updateIndustryData($update_data, $industry_no);

    /**
     * [削除]業種マスタ情報削除インタフェース
     *
     * @access public
     * @param array $delete_data
     */
    public function deleteIndustryData($delete_data);
}