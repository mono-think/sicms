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

interface PositionRepositoryInterface
{

    /**
     * 役職マスタ情報一覧ページネーション取得インタフェース
     *
     * @access public
     * @param string $free_word
     * @param int $limit
     */
    public function getPositionPagenationList($free_word, $limit = null);

    /**
     * 役職マスタ情報一覧取得インタフェース
     *
     * @access public
     * @param string $free_word
     * @param int $start
     * @param int $limit
     */
    public function getPositionList($free_word, $start = 0, $limit = null);

    /**
     * 役職マスタ取得インタフェース
     *
     * @access public
     * @param int $position_no
     */
    public function getPositionData($position_no);

    /**
     * [登録]役職マスタ情報登録インタフェース
     *
     * @access public
     * @param array $insert_data
     * @todo
     */
    public function insertPositionData($insert_data);

    /**
     * [更新]役職マスタ情報更新インタフェース
     *
     * @access public
     * @param array $update_data
     * @param int $position_no
     */
    public function updatePositionData($update_data, $position_no);

    /**
     * [削除]役職マスタ情報削除インタフェース
     *
     * @access public
     * @param array $delete_data
     */
    public function deletePositionData($delete_data);
}