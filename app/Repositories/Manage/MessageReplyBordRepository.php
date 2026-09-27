<?php
/**
 * [DB]message_reply_bord_tテーブルのRepositoryクラス
 *
 * message_reply_bord_tテーブルをメインとする検索、登録、更新、削除処理
 *
 * @access public
 * @author Seiji Ishikawa（メールアドレス）
 * @copyright Webじゃ軒 All Rights Reserved
 * @package repository
 */
namespace App\Repositories\Manage;

use App\Models\MessageReplyBord;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class MessageReplyBordRepository implements MessageReplyBordRepositoryInterface
{

    protected $message_reply_bord_obj;

    protected $class_name;

    /**
     * コンストラクタ
     *
     * @access public
     * @param MessageReplyBord $message_reply_bord
     */
    public function __construct(MessageReplyBord $message_reply_bord)
    {
        $this->message_reply_bord_obj = $message_reply_bord;
        $this->class_name = get_class($this);
        islog($this->class_name, __FUNCTION__);
    }

    /**
     * [検索]メッセージ返信ボード情報コレクション取得
     *
     * 検索条件にマッチした全てのメッセージ返信ボード情報を取得
     *
     * @access private
     * @param array $message_bord_no
     * @return \Illuminate\Database\Eloquent\Collection
     * @see MessageReplyBordRepository
     * @throws QueryException
     * @todo
     */
    private function getMessageReplyBordCollection($message_bord_no)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'message_bord_no' => $message_bord_no
        ]);

        // データ取得
        islog($this->class_name, __FUNCTION__, 'm2');
        DB::enableQueryLog();
        $ret = DB::table('message_reply_bord_t as mrb_t')->select('mrb_t.message_reply_bord_no', 'mrb_t.message_bord_no', 'mrb_t.message_reply_bord_text', 'mrb_t.comp_no', 'mrb_t.comp_branch_no', 'mrb_t.acnt_no', 'mrb_t.updated_at', 'c_t.comp_name', 'cb_t.comp_branch_name', 'a_t.acnt_sei', 'a_t.acnt_mei')
            ->join('acnt_t as a_t', function ($join) {
            $join->on('mrb_t.acnt_no', '=', 'a_t.acnt_no')
                ->on('mrb_t.comp_no', '=', 'a_t.comp_no')
                ->on('mrb_t.comp_branch_no', '=', 'a_t.comp_branch_no')
                ->where('a_t.delete_flg', '=', '0');
        })
            ->join('comp_t as c_t', function ($join) {
            $join->on('mrb_t.comp_no', '=', 'c_t.comp_no')
                ->where('c_t.delete_flg', '=', '0');
        })
            ->join('comp_branch_t as cb_t', function ($join) {
            $join->on('mrb_t.comp_no', '=', 'cb_t.comp_no')
                ->on('mrb_t.comp_branch_no', '=', 'cb_t.comp_branch_no')
                ->where('cb_t.delete_flg', '=', '0');
        })
            ->whereIn('mrb_t.message_bord_no', $message_bord_no);

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * [検索]メッセージ返信ボード情報一覧取得
     *
     * 検索条件にマッチした全てのメッセージ返信ボード情報を取得
     *
     * @access public
     * @param array $message_bord_no
     * @param int $start
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     * @see MessageReplyBordRepository
     * @throws QueryException
     * @todo
     */
    public function getMessageReplyBordList($message_bord_no, $start = 0, $limit = null)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'message_bord_no' => $message_bord_no,
            'start' => $start,
            'limit' => $limit
        ]);

        // メッセージ返信ボード情報のコレクションのみ取得
        $ret_collection = $this->getMessageReplyBordCollection($message_bord_no);
        if (is_null($limit)) {
            // 全件取得で確定
            $ret = $ret_collection->orderBy('mrb_t.updated_at', 'desc')
                ->orderBy('mrb_t.message_bord_no', 'desc')
                ->get();
        } else {
            // 指定開始位置から指定件数取得で確定
            $ret = $ret_collection->orderBy('mrb_t.updated_at', 'desc')
                ->orderBy('mrb_t.message_bord_no', 'desc')
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
     * [検索]メッセージ返信ボード情報取得
     *
     * 検索条件にマッチしたメッセージ返信ボード情報を取得
     *
     * @access public
     * @param int $message_reply_bord_no
     * @return array
     * @see MessageReplyBordRepository
     * @throws QueryException
     * @todo
     */
    public function getMessageReplyBordData($message_reply_bord_no)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'message_reply_bord_no' => $message_reply_bord_no
        ]);

        // データ取得
        islog($this->class_name, __FUNCTION__, 'm2');
        DB::enableQueryLog();
        $ret = $this->message_reply_bord_obj->where('message_reply_bord_no', $message_reply_bord_no)->first();
        dslog($this->class_name, __FUNCTION__, 'm22', DB::getQueryLog());

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * [登録]メッセージ返信ボード情報登録
     *
     * 有効なメッセージ返信ボード情報を追加
     *
     * @access public
     * @param array $insert_data
     * @return Boolean
     * @see MessageReplyBordRepository
     * @throws QueryException
     * @todo
     */
    public function insertMessageReplyBordData($insert_data)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'insert_data' => $insert_data
        ]);

        // メッセージ返信ボード登録
        islog($this->class_name, __FUNCTION__, 'm4');
        DB::beginTransaction();
        islog($this->class_name, __FUNCTION__, 'm5');
        DB::enableQueryLog();
        $ret = $this->message_reply_bord_obj->insert($insert_data);
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
     * [更新]メッセージ返信ボード情報更新
     *
     * 指定のメッセージ返信ボード情報を更新
     *
     * @access public
     * @param array $update_data
     * @param int $message_reply_bord_no
     * @return Boolean
     * @see MessageReplyBordRepository
     * @throws QueryException
     * @todo
     */
    public function updateMessageReplyBordData($update_data, $message_reply_bord_no)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'update_data' => $update_data,
            'message_reply_bord_no' => $message_reply_bord_no
        ]);

        // メッセージ返信ボード更新
        islog($this->class_name, __FUNCTION__, 'm4');
        DB::beginTransaction();
        islog($this->class_name, __FUNCTION__, 'm6');
        DB::enableQueryLog();
        $ret = $this->message_reply_bord_obj->where('message_reply_bord_no', $message_reply_bord_no)
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
     * [削除]メッセージ返信ボード情報削除
     *
     * 有効なメッセージ返信ボード情報を削除
     *
     * @access public
     * @param array $delete_data
     * @param array $message_bord_no
     * @return Boolean
     * @see MessageReplyBordRepository
     * @throws QueryException
     * @todo
     */
    public function deleteMessageReplyBordData($delete_data, $message_bord_no)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'delete_data' => $delete_data,
            'message_bord_no' => $message_bord_no
        ]);

        // メッセージ返信ボード削除
        islog($this->class_name, __FUNCTION__, 'm4');
        DB::beginTransaction();
        islog($this->class_name, __FUNCTION__, 'm7');
        DB::enableQueryLog();
        $ret = $this->message_reply_bord_obj->whereIn('message_bord_no', $message_bord_no)
            ->when(! is_null($delete_data), function ($query) use ($delete_data) {
            $query->whereIn('message_reply_bord_no', $delete_data);
        })
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