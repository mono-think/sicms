<?php
/**
 * [DB]position_mテーブルのRepositoryInterfaceクラス
 *
 * position_mテーブルをメインとする検索、登録、更新、削除処理のインタフェースを定義
 *
 * @access public
 * @author Seiji Ishikawa（メールアドレス）
 * @copyright Webじゃ軒 All Rights Reserved
 * @package repository
 */
namespace App\Repositories\Manage;

interface PrefRepositoryInterface
{

    /**
     * 都道府県マスタ情報一覧ページネーション取得インタフェース
     *
     * @access public
     * @param string $free_word
     * @param int $limit
     */
    public function getPrefPagenationList($free_word, $limit = null);

    /**
     * 都道府県マスタ情報一覧取得インタフェース
     *
     * @access public
     * @param string $free_word
     * @param int $start
     * @param int $limit
     */
    public function getPrefList($free_word, $start = 0, $limit = null);

    /**
     * 都道府県マスタ取得インタフェース
     *
     * @access public
     * @param int $position_no
     */
    public function getPrefData($position_no);

    /**
     * [登録]都道府県マスタ情報登録インタフェース
     *
     * @access public
     * @param array $insert_data
     * @todo
     */
    public function insertPrefData($insert_data);

    /**
     * [更新]都道府県マスタ情報更新インタフェース
     *
     * @access public
     * @param array $update_data
     * @param int $position_no
     */
    public function updatePrefData($update_data, $position_no);

    /**
     * [削除]都道府県マスタ情報削除インタフェース
     *
     * @access public
     * @param array $delete_data
     */
    public function deletePrefData($delete_data);
}