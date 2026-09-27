<?php
/**
 * [DB]comp_branch_tテーブルのRepositoryクラス
 *
 * comp_branch_tテーブルをメインとする企業支店情報の検索、登録、更新、削除処理
 *
 * @access public
 * @author Seiji Ishikawa（メールアドレス）
 * @copyright Webじゃ軒 All Rights Reserved
 * @package repository
 */
namespace App\Repositories\Manage;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use App\Models\CompanyBranch;

class CompanyBranchRepository implements CompanyBranchRepositoryInterface
{

    protected $comp_branch_obj;

    protected $class_name;

    /**
     * コンストラクタ
     *
     * @access public
     * @param CompanyBranch $comp_branch_obj
     */
    public function __construct(CompanyBranch $comp_branch_obj)
    {
        $this->comp_branch_obj = $comp_branch_obj;
        $this->class_name = get_class($this);
        islog($this->class_name, __FUNCTION__);
    }

    /**
     * [検索]企業支店情報コレクション取得
     *
     * 検索条件にマッチした全ての企業支店情報を取得
     *
     * @access private
     * @param string $company_no
     * @param string $free_word
     * @return \Illuminate\Database\Eloquent\Collection
     * @see CompanyBranchRepository
     * @throws QueryException
     * @todo 【MySQL暫定対応】MySQLバージョンを5.7以上にあげたら、free_word検索のロジックを変更
     */
    private function getCompanyBranchCollection($company_no, $free_word)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'company_no' => $company_no,
            'free_word' => $free_word
        ]);

        // データ取得
        islog($this->class_name, __FUNCTION__, 'm2');
        DB::enableQueryLog();
        $ret = DB::table('comp_branch_t as cb_t')->select('cb_t.comp_branch_no', 'cb_t.comp_no', 'cb_t.comp_branch_name', 'cb_t.comp_branch_zip_code', DB::raw('concat(IFNULL(pf_m.pref_name,""), IFNULL(cb_t.comp_branch_city,""), IFNULL(cb_t.comp_branch_town,""), IFNULL(cb_t.comp_branch_address,"")) as cb_address'), 'cb_t.comp_branch_order', 'cb_t.suppoter_flg')
            ->leftjoin('pref_m as pf_m', function ($join) {
            $join->on('cb_t.comp_branch_pref_code', 'pf_m.pref_no');
        })
            ->where('cb_t.delete_flg', '0')
            ->where('cb_t.comp_no', $company_no)
            ->when(! empty($free_word), function ($query) use ($free_word) {
            $query->where('cb_t.comp_branch_name', 'like', "%$free_word%")
                ->orWhere('cb_t.comp_branch_city', 'like', "%$free_word%")
                ->orWhere('cb_t.comp_branch_town', 'like', "%$free_word%")
                ->orWhere('cb_t.comp_branch_address', 'like', "%$free_word%");
            /*
             * $query->whereRaw('match (cb_t.comp_branch_name, cb_t.comp_branch_city, cb_t.comp_branch_town, cb_t.comp_branch_address) against ("' . $free_word . '" IN NATURAL LANGUAGE MODE)')
             * ->orWhereRaw('match (pf_m.pref_name) against ("' . $free_word . '" IN NATURAL LANGUAGE MODE)');
             */
        });

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * [検索]企業支店情報一覧ページネーション取得
     *
     * 検索条件にマッチした全ての企業支店情報を取得
     *
     * @access public
     * @param string $company_no
     * @param string $free_word
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     * @see CompanyBranchRepository
     * @throws QueryException
     * @todo
     */
    public function getCompanyBranchPagenationList($company_no, $free_word, $limit = null)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'company_no' => $company_no,
            'free_word' => $free_word,
            'limit' => $limit
        ]);

        // 支店一覧のコレクションのみ取得
        $ret_collection = $this->getCompanyBranchCollection($company_no, $free_word);
        // ページネーション込みで確定
        $limit = ! is_null($limit) ? $limit : getDefaultPagenation(); // 取得件数確定
        $ret = $ret_collection->orderBy('comp_branch_order', 'asc')->paginate($limit);
        dslog($this->class_name, __FUNCTION__, 'm22', DB::getQueryLog());

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * [検索]企業支店情報一覧取得
     *
     * 検索条件にマッチした全ての企業支店情報を取得
     *
     * @access public
     * @param string $company_no
     * @param string $free_word
     * @param int $start
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     * @see CompanyBranchRepository
     * @throws QueryException
     * @todo
     */
    public function getCompanyBranchList($company_no, $free_word, $start = 0, $limit = null)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'company_no' => $company_no,
            'free_word' => $free_word,
            'start' => $start,
            'limit' => $limit
        ]);

        // 支店一覧のコレクションのみ取得
        $ret_collection = $this->getCompanyBranchCollection($company_no, $free_word);
        if (is_null($limit)) {
            // 全件取得で確定
            $ret = $ret_collection->orderBy('comp_branch_order', 'asc')->get();
        } else {
            // 指定開始位置から指定件数取得で確定
            $ret = $ret_collection->orderBy('comp_branch_order', 'asc')
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
     * [検索]企業支店情報取得
     *
     * 企業支店番号により企業情報を取得
     *
     * @access public
     * @param string $company_no
     * @param string $company_branch_no
     * @param int $delete_flg
     * @return object
     * @see CompanyBranchRepository
     * @throws QueryException
     * @todo
     */
    public function getCompanyBranchData($company_no, $company_branch_no, $delete_flg)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'company_no' => $company_no,
            'company_branch_no' => $company_branch_no,
            'delete_flg' => $delete_flg
        ]);

        // データ取得
        islog($this->class_name, __FUNCTION__, 'm2');
        DB::enableQueryLog();
        $ret = DB::table('comp_branch_t as cb_t')->select('cb_t.comp_branch_no', 'cb_t.comp_no', 'cb_t.comp_branch_name', 'cb_t.comp_branch_zip_code', 'cb_t.comp_branch_pref_code', 'cb_t.comp_branch_city', 'cb_t.comp_branch_town', 'cb_t.comp_branch_address', 'cb_t.comp_branch_order', 'cb_t.suppoter_flg', 'pf_m.pref_name')
            ->leftjoin('pref_m as pf_m', function ($join) {
            $join->on('cb_t.comp_branch_pref_code', 'pf_m.pref_no');
        })
            ->when(! is_null($delete_flg), function ($query) use ($delete_flg) {
            $query->where('cb_t.delete_flg', $delete_flg);
        })
            ->where('cb_t.comp_no', $company_no)
            ->whereRaw('upper(cb_t.comp_branch_no) = ?', strtoupper($company_branch_no))
            ->first();
        dslog($this->class_name, __FUNCTION__, 'm22', DB::getQueryLog());

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * [登録]企業支店情報登録
     *
     * 有効な企業支店情報を追加
     *
     * @access public
     * @param array $insert_data
     * @return Boolean
     * @see CompanyBranchRepository
     * @throws QueryException
     * @todo
     */
    public function insertCompanyBranchData($insert_data)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'insert_data' => $insert_data
        ]);

        // 支店登録
        islog($this->class_name, __FUNCTION__, 'm4');
        DB::beginTransaction();
        islog($this->class_name, __FUNCTION__, 'm5');
        DB::enableQueryLog();
        $ret = $this->comp_branch_obj->insert($insert_data);
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
     * [更新]企業支店情報更新
     *
     * 指定の企業支店情報を更新
     *
     * @access public
     * @param array $update_data
     * @param string $company_no
     * @param string $company_branch_no
     * @return Boolean
     * @see CompanyBranchRepository
     * @throws QueryException
     * @todo
     */
    public function updateCompanyBranchData($update_data, $company_no, $company_branch_no)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'update_data' => $update_data,
            'company_no' => $company_no,
            'company_branch_no' => $company_branch_no
        ]);

        // 支店更新
        islog($this->class_name, __FUNCTION__, 'm4');
        DB::beginTransaction();
        islog($this->class_name, __FUNCTION__, 'm6');
        DB::enableQueryLog();
        $ret = $this->comp_branch_obj->where('comp_no', $company_no)
            ->where('comp_branch_no', $company_branch_no)
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
     * [削除]企業支店情報削除
     *
     * 有効な企業支店情報を削除(削除フラグを更新)
     *
     * @access public
     * @param array $delete_data
     * @param string $company_no
     * @param string $acnt_no
     * @return Boolean
     * @see CompanyBranchRepository
     * @throws QueryException
     * @todo
     */
    public function deleteCompanyBranchData($delete_data, $company_no, $acnt_no)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'delete_data' => $delete_data,
            'company_no' => $company_no,
            'acnt_no' => $acnt_no
        ]);

        // 支店削除
        islog($this->class_name, __FUNCTION__, 'm4');
        DB::beginTransaction();
        islog($this->class_name, __FUNCTION__, 'm7');
        DB::enableQueryLog();
        $ret = $this->comp_branch_obj->where('comp_no', $company_no)
            ->whereIn('comp_branch_no', $delete_data)
            ->update([
            'last_acnt_no' => $acnt_no,
            'delete_flg' => 1
        ]); // 最終更新者, 削除フラグON
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