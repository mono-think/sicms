<?php
/**
 * [DB]pageview_tテーブルのRepositoryクラス
 *
 * bformat_mテーブルをメインとする検索、登録、更新、削除処理
 *
 * @access public
 * @author Seiji Ishikawa（メールアドレス）
 * @copyright Webじゃ軒 All Rights Reserved
 * @package repository
 */
namespace App\Repositories;

use App\Models\PageView;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class PageViewRepository implements PageViewRepositoryInterface
{

    protected $pageview_master;

    protected $class_name;

    /**
     * コンストラクタ
     *
     * @access public
     * @param PageView $page_view
     */
    public function __construct(PageView $page_view)
    {
        $this->pageview_master = $page_view;
        $this->class_name = get_class($this);
        islog($this->class_name, __FUNCTION__);
    }

    /**
     * [検索]ページビュー情報一覧取得
     *
     * 検索条件にマッチした全てのページビュー情報を取得
     *
     * @access private
     * @return \Illuminate\Database\Eloquent\Collection
     * @see PageViewRepository
     * @throws QueryException
     * @todo
     */
    private function getPageViewCollection()
    {
        islog($this->class_name, __FUNCTION__, 'm1');

        // データ取得
        islog($this->class_name, __FUNCTION__, 'm2');
        DB::enableQueryLog();
        $ret = DB::table('pageview_t')->select('pageview_url', 'title');

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * [検索]ページビュー情報一覧取得
     *
     * 検索条件にマッチした全てのページビュー情報を取得
     *
     * @access public
     * @param int $start
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     * @see PageViewRepository
     * @throws QueryException
     * @todo
     */
    public function getPageViewList($start = 0, $limit = null)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'start' => $start,
            'limit' => $limit
        ]);

        // ページビュー一覧のコレクションのみ取得
        $ret_collection = $this->getPageViewCollection();
        if (is_null($limit)) {
            // 全件取得で確定
            $ret = $ret_collection->orderBy('pageview_count', 'desc')->get();
        } else {
            // 指定開始位置から指定件数取得で確定
            $ret = $ret_collection->orderBy('pageview_count', 'desc')
                ->offset($start)
                ->limit($limit)
                ->get();
        }
        dslog($this->class_name, __FUNCTION__, 'm22', DB::getQueryLog());

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * [登録/更新]ページビュー情報登録/更新
     *
     * 指定のページビュー情報を登録/更新
     *
     * @access public
     * @param array $upsert_data
     * @param array $update_data
     * @return Boolean $ret
     * @see PageViewRepository
     * @throws QueryException
     * @todo
     */
    public function upsertPageViewData($upsert_data, $update_data)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'upsert_data' => $upsert_data,
            'update_data' => $update_data
        ]);

        islog($this->class_name, __FUNCTION__, 'm4');
        DB::beginTransaction();
        // 条件にマッチするレコードがあれば更新
        islog($this->class_name, __FUNCTION__, 'm6');
        $update_data['pageview_count'] = DB::raw('pageview_count + 1');
        DB::enableQueryLog();
        $ret = $this->pageview_master->updateOrInsert($upsert_data, $update_data);
        dslog($this->class_name, __FUNCTION__, 'm22', DB::getQueryLog());

        if (empty($ret)) {
            // 登録失敗
            eslog($this->class_name, __FUNCTION__, 'm1');
            islog($this->class_name, __FUNCTION__, 'm8');
            DB::rollBack();
        } else {
            // 更新完了
            islog($this->class_name, __FUNCTION__, 'm9');
            DB::commit();
        }

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }
}