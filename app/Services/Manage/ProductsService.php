<?php
/**
 * 製品情報処理のServiceクラス
 * 検索、登録、更新、削除処理
 *
 * @access public
 * @author Seiji Ishikawa（メールアドレス）
 * @copyright Webじゃ軒 All Rights Reserved
 * @package service
 */
namespace App\Services\Manage;

use App\Repositories\Manage\ProductsRepositoryInterface;
use App\Repositories\Manage\ProductsCategoryRepositoryInterface;
use Carbon\Carbon;

class ProductsService
{

    protected $products_repository;

    protected $products_category_repository;

    protected $class_name;

    /**
     * コンストラクタ
     *
     * @access public
     * @param ProductsRepositoryInterface $products_repository
     *
     */
    public function __construct(ProductsRepositoryInterface $products_repository, ProductsCategoryRepositoryInterface $products_category_repository)
    {
        $this->products_repository = $products_repository;
        $this->products_category_repository = $products_category_repository;
        $this->class_name = get_class($this);
        islog($this->class_name, __FUNCTION__);
    }

    /**
     * 製品情報ページネーション一覧取得
     * 検索条件により全ての製品情報を取得
     *
     * @access public
     * @param string $company_no
     * @param string $free_word
     * @return \Illuminate\Database\Eloquent\Collection
     * @see ProductsService
     * @throws
     * @todo
     */
    public function getProductsPagenationList($company_no, $free_word = null)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'company_no' => $company_no,
            'free_word' => $free_word
        ]);

        // データ取得
        islog($this->class_name, __FUNCTION__, 'm10');
        $free_word = hscQuotes($free_word); //HTMLエンティティ変換
        $ret = $this->products_repository->getProductsPagenationList($company_no, $free_word);

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * 製品情報取得
     * 製品管理番号により製品情報を取得
     *
     * @access public
     * @param string $company_no
     * @param string $products_no
     * @return array
     * @see ProductsService
     * @throws
     * @todo
     */
    public function getProductsData($company_no, $products_no)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'company_no' => $company_no,
            'products_no' => $products_no
        ]);

        // データ取得
        islog($this->class_name, __FUNCTION__, 'm10');
        $ret = $this->products_repository->getProductsData($company_no, $products_no);
        $form_data = [];
        if (! empty($ret)) {
            // 配列化
            $form_data['input_no'] = $ret->products_no; // 製品番号
            $form_data['input_ct'] = $ret->products_customer_type; // お客様種別
            $form_data['input_st'] = $ret->products_service_type; // サービス種別
            $form_data['input_ctg_no'] = $ret->products_category_no; // 製品種別
            $form_data['input_ctg_name'] = $ret->products_category_name; // 製品種別名
            $form_data['input_name'] = $ret->products_name; // 製品名
            $form_data['input_cpb_no'] = $ret->comp_branch_no; // 支店番号
            $form_data['input_cpb_name'] = $ret->comp_branch_name; // 支店名
            $form_data['input_cpd_no'] = $ret->comp_department_no; // 部署番号
            $form_data['input_cpd_name'] = $ret->comp_department_name; // 部署名
            $form_data['input_acnt_no'] = $ret->acnt_no; // アカウント番号
            $form_data['input_acnt_name'] = $ret->acnt_name; // アカウント氏名
            $form_data['input_release_start'] = convertDateType($ret->release_start); // 掲載開始
            $form_data['input_release_end'] = convertDateType($ret->release_end); // 掲載終了
        }

        dslog($this->class_name, __FUNCTION__, 'm2', $form_data);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $form_data;
    }

    /**
     * 選択製品情報取得
     * 取得した製品情報をログインセッションへ設定
     *
     * @access public
     * @param string $products_no
     * @param array $login_data
     * @return array
     * @see ProductsService
     * @throws
     * @todo
     */
    public function getSelectProductsInfo($products_no, $login_data)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'products_no' => $products_no,
            'login_data' => $login_data
        ]);
        // パラメータ取得
        $company_no = $login_data['company_no'];

        // データ取得
        islog($this->class_name, __FUNCTION__, 'm10');
        $ret = $this->products_repository->getProductsData($company_no, $products_no);
        if (! empty($ret)) {
            // 配列化
            $login_data['products_no'] = $ret->products_no;
            $login_data['products_name'] = $ret->products_name;
            $login_data['products_detail_no'] = "";
        }

        dslog($this->class_name, __FUNCTION__, 'm2', $login_data);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $login_data;
    }

    /**
     * 製品情報登録
     * 入力した製品情報を登録
     *
     * @access public
     * @param array $form_data
     * @param string $company_no
     * @param string $acnt_no
     * @return Boolean
     * @see ProductsService
     * @throws
     * @todo
     */
    public function insertProductsData($form_data, $company_no, $acnt_no)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'form_data' => $form_data,
            'company_no' => $company_no,
            'acnt_no' => $acnt_no
        ]);

        // 新規登録
        // 登録データを設定
        islog($this->class_name, __FUNCTION__, 'm14');
        $insert_data['products_no'] = $form_data['input_no']; // 製品番号
        $insert_data['products_customer_type'] = $form_data['input_ct']; // お客様種別
        $insert_data['products_service_type'] = $form_data['input_st']; // サービス種別
        $insert_data['products_category_no'] = $form_data['input_ctg_no']; // 製品種別
        $insert_data['comp_no'] = $company_no; // 企業管理番号
        $insert_data['products_name'] = $form_data['input_name']; // 製品名
        $insert_data['comp_branch_no'] = $form_data['input_cpb_no']; // 支店番号
        $insert_data['comp_department_no'] = $form_data['input_cpd_no']; // 部署番号
        $insert_data['acnt_no'] = $form_data['input_acnt_no']; // アカウント番号
        $insert_data['release_start'] = $form_data['input_release_start']; // 掲載開始
        $insert_data['release_end'] = $form_data['input_release_end']; // 掲載終了
        $insert_data['created_at'] = Carbon::now(); // 登録日
        $insert_data['updated_at'] = Carbon::now(); // 更新日
        $insert_data['last_acnt_no'] = $acnt_no; // 最終更新者NO
        dslog($this->class_name, __FUNCTION__, 'm13', $insert_data);

        islog($this->class_name, __FUNCTION__, 'm18');
        $ret = $this->products_repository->insertProductsData($insert_data);

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * 製品情報更新
     * 入力した製品情報を更新
     *
     * @access public
     * @param array $form_data
     * @param string $input_no
     * @param string $company_no
     * @param string $acnt_no
     * @return Boolean
     * @see ProductsService
     * @throws
     * @todo
     */
    public function updateProductsData($form_data, $input_no, $company_no, $acnt_no)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'form_data' => $form_data,
            'input_no' => $input_no,
            'company_no' => $company_no,
            'acnt_no' => $acnt_no
        ]);

        // 既存更新
        // 更新データを設定
        islog($this->class_name, __FUNCTION__, 'm15');
        $update_data['products_customer_type'] = $form_data['input_ct']; // お客様種別
        $update_data['products_service_type'] = $form_data['input_st']; // サービス種別
        $update_data['products_category_no'] = $form_data['input_ctg_no']; // 製品種別
        $update_data['products_name'] = $form_data['input_name']; // 製品名
        $update_data['comp_branch_no'] = $form_data['input_cpb_no']; // 支店番号
        $update_data['comp_department_no'] = $form_data['input_cpd_no']; // 部署番号
        $update_data['acnt_no'] = $form_data['input_acnt_no']; // アカウント番号
        $update_data['release_start'] = $form_data['input_release_start']; // 掲載開始
        $update_data['release_end'] = $form_data['input_release_end']; // 掲載終了
        $update_data['updated_at'] = Carbon::now(); // 更新日
        $update_data['last_acnt_no'] = $acnt_no; // 最終更新者NO
        dslog($this->class_name, __FUNCTION__, 'm14', $update_data);

        islog($this->class_name, __FUNCTION__, 'm19');
        $ret = $this->products_repository->updateProductsData($update_data, $company_no, $input_no);

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * 製品情報削除
     * 選択した製品情報を削除
     *
     * @access public
     * @param array $products_no
     * @param string $company_no
     * @param string $acnt_no
     * @return Boolean
     * @see ProductsService
     * @throws
     * @todo
     */
    public function deleteProductsData($products_no, $company_no)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'products_no' => $products_no,
            'company_no' => $company_no
        ]);

        // 削除開始
        islog($this->class_name, __FUNCTION__, 'm20');
        $ret = $this->products_repository->deleteProductsData($products_no, $company_no);

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
     * @see ProductsService
     * @throws
     * @todo
     */
    public function setProductsLoginInfo($login_info, $form_data)
    {
        $login_info['products_no'] = $form_data['input_no'];
        $login_info['products_name'] = $form_data['input_name'];
        $login_info['products_detail_no'] = '';
        $login_info['products_detail_name'] = '';

        return $login_info;
    }
}
