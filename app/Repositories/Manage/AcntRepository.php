<?php
/**
 * [DB]acnt_tテーブルのRepositoryクラス
 *
 * acnt_tテーブルをメインとするアカウント情報の検索、登録、更新、削除処理
 *
 * @access public
 * @author Seiji Ishikawa（メールアドレス）
 * @copyright Webじゃ軒 All Rights Reserved
 * @package repository
 */
namespace App\Repositories\Manage;

use App\Models\Acnt;
use App\Models\AcntDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class AcntRepository implements AcntRepositoryInterface
{

    protected $acnt_obj;

    protected $acnt_detail_obj;

    protected $class_name;

    /**
     * コンストラクタ
     *
     * @access public
     * @param Acnt $acnt_obj
     */
    public function __construct(Acnt $acnt_obj, AcntDetail $acnt_detail_obj)
    {
        $this->acnt_obj = $acnt_obj;
        $this->acnt_detail_obj = $acnt_detail_obj;
        $this->class_name = get_class($this);
        islog($this->class_name, __FUNCTION__);
    }

    /**
     * [検索]アカウント情報コレクション取得
     *
     * 検索条件にマッチした全てのアカウント情報を取得
     *
     * @access private
     * @param string $company_no
     * @param string $company_branch_no
     * @param string $company_department_no
     * @param string $free_word
     * @return \Illuminate\Database\Eloquent\Collection
     * @see AcntRepository
     * @throws QueryException
     * @todo 【MySQL暫定対応】MySQLバージョンを5.7以上にあげたら、free_word検索のロジックを変更
     */
    private function getAcntCollection($company_no, $company_branch_no, $company_department_no, $free_word)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'company_no' => $company_no,
            'company_branch_no' => $company_branch_no,
            'company_department_no' => $company_department_no,
            'free_word' => $free_word
        ]);

        // データ取得
        islog($this->class_name, __FUNCTION__, 'm2');
        DB::enableQueryLog();
        $ret = DB::table('acnt_t as a_t')->select('a_t.id', 'a_t.comp_no', 'a_t.comp_branch_no', 'a_t.comp_department_no', 'a_t.acnt_no', DB::raw('concat(IFNULL(a_t.acnt_sei,""), IFNULL(a_t.acnt_mei,"")) as acnt_name'), DB::raw('concat(IFNULL(a_t.acnt_kana_sei,""), IFNULL(a_t.acnt_kana_mei,"")) as acnt_kananame'), 'a_t.acnt_id', 'a_t.acnt_tel1', 'a_t.acnt_tel2', 'a_t.email', 'a_t.acnt_email2', 'a_t.acnt_position_no', 'a_t.acnt_position_other_name', 'a_t.acnt_auth_no', 'a_t.acnt_auth_other_name', 'ad_t.acnt_detail_zip_code', DB::raw('concat(IFNULL(pf_m.pref_name,""), IFNULL(ad_t.acnt_detail_city,""), IFNULL(ad_t.acnt_detail_town,""), IFNULL(ad_t.acnt_detail_address,"")) as acnt_address'), 'c_t.comp_no', 'c_t.comp_name', 'cb_t.comp_branch_no', 'cb_t.comp_branch_name', 'cd_t.comp_department_no', 'cd_t.comp_department_name', 'p_m.position_no', 'p_m.position_name')
            ->join('acnt_detail_t as ad_t', function ($join) {
            $join->on('a_t.acnt_no', 'ad_t.acnt_no')
                ->on('a_t.comp_no', 'ad_t.comp_no')
                ->where('ad_t.delete_flg', 0);
        })
            ->leftjoin('pref_m as pf_m', function ($join) {
            $join->on('ad_t.acnt_detail_pref_code', 'pf_m.pref_no');
        })
            ->join('comp_t as c_t', function ($join) {
            $join->on('a_t.comp_no', 'c_t.comp_no')
                ->where('c_t.delete_flg', 0);
        })
            ->leftjoin('comp_branch_t as cb_t', function ($join) {
            $join->on('a_t.comp_branch_no', 'cb_t.comp_branch_no')
                ->on('a_t.comp_no', 'cb_t.comp_no')
                ->where('cb_t.delete_flg', 0);
        })
            ->leftjoin('comp_department_t as cd_t', function ($join) {
            $join->on('a_t.comp_department_no', 'cd_t.comp_department_no')
                ->on('a_t.comp_no', 'cd_t.comp_no')
                ->on('a_t.comp_branch_no', 'cd_t.comp_branch_no')
                ->where('cd_t.delete_flg', 0);
        })
            ->leftjoin('position_m as p_m', function ($join) {
            $join->on('a_t.acnt_position_no', 'p_m.position_no');
        })
            ->where('a_t.delete_flg', 0)
            ->where('a_t.comp_no', $company_no)
            ->when(! is_null($company_branch_no), function ($query) use ($company_branch_no) {
            $query->where('a_t.comp_branch_no', $company_branch_no);
        })
            ->when(! is_null($company_department_no), function ($query) use ($company_department_no) {
            $query->where('a_t.comp_department_no', $company_department_no);
        })
            ->when(! empty($free_word), function ($query) use ($free_word) {
            $query->where('a_t.acnt_sei', 'like', "%$free_word%")
                ->orWhere('a_t.acnt_mei', 'like', "%$free_word%")
                ->orWhere('a_t.acnt_kana_sei', 'like', "%$free_word%")
                ->orWhere('a_t.acnt_kana_mei', 'like', "%$free_word%")
                ->orWhere('ad_t.acnt_detail_zip_code', 'like', "%$free_word%")
                ->orWhere('ad_t.acnt_detail_city', 'like', "%$free_word%")
                ->orWhere('ad_t.acnt_detail_town', 'like', "%$free_word%")
                ->orWhere('ad_t.acnt_detail_address', 'like', "%$free_word%")
                ->orWhere('pf_m.pref_name', 'like', "%$free_word%")
                ->orWhere('c_t.comp_name', 'like', "%$free_word%")
                ->orWhere('cb_t.comp_branch_name', 'like', "%$free_word%")
                ->orWhere('cd_t.comp_department_name', 'like', "%$free_word%")
                ->orWhere('p_m.position_name', 'like', "%$free_word%");
            /*
             * $query->whereRaw('match (a_t.acnt_sei, a_t.acnt_mei, a_t.acnt_kana_sei, a_t.acnt_kana_mei) against ("' . $free_word . '" IN NATURAL LANGUAGE MODE)')
             * ->orWhereRaw('match (ad_t.acnt_detail_zip_code, ad_t.acnt_detail_city, ad_t.acnt_detail_town, ad_t.acnt_detail_address) against ("' . $free_word . '" IN NATURAL LANGUAGE MODE)')
             * ->orWhereRaw('match (pf_m.pref_name) against ("' . $free_word . '" IN NATURAL LANGUAGE MODE)')
             * ->orWhereRaw('match (c_t.comp_name) against ("' . $free_word . '" IN NATURAL LANGUAGE MODE)')
             * ->orWhereRaw('match (cb_t.comp_branch_name,cb_t.comp_branch_city,cb_t.comp_branch_town,cb_t.comp_branch_address) against ("' . $free_word . '" IN NATURAL LANGUAGE MODE)')
             * ->orWhereRaw('match (cd_t.comp_department_name) against ("' . $free_word . '" IN NATURAL LANGUAGE MODE)')
             * ->orWhereRaw('match (p_m.position_name) against ("' . $free_word . '" IN NATURAL LANGUAGE MODE)');
             */
        });

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * [検索]アカウント情報一覧ページネーション取得
     *
     * 検索条件にマッチした全てのアカウント情報を取得
     *
     * @access public
     * @param string $company_no
     * @param string $company_branch_no
     * @param string $company_department_no
     * @param string $free_word
     * @parama int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     * @see AcntRepository
     * @throws QueryException
     * @todo
     */
    public function getAcntPagenationList($company_no, $company_branch_no, $company_department_no, $free_word, $limit = null)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'company_no' => $company_no,
            'company_branch_no' => $company_branch_no,
            'company_department_no' => $company_department_no,
            'free_word' => $free_word,
            'limit' => $limit
        ]);

        // アカウント一覧のコレクションのみ取得
        $ret_collection = $this->getAcntCollection($company_no, $company_branch_no, $company_department_no, $free_word);
        // ページネーション込みで確定
        $limit = ! is_null($limit) ? $limit : getDefaultPagenation(); // 取得件数確定
        $ret = $ret_collection->orderBy('a_t.updated_at', 'desc')->paginate($limit);
        dslog($this->class_name, __FUNCTION__, 'm22', DB::getQueryLog());

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * [検索]アカウント情報一覧取得
     *
     * 検索条件にマッチした全てのアカウント情報を取得
     *
     * @access public
     * @param string $company_no
     * @param string $company_branch_no
     * @param string $company_department_no
     * @param string $free_word
     * @parama int $start
     * @parama int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     * @see AcntRepository
     * @throws QueryException
     * @todo
     */
    public function getAcntList($company_no, $company_branch_no, $company_department_no, $free_word, $start = 0, $limit = null)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'company_no' => $company_no,
            'company_branch_no' => $company_branch_no,
            'company_department_no' => $company_department_no,
            'free_word' => $free_word,
            'start' => $start
        ]);

        // アカウント一覧のコレクションのみ取得
        $ret_collection = $this->getAcntCollection($company_no, $company_branch_no, $company_department_no, $free_word);
        if (is_null($limit)) {
            // 全件取得で確定
            $ret = $ret_collection->get();
        } else {
            // 指定開始位置から指定件数取得で確定
            $ret = $ret_collection->offset($start)
                ->limit($limit)
                ->get();
        }
        dslog($this->class_name, __FUNCTION__, 'm22', DB::getQueryLog());

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * [検索]アカウント情報取得
     *
     * アカウント番号によりアカウント情報を取得
     *
     * @access public
     * @param string $company_no
     * @param string $acnt_no
     * @param string $acnt_id
     * @param int $delete_flg
     * @return object
     * @see AcntRepository
     * @throws QueryException
     * @todo
     */
    public function getAcntData($company_no, $acnt_no, $acnt_id, $delete_flg)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'company_no' => $company_no,
            'acnt_no' => $acnt_no,
            'acnt_id' => $acnt_id,
            'delete_flg' => $delete_flg
        ]);

        // データ取得
        islog($this->class_name, __FUNCTION__, 'm2');
        DB::enableQueryLog();
        $ret = DB::table('acnt_t as a_t')->select('a_t.id', 'a_t.comp_no', 'a_t.comp_branch_no', 'a_t.comp_department_no', 'a_t.acnt_no', 'a_t.acnt_sei', 'a_t.acnt_mei', DB::raw('concat(IFNULL(a_t.acnt_sei,""), IFNULL(a_t.acnt_mei,"")) as acnt_name'), 'a_t.acnt_kana_sei', 'a_t.acnt_kana_mei', DB::raw('concat(IFNULL(a_t.acnt_kana_sei,""), IFNULL(a_t.acnt_kana_mei,"")) as acnt_kananame'), 'a_t.acnt_id', 'a_t.acnt_tel1', 'a_t.acnt_tel2', 'a_t.email', 'a_t.acnt_email2', 'a_t.acnt_position_no', 'a_t.acnt_position_other_name', 'a_t.acnt_auth_no', 'a_t.acnt_auth_other_name', 'ad_t.acnt_detail_zip_code', 'ad_t.acnt_detail_pref_code', 'ad_t.acnt_detail_city', 'ad_t.acnt_detail_town', 'ad_t.acnt_detail_address', 'pf_m.pref_name', 'c_t.comp_no', 'c_t.comp_name', 'c_t.comp_type', 'cb_t.comp_branch_no', 'cb_t.comp_branch_name', 'cd_t.comp_department_no', 'cd_t.comp_department_name', 'p_m.position_no', 'p_m.position_name')
            ->join('acnt_detail_t as ad_t', function ($join) {
            $join->on('a_t.acnt_no', 'ad_t.acnt_no')
                ->on('a_t.comp_no', 'ad_t.comp_no')
                ->where('ad_t.delete_flg', 0);
        })
            ->leftjoin('pref_m as pf_m', function ($join) {
            $join->on('ad_t.acnt_detail_pref_code', 'pf_m.pref_no');
        })
            ->join('comp_t as c_t', function ($join) {
            $join->on('a_t.comp_no', 'c_t.comp_no')
                ->where('c_t.delete_flg', 0);
        })
            ->leftjoin('comp_branch_t as cb_t', function ($join) {
            $join->on('a_t.comp_branch_no', 'cb_t.comp_branch_no')
                ->on('a_t.comp_no', 'cb_t.comp_no')
                ->where('cb_t.delete_flg', 0);
        })
            ->leftjoin('comp_department_t as cd_t', function ($join) {
            $join->on('a_t.comp_department_no', 'cd_t.comp_department_no')
                ->on('a_t.comp_no', 'cd_t.comp_no')
                ->on('a_t.comp_branch_no', 'cd_t.comp_branch_no')
                ->where('cd_t.delete_flg', 0);
        })
            ->leftjoin('position_m as p_m', function ($join) {
            $join->on('a_t.acnt_position_no', 'p_m.position_no');
        })
            ->when(! is_null($delete_flg), function ($query) use ($delete_flg) {
            $query->where('a_t.delete_flg', $delete_flg);
        })
            ->when(! is_null($company_no), function ($query) use ($company_no) {
            $query->where('a_t.comp_no', $company_no);
        })
            ->when(! is_null($acnt_no), function ($query) use ($acnt_no) {
            $query->whereRaw('upper(a_t.acnt_no) = ?', strtoupper($acnt_no));
        })
            ->when(! is_null($acnt_id), function ($query) use ($acnt_id) {
            $query->whereRaw('upper(a_t.acnt_id) = ?', strtoupper($acnt_id));
        })
            ->first();
        dslog($this->class_name, __FUNCTION__, 'm22', DB::getQueryLog());

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * [登録]アカウント情報登録
     *
     * 有効なアカウント情報を追加
     *
     * @access public
     * @param array $insert_data
     * @param array $insert_detail_data
     * @return Boolean
     * @see AcntRepository
     * @throws QueryException
     * @todo
     */
    public function insertAcntData($insert_data, $insert_detail_data)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'insert_data' => $insert_data,
            'insert_detail_data' => $insert_detail_data
        ]);

        // アカウント登録
        islog($this->class_name, __FUNCTION__, 'm4');
        DB::beginTransaction();
        islog($this->class_name, __FUNCTION__, 'm5');
        DB::enableQueryLog();
        $ret = $this->acnt_obj->insert($insert_data);
        if (empty($ret)) {
            // 登録失敗
            eslog($this->class_name, __FUNCTION__, 'm14', $ret);
            islog($this->class_name, __FUNCTION__, 'm8');
            DB::rollBack();
            return $ret;
        }
        dslog($this->class_name, __FUNCTION__, 'm22', DB::getQueryLog());

        // アカウント詳細登録
        DB::enableQueryLog();
        $ret = $this->acnt_detail_obj->insert($insert_detail_data);
        if (empty($ret)) {
            // 登録失敗
            eslog($this->class_name, __FUNCTION__, 'm15', $ret);
            islog($this->class_name, __FUNCTION__, 'm8');
            DB::rollBack();
            return $ret;
        }
        dslog($this->class_name, __FUNCTION__, 'm22', DB::getQueryLog());

        islog($this->class_name, __FUNCTION__, 'm9');
        DB::commit();

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * [更新]アカウント情報更新
     *
     * 指定のアカウント情報を更新
     *
     * @access public
     * @param array $update_data
     * @param array $update_detail_data
     * @param string $company_no
     * @param string $acnt_no
     * @param string $acnt_id
     * @return Boolean
     * @see AcntRepository
     * @throws QueryException
     * @todo
     */
    public function updateAcntData($update_data, $update_detail_data, $company_no, $acnt_no, $acnt_id)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'update_data' => $update_data,
            'update_detail_data' => $update_detail_data,
            'company_no' => $company_no,
            'acnt_no' => $acnt_no,
            'acnt_id' => $acnt_id
        ]);

        // アカウント情報更新
        islog($this->class_name, __FUNCTION__, 'm4');
        DB::beginTransaction();
        islog($this->class_name, __FUNCTION__, 'm6');
        DB::enableQueryLog();
        $ret = $this->acnt_obj->where('delete_flg', 0)
            ->where('comp_no', $company_no)
            ->where('acnt_no', $acnt_no)
            ->where('acnt_id', $acnt_id)
            ->update($update_data);
        if (empty($ret)) {
            // 更新失敗
            eslog($this->class_name, __FUNCTION__, 'm16', $ret);
            islog($this->class_name, __FUNCTION__, 'm8');
            DB::rollBack();
            return $ret;
        }

        // アカウント詳細更新
        DB::enableQueryLog();
        $ret = $this->acnt_detail_obj->where('delete_flg', 0)
            ->where('comp_no', $company_no)
            ->where('acnt_no', $acnt_no)
            ->update($update_detail_data);
        if (empty($ret)) {
            // 更新失敗
            eslog($this->class_name, __FUNCTION__, 'm17', $ret);
            islog($this->class_name, __FUNCTION__, 'm8');
            DB::rollBack();
            return $ret;
        }
        dslog($this->class_name, __FUNCTION__, 'm22', DB::getQueryLog());

        islog($this->class_name, __FUNCTION__, 'm9');
        DB::commit();

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * [削除]アカウント情報削除
     *
     * 有効なアカウント情報を削除(削除フラグを更新)
     *
     * @access public
     * @param array $delete_data
     * @param string $company_no
     * @param string $acnt_no
     * @return Boolean
     * @see AcntRepository
     * @throws QueryException
     * @todo
     */
    public function deleteAcntData($delete_data, $company_no, $acnt_no)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'delete_data' => $delete_data,
            'company_no' => $company_no,
            'acnt_no' => $acnt_no
        ]);

        // アカウント情報削除
        islog($this->class_name, __FUNCTION__, 'm4');
        DB::beginTransaction();
        islog($this->class_name, __FUNCTION__, 'm7');
        DB::enableQueryLog();
        $ret = $this->acnt_obj->where('delete_flg', 0)
            ->where('comp_no', $company_no)
            ->whereIn('acnt_no', $delete_data)
            ->update([
            'last_acnt_no' => $acnt_no,
            'delete_flg' => 1
        ]); // 最終更新者, 削除フラグON
        if (empty($ret)) {
            // 削除失敗（削除フラグ更新失敗）
            eslog($this->class_name, __FUNCTION__, 'm18', $ret);
            islog($this->class_name, __FUNCTION__, 'm8');
            DB::rollBack();
            return $ret;
        }
        dslog($this->class_name, __FUNCTION__, 'm22', DB::getQueryLog());

        // アカウント詳細削除
        DB::enableQueryLog();
        $ret = $this->acnt_detail_obj->where('delete_flg', 0)
            ->where('comp_no', $company_no)
            ->whereIn('acnt_no', $delete_data)
            ->update([
            'last_acnt_no' => $acnt_no,
            'delete_flg' => 1
        ]); // 最終更新者, 削除フラグON
        if (empty($ret)) {
            // 削除失敗（削除フラグ更新失敗）
            eslog($this->class_name, __FUNCTION__, 'm19', $ret);
            islog($this->class_name, __FUNCTION__, 'm8');
            DB::rollBack();
            return $ret;
        }
        dslog($this->class_name, __FUNCTION__, 'm22', DB::getQueryLog());

        // 削除完了
        islog($this->class_name, __FUNCTION__, 'm9');
        DB::commit();

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }
}