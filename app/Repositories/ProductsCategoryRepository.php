<?php
/**
 * [DB]products_category_mテーブルのRepositoryクラス
 *
 * products_category_mテーブルをメインとする製品カテゴリ情報の検索、登録、更新、削除処理
 *
 * @access public
 * @author Seiji Ishikawa（メールアドレス）
 * @copyright Webじゃ軒 All Rights Reserved
 * @package repository
 */
namespace App\Repositories;

use App\Models\ProductsCategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Carbon\Carbon;

class ProductsCategoryRepository implements ProductsCategoryRepositoryInterface
{

    protected $products_category_obj;

    protected $class_name;

    /**
     * コンストラクタ
     *
     * @access public
     * @param ProductsCategory $products_category
     */
    public function __construct(ProductsCategory $products_category)
    {
        $this->products_category_obj = $products_category;
        $this->class_name = get_class($this);
        islog($this->class_name, __FUNCTION__);
    }

    /**
     * [検索]製品カテゴリ情報コレクション取得
     *
     * 検索条件にマッチした全ての製品カテゴリ情報を取得
     *
     * @access private
     * @param string $company_no
     * @return \Illuminate\Database\Eloquent\Collection
     * @see ProductsCategoryRepository
     * @throws QueryException
     * @todo
     */
    private function getProductsCategoryCollection($company_no)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'company_no' => $company_no
        ]);

        // 検索結果取得
        islog($this->class_name, __FUNCTION__, 'm2');
        $cb = new Carbon(); // 現在日時
        $now_datetime = $cb->format('Y-m-d H:i:s');
        DB::enableQueryLog();

        // 製品詳細サブクエリ
        $ret_products_detail = DB::table('products_detail_t')->select('comp_no', 'products_no', DB::raw('(max(updated_at)) as updated_at'))
            ->whereDate('release_start', '<=', $now_datetime)
            ->whereDate('release_end', '>=', $now_datetime)
            ->groupBy('comp_no', 'products_no')
            ->orderBy('updated_at', 'desc');

        // 製品サブクエリ
        $ret = DB::table('products_t as p_t')->select('pc_m.products_category_no', 'pc_m.products_category_name', DB::raw('(substring_index(group_concat(pd_t.comp_no order by pd_t.updated_at desc separator ",:;"),",:;",1)) as comp_no'), DB::raw('(substring_index(group_concat(c_t.comp_name order by pd_t.updated_at desc separator ",:;"),",:;",1)) as comp_name'), DB::raw('(max(pd_t.updated_at)) as updated_at'))
            ->join('products_category_m as pc_m', function ($join) {
            $join->on('pc_m.products_category_no', 'p_t.products_category_no');
        })
            ->join('comp_t as c_t', function ($join) {
            $join->where('c_t.delete_flg', '0')
                ->where('c_t.comp_type', '<>', '0')
                ->on('c_t.comp_no', 'p_t.comp_no');
        })
            ->JoinSub($ret_products_detail, 'pd_t', function ($join) {
            $join->on('pd_t.comp_no', 'p_t.comp_no')
                ->on('pd_t.products_no', 'p_t.products_no');
        })
            ->when(! is_null($company_no), function ($query) use ($company_no) {
            return $query->where('p_t.comp_no', $company_no);
        })
            ->whereDate('p_t.release_start', '<=', $now_datetime)
            ->whereDate('p_t.release_end', '>=', $now_datetime)
            ->groupBy('pc_m.products_category_no', 'pc_m.products_category_name');
        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * [検索]製品カテゴリ一覧ページネーション取得
     *
     * 検索条件にマッチした全ての製品カテゴリ情報を取得
     *
     * @access public
     * @param string $company_no
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     * @see ProductsCategoryRepository
     * @throws QueryException
     * @todo
     */
    public function getProductsCategoryPagenationList($company_no, $limit = null)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'company_no' => $company_no
        ]);

        // 製品カテゴリ情報一覧のコレクションのみ取得
        $ret_collection = $this->getProductsCategoryCollection($company_no);
        // ページネーション込みで確定
        $limit = ! is_null($limit) ? $limit : getDefaultPagenation(); // 取得件数確定
        $ret = $ret_collection->orderBy('updated_at', 'desc')->paginate($limit);
        dslog($this->class_name, __FUNCTION__, 'm22', DB::getQueryLog());

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * [検索]製品カテゴリ一覧取得
     *
     * 検索条件にマッチした全ての製品カテゴリ情報を取得
     *
     * @access public
     * @param string $company_no
     * @param int $start
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     * @see ProductsCategoryRepository
     * @throws QueryException
     * @todo
     */
    public function getProductsCategoryList($company_no, $start = 0, $limit = null)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'company_no' => $company_no,
            'start' => $start,
            'limit' => $limit
        ]);
        
        // 製品カテゴリ情報一覧のコレクションのみ取得
        $ret_collection = $this->getProductsCategoryCollection($company_no);
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
}