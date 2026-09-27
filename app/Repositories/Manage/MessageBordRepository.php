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
namespace App\Repositories\Manage;

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
     * @param string $free_word
     * @return \Illuminate\Database\Eloquent\Collection
     * @see MessageBordRepository
     * @throws QueryException
     * @todo 【MySQL暫定対応】MySQLバージョンを5.7以上にあげたら、free_word検索のロジックを変更
     */
    private function getMessageBordCollection($free_word)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'free_word' => $free_word
        ]);

        // データ取得
        islog($this->class_name, __FUNCTION__, 'm2');
        DB::enableQueryLog();
        $ret = $this->message_bord_obj->when(! empty($free_word), function ($query) use ($free_word) {
            $query->where('message_bord_text', 'like', "%$free_word%")
                ->orWhere('message_bord_sender', 'like', "%$free_word%");
            /*
             * $query->whereRaw('match (message_bord_text, message_bord_sender) against ("' . $free_word . '" IN NATURAL LANGUAGE MODE)');
             */
        });

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
     * @param string $free_word
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     * @see MessageBordRepository
     * @throws QueryException
     * @todo
     */
    public function getMessageBordPagenationList($free_word, $limit = null)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'free_word' => $free_word,
            'limit' => $limit
        ]);

        // メッセージボード情報一覧のコレクションのみ取得
        $ret_collection = $this->getMessageBordCollection($free_word);
        // ページネーション込みで確定
        $limit = ! is_null($limit) ? $limit : getDefaultPagenation(); // 取得件数確定
        $ret = $ret_collection->orderBy('updated_at', 'desc')->paginate($limit);
        dslog($this->class_name, __FUNCTION__, 'm22', DB::getQueryLog());

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
     * @param array $delete_data
     * @param string $acnt_no
     * @return Boolean
     * @see MessageBordRepository
     * @throws QueryException
     * @todo
     */
    public function deleteMessageBordData($delete_data)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'delete_data' => $delete_data
        ]);

        // メッセージボード情報削除
        islog($this->class_name, __FUNCTION__, 'm4');
        DB::beginTransaction();
        islog($this->class_name, __FUNCTION__, 'm7');
        DB::enableQueryLog();
        $ret = $this->message_bord_obj->whereIn('message_bord_no', $delete_data)->delete();
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