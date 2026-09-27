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

use App\Services\CompanyNewsService;
use Illuminate\Http\Request;
use Exception;
use PDOException;

class CompanyShopNewsFrontController extends Controller
{

    protected $company_news_service;

    protected $class_name;

    /**
     * コンストラクタ
     *
     * @param
     */
    public function __construct(CompanyNewsService $company_news_service)
    {
        $this->company_news_service = $company_news_service;
        $this->class_name = get_class($this);
        islog($this->class_name, __FUNCTION__);
    }

    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @param string $company_no
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $company_no)
    {
        try {
            islog($this->class_name, __FUNCTION__, 'm1');

            // フォーム値を取得
            islog($this->class_name, __FUNCTION__, 'm21');
            dslog($this->class_name, __FUNCTION__, 'm3', $company_no); // 企業番号

            // 企業支店ニュース情報一覧を取得
            islog($this->class_name, __FUNCTION__, 'm10');
            $ret = $this->company_news_service->getCompanyBranchNewsPagenationList($company_no);
            if (is_null($ret['view']->first())) {
                // 値がなし
                dslog($this->class_name, __FUNCTION__, 'm4', null);
            }

            // ビューパラメータ作成
            $view_param = [
                'list_form_data' => $ret['view'],
                'form_data' => $ret['sub_data'],
                'breadcrumbs_view' => 'company_shop_news'
            ];

            // ビューへレンダリング
            dslog($this->class_name, __FUNCTION__, 'm4', $view_param);
            islog($this->class_name, __FUNCTION__, 'm24');
            return view('company_shop_news_list', $view_param);
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
     * @param string $company_no
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $company_no)
    {
        try {
            islog($this->class_name, __FUNCTION__, 'm1');

            // フォーム値を取得
            islog($this->class_name, __FUNCTION__, 'm21');
            $company_branch_no = $request->input('b');
            $company_branch_news_no = $request->input('bn');
            dslog($this->class_name, __FUNCTION__, 'm3', [
                'company_no' => $company_no, // 企業番号
                $company_branch_no, // 企業支店番号
                $company_branch_news_no // 企業支店ニュース番号
            ]);

            // 表示する企業支店ニュース情報を取得
            islog($this->class_name, __FUNCTION__, 'm10');
            $ret = $this->company_news_service->getCompanyBranchNewsData($company_no, $company_branch_no, $company_branch_news_no);
            if (is_null($ret['view'])) {
                // 値が空だった場合はページが存在しない
                dslog($this->class_name, __FUNCTION__, 'm4', null);
                abort(404, dispExceptionMessage(404));
            }

            // ビューパラメータ作成
            $view_param = [
                'detail_form_data' => $ret['view'],
                'form_data' => $ret['sub_data'],
                'breadcrumbs_view' => 'company_shop_news'
            ];

            // ビューへレンダリング
            dslog($this->class_name, __FUNCTION__, 'm4', $view_param);
            islog($this->class_name, __FUNCTION__, 'm24');
            return view('company_shop_news', $view_param);
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
