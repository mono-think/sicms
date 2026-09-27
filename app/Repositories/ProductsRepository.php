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
namespace App\Repositories;

use App\Models\Products;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Carbon\Carbon;

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
     * [検索]製品新着情報の一覧取得
     *
     * 検索条件にマッチした全ての新着情報を取得
     *
     * @access public
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     * @see ProductsRepository
     * @throws QueryException
     * @todo
     */
    public function getProductsNewUpdateList($limit)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'limit_no' => $limit
        ]);

        // 製品情報取得
        islog($this->class_name, __FUNCTION__, 'm2');
        $cb = new Carbon(); // 現在日時
        $now_datetime = $cb->format('Y-m-d H:i:s');
        DB::enableQueryLog();

        // 製品詳細サブクエリ
        $ret_products_detail = DB::table('products_detail_t')->select('products_no', 'comp_no', DB::raw('(substring_index(group_concat(products_detail_no order by updated_at desc separator ",:;"),",:;",1)) as products_detail_no'), DB::raw('(substring_index(group_concat(products_detail_title order by updated_at desc separator ",:;"),",:;",1)) as products_detail_title'), DB::raw('(max(updated_at)) as updated_at'), DB::raw('(substring_index(group_concat(products_detail_image_no order by updated_at desc separator ",:;"),",:;",1)) as products_detail_image_no'))
            ->whereDate('release_start', '<=', $now_datetime)
            ->whereDate('release_end', '>=', $now_datetime)
            ->groupBy('comp_no', 'products_no')
            ->orderBy('updated_at', 'desc');

        // 製品クエリ（製品詳細と結合）
        $ret = DB::table('products_t as p_t')->select(DB::raw('"products" as page'), 'p_t.products_category_no as param1', 'p_t.products_no as param2', 'pd_t.products_detail_no as param3', 'p_t.products_name as title', 'pd_t.products_detail_title as title_text', 'pd_t.updated_at as update_date', 'pd_t.products_detail_image_no as image_no', 'i_t.image_category as image_category', 'i_t.image_name as image_name', 'i_t.image_folder as image_folder', 'i_t.image_type as image_type', 'i_t.image_alt as image_alt', 'i_t.image_text as image_text');
        // route('front_products.show')のURL（修正時注意）
        $ret = $ret->selectRaw('concat(?,"/products/",p_t.products_category_no,"/detail?n=",p_t.products_no,"&d=",pd_t.products_detail_no) as url', [
            url('/')
        ]); // URL生成
        $ret = $ret->selectRaw('case when i_t.image_name is not null then concat(?,"/", i_t.image_folder, "/", i_t.image_name, "_500.", i_t.image_type) else ? end as img_file', [ // 画像パス生成(imgのみ)
            getImagePublicFullpath(),
            getNoimagePath()
        ]);
        $ret = $ret->JoinSub($ret_products_detail, 'pd_t', function ($join) {
            $join->on('p_t.comp_no', 'pd_t.comp_no')
                ->on('p_t.products_no', 'pd_t.products_no');
        })
            ->leftjoin('image_t as i_t', function ($join) {
            $join->where('i_t.image_category', getImageProductsCategory())
                ->on('pd_t.comp_no', 'i_t.comp_no')
                ->on('pd_t.products_detail_image_no', 'i_t.image_no');
        });
        $ret = $ret->whereDate('p_t.release_start', '<=', $now_datetime)
            ->whereDate('p_t.release_end', '>=', $now_datetime)
            ->orderBy('update_date', 'desc')
            ->take($limit)
            ->get();
        dslog($this->class_name, __FUNCTION__, 'm22', DB::getQueryLog());

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * [検索]製品詳細情報も含めた最新の製品情報コレクション取得
     *
     * 検索条件にマッチした全ての製品情報を取得
     *
     * @access private
     * @param string $products_category_no
     * @param string $company_no
     * @param string $search_tag
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     * @see ProductsRepository
     * @throws QueryException
     * @todo
     */
    private function getProductsAndDetailNewCollection($products_category_no, $company_no, $search_tag = null)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'products_category_no' => $products_category_no,
            'company_no' => $company_no,
            'search_tag' => $search_tag
        ]);

        // 情報取得
        islog($this->class_name, __FUNCTION__, 'm2');
        $cb = new Carbon(); // 現在日時
        $now_datetime = $cb->format('Y-m-d H:i:s');
        DB::enableQueryLog();
        $ret = DB::table('products_t as p_t')->select('p_t.products_no', 'p_t.products_customer_type', 'p_t.products_service_type', 'p_t.products_category_no', 'p_t.comp_no', 'p_t.products_name', 'pd_t.products_detail_no', 'pd_t.products_detail_title', 'pd_t.search_tag', 'pc_m.products_category_name', 'pd_t.products_detail_image_no as image_no', 'i_t.image_category', 'i_t.image_name', 'i_t.image_folder', 'i_t.image_type', 'i_t.image_alt', 'i_t.image_text', 'pd_t.updated_at', 'c_t.comp_name')
            ->join('products_detail_t as pd_t', function ($join) use ($now_datetime, $search_tag) {
            $join->on('p_t.comp_no', 'pd_t.comp_no')
                ->on('p_t.products_no', 'pd_t.products_no')
                ->when(! is_null($search_tag), function ($query) use ($search_tag, $now_datetime) {
                return $query->where('pd_t.search_tag', 'like', "%$search_tag%")
                    ->whereDate('pd_t.release_start', '<=', $now_datetime)
                    ->whereDate('pd_t.release_end', '>=', $now_datetime);
            })
                ->when(is_null($search_tag), function ($query) use ($now_datetime) {
                return $query->whereDate('pd_t.release_start', '<=', $now_datetime)
                    ->whereDate('pd_t.release_end', '>=', $now_datetime);
            });
        })
            ->join('products_category_m as pc_m', function ($join) {
            $join->on('p_t.products_category_no', 'pc_m.products_category_no');
        })
            ->join('comp_t as c_t', function ($join) {
            $join->where('c_t.delete_flg', '0')
                ->where('c_t.comp_type', '<>', '0')
                ->on('p_t.comp_no', 'c_t.comp_no');
        })
            ->leftjoin('image_t as i_t', function ($join) {
            $join->where('i_t.image_category', getImageProductsCategory())
                ->on('pd_t.comp_no', 'i_t.comp_no')
                ->on('pd_t.products_detail_image_no', 'i_t.image_no');
        })
            ->when(! is_null($company_no), function ($query) use ($company_no) {
            return $query->where('p_t.comp_no', $company_no);
        })
            ->when(! is_null($products_category_no), function ($query) use ($products_category_no) {
            return $query->where('p_t.products_category_no', $products_category_no);
        })
            ->whereDate('p_t.release_start', '<=', $now_datetime)
            ->whereDate('p_t.release_end', '>=', $now_datetime);

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * [検索]製品詳細情報も含めた最新の製品一覧ページネーション取得
     *
     * 検索条件にマッチした全ての製品情報を取得
     *
     * @access public
     * @param string $products_category_no
     * @param string $company_no
     * @param string $search_tag
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     * @see ProductsRepository
     * @throws QueryException
     * @todo
     */
    public function getProductsAndDetailNewPagenationList($products_category_no, $company_no, $search_tag = null, $limit = null)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'products_category_no' => $products_category_no,
            'company_no' => $company_no,
            'search_tag' => $search_tag,
            'limit' => $limit
        ]);

        // 製品詳細情報も含めた最新の製品情報コレクションのみ取得
        $ret_collection = $this->getProductsAndDetailNewCollection($products_category_no, $company_no, $search_tag);
        // ページネーション込みで確定
        $limit = ! is_null($limit) ? $limit : getDefaultPagenation(); // 取得件数確定
        $ret = $ret_collection->orderBy('pd_t.updated_at', 'desc')->paginate($limit);
        dslog($this->class_name, __FUNCTION__, 'm22', DB::getQueryLog());

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * [検索]製品詳細情報も含めた最新の製品一覧取得
     *
     * 検索条件にマッチした全ての製品情報を取得
     *
     * @access public
     * @param string $products_category_no
     * @param string $company_no
     * @param string $search_tag
     * @param int $start
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     * @see ProductsRepository
     * @throws QueryException
     * @todo
     */
    public function getProductsAndDetailNewList($products_category_no, $company_no, $search_tag = null, $start = 0, $limit = null)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'products_category_no' => $products_category_no,
            'company_no' => $company_no,
            'search_tag' => $search_tag,
            'start' => $start,
            'limit' => $limit
        ]);

        // 製品詳細情報も含めた最新の製品情報コレクションのみ取得
        $ret_collection = $this->getProductsAndDetailNewCollection($products_category_no, $company_no, $search_tag);
        if (is_null($limit)) {
            // 全件取得で確定
            $ret = $ret_collection->orderBy('pd_t.updated_at', 'desc')->get();
        } else {
            // 指定開始位置から指定件数取得で確定
            $ret = $ret_collection->orderBy('pd_t.updated_at', 'desc')
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
     * [検索]製品詳細情報リスト取得
     *
     * 製品番号により製品詳細情報を取得
     *
     * @access public
     * @param string $products_category_no
     * @param string $products_no
     * @param string $company_no;
     * @return object
     * @see ProductsRepository
     * @throws QueryException
     * @todo
     */
    public function getProductsList($products_category_no, $products_no, $company_no, $start = 0, $limit = null)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'products_category_no' => $products_category_no,
            'products_no' => $products_no,
            'company_no' => $company_no,
            'start' => $start,
            'limit' => $limit
        ]);

        // 製品詳細情報取得
        islog($this->class_name, __FUNCTION__, 'm2');
        $cb = new Carbon(); // 現在日時
        $now_datetime = $cb->format('Y-m-d H:i:s');
        DB::enableQueryLog();

        $ret_collection = DB::table('products_t as p_t')->select('p_t.products_no', 'p_t.products_customer_type', 'p_t.products_service_type', 'p_t.products_category_no', 'p_t.comp_no', 'c_t.comp_name', 'p_t.created_at', 'pc_m.products_category_name', 'pd_t.products_detail_no', 'pd_t.products_detail_text', 'pd_t.search_tag', 'p_t.products_name', 'pd_t.products_detail_title', 'pd_t.products_detail_description', 'pd_t.products_detail_image_no as image_no', 'i_t.image_category', 'i_t.image_name', 'i_t.image_folder', 'i_t.image_type', 'i_t.image_alt', 'i_t.image_text', 'pd_t.updated_at')
            ->join('products_category_m as pc_m', function ($join) {
            $join->on('p_t.products_category_no', 'pc_m.products_category_no');
        })
            ->join('comp_t as c_t', function ($join) {
            $join->where('c_t.delete_flg', '0')
                ->where('c_t.comp_type', '<>', '0')
                ->on('p_t.comp_no', 'c_t.comp_no');
        })
            ->leftjoin('products_detail_t as pd_t', function ($join) use ($now_datetime) {
            $join->on('p_t.comp_no', 'pd_t.comp_no')
                ->on('p_t.products_no', 'pd_t.products_no')
                ->whereDate('pd_t.release_start', '<=', $now_datetime)
                ->whereDate('pd_t.release_end', '>=', $now_datetime);
        })
            ->leftjoin('image_t as i_t', function ($join) {
            $join->where('i_t.image_category', getImageProductsCategory())
                ->on('pd_t.comp_no', 'i_t.comp_no')
                ->on('pd_t.products_detail_image_no', 'i_t.image_no');
        })
            ->when(! is_null($company_no), function ($query) use ($company_no) {
            return $query->where('p_t.comp_no', $company_no);
        })
            ->when(! is_null($products_category_no), function ($query) use ($products_category_no) {
            return $query->where('p_t.products_category_no', $products_category_no);
        })
            ->when(! is_null($products_no), function ($query) use ($products_no) {
            return $query->where('p_t.products_no', $products_no);
        })
            ->whereDate('p_t.release_start', '<=', $now_datetime)
            ->whereDate('p_t.release_end', '>=', $now_datetime);
        if (is_null($limit)) {
            // 全件取得で確定
            $ret = $ret_collection->orderBy('pd_t.updated_at', 'desc')->get();
        } else {
            // 指定開始位置から指定件数取得で確定
            $ret = $ret_collection->orderBy('pd_t.updated_at', 'desc')
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
     * [検索]製品詳細情報取得
     *
     * 製品と詳細番号、レビュー番号などにより製品詳細情報を取得
     *
     * @access public
     * @param string $products_category_no
     * @param string $products_no
     * @param string $products_detail_no
     * @param string $company_no;
     * @return object
     * @see ProductsRepository
     * @throws QueryException
     * @todo
     */
    public function getProductsDetailData($products_category_no, $products_no, $products_detail_no, $company_no)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'products_category_no' => $products_category_no,
            'products_no' => $products_no,
            'products_detail_no' => $products_detail_no,
            'company_no' => $company_no
        ]);

        // 製品詳細情報取得
        islog($this->class_name, __FUNCTION__, 'm2');
        $cb = new Carbon(); // 現在日時
        $now_datetime = $cb->format('Y-m-d H:i:s');
        DB::enableQueryLog();

        $ret = DB::table('products_t as p_t')->select('p_t.products_no', 'p_t.products_customer_type', 'p_t.products_service_type', 'p_t.products_category_no', 'p_t.comp_no', 'c_t.comp_name', 'p_t.created_at', 'pc_m.products_category_name', 'pd_t.products_detail_no', 'pd_t.products_detail_text', 'pd_t.search_tag', 'p_t.products_name', 'pd_t.products_detail_title', 'pd_t.products_detail_description', 'pd_t.products_detail_image_no as image_no', 'i_t.image_category', 'i_t.image_name', 'i_t.image_folder', 'i_t.image_type', 'i_t.image_alt', 'i_t.image_text', 'pd_t.updated_at')
            ->join('products_category_m as pc_m', function ($join) {
            $join->on('p_t.products_category_no', 'pc_m.products_category_no');
        })
            ->join('comp_t as c_t', function ($join) {
            $join->where('c_t.delete_flg', '0')
                ->where('c_t.comp_type', '<>', '0')
                ->on('p_t.comp_no', 'c_t.comp_no');
        })
            ->join('products_detail_t as pd_t', function ($join) use ($products_detail_no, $now_datetime) {
            $join->on('p_t.comp_no', 'pd_t.comp_no')
                ->on('p_t.products_no', 'pd_t.products_no')
                ->where('pd_t.products_detail_no', $products_detail_no)
                ->whereDate('pd_t.release_start', '<=', $now_datetime)
                ->whereDate('pd_t.release_end', '>=', $now_datetime);
        })
            ->leftjoin('image_t as i_t', function ($join) {
            $join->where('i_t.image_category', getImageProductsCategory())
                ->on('pd_t.comp_no', 'i_t.comp_no')
                ->on('pd_t.products_detail_image_no', 'i_t.image_no');
        })
            ->when(! is_null($company_no), function ($query) use ($company_no) {
            return $query->where('p_t.comp_no', $company_no);
        })
            ->where('p_t.products_category_no', $products_category_no)
            ->where('p_t.products_no', $products_no)
            ->whereDate('p_t.release_start', '<=', $now_datetime)
            ->whereDate('p_t.release_end', '>=', $now_datetime)
            ->first();
        dslog($this->class_name, __FUNCTION__, 'm22', DB::getQueryLog());

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }
}