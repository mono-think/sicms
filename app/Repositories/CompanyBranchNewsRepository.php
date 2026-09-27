<?php
/**
 * [DB]comp_branch_news_tテーブルのRepositoryクラス
 *
 * comp_branch_news_tテーブルをメインとする企業支店情報の検索、登録、更新、削除処理
 *
 * @access public
 * @author Seiji Ishikawa（メールアドレス）
 * @copyright Webじゃ軒 All Rights Reserved
 * @package repository
 */
namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use App\Models\CompanyBranchNews;

class CompanyBranchNewsRepository implements CompanyBranchNewsRepositoryInterface
{

    protected $comp_branch_news_obj;

    protected $class_name;

    /**
     * コンストラクタ
     *
     * @access public
     * @param CompanyBranchNews $comp_branch_news_obj
     */
    public function __construct(CompanyBranchNews $comp_branch_news_obj)
    {
        $this->comp_branch_news_obj = $comp_branch_news_obj;
        $this->class_name = get_class($this);
        islog($this->class_name, __FUNCTION__);
    }

    /**
     * [検索]企業支店ニュース情報コレクション取得
     *
     * 検索条件にマッチした全ての企業支店ニュース情報を取得
     *
     * @access private
     * @param string $company_no
     * @return \Illuminate\Database\Eloquent\Collection
     * @see CompanyBranchNewsRepository
     * @throws QueryException
     * @todo
     */
    private function getCompanyBranchNewsCollection($company_no)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'company_no' => $company_no
        ]);

        // データ取得
        islog($this->class_name, __FUNCTION__, 'm2');
        DB::enableQueryLog();
        $ret = DB::table('comp_branch_news_t as cbn_t')->select('cbn_t.comp_branch_news_no', 'cbn_t.comp_branch_no', 'cbn_t.comp_no', 'cbn_t.comp_branch_news_title', 'cbn_t.comp_branch_news_text', 'cbn_t.created_at', 'cbn_t.updated_at', 'c_t.comp_name', 'c_t.comp_banner_image_no', 'cb_t.comp_branch_name', 'i_t.image_category', 'i_t.image_no', 'i_t.image_name', 'i_t.image_folder', 'i_t.image_type', 'i_t.image_alt', 'i_t.image_text')
            ->join('comp_t as c_t', function ($join) {
            $join->where('c_t.delete_flg', '0')
                ->where('c_t.comp_type', '<>', '0')
                ->on('cbn_t.comp_no', 'c_t.comp_no');
        })
            ->leftjoin('image_t as i_t', function ($join) {
            $join->where('i_t.image_category', getImageCompanyCategory())
                ->on('c_t.comp_no', 'i_t.comp_no')
                ->on('c_t.comp_banner_image_no', 'i_t.image_no');
        })
            ->join('comp_branch_t as cb_t', function ($join) {
            $join->where('cb_t.delete_flg', '0')
                ->on('cbn_t.comp_no', 'cb_t.comp_no')
                ->on('cbn_t.comp_branch_no', 'cb_t.comp_branch_no');
        })
            ->when(! is_null($company_no), function ($query) use ($company_no) {
            return $query->where('cbn_t.comp_no', $company_no);
        });

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * [検索]企業支店ニュース情報一覧ページネーション取得
     *
     * 検索条件にマッチした全ての企業支店ニュース情報を取得
     *
     * @access public
     * @param string $company_no
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     * @see CompanyBranchNewsRepository
     * @throws QueryException
     * @todo
     */
    public function getCompanyBranchNewsPagenationList($company_no, $limit = null)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'company_no' => $company_no,
            'limit' => $limit
        ]);

        // 支店ニュース一覧のコレクションのみ取得
        $ret_collection = $this->getCompanyBranchNewsCollection($company_no);
        // ページネーション込みで確定
        $limit = ! is_null($limit) ? $limit : getDefaultPagenation(); // 取得件数確定
        $ret = $ret_collection->orderBy('cbn_t.updated_at', 'desc')->paginate($limit);
        dslog($this->class_name, __FUNCTION__, 'm22', DB::getQueryLog());

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * [検索]企業支店ニュース情報一覧取得（条件指定）
     *
     * 検索条件にマッチした全ての企業支店ニュース情報を取得
     *
     * @access public
     * @param string $company_no
     * @param int $start
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     * @see CompanyBranchNewsRepository
     * @throws QueryException
     * @todo
     */
    public function getCompanyBranchNewsList($company_no, $start = 0, $limit = null)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'company_no' => $company_no,
            'start' => $start,
            'limit' => $limit
        ]);

        // 支店ニュース一覧のコレクションのみ取得
        $ret_collection = $this->getCompanyBranchNewsCollection($company_no);
        if (is_null($limit)) {
            // 全件取得で確定
            $ret = $ret_collection->orderBy('cbn_t.updated_at', 'desc')->get();
        } else {
            // 指定開始位置から指定件数取得で確定
            $ret = $ret_collection->orderBy('cbn_t.updated_at', 'desc')
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
     * [検索]企業支店ニュース情報取得
     *
     * 企業支店番号によりニュース情報を取得
     *
     * @access public
     * @param string $company_no
     * @param int $company_branch_news_no
     * @param string $company_branch_no
     * @return object
     * @see CompanyBranchNewsRepository
     * @throws QueryException
     * @todo
     */
    public function getCompanyBranchNewsData($company_no, $company_branch_news_no, $company_branch_no)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'company_no' => $company_no,
            'company_branch_news_no' => $company_branch_news_no,
            'company_branch_no' => $company_branch_no
        ]);

        // データ取得
        islog($this->class_name, __FUNCTION__, 'm2');
        DB::enableQueryLog();
        $ret = DB::table('comp_branch_news_t as cbn_t')->select('cbn_t.comp_branch_news_no', 'cbn_t.comp_branch_no', 'cbn_t.comp_no', 'cbn_t.comp_branch_news_title', 'cbn_t.comp_branch_news_text', 'cbn_t.created_at', 'cbn_t.updated_at', 'c_t.comp_name', 'c_t.comp_banner_image_no', 'cb_t.comp_branch_name', 'i_t.image_category', 'i_t.image_no', 'i_t.image_name', 'i_t.image_folder', 'i_t.image_type', 'i_t.image_alt', 'i_t.image_text')
            ->join('comp_t as c_t', function ($join) {
            $join->where('c_t.delete_flg', '0')
                ->where('c_t.comp_type', '<>', '0')
                ->on('cbn_t.comp_no', 'c_t.comp_no');
        })
            ->join('comp_branch_t as cb_t', function ($join) {
            $join->where('cb_t.delete_flg', '0')
                ->on('cbn_t.comp_no', 'cb_t.comp_no')
                ->on('cbn_t.comp_branch_no', 'cb_t.comp_branch_no');
        })
            ->leftjoin('image_t as i_t', function ($join) {
            $join->where('i_t.image_category', getImageCompanyCategory())
                ->on('c_t.comp_no', 'i_t.comp_no')
                ->on('c_t.comp_banner_image_no', 'i_t.image_no');
        })
            ->where('cbn_t.comp_no', $company_no)
            ->where('cbn_t.comp_branch_no', $company_branch_no)
            ->where('cbn_t.comp_branch_news_no', $company_branch_news_no)
            ->first();
        dslog($this->class_name, __FUNCTION__, 'm22', DB::getQueryLog());

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }
}