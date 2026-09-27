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
namespace App\Repositories\Manage;

interface MessageBordRepositoryInterface
{

    /**
     * メッセージボード情報一覧ページネーション取得インタフェース
     *
     * @access public
     * @param string $free_word
     * @param int $limit
     */
    public function getMessageBordPagenationList($free_word, $limit = null);

    /**
     * メッセージボード情報の削除インタフェース
     *
     * @access public
     * @param array $delete_data
     * @param string $acnt_no
     */
    public function deleteMessageBordData($delete_data);
}