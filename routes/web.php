<?php
//フロント
use App\Http\Controllers\ProductsCategoryFrontController;
use App\Http\Controllers\ProductsFrontController;
use App\Http\Controllers\CompanyShopFrontController;
use App\Http\Controllers\CompanyShopNewsFrontController;
use App\Http\Controllers\EventFrontController;
use App\Http\Controllers\ContentFrontController;
use App\Http\Controllers\ContactFrontController;
// フロントリクエスト
use App\Http\Requests\ProductsDetailListRequest;
use App\Http\Requests\AddPvRequest;
use App\Http\Requests\ProductsReviewRequest;
// フロントサービス
use App\Services\ImageFrontService;
// フロントリポジトリ
use App\Repositories\PageViewRepositoryInterface;
use App\Repositories\NewUpdateRepositoryInterface;
use App\Repositories\ProductsRepositoryInterface;
use App\Repositories\ProductsDetailRepositoryInterface;
use App\Repositories\CompanyRepositoryInterface;
use App\Repositories\CompanyBranchNewsRepositoryInterface;
use App\Repositories\CompanyBranchFrontRepositoryInterface;
use App\Repositories\EventRepositoryInterface;
use App\Repositories\ContentRepositoryInterface;
// フロントモデル
use App\Models\ProductsReview;

//管理
use App\Http\Controllers\Manage\CompanyController;
use App\Http\Controllers\Manage\CompanyBranchController;
use App\Http\Controllers\Manage\CompanyBranchNewsController;
use App\Http\Controllers\Manage\ImageController;
use App\Http\Controllers\Manage\CompanyDepartmentController;
use App\Http\Controllers\Manage\AcntController;
use App\Http\Controllers\Manage\IndustryController;
use App\Http\Controllers\Manage\BformatController;
use App\Http\Controllers\Manage\PositionController;
use App\Http\Controllers\Manage\ContentController;
use App\Http\Controllers\Manage\EventController;
use App\Http\Controllers\Manage\ProductsCategoryController;
use App\Http\Controllers\Manage\ProductsDetailController;
use App\Http\Controllers\Manage\ProductsController;
use App\Http\Controllers\Manage\ProductsReviewController;
use App\Http\Controllers\Manage\MessageBordController;
use App\Http\Controllers\Manage\PasswordController;
use App\Http\Controllers\Manage\TwoStepVerificationController;
use App\Http\Controllers\Manage\LoginController;
use App\Http\Controllers\Manage\PrefController;
use App\Http\Controllers\XmlSitemapFrontController;
use App\Http\Controllers\RssFeedFrontController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Carbon\Carbon;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*
 * フロント系
 */
//pixyJSテスト
Route::middleware('web')->group(function () {
    Route::view('test', 'test.view')->name('test.index');

    //サイト全般
    Route::get('sitemap.xml', [XmlSitemapFrontController::class,'index'])->name('common.sitemap');
    Route::get('sitemap.rss', [RssFeedFrontController::class,'index'])->name('common.rssfeed');
    
    //TOP系画面
    Route::view('', 'index',['form_data' => []])->name('index');
    
    //製品カテゴリ系画面
    Route::get('products_category', [ProductsCategoryFrontController::class,'index'])->name('front_products_category.index');
    
    //製品系画面
    Route::get('products/{id}', [ProductsFrontController::class,'index'])->name('front_products.index');
    Route::get('products/{id}/detail', [ProductsFrontController::class,'show'])->name('front_products.show');
    
    //企業系画面
    Route::get('company_shop', [CompanyShopFrontController::class,'index'])->name('company_shop.index');
    Route::get('company_shop/{id}/detail', [CompanyShopFrontController::class,'show'])->name('company_shop.show');
    
    //企業ニュース系画面
    Route::get('company_shop_news/{id}', [CompanyShopNewsFrontController::class,'index'])->name('company_shop_news.index');
    Route::get('company_shop_news/{id}/detail', [CompanyShopNewsFrontController::class,'show'])->name('company_shop_news.show');
    
    //イベント系画面
    Route::get('event', [EventFrontController::class,'index'])->name('front_event.index');
    Route::get('event/{id}/detail', [EventFrontController::class,'show'])->name('front_event.show');
    
    //コンテンツ系画面
    Route::get('content/{id}', [ContentFrontController::class,'index'])->name('front_content.index');
    Route::get('content/{id}/detail', [ContentFrontController::class,'show'])->name('front_content.show');
    
    //問い合わせ系画面
    Route::get('contact/create', [ContactFrontController::class,'create'])->name('front_contact');
    Route::post('contact/confirm', [ContactFrontController::class,'confirm'])->name('front_contact.confirm');
    Route::post('contact', [ContactFrontController::class,'store'])->name('front_contact.store');

    /*
     * フロント系API
     */
    // フロント共通
    Route::get('getsupporter', function (Request $request, CompanyBranchFrontRepositoryInterface $company_branch_repository) {
        $ret_collection = $company_branch_repository->getCompanySupporterBranchList(1);
        $ret = $ret_collection->toJson(); // Json化
        
        if (empty($ret)) {
            // 値が空
            $seq_state = 500;
        } else {
            $seq_state = 200;
        }
        return response($ret, $seq_state);
    })->name('common.getsupporter'); // サポーター情報取得
    
    Route::get('addpv', function (AddPvRequest $request, PageViewRepositoryInterface $pageview_repository) {
        $url = $request->input('u'); // URL
        $title = $request->input('t'); // ページタイトル
        $ipaddress = $request->ip();
        $upsert_data['pageview_url'] = $url;
        $update_data = [
            'title' => Str::limit($title, 255, '...'),
            'pageview_ipaddress' => $ipaddress,
            'updated_at' => Carbon::now()
        ];
        
        $ret = $pageview_repository->upsertPageViewData($upsert_data, $update_data);
        if (empty($ret)) {
            // 値が空
            $seq_state = 500;
            $ret_value = false;
        } else {
            $seq_state = 200;
            $ret_value = true;
        }
        return response($ret_value, $seq_state);
    })->name('common.addpv'); // PV更新
    
    Route::get('getpv', function (Request $request, PageViewRepositoryInterface $pageview_repository) {
        $ret_collection = $pageview_repository->getPageViewList(0, 5);
        $ret = $ret_collection->toJson(); // Json化
        
        $seq_state = 200;
        if (empty($ret)) {
            // 値が空
            $seq_state = 500;
        }
        return response($ret, $seq_state);
    })->name('common.getpv'); // PV取得
    
    Route::get('getcaptcha', function (Request $request, ImageFrontService $image_service) {
        // Captcha情報生成
        $ret_param = $image_service->createCaptchaImage(4);
        return response(json_encode(getCaptchaPath()), 200)->cookie('CpNum', $ret_param, time() + 3600); // 有効期限1時間
    })->name('common.getcaptcha'); // Captcha画像出力
    
    // Top画面
    Route::get('getupdate', function (Request $request, NewUpdateRepositoryInterface $newupdate_repository) {
        $ret_collection = $newupdate_repository->getNewUpdateList(4); // 最新4件取得
        $ret = $ret_collection->toJson(); // Json化
        
        $seq_state = 200;
        if (empty($ret)) {
            // 値が空
            $seq_state = 500;
        }
        return response($ret, $seq_state);
    })->name('index.getupdate'); // 全新着情報取得
    
    Route::get('getupdate/products', function (Request $request, ProductsRepositoryInterface $products_repository) {
        $ret_collection = $products_repository->getProductsNewUpdateList(4); // 最新4件取得
        $ret = $ret_collection->toJson(); // Json化
        
        $seq_state = 200;
        if (empty($ret)) {
            // 値が空
            $seq_state = 500;
        }
        return response($ret, $seq_state);
    })->name('index.getupdate_products'); // 製品新着情報取得
    
    Route::get('getupdate/company', function (Request $request, CompanyRepositoryInterface $company_repository) {
        $ret_collection = $company_repository->getCompanyNewUpdateList(4); // 最新4件取得
        $ret = $ret_collection->toJson(); // Json化
        
        $seq_state = 200;
        if (empty($ret)) {
            // 値が空
            $seq_state = 500;
        }
        return response($ret, $seq_state);
    })->name('index.getupdate_company'); // 企業新着情報取得
    
    Route::get('getupdate/event', function (Request $request, EventRepositoryInterface $event_repository) {
        $ret_collection = $event_repository->getEventNewUpdateList(4); // 最新4件取得
        $ret = $ret_collection->toJson(); // Json化
        
        $seq_state = 200;
        if (empty($ret)) {
            // 値が空
            $seq_state = 500;
        }
        return response($ret, $seq_state);
    })->name('index.getupdate_event'); // イベント新着情報取得
    
    Route::get('getupdate/content/{id}', function (Request $request, $contents_type, ContentRepositoryInterface $content_repository) {
        $ret_collection = $content_repository->getContentNewUpdateList($contents_type, 4);
        $ret = $ret_collection->toJson(); // Json化
        
        $seq_state = 200;
        if (empty($ret)) {
            // 値が空
            $seq_state = 500;
        }
        return response($ret, $seq_state);
    })->name('index.getupdate_content'); // 非同期
    
    // 製品系画面
    Route::get('products/{id}/getdetail', function (ProductsDetailListRequest $request, $products_no, ProductsDetailRepositoryInterface $products_detail_repository) {
        $company_no = $request->input('c'); // 企業番号
        $ret_collection = $products_detail_repository->getProductsDetailList($company_no, $products_no);
        $ret = $ret_collection->toJson(); // Json化
        
        $seq_state = 200;
        if (empty($ret)) {
            // 値が空
            $seq_state = 500;
        }
        return response($ret, $seq_state);
    })->name('front_products.getdetail'); // 製品詳細リスト
    
    Route::get('products/{id}/getreview', function (ProductsReviewRequest $request, $products_detail_no) {
        $products_no = $request->input('n'); // 製品番号
        $company_no = $request->input('c'); // 企業番号
        $start = $request->input('start');
        
        $ret = ProductsReview::when(! is_null($company_no), function ($query) use ($company_no) {
            return $query->where('comp_no', $company_no);
        })->where('products_no', $products_no)
        ->when(! is_null($products_detail_no), function ($query) use ($products_detail_no) {
            return $query->where('products_detail_no', $products_detail_no);
        })
        ->offset($start)
        ->limit(5)
        ->get()
        ->toJson();
        return $ret;
    })->name('front_products.getreview'); // 製品レビュー
    
    // 企業系画面
    Route::get('company_shop/{id}/branch', function (Request $request, $company_no, CompanyBranchFrontRepositoryInterface $company_branch_repository) {
        $ret_collection = $company_branch_repository->getCompanyBranchList($company_no, 0, 5);
        $ret = $ret_collection->toJson(); // Json化
        
        $seq_state = 200;
        if (empty($ret)) {
            // 値が空
            $seq_state = 500;
        }
        return response($ret, $seq_state);
    })->name('company_shop.branch'); // 企業支店リスト
    
    Route::get('company_shop/{id}/news', function (Request $request, $company_no, CompanyBranchNewsRepositoryInterface $company_branch_news_repository) {
        $ret_collection = $company_branch_news_repository->getCompanyBranchNewsList($company_no, 0, 5);
        $ret = $ret_collection->toJson(); // Json化
        
        $seq_state = 200;
        if (empty($ret)) {
            // 値が空
            $seq_state = 500;
        }
        return response($ret, $seq_state);
    })->name('company_shop.news'); // 企業支店ニュースリスト
});

/*
 * 管理系
 */
Route::middleware('guest')->group(function () {
    //ログイン画面
    Route::view('manage/login', 'manage.login')->name('login');
    Route::post('manage/login', [LoginController::class,'authenticate'])->name('login.auth');
    
    //パスワード画面
    Route::view('manage/password', 'manage.password_forget_mail')->name('password.index');
    Route::post('manage/password', [PasswordController::class,'sendmail'])->name('password.sendmail');
    Route::get('manage/password_reset', [PasswordController::class,'reset'])->name('password.reset');
    Route::put('manage/password', [PasswordController::class,'update'])->name('password.update');
});

//二段階認証画面
Route::view('manage/twostep_verify', 'manage.twostep_verify')->name('twoverify.index');
Route::post('manage/twostep_verify', [TwoStepVerificationController::class,'verification'])->name('twoverify.verify');
Route::get('manage/twostep_verify/send', [TwoStepVerificationController::class,'send'])->name('twoverify.send');
    
//認証後画面
Route::middleware('sicms-auth')->group(function () {
    //アカウント系画面
    Route::get('manage/acnt', [AcntController::class,'index'])->name('acnt.index');
    Route::get('manage/acnt/create', [AcntController::class,'create'])->name('acnt.create');
    Route::post('manage/acnt/confirm', [AcntController::class,'confirm'])->name('acnt.confirm');
    Route::post('manage/acnt', [AcntController::class,'store'])->name('acnt.store');
    Route::get('manage/acnt/{id}', [AcntController::class,'show'])->name('acnt.show');
    Route::get('manage/acnt/{id}/edit', [AcntController::class,'edit'])->name('acnt.edit');
    Route::put('manage/acnt/{id}', [AcntController::class,'update'])->name('acnt.update');
    Route::delete('manage/acnt', [AcntController::class,'destroy'])->name('acnt.destroy');

    //企業系画面
    Route::get('manage/company', [CompanyController::class,'index'])->name('company.index');
    Route::get('manage/company/create', [CompanyController::class,'create'])->name('company.create');
    Route::post('manage/company/confirm', [CompanyController::class,'confirm'])->name('company.confirm');
    Route::post('manage/company', [CompanyController::class,'store'])->name('company.store');
    Route::get('manage/company/{id}', [CompanyController::class,'show'])->name('company.show');
    Route::get('manage/company/{id}/edit', [CompanyController::class,'edit'])->name('company.edit');
    Route::put('manage/company/{id}', [CompanyController::class,'update'])->name('company.update');
    Route::delete('manage/company', [CompanyController::class,'destroy'])->name('company.destroy');

    //企業支店系画面
    Route::get('manage/company_branch', [CompanyBranchController::class,'index'])->name('company_branch.index');
    Route::get('manage/company_branch/create', [CompanyBranchController::class,'create'])->name('company_branch.create');
    Route::post('manage/company_branch/confirm', [CompanyBranchController::class,'confirm'])->name('company_branch.confirm');
    Route::post('manage/company_branch', [CompanyBranchController::class,'store'])->name('company_branch.store');
    Route::get('manage/company_branch/{id}', [CompanyBranchController::class,'show'])->name('company_branch.show');
    Route::get('manage/company_branch/{id}/edit', [CompanyBranchController::class,'edit'])->name('company_branch.edit');
    Route::put('manage/company_branch/{id}', [CompanyBranchController::class,'update'])->name('company_branch.update');
    Route::delete('manage/company_branch', [CompanyBranchController::class,'destroy'])->name('company_branch.destroy');

    //企業支店ニュース系画面
    Route::get('manage/company_branch_news', [CompanyBranchNewsController::class,'index'])->name('company_branch_news.index');
    Route::get('manage/company_branch_news/create', [CompanyBranchNewsController::class,'create'])->name('company_branch_news.create');
    Route::post('manage/company_branch_news/confirm', [CompanyBranchNewsController::class,'confirm'])->name('company_branch_news.confirm');
    Route::post('manage/company_branch_news', [CompanyBranchNewsController::class,'store'])->name('company_branch_news.store');
    Route::get('manage/company_branch_news/{id}', [CompanyBranchNewsController::class,'show'])->name('company_branch_news.show');
    Route::get('manage/company_branch_news/{id}/edit', [CompanyBranchNewsController::class,'edit'])->name('company_branch_news.edit');
    Route::put('manage/company_branch_news/{id}', [CompanyBranchNewsController::class,'update'])->name('company_branch_news.update');
    Route::delete('manage/company_branch_news', [CompanyBranchNewsController::class,'destroy'])->name('company_branch_news.destroy');

    //企業部署系画面
    Route::get('manage/company_department', [CompanyDepartmentController::class,'index'])->name('company_department.index');
    Route::get('manage/company_department/create', [CompanyDepartmentController::class,'create'])->name('company_department.create');
    Route::post('manage/company_department/confirm', [CompanyDepartmentController::class,'confirm'])->name('company_department.confirm');
    Route::post('manage/company_department', [CompanyDepartmentController::class,'store'])->name('company_department.store');
    Route::get('manage/company_department/{id}', [CompanyDepartmentController::class,'show'])->name('company_department.show');
    Route::get('manage/company_department/{id}/edit', [CompanyDepartmentController::class,'edit'])->name('company_department.edit');
    Route::put('manage/company_department/{id}', [CompanyDepartmentController::class,'update'])->name('company_department.update');
    Route::delete('manage/company_department', [CompanyDepartmentController::class,'destroy'])->name('company_department.destroy');

    //画像系画面
    Route::get('manage/image', [ImageController::class,'index'])->name('image.index');
    Route::post('manage/image', [ImageController::class,'store'])->name('image.store');
    Route::put('manage/image/{id}', [ImageController::class,'update'])->name('image.update');
    Route::delete('manage/image', [ImageController::class,'destroy'])->name('image.destroy');

    //業種マスタ画面
    Route::get('manage/industry', [IndustryController::class,'index'])->name('industry.index');
    Route::get('manage/industry/create', [IndustryController::class,'create'])->name('industry.create');
    Route::post('manage/industry/confirm', [IndustryController::class,'confirm'])->name('industry.confirm');
    Route::post('manage/industry', [IndustryController::class,'store'])->name('industry.store');
    Route::get('manage/industry/{id}', [IndustryController::class,'show'])->name('industry.show');
    Route::get('manage/industry/{id}/edit', [IndustryController::class,'edit'])->name('industry.edit');
    Route::put('manage/industry/{id}', [IndustryController::class,'update'])->name('industry.update');
    Route::delete('manage/industry', [IndustryController::class,'destroy'])->name('industry.destroy');

    //業態マスタ画面
    Route::get('manage/bformat', [BformatController::class,'index'])->name('bformat.index');
    Route::get('manage/bformat/create', [BformatController::class,'create'])->name('bformat.create');
    Route::post('manage/bformat/confirm', [BformatController::class,'confirm'])->name('bformat.confirm');
    Route::post('manage/bformat', [BformatController::class,'store'])->name('bformat.store');
    Route::get('manage/bformat/{id}', [BformatController::class,'show'])->name('bformat.show');
    Route::get('manage/bformat/{id}/edit', [BformatController::class,'edit'])->name('bformat.edit');
    Route::put('manage/bformat/{id}', [BformatController::class,'update'])->name('bformat.update');
    Route::delete('manage/bformat', [BformatController::class,'destroy'])->name('bformat.destroy');

    //役職マスタ画面
    Route::get('manage/position', [PositionController::class,'index'])->name('position.index');
    Route::get('manage/position/create', [PositionController::class,'create'])->name('position.create');
    Route::post('manage/position/confirm', [PositionController::class,'confirm'])->name('position.confirm');
    Route::post('manage/position', [PositionController::class,'store'])->name('position.store');
    Route::get('manage/position/{id}', [PositionController::class,'show'])->name('position.show');
    Route::get('manage/position/{id}/edit', [PositionController::class,'edit'])->name('position.edit');
    Route::put('manage/position/{id}', [PositionController::class,'update'])->name('position.update');
    Route::delete('manage/position', [PositionController::class,'destroy'])->name('position.destroy');

    //都道府県マスタ画面
    Route::get('manage/pref', [PrefController::class,'index'])->name('pref.index');
    Route::get('manage/pref/create', [PrefController::class,'create'])->name('pref.create');
    Route::post('manage/pref/confirm', [PrefController::class,'confirm'])->name('pref.confirm');
    Route::post('manage/pref', [PrefController::class,'store'])->name('pref.store');
    Route::get('manage/pref/{id}', [PrefController::class,'show'])->name('pref.show');
    Route::get('manage/pref/{id}/edit', [PrefController::class,'edit'])->name('pref.edit');
    Route::put('manage/pref/{id}', [PrefController::class,'update'])->name('pref.update');
    Route::delete('manage/pref', [PrefController::class,'destroy'])->name('pref.destroy');

    //製品カテゴリマスタ画面
    Route::get('manage/products_category', [ProductsCategoryController::class,'index'])->name('products_category.index');
    Route::get('manage/products_category/create', [ProductsCategoryController::class,'create'])->name('products_category.create');
    Route::post('manage/products_category/confirm', [ProductsCategoryController::class,'confirm'])->name('products_category.confirm');
    Route::post('manage/products_category', [ProductsCategoryController::class,'store'])->name('products_category.store');
    Route::get('manage/products_category/{id}', [ProductsCategoryController::class,'show'])->name('products_category.show');
    Route::get('manage/products_category/{id}/edit', [ProductsCategoryController::class,'edit'])->name('products_category.edit');
    Route::put('manage/products_category/{id}', [ProductsCategoryController::class,'update'])->name('products_category.update');
    Route::delete('manage/products_category', [ProductsCategoryController::class,'destroy'])->name('products_category.destroy');

    //製品系画面
    Route::get('manage/products', [ProductsController::class,'index'])->name('products.index');
    Route::get('manage/products/create', [ProductsController::class,'create'])->name('products.create');
    Route::post('manage/products/confirm', [ProductsController::class,'confirm'])->name('products.confirm');
    Route::post('manage/products', [ProductsController::class,'store'])->name('products.store');
    Route::get('manage/products/{id}', [ProductsController::class,'show'])->name('products.show');
    Route::get('manage/products/{id}/edit', [ProductsController::class,'edit'])->name('products.edit');
    Route::put('manage/products/{id}', [ProductsController::class,'update'])->name('products.update');
    Route::delete('manage/products', [ProductsController::class,'destroy'])->name('products.destroy');

    //製品詳細系画面
    Route::get('manage/products_detail', [ProductsDetailController::class,'index'])->name('products_detail.index');
    Route::get('manage/products_detail/create', [ProductsDetailController::class,'create'])->name('products_detail.create');
    Route::post('manage/products_detail/confirm', [ProductsDetailController::class,'confirm'])->name('products_detail.confirm');
    Route::post('manage/products_detail', [ProductsDetailController::class,'store'])->name('products_detail.store');
    Route::get('manage/products_detail/{id}', [ProductsDetailController::class,'show'])->name('products_detail.show');
    Route::get('manage/products_detail/{id}/edit', [ProductsDetailController::class,'edit'])->name('products_detail.edit');
    Route::put('manage/products_detail/{id}', [ProductsDetailController::class,'update'])->name('products_detail.update');
    Route::delete('manage/products_detail', [ProductsDetailController::class,'destroy'])->name('products_detail.destroy');

    //製品レビュー系画面
    Route::get('manage/products_review', [ProductsReviewController::class,'index'])->name('products_review.index');
    Route::get('manage/products_review/create', [ProductsReviewController::class,'create'])->name('products_review.create');
    Route::post('manage/products_review/confirm', [ProductsReviewController::class,'confirm'])->name('products_review.confirm');
    Route::post('manage/products_review', [ProductsReviewController::class,'store'])->name('products_review.store');
    Route::get('manage/products_review/{id}', [ProductsReviewController::class,'show'])->name('products_review.show');
    Route::get('manage/products_review/{id}/edit', [ProductsReviewController::class,'edit'])->name('products_review.edit');
    Route::put('manage/products_review/{id}', [ProductsReviewController::class,'update'])->name('products_review.update');
    Route::delete('manage/products_review', [ProductsReviewController::class,'destroy'])->name('products_review.destroy');

    //イベント系画面
    Route::get('manage/event', [EventController::class,'index'])->name('event.index');
    Route::get('manage/event/create', [EventController::class,'create'])->name('event.create');
    Route::post('manage/event/confirm', [EventController::class,'confirm'])->name('event.confirm');
    Route::post('manage/event', [EventController::class,'store'])->name('event.store');
    Route::get('manage/event/{id}', [EventController::class,'show'])->name('event.show');
    Route::get('manage/event/{id}/edit', [EventController::class,'edit'])->name('event.edit');
    Route::put('manage/event/{id}', [EventController::class,'update'])->name('event.update');
    Route::delete('manage/event', [EventController::class,'destroy'])->name('event.destroy');

    //コンテンツ系画面
    Route::get('manage/content', [ContentController::class,'index'])->name('content.index');
    Route::get('manage/content/create', [ContentController::class,'create'])->name('content.create');
    Route::post('manage/content/confirm', [ContentController::class,'confirm'])->name('content.confirm');
    Route::post('manage/content', [ContentController::class,'store'])->name('content.store');
    Route::get('manage/content/{id}', [ContentController::class,'show'])->name('content.show');
    Route::get('manage/content/{id}/edit', [ContentController::class,'edit'])->name('content.edit');
    Route::put('manage/content/{id}', [ContentController::class,'update'])->name('content.update');
    Route::delete('manage/content', [ContentController::class,'destroy'])->name('content.destroy');

    //メッセージボード系画面
    Route::get('manage/message_bord', [MessageBordController::class,'index'])->name('message_bord.index');
    Route::get('manage/message_bord/{id}/create', [MessageBordController::class,'create'])->name('message_bord.create');
    Route::post('manage/message_bord/confirm', [MessageBordController::class,'confirm'])->name('message_bord.confirm');
    Route::post('manage/message_bord', [MessageBordController::class,'store'])->name('message_bord.store');
    Route::get('manage/message_bord/{id}/edit', [MessageBordController::class,'edit'])->name('message_bord.edit');
    Route::put('manage/message_bord/{id}', [MessageBordController::class,'update'])->name('message_bord.update');
    Route::delete('manage/message_bord', [MessageBordController::class,'destroy'])->name('message_bord.destroy');
});