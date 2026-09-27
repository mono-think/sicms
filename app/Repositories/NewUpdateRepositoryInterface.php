<?php
namespace App\Repositories;

interface NewUpdateRepositoryInterface
{

    /**
     * 新着情報取得インタフェース
     *
     * @access public
     * @param int $limit
     */
    public function getNewUpdateList($limit);
}