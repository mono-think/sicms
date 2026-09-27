<?php
/**
 * [フロント]企業_ショップサービス系画面のControllerクラス
 *
 * 企業ショップサービス系画面の表示、検索処理
 *
 * @access public
 * @author Seiji Ishikawa（メールアドレス）
 * @copyright Webじゃ軒 All Rights Reserved
 * @package controller
 */
namespace App\Http\Controllers;

use App\Services\CompanyService;
use Illuminate\Http\Request;
use PDOException;
use Exception;

class CompanyShopFrontController extends Controller
{

    protected $company_service;

    protected $class_name;

    /**
     * コンストラクタ
     *
     * @param
     */
    public function __construct(CompanyService $company_service)
    {
        $this->company_service = $company_service;
        $this->class_name = get_class($this);
        islog($this->class_name, __FUNCTION__);
    }

    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            islog($this->class_name, __FUNCTION__, 'm1');

            // フォーム値を取得
            islog($this->class_name, __FUNCTION__, 'm21');
            $search_tag = $request->input('tag');
            dslog($this->class_name, __FUNCTION__, 'm3', [
                'search_tag' => $search_tag // 検索タグ
            ]);

            // 企業情報一覧を取得
            islog($this->class_name, __FUNCTION__, 'm10');
            $ret = $this->company_service->getCompanyPagenationList($search_tag);
            if (is_null($ret['view']->first())) {
                // 値がなし
                dslog($this->class_name, __FUNCTION__, 'm4', null);
            }

            // ビューパラメータ
            $view_param = [
                'list_form_data' => $ret['view'],
                'form_data' => $ret['sub_data'],
                'breadcrumbs_view' => 'company_shop'
            ];

            // ビューへレンダリング
            dslog($this->class_name, __FUNCTION__, 'm4', $view_param);
            islog($this->class_name, __FUNCTION__, 'm24');
            return view('company_shop_list', $view_param);
        } catch (Exception $e) {
            // 例外処理
            eslog($this->class_name, __FUNCTION__, 'm5', $e->getMessage());
            abort(500, dispExceptionMessage(500));
        }
    }

    /**
     * 登録画面
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
     * @param string $company_no
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $company_no)
    {
        try {
            islog($this->class_name, __FUNCTION__, 'm1');

            // フォーム値を取得
            islog($this->class_name, __FUNCTION__, 'm21');
            dslog($this->class_name, __FUNCTION__, 'm3', $company_no); // 企業番号

            // 表示する企業情報を取得
            islog($this->class_name, __FUNCTION__, 'm10');
            $ret = $this->company_service->getCompanyData($company_no);
            if (is_null($ret['view'])) {
                // 値が空だった場合はページが存在しない
                dslog($this->class_name, __FUNCTION__, 'm4',null);
                abort(404, dispExceptionMessage(404));
            }

            // ビューパラメータ
            $view_param = [
                'detail_form_data' => $ret['view'],
                'form_data' => $ret['sub_data'],
                'breadcrumbs_view' => 'company_shop'
            ];

            // ビューへレンダリング
            dslog($this->class_name, __FUNCTION__, 'm4', $view_param);
            islog($this->class_name, __FUNCTION__, 'm24');
            return view('company_shop', $view_param);
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
