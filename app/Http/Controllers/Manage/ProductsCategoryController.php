<?php
/**
 * [管理]製品カテゴリ系画面のControllerクラス
 *
 * 製品カテゴリ系画面の表示、検索、登録、更新、削除処理
 *
 * @access public
 * @author Seiji Ishikawa（メールアドレス）
 * @copyright Webじゃ軒 All Rights Reserved
 * @package controller
 */
namespace App\Http\Controllers\Manage;

use App\Http\Requests\Manage\ProductsCategoryRegistRequest;
use App\Http\Requests\Manage\CommonSearchRequest;
use App\Http\Requests\Manage\CommonDeleteAlphaNumRequest;
use App\Services\Manage\ProductsCategoryService;
use Illuminate\Http\Request;
use Exception;

class ProductsCategoryController extends BaseController
{

    protected $products_category_service;

    protected $products_category_session_name;

    protected $upsert_session_name;

    protected $class_name;

    /**
     * コンストラクタ
     *
     * @param ProductsCategoryService $products_category_service
     */
    public function __construct(ProductsCategoryService $products_category_service)
    {
        $this->products_category_service = $products_category_service;
        $this->products_category_session_name = config('consts.session_info.products_category_auth');
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
            $request->session()->forget($this->products_category_session_name);
            $request->session()->forget($this->upsert_session_name);

            // フォーム値を取得
            islog($this->class_name, __FUNCTION__, 'm21');
            $free_word = $request->input('input_free_word');
            dslog($this->class_name, __FUNCTION__, 'm3', $free_word);

            // 製品カテゴリ情報一覧を取得
            islog($this->class_name, __FUNCTION__, 'm10');
            $form_data = $this->products_category_service->getProductsCategoryPagenationList($free_word);

            // ビューパラメータ作成
            $view_param = [
                'form_data' => $form_data,
                'free_word' => $free_word,
                'login_info' => $login_info
            ];

            // ビューへレンダリング
            dslog($this->class_name, __FUNCTION__, 'm4', $view_param);
            islog($this->class_name, __FUNCTION__, 'm24');
            return view('manage.products_category_list', $view_param);
        } catch (Exception $e) {
            // 例外処理
            eslog($this->class_name, __FUNCTION__, 'm5', $e->getMessage());
            abort(500, dispExceptionMessage(500));
        }
    }

    /**
     * 製品カテゴリ登録画面
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
            $form_data = ! is_null($request->session()->get($this->products_category_session_name)) ? $request->session()->get($this->products_category_session_name) : [];

            // ビューパラメータ作成
            $view_param = [
                'form_data' => $form_data,
                'login_info' => $login_info
            ];

            // ビューへレンダリング
            dslog($this->class_name, __FUNCTION__, 'm4', $view_param);
            islog($this->class_name, __FUNCTION__, 'm24');
            return view('manage.products_category_regist', $view_param);
        } catch (Exception $e) {
            // 例外処理
            eslog($this->class_name, __FUNCTION__, 'm5', $e->getMessage());
            abort(500, dispExceptionMessage(500));
        }
    }

    /**
     * Confirm a newly created resource in storage.
     *
     * @param \App\Http\Requests\Manage\ProductsCategoryRegistRequest $request
     * @return \Illuminate\Http\Response
     */
    public function confirm(ProductsCategoryRegistRequest $request)
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
            $request->session()->put($this->products_category_session_name, $form_data);

            // ビューパラメータ作成
            $view_param = [
                'form_data' => $form_data,
                'login_info' => $login_info
            ];

            // ビューへレンダリング
            dslog($this->class_name, __FUNCTION__, 'm4', $view_param);
            islog($this->class_name, __FUNCTION__, 'm24');
            return view('manage.products_category_confirm_' . $request->session()->get($this->upsert_session_name), $view_param);
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
            $form_data = $request->session()->pull($this->products_category_session_name);
            dslog($this->class_name, __FUNCTION__, 'm11', $form_data);

            $ret = false;
            if (! empty($form_data)) {
                // 製品カテゴリ情報登録
                islog($this->class_name, __FUNCTION__, 'm18');
                $ret = $this->products_category_service->insertProductsCategoryData($form_data, $acnt_no);
            }

            // 各使用セッションの削除
            islog($this->class_name, __FUNCTION__, 'm25');
            $request->session()->forget($this->upsert_session_name);

            if (empty($ret)) {
                // 登録エラー
                // 一覧画面へ戻る
                eslog($this->class_name, __FUNCTION__, 'm1');
                islog($this->class_name, __FUNCTION__, 'm29');
                return redirect()->route('products_category.index')->with('disp_error_message', dispErrorMessage('m1'));
            }

            // 一覧画面へ戻る
            islog($this->class_name, __FUNCTION__, 'm30');
            islog($this->class_name, __FUNCTION__, 'm29');
            return redirect()->route('products_category.index')->with('disp_message', dispInfoMessage('m1'));
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

            // DBから製品カテゴリ情報を取得
            islog($this->class_name, __FUNCTION__, 'm10');
            $form_data = $this->products_category_service->getProductsCategoryData($id);
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
            return view('manage.products_category_show', $view_param);
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
            $form_data = ! is_null($request->session()->get($this->products_category_session_name)) ? $request->session()->get($this->products_category_session_name) : $this->products_category_service->getProductsCategoryData($id);
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
            return view('manage.products_category_regist', $view_param);
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

            // ログイン情報取得
            islog($this->class_name, __FUNCTION__, 'm28');
            $login_info = getLoginInfo($request);
            dslog($this->class_name, __FUNCTION__, 'm11', $login_info);
            // パラメータ取得
            $acnt_no = isset($login_info['acnt_no']) ? $login_info['acnt_no'] : null;

            // フォーム値を取得
            islog($this->class_name, __FUNCTION__, 'm21');
            dslog($this->class_name, __FUNCTION__, 'm3', $id);

            // セッションからフォーム値を取得
            islog($this->class_name, __FUNCTION__, 'm26');
            $form_data = $request->session()->pull($this->products_category_session_name);
            dslog($this->class_name, __FUNCTION__, 'm11', $form_data);

            $ret = false;
            if (! empty($form_data)) {
                // 製品カテゴリ情報更新
                islog($this->class_name, __FUNCTION__, 'm19');
                $ret = $this->products_category_service->updateProductsCategoryData($form_data, $id, $acnt_no);
            }

            // 各使用セッションの削除
            islog($this->class_name, __FUNCTION__, 'm25');
            $request->session()->forget($this->upsert_session_name);

            if (empty($ret)) {
                // 登録エラー
                // 一覧画面へ戻る
                eslog($this->class_name, __FUNCTION__, 'm1');
                islog($this->class_name, __FUNCTION__, 'm29');
                return redirect()->route('products_category.index')->with('disp_error_message', dispErrorMessage('m2'));
            }

            // 一覧画面へ戻る
            islog($this->class_name, __FUNCTION__, 'm30');
            islog($this->class_name, __FUNCTION__, 'm29');
            return redirect()->route('products_category.index')->with('disp_message', dispInfoMessage('m2'));
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
     * @param \App\Http\Requests\Manage\CommonDeleteAlphaNumRequest $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(CommonDeleteAlphaNumRequest $request)
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

            // 製品カテゴリ情報削除
            islog($this->class_name, __FUNCTION__, 'm20');
            $ret = $this->products_category_service->deleteProductsCategoryData($select_no);

            if (empty($ret)) {
                // 削除エラー
                // 一覧画面へ戻る
                eslog($this->class_name, __FUNCTION__, 'm9');
                islog($this->class_name, __FUNCTION__, 'm29');
                return redirect()->route('products_category.index')->with('disp_error_message', dispErrorMessage('m3'));
            }

            // 一覧画面へ戻る
            islog($this->class_name, __FUNCTION__, 'm32');
            islog($this->class_name, __FUNCTION__, 'm29');
            return redirect()->route('products_category.index')->with('disp_message', dispInfoMessage('m3'));
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
