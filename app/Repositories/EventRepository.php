<?php
/**
 * [DB]event_tテーブルのRepositoryクラス
 *
 * event_tテーブルをメインとするイベント情報の検索、登録、更新、削除処理
 *
 * @access public
 * @author Seiji Ishikawa（メールアドレス）
 * @copyright Webじゃ軒 All Rights Reserved
 * @package repository
 */
namespace App\Repositories;

use App\Models\Event;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Carbon\Carbon;

class EventRepository implements EventRepositoryInterface
{

    protected $event_obj;

    protected $class_name;

    /**
     * コンストラクタ
     *
     * @access public
     * @param Event $event
     */
    public function __construct(Event $event)
    {
        $this->event_obj = $event;
        $this->class_name = get_class($this);
        islog($this->class_name, __FUNCTION__);
    }

    /**
     * [検索]イベント情報コレクション取得
     *
     * 検索条件にマッチした全てのイベント情報を取得
     *
     * @access private
     * @param string $search_tag
     * @return \Illuminate\Database\Eloquent\Collection
     * @see EventRepository
     * @throws QueryException
     * @todo
     */
    private function getEventCollection($search_tag = null)
    {
        islog($this->class_name, __FUNCTION__, 'm1');

        // イベント一覧情報取得
        islog($this->class_name, __FUNCTION__, 'm2');
        $cb = new Carbon(); // 現在日時
        $now_datetime = $cb->format('Y-m-d H:i:s');
        DB::enableQueryLog();
        $ret = DB::table('event_t as e_t')->select('e_t.event_mgr_no', 'e_t.event_name', 'e_t.event_description', 'e_t.event_start_date', 'e_t.event_stage', 'e_t.event_weather_type', 'e_t.event_organizer', 'e_t.event_text', 'e_t.event_image_no', 'e_t.search_tag', 'e_t.updated_at', 'i_t.image_category', 'i_t.image_name', 'i_t.image_folder', 'i_t.image_type', 'i_t.image_alt', 'i_t.image_text')
            ->leftjoin('image_t as i_t', function ($join) {
            $join->where('i_t.image_category', getImageEventCategory())
                ->on('e_t.event_image_no', 'i_t.image_no');
        })
            ->whereDate('e_t.release_start', '<=', $now_datetime)
            ->whereDate('e_t.release_end', '>=', $now_datetime)
            ->when(! is_null($search_tag), function ($query) use ($search_tag) {
            return $query->where('e_t.search_tag', 'like', "%$search_tag%");
        });
        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * [検索]イベント一覧ページネーション取得
     *
     * 検索条件にマッチした全てのイベント情報を取得
     *
     * @access public
     * @param string $search_tag
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     * @see EventRepository
     * @throws QueryException
     * @todo
     */
    public function getEventPagenationList($search_tag = null, $limit = null)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'tag' => $search_tag,
            'limit' => $limit
        ]);

        // イベント情報一覧のコレクションのみ取得
        $ret_collection = $this->getEventCollection($search_tag);
        // ページネーション込みで確定
        $limit = ! is_null($limit) ? $limit : getDefaultPagenation(); // 取得件数確定
        $ret = $ret_collection->orderBy('e_t.updated_at', 'desc')->paginate($limit);
        dslog($this->class_name, __FUNCTION__, 'm22', DB::getQueryLog());

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * [検索]イベント一覧取得
     *
     * 検索条件にマッチした全てのイベント情報を取得
     *
     * @access public
     * @param string $search_tag
     * @param int $start
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     * @see EventRepository
     * @throws QueryException
     * @todo
     */
    public function getEventList($search_tag = null, $start = 0, $limit = null)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'tag' => $search_tag,
            'start' => $start,
            'limit' => $limit
        ]);

        // イベント情報一覧のコレクションのみ取得
        $ret_collection = $this->getEventCollection($search_tag);
        if (is_null($limit)) {
            // 全件取得で確定
            $ret = $ret_collection->orderBy('e_t.updated_at', 'desc')->get();
        } else {
            // 指定開始位置から指定件数取得で確定
            $ret = $ret_collection->orderBy('e_t.updated_at', 'desc')
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
     * [検索]イベント情報取得
     *
     * イベント番号によりイベント情報を取得
     *
     * @access public
     * @param string $event_no
     * @return object
     * @see EventRepository
     * @throws QueryException
     * @todo
     */
    public function getEventData($event_no)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'event_no' => $event_no
        ]);

        // イベント情報取得
        islog($this->class_name, __FUNCTION__, 'm2');
        $cb = new Carbon(); // 現在日時
        $now_datetime = $cb->format('Y-m-d H:i:s');
        DB::enableQueryLog();
        $ret = DB::table('event_t as e_t')->select('e_t.event_mgr_no', 'e_t.event_name', 'e_t.event_description', 'e_t.event_start_date', 'e_t.event_stage', 'e_t.event_weather_type', 'e_t.event_organizer', 'e_t.event_text', 'e_t.event_image_no', 'e_t.search_tag', 'e_t.created_at', 'e_t.updated_at', 'i_t.image_category', 'i_t.image_no', 'i_t.image_name', 'i_t.image_folder', 'i_t.image_type', 'i_t.image_alt', 'i_t.image_text')
            ->leftjoin('image_t as i_t', function ($join) {
            $join->where('i_t.image_category', getImageEventCategory())
                ->on('e_t.event_image_no', 'i_t.image_no');
        })
            ->where('e_t.event_mgr_no', $event_no)
            ->whereDate('e_t.release_start', '<=', $now_datetime)
            ->whereDate('e_t.release_end', '>=', $now_datetime)
            ->first();
        dslog($this->class_name, __FUNCTION__, 'm22', DB::getQueryLog());

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * [検索]イベント新着情報の一覧取得
     *
     * 検索条件にマッチした全ての新着情報を取得
     *
     * @access public
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     * @see EventRepository
     * @throws QueryException
     * @todo
     */
    public function getEventNewUpdateList($limit)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'limit' => $limit
        ]);

        // イベント新着情報取得
        islog($this->class_name, __FUNCTION__, 'm2');
        $cb = new Carbon(); // 現在日時
        $now_datetime = $cb->format('Y-m-d H:i:s');
        DB::enableQueryLog();
        $ret = DB::table('event_t as e_t')->select(DB::raw('"event" as page'), 'e_t.event_mgr_no as param1', DB::raw('"" as param2'), DB::raw('"" as param3'), 'e_t.event_name as title', DB::raw('"" as title_text'), 'e_t.updated_at as update_date', 'e_t.event_image_no as image_no', 'i_t.image_category as image_category', 'i_t.image_name as image_name', 'i_t.image_folder as image_folder', 'i_t.image_type as image_type', 'i_t.image_alt as image_alt', 'i_t.image_text as image_text');
        // route('front_event.show')のURL（修正時注意）
        $ret = $ret->selectRaw('concat(?,"/event/",e_t.event_mgr_no,"/detail") as url', [
            url('/')
        ]); // URL生成
        $ret = $ret->selectRaw('case when i_t.image_name is not null then concat(?,"/", i_t.image_folder, "/", i_t.image_name, "_500.", i_t.image_type) else ? end as img_file', [ // 画像パス生成(imgのみ)
            getImagePublicFullpath(),
            getNoimagePath()
        ]);
        $ret = $ret->leftjoin('image_t as i_t', function ($join) {
            $join->where('i_t.image_category', getImageEventCategory())
                ->on('e_t.event_image_no', 'i_t.image_no');
        })
            ->whereDate('e_t.release_start', '<=', $now_datetime)
            ->whereDate('e_t.release_end', '>=', $now_datetime)
            ->orderBy('update_date', 'desc')
            ->take($limit)
            ->get();
        dslog($this->class_name, __FUNCTION__, 'm22', DB::getQueryLog());

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }
}