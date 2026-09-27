<?php
/**
 * [DB]message_bord_tテーブルのRepositoryクラス
 *
 * message_bord_tテーブルをメインとする検索、登録、更新、削除処理
 *
 * @access public
 * @author Seiji Ishikawa（メールアドレス）
 * @copyright Webじゃ軒 All Rights Reserved
 * @package repository
 */
namespace App\Repositories;

use App\Models\MessageBord;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class MessageBordRepository implements MessageBordRepositoryInterface
{

    protected $message_bord_obj;

    protected $class_name;

    /**
     * コンストラクタ
     *
     * @access public
     * @param MessageBord $message_bord
     */
    public function __construct(MessageBord $message_bord)
    {
        $this->message_bord_obj = $message_bord;
        $this->class_name = get_class($this);
        islog($this->class_name, __FUNCTION__);
    }

    /**
     * [検索]メッセージボード情報コレクション取得
     *
     * 検索条件にマッチした全てのメッセージボード情報を取得
     *
     * @access private
     * @return \Illuminate\Database\Eloquent\Collection
     * @see MessageBordRepository
     * @throws QueryException
     * @todo
     */
    private function getMessageBordCollection()
    {
        islog($this->class_name, __FUNCTION__, 'm1');

        // データ取得
        islog($this->class_name, __FUNCTION__, 'm2');
        DB::enableQueryLog();
        $ret = DB::table('message_bord_t as mb_t')->select('mb_t.message_bord_no', 'mb_t.message_bord_text', 'mb_t.message_bord_sender', 'mb_t.updated_at', 'mb_t.end_flg', 
            DB::raw('concat("[",group_concat("{","\"message_reply_bord_no\":","\"",mrb_t.message_reply_bord_no,"\"",",\"message_reply_bord_text\":","\"",mrb_t.message_reply_bord_text,"\"",",\"updated_at\":","\"",mrb_t.updated_at,"\"",",\"comp_no\":","\"",mrb_t.comp_no,"\"",",\"comp_name\":","\"",c_t.comp_name,"\"",",\"comp_branch_name\":","\"",cb_t.comp_branch_name,"\"}" order by mrb_t.updated_at desc separator ","),"]") as mrb_data'))
            ->leftjoin('message_reply_bord_t as mrb_t', function ($join) {
            $join->on('mb_t.message_bord_no', 'mrb_t.message_bord_no');
        })
            ->leftjoin('comp_t as c_t', function ($join) {
            $join->on('mrb_t.comp_no', 'c_t.comp_no')
                ->where('c_t.delete_flg', '0');
        })
            ->leftjoin('comp_branch_t as cb_t', function ($join) {
            $join->on('mrb_t.comp_no', 'cb_t.comp_no')
                ->on('mrb_t.comp_branch_no', 'cb_t.comp_branch_no')
                ->where('cb_t.delete_flg', '0');
        })
        ->groupBy('mb_t.message_bord_no', 'mb_t.message_bord_text', 'mb_t.message_bord_sender', 'mb_t.updated_at', 'mb_t.end_flg');

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * [検索]メッセージボード情報一覧ページネーション取得
     *
     * 検索条件にマッチした全てのメッセージボード情報を取得
     *
     * @access public
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     * @see MessageBordRepository
     * @throws QueryException
     * @todo
     */
    public function getMessageBordPagenationList($limit = null)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'limit' => $limit
        ]);

        // メッセージボード情報一覧のコレクションのみ取得
        $ret_collection = $this->getMessageBordCollection();
        // ページネーション込みで確定
        $limit = ! is_null($limit) ? $limit : getDefaultPagenation(); // 取得件数確定
        $ret = $ret_collection->orderBy('mb_t.updated_at', 'desc')->paginate($limit);
        dslog($this->class_name, __FUNCTION__, 'm22', DB::getQueryLog());

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * [検索]メッセージボード情報一覧取得
     *
     * 検索条件にマッチした全てのメッセージボード情報を取得
     *
     * @access public
     * @param int $start
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     * @see MessageBordRepository
     * @throws QueryException
     * @todo
     */
    public function getMessageBordList($start = 0, $limit = null)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'start' => $start,
            'limit' => $limit
        ]);

        // メッセージボード情報一覧のコレクションのみ取得
        $ret_collection = $this->getMessageBordCollection();
        if (is_null($limit)) {
            // 全件取得で確定
            $ret = $ret_collection->orderBy('mb_t.updated_at', 'desc')->get();
        } else {
            // 指定開始位置から指定件数取得で確定
            $ret = $ret_collection->orderBy('mb_t.updated_at', 'desc')
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
     * [登録]メッセージボード情報登録
     *
     * 有効なメッセージボード情報を追加
     *
     * @access public
     * @param array $insert_data
     * @return Boolean $ret
     * @see MessageBordRepository
     * @throws QueryException
     * @todo
     */
    public function insertMessageBordData($insert_data)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'insert_data' => $insert_data
        ]);

        // メッセージボード登録
        islog($this->class_name, __FUNCTION__, 'm4');
        DB::beginTransaction();
        islog($this->class_name, __FUNCTION__, 'm5');
        DB::enableQueryLog();
        $ret = $this->message_bord_obj->insert($insert_data);
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
     * [更新]メッセージボード情報更新
     *
     * 指定のメッセージボード情報を更新
     *
     * @access public
     * @param array $update_data
     * @param string $message_bord_auth_no
     * @param int $message_bord_no
     * @return Boolean $ret
     * @see MessageBordRepository
     * @throws QueryException
     * @todo
     */
    public function updateMessageBordData($update_data, $message_bord_auth_no, $message_bord_no)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'update_data' => $update_data,
            'message_bord_no' => $message_bord_no,
            'message_bord_auth_no' => $message_bord_auth_no
        ]);

        // メッセージボード更新
        islog($this->class_name, __FUNCTION__, 'm4');
        DB::beginTransaction();
        islog($this->class_name, __FUNCTION__, 'm6');
        DB::enableQueryLog();
        $ret = $this->message_bord_obj->where('message_bord_auth_no', $message_bord_auth_no)
            ->where('message_bord_no', $message_bord_no)
            ->update($update_data);
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
     * [削除]メッセージボード情報削除
     *
     * 有効なメッセージボード情報を削除
     *
     * @access public
     * @param int $delete_data
     * @param string $message_bord_auth_no
     * @return Boolean
     * @see MessageBordRepository
     * @throws QueryException
     * @todo
     */
    public function deleteMessageBordData($delete_data, $message_bord_auth_no)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'delete_data' => $delete_data,
            'message_bord_auth_no' => $message_bord_auth_no
        ]);

        // イベント削除
        islog($this->class_name, __FUNCTION__, 'm4');
        DB::beginTransaction();
        islog($this->class_name, __FUNCTION__, 'm7');
        DB::enableQueryLog();
        $ret = $this->message_bord_obj->where('message_bord_auth_no', $message_bord_auth_no)
            ->where('message_bord_no', $delete_data)
            ->delete();
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