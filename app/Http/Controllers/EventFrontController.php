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

use App\Services\EventService;
use Illuminate\Http\Request;
use Exception;
use PDOException;

class EventFrontController extends Controller
{

    protected $event_service;

    protected $class_name;

    /**
     * コンストラクタ
     *
     * @param
     */
    public function __construct(EventService $event_service)
    {
        $this->event_service = $event_service;
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

            // 情報一覧を取得
            islog($this->class_name, __FUNCTION__, 'm10');
            $ret = $this->event_service->getEventPagenationList($search_tag);
            if (is_null($ret['view']->first())) {
                // 値がなし
                dslog($this->class_name, __FUNCTION__, 'm4', null);
            }

            // ビューパラメータ作成
            $view_param = [
                'list_form_data' => $ret['view'],
                'form_data' => $ret['sub_data'],
                'breadcrumbs_view' => 'event'
            ];

            // ビューへレンダリング
            dslog($this->class_name, __FUNCTION__, 'm4', $view_param);
            islog($this->class_name, __FUNCTION__, 'm24');
            return view('event_list', $view_param);
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
     * @param int $event_no
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $event_no)
    {
        try {
            islog($this->class_name, __FUNCTION__, 'm1');

            // フォーム値を取得
            islog($this->class_name, __FUNCTION__, 'm21');
            dslog($this->class_name, __FUNCTION__, 'm3', $event_no); // イベント管理番号

            // 表示するイベント情報を取得
            islog($this->class_name, __FUNCTION__, 'm10');
            $event_no = ltrim($event_no, '0');
            $ret = $this->event_service->getEventData($event_no);
            if (is_null($ret['view'])) {
                // 値が空だった場合はページが存在しない
                dslog($this->class_name, __FUNCTION__, 'm4', null);
                abort(404, dispExceptionMessage(404));
            }

            // ビューパラメータ
            $view_param = [
                'detail_form_data' => $ret['view'],
                'form_data' => $ret['sub_data'],
                'breadcrumbs_view' => 'event'
            ];

            // ビューへレンダリング
            dslog($this->class_name, __FUNCTION__, 'm4', $view_param);
            islog($this->class_name, __FUNCTION__, 'm24');
            return view('event', $view_param);
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
