<?php
/**
 * [フロント]イベント系画面のControllerクラス
 *
 * イベント系画面の表示、検索処理
 *
 * @access public
 * @author Seiji Ishikawa（メールアドレス）
 * @copyright Webじゃ軒 All Rights Reserved
 * @package controller
 */
namespace App\Http\Controllers;

use App\Services\ContentService;
use Illuminate\Http\Request;
use Exception;
use PDOException;

class ContentFrontController extends Controller
{

    protected $content_service;

    protected $class_name;

    /**
     * コンストラクタ
     *
     * @param
     */
    public function __construct(ContentService $content_service)
    {
        $this->content_service = $content_service;
        $this->class_name = get_class($this);
        islog($this->class_name, __FUNCTION__);
    }

    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $contents_type
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $contents_type)
    {
        try {
            islog($this->class_name, __FUNCTION__, 'm1');

            // フォーム値を取得
            islog($this->class_name, __FUNCTION__, 'm21');
            $search_tag = trim($request->input('tag'));
            dslog($this->class_name, __FUNCTION__, 'm3', [
                'search_tag' => $search_tag // 検索タグ
            ]);

            // フォーム値を取得
            islog($this->class_name, __FUNCTION__, 'm21');
            dslog($this->class_name, __FUNCTION__, 'm3', $contents_type); // コンテンツ種別

            // コンテンツ情報一覧を取得
            islog($this->class_name, __FUNCTION__, 'm10');
            if ($contents_type > 0) {
                $contents_type = ltrim($contents_type, '0');
            }
            $ret = $this->content_service->getContentPagenationList($contents_type, $search_tag);
            if (is_null($ret['view']->first())) {
                // 値がなし
                dslog($this->class_name, __FUNCTION__, 'm4', null);
            }

            // ビューパラメータ作成
            $view_param = [
                'list_form_data' => $ret['view'],
                'form_data' => $ret['sub_data'],
                'breadcrumbs_view' => 'content'
            ];

            // ビューへレンダリング
            dslog($this->class_name, __FUNCTION__, 'm4', $view_param);
            islog($this->class_name, __FUNCTION__, 'm24');
            return view('content_list', $view_param);
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
     * @param int $contents_type
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $contents_type)
    {
        try {
            islog($this->class_name, __FUNCTION__, 'm1');

            // フォーム値を取得
            islog($this->class_name, __FUNCTION__, 'm21');
            $contents_no = $request->input('n');
            dslog($this->class_name, __FUNCTION__, 'm3', [
                'contents_type' => $contents_type, // コンテンツ種別
                'contents_no' => $contents_no // コンテンツ管理番号
            ]);

            // 表示するコンテンツ情報を取得
            islog($this->class_name, __FUNCTION__, 'm10');
            if ($contents_type > 0) {
                $contents_type = ltrim($contents_type, '0');
            }
            $contents_no = ltrim($contents_no, '0');
            $ret = $this->content_service->getContentData($contents_type, $contents_no);
            if (is_null($ret['view'])) {
                // 値が空だった場合はページが存在しない
                dslog($this->class_name, __FUNCTION__, 'm4', null);
                abort(404, dispExceptionMessage(404));
            }

            // ビューパラメータ作成
            $view_param = [
                'detail_form_data' => $ret['view'],
                'form_data' => $ret['sub_data'],
                'breadcrumbs_view' => 'content'
            ];

            // ビューへレンダリング
            dslog($this->class_name, __FUNCTION__, 'm4', $view_param);
            islog($this->class_name, __FUNCTION__, 'm24');
            return view('content', $view_param);
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
