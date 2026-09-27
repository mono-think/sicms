<?php
/**
 * [DB]industry_mテーブルのRepositoryクラス
 *
 * industry_mテーブルをメインとする検索、登録、更新、削除処理
 *
 * @access public
 * @author Seiji Ishikawa（メールアドレス）
 * @copyright Webじゃ軒 All Rights Reserved
 * @package repository
 */
namespace App\Repositories\Manage;

use App\Models\Industry;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class IndustryRepository implements IndustryRepositoryInterface
{

    protected $industry_master;

    protected $class_name;

    /**
     * コンストラクタ
     *
     * @access public
     * @param Industry $industry
     */
    public function __construct(Industry $industry)
    {
        $this->industry_master = $industry;
        $this->class_name = get_class($this);
        islog($this->class_name, __FUNCTION__);
    }

    /**
     * [検索]業種マスタ情報コレクション取得
     *
     * 検索条件にマッチした全ての業種マスタ情報を取得
     *
     * @access private
     * @param string $free_word
     * @return \Illuminate\Database\Eloquent\Collection
     * @see IndustryRepository
     * @throws QueryException
     * @todo 【MySQL暫定対応】MySQLバージョンを5.7以上にあげたら、free_word検索のロジックを変更
     */
    private function getIndustryCollection($free_word)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'free_word' => $free_word
        ]);

        // データ取得
        islog($this->class_name, __FUNCTION__, 'm2');
        DB::enableQueryLog();
        $ret = $this->industry_master->when(! empty($free_word), function ($query) use ($free_word) {
            $query->where('industry_name', 'like', "%$free_word%");
            /*
             * $query->whereRaw('match (industry_name) against ("' . $free_word . '" IN NATURAL LANGUAGE MODE)');
             */
        });

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * [検索]業種マスタ情報ページネーション一覧取得
     *
     * 検索条件にマッチした全ての業種マスタ情報を取得
     *
     * @access public
     * @param string $free_word
     * @parama int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     * @see IndustryRepository
     * @throws QueryException
     * @todo
     */
    public function getIndustryPagenationList($free_word, $limit = null)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'free_word' => $free_word,
            'limit' => $limit
        ]);

        // 業種マスタ一覧のコレクションのみ取得
        $ret_collection = $this->getIndustryCollection($free_word);
        // ページネーション込みで確定
        $limit = ! is_null($limit) ? $limit : getDefaultPagenation(); // 取得件数確定
        $ret = $ret_collection->orderBy('updated_at', 'desc')->paginate($limit);
        dslog($this->class_name, __FUNCTION__, 'm22', DB::getQueryLog());

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * [検索]業種マスタ情報一覧取得
     *
     * 検索条件にマッチした全ての業種マスタ情報を取得
     *
     * @access public
     * @param string $free_word
     * @param int $start
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     * @see IndustryRepository
     * @throws QueryException
     * @todo
     */
    public function getIndustryList($free_word, $start = 0, $limit = null)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'free_word' => $free_word,
            'start' => $start,
            'limit' => $limit
        ]);

        // 業種マスタ一覧のコレクションのみ取得
        $ret_collection = $this->getIndustryCollection($free_word);
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
     * [検索]業種マスタ情報を取得
     *
     * 有効なマスタ情報を取得
     *
     * @access public
     * @param int $industry_no
     * @return object
     * @see IndustryRepository
     * @throws QueryException
     * @todo
     */
    public function getIndustryData($industry_no)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'industry_no' => $industry_no
        ]);

        // データ取得
        islog($this->class_name, __FUNCTION__, 'm2');
        DB::enableQueryLog();
        $ret = $this->industry_master->where('industry_no', $industry_no)->first();
        dslog($this->class_name, __FUNCTION__, 'm22', DB::getQueryLog());

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * [登録]業種マスタ情報登録
     *
     * 有効な業種マスタ情報を追加
     *
     * @access public
     * @param array $insert_data
     * @return Boolean
     * @see IndustryRepository
     * @throws QueryException
     * @todo
     */
    public function insertIndustryData($insert_data)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'insert_data' => $insert_data
        ]);

        // 業種マスタ登録
        islog($this->class_name, __FUNCTION__, 'm4');
        DB::beginTransaction();
        islog($this->class_name, __FUNCTION__, 'm5');
        DB::enableQueryLog();
        $ret = $this->industry_master->insert($insert_data);
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
     * [更新]業種マスタ情報更新
     *
     * 指定の業種マスタ情報を更新
     *
     * @access public
     * @param array $update_data
     * @param int $industry_no
     * @return Boolean
     * @see IndustryRepository
     * @throws QueryException
     * @todo
     */
    public function updateIndustryData($update_data, $industry_no)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'update_data' => $update_data,
            'industry_no' => $industry_no
        ]);

        // 業種マスタ更新
        islog($this->class_name, __FUNCTION__, 'm4');
        DB::beginTransaction();
        islog($this->class_name, __FUNCTION__, 'm6');
        DB::enableQueryLog();
        $ret = $this->industry_master->where('industry_no', $industry_no)->update($update_data);
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
     * [削除]業種マスタ情報削除
     *
     * 有効な業種マスタ情報を削除
     *
     * @access public
     * @param array $delete_data
     * @param string $acnt_no
     * @return Boolean
     * @see IndustryRepository
     * @throws QueryException
     * @todo
     */
    public function deleteIndustryData($delete_data)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'delete_data' => $delete_data
        ]);

        // 業種マスタ削除
        islog($this->class_name, __FUNCTION__, 'm4');
        DB::beginTransaction();
        islog($this->class_name, __FUNCTION__, 'm7');
        DB::enableQueryLog();
        $ret = $this->industry_master->whereIn('industry_no', $delete_data)->delete(); // 最終更新者, 削除フラグON
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
