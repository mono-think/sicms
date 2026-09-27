<?php
/**
 * [DB]products_tテーブルのRepositoryクラス
 *
 * products_tテーブルをメインとする製品情報の検索、登録、更新、削除処理
 *
 * @access public
 * @author Seiji Ishikawa（メールアドレス）
 * @copyright Webじゃ軒 All Rights Reserved
 * @package repository
 */
namespace App\Repositories\Manage;

use App\Models\Products;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class ProductsRepository implements ProductsRepositoryInterface
{

    protected $products_obj;

    protected $class_name;

    /**
     * コンストラクタ
     *
     * @access public
     * @param Products $products
     */
    public function __construct(Products $products)
    {
        $this->products_obj = $products;
        $this->class_name = get_class($this);
        islog($this->class_name, __FUNCTION__);
    }

    /**
     * [検索]製品情報コレクション取得
     *
     * 検索条件にマッチした全ての製品情報を取得
     *
     * @access private
     * @param string $company_no
     * @param string $free_word
     * @return \Illuminate\Database\Eloquent\Collection
     * @see ProductsRepository
     * @throws QueryException
     * @todo 【MySQL暫定対応】MySQLバージョンを5.7以上にあげたら、free_word検索のロジックを変更
     */
    private function getProductsCollection($company_no, $free_word)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'company_no' => $company_no,
            'free_word' => $free_word
        ]);

        // データ取得
        islog($this->class_name, __FUNCTION__, 'm2');
        DB::enableQueryLog();
        $ret = DB::table('products_t')->select('products_no', 'products_customer_type', 'products_service_type', 'products_category_no', 'comp_no', 'products_name', 'comp_branch_no', 'comp_department_no', 'acnt_no', 'release_start', 'release_end')
            ->where('comp_no', $company_no)
            ->when(! empty($free_word), function ($query) use ($free_word) {
            $query->where('products_name', 'like', "%$free_word%");
            /*
             * $query->whereRaw('match (products_name) against ("' . $free_word . '" IN NATURAL LANGUAGE MODE)');
             */
        });

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * [検索]製品一覧ページネーション取得
     *
     * 検索条件にマッチした全ての製品情報を取得
     *
     * @access public
     * @param string $company_no
     * @param string $free_word
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     * @see ProductsRepository
     * @throws QueryException
     * @todo
     */
    public function getProductsPagenationList($company_no, $free_word, $limit = null)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'company_no' => $company_no,
            'free_word' => $free_word,
            'limit' => $limit
        ]);

        // 製品情報一覧のコレクションのみ取得
        $ret_collection = $this->getProductsCollection($company_no, $free_word);
        // ページネーション込みで確定
        $limit = ! is_null($limit) ? $limit : getDefaultPagenation(); // 取得件数確定
        $ret = $ret_collection->orderBy('updated_at', 'desc')->paginate($limit);
        dslog($this->class_name, __FUNCTION__, 'm22', DB::getQueryLog());

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * [検索]製品情報取得
     *
     * 製品番号により製品情報を取得
     *
     * @access public
     * @param string $company_no
     * @param string $products_no
     * @return object
     * @see ProductsRepository
     * @throws QueryException
     * @todo
     */
    public function getProductsData($company_no, $products_no)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'company_no' => $company_no,
            'products_no' => $products_no
        ]);

        // データ取得
        islog($this->class_name, __FUNCTION__, 'm2');
        DB::enableQueryLog();
        $ret = DB::table('products_t as p_t')->select('p_t.products_no', 'p_t.products_customer_type', 'p_t.products_service_type', 'p_t.products_category_no', 'pc_m.products_category_name', 'p_t.comp_no', 'p_t.products_name', 'p_t.comp_branch_no', 'cb_t.comp_branch_name', 'p_t.comp_department_no', 'cd_t.comp_department_name', 'p_t.acnt_no', DB::raw('concat(IFNULL(a_t.acnt_sei,""), IFNULL(a_t.acnt_mei,"")) as acnt_name'), 'p_t.release_start', 'p_t.release_end')
            ->join('products_category_m as pc_m', function ($join) {
            $join->on('p_t.products_category_no', '=', 'pc_m.products_category_no');
        })
            ->leftjoin('comp_branch_t as cb_t', function ($join) {
            $join->on('p_t.comp_branch_no', '=', 'cb_t.comp_branch_no')
                ->on('p_t.comp_no', '=', 'cb_t.comp_no')
                ->where('cb_t.delete_flg', '=', '0');
        })
            ->leftjoin('comp_department_t as cd_t', function ($join) {
            $join->on('p_t.comp_department_no', '=', 'cd_t.comp_department_no')
                ->on('p_t.comp_no', '=', 'cd_t.comp_no')
                ->where('cd_t.delete_flg', '=', '0');
        })
            ->leftjoin('acnt_t as a_t', function ($join) {
            $join->on('p_t.acnt_no', '=', 'a_t.acnt_no')
                ->on('p_t.comp_no', '=', 'a_t.comp_no')
                ->where('a_t.delete_flg', '=', '0');
        })
            ->where('p_t.comp_no', $company_no)
            ->whereRaw('upper(p_t.products_no) = ?', strtoupper($products_no))
            ->first();
        dslog($this->class_name, __FUNCTION__, 'm22', DB::getQueryLog());

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * [登録]製品情報登録
     *
     * 有効な製品情報を追加
     *
     * @access public
     * @param array $insert_data
     * @return Boolean
     * @see ProductsRepository
     * @throws QueryException
     * @todo
     */
    public function insertProductsData($insert_data)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'insert_data' => $insert_data
        ]);

        // 製品情報登録
        islog($this->class_name, __FUNCTION__, 'm4');
        DB::beginTransaction();
        islog($this->class_name, __FUNCTION__, 'm5');
        DB::enableQueryLog();
        $ret = $this->products_obj->insert($insert_data);
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
     * [更新]製品情報更新
     *
     * 指定の製品情報を更新
     *
     * @access public
     * @param array $update_data
     * @param string $company_no
     * @param string $products_no
     * @return Boolean
     * @see ProductsRepository
     * @throws QueryException
     * @todo
     */
    public function updateProductsData($update_data, $company_no, $products_no)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'update_data' => $update_data,
            'company_no' => $company_no,
            'products_no' => $products_no
        ]);

        // 製品情報更新
        islog($this->class_name, __FUNCTION__, 'm4');
        DB::beginTransaction();
        islog($this->class_name, __FUNCTION__, 'm6');
        DB::enableQueryLog();
        $ret = $this->products_obj->where('comp_no', $company_no)
            ->where('products_no', $products_no)
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
     * [削除]製品情報削除
     *
     * 有効な製品情報を削除
     *
     * @access public
     * @param array $delete_data
     * @param string $company_no
     * @return Boolean
     * @see ProductsRepository
     * @throws QueryException
     * @todo
     */
    public function deleteProductsData($delete_data, $company_no)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'delete_data' => $delete_data,
            'company_no' => $company_no
        ]);

        // 製品情報削除
        islog($this->class_name, __FUNCTION__, 'm4');
        DB::beginTransaction();
        islog($this->class_name, __FUNCTION__, 'm7');
        DB::enableQueryLog();
        $ret = $this->products_obj->where('comp_no', $company_no)
            ->whereIn('products_no', $delete_data)
            ->delete();
        dslog($this->class_name, __FUNCTION__, 'm22', DB::getQueryLog());

        if (empty($ret)) {
            // 削除失敗
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