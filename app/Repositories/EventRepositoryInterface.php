<?php
namespace App\Repositories;

interface EventRepositoryInterface
{

    /**
     * イベントの一覧情報ページネーション取得インタフェース
     *
     * @access public
     * @param string $search_tag
     * @param int $limit
     */
    public function getEventPagenationList($search_tag = null, $limit = null);

    /**
     * [検索]イベント一覧取得
     *
     * 検索条件にマッチした全てのイベント情報を取得
     *
     * @access public
     * @param string $search_tag
     * @param int $start
     * @param int $limit
     */
    public function getEventList($search_tag = null, $start = 0, $limit = null);

    /**
     * イベントの情報取得インタフェース
     *
     * @access public
     * @param string $event_no
     */
    public function getEventData($event_no);

    /**
     * イベント新着情報取得インタフェース
     *
     * @access public
     * @param int $limit
     */
    public function getEventNewUpdateList($limit);
}