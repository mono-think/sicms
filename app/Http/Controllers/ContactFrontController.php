<?php
/**
 * [フロント]メッセージボード系画面のControllerクラス
 *
 * メッセージボード系画面の表示、検索処理
 *
 * @access public
 * @author Seiji Ishikawa（メールアドレス）
 * @copyright Webじゃ軒 All Rights Reserved
 * @package controller
 */
namespace App\Http\Controllers;

use App\Http\Requests\ContactRegistFrontRequest;
use App\Services\ContactService;
use Illuminate\Http\Request;
use Exception;

class ContactFrontController extends Controller
{

    protected $contact_service;

    protected $contact_session_name;

    protected $class_name;

    /**
     * コンストラクタ
     *
     * @param
     */
    public function __construct(ContactService $contact_service)
    {
        $this->contact_service = $contact_service;
        $this->contact_session_name = config('consts.session_info.front_contact_auth');
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
        //
    }

    /**
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        try {
            islog($this->class_name, __FUNCTION__, 'm1');

            // セッション値を取得
            islog($this->class_name, __FUNCTION__, 'm28');
            $form_data = ! is_null($request->session()->get($this->contact_session_name)) ? $request->session()->get($this->contact_session_name) : [];

            // ビューパラメータ作成
            $form_data['breadcrumbs'] = '';

            $view_param = [
                'form_data' => $form_data,
                'breadcrumbs_view' => 'contact'
            ];

            // ビューへレンダリング
            dslog($this->class_name, __FUNCTION__, 'm4', $view_param);
            islog($this->class_name, __FUNCTION__, 'm24');
            return view('contact_regist', $view_param);
        } catch (Exception $e) {
            // 例外処理
            eslog($this->class_name, __FUNCTION__, 'm5', $e->getMessage());
            abort(500, dispExceptionMessage(500));
        }
    }

    /**
     * Confirm a newly created resource in storage.
     *
     * @param \App\Http\Requests\ContactRegistFrontRequest $regist_request
     * @return \Illuminate\Http\Response
     */
    public function confirm(ContactRegistFrontRequest $request)
    {
        try {
            islog($this->class_name, __FUNCTION__, 'm1');

            // フォーム値を取得
            islog($this->class_name, __FUNCTION__, 'm27');
            $form_data = $request->all();
            dslog($this->class_name, __FUNCTION__, 'm5', $form_data);

            // セッションへフォーム値を保存
            islog($this->class_name, __FUNCTION__, 'm28');
            $request->session()->put($this->contact_session_name, $form_data);

            // ビューパラメータ作成
            $form_data['breadcrumbs'] = '';
            $view_param = [
                'form_data' => $form_data,
                'breadcrumbs_view' => 'contact'
            ];

            // ビューへレンダリング
            dslog($this->class_name, __FUNCTION__, 'm4', $view_param);
            islog($this->class_name, __FUNCTION__, 'm24');
            return view('contact_confirm', $view_param);
        } catch (Exception $e) {
            // 例外処理
            eslog($this->class_name, __FUNCTION__, 'm5', $e->getMessage());
            abort(500, dispExceptionMessage(500));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            islog($this->class_name, __FUNCTION__, 'm1');

            // セッションからフォーム値を取得
            islog($this->class_name, __FUNCTION__, 'm26');
            $form_data = $request->session()->pull($this->contact_session_name);
            dslog($this->class_name, __FUNCTION__, 'm11', $form_data);

            if (! empty($form_data)) {
                // メール送信
                islog($this->class_name, __FUNCTION__, 'm18');
                $this->contact_service->sendContactMail($form_data);
            }

            // ビューパラメータ作成
            $form_data['breadcrumbs'] = '';
            $view_param = [
                'form_data' => $form_data,
                'breadcrumbs_view' => 'contact'
            ];

            // ビューへレンダリング
            dslog($this->class_name, __FUNCTION__, 'm4', $view_param);
            islog($this->class_name, __FUNCTION__, 'm24');
            return view('contact_complete', $view_param);
        } catch (Exception $e) {
            // 例外処理
            eslog($this->class_name, __FUNCTION__, 'm5', $e->getMessage());
            abort(500, dispExceptionMessage(500));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        //
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
    {}

    /**
     * Remove the specified resource from storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
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
