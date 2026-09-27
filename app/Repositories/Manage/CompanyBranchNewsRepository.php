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
namespace App\Repositories\Manage;

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
     * 検索条件にマッチした全ての企業支店情報を取得
     *
     * @access private
     * @param string $company_no
     * @param string $company_branch_no
     * @param string $free_word
     * @return \Illuminate\Database\Eloquent\Collection
     * @see CompanyBranchNewsRepository
     * @throws QueryException
     * @todo 【MySQL暫定対応】MySQLバージョンを5.7以上にあげたら、free_word検索のロジックを変更
     */
    private function getCompanyBranchNewsCollection($company_no, $company_branch_no, $free_word)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'company_no' => $company_no,
            'company_branch_no' => $company_branch_no,
            'free_word' => $free_word
        ]);

        // データ取得
        islog($this->class_name, __FUNCTION__, 'm2');
        DB::enableQueryLog();
        $ret = DB::table('comp_branch_news_t')->select('comp_branch_news_no', 'comp_branch_no', 'comp_no', 'comp_branch_news_title', 'comp_branch_news_text', 'created_at')
            ->where('comp_no', '=', $company_no)
            ->where('comp_branch_no', '=', $company_branch_no)
            ->when(! empty($free_word), function ($query) use ($free_word) {
            $query->where('comp_branch_news_title', 'like', "%$free_word%")
                ->orWhere('comp_branch_news_text', 'like', "%$free_word%");
            /*
             * $query->whereRaw('match (comp_branch_news_title, comp_branch_news_text) against ("' . $free_word . '" IN NATURAL LANGUAGE MODE)');
             */
        });

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * [検索]企業支店ニュース情報一覧ページネーション取得
     *
     * 検索条件にマッチした全ての企業支店情報を取得
     *
     * @access public
     * @param string $company_no
     * @param string $company_branch_no
     * @param string $free_word
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     * @see CompanyBranchNewsRepository
     * @throws QueryException
     * @todo
     */
    public function getCompanyBranchNewsPagenationList($company_no, $company_branch_no, $free_word, $limit = null)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'company_no' => $company_no,
            'company_branch_no' => $company_branch_no,
            'free_word' => $free_word,
            'limit' => $limit
        ]);

        // 支店ニュース一覧のコレクションのみ取得
        $ret_collection = $this->getCompanyBranchNewsCollection($company_no, $company_branch_no, $free_word);
        // ページネーション込みで確定
        $limit = ! is_null($limit) ? $limit : getDefaultPagenation(); // 取得件数確定
        $ret = $ret_collection->orderBy('updated_at', 'desc')->paginate($limit);
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
     * @param string $company_branch_no
     * @param string $company_branch_news_no
     * @return object
     * @see CompanyBranchNewsRepository
     * @throws QueryException
     * @todo
     */
    public function getCompanyBranchNewsData($company_no, $company_branch_no, $company_branch_news_no)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'company_no' => $company_no,
            'company_branch_no' => $company_branch_no,
            'company_branch_news_no' => $company_branch_news_no
        ]);

        // データ取得
        islog($this->class_name, __FUNCTION__, 'm2');
        DB::enableQueryLog();
        $ret = $this->comp_branch_news_obj->where('comp_no', $company_no)
            ->where('comp_branch_no', '=', $company_branch_no)
            ->where('comp_branch_news_no', '=', $company_branch_news_no)
            ->first();
        dslog($this->class_name, __FUNCTION__, 'm22', DB::getQueryLog());

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * [登録]企業支店ニュース情報登録
     *
     * 有効な企業支店ニュース情報を追加
     *
     * @access public
     * @param array $insert_data
     * @return Boolean
     * @see CompanyBranchNewsRepository
     * @throws QueryException
     * @todo
     */
    public function insertCompanyBranchNewsData($insert_data)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'insert_data' => $insert_data
        ]);

        // 支店ニュース登録
        islog($this->class_name, __FUNCTION__, 'm4');
        DB::beginTransaction();
        islog($this->class_name, __FUNCTION__, 'm5');
        DB::enableQueryLog();
        $ret = $this->comp_branch_news_obj->insert($insert_data);
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
     * [更新]企業支店ニュース情報更新
     *
     * 指定の企業支店ニュース情報を更新
     *
     * @access public
     * @param array $update_data
     * @param string $company_no
     * @param string $company_branch_no
     * @param int $company_branch_news_no
     * @return Boolean
     * @see CompanyBranchNewsRepository
     * @throws QueryException
     * @todo
     */
    public function updateCompanyBranchNewsData($update_data, $company_no, $company_branch_no, $company_branch_news_no)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'update_data' => $update_data,
            'company_no' => $company_no,
            'company_branch_no' => $company_branch_no,
            'company_branch_news_no' => $company_branch_news_no
        ]);

        // 支店ニュース更新
        islog($this->class_name, __FUNCTION__, 'm4');
        DB::beginTransaction();
        islog($this->class_name, __FUNCTION__, 'm6');
        DB::enableQueryLog();
        $ret = $this->comp_branch_news_obj->where('comp_no', $company_no)
            ->where('comp_branch_no', $company_branch_no)
            ->where('comp_branch_news_no', $company_branch_news_no)
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
     * [削除]企業支店ニュース情報削除
     *
     * 有効な企業支店ニュース情報を削除(削除フラグを更新)
     *
     * @access public
     * @param array $delete_data
     * @param string $company_no
     * @param string $company_branch_no
     * @return Boolean
     * @see CompanyBranchNewsRepository
     * @throws QueryException
     * @todo
     */
    public function deleteCompanyBranchNewsData($delete_data, $company_no, $company_branch_no)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'delete_data' => $delete_data,
            'company_no' => $company_no,
            'company_branch_no' => $company_branch_no
        ]);

        // 支店ニュース削除
        islog($this->class_name, __FUNCTION__, 'm4');
        DB::beginTransaction();
        islog($this->class_name, __FUNCTION__, 'm7');
        DB::enableQueryLog();
        $ret = $this->comp_branch_news_obj->where('comp_no', $company_no)
            ->where('comp_branch_no', $company_branch_no)
            ->whereIn('comp_branch_news_no', $delete_data)
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