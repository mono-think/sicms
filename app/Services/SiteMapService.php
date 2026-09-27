<?php
/**
 * XML制御処理のServiceクラス
 * 登録、更新、削除処理
 *
 * @access public
 * @author Seiji Ishikawa（メールアドレス）
 * @copyright Webじゃ軒 All Rights Reserved
 * @package service
 */
namespace App\Services;

use App\Repositories\ProductsCategoryRepositoryInterface;
use App\Repositories\ProductsRepositoryInterface;
use App\Repositories\CompanyRepositoryInterface;
use App\Repositories\CompanyBranchNewsRepositoryInterface;
use App\Repositories\EventRepositoryInterface;
use App\Repositories\ContentRepositoryInterface;
use Carbon\Carbon;

class SiteMapService
{

    protected $products_category_repository;

    protected $products_repository;

    protected $company_repository;

    protected $company_branch_news_repository;

    protected $event_repository;

    protected $content_repository;

    protected $date_format;

    protected $class_name;

    /**
     * コンストラクタ
     *
     * @access public
     *        
     */
    public function __construct(ProductsCategoryRepositoryInterface $products_category_repository, ProductsRepositoryInterface $products_repository, CompanyRepositoryInterface $company_repository, CompanyBranchNewsRepositoryInterface $company_branch_news_repository, EventRepositoryInterface $event_repository, ContentRepositoryInterface $content_repository)
    {
        $this->products_category_repository = $products_category_repository;
        $this->products_repository = $products_repository;
        $this->company_repository = $company_repository;
        $this->company_branch_news_repository = $company_branch_news_repository;
        $this->event_repository = $event_repository;
        $this->content_repository = $content_repository;
        $this->class_name = get_class($this);
        //$this->date_format = 'Y-m-dTH:i:sP';
        $this->date_format = 'Y-m-d';
        islog($this->class_name, __FUNCTION__);
    }

    /**
     * サイトマップ生成データ取得
     * 各関数より得られたデータをまとめる
     *
     * @access public
     * @return array
     * @see SiteMapService
     * @throws
     * @todo
     */
    public function getSiteMapData()
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        $ret_data = [];

        // 製品カテゴリリスト
        $ret_products_category_data = $this->getSiteMapProductsCategoryData();

        // 製品リスト
        $ret_products_list_data = $this->getSiteMapProductsListData();

        // 製品
        $ret_products_data = $this->getSiteMapProductsData();

        // 企業ショップ
        $ret_company_data = $this->getSiteMapCompanyShopListData();

        // 企業ショップニュース
        $ret_company_news_data = $this->getSiteMapCompanyShopNewsListData();

        // イベントリスト
        $ret_event_data = $this->getSiteMapEventListData();

        // コンテンツリスト
        $ret_contents_data = $this->getSiteMapContentListData();

        // Topページ、その他とマージ
        $ret_data = $this->getSiteMapIndexData([
            $ret_products_category_data,
            $ret_products_list_data,
            $ret_products_data,
            $ret_company_data,
            $ret_company_news_data,
            $ret_event_data,
            $ret_contents_data
        ]);

        dslog($this->class_name, __FUNCTION__, 'm2', $ret_data);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret_data;
    }

    /**
     * サイトマップ生成データマージ
     * TOPページなど変化の少ないページ情報もここで設定、マージ
     *
     * @access private
     * @param array $mearge_data
     * @return array
     * @see SiteMapService
     * @throws
     * @todo
     */
    private function getSiteMapIndexData($mearge_data)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'mearge_data' => $mearge_data
        ]);
        $top_sitemap_data = [];
        $sitemap_data = [];
        
        $last_date = '';
        foreach($mearge_data as $m_data){
            //サイトマップ配列をマージ
            $sitemap_data = array_merge($sitemap_data, $m_data['sitemap']);
            if ($m_data === reset($mearge_data)) {
                // 先頭のみそのまま設定
                $last_date = $m_data['last_update_date'];
            } else {
                // 比較して直近の日時を設定
                $lastdate_obj = new Carbon($last_date);
                if (!empty($m_data['last_update_date'])){
                    $date_obj = new Carbon($m_data['last_update_date']);
                    if ($date_obj->gt($lastdate_obj)){
                        $last_date = $m_data['last_update_date'];
                    }
                }
            }
        }

        // Topページ
        $top_sitemap_data[] = [
            'url' => route('index'),
            'lastmod' => $last_date,
            'changefreq' => 'monthly',
            'priority' => '0.5'
        ];

        $sitemap_data = array_merge($top_sitemap_data, $sitemap_data);
        
        dslog($this->class_name, __FUNCTION__, 'm2', $sitemap_data);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $sitemap_data;
    }

    /**
     * サイトマップ生成データ取得（製品カテゴリリスト）
     * 製品カテゴリページ情報を取得
     *
     * @access private
     * @return array
     * @see SiteMapService
     * @throws
     * @todo
     */
    private function getSiteMapProductsCategoryData()
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        $sitemap_data = [];
        $last_update_date = '';

        // 製品カテゴリ情報取得
        $ret = $this->products_category_repository->getProductsCategoryList(null);
        $ret_finfo = $ret->first();
        if (! is_null($ret_finfo)) {
            // データ取得先頭のみ
            // 日付変換
            $date_obj = new Carbon($ret_finfo->updated_at);
            $update_date = $date_obj->format($this->date_format);
            
            $sitemap_data[] = [
                'url' => route('front_products_category.index'),
                'lastmod' => $update_date,
                'changefreq' => 'weekly',
                'priority' => '0.5'
            ];

            // 先頭の日付のみ別で確保
            $last_update_date = $update_date;

            // データ取得
            $ret = $ret->unique('comp_no'); // 処理前に企業番号の重複を除外
            foreach ($ret as $ret_data) {
                // 日付変換
                $date_obj = new Carbon($ret_data->updated_at);
                $update_date = $date_obj->format($this->date_format);

                // 企業番号付き
                $sitemap_data[] = [
                    'url' => route('front_products_category.index') . "?c={$ret_data->comp_no}",
                    'lastmod' => $update_date,
                    'changefreq' => 'weekly',
                    'priority' => '0.5'
                ];
            }
        }

        // 戻り値作成
        $ret_value = [
            'sitemap' => $sitemap_data,
            'last_update_date' => $last_update_date
        ];

        dslog($this->class_name, __FUNCTION__, 'm2', $ret_value);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret_value;
    }

    /**
     * サイトマップ生成データ取得（製品リスト）
     * 製品リスト情報を取得
     *
     * @access private
     * @return array
     * @see SiteMapService
     * @throws
     * @todo
     */
    private function getSiteMapProductsListData()
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        $sitemap_data = [];
        $last_update_date = '';

        // 製品情報取得
        $ret = $this->products_repository->getProductsAndDetailNewList(null, null);
        $ret_finfo = $ret->first();
        if (! is_null($ret_finfo)) {
            // 日付変換
            $date_obj = new Carbon($ret_finfo->updated_at);
            $update_date = $date_obj->format($this->date_format);
            // 先頭の日付のみ別で確保
            $last_update_date = $update_date;

            // データ取得（企業番号なし）
            $ret_info = $ret->unique('products_category_no'); // 処理前に製品カテゴリ番号の重複を除外
            foreach ($ret_info as $ret_data) {
                // 日付変換
                $date_obj = new Carbon($ret_data->updated_at);
                $update_date = $date_obj->format($this->date_format);

                // 企業番号なし
                $sitemap_data[] = [
                    'url' => route('front_products.index', $ret_data->products_category_no),
                    'lastmod' => $update_date,
                    'changefreq' => 'weekly',
                    'priority' => '0.7'
                ];
            }

            // データ取得（企業番号付き）
            $ret_info = $ret->unique('comp_no'); // 処理前に企業番号の重複を除外
            foreach ($ret_info as $ret_data) {
                // 日付変換
                $date_obj = new Carbon($ret_data->updated_at);
                $update_date = $date_obj->format($this->date_format);

                // 企業番号付き
                $sitemap_data[] = [
                    'url' => route('front_products.index', $ret_data->products_category_no) . "?c={$ret_data->comp_no}",
                    'lastmod' => $update_date,
                    'changefreq' => 'weekly',
                    'priority' => '0.7'
                ];
            }
        }

        // 戻り値作成
        $ret_value = [
            'sitemap' => $sitemap_data,
            'last_update_date' => $last_update_date
        ];

        dslog($this->class_name, __FUNCTION__, 'm2', $ret_value);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret_value;
    }

    /**
     * サイトマップ生成データ取得（製品）
     * 製品情報を取得
     *
     * @access private
     * @return array
     * @see SiteMapService
     * @throws
     * @todo
     */
    private function getSiteMapProductsData()
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        $sitemap_data = [];
        $last_update_date = '';

        // 製品情報取得
        $ret = $this->products_repository->getProductsList(null, null, null);
        $ret_finfo = $ret->first();
        if (! is_null($ret_finfo)) {
            // 日付変換
            $date_obj = new Carbon($ret_finfo->updated_at);
            $update_date = $date_obj->format($this->date_format);
            // 先頭の日付のみ別で確保
            $last_update_date = $update_date;

            // データ取得（企業番号なし）
            foreach ($ret as $ret_data) {
                // 日付変換
                $date_obj = new Carbon($ret_data->updated_at);
                $update_date = $date_obj->format($this->date_format);

                // 企業番号なし
                $sitemap_data[] = [
                    'url' => route('front_products.show', $ret_data->products_category_no) . "?n={$ret_data->products_no}&d={$ret_data->products_detail_no}",
                    'lastmod' => $update_date,
                    'changefreq' => 'weekly',
                    'priority' => '1.0'
                ];

                // 企業番号付き
                $sitemap_data[] = [
                    'url' => route('front_products.show', $ret_data->products_category_no) . "?n={$ret_data->products_no}&d={$ret_data->products_detail_no}&c={$ret_data->comp_no}",
                    'lastmod' => $update_date,
                    'changefreq' => 'weekly',
                    'priority' => '1.0'
                ];
            }
        }

        // 戻り値作成
        $ret_value = [
            'sitemap' => $sitemap_data,
            'last_update_date' => $last_update_date
        ];

        dslog($this->class_name, __FUNCTION__, 'm2', $ret_value);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret_value;
    }

    /**
     * サイトマップ生成データ取得（企業ショップリスト）
     * 企業ショップリスト情報を取得
     *
     * @access private
     * @return array
     * @see SiteMapService
     * @throws
     * @todo
     */
    private function getSiteMapCompanyShopListData()
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        $sitemap_data = [];
        $last_update_date = '';

        // 企業ショップ情報取得
        $ret = $this->company_repository->getCompanyList();
        $ret_finfo = $ret->first();
        if (! is_null($ret_finfo)) {
            // データ取得先頭のみ
            // 日付変換
            $date_obj = new Carbon($ret_finfo->update_date);
            $update_date = $date_obj->format($this->date_format);

            $sitemap_data[] = [
                'url' => route('company_shop.index'),
                'lastmod' => $update_date,
                'changefreq' => 'monthly',
                'priority' => '0.5'
            ];

            // 先頭の日付のみ別で確保
            $last_update_date = $update_date;

            // データ取得
            foreach ($ret as $ret_data) {
                // 日付変換
                $date_obj = new Carbon($ret_data->update_date);
                $update_date = $date_obj->format($this->date_format);

                $sitemap_data[] = [
                    'url' => route('company_shop.show', $ret_data->comp_no),
                    'lastmod' => $update_date,
                    'changefreq' => 'monthly',
                    'priority' => '0.5'
                ];
            }
        }

        // 戻り値作成
        $ret_value = [
            'sitemap' => $sitemap_data,
            'last_update_date' => $last_update_date
        ];

        dslog($this->class_name, __FUNCTION__, 'm2', $ret_value);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret_value;
    }

    /**
     * サイトマップ生成データ取得（企業ショップニュースリスト）
     * 企業ショップニュースリスト情報を取得
     *
     * @access private
     * @return array
     * @see SiteMapService
     * @throws
     * @todo
     */
    private function getSiteMapCompanyShopNewsListData()
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        $sitemap_data = [];
        $last_update_date = '';

        // 企業ショップニュース情報取得
        $ret = $this->company_branch_news_repository->getCompanyBranchNewsList(null);
        $ret_finfo = $ret->first();
        if (! is_null($ret_finfo)) {
            // 日付変換
            $date_obj = new Carbon($ret_finfo->updated_at);
            $update_date = $date_obj->format($this->date_format);
            // 先頭の日付のみ別で確保
            $last_update_date = $update_date;

            // データ取得（企業毎のニュースリスト）
            $ret_info = $ret->unique('company_no'); // 処理前に企業番号の重複を除外
            foreach ($ret_info as $ret_data) {
                // 日付変換
                $date_obj = new Carbon($ret_data->updated_at);
                $update_date = $date_obj->format($this->date_format);

                $sitemap_data[] = [
                    'url' => route('company_shop_news.index', $ret_data->comp_no),
                    'lastmod' => $update_date,
                    'changefreq' => 'monthly',
                    'priority' => '0.5'
                ];
            }

            // データ取得（ニュースデータ）
            foreach ($ret as $ret_data) {
                // 日付変換
                $date_obj = new Carbon($ret_data->updated_at);
                $update_date = $date_obj->format($this->date_format);

                $sitemap_data[] = [
                    'url' => route('company_shop_news.show', $ret_data->comp_no) . "?b={$ret_data->comp_branch_no}&bn={$ret_data->comp_branch_news_no}",
                    'lastmod' => $update_date,
                    'changefreq' => 'monthly',
                    'priority' => '0.5'
                ];
            }
        }

        // 戻り値作成
        $ret_value = [
            'sitemap' => $sitemap_data,
            'last_update_date' => $last_update_date
        ];

        dslog($this->class_name, __FUNCTION__, 'm2', $ret_value);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret_value;
    }

    /**
     * サイトマップ生成データ取得（イベントリスト）
     * イベントリスト情報を取得
     *
     * @access private
     * @return array
     * @see SiteMapService
     * @throws
     * @todo
     */
    private function getSiteMapEventListData()
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        $sitemap_data = [];
        $last_update_date = '';

        // イベント情報取得
        $ret = $this->event_repository->getEventList();
        $ret_finfo = $ret->first();
        if (! is_null($ret_finfo)) {
            // データ取得先頭のみ
            // 日付変換
            $date_obj = new Carbon($ret_finfo->updated_at);
            $update_date = $date_obj->format($this->date_format);

            $sitemap_data[] = [
                'url' => route('front_event.index'),
                'lastmod' => $update_date,
                'changefreq' => 'monthly',
                'priority' => '0.7'
            ];
            
            // 先頭の日付のみ別で確保
            $last_update_date = $update_date;

            // データ取得
            foreach ($ret as $ret_data) {
                // 日付変換
                $date_obj = new Carbon($ret_data->updated_at);
                $update_date = $date_obj->format($this->date_format);

                $sitemap_data[] = [
                    'url' => route('front_event.show', $ret_data->event_mgr_no),
                    'lastmod' => $update_date,
                    'changefreq' => 'monthly',
                    'priority' => '0.7'
                ];
            }
        }

        // 戻り値作成
        $ret_value = [
            'sitemap' => $sitemap_data,
            'last_update_date' => $last_update_date
        ];

        dslog($this->class_name, __FUNCTION__, 'm2', $ret_value);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret_value;
    }

    /**
     * サイトマップ生成データ取得（コンテンツリスト）
     * コンテンツリスト情報を取得
     *
     * @access private
     * @return array
     * @see SiteMapService
     * @throws
     * @todo
     */
    private function getSiteMapContentListData()
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        $sitemap_data = [];
        $last_update_date = '';

        // コンテンツ情報取得
        $ret = $this->content_repository->getContentList(null);
        $ret_finfo = $ret->first();
        if (! is_null($ret_finfo)) {
            // 日付変換
            $date_obj = new Carbon($ret_finfo->updated_at);
            $update_date = $date_obj->format($this->date_format);

            // 先頭の日付のみ別で確保
            $last_update_date = $update_date;

            // データ取得（コンテンツリスト）
            $ret_info = $ret->unique('contents_type'); // 処理前にコンテンツ種別の重複を除外
            foreach ($ret_info as $ret_data) {
                // 日付変換
                $date_obj = new Carbon($ret_data->updated_at);
                $update_date = $date_obj->format($this->date_format);

                if (($ret_data->contents_type === 0) || ($ret_data->contents_type === 1) || ($ret_data->contents_type === 2)) {
                    // 更新頻度が高い
                    $changefreq = 'weekly';
                    $priority = '1.0';
                } else {
                    // 更新頻度が低い
                    $changefreq = 'monthly';
                    $priority = '0.5';
                }

                $sitemap_data[] = [
                    'url' => route('front_content.index', $ret_data->contents_type),
                    'lastmod' => $update_date,
                    'changefreq' => $changefreq,
                    'priority' => $priority
                ];
            }

            // データ取得（コンテンツデータ）
            foreach ($ret as $ret_data) {
                // 日付変換
                $date_obj = new Carbon($ret_data->updated_at);
                $update_date = $date_obj->format($this->date_format);

                if (($ret_data->contents_type === 0) || ($ret_data->contents_type === 1) || ($ret_data->contents_type === 2)) {
                    // 更新頻度が高い
                    $changefreq = 'weekly';
                    $priority = '1.0';
                } else {
                    // 更新頻度が低い
                    $changefreq = 'monthly';
                    $priority = '0.5';
                }

                $sitemap_data[] = [
                    'url' => route('front_content.show', $ret_data->contents_type) . "?n={$ret_data->contents_mgr_no}",
                    'lastmod' => $update_date,
                    'changefreq' => $changefreq,
                    'priority' => $priority
                ];
            }
        }

        // 戻り値作成
        $ret_value = [
            'sitemap' => $sitemap_data,
            'last_update_date' => $last_update_date
        ];

        dslog($this->class_name, __FUNCTION__, 'm2', $ret_value);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret_value;
    }
}
