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
namespace App\Repositories\Manage;

use App\Models\Event;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

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
     * @param string $free_word
     * @return \Illuminate\Database\Eloquent\Collection
     * @see EventRepository
     * @throws QueryException
     * @todo 【MySQL暫定対応】MySQLバージョンを5.7以上にあげたら、free_word検索のロジックを変更
     */
    private function getEventCollection($free_word)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'free_word' => $free_word
        ]);

        // データ取得
        islog($this->class_name, __FUNCTION__, 'm2');
        DB::enableQueryLog();
        $ret = DB::table('event_t')->select('event_mgr_no', 'event_name', 'event_description', 'event_start_date', 'event_stage', 'event_weather_type', 'event_organizer', 'event_text', 'event_image_no', 'search_tag', 'release_start', 'release_end')->when(! empty($free_word), function ($query) use ($free_word) {
            $query->where('event_name', 'like', "%$free_word%")
                ->orWhere('event_description', 'like', "%$free_word%")
                ->orWhere('event_stage', 'like', "%$free_word%")
                ->orWhere('event_organizer', 'like', "%$free_word%")
                ->orWhere('event_text', 'like', "%$free_word%");
            /*
             * $query->whereRaw('match (event_name,event_description,event_stage,event_organizer,event_text) against ("' . $free_word . '" IN NATURAL LANGUAGE MODE)');
             */
        });

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * [検索]イベント情報一覧ページネーション取得
     *
     * 検索条件にマッチした全てのイベント情報を取得
     *
     * @access public
     * @param string $free_word
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     * @see EventRepository
     * @throws QueryException
     * @todo
     */
    public function getEventPagenationList($free_word, $limit = null)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'free_word' => $free_word,
            'limit' => $limit
        ]);

        // イベント情報一覧のコレクションのみ取得
        $ret_collection = $this->getEventCollection($free_word);
        // ページネーション込みで確定
        $limit = ! is_null($limit) ? $limit : getDefaultPagenation(); // 取得件数確定
        $ret = $ret_collection->orderBy('updated_at', 'desc')->paginate($limit);
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

        // データ取得
        islog($this->class_name, __FUNCTION__, 'm2');
        DB::enableQueryLog();
        $ret = DB::table('event_t as e_t')->select('e_t.event_mgr_no', 'e_t.event_name', 'e_t.event_description', 'e_t.event_start_date', 'e_t.event_stage', 'e_t.event_weather_type', 'e_t.event_organizer', 'e_t.event_text', 'e_t.event_image_no', 'e_t.search_tag', 'e_t.release_start', 'e_t.release_end', 'e_t.created_at', 'e_t.updated_at', 'i_t.image_no', 'i_t.image_category', 'i_t.image_name', 'i_t.image_folder', 'i_t.image_type', 'i_t.image_alt', 'i_t.image_text')
            ->leftjoin('image_t as i_t', function ($join) {
            $join->on('e_t.event_image_no', '=', 'i_t.image_no')
                ->where('i_t.image_category', '=', getImageEventCategory());
        })
            ->where('e_t.event_mgr_no', $event_no)
            ->first();
        dslog($this->class_name, __FUNCTION__, 'm22', DB::getQueryLog());

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * [登録]イベント情報登録
     *
     * 有効なイベント情報を追加
     *
     * @access public
     * @param array $insert_data
     * @return Boolean
     * @see EventRepository
     * @throws QueryException
     * @todo
     */
    public function insertEventData($insert_data)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'insert_data' => $insert_data
        ]);

        // イベント登録
        islog($this->class_name, __FUNCTION__, 'm4');
        DB::beginTransaction();
        islog($this->class_name, __FUNCTION__, 'm5');
        DB::enableQueryLog();
        $ret = $this->event_obj->insert($insert_data);
        dslog($this->class_name, __FUNCTION__, 'm22', DB::getQueryLog());

        if (empty($ret)) {
            // 登録失敗
            eslog($this->class_name, __FUNCTION__, 'm1');
            islog($this->class_name, __FUNCTION__, 'm8');
            DB::rollBack();
        } else {
            // 登録完了
            islog($this->class_name, __FUNCTION__, 'm9');
            DB::commit();
        }

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * [更新]イベント情報更新
     *
     * 指定のイベント情報を更新
     *
     * @access public
     * @param array $update_data
     * @param string $event_no
     * @return Boolean
     * @see EventRepository
     * @throws QueryException
     * @todo
     */
    public function updateEventData($update_data, $event_no)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'update_data' => $update_data,
            'event_no' => $event_no
        ]);

        // イベント更新
        islog($this->class_name, __FUNCTION__, 'm4');
        DB::beginTransaction();
        islog($this->class_name, __FUNCTION__, 'm6');
        DB::enableQueryLog();
        $ret = $this->event_obj->where('event_mgr_no', $event_no)->update($update_data);
        dslog($this->class_name, __FUNCTION__, 'm22', DB::getQueryLog());

        if (empty($ret)) {
            // 更新失敗
            eslog($this->class_name, __FUNCTION__, 'm2');
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

    /**
     * [削除]イベント情報削除
     *
     * 有効なイベント情報を削除
     *
     * @access public
     * @param array $delete_data
     * @return Boolean
     * @see EventRepository
     * @throws QueryException
     * @todo
     */
    public function deleteEventData($delete_data)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'delete_data' => $delete_data
        ]);

        // イベント削除
        islog($this->class_name, __FUNCTION__, 'm4');
        DB::beginTransaction();
        islog($this->class_name, __FUNCTION__, 'm7');
        DB::enableQueryLog();
        $ret = $this->event_obj->whereIn('event_mgr_no', $delete_data)->delete();
        dslog($this->class_name, __FUNCTION__, 'm22', DB::getQueryLog());

        if (empty($ret)) {
            // 削除失敗（削除フラグ更新失敗）
            eslog($this->class_name, __FUNCTION__, 'm3');
            islog($this->class_name, __FUNCTION__, 'm8');
            DB::rollBack();
        } else {
            // 削除完了
            islog($this->class_name, __FUNCTION__, 'm9');
            DB::commit();
        }

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }
}