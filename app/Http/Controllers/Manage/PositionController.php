<?php
/**
 * [管理]役職マスタ系画面のControllerクラス
 *
 * 役職マスタ系画面の表示、検索、登録、更新、削除処理
 *
 * @access public
 * @author Seiji Ishikawa（メールアドレス）
 * @copyright Webじゃ軒 All Rights Reserved
 * @package controller
 */
namespace App\Http\Controllers\Manage;

use App\Http\Requests\Manage\PositionRegistRequest;
use App\Http\Requests\Manage\CommonSearchRequest;
use App\Http\Requests\Manage\CommonDeleteNumRequest;
use App\Services\Manage\PositionService;
use Illuminate\Http\Request;
use Exception;

class PositionController extends BaseController
{

    protected $position_service;

    protected $position_session_name;

    protected $upsert_session_name;

    protected $class_name;

    /**
     * コンストラクタ
     *
     * @param PositionService $position_service
     */
    public function __construct(PositionService $position_service)
    {
        $this->position_service = $position_service;
        $this->position_session_name = config('consts.session_info.position_auth');
        $this->upsert_session_name = config('consts.session_info.upsert_auth');
        $this->class_name = get_class($this);
        islog($this->class_name, __FUNCTION__);
    }

    /**
     * Display a listing of the resource.
     *
     * @param \App\Http\Requests\Manage\CommonSearchRequest $request
     * @return \Illuminate\Http\Response
     */
    public function index(CommonSearchRequest $request)
    {
        try {
            islog($this->class_name, __FUNCTION__, 'm1');

            // ログイン情報取得
            islog($this->class_name, __FUNCTION__, 'm28');
            $login_info = getLoginInfo($request);
            dslog($this->class_name, __FUNCTION__, 'm11', $login_info);

            // 各使用セッションの初期化（削除）
            islog($this->class_name, __FUNCTION__, 'm25');
            $request->session()->forget($this->position_session_name);
            $request->session()->forget($this->upsert_session_name);

            // フォーム値を取得
            islog($this->class_name, __FUNCTION__, 'm21');
            $free_word = $request->input('input_free_word');
            dslog($this->class_name, __FUNCTION__, 'm3', $free_word);

            // 役職マスタ情報一覧を取得
            islog($this->class_name, __FUNCTION__, 'm10');
            $form_data = $this->position_service->getPositionPagenationList($free_word);

            // ビューパラメータ作成
            $view_param = [
                'form_data' => $form_data,
                'free_word' => $free_word,
                'login_info' => $login_info
            ];

            // ビューへレンダリング
            dslog($this->class_name, __FUNCTION__, 'm4', $view_param);
            islog($this->class_name, __FUNCTION__, 'm24');
            return view('manage.position_list', $view_param);
        } catch (Exception $e) {
            // 例外処理
            eslog($this->class_name, __FUNCTION__, 'm5', $e->getMessage());
            abort(500, dispExceptionMessage(500));
        }
    }

    /**
     * 役職マスタ登録画面
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        try {
            islog($this->class_name, __FUNCTION__, 'm1');

            // ログイン情報取得
            islog($this->class_name, __FUNCTION__, 'm28');
            $login_info = getLoginInfo($request);
            dslog($this->class_name, __FUNCTION__, 'm11', $login_info);

            // メソッド名をセッションへ設定
            islog($this->class_name, __FUNCTION__, 'm28');
            $request->session()->put($this->upsert_session_name, __FUNCTION__);

            // セッション値を取得
            islog($this->class_name, __FUNCTION__, 'm28');
            $form_data = ! is_null($request->session()->get($this->position_session_name)) ? $request->session()->get($this->position_session_name) : [];

            // ビューパラメータ作成
            $view_param = [
                'form_data' => $form_data,
                'login_info' => $login_info
            ];

            // ビューへレンダリング
            dslog($this->class_name, __FUNCTION__, 'm4', $view_param);
            islog($this->class_name, __FUNCTION__, 'm24');
            return view('manage.position_regist', $view_param);
        } catch (Exception $e) {
            // 例外処理
            eslog($this->class_name, __FUNCTION__, 'm5', $e->getMessage());
            abort(500, dispExceptionMessage(500));
        }
    }

    /**
     * Confirm a newly created resource in storage.
     *
     * @param \App\Http\Requests\Manage\PositionRegistRequest $request
     * @return \Illuminate\Http\Response
     */
    public function confirm(PositionRegistRequest $request)
    {
        try {
            islog($this->class_name, __FUNCTION__, 'm1');

            // ログイン情報取得
            islog($this->class_name, __FUNCTION__, 'm28');
            $login_info = getLoginInfo($request);
            dslog($this->class_name, __FUNCTION__, 'm11', $login_info);

            // フォーム値を取得
            islog($this->class_name, __FUNCTION__, 'm22');
            $form_data = $request->all();
            if (empty($form_data)) {
                // セッションエラー
                eslog($this->class_name, __FUNCTION__, 'm6', $form_data);
                abort(500, dispExceptionMessage(500));
            }
            dslog($this->class_name, __FUNCTION__, 'm6', $form_data);

            // セッションへフォーム値を保存
            islog($this->class_name, __FUNCTION__, 'm28');
            $request->session()->put($this->position_session_name, $form_data);

            // ビューパラメータ作成
            $view_param = [
                'form_data' => $form_data,
                'login_info' => $login_info
            ];

            // ビューへレンダリング
            dslog($this->class_name, __FUNCTION__, 'm4', $view_param);
            islog($this->class_name, __FUNCTION__, 'm24');
            return view('manage.position_confirm_' . $request->session()->get($this->upsert_session_name), $view_param);
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

            // ログイン情報取得
            islog($this->class_name, __FUNCTION__, 'm28');
            $login_info = getLoginInfo($request);
            dslog($this->class_name, __FUNCTION__, 'm11', $login_info);
            // パラメータ取得
            $acnt_no = $login_info['acnt_no'];

            // セッションからフォーム値を取得
            islog($this->class_name, __FUNCTION__, 'm26');
            $form_data = $request->session()->pull($this->position_session_name);
            dslog($this->class_name, __FUNCTION__, 'm11', $form_data);

            $ret = false;
            if (! empty($form_data)) {
                // 役職マスタ情報登録
                islog($this->class_name, __FUNCTION__, 'm18');
                $ret = $this->position_service->insertPositionData($form_data, $acnt_no);
            }

            // 各使用セッションの削除
            islog($this->class_name, __FUNCTION__, 'm25');
            $request->session()->forget($this->upsert_session_name);

            if (empty($ret)) {
                // 登録エラー
                // 一覧画面へ戻る
                eslog($this->class_name, __FUNCTION__, 'm1');
                islog($this->class_name, __FUNCTION__, 'm29');
                return redirect()->route('position.index')->with('disp_error_message', dispErrorMessage('m1'));
            }

            // 一覧画面へ戻る
            islog($this->class_name, __FUNCTION__, 'm30');
            islog($this->class_name, __FUNCTION__, 'm29');
            return redirect()->route('position.index')->with('disp_message', dispInfoMessage('m1'));
        } catch (Exception $e) {
            // 各使用セッションの削除
            islog($this->class_name, __FUNCTION__, 'm25');
            $request->session()->forget($this->upsert_session_name);
            // 例外処理
            eslog($this->class_name, __FUNCTION__, 'm5', $e->getMessage());
            abort(500, dispExceptionMessage(500));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        try {
            islog($this->class_name, __FUNCTION__, 'm1');

            // ログイン情報取得
            islog($this->class_name, __FUNCTION__, 'm28');
            $login_info = getLoginInfo($request);
            dslog($this->class_name, __FUNCTION__, 'm11', $login_info);

            // フォーム値を取得
            islog($this->class_name, __FUNCTION__, 'm21');
            dslog($this->class_name, __FUNCTION__, 'm3', $id);

            // DBから役職マスタ情報を取得
            islog($this->class_name, __FUNCTION__, 'm10');
            $form_data = $this->position_service->getPositionData($id);
            if (empty($form_data)) {
                // 不明なキー指定、値が空だった場合はエラー処理
                eslog($this->class_name, __FUNCTION__, 'm6', $form_data);
                abort(500, dispExceptionMessage(500));
            }

            // ビューパラメータ作成
            $view_param = [
                'form_data' => $form_data,
                'login_info' => $login_info
            ];

            // ビューへレンダリング
            dslog($this->class_name, __FUNCTION__, 'm4', $view_param);
            islog($this->class_name, __FUNCTION__, 'm24');
            return view('manage.position_show', $view_param);
        } catch (Exception $e) {
            // 例外処理
            eslog($this->class_name, __FUNCTION__, 'm5', $e->getMessage());
            abort(500, dispExceptionMessage(500));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        try {
            islog($this->class_name, __FUNCTION__, 'm1');

            // ログイン情報取得
            islog($this->class_name, __FUNCTION__, 'm28');
            $login_info = getLoginInfo($request);
            dslog($this->class_name, __FUNCTION__, 'm11', $login_info);

            // メソッド名をセッションへ設定
            islog($this->class_name, __FUNCTION__, 'm28');
            $request->session()->put($this->upsert_session_name, __FUNCTION__);

            // フォーム値を取得
            islog($this->class_name, __FUNCTION__, 'm21');
            dslog($this->class_name, __FUNCTION__, 'm3', $id);

            // DBまたはセッションから値を取得
            islog($this->class_name, __FUNCTION__, 'm28');
            islog($this->class_name, __FUNCTION__, 'm10');
            $form_data = ! is_null($request->session()->get($this->position_session_name)) ? $request->session()->get($this->position_session_name) : $this->position_service->getPositionData($id);
            if (empty($form_data)) {
                // セッションエラー
                eslog($this->class_name, __FUNCTION__, 'm6', $form_data);
                abort(500, dispExceptionMessage(500));
            }

            // ビューパラメータ作成
            $view_param = [
                'form_data' => $form_data,
                'login_info' => $login_info
            ];

            // ビューへレンダリング
            dslog($this->class_name, __FUNCTION__, 'm4', $view_param);
            islog($this->class_name, __FUNCTION__, 'm24');
            return view('manage.position_regist', $view_param);
        } catch (Exception $e) {
            // 例外処理
            eslog($this->class_name, __FUNCTION__, 'm5', $e->getMessage());
            abort(500, dispExceptionMessage(500));
        }
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
        try {
            islog($this->class_name, __FUNCTION__, 'm1');

            $ret = false;
            // ID番号のバリデーション後に実施
            if (mb_strlen($id, 'utf-8') === strlen($id)){
                if (is_numeric($id)){
                    // ログイン情報取得
                    islog($this->class_name, __FUNCTION__, 'm28');
                    $login_info = getLoginInfo($request);
                    dslog($this->class_name, __FUNCTION__, 'm11', $login_info);
                    // パラメータ取得
                    $acnt_no = $login_info['acnt_no'];

                    // フォーム値を取得
                    islog($this->class_name, __FUNCTION__, 'm21');
                    dslog($this->class_name, __FUNCTION__, 'm3', $id);

                    // セッションからフォーム値を取得
                    islog($this->class_name, __FUNCTION__, 'm26');
                    $form_data = $request->session()->pull($this->position_session_name);
                    dslog($this->class_name, __FUNCTION__, 'm11', $form_data);

                    if (! empty($form_data)) {
                        // 役職マスタ情報更新
                        islog($this->class_name, __FUNCTION__, 'm19');
                        $ret = $this->position_service->updatePositionData($form_data, $id, $acnt_no);
                    }
                }
            }

            // 各使用セッションの削除
            islog($this->class_name, __FUNCTION__, 'm25');
            $request->session()->forget($this->upsert_session_name);

            if (empty($ret)) {
                // 登録エラー
                // 一覧画面へ戻る
                eslog($this->class_name, __FUNCTION__, 'm1');
                islog($this->class_name, __FUNCTION__, 'm29');
                return redirect()->route('position.index')->with('disp_error_message', dispErrorMessage('m2'));
            }

            // 一覧画面へ戻る
            islog($this->class_name, __FUNCTION__, 'm30');
            islog($this->class_name, __FUNCTION__, 'm29');
            return redirect()->route('position.index')->with('disp_message', dispInfoMessage('m2'));
        } catch (Exception $e) {
            // 各使用セッションの削除
            islog($this->class_name, __FUNCTION__, 'm25');
            $request->session()->forget($this->upsert_session_name);
            // 例外処理
            eslog($this->class_name, __FUNCTION__, 'm5', $e->getMessage());
            abort(500, dispExceptionMessage(500));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Http\Requests\Manage\CommonDeleteNumRequest $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(CommonDeleteNumRequest $request)
    {
        try {
            islog($this->class_name, __FUNCTION__, 'm1');

            // ログイン情報取得
            islog($this->class_name, __FUNCTION__, 'm28');
            $login_info = getLoginInfo($request);
            dslog($this->class_name, __FUNCTION__, 'm11', $login_info);

            // フォーム値を取得
            islog($this->class_name, __FUNCTION__, 'm21');
            $select_no = $request->input('select_no');
            dslog($this->class_name, __FUNCTION__, 'm3', $select_no);

            // 役職マスタ情報削除
            islog($this->class_name, __FUNCTION__, 'm20');
            $ret = $this->position_service->deletePositionData($select_no);

            if (empty($ret)) {
                // 削除エラー
                // 一覧画面へ戻る
                eslog($this->class_name, __FUNCTION__, 'm9');
                islog($this->class_name, __FUNCTION__, 'm29');
                return redirect()->route('position.index')->with('disp_error_message', dispErrorMessage('m3'));
            }

            // 一覧画面へ戻る
            islog($this->class_name, __FUNCTION__, 'm32');
            islog($this->class_name, __FUNCTION__, 'm29');
            return redirect()->route('position.index')->with('disp_message', dispInfoMessage('m3'));
        } catch (Exception $e) {
            // 例外処理
            eslog($this->class_name, __FUNCTION__, 'm5', $e->getMessage());
            abort(500, dispExceptionMessage(500));
        }
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
