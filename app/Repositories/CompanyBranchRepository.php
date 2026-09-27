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
namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use App\Models\CompanyBranch;

class CompanyBranchRepository implements CompanyBranchFrontRepositoryInterface
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
     * @return \Illuminate\Database\Eloquent\Collection
     * @see CompanyBranchRepository
     * @throws QueryException
     * @todo
     */
    private function getCompanyBranchCollection($company_no)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'company_no' => $company_no
        ]);

        // データ取得
        islog($this->class_name, __FUNCTION__, 'm2');
        DB::enableQueryLog();
        $ret = DB::table('comp_branch_t as cb_t')->select('cb_t.comp_branch_no', 'cb_t.comp_no', 'cb_t.comp_branch_name', 'cb_t.comp_branch_zip_code', DB::raw('concat(IFNULL(pf_m.pref_name,""), IFNULL(cb_t.comp_branch_city,""), IFNULL(cb_t.comp_branch_town,""), IFNULL(cb_t.comp_branch_address,"")) as cb_address'), 'cb_t.comp_branch_order', DB::raw('concat("[",group_concat("{",
                    "\"comp_department_name\":","\"",cd_t.comp_department_name,"\"",
                    ",\"comp_department_tel\":","\"",cd_t.comp_department_tel,"\"",
                    ",\"comp_department_fax\":","\"",cd_t.comp_department_fax,"\"",
                    ",\"comp_department_mail\":","\"",cd_t.comp_department_mail,"\"",
                    ",\"sns_no1\":","\"",IFNULL(cd_t.sns_no1,""),"\"",
                    ",\"sns_url1\":","\"",IFNULL(cd_t.sns_url1,""),"\"",
                    ",\"sns_no2\":","\"",IFNULL(cd_t.sns_no2,""),"\"",
                    ",\"sns_url2\":","\"",IFNULL(cd_t.sns_url2,""),"\"",
                    ",\"sns_no3\":","\"",IFNULL(cd_t.sns_no3,""),"\"",
                    ",\"sns_url3\":","\"",IFNULL(cd_t.sns_url3,""),"\"",
                    ",\"sns_no4\":","\"",IFNULL(cd_t.sns_no4,""),"\"",
                    ",\"sns_url4\":","\"",IFNULL(cd_t.sns_url4,""),"\"",
                    ",\"sns_no5\":","\"",IFNULL(cd_t.sns_no5,""),"\"",
                    ",\"sns_url5\":","\"",IFNULL(cd_t.sns_url5,""),"\"}" 
                    order by cd_t.comp_department_order asc separator ","),"]") as cd_data'))
            ->join('pref_m as pf_m', function ($join) {
            $join->on('cb_t.comp_branch_pref_code', 'pf_m.pref_no');
        })
            ->leftjoin('comp_department_t as cd_t', function ($join) {
            $join->where('cd_t.delete_flg', '0')
                ->where('cd_t.comp_department_public_flg', '1')
                ->on('cb_t.comp_no', 'cd_t.comp_no')
                ->on('cb_t.comp_branch_no', 'cd_t.comp_branch_no');
        })
            ->where('cb_t.delete_flg', '0')
            ->where('cb_t.comp_no', $company_no)
            ->groupBy('cb_t.comp_branch_no', 'cb_t.comp_no', 'cb_t.comp_branch_name', 'cb_t.comp_branch_zip_code', 'cb_t.comp_branch_pref_code', 'cb_t.comp_branch_city', 'pf_m.pref_name', 'cb_t.comp_branch_town', 'cb_t.comp_branch_address', 'cb_t.comp_branch_order');

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
     * @param int $start
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     * @see CompanyBranchRepository
     * @throws QueryException
     * @todo
     */
    public function getCompanyBranchList($company_no, $start = 0, $limit = null)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'company_no' => $company_no,
            'start' => $start,
            'limit' => $limit
        ]);

        // 支店一覧のコレクションのみ取得
        $ret_collection = $this->getCompanyBranchCollection($company_no);
        if (is_null($limit)) {
            // 全件取得で確定
            $ret = $ret_collection->orderBy('cb_t.comp_branch_order', 'asc')->get();
        } else {
            // 指定開始位置から指定件数取得で確定
            $ret = $ret_collection->orderBy('cb_t.comp_branch_order', 'asc')
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
     * [検索]サポーター支店を持つ企業情報一覧を取得
     *
     * 指定パラメータによりサポーター支店を持つ企業情報一覧を取得
     *
     * @access public
     * @param int $suppoter_flg
     * @return \Illuminate\Database\Eloquent\Collection
     * @see CompanyBranchRepository
     * @throws QueryException
     * @todo
     */
    public function getCompanySupporterBranchList($suppoter_flg)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'suppoter_flg' => $suppoter_flg
        ]);

        //変換パラメータ
        $disp_image = getConstsType('image_disp'); // 変換後画像サイズ（配列）
        $image_type = getConstsType('image_type'); // 変換後画像タイプ（配列）
        
        // 企業支店情報取得
        islog($this->class_name, __FUNCTION__, 'm2');
        DB::enableQueryLog();
        $ret = DB::table('comp_branch_t as cb_t');
        // route('company_shop.show')のURL（修正時注意）
        $ret = $ret->selectRaw('concat(?,"/company_shop/",c_t.comp_no,"/detail") as url', [
            url('/')
        ]); // URL生成
        $ret = $ret->selectRaw('case when i_t.image_name is not null then concat(?,"/", i_t.image_folder, "/", i_t.image_name, ".", i_t.image_type) else ? end as img_file', [ // 画像パス生成(imgのみ)
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
        $ret = $ret->join('comp_t as c_t', function ($join) {
            $join->where('c_t.delete_flg', '0')
                ->where('c_t.comp_type', '<>', '0')
                ->on('cb_t.comp_no', 'c_t.comp_no');
        })
            ->leftjoin('image_t as i_t', function ($join) {
            $join->where('i_t.image_category', getImageCompanyCategory())
                ->on('c_t.comp_no', 'i_t.comp_no')
                ->on('c_t.comp_banner_image_no', 'i_t.image_no');
        })
            ->distinct()
            ->where('cb_t.delete_flg', '0')
            ->where('cb_t.suppoter_flg', $suppoter_flg)
            ->get();
        dslog($this->class_name, __FUNCTION__, 'm22', DB::getQueryLog());

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }
}