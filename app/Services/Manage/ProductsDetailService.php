<?php
/**
 * 製品詳細情報処理のServiceクラス
 * 検索、登録、更新、削除処理
 *
 * @access public
 * @author Seiji Ishikawa（メールアドレス）
 * @copyright Webじゃ軒 All Rights Reserved
 * @package service
 */
namespace App\Services\Manage;

use App\Repositories\Manage\ProductsDetailRepositoryInterface;
use Carbon\Carbon;

class ProductsDetailService
{

    protected $products_detail_repository;

    protected $class_name;

    /**
     * コンストラクタ
     *
     * @access public
     * @param ProductsDetailRepositoryInterface $products_detail_repository
     *
     */
    public function __construct(ProductsDetailRepositoryInterface $products_detail_repository)
    {
        $this->products_detail_repository = $products_detail_repository;
        $this->class_name = get_class($this);
        islog($this->class_name, __FUNCTION__);
    }

    /**
     * 製品詳細情報ページネーション一覧取得
     * 検索条件により全ての製品詳細情報を取得
     *
     * @access public
     * @param string $company_no
     * @param string $products_no
     * @param string $free_word
     * @return \Illuminate\Database\Eloquent\Collection
     * @see ProductsDetailService
     * @throws
     * @todo
     */
    public function getProductsDetailPagenationList($company_no, $products_no, $free_word = null)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'company_no' => $company_no,
            'products_no' => $products_no,
            'free_word' => $free_word
        ]);

        // データ取得
        islog($this->class_name, __FUNCTION__, 'm10');
        $free_word = hscQuotes($free_word); //HTMLエンティティ変換
        $ret = $this->products_detail_repository->getProductsDetailPagenationList($company_no, $products_no, $free_word);

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * 製品詳細情報取得
     * 製品詳細管理番号により製品詳細情報を取得
     *
     * @access public
     * @param string $company_no
     * @param int $products__no
     * @param int $products_detail_no
     * @return array
     * @see ProductsDetailService
     * @throws
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
        islog($this->class_name, __FUNCTION__, 'm10');
        $ret = $this->products_detail_repository->getProductsDetailData($company_no, $products_no, $products_detail_no);
        $form_data = [];
        if (! empty($ret)) {
            // 配列化
            $form_data['input_no'] = $ret->products_detail_no; // 製品詳細番号
            $form_data['input_p_no'] = $ret->products_no; // 製品管理番号
            $form_data['input_image_no'] = (int)$ret->products_detail_image_no; // 製品詳細画像番号
            $form_data['input_image_name'] = $ret->image_name; // 製品画像名称
            $form_data['input_image_folder'] = $ret->image_folder; // 製品画像フォルダ
            $form_data['input_image_type'] = $ret->image_type; // 製品画像種別
            $form_data['input_image_alt'] = $ret->image_alt; // 製品画像代替テキスト
            $form_data['input_image_text'] = convertHtmlText($ret->image_text); // 製品画像キャプション
            $form_data['input_name'] = $ret->products_detail_title; // 製品詳細タイトル
            $form_data['input_description'] = $ret->products_detail_description; // 製品詳細紹介
            $form_data['input_text'] = $ret->products_detail_text; // 製品詳細テキスト
            $form_data['input_image_file'] = getImagePath($ret->image_folder, $ret->image_name, $ret->image_type); // imgタグのsrcパス
            $form_data['input_search_tag'] = $ret->search_tag; // タグ
            $form_data['input_release_start'] = convertDateType($ret->release_start); // 掲載開始
            $form_data['input_release_end'] = convertDateType($ret->release_end); // 掲載終了
        }

        dslog($this->class_name, __FUNCTION__, 'm2', $form_data);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $form_data;
    }

    /**
     * 製品詳細情報登録
     * 入力した製品詳細情報を登録
     *
     * @access public
     * @param array $form_data
     * @param string $products_no
     * @param string $company_no
     * @param string $acnt_no
     * @return Boolean
     * @see ProductsDetailService
     * @throws
     * @todo
     */
    public function insertProductsDetailData($form_data, $products_no, $company_no, $acnt_no)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'form_data' => $form_data,
            'products_no' => $products_no,
            'company_no' => $company_no,
            'acnt_no' => $acnt_no
        ]);

        // 新規登録
        // 登録データを設定
        islog($this->class_name, __FUNCTION__, 'm14');
        $insert_data['products_detail_no'] = $form_data['input_no']; // 製品詳細番号
        $insert_data['products_no'] = $products_no; // 製品番号
        $insert_data['comp_no'] = $company_no; // 企業番号
        $insert_data['products_detail_image_no'] = (int)$form_data['input_image_no']; // 製品詳細画像番号
        $insert_data['products_detail_title'] = $form_data['input_name']; // 製品詳細タイトル
        $insert_data['products_detail_description'] = $form_data['input_description']; // 製品詳細紹介
        $insert_data['products_detail_text'] = convertHtmlText($form_data['input_text']); // 製品詳細テキスト
        $insert_data['search_tag'] = $form_data['input_search_tag']; // タグ
        $insert_data['release_start'] = $form_data['input_release_start']; // 掲載開始
        $insert_data['release_end'] = $form_data['input_release_end']; // 掲載終了
        $insert_data['created_at'] = Carbon::now(); // 登録日
        $insert_data['updated_at'] = Carbon::now(); // 更新日
        $insert_data['last_acnt_no'] = $acnt_no; // 最終更新者NO
        dslog($this->class_name, __FUNCTION__, 'm13', $insert_data);

        islog($this->class_name, __FUNCTION__, 'm18');
        $ret = $this->products_detail_repository->insertProductsDetailData($insert_data);

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    
    /**
     * 製品詳細情報更新
     * 入力した製品詳細情報を更新
     *
     * @access public
     * @param array $form_data
     * @param string $input_no
     * @param string $products_no
     * @param string $company_no
     * @param string $acnt_no
     * @return Boolean
     * @see ProductsDetailService
     * @throws
     * @todo
     */
    public function updateProductsDetailData($form_data, $input_no, $products_no, $company_no, $acnt_no)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'form_data' => $form_data,
            'input_no' => $input_no,
            'products_no' => $products_no,
            'company_no' => $company_no,
            'acnt_no' => $acnt_no
        ]);
        
        // 既存更新
        // 更新データを設定
        islog($this->class_name, __FUNCTION__, 'm15');
        $update_data['products_detail_image_no'] = (int)$form_data['input_image_no']; // 製品詳細画像番号
        $update_data['products_detail_title'] = $form_data['input_name']; // 製品詳細タイトル
        $update_data['products_detail_description'] = $form_data['input_description']; // 製品詳細紹介
        $update_data['products_detail_text'] = convertHtmlText($form_data['input_text']); // 製品詳細テキスト
        $update_data['search_tag'] = $form_data['input_search_tag']; // タグ
        $update_data['release_start'] = $form_data['input_release_start']; // 掲載開始
        $update_data['release_end'] = $form_data['input_release_end']; // 掲載終了
        $update_data['updated_at'] = Carbon::now(); // 更新日
        $update_data['last_acnt_no'] = $acnt_no; // 最終更新者NO
        dslog($this->class_name, __FUNCTION__, 'm14', $update_data);
        
        islog($this->class_name, __FUNCTION__, 'm19');
        $ret = $this->products_detail_repository->updateProductsDetailData($update_data, $company_no, $products_no, $input_no);
        
        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }
    
    /**
     * 製品詳細情報削除
     * 選択した製品詳細情報を削除
     *
     * @access public
     * @param array $products_detail_no
     * @param string $products_no
     * @param string $company_no
     * @return Boolean
     * @see ProductsDetailService
     * @throws
     * @todo
     */
    public function deleteProductsDetailData($products_detail_no, $products_no, $company_no)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'products_detail_no' => $products_detail_no,
            'products_no' => $products_no,
            'company_no' => $company_no
        ]);

        // 削除開始
        islog($this->class_name, __FUNCTION__, 'm20');
        $ret = $this->products_detail_repository->deleteProductsDetailData($products_detail_no, $products_no, $company_no);

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * ログイン情報設定
     * 選択した製品情報をログイン情報として設定
     *
     * @access public
     * @param array $login_info
     * @param array $form_data
     * @return array
     * @see ProductsDetailService
     * @throws
     * @todo
     */
    public function setProductsDetailLoginInfo($login_info, $form_data)
    {
        $login_info['products_detail_no'] = $form_data['input_no'];
        $login_info['products_detail_name'] = $form_data['input_name'];
        
        return $login_info;
    }
}
