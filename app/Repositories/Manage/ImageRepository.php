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
namespace App\Repositories\Manage;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use App\Models\Image;

class ImageRepository implements ImageRepositoryInterface
{

    protected $image_obj;

    protected $class_name;

    /**
     * コンストラクタ
     *
     * @access public
     * @param Image $image_obj
     */
    public function __construct(Image $image_obj)
    {
        $this->image_obj = $image_obj;
        $this->class_name = get_class($this);
        islog($this->class_name, __FUNCTION__);
    }

    /**
     * [検索]画像情報コレクション取得
     *
     * 検索条件にマッチした全ての画像情報を取得
     *
     * @access private
     * @param string $company_no
     * @param int $image_category
     * @param string $free_word
     * @return \Illuminate\Database\Eloquent\Collection
     * @see ImageRepository
     * @throws QueryException
     * @todo 【MySQL暫定対応】MySQLバージョンを5.7以上にあげたら、free_word検索のロジックを変更
     */
    private function getImageCollection($company_no, $image_category, $free_word)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'company_no' => $company_no,
            'image_category' => $image_category,
            'free_word' => $free_word
        ]);

        // データ取得
        islog($this->class_name, __FUNCTION__, 'm2');
        DB::enableQueryLog();
        $ret = DB::table('image_t')->select('image_no', 'comp_no', 'image_category', 'image_name', 'image_folder', 'image_type', 'image_alt', 'image_text');
        // route('image.update')のURL（修正時注意）
        $ret = $ret->selectRaw('concat(?,"/manage/image/",image_no) as url', [
            url('/')
        ]); // URL生成
        $ret = $ret->selectRaw('concat(?,"/", image_folder, "/", image_name, ".", image_type) as img_file', [
            getImagePublicFullpath()
        ]); // 画像パス生成
        $ret = $ret->when(! empty($free_word), function ($query) use ($free_word) {
            $query->where('image_name', 'like', "%$free_word%")
                ->orWhere('image_alt', 'like', "%$free_word%")
                ->orWhere('image_text', 'like', "%$free_word%");
            /*
             * $query->whereRaw('match (image_name, image_alt, image_text) against ("' . $free_word . '" IN NATURAL LANGUAGE MODE)');
             */
        })
            ->when(! is_null($company_no), function ($query) use ($company_no) {
            return $query->where('comp_no', $company_no);
        })
            ->when(! is_null($image_category), function ($query) use ($image_category) {
            return $query->where('image_category', $image_category);
        });

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * [検索]画像情報ページネーション一覧取得
     *
     * 検索条件にマッチした全ての画像情報を取得
     *
     * @access public
     * @param string $company_no
     * @param string $free_word
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     * @see ImageRepository
     * @throws QueryException
     * @todo
     */
    public function getImagePagenationList($company_no, $free_word, $limit = null)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'company_no' => $company_no,
            'free_word' => $free_word,
            'limit' => $limit
        ]);

        // アカウント一覧のコレクションのみ取得
        $ret_collection = $this->getImageCollection($company_no, null, $free_word);
        // ページネーション込みで確定
        $limit = ! is_null($limit) ? $limit : getDefaultPagenation(); // 取得件数確定
        $ret = $ret_collection->orderBy('updated_at', 'desc')->paginate($limit);
        dslog($this->class_name, __FUNCTION__, 'm22', DB::getQueryLog());

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * [検索]画像情報一覧取得
     *
     * 検索条件にマッチした全ての画像情報を取得
     *
     * @access public
     * @param string $company_no
     * @param int $image_category
     * @param string $free_word
     * @param int $start
     * @parama int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     * @see ImageRepository
     * @throws QueryException
     * @todo
     */
    public function getImageList($company_no, $image_category, $free_word, $start = 0, $limit = null)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'company_no' => $company_no,
            'image_category' => $image_category,
            'free_word' => $free_word,
            'start' => $start,
            'limit' => $limit
        ]);

        // アカウント一覧のコレクションのみ取得
        $ret_collection = $this->getImageCollection($company_no, $image_category, $free_word);
        if (is_null($limit)) {
            // 全件取得で確定
            $ret = $ret_collection->orderBy('updated_at', 'desc')->get();
        } else {
            // 指定開始位置から指定件数取得で確定
            $ret = $ret_collection->offset($start)
                ->limit($limit)
                ->orderBy('updated_at', 'desc')
                ->get();
        }
        dslog($this->class_name, __FUNCTION__, 'm22', DB::getQueryLog());

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * [検索]画像情報取得
     *
     * 画像番号により画像情報を取得
     *
     * @access public
     * @param string $company_no
     * @param string $image_name
     * @return object
     * @see ImageRepository
     * @throws QueryException
     * @todo
     */
    public function getImageData($company_no, $image_name)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'company_no' => $company_no,
            'image_name' => $image_name
        ]);

        // データ取得
        islog($this->class_name, __FUNCTION__, 'm2');
        $ret = $this->image_obj->where('image_name', $image_name)
            ->where('comp_no', $company_no)
            ->first();

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * [登録]画像情報登録
     *
     * 有効な画像情報を追加
     *
     * @access public
     * @param array $insert_data
     * @return Boolean
     * @see ImageRepository
     * @throws QueryException
     * @todo
     */
    public function insertImageData($insert_data)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'insert_data' => $insert_data
        ]);

        // 画像情報登録
        islog($this->class_name, __FUNCTION__, 'm4');
        DB::beginTransaction();
        islog($this->class_name, __FUNCTION__, 'm5');
        $ret = $this->image_obj->insert($insert_data);

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
     * [更新]画像情報更新
     *
     * 指定の画像情報を更新
     *
     * @access public
     * @param array $update_data
     * @param string $image_no
     * @return Boolean
     * @see ImageRepository
     * @throws QueryException
     * @todo
     */
    public function updateImageData($update_data, $image_no)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'update_data' => $update_data,
            'image_no' => $image_no
        ]);

        // イベント更新
        islog($this->class_name, __FUNCTION__, 'm4');
        DB::beginTransaction();
        islog($this->class_name, __FUNCTION__, 'm6');
        $ret = $this->image_obj->where('image_no', $image_no)->update($update_data);

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
     * [削除]画像情報削除
     *
     * 有効な画像情報を削除
     *
     * @access public
     * @param array $delete_data
     * @param string $company_no
     * @return Boolean
     * @see ImageRepository
     * @throws QueryException
     * @todo
     */
    public function deleteImageData($delete_data, $company_no)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'delete_data' => $delete_data,
            'company_no' => $company_no
        ]);

        // 画像情報削除
        islog($this->class_name, __FUNCTION__, 'm4');
        DB::beginTransaction();
        islog($this->class_name, __FUNCTION__, 'm7');
        $ret = $this->image_obj->whereIn('image_no', $delete_data)
            ->when(! is_null($company_no), function ($query) use ($company_no) {
            return $query->where('comp_no', $company_no);
        })
            ->delete();

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