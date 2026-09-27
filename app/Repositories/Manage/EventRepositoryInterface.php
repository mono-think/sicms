<?php
namespace App\Repositories\Manage;

interface EventRepositoryInterface
{

    /**
     * イベント情報一覧ページネーション取得インタフェース
     *
     * @access public
     * @param string $free_word
     * @param int $limit
     */
    public function getEventPagenationList($free_word, $limit = null);

    /**
     * イベントの情報取得インタフェース
     *
     * @access public
     * @param string $event_no
     */
    public function getEventData($event_no);

    /**
     * イベントの情報登録インタフェース
     *
     * @access public
     * @param array $insert_data
     */
    public function insertEventData($insert_data);

    /**
     * イベントの情報更新インタフェース
     *
     * @access public
     * @param array $update_data
     * @param string $event_no
     */
    public function updateEventData($update_data, $event_no);

    /**
     * イベントの情報削除インタフェース
     *
     * @access public
     * @param array $delete_data
     */
    public function deleteEventData($delete_data);
}