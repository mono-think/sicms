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
namespace App\Repositories\Manage;

use App\Models\Company;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class CompanyRepository implements CompanyRepositoryInterface
{

    protected $comp_obj;

    protected $class_name;

    /**
     * コンストラクタ
     *
     * @access public
     * @param Company $comp_obj
     */
    public function __construct(Company $comp_obj)
    {
        $this->comp_obj = $comp_obj;
        $this->class_name = get_class($this);
        islog($this->class_name, __FUNCTION__);
    }

    /**
     * [検索]企業情報コレクション取得
     *
     * 検索条件にマッチした全ての企業情報を取得
     *
     * @access private
     * @param string $free_word
     * @param int $company_type
     * @return \Illuminate\Database\Eloquent\Collection
     * @see CompanyRepository
     * @throws QueryException
     * @todo 【MySQL暫定対応】MySQLバージョンを5.7以上にあげたら、free_word検索のロジックを変更
     */
    private function getCompanyCollection($free_word, $company_type)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'free_word' => $free_word,
            'company_type' => $company_type
        ]);

        // データ取得
        islog($this->class_name, __FUNCTION__, 'm2');
        DB::enableQueryLog();
        $ret = DB::table('comp_t as c_t')->select('c_t.comp_no', 'c_t.comp_name', 'i_m.industry_name', 'bf_m.bformat_name', 'c_t.comp_url', 'c_t.comp_type')
            ->leftjoin('industry_m as i_m', function ($join) {
            $join->on('c_t.industry_no', 'i_m.industry_no');
        })
            ->leftjoin('bformat_m as bf_m', function ($join) {
            $join->on('c_t.bformat_no', 'bf_m.bformat_no');
        })
            ->where('c_t.delete_flg', '0')
            ->when(is_null($company_type), function ($query) {
            $query->where('c_t.comp_type', '<>', '0');
        })
            ->when(! is_null($company_type), function ($query) use ($company_type) {
            $query->where('c_t.comp_type', $company_type);
        })
            ->when(! empty($free_word), function ($query) use ($free_word) {
            $query->where('c_t.comp_name', 'like', "%$free_word%")
                ->orWhere('i_m.industry_name', 'like', "%$free_word%")
                ->orWhere('bf_m.bformat_name', 'like', "%$free_word%");
            /*
             * $query->whereRaw('match (c_t.comp_name) against ("' . $free_word . '" IN NATURAL LANGUAGE MODE)')
             * ->orWhereRaw('match (i_m.industry_name) against ("' . $free_word . '" IN NATURAL LANGUAGE MODE)')
             * ->orWhereRaw('match (bf_m.bformat_name) against ("' . $free_word . '" IN NATURAL LANGUAGE MODE)');
             */
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
     * @param string $free_word
     * @param int $company_type
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     * @see CompanyRepository
     * @throws QueryException
     * @todo
     */
    public function getCompanyPagenationList($free_word, $company_type, $limit = null)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'free_word' => $free_word,
            'company_type' => $company_type,
            'limit' => $limit
        ]);

        // 企業情報一覧のコレクションのみ取得
        $ret_collection = $this->getCompanyCollection($free_word, $company_type);
        // ページネーション込みで確定
        $limit = ! is_null($limit) ? $limit : getDefaultPagenation(); // 取得件数確定
        $ret = $ret_collection->orderBy('c_t.updated_at', 'desc')->paginate($limit);
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
     * @param int $delete_flg
     * @return object
     * @see CompanyRepository
     * @throws QueryException
     * @todo
     */
    public function getCompanyData($company_no, $delete_flg)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'company_no' => $company_no,
            'delete_flg' => $delete_flg
        ]);

        // データ取得
        islog($this->class_name, __FUNCTION__, 'm2');
        DB::enableQueryLog();
        $ret = DB::table('comp_t as c_t')->select('c_t.comp_no', 'c_t.comp_name', 'c_t.industry_no', 'i_m.industry_name', 'c_t.bformat_no', 'bf_m.bformat_name', 'c_t.comp_business_text', 'c_t.comp_banner_image_no', 'c_t.search_tag', 'c_t.comp_url', 'c_t.comp_type', 'c_t.page_title', 'i_t.image_no', 'i_t.image_category', 'i_t.image_name', 'i_t.image_folder', 'i_t.image_type', 'i_t.image_alt', 'i_t.image_text')
            ->leftjoin('industry_m as i_m', function ($join) {
            $join->on('c_t.industry_no', 'i_m.industry_no');
        })
            ->leftjoin('bformat_m as bf_m', function ($join) {
            $join->on('c_t.bformat_no', 'bf_m.bformat_no');
        })
            ->leftjoin('image_t as i_t', function ($join) {
            $join->on('c_t.comp_banner_image_no', 'i_t.image_no')
                ->on('c_t.comp_no', 'i_t.comp_no');
        })
            ->when(! is_null($delete_flg), function ($query) use ($delete_flg) {
            $query->where('c_t.delete_flg', $delete_flg);
        })
            ->whereRaw('upper(c_t.comp_no) = ?', strtoupper($company_no))
            ->first();
        dslog($this->class_name, __FUNCTION__, 'm22', DB::getQueryLog());

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * [登録]企業情報登録
     *
     * 有効な企業情報を追加
     *
     * @access public
     * @param array $insert_data
     * @return Boolean
     * @see CompanyRepository
     * @throws QueryException
     * @todo
     */
    public function insertCompanyData($insert_data)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'insert_data' => $insert_data
        ]);

        // 企業登録
        islog($this->class_name, __FUNCTION__, 'm4');
        DB::beginTransaction();
        islog($this->class_name, __FUNCTION__, 'm5');
        DB::enableQueryLog();
        $ret = $this->comp_obj->insert($insert_data);
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
     * [更新]企業情報更新
     *
     * 指定の企業情報を更新
     *
     * @access public
     * @param array $update_data
     * @param string $company_no
     * @return Boolean
     * @see CompanyRepository
     * @throws QueryException
     * @todo
     */
    public function updateCompanyData($update_data, $company_no)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'update_data' => $update_data,
            'company_no' => $company_no
        ]);

        // 企業更新
        islog($this->class_name, __FUNCTION__, 'm4');
        DB::beginTransaction();
        islog($this->class_name, __FUNCTION__, 'm6');
        DB::enableQueryLog();
        $ret = $this->comp_obj->where('comp_no', $company_no)->update($update_data);
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
     * [削除]企業情報削除
     *
     * 有効な企業情報を削除(削除フラグを更新)
     *
     * @access public
     * @param array $delete_data
     * @param string $acnt_no
     * @return Boolean
     * @see CompanyRepository
     * @throws QueryException
     * @todo
     */
    public function deleteCompanyData($delete_data, $acnt_no)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'delete_data' => $delete_data,
            'acnt_no' => $acnt_no
        ]);

        // 企業削除
        islog($this->class_name, __FUNCTION__, 'm4');
        DB::beginTransaction();
        islog($this->class_name, __FUNCTION__, 'm7');
        DB::enableQueryLog();
        $ret = $this->comp_obj->whereIn('comp_no', $delete_data)->update([
            'last_acnt_no' => $acnt_no,
            'delete_flg' => 1
        ]); // 最終更新者, 削除フラグON
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