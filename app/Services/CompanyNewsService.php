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

use App\Repositories\CompanyBranchNewsRepositoryInterface;

class CompanyNewsService
{

    protected $company_branch_news_repository;

    protected $class_name;

    /**
     * コンストラクタ
     *
     * @access public
     * @param CompanyBranchNewsRepositoryInterface $company_branch_news_repository
     *
     */
    public function __construct(CompanyBranchNewsRepositoryInterface $company_branch_news_repository)
    {
        $this->company_branch_news_repository = $company_branch_news_repository;
        $this->class_name = get_class($this);
        islog($this->class_name, __FUNCTION__);
    }

    /**
     * 企業支店ニュース情報一覧取得
     * 全ての企業支店ニュース情報を取得
     *
     * @access public
     * @param string $company_no
     * @return array
     * @see CompanyNewsService
     * @throws
     * @todo
     */
    public function getCompanyBranchNewsPagenationList($company_no)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'company_no' => $company_no
        ]);

        // データ取得
        islog($this->class_name, __FUNCTION__, 'm10');
        $ret_collection = $this->company_branch_news_repository->getCompanyBranchNewsPagenationList($company_no);

        // ヘッダー情報作成
        list ($header_title, $header_description, $header_keywords) = getSiteCompanyNewsList();

        // 正規URL取得
        $url = route('company_shop_news.index', $company_no);

        // データが取得できたら、パンくずデータとヘッダー情報作成
        $breadcrumbs_data = [];
        $image_no = null; // ヘッダー画像番号
        $image_path = null; // ヘッダー画像
        $image_width = 0; // ヘッダー画像幅
        $image_height = 0; // ヘッダー画像高
        if ($ret_collection->count() > 0) {

            // パンくずデータ作成
            islog($this->class_name, __FUNCTION__, 'm11');
            $ret_obj = $ret_collection->first(); // 企業指定なので取得データから１件のみ抽出
            $breadcrumbs_data = [
                'company_no' => $ret_obj->comp_no,
                'company_name' => $ret_obj->comp_name,
                'company_branch_no' => $ret_obj->comp_branch_no
            ];

            // ヘッダー情報作成
            islog($this->class_name, __FUNCTION__, 'm34');
            $header_title = $header_title . ' - ' . $ret_obj->comp_name;
            $header_description = $ret_obj->comp_name . ' ' . $header_description;

            // ヘッダー画像
            $image_no = $ret_obj->comp_banner_image_no; // 画像番号
            $image_path = getImagePath($ret_obj->image_folder, $ret_obj->image_name, $ret_obj->image_type); // 公開画像パス
            if (! empty($ret_obj->image_name) && ! empty($ret_obj->image_type)) {
                $image_local_path = getUploadImageFullPath($ret_obj->image_folder, $ret_obj->image_name, $ret_obj->image_type); // ローカル画像パス
                if (file_exists($image_local_path)){
                    list ($image_width, $image_height) = getimagesize($image_local_path);
                }
            }
        }

        // リターンパラメータ作成
        islog($this->class_name, __FUNCTION__, 'm13');
        $form_data = [
            'view' => $ret_collection,
            'sub_data' => [
                'breadcrumbs' => $breadcrumbs_data,
                'nav_company' => 'active', // ナビメニュー表示用
                'title' => $header_title, // タイトルテキスト
                'description' => $header_description, // 紹介テキスト
                'keywords' => $header_keywords, // キーワード
                'url' => $url, // 正規URL
                'image_no' => $image_no, // 画像番号
                'image_path' => $image_path, // 画像パス
                'image_width' => $image_width, // 画像幅
                'image_height' => $image_height // 画像高
            ]
        ];

        dslog($this->class_name, __FUNCTION__, 'm2', $form_data);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $form_data;
    }

    /**
     * 企業支店ニュース情報取得
     * 検索条件により企業支店ニュース情報を取得
     *
     * @access public
     * @param string $company_no
     * @param string $company_branch_no
     * @param int $company_branch_news_no
     * @return array
     * @see CompanyNewsService
     * @throws
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
        islog($this->class_name, __FUNCTION__, 'm10');
        $ret_collection = $this->company_branch_news_repository->getCompanyBranchNewsData($company_no, $company_branch_news_no, $company_branch_no);

        // 正規URL取得
        $url = route('company_shop_news.show', $company_no);
        $url .= "?b={$company_branch_no}&bn={$company_branch_news_no}";

        // データが取得できたら、パンくずリストデータとヘッダ情報も設定
        $breadcrumbs_data = [];
        $header_title = "";
        $header_description = "";
        $header_keywords = getSiteCompanyNews();
        $image_no = null;
        $image_path = null; // ヘッダー画像パス
        $image_width = 0; // ヘッダー画像幅
        $image_height = 0; // ヘッダー画像高

        if (! is_null($ret_collection)) {

            // パンくずデータ作成
            islog($this->class_name, __FUNCTION__, 'm11');
            $breadcrumbs_data = [
                'company_no' => $ret_collection->comp_no,
                'company_name' => $ret_collection->comp_name,
                'company_branch_no' => $ret_collection->comp_branch_no,
                'company_branch_name' => $ret_collection->comp_branch_name,
                'company_branch_news_no' => $ret_collection->comp_branch_news_no,
                'company_branch_news_title' => $ret_collection->comp_branch_news_title
            ];

            // ヘッダー情報作成
            islog($this->class_name, __FUNCTION__, 'm34');
            $header_title = "{$ret_collection->comp_branch_news_title} - {$ret_collection->comp_name}"; // タイトル
            $header_description = strip_tags($ret_collection->comp_branch_news_text); // 紹介
            $header_keywords = "{$ret_collection->comp_name},{$header_keywords}"; // タグ;

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
                'image_no' => $image_no, // 画像番号
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
