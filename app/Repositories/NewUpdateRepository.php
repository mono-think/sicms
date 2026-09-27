<?php
/**
 * [DB]製品、企業、イベント、コンテンツのRepositoryクラス
 *
 * トップページ周りの検索処理
 *
 * @access public
 * @author Seiji Ishikawa（メールアドレス）
 * @copyright Webじゃ軒 All Rights Reserved
 * @package repository
 */
namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Carbon\Carbon;

class NewUpdateRepository implements NewUpdateRepositoryInterface
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
     * [検索]全新着情報の一覧取得
     *
     * 検索条件にマッチした全ての新着情報を取得
     *
     * @access public
     * @param int $limit
     * @return array $ret
     * @see NewUpdateRepository
     * @throws QueryException
     * @todo
     */
    public function getNewUpdateList($limit)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'limit_no' => $limit
        ]);

        //変換パラメータ
        $disp_image = getConstsType('image_disp'); // 変換後画像サイズ（配列）
        $image_type = getConstsType('image_type'); // 変換後画像タイプ（配列）

        // 全新着情報取得
        islog($this->class_name, __FUNCTION__, 'm2');
        $cb = new Carbon(); // 現在日時
        $now_datetime = $cb->format('Y-m-d H:i:s');
        DB::enableQueryLog();

        // 企業情報取得
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
        $ret_company = DB::table('comp_t as c_t')->select(DB::raw('"company" as page'), 'c_t.comp_no as param1', DB::raw('"" as param2'), DB::raw('"" as param3'), 'c_t.comp_name as title', DB::raw('(case when (c_t.updated_at < cb_t.updated_at and cb_t.updated_at < cd_t.updated_at and cd_t.updated_at < cbn_t.updated_at) then cbn_t.comp_branch_news_title else "" end) as title_text'), DB::raw('(case when (c_t.updated_at < cb_t.updated_at and cb_t.updated_at < cd_t.updated_at and cd_t.updated_at < cbn_t.updated_at) then cbn_t.updated_at when (c_t.updated_at < cb_t.updated_at and cb_t.updated_at < cd_t.updated_at and cbn_t.updated_at < cd_t.updated_at) then cd_t.updated_at when (c_t.updated_at < cb_t.updated_at and cd_t.updated_at < cb_t.updated_at and cbn_t.updated_at < cb_t.updated_at) then cb_t.updated_at else c_t.updated_at end) as update_date'), 'c_t.comp_banner_image_no as image_no', 'i_t.image_category  as image_category', 'i_t.image_name as image_name', 'i_t.image_folder as image_folder', 'i_t.image_type as image_type', 'i_t.image_alt as image_alt', 'i_t.image_text as image_text');
        // route('company_shop.show')のURL（修正時注意）
        $ret_company = $ret_company->selectRaw('concat(?,"/company_shop/",c_t.comp_no,"/detail") as url', [
            url('/')
        ]); // URL生成
        $ret_company = $ret_company->selectRaw('case when i_t.image_name is not null then concat(?,"/", i_t.image_folder, "/", i_t.image_name, "_500.", i_t.image_type) else ? end as img_file', [ // 画像パス生成(imgのみ)
            getImagePublicFullpath(),
            getNoimagePath()
        ]);
        foreach ($disp_image as $idx=>$disp) {
            $ret_company = $ret_company->selectRaw('case when i_t.image_name is not null then concat(?,"/", i_t.image_folder, "/", i_t.image_name,"_",?, ".",i_t.image_type, " ",?,"w") else ? end as ' . 'img_srcset_' . $idx, [ // 画像パス生成(imgタグのsrcsetパス)
                getImagePublicFullpath(),
                $disp,
                $disp,
                getNoimagePath($disp)
            ]);
            $ret_company = $ret_company->selectRaw('case when i_t.image_name is not null then concat(?,"/", i_t.image_folder, "/", i_t.image_name,"_",?, ".",?, " ",?,"w") else ? end as ' . 'source_srcset_' . $idx, [ // 画像パス生成(sourceタグのsrcsetパス)
                getImagePublicFullpath(),
                $disp,
                $image_type[1],
                $disp,
                getNoimagePath($disp, $image_type[1])
            ]);
        }
        $ret_company = $ret_company->selectRaw('? as source_srcset_type', [ // 画像タイプ生成(sourceタグの画像タイプ)
            $image_type[1]
        ]);
        $ret_company = $ret_company->leftjoin('image_t as i_t', function ($join) {
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
            ->where('c_t.comp_type', '<>', '0');

        // イベント情報取得
        $ret_event = DB::table('event_t as e_t')->select(DB::raw('"event" as page'), 'e_t.event_mgr_no as param1', DB::raw('"" as param2'), DB::raw('"" as param3'), 'e_t.event_name as title', DB::raw('"" as title_text'), 'e_t.updated_at as update_date', 'e_t.event_image_no as image_no', 'i_t.image_category as image_category', 'i_t.image_name as image_name', 'i_t.image_folder as image_folder', 'i_t.image_type as image_type', 'i_t.image_alt as image_alt', 'i_t.image_text as image_text');
        // route('front_event.show')のURL（修正時注意）
        $ret_event = $ret_event->selectRaw('concat(?,"/event/",e_t.event_mgr_no,"/detail") as url', [
            url('/')
        ]); // URL生成
        $ret_event = $ret_event->selectRaw('case when i_t.image_name is not null then concat(?,"/", i_t.image_folder, "/", i_t.image_name, "_500.", i_t.image_type) else ? end as img_file', [ // 画像パス生成(imgのみ)
            getImagePublicFullpath(),
            getNoimagePath()
        ]);
        foreach ($disp_image as $idx=>$disp) {
            $ret_event = $ret_event->selectRaw('case when i_t.image_name is not null then concat(?,"/", i_t.image_folder, "/", i_t.image_name,"_",?, ".",i_t.image_type, " ",?,"w") else ? end as ' . 'img_srcset_' . $idx, [ // 画像パス生成(imgタグのsrcsetパス)
                getImagePublicFullpath(),
                $disp,
                $disp,
                getNoimagePath($disp)
            ]);
            $ret_event = $ret_event->selectRaw('case when i_t.image_name is not null then concat(?,"/", i_t.image_folder, "/", i_t.image_name,"_",?, ".",?, " ",?,"w") else ? end as ' . 'source_srcset_' . $idx, [ // 画像パス生成(sourceタグのsrcsetパス)
                getImagePublicFullpath(),
                $disp,
                $image_type[1],
                $disp,
                getNoimagePath($disp, $image_type[1])
            ]);
        }
        $ret_event = $ret_event->selectRaw('? as source_srcset_type', [ // 画像タイプ生成(sourceタグの画像タイプ)
            $image_type[1]
        ]);
        $ret_event = $ret_event->leftjoin('image_t as i_t', function ($join) {
            $join->where('i_t.image_category', getImageEventCategory())
                ->on('e_t.event_image_no', 'i_t.image_no');
        })
            ->whereDate('e_t.release_start', '<=', $now_datetime)
            ->whereDate('e_t.release_end', '>=', $now_datetime);

        // コンテンツ情報取得
        $ret_content = DB::table('contents_t as cst_t')->select(DB::raw('"contents" as page'), 'cst_t.contents_type as param1', 'cst_t.contents_mgr_no as param2', DB::raw('"" as param3'), 'cst_t.contents_title as title', DB::raw('"" as title_text'), 'cst_t.updated_at as update_date', 'cst_t.contents_image_no as image_no', 'i_t.image_category as image_category', 'i_t.image_name as image_name', 'i_t.image_folder as image_folder', 'i_t.image_type as image_type', 'i_t.image_alt as image_alt', 'i_t.image_text as image_text');
        // route('front_content.show')のURL（修正時注意）
        $ret_content = $ret_content->selectRaw('concat(?,"/content/",cst_t.contents_type,"/detail?n=", cst_t.contents_mgr_no) as url', [
            url('/')
        ]); // URL生成
        $ret_content = $ret_content->selectRaw('case when i_t.image_name is not null then concat(?,"/", i_t.image_folder, "/", i_t.image_name, "_500.", i_t.image_type) else ? end as img_file', [ // 画像パス生成(imgのみ)
            getImagePublicFullpath(),
            getNoimagePath()
        ]);
        foreach ($disp_image as $idx=>$disp) {
            $ret_content = $ret_content->selectRaw('case when i_t.image_name is not null then concat(?,"/", i_t.image_folder, "/", i_t.image_name,"_",?, ".",i_t.image_type, " ",?,"w") else ? end as ' . 'img_srcset_' . $idx, [ // 画像パス生成(imgタグのsrcsetパス)
                getImagePublicFullpath(),
                $disp,
                $disp,
                getNoimagePath($disp)
            ]);
            $ret_content = $ret_content->selectRaw('case when i_t.image_name is not null then concat(?,"/", i_t.image_folder, "/", i_t.image_name,"_",?, ".",?, " ",?,"w") else ? end as ' . 'source_srcset_' . $idx, [ // 画像パス生成(sourceタグのsrcsetパス)
                getImagePublicFullpath(),
                $disp,
                $image_type[1],
                $disp,
                getNoimagePath($disp, $image_type[1])
            ]);
        }
        $ret_content = $ret_content->selectRaw('? as source_srcset_type', [ // 画像タイプ生成(sourceタグの画像タイプ)
            $image_type[1]
        ]);
        $ret_content = $ret_content->leftjoin('image_t as i_t', function ($join) {
            $join->where('i_t.image_category', getImageContentsCategory())
                ->on('cst_t.contents_image_no', 'i_t.image_no');
        })
            ->whereDate('cst_t.release_start', '<=', $now_datetime)
            ->whereDate('cst_t.release_end', '>=', $now_datetime);

        // 製品情報取得（企業情報、イベント情報、コンテンツ情報、これらをユニオン結合）
        // 製品詳細サブクエリ
        $ret_products_detail = DB::table('products_detail_t')->select('products_no', 'comp_no', DB::raw('(substring_index(group_concat(products_detail_no order by updated_at desc separator ",:;"),",:;",1)) as products_detail_no'), DB::raw('(substring_index(group_concat(products_detail_title order by updated_at desc separator ",:;"),",:;",1)) as products_detail_title'), DB::raw('(max(updated_at)) as updated_at'), DB::raw('(substring_index(group_concat(products_detail_image_no order by updated_at desc separator ",:;"),",:;",1)) as products_detail_image_no'))
            ->whereDate('release_start', '<=', $now_datetime)
            ->whereDate('release_end', '>=', $now_datetime)
            ->groupBy('comp_no', 'products_no')
            ->orderBy('updated_at', 'desc');

        // 製品クエリ
        $ret = DB::table('products_t as p_t')->select(DB::raw('"products" as page'), 'p_t.products_category_no as param1', 'p_t.products_no as param2', 'pd_t.products_detail_no as param3', 'p_t.products_name as title', 'pd_t.products_detail_title as title_text', 'pd_t.updated_at as update_date', 'pd_t.products_detail_image_no as image_no', 'i_t.image_category as image_category', 'i_t.image_name as image_name', 'i_t.image_folder as image_folder', 'i_t.image_type as image_type', 'i_t.image_alt as image_alt', 'i_t.image_text as image_text');
        // route('front_products.show')のURL（修正時注意）
        $ret = $ret->selectRaw('concat(?,"/products/",p_t.products_category_no,"/detail?n=",p_t.products_no,"&d=",pd_t.products_detail_no) as url', [
            url('/')
        ]); // URL生成
        $ret = $ret->selectRaw('case when i_t.image_name is not null then concat(?,"/", i_t.image_folder, "/", i_t.image_name, "_500.", i_t.image_type) else ? end as img_file', [ // 画像パス生成(imgのみ)
            getImagePublicFullpath(),
            getNoimagePath()
        ]);
        foreach ($disp_image as $idx=>$disp) {
            $ret = $ret->selectRaw('case when i_t.image_name is not null then concat(?,"/", i_t.image_folder, "/", i_t.image_name,"_",?, ".",i_t.image_type, " ",?,"w") else ? end as ' . 'img_srcset_' . $idx, [ // 画像パス生成(imgタグのsrcsetパス)
                getImagePublicFullpath(),
                $disp,
                $disp,
                getNoimagePath($disp)
            ]);
            $ret = $ret->selectRaw('case when i_t.image_name is not null then concat(?,"/", i_t.image_folder, "/", i_t.image_name,"_",?, ".",?, " ",?,"w") else ? end as ' . 'source_srcset_' . $idx, [ // 画像パス生成(sourceタグのsrcsetパス)
                getImagePublicFullpath(),
                $disp,
                $image_type[1],
                $disp,
                getNoimagePath($disp, $image_type[1])
            ]);
        }
        $ret = $ret->selectRaw('? as source_srcset_type', [ // 画像タイプ生成(sourceタグの画像タイプ)
            $image_type[1]
        ]);
        $ret = $ret->JoinSub($ret_products_detail, 'pd_t', function ($join) {
            $join->on('p_t.comp_no', 'pd_t.comp_no')
                ->on('p_t.products_no', 'pd_t.products_no');
        })
            ->leftjoin('image_t as i_t', function ($join) {
            $join->where('i_t.image_category', getImageProductsCategory())
                ->on('pd_t.comp_no', 'i_t.comp_no')
                ->on('pd_t.products_detail_image_no', 'i_t.image_no');
        })
            ->whereDate('p_t.release_start', '<=', $now_datetime)
            ->whereDate('p_t.release_end', '>=', $now_datetime)
            ->union($ret_company)
            ->union($ret_event)
            ->union($ret_content)
            ->orderBy('update_date', 'desc')
            ->take($limit)
            ->get();
        dslog($this->class_name, __FUNCTION__, 'm22', DB::getQueryLog());

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }
}