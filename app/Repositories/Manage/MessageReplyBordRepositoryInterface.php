<?php
/**
 * [DB]message_reply_bord_tテーブルのRepositoryInterfaceクラス
 *
 * message_reply_bord_tテーブルをメインとする検索、登録、更新、削除処理のインタフェースを定義
 *
 * @access public
 * @author Seiji Ishikawa（メールアドレス）
 * @copyright Webじゃ軒 All Rights Reserved
 * @package repository
 */
namespace App\Repositories\Manage;

interface MessageReplyBordRepositoryInterface
{

    /**
     * メッセージ返信ボード情報一覧取得インタフェース
     *
     * @access public
     * @param array $message_bord_no
     * @param int $start
     * @param int $limit
     */
    public function getMessageReplyBordList($message_bord_no, $start = 0, $limit = null);

    /**
     * [検索]メッセージ返信ボード情報取得インタフェース
     *
     * @access public
     * @param int $message_reply_bord_no
     */
    public function getMessageReplyBordData($message_reply_bord_no);

    /**
     * [登録]メッセージ返信ボード情報情報登録インタフェース
     *
     * @access public
     * @param array $insert_data
     * @todo
     */
    public function insertMessageReplyBordData($insert_data);

    /**
     * [更新]メッセージ返信ボード情報更新インタフェース
     *
     * @access public
     * @param array $update_data
     * @param int $message_reply_bord_no
     * @todo
     */
    public function updateMessageReplyBordData($update_data, $message_reply_bord_no);

    /**
     * [削除]メッセージ返信ボード情報情報削除インタフェース
     *
     * @access public
     * @param array $delete_data
     * @param array $message_bord_no
     */
    public function deleteMessageReplyBordData($delete_data, $message_bord_no);
}