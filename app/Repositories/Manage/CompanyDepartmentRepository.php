<?php
/**
 * [DB]comp_department_tテーブルのRepositoryクラス
 *
 * comp_department_tテーブルをメインとする企業部署情報の検索、登録、更新、削除処理
 *
 * @access public
 * @author Seiji Ishikawa（メールアドレス）
 * @copyright Webじゃ軒 All Rights Reserved
 * @package repository
 */
namespace App\Repositories\Manage;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use App\Models\CompanyDepartment;

class CompanyDepartmentRepository implements CompanyDepartmentRepositoryInterface
{

    protected $comp_department_obj;

    protected $class_name;

    /**
     * コンストラクタ
     *
     * @access public
     * @param CompanyDepartment $comp_department_obj
     */
    public function __construct(CompanyDepartment $comp_department_obj)
    {
        $this->comp_department_obj = $comp_department_obj;
        $this->class_name = get_class($this);
        islog($this->class_name, __FUNCTION__);
    }

    /**
     * [検索]企業部署情報コレクション取得
     *
     * 検索条件にマッチした全ての企業部署情報を取得
     *
     * @access private
     * @param string $company_no
     * @param string $company_branch_no
     * @param string $free_word
     * @return \Illuminate\Database\Eloquent\Collection
     * @see CompanyDepartmentRepository
     * @throws QueryException
     * @todo 【MySQL暫定対応】MySQLバージョンを5.7以上にあげたら、free_word検索のロジックを変更
     */
    private function getCompanyDepartmentCollection($company_no, $company_branch_no, $free_word)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'company_no' => $company_no,
            'company_branch_no' => $company_branch_no,
            'free_word' => $free_word
        ]);

        // データ取得
        islog($this->class_name, __FUNCTION__, 'm2');
        DB::enableQueryLog();
        $ret = DB::table('comp_department_t')->select('comp_department_no', 'comp_no', 'comp_branch_no', 'comp_department_name', 'comp_department_order', 'comp_department_tel', 'comp_department_fax', 'comp_department_mail', 'comp_department_public_flg')
            ->where('delete_flg', '0')
            ->where('comp_no', $company_no)
            ->where('comp_branch_no', $company_branch_no)
            ->when(! empty($free_word), function ($query) use ($free_word) {
            $query->where('comp_department_name', 'like', "%$free_word%");
            /*
             * $query->whereRaw('match (comp_department_name) against ("' . $free_word . '" IN NATURAL LANGUAGE MODE)');
             */
        });

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * [検索]企業部署情報ページネーション一覧取得
     *
     * 検索条件にマッチした全ての企業部署情報を取得
     *
     * @access public
     * @param string $company_no
     * @param string $company_branch_no
     * @param string $free_word
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     * @see CompanyDepartmentRepository
     * @throws QueryException
     * @todo
     */
    public function getCompanyDepartmentPagenationList($company_no, $company_branch_no, $free_word, $limit = null)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'company_no' => $company_no,
            'company_branch_no' => $company_branch_no,
            'free_word' => $free_word,
            'limit' => $limit
        ]);

        // 部署一覧のコレクションのみ取得
        $ret_collection = $this->getCompanyDepartmentCollection($company_no, $company_branch_no, $free_word);
        // ページネーション込みで確定
        $limit = ! is_null($limit) ? $limit : getDefaultPagenation(); // 取得件数確定
        $ret = $ret_collection->orderBy('comp_department_order', 'asc')->paginate($limit);
        dslog($this->class_name, __FUNCTION__, 'm22', DB::getQueryLog());

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * [検索]企業部署情報一覧取得
     *
     * 検索条件にマッチした全ての企業部署情報を取得
     *
     * @access public
     * @param string $company_no
     * @param string $company_branch_no
     * @param string $free_word
     * @param int $start
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     * @see CompanyDepartmentRepository
     * @throws QueryException
     * @todo
     */
    public function getCompanyDepartmentList($company_no, $company_branch_no, $free_word, $start = 0, $limit = null)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'company_no' => $company_no,
            'company_branch_no' => $company_branch_no,
            'free_word' => $free_word,
            'start' => $start,
            'limit' => $limit
        ]);

        // 部署一覧のコレクションのみ取得
        $ret_collection = $this->getCompanyDepartmentCollection($company_no, $company_branch_no, $free_word);
        if (is_null($limit)) {
            // 全件取得で確定
            $ret = $ret_collection->orderBy('comp_department_order', 'asc')->get();
        } else {
            // 指定開始位置から指定件数取得で確定
            $ret = $ret_collection->orderBy('comp_department_order', 'asc')
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
     * [検索]企業部署情報取得
     *
     * 企業部署番号により企業部署情報を取得
     *
     * @access public
     * @param string $company_no
     * @param string $company_branch_no
     * @param string $company_department_no
     * @param int $delete_flg
     * @return object
     * @see CompanyDepartmentRepository
     * @throws QueryException
     * @todo
     */
    public function getCompanyDepartmentData($company_no, $company_branch_no, $company_department_no, $delete_flg)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'company_no' => $company_no,
            'company_branch_no' => $company_branch_no,
            'company_department_no' => $company_department_no,
            'delete_flg' => $delete_flg
        ]);

        // データ取得
        islog($this->class_name, __FUNCTION__, 'm2');
        DB::enableQueryLog();
        $ret = $this->comp_department_obj->when(! is_null($delete_flg), function ($query) use ($delete_flg) {
            $query->where('delete_flg', $delete_flg);
        })
            ->where('comp_no', $company_no)
            ->where('comp_branch_no', $company_branch_no)
            ->whereRaw('upper(comp_department_no) = ?', strtoupper($company_department_no))
            ->first();
        dslog($this->class_name, __FUNCTION__, 'm22', DB::getQueryLog());

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * [登録]企業部署情報登録
     *
     * 有効な企業部署情報を追加
     *
     * @access public
     * @param array $insert_data
     * @return Boolean
     * @see CompanyDepartmentRepository
     * @throws QueryException
     * @todo
     */
    public function insertCompanyDepartmentData($insert_data)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'insert_data' => $insert_data
        ]);

        // 部署登録
        islog($this->class_name, __FUNCTION__, 'm4');
        DB::beginTransaction();
        islog($this->class_name, __FUNCTION__, 'm5');
        DB::enableQueryLog();
        $ret = $this->comp_department_obj->insert($insert_data);
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
     * [更新]企業部署情報更新
     *
     * 指定の企業部署情報を更新
     *
     * @access public
     * @param array $update_data
     * @param string $company_no
     * @param string $company_branch_no
     * @param string $company_department_no
     * @return Boolean
     * @see CompanyDepartmentRepository
     * @throws QueryException
     * @todo
     */
    public function updateCompanyDepartmentData($update_data, $company_no, $company_branch_no, $company_department_no)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'update_data' => $update_data,
            'company_no' => $company_no,
            'company_branch_no' => $company_branch_no,
            'company_department_no' => $company_department_no
        ]);

        // 部署更新
        islog($this->class_name, __FUNCTION__, 'm4');
        DB::beginTransaction();
        islog($this->class_name, __FUNCTION__, 'm6');
        DB::enableQueryLog();
        $ret = $this->comp_department_obj->where('comp_no', $company_no)
            ->where('comp_branch_no', $company_branch_no)
            ->where('comp_department_no', $company_department_no)
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
     * [削除]企業部署情報削除
     *
     * 有効な企業部署情報を削除(削除フラグを更新)
     *
     * @access public
     * @param array $delete_data
     * @param string $company_no
     * @param string $company_branch_no
     * @param string $acnt_no
     * @return Boolean
     * @see CompanyDepartmentRepository
     * @throws QueryException
     * @todo
     */
    public function deleteCompanyDepartmentData($delete_data, $company_no, $company_branch_no, $acnt_no)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'delete_data' => $delete_data,
            'company_no' => $company_no,
            'company_branch_no' => $company_branch_no,
            'acnt_no' => $acnt_no
        ]);

        // 部署削除
        islog($this->class_name, __FUNCTION__, 'm4');
        DB::beginTransaction();
        islog($this->class_name, __FUNCTION__, 'm7');
        DB::enableQueryLog();
        $ret = $this->comp_department_obj->where('comp_no', $company_no)
            ->where('comp_branch_no', $company_branch_no)
            ->whereIn('comp_department_no', $delete_data)
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