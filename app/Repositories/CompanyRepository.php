<?php
/**
 * [DB]comp_tテーブルのRepositoryクラス
 *
 * comp_tテーブルをメインとする企業情報の検索、登録、更新、削除処理
 *
 * @access public
 * @author Seiji Ishikawa（メールアドレス）
 * @copyright Webじゃ軒 All Rights Reserved
 * @package repository
 */
namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class CompanyRepository implements CompanyRepositoryInterface
{

    protected $class_name;

    /**
     * コンストラクタ
     *
     * @access public
     */
    public function __construct()
    {
        $this->class_name = get_class($this);
        islog($this->class_name, __FUNCTION__);
    }

    /**
     * [検索]企業情報コレクション取得
     *
     * 検索条件にマッチした全ての企業情報を取得
     *
     * @access private
     * @param string $search_tag
     * @return \Illuminate\Database\Eloquent\Collection
     * @see CompanyRepository
     * @throws QueryException
     * @todo
     */
    private function getCompanyCollection($search_tag = null)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'tag' => $search_tag
        ]);

        // データ取得
        islog($this->class_name, __FUNCTION__, 'm2');
        DB::enableQueryLog();

        // 企業支店サブクエリ
        $ret_company_branch = DB::table('comp_branch_t')->select('comp_no', DB::raw('(max(updated_at)) as updated_at'))
            ->where('delete_flg', '0')
            ->groupBy('comp_no')
            ->orderBy('updated_at', 'desc');
        // 企業部署サブクエリ
        $ret_company_department = DB::table('comp_department_t')->select('comp_no', DB::raw('(max(updated_at)) as updated_at'))
            ->where('delete_flg', '0')
            ->groupBy('comp_no')
            ->orderBy('updated_at', 'desc');
        // 企業支店ニュースサブクエリ
        $ret_company_branch_news = DB::table('comp_branch_news_t')->select('comp_no', DB::raw('(substring_index(group_concat(comp_branch_news_title order by updated_at desc separator ",:;"),",:;",1)) as comp_branch_news_title'), DB::raw('(max(updated_at)) as updated_at'))
            ->groupBy('comp_no')
            ->orderBy('updated_at', 'desc');

        // 企業クエリ（企業支店、企業部署、企業支店ニュースと結合）
        $ret = DB::table('comp_t as c_t')->select('c_t.comp_no', 'c_t.comp_name', DB::raw('(greatest(IFNULL(c_t.updated_at,0), ifnull(cb_t.updated_at,0), ifnull(cd_t.updated_at,0), ifnull(cbn_t.updated_at,0))) as update_date'), 'i_m.industry_name', 'bf_m.bformat_name', 'c_t.comp_business_text', 'c_t.comp_banner_image_no', 'i_t.image_category', 'i_t.image_no', 'i_t.image_name', 'i_t.image_folder', 'i_t.image_type', 'i_t.image_alt', 'i_t.image_text')
            ->join('industry_m as i_m', function ($join) {
            $join->on('c_t.industry_no', 'i_m.industry_no');
        })
            ->join('bformat_m as bf_m', function ($join) {
            $join->on('c_t.bformat_no', 'bf_m.bformat_no');
        })
            ->leftjoin('image_t as i_t', function ($join) {
            $join->where('i_t.image_category', getImageCompanyCategory())
                ->on('c_t.comp_no', 'i_t.comp_no')
                ->on('c_t.comp_banner_image_no', 'i_t.image_no');
        })
            ->leftJoinSub($ret_company_branch, 'cb_t', function ($join) {
            $join->on('c_t.comp_no', 'cb_t.comp_no');
        })
            ->leftJoinSub($ret_company_department, 'cd_t', function ($join) {
            $join->on('cb_t.comp_no', 'cd_t.comp_no');
        })
            ->leftJoinSub($ret_company_branch_news, 'cbn_t', function ($join) {
            $join->on('cb_t.comp_no', 'cbn_t.comp_no');
        })
            ->where('c_t.delete_flg', '0')
            ->where('c_t.comp_type', '<>', '0')
            ->when(! is_null($search_tag), function ($query) use ($search_tag) {
            return $query->where('c_t.search_tag', 'like', "%$search_tag%");
        });

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * [検索]企業情報一覧ページネーション取得
     *
     * 検索条件にマッチした全ての企業情報を取得
     *
     * @access public
     * @param string $search_tag
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     * @see CompanyRepository
     * @throws QueryException
     * @todo
     */
    public function getCompanyPagenationList($search_tag = null, $limit = null)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'tag' => $search_tag,
            'limit' => $limit
        ]);

        // 企業一覧のコレクションのみ取得
        $ret_collection = $this->getCompanyCollection($search_tag);
        // ページネーション込みで確定
        $limit = ! is_null($limit) ? $limit : getDefaultPagenation(); // 取得件数確定
        $ret = $ret_collection->orderBy('update_date', 'desc')->paginate($limit);
        dslog($this->class_name, __FUNCTION__, 'm22', DB::getQueryLog());

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * [検索]企業情報一覧取得
     *
     * 検索条件にマッチした全ての企業情報を取得
     *
     * @access public
     * @param string $search_tag
     * @param int $start
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     * @see CompanyRepository
     * @throws QueryException
     * @todo
     */
    public function getCompanyList($search_tag = null, $start = 0, $limit = null)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'tag' => $search_tag,
            'start' => $start,
            'limit' => $limit
        ]);

        // 企業一覧のコレクションのみ取得
        $ret_collection = $this->getCompanyCollection($search_tag);
        if (is_null($limit)) {
            // 全件取得で確定
            $ret = $ret_collection->orderBy('update_date', 'desc')->get();
        } else {
            // 指定開始位置から指定件数取得で確定
            $ret = $ret_collection->orderBy('update_date', 'desc')
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
     * [検索]企業情報取得
     *
     * 企業番号により企業情報を取得
     *
     * @access public
     * @param string $company_no
     * @return object
     * @see CompanyRepository
     * @throws QueryException
     * @todo
     */
    public function getCompanyData($company_no)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'company_no' => $company_no
        ]);

        // 企業情報取得
        islog($this->class_name, __FUNCTION__, 'm2');
        DB::enableQueryLog();
        $ret = DB::table('comp_t as c_t')->select('c_t.comp_no', 'c_t.comp_name', 'c_t.industry_no', 'i_m.industry_name', 'c_t.bformat_no', 'bf_m.bformat_name', 'c_t.comp_business_text', 'c_t.comp_banner_image_no', 'c_t.comp_url', 'c_t.comp_type', 'c_t.page_title', 'c_t.search_tag', 'i_t.image_category', 'i_t.image_no', 'i_t.image_name', 'i_t.image_folder', 'i_t.image_type', 'i_t.image_alt', 'i_t.image_text')
            ->join('industry_m as i_m', function ($join) {
            $join->on('c_t.industry_no', 'i_m.industry_no');
        })
            ->join('bformat_m as bf_m', function ($join) {
            $join->on('c_t.bformat_no', 'bf_m.bformat_no');
        })
            ->leftjoin('image_t as i_t', function ($join) {
            $join->where('i_t.image_category', getImageCompanyCategory())
                ->on('c_t.comp_no', 'i_t.comp_no')
                ->on('c_t.comp_banner_image_no', 'i_t.image_no');
        })
            ->where('c_t.delete_flg', '0')
            ->where('c_t.comp_type', '<>', '0')
            ->where('c_t.comp_no', $company_no)
            ->first();
        dslog($this->class_name, __FUNCTION__, 'm22', DB::getQueryLog());

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * [検索]企業新着情報の一覧取得
     *
     * 検索条件にマッチした全ての新着情報を取得
     *
     * @access public
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     * @see CompanyRepository
     * @throws QueryException
     * @todo
     */
    public function getCompanyNewUpdateList($limit)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'limit_no' => $limit
        ]);

        // データ取得
        islog($this->class_name, __FUNCTION__, 'm2');
        DB::enableQueryLog();

        // 企業支店サブクエリ
        $ret_company_branch = DB::table('comp_branch_t')->select('comp_no', DB::raw('(max(updated_at)) as updated_at'))
            ->where('delete_flg', '0')
            ->groupBy('comp_no')
            ->orderBy('updated_at', 'desc');
        // 企業部署サブクエリ
        $ret_company_department = DB::table('comp_department_t')->select('comp_no', DB::raw('(max(updated_at)) as updated_at'))
            ->where('delete_flg', '0')
            ->groupBy('comp_no')
            ->orderBy('updated_at', 'desc');
        // 企業支店ニュースサブクエリ
        $ret_company_branch_news = DB::table('comp_branch_news_t')->select('comp_no', DB::raw('(substring_index(group_concat(comp_branch_news_title order by updated_at desc separator ",:;"),",:;",1)) as comp_branch_news_title'), DB::raw('(max(updated_at)) as updated_at'))
            ->groupBy('comp_no')
            ->orderBy('updated_at', 'desc');

        // 企業クエリ（企業支店、企業部署、企業支店ニュースと結合）
        $ret = DB::table('comp_t as c_t')->select(DB::raw('"company" as page'), 'c_t.comp_no as param1', DB::raw('"" as param2'), DB::raw('"" as param3'), 'c_t.comp_name as title', DB::raw('(case when (c_t.updated_at < cb_t.updated_at and cb_t.updated_at < cd_t.updated_at and cd_t.updated_at < cbn_t.updated_at) then cbn_t.comp_branch_news_title else "" end) as title_text'), DB::raw('(greatest(IFNULL(c_t.updated_at,0), ifnull(cb_t.updated_at,0), ifnull(cd_t.updated_at,0), ifnull(cbn_t.updated_at,0))) as update_date'), 'c_t.comp_banner_image_no as image_no', 'i_t.image_category  as image_category', 'i_t.image_name as image_name', 'i_t.image_folder as image_folder', 'i_t.image_type as image_type', 'i_t.image_alt as image_alt', 'i_t.image_text as image_text');
        // route('company_shop.show')のURL（修正時注意）
        $ret = $ret->selectRaw('concat(?,"/company_shop/",c_t.comp_no,"/detail") as url', [
            url('/')
        ]); // URL生成
        $ret = $ret->selectRaw('case when i_t.image_name is not null then concat(?,"/", i_t.image_folder, "/", i_t.image_name, "_500.", i_t.image_type) else ? end as img_file', [ // 画像パス生成(imgのみ)
            getImagePublicFullpath(),
            getNoimagePath()
        ]);
        $ret = $ret->join('industry_m as i_m', function ($join) {
            $join->on('c_t.industry_no', 'i_m.industry_no');
        })
            ->join('bformat_m as bf_m', function ($join) {
            $join->on('c_t.bformat_no', 'bf_m.bformat_no');
        })
            ->leftjoin('image_t as i_t', function ($join) {
            $join->where('i_t.image_category', getImageCompanyCategory())
                ->on('c_t.comp_no', 'i_t.comp_no')
                ->on('c_t.comp_banner_image_no', 'i_t.image_no');
        })
            ->leftJoinSub($ret_company_branch, 'cb_t', function ($join) {
            $join->on('c_t.comp_no', 'cb_t.comp_no');
        })
            ->leftJoinSub($ret_company_department, 'cd_t', function ($join) {
            $join->on('cb_t.comp_no', 'cd_t.comp_no');
        })
            ->leftJoinSub($ret_company_branch_news, 'cbn_t', function ($join) {
            $join->on('cb_t.comp_no', 'cbn_t.comp_no');
        })
            ->where('c_t.delete_flg', '0')
            ->where('c_t.comp_type', '<>', '0')
            ->orderBy('update_date', 'desc')
            ->take($limit)
            ->get();
        dslog($this->class_name, __FUNCTION__, 'm22', DB::getQueryLog());

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }
}