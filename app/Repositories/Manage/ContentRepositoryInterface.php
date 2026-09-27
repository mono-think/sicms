<?php
namespace App\Repositories\Manage;

interface ContentRepositoryInterface
{

    /**
     * コンテンツ情報一覧ページネーション取得インタフェース
     *
     * @access public
     * @param string $free_word
     * @param int $limit
     */
    public function getContentPagenationList($free_word, $limit = null);

    /**
     * コンテンツの情報取得インタフェース
     *
     * @access public
     * @param int $content_no
     */
    public function getContentData($content_no);

    /**
     * コンテンツの情報登録インタフェース
     *
     * @access public
     * @param array $insert_data
     */
    public function insertContentData($insert_data);

    /**
     * コンテンツの情報更新インタフェース
     *
     * @access public
     * @param array $update_data
     * @param int $content_no
     */
    public function updateContentData($update_data, $content_no);

    /**
     * コンテンツの情報削除インタフェース
     *
     * @access public
     * @param array $delete_data
     */
    public function deleteContentData($delete_data);
}