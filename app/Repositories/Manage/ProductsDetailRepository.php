<?php
/**
 * [DB]products_detail_tテーブルのRepositoryクラス
 *
 * products_detail_tテーブルをメインとする製品詳細情報の検索、登録、更新、削除処理
 *
 * @access public
 * @author Seiji Ishikawa（メールアドレス）
 * @copyright Webじゃ軒 All Rights Reserved
 * @package repository
 */
namespace App\Repositories\Manage;

use App\Models\ProductsDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

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
     * [検索]製品詳細情報コレクション取得
     *
     * 検索条件にマッチした全ての製品詳細情報を取得
     *
     * @access private
     * @param string $company_no
     * @param string $products_no
     * @param string $free_word
     * @return \Illuminate\Database\Eloquent\Collection
     * @see ProductsDetailRepository
     * @throws QueryException
     * @todo 【MySQL暫定対応】MySQLバージョンを5.7以上にあげたら、free_word検索のロジックを変更
     */
    private function getProductsDetailCollection($company_no, $products_no, $free_word)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'company_no' => $company_no,
            'products_no' => $products_no,
            'free_word' => $free_word
        ]);

        // データ取得
        islog($this->class_name, __FUNCTION__, 'm2');
        DB::enableQueryLog();
        $ret = DB::table('products_detail_t')->select('products_detail_no', 'products_no', 'comp_no', 'products_detail_title', 'products_detail_description', 'products_detail_text', 'search_tag', 'release_start', 'release_end')
            ->where('comp_no', $company_no)
            ->where('products_no', $products_no)
            ->when(! empty($free_word), function ($query) use ($free_word) {
            $query->where('products_detail_title', 'like', "%$free_word%")
                ->orWhere('products_detail_description', 'like', "%$free_word%")
                ->orWhere('products_detail_text', 'like', "%$free_word%");
            /*
             * $query->whereRaw('match (products_detail_title,products_detail_description,products_detail_text) against ("' . $free_word . '" IN NATURAL LANGUAGE MODE)');
             */
        });

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * [検索]製品詳細の一覧情報ページネーション取得
     *
     * 検索条件にマッチした全ての製品詳細情報を取得
     *
     * @access public
     * @param string $company_no
     * @param string $products_no
     * @param string $free_word
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     * @see ProductsDetailRepository
     * @throws QueryException
     * @todo
     */
    public function getProductsDetailPagenationList($company_no, $products_no, $free_word, $limit = null)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'company_no' => $company_no,
            'products_no' => $products_no,
            'free_word' => $free_word,
            'limit' => $limit
        ]);

        // 製品詳細情報一覧のコレクションのみ取得
        $ret_collection = $this->getProductsDetailCollection($company_no, $products_no, $free_word);
        // ページネーション込みで確定
        $limit = ! is_null($limit) ? $limit : getDefaultPagenation(); // 取得件数確定
        $ret = $ret_collection->orderBy('updated_at', 'desc')->paginate($limit);
        dslog($this->class_name, __FUNCTION__, 'm22', DB::getQueryLog());

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * [検索]製品詳細情報取得
     *
     * 製品詳細番号により製品詳細情報を取得
     *
     * @access public
     * @param string $company_no
     * @param string $products_no
     * @param int $products_detail_no
     * @return object
     * @see ProductsDetailRepository
     * @throws QueryException
     * @todo
     */
    public function getProductsDetailData($company_no, $products_no, $products_detail_no)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'company_no' => $company_no,
            'products_no' => $products_no,
            'products_detail_no' => $products_detail_no
        ]);

        // データ取得
        islog($this->class_name, __FUNCTION__, 'm2');
        DB::enableQueryLog();
        $ret = DB::table('products_detail_t as pd_t')->select('pd_t.products_detail_no', 'pd_t.products_no', 'pd_t.comp_no', 'pd_t.products_detail_image_no', 'pd_t.products_detail_title', 'pd_t.products_detail_description', 'pd_t.products_detail_text', 'pd_t.search_tag', 'pd_t.release_start', 'pd_t.release_end', 'i_t.image_category', 'i_t.image_no', 'i_t.image_name', 'i_t.image_folder', 'i_t.image_type', 'i_t.image_alt', 'i_t.image_text')
            ->leftjoin('image_t as i_t', function ($join) {
            $join->on('pd_t.products_detail_image_no', '=', 'i_t.image_no')
                ->on('pd_t.comp_no', '=', 'i_t.comp_no');
        })
            ->where('pd_t.comp_no', $company_no)
            ->where('pd_t.products_no', $products_no)
            ->whereRaw('upper(pd_t.products_detail_no) = ?', strtoupper($products_detail_no))
            ->first();
        dslog($this->class_name, __FUNCTION__, 'm22', DB::getQueryLog());

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * [登録]製品詳細情報登録
     *
     * 有効な製品詳細情報を追加
     *
     * @access public
     * @param array $insert_data
     * @return Boolean
     * @see ProductsDetailRepository
     * @throws QueryException
     * @todo
     */
    public function insertProductsDetailData($insert_data)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'insert_data' => $insert_data
        ]);

        // 製品詳細情報登録
        islog($this->class_name, __FUNCTION__, 'm4');
        DB::beginTransaction();
        islog($this->class_name, __FUNCTION__, 'm5');
        DB::enableQueryLog();
        $ret = $this->products_detail_obj->insert($insert_data);
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
     * [更新]製品詳細情報更新
     *
     * 指定の製品詳細情報を更新
     *
     * @access public
     * @param array $update_data
     * @param string $company_no
     * @param string $products_no
     * @param int $products_detail_no
     * @return Boolean
     * @see ProductsDetailRepository
     * @throws QueryException
     * @todo
     */
    public function updateProductsDetailData($update_data, $company_no, $products_no, $products_detail_no)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'update_data' => $update_data,
            'company_no' => $company_no,
            'products_no' => $products_no,
            'products_detail_no' => $products_detail_no
        ]);

        // 製品詳細情報更新
        islog($this->class_name, __FUNCTION__, 'm4');
        DB::beginTransaction();
        islog($this->class_name, __FUNCTION__, 'm6');
        DB::enableQueryLog();
        $ret = $this->products_detail_obj->where('comp_no', $company_no)
            ->where('products_no', $products_no)
            ->where('products_detail_no', $products_detail_no)
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
     * [削除]製品詳細情報削除
     *
     * 有効な製品詳細情報を削除
     *
     * @access public
     * @param array $delete_data
     * @param string $products_no
     * @param string $company_no
     * @return Boolean
     * @see ProductsDetailRepository
     * @throws QueryException
     * @todo
     */
    public function deleteProductsDetailData($delete_data, $products_no, $company_no)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'delete_data' => $delete_data,
            'products_no' => $products_no,
            'company_no' => $company_no
        ]);

        // 製品詳細情報削除
        islog($this->class_name, __FUNCTION__, 'm4');
        DB::beginTransaction();
        islog($this->class_name, __FUNCTION__, 'm7');
        DB::enableQueryLog();
        $ret = $this->products_detail_obj->where('comp_no', $company_no)
            ->where('products_no', $products_no)
            ->whereIn('products_detail_no', $delete_data)
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