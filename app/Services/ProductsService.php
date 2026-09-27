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
namespace App\Services;

use App\Repositories\ProductsRepositoryInterface;
use App\Repositories\ProductsCategoryRepositoryInterface;

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
     * 製品カテゴリ一覧取得
     * 製品カテゴリ情報の一覧を取得
     *
     * @access public
     * @param string $company_no
     * @return array
     * @see ProductsService
     * @throws
     * @todo
     */
    public function getProductsCategoryPagenationList($company_no)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'company_no' => $company_no
        ]);

        // データ取得
        islog($this->class_name, __FUNCTION__, 'm10');
        $ret_collection = $this->products_category_repository->getProductsCategoryPagenationList($company_no);

        // ヘッダー情報作成
        list ($header_title, $header_description, $header_keywords) = getSiteProductsCategory();

        // 正規URL作成
        $url = route('front_products_category.index');
        $url .= ! empty($company_no) ? "&c={$company_no}" : "";

        // データが取得できたら、パンくずデータ作成
        $breadcrumbs_data = [];
        $ret_data = collect([]);
        if ($ret_collection->count() > 0) {
            // パンくずデータ作成
            islog($this->class_name, __FUNCTION__, 'm11');
            $ret_obj = $ret_collection->first();
            if (! is_null($company_no)) {
                $breadcrumbs_data = [
                    'company_no' => $ret_obj->comp_no,
                    'company_name' => $ret_obj->comp_name
                ];
            }
            if (! empty($ret_obj->products_category_no)) {
                $ret_data = $ret_collection;
            }
        }

        // リターンパラメータ作成
        islog($this->class_name, __FUNCTION__, 'm13');
        $form_data = [
            'view' => $ret_data,
            'sub_data' => [
                'breadcrumbs' => $breadcrumbs_data, // パンくずリスト
                'nav_products' => 'active',
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
     * 製品情報一覧取得
     * 製品情報の一覧を取得
     *
     * @access public
     * @param int $products_category_no
     * @param string $company_no
     * @param string $search_tag
     * @return array
     * @see ProductsService
     * @throws
     * @todo
     */
    public function getProductsAndDetailNewPagenationList($products_category_no, $company_no, $search_tag = null)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'products_category_no' => $products_category_no,
            'company_no' => $company_no,
            'search_tag' => $search_tag
        ]);

        // データ取得
        islog($this->class_name, __FUNCTION__, 'm10');
        $ret_collection = $this->products_repository->getProductsAndDetailNewPagenationList($products_category_no, $company_no, $search_tag);

        // デフォルトヘッダー情報取得
        list ($header_title, $header_description) = getSiteProductsList();
        $header_keywords = "";

        // 正規URL作成
        $url = route('front_products.index', $products_category_no);
        $url .= ! empty($company_no) ? "&c={$company_no}" : "";

        // データが取得できたら、パンくずリストデータとヘッダ情報、ページ表題も設定
        $breadcrumbs_data = [];
        $page_title = '';
        $image_no = null; // ヘッダー画像番号
        $image_path = null; // ヘッダー画像
        $image_width = 0; // ヘッダー画像幅
        $image_height = 0; // ヘッダー画像高
        if ($ret_collection->count() > 0) {

            // パンくずデータ作成
            islog($this->class_name, __FUNCTION__, 'm11');
            $ret_obj = $ret_collection->first();
            $breadcrumbs_data = [
                'category' => $ret_obj->products_category_no,
                'category_name' => $ret_obj->products_category_name
            ];
            // 企業指定有りなら企業情報も設定
            if (! is_null($company_no)) {
                $breadcrumbs_data['company_no'] = $ret_obj->comp_no;
                $breadcrumbs_data['company_name'] = $ret_obj->comp_name;
            }

            // ヘッダー情報作成
            islog($this->class_name, __FUNCTION__, 'm34');
            $products_text = implode(',', array_unique($ret_collection->pluck('products_name')
                ->filter()
                ->all())); // カテゴリ名 ※emptyがtrueとなる値と重複値を除外、文字列化
            $header_title = "{$ret_obj->products_category_name}{$header_title}";
            $header_description = "{$products_text}{$header_description}";
            $header_keywords = $products_text;
            // ヘッダー画像
            $image_no = $ret_obj->image_no; // 画像番号
            $image_path = getImagePath($ret_obj->image_folder, $ret_obj->image_name, $ret_obj->image_type); // ヘッダー画像パス
            if (! empty($ret_obj->image_name) && ! empty($ret_obj->image_type)) {
                $image_local_path = getUploadImageFullPath($ret_obj->image_folder, $ret_obj->image_name, $ret_obj->image_type); // ローカル画像パス
                if (file_exists($image_local_path)){
                    list ($image_width, $image_height) = getimagesize($image_local_path);
                }
            }

            // ページ表題
            $page_title = $ret_obj->products_category_name;
        }

        // リターンパラメータ作成
        islog($this->class_name, __FUNCTION__, 'm13');
        $form_data = [
            'view' => $ret_collection,
            'sub_data' => [
                'breadcrumbs' => $breadcrumbs_data, // パンくずリスト
                'nav_products' => 'active', // ナビメニュー表示用
                'page_title' => $page_title, // ページタイトル
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

    /**
     * 製品詳細情報取得
     * 製品番号などにより製品詳細情報を取得
     *
     * @access public
     * @param string $products_category_no
     * @param string $products_no
     * @param string $products_detail_no
     * @param string $company_no
     * @return array
     * @see ProductsService
     * @throws
     * @todo
     */
    public function getProductsDetailData($products_category_no, $products_no, $products_detail_no, $company_no)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'products_category_no' => $products_category_no,
            'products_no' => $products_no,
            'products_detail_no' => $products_detail_no,
            'company_no' => $company_no
        ]);

        // データ取得
        islog($this->class_name, __FUNCTION__, 'm10');
        $ret_collection = $this->products_repository->getProductsDetailData($products_category_no, $products_no, $products_detail_no, $company_no);

        // 正規URL作成
        $url = route('front_products.show', $products_category_no);
        $url .= "?n={$products_no}&d={$products_detail_no}";
        $url .= ! empty($company_no) ? "&c={$company_no}" : "";

        // データが取得できたら、パンくずデータとヘッダ情報も設定
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
                'category' => $ret_collection->products_category_no,
                'category_name' => $ret_collection->products_category_name,
                'product' => $ret_collection->products_no,
                'products_name' => $ret_collection->products_name,
                'detail' => $ret_collection->products_detail_no,
                'detail_name' => $ret_collection->products_detail_title,
                'company_no' => $ret_collection->comp_no,
                'company_name' => $ret_collection->comp_name
            ];

            // ヘッダー情報作成
            islog($this->class_name, __FUNCTION__, 'm34');
            $header_title = $ret_collection->products_detail_title . "($ret_collection->products_name)";
            $header_description = $ret_collection->products_detail_description;
            $header_keywords = $ret_collection->search_tag; // タグ;

            // ヘッダー画像
            $image_no = $ret_collection->image_no; // 製品画像番号
                                                   // 製品画像を設定
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
                'nav_products' => 'active', // ナビメニュー表示用
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
