<?php
/**
 * [DB]message_bord_tテーブルのRepositoryInterfaceクラス
 *
 * message_bord_tテーブルをメインとする検索、登録、更新、削除処理のインタフェースを定義
 *
 * @access public
 * @author Seiji Ishikawa（メールアドレス）
 * @copyright Webじゃ軒 All Rights Reserved
 * @package repository
 */
namespace App\Repositories;

interface MessageBordRepositoryInterface
{
    /**
     * メッセージボード情報一覧取得インタフェース
     *
     * @access public
     * @param int $limit
     */
    public function getMessageBordPagenationList($limit = null);

    /**
     * メッセージボード情報一覧取得インタフェース（条件指定）
     *
     * @access public
     * @param string $company_no
     * @param int $start
     * @param int $limit
     */
    public function getMessageBordList($start = 0, $limit = null);

    /**
     * [登録]メッセージボード情報情報登録インタフェース
     *
     * @access public
     * @param array $insert_data
     * @todo
     */
    public function insertMessageBordData($insert_data);

    /**
     * [更新]メッセージボード情報更新インタフェース
     *
     * @access public
     * @param array $update_data
     * @param string $message_bord_auth_no
     * @param int $message_bord_no
     * @todo
     */
    public function updateMessageBordData($update_data, $message_bord_auth_no, $message_bord_no);

    /**
     * [削除]メッセージボード情報削除インタフェース
     *
     * @access public
     * @param int $delete_data
     * @param string $message_bord_auth_no
     */
    public function deleteMessageBordData($delete_data, $message_bord_auth_no);
}