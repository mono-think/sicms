<?php
// フロントリクエスト
use App\Http\Requests\Manage\ImageListRequest;

// 管理サービス
use App\Services\Manage\ImageService;
// 管理リポジトリ
use App\Repositories\Manage\AcntRepositoryInterface;
use App\Repositories\Manage\CompanyBranchRepositoryInterface;
use App\Repositories\Manage\CompanyDepartmentRepositoryInterface;

use App\Models\Acnt;
use App\Models\Company;
use App\Models\CompanyBranch;
use App\Models\CompanyDepartment;
use App\Models\ProductsCategory;
use App\Models\Products;
use App\Models\ProductsDetail;
use App\Models\ProductsReview;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

/*
 * |--------------------------------------------------------------------------
 * | API Routes
 * |--------------------------------------------------------------------------
 * |
 * | Here is where you can register API routes for your application. These
 * | routes are loaded by the RouteServiceProvider within a group which
 * | is assigned the "api" middleware group. Enjoy building your API!
 * |
 */
/*
 * Route::middleware('auth:api')->get('/user', function (Request $request) {
 * return $request->user();
 * });
 */

 /*
 * 管理系API
 */
// ログアウト
Route::get('manage/logout', function (Request $request) {
    $user = Auth::user(); // ログイン者情報取得
    $user->tokens()->delete(); // トークン削除
    Auth::guard('web')->logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect(route('login'));
})->name('logout.index');

// アカウント系画面
Route::get('manage/acnt/checkno', function (Request $request) {
    // ログイン-選択中情報取得
    $login_info = getLoginInfo($request);
    $company_no = $login_info['company_no']; // 企業番号
    $acnt_no = $request->input('n'); // アカウント番号

    $ret = Acnt::where('comp_no', $company_no)->whereRaw('upper(acnt_no) = ?', strtoupper($acnt_no))->first();
    if (empty($ret)) {
        return json_encode(false);
    } else {
        return json_encode(true);
    }
})->name('acnt.checkno');
Route::get('manage/acnt/checkid', function (Request $request) {
    $acnt_id = $request->input('i'); // アカウントID
    $ret = Acnt::whereRaw('upper(acnt_id) = ?', strtoupper($acnt_id))->first();
    if (empty($ret)) {
        return json_encode(false);
    } else {
        return json_encode(true);
    }
})->name('acnt.checkid');

Route::get('manage/acnt/getlist', function (Request $request, AcntRepositoryInterface $acnt_repository) {
    // ログイン-選択中情報取得
    $login_info = getLoginInfo($request);
    $company_no = $login_info['company_no']; // 企業番号
    $company_branch_no = $request->input('bno'); // 支店番号
    $company_department_no = $request->input('dno'); // 部署番号
    $free_word = $request->input('input_free_word');
    $free_word = hscQuotes($free_word); // HTMLエンティティ変換

    $ret_collection = $acnt_repository->getAcntList($company_no, $company_branch_no, $company_department_no, $free_word, 0, 5);
    $ret = $ret_collection->toJson(); // Json化

    $seq_state = 200;
    if (empty($ret)) {
        // 値が空
        $seq_state = 500;
    }
    return response($ret, $seq_state);
})->name('acnt.getlist');

// 企業系画面
Route::get('manage/company/checkno', function (Request $request) {
    $company_no = $request->input('n'); // 企業番号
    $ret = Company::whereRaw('upper(comp_no) = ?', strtoupper($company_no))->first();
    if (empty($ret)) {
        return json_encode(false);
    } else {
        return json_encode(true);
    }
})->name('company.checkno');

// 企業支店系画面
Route::get('manage/company_branch/checkno', function (Request $request) {
    // ログイン-選択中情報取得
    $login_info = getLoginInfo($request);
    $company_no = $login_info['company_no']; // 企業番号
    $company_branch_no = $request->input('n'); // 企業支店番号
    $ret = CompanyBranch::where('comp_no', $company_no)->whereRaw('upper(comp_branch_no) = ?', strtoupper($company_branch_no))->first();
    if (empty($ret)) {
        return json_encode(false);
    } else {
        return json_encode(true);
    }
})->name('company_branch.checkno');
Route::get('manage/company_branch/getlist', function (Request $request, CompanyBranchRepositoryInterface $company_branch_repository) {
    // ログイン-選択中情報取得
    $login_info = getLoginInfo($request);
    $company_no = $login_info['company_no']; // 企業番号
    $start = $request->input('start');
    $free_word = $request->input('input_free_word');
    $free_word = hscQuotes($free_word); // HTMLエンティティ変換

    $ret_collection = $company_branch_repository->getCompanyBranchList($company_no, $free_word, $start, 5);
    $ret = $ret_collection->toJson(); // Json化

    $seq_state = 200;
    if (empty($ret)) {
        // 値が空
        $seq_state = 500;
    }
    return response($ret, $seq_state);
})->name('company_branch.getlist');

// 企業部署系画面
Route::get('manage/company_department/checkno', function (Request $request) {
    // ログイン-選択中情報取得
    $login_info = getLoginInfo($request);
    $company_no = $login_info['company_no']; // 企業番号
    $company_branch_no = $login_info['company_branch_no']; // 企業支店番号
    $company_department_no = $request->input('n'); // 企業部署番号
    $ret = CompanyDepartment::where('comp_no', $company_no)->where('comp_branch_no', $company_branch_no)
        ->whereRaw('upper(comp_department_no) = ?', strtoupper($company_department_no))
        ->first();
    if (empty($ret)) {
        return json_encode(false);
    } else {
        return json_encode(true);
    }
})->name('company_department.checkno');
Route::get('manage/company_department/getlist', function (Request $request, CompanyDepartmentRepositoryInterface $company_department_repository) {
    // ログイン-選択中情報取得
    $login_info = getLoginInfo($request);
    $company_no = $login_info['company_no']; // 企業番号
    $company_branch_no = $request->input('n'); // 支店番号
    $start = $request->input('start');
    $free_word = $request->input('input_free_word'); // 検索ワード
    $free_word = hscQuotes($free_word); // HTMLエンティティ変換

    $ret_collection = $company_department_repository->getCompanyDepartmentList($company_no, $company_branch_no, $free_word, $start, 5);
    $ret = $ret_collection->toJson(); // Json化

    $seq_state = 200;
    if (empty($ret)) {
        // 値が空
        $seq_state = 500;
    }
    return response($ret, $seq_state);
})->name('company_department.getlist');

// 画像系画面
Route::get('manage/image_select/{id}/getlist', function (ImageListRequest $request, $category_no, ImageService $image_service) {
    // ログイン-選択中情報取得
    $login_info = getLoginInfo($request);
    $company_no = $login_info['company_no']; // 企業番号
    $free_word = $request->input('input_free_word');
    $start = $request->input('start');
    dslog('test1', 'test1', 'm1',[$company_no, $free_word, $category_no, $start]);
    
    $ret = $image_service->getImageList($company_no, $category_no, $free_word, $start, 6);
    $seq_state = 200;
    if (empty($ret)) {
        // 値が空
        $seq_state = 500;
    }
    return response($ret, $seq_state);
})->name('image_select.getlist');

// 製品カテゴリマスタ画面
Route::get('manage/products_category/checkno', function (Request $request) {
    $products_category_no = $request->input('n'); // 製品カテゴリ番号
    $ret = ProductsCategory::whereRaw('upper(products_category_no) = ?', strtoupper($products_category_no))->first();
    if (empty($ret)) {
        return json_encode(false);
    } else {
        return json_encode(true);
    }
})->name('products_category.checkno');

// 製品系画面
Route::get('manage/products/checkno', function (Request $request) {
    // ログイン-選択中情報取得
    $login_info = getLoginInfo($request);
    $company_no = $login_info['company_no']; // 企業番号
    $products_no = $request->input('n'); // 製品番号
    $ret = Products::where('comp_no', $company_no)->whereRaw('upper(products_no) = ?', strtoupper($products_no))->first();
    if (empty($ret)) {
        return json_encode(false);
    } else {
        return json_encode(true);
    }
})->name('products.checkno');

// 製品詳細系画面
Route::get('manage/products_detail/checkno', function (Request $request) {
    // ログイン-選択中情報取得
    $login_info = getLoginInfo($request);
    $company_no = $login_info['company_no']; // 企業番号
    $products_no = $login_info['products_no']; // 製品番号
    $products_detail_no = $request->input('n'); // 製品詳細番号
    $ret = ProductsDetail::where('comp_no', $company_no)->where('products_no', $products_no)
        ->whereRaw('upper(products_detail_no) = ?', strtoupper($products_detail_no))
        ->first();
    if (empty($ret)) {
        return json_encode(false);
    } else {
        return json_encode(true);
    }
})->name('products_detail.checkno');

// 製品レビュー系画面
Route::get('manage/products_review/checkno', function (Request $request) {
    // ログイン-選択中情報取得
    $login_info = getLoginInfo($request);
    $company_no = $login_info['company_no']; // 企業番号
    $products_no = $login_info['products_no']; // 製品番号
    $products_detail_no = $login_info['products_detail_no']; // 製品詳細番号
    $products_review_no = $request->input('n'); // 製品レビュー番号
    $ret = ProductsReview::where('comp_no', $company_no)->where('products_no', $products_no)
        ->where('products_detail_no', $products_detail_no)
        ->whereRaw('upper(products_review_no) = ?', strtoupper($products_review_no))
        ->first();
    if (empty($ret)) {
        return json_encode(false);
    } else {
        return json_encode(true);
    }
})->name('products_review.checkno');