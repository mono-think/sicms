<?php
/**
 * [DB]contents_tテーブルのRepositoryクラス
 *
 * contents_tテーブルをメインとするコンテンツ情報の検索、登録、更新、削除処理
 *
 * @access public
 * @author Seiji Ishikawa（メールアドレス）
 * @copyright Webじゃ軒 All Rights Reserved
 * @package repository
 */
namespace App\Repositories\Manage;

use App\Models\Content;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class ContentRepository implements ContentRepositoryInterface
{

    protected $content_obj;

    protected $class_name;

    /**
     * コンストラクタ
     *
     * @access public
     * @param Content $content
     */
    public function __construct(Content $content)
    {
        $this->content_obj = $content;
        $this->class_name = get_class($this);
        islog($this->class_name, __FUNCTION__);
    }

    /**
     * [検索]コンテンツ情報コレクション取得
     *
     * 検索条件にマッチした全てのコンテンツ情報を取得
     *
     * @access private
     * @param string $free_word
     * @return \Illuminate\Database\Eloquent\Collection
     * @see ContentRepository
     * @throws QueryException
     * @todo 【MySQL暫定対応】MySQLバージョンを5.7以上にあげたら、free_word検索のロジックを変更
     */
    private function getContentCollection($free_word)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'free_word' => $free_word
        ]);

        // データ取得
        islog($this->class_name, __FUNCTION__, 'm2');
        DB::enableQueryLog();
        $ret = DB::table('contents_t')->select('contents_mgr_no', 'contents_type', 'contents_title', 'contents_description', 'contents_text', 'contents_image_no', 'search_tag', 'release_start', 'release_end')->when(! empty($free_word), function ($query) use ($free_word) {
            $query->where('contents_title', 'like', "%$free_word%")
                ->orWhere('contents_description', 'like', "%$free_word%")
                ->orWhere('contents_text', 'like', "%$free_word%");
            /*
             * $query->whereRaw('match (contents_title, contents_description, contents_text) against ("' . $free_word . '" IN NATURAL LANGUAGE MODE)');
             */
        });

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * [検索]コンテンツ情報一覧ページネーション取得
     *
     * 検索条件にマッチした全てのイベント情報を取得
     *
     * @access public
     * @param string $free_word
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     * @see ContentRepository
     * @throws QueryException
     * @todo
     */
    public function getContentPagenationList($free_word, $limit = null)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'free_word' => $free_word,
            'limit' => $limit
        ]);

        // イベント情報一覧のコレクションのみ取得
        $ret_collection = $this->getContentCollection($free_word);
        // ページネーション込みで確定
        $limit = ! is_null($limit) ? $limit : getDefaultPagenation(); // 取得件数確定
        $ret = $ret_collection->orderBy('updated_at', 'desc')->paginate($limit);
        dslog($this->class_name, __FUNCTION__, 'm22', DB::getQueryLog());

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * [検索]コンテンツ情報取得
     *
     * コンテンツ番号によりコンテンツ情報を取得
     *
     * @access public
     * @param int $content_no
     * @return object
     * @see ContentRepository
     * @throws QueryException
     * @todo
     */
    public function getContentData($content_no)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'content_no' => $content_no
        ]);

        // データ取得
        islog($this->class_name, __FUNCTION__, 'm2');
        DB::enableQueryLog();
        $ret = DB::table('contents_t as c_t')->select('c_t.contents_mgr_no', 'c_t.contents_type', 'c_t.contents_title', 'c_t.contents_description', 'c_t.contents_text', 'c_t.contents_image_no', 'c_t.search_tag', 'c_t.release_start', 'c_t.release_end', 'i_t.image_category', 'i_t.image_no', 'i_t.image_name', 'i_t.image_folder', 'i_t.image_type', 'i_t.image_alt', 'i_t.image_text')
            ->leftjoin('image_t as i_t', function ($join) {
            $join->on('c_t.contents_image_no', '=', 'i_t.image_no')
                ->where('i_t.image_category', '=', getImageContentsCategory());
        })
            ->where('c_t.contents_mgr_no', $content_no)
            ->first();
        dslog($this->class_name, __FUNCTION__, 'm22', DB::getQueryLog());

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * [登録]コンテンツ情報登録
     *
     * 有効なコンテンツ情報を追加
     *
     * @access public
     * @param array $insert_data
     * @return Boolean
     * @see ContentRepository
     * @throws QueryException
     * @todo
     */
    public function insertContentData($insert_data)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'insert_data' => $insert_data
        ]);

        // コンテンツ登録
        islog($this->class_name, __FUNCTION__, 'm4');
        DB::beginTransaction();
        islog($this->class_name, __FUNCTION__, 'm5');
        DB::enableQueryLog();
        $ret = $this->content_obj->insert($insert_data);
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
     * [更新]コンテンツ情報更新
     *
     * 指定のコンテンツ情報を更新
     *
     * @access public
     * @param array $update_data
     * @param int $content_no
     * @return Boolean
     * @see ContentRepository
     * @throws QueryException
     * @todo
     */
    public function updateContentData($update_data, $content_no)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'update_data' => $update_data,
            'content_no' => $content_no
        ]);

        // コンテンツ更新
        islog($this->class_name, __FUNCTION__, 'm4');
        DB::beginTransaction();
        islog($this->class_name, __FUNCTION__, 'm6');
        DB::enableQueryLog();
        $ret = $this->content_obj->where('contents_mgr_no', $content_no)->update($update_data);
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
     * [削除]コンテンツ情報削除
     *
     * 有効なコンテンツ情報を削除
     *
     * @access public
     * @param array $delete_data
     * @return Boolean
     * @see ContentRepository
     * @throws QueryException
     * @todo
     */
    public function deleteContentData($delete_data)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'delete_data' => $delete_data
        ]);

        // コンテンツ削除
        islog($this->class_name, __FUNCTION__, 'm4');
        DB::beginTransaction();
        islog($this->class_name, __FUNCTION__, 'm7');
        DB::enableQueryLog();
        $ret = $this->content_obj->whereIn('contents_mgr_no', $delete_data)->delete();
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