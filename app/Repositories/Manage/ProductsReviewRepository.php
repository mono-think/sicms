<?php
/**
 * [DB]products_review_tテーブルのRepositoryクラス
 *
 * products_review_tテーブルをメインとする製品レビュー情報の検索、登録、更新、削除処理
 *
 * @access public
 * @author Seiji Ishikawa（メールアドレス）
 * @copyright Webじゃ軒 All Rights Reserved
 * @package repository
 */
namespace App\Repositories\Manage;

use App\Models\ProductsReview;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class ProductsReviewRepository implements ProductsReviewRepositoryInterface
{

    protected $products_review_obj;

    protected $class_name;

    /**
     * コンストラクタ
     *
     * @access public
     * @param ProductsReview $products_review
     */
    public function __construct(ProductsReview $products_review)
    {
        $this->products_review_obj = $products_review;
        $this->class_name = get_class($this);
        islog($this->class_name, __FUNCTION__);
    }

    /**
     * [検索]製品レビュー情報コレクション取得
     *
     * 検索条件にマッチした全ての製品レビュー情報を取得
     *
     * @access private
     * @param string $company_no
     * @param string $products_no
     * @param string $products_detail_no
     * @param string $free_word
     * @return \Illuminate\Database\Eloquent\Collection
     * @see ProductsReviewRepository
     * @throws QueryException
     * @todo 【MySQL暫定対応】MySQLバージョンを5.7以上にあげたら、free_word検索のロジックを変更
     */
    private function getProductsReviewCollection($company_no, $products_no, $products_detail_no, $free_word)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'company_no' => $company_no,
            'products_no' => $products_no,
            'products_detail_no' => $products_detail_no,
            'free_word' => $free_word
        ]);

        // データ取得
        islog($this->class_name, __FUNCTION__, 'm2');
        DB::enableQueryLog();
        $ret = DB::table('products_review_t')->select('products_review_no', 'products_detail_no', 'products_no', 'comp_no', 'products_review_title', 'products_review_text')
            ->where('comp_no', '=', $company_no)
            ->where('products_no', '=', $products_no)
            ->where('products_detail_no', '=', $products_detail_no)
            ->when(! empty($free_word), function ($query) use ($free_word) {
            $query->where('products_review_title', 'like', "%$free_word%")
                ->orWhere('products_review_text', 'like', "%$free_word%");
            /*
             * $query->whereRaw('match (products_review_title,products_review_text) against ("' . $free_word . '" IN NATURAL LANGUAGE MODE)');
             */
        });

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * [検索]製品レビュー一覧ページネーション取得
     *
     * 検索条件にマッチした全ての製品レビュー情報を取得
     *
     * @access public
     * @param string $company_no
     * @param string $products_no
     * @param string $products_detail_no
     * @param string $free_word
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     * @see ProductsReviewRepository
     * @throws QueryException
     * @todo
     */
    public function getProductsReviewPagenationList($company_no, $products_no, $products_detail_no, $free_word, $limit = null)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'company_no' => $company_no,
            'products_no' => $products_no,
            'products_detail_no' => $products_detail_no,
            'free_word' => $free_word,
            'limit' => $limit
        ]);

        // 製品レビュー情報一覧のコレクションのみ取得
        $ret_collection = $this->getProductsReviewCollection($company_no, $products_no, $products_detail_no, $free_word);
        // ページネーション込みで確定
        $limit = ! is_null($limit) ? $limit : getDefaultPagenation(); // 取得件数確定
        $ret = $ret_collection->orderBy('updated_at', 'desc')->paginate($limit);
        dslog($this->class_name, __FUNCTION__, 'm22', DB::getQueryLog());

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * [検索]製品レビュー情報取得
     *
     * 製品レビュー番号により製品レビュー情報を取得
     *
     * @access public
     * @param string $company_no
     * @param string $products_no
     * @param string $products_detail_no
     * @param string $products_review_no
     * @return object Illuminate\Database\Eloquent\Collectionオブジェクト
     * @see ProductsReviewRepository
     * @throws QueryException
     * @todo
     */
    public function getProductsReviewData($company_no, $products_no, $products_detail_no, $products_review_no)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'company_no' => $company_no,
            'products_no' => $products_no,
            'products_detail_no' => $products_detail_no,
            'products_review_no' => $products_review_no
        ]);

        // データ取得
        islog($this->class_name, __FUNCTION__, 'm2');
        DB::enableQueryLog();
        $ret = $this->products_review_obj->where('comp_no', $company_no)
            ->where('products_no', $products_no)
            ->where('products_detail_no', $products_detail_no)
            ->whereRaw('upper(products_review_no) = ?', strtoupper($products_review_no))
            ->first();
        dslog($this->class_name, __FUNCTION__, 'm22', DB::getQueryLog());

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * [登録]製品レビュー情報登録
     *
     * 有効な製品レビュー情報を追加
     *
     * @access public
     * @param array $insert_data
     * @return Boolean $ret
     * @see ProductsReviewRepository
     * @throws QueryException
     * @todo
     */
    public function insertProductsReviewData($insert_data)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'insert_data' => $insert_data
        ]);

        // 製品レビュー情報登録
        islog($this->class_name, __FUNCTION__, 'm4');
        DB::beginTransaction();
        islog($this->class_name, __FUNCTION__, 'm5');
        DB::enableQueryLog();
        $ret = $this->products_review_obj->insert($insert_data);
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
     * [更新]製品レビュー情報更新
     *
     * 指定の製品レビュー情報を更新
     *
     * @access public
     * @param array $update_data
     * @param string $company_no
     * @param string $products_no
     * @param string $products_detail_no
     * @param string $products_review_no
     * @return Boolean $ret
     * @see ProductsReviewRepository
     * @throws QueryException
     * @todo
     */
    public function updateProductsReviewData($update_data, $company_no, $products_no, $products_detail_no, $products_review_no)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'update_data' => $update_data,
            'company_no' => $company_no,
            'products_no' => $products_no,
            'products_detail_no' => $products_detail_no,
            'products_review_no' => $products_review_no
        ]);

        // 製品レビュー情報更新
        islog($this->class_name, __FUNCTION__, 'm4');
        DB::beginTransaction();
        islog($this->class_name, __FUNCTION__, 'm6');
        DB::enableQueryLog();
        $ret = $this->products_review_obj->where('comp_no', $company_no)
            ->where('products_no', $products_no)
            ->where('products_detail_no', $products_detail_no)
            ->where('products_review_no', $products_review_no)
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
     * [削除]製品レビュー情報削除
     *
     * 有効な製品レビュー情報を削除
     *
     * @access public
     * @param array $delete_data
     * @param string $products_no
     * @param string $products_detail_no
     * @param string $company_no
     * @return Boolean $ret
     * @see ProductsReviewRepository
     * @throws QueryException
     * @todo
     */
    public function deleteProductsReviewData($delete_data, $products_no, $products_detail_no, $company_no)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'delete_data' => $delete_data,
            'products_no' => $products_no,
            'products_detail_no' => $products_detail_no,
            'company_no' => $company_no
        ]);

        // 製品レビュー情報削除
        islog($this->class_name, __FUNCTION__, 'm4');
        DB::beginTransaction();
        islog($this->class_name, __FUNCTION__, 'm7');
        DB::enableQueryLog();
        $ret = $this->products_review_obj->where('comp_no', $company_no)
            ->where('products_no', $products_no)
            ->where('products_detail_no', $products_detail_no)
            ->whereIn('products_review_no', $delete_data)
            ->delete();
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