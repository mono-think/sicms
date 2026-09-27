<?php
/**
 * [DB]position_mテーブルのRepositoryクラス
 *
 * position_mテーブルをメインとする検索、登録、更新、削除処理
 *
 * @access public
 * @author Seiji Ishikawa（メールアドレス）
 * @copyright Webじゃ軒 All Rights Reserved
 * @package repository
 */
namespace App\Repositories\Manage;

use App\Models\Position;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class PositionRepository implements PositionRepositoryInterface
{

    protected $position_master;

    protected $class_name;

    /**
     * コンストラクタ
     *
     * @access public
     * @param Position $Position
     */
    public function __construct(Position $position)
    {
        $this->position_master = $position;
        $this->class_name = get_class($this);
        islog($this->class_name, __FUNCTION__);
    }

    /**
     * [検索]役職マスタ情報コレクション取得
     *
     * 検索条件にマッチした全ての役職マスタ情報を取得
     *
     * @access private
     * @param string $free_word
     * @return \Illuminate\Database\Eloquent\Collection
     * @see PositionRepository
     * @throws QueryException
     * @todo 【MySQL暫定対応】MySQLバージョンを5.7以上にあげたら、free_word検索のロジックを変更
     */
    private function getPositionCollection($free_word)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'free_word' => $free_word
        ]);

        // データ取得
        islog($this->class_name, __FUNCTION__, 'm2');
        DB::enableQueryLog();
        $ret = $this->position_master->when(! empty($free_word), function ($query) use ($free_word) {
            $query->where('position_name', 'like', "%$free_word%");
            /*
             * $query->whereRaw('match (position_name) against ("' . $free_word . '" IN NATURAL LANGUAGE MODE)');
             */
        });

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * [検索]役職マスタ情報一覧ページネーション取得
     *
     * 検索条件にマッチした全ての役職マスタ情報を取得
     *
     * @access public
     * @param string $free_word
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     * @see PositionRepository
     * @throws QueryException
     * @todo
     */
    public function getPositionPagenationList($free_word, $limit = null)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'free_word' => $free_word,
            'limit' => $limit
        ]);

        // 役職マスタ一覧のコレクションのみ取得
        $ret_collection = $this->getPositionCollection($free_word);
        // ページネーション込みで確定
        $limit = ! is_null($limit) ? $limit : getDefaultPagenation(); // 取得件数確定
        $ret = $ret_collection->orderBy('updated_at', 'desc')->paginate($limit);
        dslog($this->class_name, __FUNCTION__, 'm22', DB::getQueryLog());

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * [検索]役職マスタ情報一覧取得
     *
     * 検索条件にマッチした全ての役職マスタ情報を取得
     *
     * @access public
     * @param string $free_word
     * @param int $start
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     * @see PositionRepository
     * @throws QueryException
     * @todo
     */
    public function getPositionList($free_word, $start = 0, $limit = null)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'free_word' => $free_word,
            'start' => $start,
            'limit' => $limit
        ]);

        // 役職マスタ一覧のコレクションのみ取得
        $ret_collection = $this->getPositionCollection($free_word);
        if (is_null($limit)) {
            // 全件取得で確定
            $ret = $ret_collection->orderBy('updated_at', 'desc')->get();
        } else {
            // 指定開始位置から指定件数取得で確定
            $ret = $ret_collection->orderBy('updated_at', 'desc')
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
     * [検索]役職マスタ情報を取得
     *
     * 有効なマスタ情報を取得
     *
     * @access public
     * @param int $position_no
     * @return object
     * @see PositionRepository
     * @throws QueryException
     * @todo
     */
    public function getPositionData($position_no)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'position_no' => $position_no
        ]);

        // データ取得
        islog($this->class_name, __FUNCTION__, 'm2');
        DB::enableQueryLog();
        $ret = $this->position_master->where('position_no', $position_no)->first();
        dslog($this->class_name, __FUNCTION__, 'm22', DB::getQueryLog());

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * [登録]役職マスタ情報登録
     *
     * 有効な役職マスタ情報を追加
     *
     * @access public
     * @param array $insert_data
     * @return Boolean
     * @see PositionRepository
     * @throws QueryException
     * @todo
     */
    public function insertPositionData($insert_data)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'insert_data' => $insert_data
        ]);

        // 役職マスタ登録
        islog($this->class_name, __FUNCTION__, 'm4');
        DB::beginTransaction();
        islog($this->class_name, __FUNCTION__, 'm5');
        DB::enableQueryLog();
        $ret = $this->position_master->insert($insert_data);
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
        dslog($this->class_name, __FUNCTION__, 'm22', DB::getQueryLog());

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * [更新]役職マスタ情報更新
     *
     * 指定の役職マスタ情報を更新
     *
     * @access public
     * @param array $update_data
     * @param int $position_no
     * @return Boolean
     * @see PositionRepository
     * @throws QueryException
     * @todo
     */
    public function updatePositionData($update_data, $position_no)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'update_data' => $update_data,
            'position_no' => $position_no
        ]);

        // 役職マスタ更新
        islog($this->class_name, __FUNCTION__, 'm4');
        DB::beginTransaction();
        islog($this->class_name, __FUNCTION__, 'm6');
        DB::enableQueryLog();
        $ret = $this->position_master->where('position_no', $position_no)->update($update_data);
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
        dslog($this->class_name, __FUNCTION__, 'm22', DB::getQueryLog());

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * [削除]役職マスタ情報削除
     *
     * 有効な役職マスタ情報を削除
     *
     * @access public
     * @param array $delete_data
     * @return Boolean
     * @see PositionRepository
     * @throws QueryException
     * @todo
     */
    public function deletePositionData($delete_data)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'delete_data' => $delete_data
        ]);

        // 役職マスタ削除
        islog($this->class_name, __FUNCTION__, 'm4');
        DB::beginTransaction();
        islog($this->class_name, __FUNCTION__, 'm7');
        DB::enableQueryLog();
        $ret = $this->position_master->whereIn('position_no', $delete_data)->delete();
        if (empty($ret)) {
            // 削除失敗（削除フラグ更新失敗）
            eslog($this->class_name, __FUNCTION__, 'm3');
            islog($this->class_name, __FUNCTION__, 'm8');
            DB::rollBack();
        } else {
            islog($this->class_name, __FUNCTION__, 'm9');
            DB::commit();
        }
        dslog($this->class_name, __FUNCTION__, 'm22', DB::getQueryLog());

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }
}