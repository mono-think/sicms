<?php
/**
 * 製品レビュー情報処理のServiceクラス
 * 検索、登録、更新、削除処理
 *
 * @access public
 * @author Seiji Ishikawa（メールアドレス）
 * @copyright Webじゃ軒 All Rights Reserved
 * @package service
 */
namespace App\Services\Manage;

use App\Repositories\Manage\ProductsReviewRepositoryInterface;
use Carbon\Carbon;

class ProductsReviewService
{

    protected $products_review_repository;

    protected $class_name;

    /**
     * コンストラクタ
     *
     * @access public
     * @param ProductsReviewRepositoryInterface $products_review_repository
     *
     */
    public function __construct(ProductsReviewRepositoryInterface $products_review_repository)
    {
        $this->products_review_repository = $products_review_repository;
        $this->class_name = get_class($this);
        islog($this->class_name, __FUNCTION__);
    }

    /**
     * 製品レビュー情報ページネーション一覧取得
     * 検索条件により全ての製品レビュー情報を取得
     *
     * @access public
     * @param string $company_no
     * @param string $products_no
     * @param string $products_detail_no
     * @param string $free_word
     * @return \Illuminate\Database\Eloquent\Collection
     * @see ProductsReviewService
     * @throws
     * @todo
     */
    public function getProductsReviewPagenationList($company_no, $products_no, $products_detail_no, $free_word = null)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'company_no' => $company_no,
            'products_no' => $products_no,
            'products_detail_no' => $products_detail_no,
            'free_word' => $free_word
        ]);

        // データ取得
        islog($this->class_name, __FUNCTION__, 'm10');
        $free_word = hscQuotes($free_word); //HTMLエンティティ変換
        $ret = $this->products_review_repository->getProductsReviewPagenationList($company_no, $products_no, $products_detail_no, $free_word);

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * 製品レビュー情報取得
     * 製品レビュー管理番号により製品レビュー情報を取得
     *
     * @access public
     * @param string $company_no
     * @param string $products__no
     * @param string $products_detail_no
     * @param string $products_review_no
     * @return array
     * @see ProductsReviewService
     * @throws
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
        islog($this->class_name, __FUNCTION__, 'm10');
        $ret = $this->products_review_repository->getProductsReviewData($company_no, $products_no, $products_detail_no, $products_review_no);
        $form_data = [];
        if (! empty($ret)) {
            // 配列化
            $form_data['input_no'] = $ret->products_review_no; // 製品レビュー管理番号
            $form_data['input_pd_no'] = $ret->products_detail_no; // 製品詳細管理番号
            $form_data['input_p_no'] = $ret->products_no; // 製品管理番号
            $form_data['input_name'] = $ret->products_review_title; // レビュータイトル
            $form_data['input_text'] = convertHtmlText($ret->products_review_text); // レビューテキスト
        }

        dslog($this->class_name, __FUNCTION__, 'm2', $form_data);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $form_data;
    }

    /**
     * 製品レビュー情報登録
     * 入力した製品レビュー情報を登録
     *
     * @access public
     * @param array $form_data
     * @param string $products_no
     * @param string $products_detail_no
     * @param string $company_no
     * @param string $acnt_no
     * @return Boolean
     * @see ProductsReviewService
     * @throws
     * @todo
     */
    public function insertProductsReviewData($form_data, $products_no, $products_detail_no, $company_no, $acnt_no)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'form_data' => $form_data,
            'products_no' => $products_no,
            'products_detail_no' => $products_detail_no,
            'company_no' => $company_no,
            'acnt_no' => $acnt_no
        ]);

        // 新規登録
        // 登録データを設定
        islog($this->class_name, __FUNCTION__, 'm14');
        $insert_data['products_review_no'] = $form_data['input_no']; // 製品レビュー番号
        $insert_data['products_detail_no'] = $products_detail_no; // 製品詳細管理番号
        $insert_data['products_no'] = $products_no; // 製品管理番号
        $insert_data['comp_no'] = $company_no; // 企業番号
        $insert_data['products_review_title'] = $form_data['input_name']; // 製品レビュータイトル
        $insert_data['products_review_text'] = convertHtmlText($form_data['input_text']); // 製品レビューテキスト
        $insert_data['created_at'] = Carbon::now(); // 登録日
        $insert_data['updated_at'] = Carbon::now(); // 更新日
        $insert_data['last_acnt_no'] = $acnt_no; // 最終更新者NO
        dslog($this->class_name, __FUNCTION__, 'm13', $insert_data);

        islog($this->class_name, __FUNCTION__, 'm18');
        $ret = $this->products_review_repository->insertProductsReviewData($insert_data);

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * 製品レビュー情報更新
     * 入力した製品レビュー情報を更新
     *
     * @access public
     * @param array $form_data
     * @param string $input_no
     * @param string $products_no
     * @param string $products_detail_no
     * @param string $company_no
     * @param string $acnt_no
     * @return Boolean
     * @see ProductsReviewService
     * @throws
     * @todo
     */
    public function updateProductsReviewData($form_data, $input_no, $products_no, $products_detail_no, $company_no, $acnt_no)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'form_data' => $form_data,
            'input_no' => $input_no,
            'products_no' => $products_no,
            'products_detail_no' => $products_detail_no,
            'company_no' => $company_no,
            'acnt_no' => $acnt_no
        ]);
        
        // 既存更新
        // 更新データを設定
        
        islog($this->class_name, __FUNCTION__, 'm15');
        $update_data['products_review_title'] = $form_data['input_name']; // 製品レビュータイトル
        $update_data['products_review_text'] = convertHtmlText($form_data['input_text']); // 製品レビューテキスト
        $update_data['updated_at'] = Carbon::now(); // 更新日
        $update_data['last_acnt_no'] = $acnt_no; // 最終更新者NO
        dslog($this->class_name, __FUNCTION__, 'm14', $update_data);

        islog($this->class_name, __FUNCTION__, 'm19');
        $ret = $this->products_review_repository->updateProductsReviewData($update_data, $company_no, $products_no, $products_detail_no, $input_no);

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }
    
    /**
     * 製品レビュー情報削除
     * 選択した製品レビュー情報を削除
     *
     * @access public
     * @param array $products_review_no
     * @param string $products_no
     * @param string $products_detail_no
     * @param string $company_no
     * @return Boolean
     * @see ProductsReviewService
     * @throws
     * @todo
     */
    public function deleteProductsReviewData($products_review_no, $products_no, $products_detail_no, $company_no)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'products_review_no' => $products_review_no,
            'products_no' => $products_no,
            'products_detail_no' => $products_detail_no,
            'company_no' => $company_no
        ]);

        // 削除開始
        islog($this->class_name, __FUNCTION__, 'm20');
        $ret = $this->products_review_repository->deleteProductsReviewData($products_review_no, $products_no, $products_detail_no, $company_no);

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }
}
