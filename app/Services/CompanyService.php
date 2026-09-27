<?php
/**
 * 企業情報処理のServiceクラス
 * 検索、登録、更新、削除処理
 *
 * @access public
 * @author Seiji Ishikawa（メールアドレス）
 * @copyright Webじゃ軒 All Rights Reserved
 * @package service
 */
namespace App\Services;

use App\Repositories\CompanyRepositoryInterface;
use App\Repositories\CompanyBranchFrontRepositoryInterface;

class CompanyService
{

    protected $company_repository;

    protected $company_branch_repository;

    protected $class_name;

    /**
     * コンストラクタ
     *
     * @access public
     * @param CompanyRepositoryInterface $company_repository
     * @param CompanyBranchFrontRepositoryInterface $company_branch_repository
     *
     */
    public function __construct(CompanyRepositoryInterface $company_repository, CompanyBranchFrontRepositoryInterface $company_branch_repository)
    {
        $this->company_repository = $company_repository;
        $this->company_branch_repository = $company_branch_repository;
        $this->class_name = get_class($this);
        islog($this->class_name, __FUNCTION__);
    }

    /**
     * 企業情報一覧取得
     * 検索条件により全ての企業情報を取得
     *
     * @access public
     * @param string $search_tag
     * @return array
     * @see CompanyService
     * @throws
     * @todo
     */
    public function getCompanyPagenationList($search_tag)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'search_tag' => $search_tag
        ]);

        // データ取得
        islog($this->class_name, __FUNCTION__, 'm10');
        $ret_collection = $this->company_repository->getCompanyPagenationList($search_tag);

        // ヘッダー情報作成
        list ($header_title, $header_description, $header_keywords) = getSiteCompanyList();

        // 正規URL作成
        $url = route('company_shop.index');

        // データが取得できたら、ヘッダー情報作成
        if ($ret_collection->count() > 0) {
            // ヘッダー情報作成
            islog($this->class_name, __FUNCTION__, 'm34');
            $comp_name = implode(',', array_unique($ret_collection->pluck('comp_name')
                ->filter()
                ->all())); // 企業名 ※emptyがtrueとなる値と重複値を除外、文字列化
            $header_description = $comp_name . $header_description;
        }

        // リターンパラメータ作成
        islog($this->class_name, __FUNCTION__, 'm13');
        $form_data = [
            'view' => $ret_collection,
            'sub_data' => [
                'breadcrumbs' => [], // パンくずリスト
                'nav_company' => 'active', // ナビメニュー表示用
                'title' => $header_title, // タイトルテキスト
                'description' => $header_description, // 紹介テキスト
                'keywords' => $header_keywords, // キーワード
                'url' => $url // 正規URL
            ]
        ];

        dslog($this->class_name, __FUNCTION__, 'm2', $form_data);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $form_data;
    }

    /**
     * 企業情報取得
     * 企業番号により企業情報を取得
     *
     * @access public
     * @param string $company_no
     * @return array
     * @see CompanyService
     * @throws
     * @todo
     */
    public function getCompanyData($company_no)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'company_no' => $company_no
        ]);

        // データ取得
        islog($this->class_name, __FUNCTION__, 'm10');
        $ret_collection = $this->company_repository->getCompanyData($company_no);

        // 正規URL作成
        $url = route('company_shop.show', $company_no);

        // データが取得できたら、パンくずリストデータとヘッダ情報も設定
        $breadcrumbs_data = [];
        $header_title = "";
        $header_description = "";
        $header_keywords = "";
        $image_no = null;
        $image_path = null; // ヘッダー画像
        $image_width = 0; // ヘッダー画像幅
        $image_height = 0; // ヘッダー画像高

        if (! is_null($ret_collection)) {
            // パンくずデータ作成
            islog($this->class_name, __FUNCTION__, 'm11');
            $breadcrumbs_data = [
                'company_no' => $ret_collection->comp_no,
                'company_name' => $ret_collection->comp_name
            ];

            // ヘッダー情報作成
            islog($this->class_name, __FUNCTION__, 'm34');
            $header_title = ! is_null($ret_collection->page_title) ? $ret_collection->page_title : $ret_collection->comp_name; // タイトル
            $header_description = strip_tags($ret_collection->comp_business_text); // 紹介
            $header_keywords = $ret_collection->search_tag; // タグ;
                                                            // ヘッダー画像
            $image_no = $ret_collection->comp_banner_image_no; // 画像番号
            $image_path = getImagePath($ret_collection->image_folder, $ret_collection->image_name, $ret_collection->image_type); // 画像パス
            if (! empty($ret_collection->image_name) && ! empty($ret_collection->image_type)) {
                $image_local_path = getUploadImageFullPath($ret_collection->image_folder, $ret_collection->image_name, $ret_collection->image_type); // ローカル画像パス
                if (file_exists($image_local_path)){
                    list ($image_width, $image_height) = getimagesize($image_local_path);
                }
            }
        }

        $form_data = [
            'view' => $ret_collection,
            'sub_data' => [
                'breadcrumbs' => $breadcrumbs_data, // パンくずリスト
                'nav_company' => 'active', // ナビメニュー表示用
                'title' => $header_title, // タイトルテキスト
                'description' => $header_description, // 紹介テキスト
                'keywords' => $header_keywords, // キーワード
                'url' => $url, // 正規URL
                'image_no' => $image_no,
                'image_path' => $image_path, // 画像パス
                'image_width' => $image_width, // 画像幅
                'image_height' => $image_height // 画像高
            ]
        ];

        dslog($this->class_name, __FUNCTION__, 'm2', $form_data);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $form_data;
    }
}
