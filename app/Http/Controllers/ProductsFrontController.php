<?php
/**
 * [フロント]製品サービス系画面のControllerクラス
 *
 * 製品サービス系画面の表示、検索処理
 *
 * @access public
 * @author Seiji Ishikawa（メールアドレス）
 * @copyright Webじゃ軒 All Rights Reserved
 * @package controller
 */
namespace App\Http\Controllers;

use App\Services\ProductsService;
use Illuminate\Http\Request;
use Exception;
use PDOException;

class ProductsFrontController extends Controller
{

    protected $products_service;

    protected $class_name;

    /**
     * コンストラクタ
     *
     * @param
     */
    public function __construct(ProductsService $products_service)
    {
        $this->products_service = $products_service;
        $this->class_name = get_class($this);
        islog($this->class_name, __FUNCTION__);
    }

    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @param string $products_category_no
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $products_category_no)
    {
        try {
            islog($this->class_name, __FUNCTION__, 'm1');

            // フォーム値を取得
            islog($this->class_name, __FUNCTION__, 'm21');
            $company_no = $request->input('c');
            $search_tag = $request->input('tag');
            dslog($this->class_name, __FUNCTION__, 'm3', [
                'products_category_no' => $products_category_no, // 製品カテゴリ番号
                'company_no' => $company_no, // 企業番号
                'search_tag' => $search_tag // 検索タグ
            ]);

            // 情報一覧を取得
            islog($this->class_name, __FUNCTION__, 'm10');
            $ret = $this->products_service->getProductsAndDetailNewPagenationList($products_category_no, $company_no, $search_tag);
            if (is_null($ret['view']->first())) {
                // 値が空だった場合はページが存在しない
                dslog($this->class_name, __FUNCTION__, 'm4', null);
            }

            // パンくずリストパターン
            islog($this->class_name, __FUNCTION__, 'm11');
            $breadcrumbs_pattern = is_null($company_no) ? 'products' : 'company_shop_products';

            // ビューパラメータ作成
            $view_param = [
                'list_form_data' => $ret['view'],
                'form_data' => $ret['sub_data'],
                'company_no' => $company_no,
                'breadcrumbs_view' => $breadcrumbs_pattern
            ];

            // ビューへレンダリング
            dslog($this->class_name, __FUNCTION__, 'm4', $view_param);
            islog($this->class_name, __FUNCTION__, 'm24');
            return view('products_list', $view_param);
        } catch (Exception $e) {
            // 例外処理
            eslog($this->class_name, __FUNCTION__, 'm5', $e->getMessage());
            abort(500, dispExceptionMessage(500));
        }
    }

    /**
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //
    }

    /**
     * Confirm a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function confirm(Request $request)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param \Illuminate\Http\Request $request
     * @parama string $products_category_no
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $products_category_no)
    {
        try {
            islog($this->class_name, __FUNCTION__, 'm1');

            // フォーム値を取得
            islog($this->class_name, __FUNCTION__, 'm21');
            $products_no = $request->input('n'); // 製品番号
            $products_detail_no = $request->input('d'); // 製品詳細番号
            $company_no = $request->input('c'); // 企業番号
            dslog($this->class_name, __FUNCTION__, 'm3', [
                'products_category_no' => $products_category_no, // 製品カテゴリ
                'products_no' => $products_no, // 製品番号
                'products_detail_no' => $products_detail_no, // 製品詳細番号
                'company_no' => $company_no // 企業番号
            ]);

            // 製品詳細情報を取得
            islog($this->class_name, __FUNCTION__, 'm10');
            $ret = $this->products_service->getProductsDetailData($products_category_no, $products_no, $products_detail_no, $company_no);
            if (is_null($ret['view'])) {
                // 値が空だった場合はページが存在しない
                dslog($this->class_name, __FUNCTION__, 'm4', null);
                abort(404, dispExceptionMessage(404));
            }

            // パンくずリストパターン
            islog($this->class_name, __FUNCTION__, 'm11');
            $breadcrumbs_pattern = is_null($company_no) ? 'products' : 'company_shop_products';

            // ビューパラメータ作成
            $view_param = [
                'detail_form_data' => $ret['view'],
                'form_data' => $ret['sub_data'],
                'company_no' => $company_no,
                'breadcrumbs_view' => $breadcrumbs_pattern
            ];

            // ビューへレンダリング
            dslog($this->class_name, __FUNCTION__, 'm4', $view_param);
            islog($this->class_name, __FUNCTION__, 'm24');
            return view('products', $view_param);
        } catch (PDOException $e) {
            // 例外処理
            eslog($this->class_name, __FUNCTION__, 'm5', $e->getMessage());
            abort(500, dispExceptionMessage(500));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     *
     * @param \Illuminate\Http\Request $request
     */
    public function delete(Request $request)
    {
        //
    }
}
