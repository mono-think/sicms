<?php
/**
 * 製品カテゴリ情報処理のServiceクラス
 * 検索、登録、更新、削除処理
 *
 * @access public
 * @author Seiji Ishikawa（メールアドレス）
 * @copyright Webじゃ軒 All Rights Reserved
 * @package service
 */
namespace App\Services\Manage;

use App\Repositories\Manage\ProductsCategoryRepositoryInterface;
use Carbon\Carbon;

class ProductsCategoryService
{

    protected $products_category_repository;

    protected $class_name;

    /**
     * コンストラクタ
     *
     * @access public
     * @param ProductsCategoryRepositoryInterface $products_category_repository
     *
     */
    public function __construct(ProductsCategoryRepositoryInterface $products_category_repository)
    {
        $this->products_category_repository = $products_category_repository;
        $this->class_name = get_class($this);
        islog($this->class_name, __FUNCTION__);
    }

    /**
     * 製品カテゴリ情報ページネーション一覧取得
     * 検索条件により全ての製品カテゴリ情報を取得
     *
     * @access public
     * @param string $free_word
     * @return \Illuminate\Database\Eloquent\Collection
     * @see ProductsCategoryService
     * @throws
     * @todo
     */
    public function getProductsCategoryPagenationList($free_word = null)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'free_word' => $free_word
        ]);

        // データ取得
        islog($this->class_name, __FUNCTION__, 'm10');
        $free_word = hscQuotes($free_word); // HTMLエンティティ変換
        $ret = $this->products_category_repository->getProductsCategoryPagenationList($free_word);

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * 製品カテゴリ情報一覧取得
     * 検索条件により全ての製品カテゴリ情報を取得
     *
     * @access public
     * @param string $free_word
     * @param int $start
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     * @see ProductsCategoryService
     * @throws
     * @todo
     */
    public function getProductsCategoryList($free_word = null, $start = 0, $limit = null)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'free_word' => $free_word,
            'start' => $start,
            'limit' => $limit
        ]);

        // データ取得
        islog($this->class_name, __FUNCTION__, 'm10');
        $free_word = hscQuotes($free_word); // HTMLエンティティ変換
        $ret = $this->products_category_repository->getProductsCategoryList($free_word, $start, $limit);

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * 製品カテゴリ情報取得
     * 製品カテゴリ管理番号により製品カテゴリ情報を取得
     *
     * @access public
     * @param int $products_category_no
     * @return array
     * @see ProductsCategoryService
     * @throws
     * @todo
     */
    public function getProductsCategoryData($products_category_no)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'products_category_no' => $products_category_no
        ]);

        // データ取得
        islog($this->class_name, __FUNCTION__, 'm10');
        $ret = $this->products_category_repository->getProductsCategoryData($products_category_no);
        $form_data = [];
        if (! empty($ret)) {
            // 配列化
            $form_data = [
                'input_no' => $ret->products_category_no, // 製品カテゴリ番号
                'input_name' => $ret->products_category_name // 製品カテゴリ名
            ];
        }

        dslog($this->class_name, __FUNCTION__, 'm2', $form_data);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $form_data;
    }

    /**
     * 製品カテゴリ情報登録
     * 入力した製品カテゴリ情報を登録
     *
     * @access public
     * @param array $form_data
     * @param string $acnt_no
     * @return Boolean
     * @see ProductsCategoryService
     * @throws
     * @todo
     */
    public function insertProductsCategoryData($form_data, $acnt_no)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'form_data' => $form_data,
            'acnt_no' => $acnt_no
        ]);

        // 新規登録
        // 登録データを設定
        islog($this->class_name, __FUNCTION__, 'm14');
        $insert_data['products_category_no'] = $form_data['input_no']; // 製品カテゴリ番号
        $insert_data['products_category_name'] = $form_data['input_name']; // 製品カテゴリ名
        $insert_data['created_at'] = Carbon::now(); // 登録日
        $insert_data['updated_at'] = Carbon::now(); // 更新日
        $insert_data['last_acnt_no'] = $acnt_no; // 最終更新者NO
        dslog($this->class_name, __FUNCTION__, 'm13', $insert_data);

        islog($this->class_name, __FUNCTION__, 'm18');
        $ret = $this->products_category_repository->insertProductsCategoryData($insert_data);

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * 製品カテゴリ情報更新
     * 入力した製品カテゴリ情報を更新
     *
     * @access public
     * @param array $form_data
     * @param string $products_category_no
     * @param string $acnt_no
     * @return Boolean
     * @see ProductsCategoryService
     * @throws
     * @todo
     */
    public function updateProductsCategoryData($form_data, $products_category_no, $acnt_no)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'form_data' => $form_data,
            'products_category_no' => $products_category_no,
            'acnt_no' => $acnt_no
        ]);

        // 既存更新
        // 更新データを設定

        islog($this->class_name, __FUNCTION__, 'm15');
        $update_data['products_category_name'] = $form_data['input_name']; // 製品カテゴリ名
        $update_data['updated_at'] = Carbon::now(); // 更新日
        $update_data['last_acnt_no'] = $acnt_no; // 最終更新者NO
        dslog($this->class_name, __FUNCTION__, 'm14', $update_data);

        islog($this->class_name, __FUNCTION__, 'm19');
        $ret = $this->products_category_repository->updateProductsCategoryData($update_data, $products_category_no);

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * 製品カテゴリ情報削除
     * 選択した製品カテゴリ情報を削除
     *
     * @access public
     * @param array $products_category_no
     * @return Boolean
     * @see ProductsCategoryService
     * @throws
     * @todo
     */
    public function deleteProductsCategoryData($products_category_no)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'products_category_no' => $products_category_no
        ]);

        // 削除開始
        islog($this->class_name, __FUNCTION__, 'm20');
        $ret = $this->products_category_repository->deleteProductsCategoryData($products_category_no);

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }
}
