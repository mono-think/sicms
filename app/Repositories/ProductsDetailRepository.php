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

use App\Models\ProductsDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Carbon\Carbon;
use DateTime;

class ProductsDetailRepository implements ProductsDetailRepositoryInterface
{

    protected $products_detail_obj;

    protected $class_name;

    /**
     * コンストラクタ
     *
     * @access public
     * @param ProductsDetail $products_detail
     */
    public function __construct(ProductsDetail $products_detail)
    {
        $this->products_detail_obj = $products_detail;
        $this->class_name = get_class($this);
        islog($this->class_name, __FUNCTION__);
    }

    /**
     * [検索]製品詳細情報の一覧取得
     *
     * 検索条件にマッチした全ての新着情報を取得
     *
     * @access public
     * @param string $company_no
     * @param string $products_no
     * @return \Illuminate\Database\Eloquent\Collection
     * @see ProductsDetailRepository
     * @throws QueryException
     * @todo
     */
    public function getProductsDetailList($company_no, $products_no)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'company_no' => $company_no,
            'products_no' => $products_no
        ]);

        // 製品情報取得
        islog($this->class_name, __FUNCTION__, 'm2');
        $cb = new Carbon(); // 現在日時
        $now_datetime = $cb->format('Y-m-d H:i:s');
        DB::enableQueryLog();

        // 製品詳細サブクエリ
        $ret = DB::table('products_detail_t as pd_t')->select('pd_t.products_detail_no', 'pd_t.products_no', 'pd_t.comp_no', 'pd_t.products_detail_title', 'pd_t.updated_at', 'i_t.image_category', 'i_t.image_no', 'i_t.image_name', 'i_t.image_folder', 'i_t.image_type', 'i_t.image_alt', 'i_t.image_text');
        $ret = $ret->selectRaw('case when i_t.image_name is not null then concat(?,"/", i_t.image_folder, "/", i_t.image_name, ".", i_t.image_type) else ? end as img_file, concat(left(pd_t.products_detail_description,29),"･･･") as products_detail_description', [ // 画像パス生成(imgのみ)
            getImagePublicFullpath(),
            getNoimagePath()
        ]);
        $disp_image = getConstsType('image_disp'); // 変換後画像サイズ（配列）
        $image_type = getConstsType('image_type'); // 変換後画像タイプ（配列）

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
        $ret = $ret->leftjoin('image_t as i_t', function ($join) {
            $join->where('i_t.image_category', getImageProductsCategory())
                ->on('pd_t.comp_no', 'i_t.comp_no')
                ->on('pd_t.products_detail_image_no', 'i_t.image_no');
        })
            ->when(! is_null($company_no), function ($query) use ($company_no) {
            return $query->where('pd_t.comp_no', $company_no);
        })
            ->where('pd_t.products_no', $products_no)
            ->whereDate('pd_t.release_start', '<=', $now_datetime)
            ->whereDate('pd_t.release_end', '>=', $now_datetime)
            ->orderBy('pd_t.updated_at', 'desc')
            ->get();
        dslog($this->class_name, __FUNCTION__, 'm22', DB::getQueryLog());

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }
}