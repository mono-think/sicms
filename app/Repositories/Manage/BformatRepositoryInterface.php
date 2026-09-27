<?php
/**
 * [DB]bformat_mテーブルのRepositoryInterfaceクラス
 *
 * bformat_mテーブルをメインとする検索、登録、更新、削除処理のインタフェースを定義
 *
 * @access public
 * @author Seiji Ishikawa（メールアドレス）
 * @copyright Webじゃ軒 All Rights Reserved
 * @package repository
 */
namespace App\Repositories\Manage;

interface BformatRepositoryInterface
{

    /**
     * [検索]業態マスタ情報ページネーション一覧取得インタフェース
     *
     * @access public
     * @param string $free_word
     * @param int $limit
     */
    public function getBfromatPagenationList($free_word, $limit = null);

    /**
     * 業態マスタ情報一覧取得インタフェース
     *
     * @access public
     * @param string $free_word
     * @param int $start
     * @param int $limit
     */
    public function getBfromatList($free_word, $start = 0, $limit = null);

    /**
     * 業態マスタ取得インタフェース
     *
     * @access public
     * @param int $bformat_no
     */
    public function getBformatData($bformat_no);

    /**
     * [登録]業態マスタ情報登録インタフェース
     *
     * @access public
     * @param array $insert_data
     * @todo
     */
    public function insertBformatData($insert_data);

    /**
     * [更新]業態マスタ情報更新インタフェース
     *
     * @access public
     * @param array $update_data
     * @param int $bformat_no
     */
    public function updateBformatData($update_data, $bformat_no);

    /**
     * [削除]業態マスタ情報削除インタフェース
     *
     * @access public
     * @param array $delete_data
     */
    public function deleteBformatData($delete_data);
}