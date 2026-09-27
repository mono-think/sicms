<?php
namespace App\Repositories;

interface ContentRepositoryInterface
{

    /**
     * コンテンツの一覧情報ページネーション取得インタフェース
     *
     * @access public
     * @param int $type
     * @param string $search_tag
     * @param int $limit
     */
    public function getContentPagenationList($type, $search_tag = null, $limit = null);

    
    /**
     * [検索]コンテンツ一覧取得インタフェース
     *
     * @access public
     * @param int $type
     * @param string $search_tag
     * @param int $start
     * @param int $limit
     */
    public function getContentList($type, $search_tag = null, $start = 0, $limit = null);

    /**
     * コンテンツの情報取得インタフェース
     *
     * @access public
     * @param int $type
     * @param int $contents_no
     */
    public function getContentData($type, $contents_no);

    /**
     * コンテンツ新着情報取得インタフェース
     *
     * @access public
     * @param int $type
     * @param int $limit
     */
    public function getContentNewUpdateList($type, $limit);
}