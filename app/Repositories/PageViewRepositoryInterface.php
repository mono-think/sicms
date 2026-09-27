<?php
/**
 * [DB]pageview_tテーブルのRepositoryInterfaceクラス
 *
 * pageview_tテーブルをメインとする検索、登録、更新、削除処理のインタフェースを定義
 *
 * @access public
 * @author Seiji Ishikawa（メールアドレス）
 * @copyright Webじゃ軒 All Rights Reserved
 * @package repository
 */
namespace App\Repositories;

interface PageViewRepositoryInterface
{

    /**
     * ページビュー情報一覧取得インタフェース
     *
     * @access public
     * @param int $number
     */
    public function getPageViewList($start = 0, $limit = null);
    
    /**
     * ページビュー情報ページビュー情報登録/更新インタフェース
     *
     * @access public
     * @param array $upsert_data
     * @param array $update_data
     */
    public function upsertPageViewData($upsert_data, $update_data);
}