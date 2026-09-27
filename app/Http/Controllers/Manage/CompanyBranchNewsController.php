<?php
/**
 * [管理]企業支店ニュース系画面のControllerクラス
 *
 * 企業支店系ニュース画面の表示、検索、登録、更新、削除処理
 *
 * @access public
 * @author Seiji Ishikawa（メールアドレス）
 * @copyright Webじゃ軒 All Rights Reserved
 * @package controller
 */
namespace App\Http\Controllers\Manage;

use App\Http\Requests\Manage\CompanyBranchNewsRegistRequest;
use App\Http\Requests\Manage\CommonSearchRequest;
use App\Http\Requests\Manage\CommonDeleteNumRequest;
use App\Services\Manage\CompanyBranchNewsService;
use Illuminate\Http\Request;
use Exception;

class CompanyBranchNewsController extends BaseController
{

    protected $company_branch_news_service;

    protected $company_branch_news_session_name;

    protected $upsert_session_name;

    protected $class_name;

    /**
     * コンストラクタ
     *
     * @param CompanyBranchNewsService $company_branch_news_service
     */
    public function __construct(CompanyBranchNewsService $company_branch_news_service)
    {
        $this->company_branch_news_service = $company_branch_news_service;
        $this->company_branch_news_session_name = config('consts.session_info.branch_news_auth');
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
            // パラメータ取得
            $company_no = $login_info['company_no'];
            $company_branch_no = $login_info['company_branch_no'];

            // 各使用セッションの初期化（削除）
            islog($this->class_name, __FUNCTION__, 'm25');
            $request->session()->forget($this->company_branch_news_session_name);
            $request->session()->forget($this->upsert_session_name);

            // フォーム値を取得
            islog($this->class_name, __FUNCTION__, 'm21');
            $free_word = $request->input('input_free_word');
            dslog($this->class_name, __FUNCTION__, 'm3', $free_word);

            // 企業支店ニュース情報一覧を取得
            islog($this->class_name, __FUNCTION__, 'm10');
            $form_data = $this->company_branch_news_service->getCompanyBranchNewsPagenationList($company_no, $company_branch_no, $free_word);

            // ビューパラメータ作成
            $view_param = [
                'form_data' => $form_data,
                'free_word' => $free_word,
                'login_info' => $login_info
            ];

            // ビューへレンダリング
            dslog($this->class_name, __FUNCTION__, 'm4', $view_param);
            islog($this->class_name, __FUNCTION__, 'm24');
            return view('manage.company_branch_news_list', $view_param);
        } catch (Exception $e) {
            // 例外処理
            eslog($this->class_name, __FUNCTION__, 'm5', $e->getMessage());
            abort(500, dispExceptionMessage(500));
        }
    }

    /**
     * 企業支店ニュース登録画面
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

            // フォーム値を取得
            islog($this->class_name, __FUNCTION__, 'm21');
            $free_word = $request->input('input_free_word');
            dslog($this->class_name, __FUNCTION__, 'm3', $free_word);

            // セッション値を取得
            islog($this->class_name, __FUNCTION__, 'm28');
            $form_data = ! is_null($request->session()->get($this->company_branch_news_session_name)) ? $request->session()->get($this->company_branch_news_session_name) : [];

            // ビューパラメータ作成
            $view_param = [
                'form_data' => $form_data,
                'free_word' => $free_word,
                'login_info' => $login_info,
                'image_category' => getImageCompanyCategory()
            ];

            // ビューへレンダリング
            dslog($this->class_name, __FUNCTION__, 'm4', $view_param);
            islog($this->class_name, __FUNCTION__, 'm24');
            return view('manage.company_branch_news_regist', $view_param);
        } catch (Exception $e) {
            // 例外処理
            eslog($this->class_name, __FUNCTION__, 'm5', $e->getMessage());
            abort(500, dispExceptionMessage(500));
        }
    }

    /**
     * Confirm a newly created resource in storage.
     *
     * @param \App\Http\Requests\Manage\CompanyBranchNewsRegistRequest $request
     * @return \Illuminate\Http\Response
     */
    public function confirm(CompanyBranchNewsRegistRequest $request)
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
            $request->session()->put($this->company_branch_news_session_name, $form_data);

            // ビューパラメータ作成
            $view_param = [
                'form_data' => $form_data,
                'login_info' => $login_info
            ];

            // ビューへレンダリング
            dslog($this->class_name, __FUNCTION__, 'm4', $view_param);
            islog($this->class_name, __FUNCTION__, 'm24');
            return view('manage.company_branch_news_confirm_' . $request->session()->get($this->upsert_session_name), $view_param);
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
            $company_no = $login_info['company_no'];
            $company_branch_no = $login_info['company_branch_no'];
            $acnt_no = $login_info['acnt_no'];

            // セッションからフォーム値を取得
            islog($this->class_name, __FUNCTION__, 'm26');
            $form_data = $request->session()->pull($this->company_branch_news_session_name);
            dslog($this->class_name, __FUNCTION__, 'm11', $form_data);

            $ret = false;
            if (! empty($form_data)) {
                // 企業支店ニュース情報登録
                islog($this->class_name, __FUNCTION__, 'm18');
                $ret = $this->company_branch_news_service->insertCompanyBranchNewsData($form_data, $company_no, $company_branch_no, $acnt_no);
            }

            // 各使用セッションの削除
            islog($this->class_name, __FUNCTION__, 'm25');
            $request->session()->forget($this->upsert_session_name);

            if (empty($ret)) {
                // 登録エラー
                // 一覧画面へ戻る
                eslog($this->class_name, __FUNCTION__, 'm1');
                islog($this->class_name, __FUNCTION__, 'm29');
                // 登録画面へ戻る
                return redirect()->route('company_branch_news.index')->with('disp_error_message', dispErrorMessage('m1'));
            }

            // 一覧画面へ戻る
            islog($this->class_name, __FUNCTION__, 'm30');
            islog($this->class_name, __FUNCTION__, 'm29');
            return redirect()->route('company_branch_news.index')->with('disp_message', dispInfoMessage('m1'));
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
     * @param String $id
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
            // パラメータ取得
            $company_no = $login_info['company_no'];
            $company_branch_no = $login_info['company_branch_no'];

            // フォーム値を取得
            islog($this->class_name, __FUNCTION__, 'm21');
            dslog($this->class_name, __FUNCTION__, 'm3', $id);

            // DBから企業支店ニュース情報を取得
            $form_data = $this->company_branch_news_service->getCompanyBranchNewsData($company_no, $company_branch_no, $id);
            if (empty($form_data)) {
                // 不明なキー指定、値が空だった場合はエラー処理
                eslog($this->class_name, __FUNCTION__, 'm6');
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
            return view('manage.company_branch_news_show', $view_param);
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
            // パラメータ取得
            $company_no = $login_info['company_no'];
            $company_branch_no = $login_info['company_branch_no'];

            // メソッド名をセッションへ設定
            islog($this->class_name, __FUNCTION__, 'm28');
            $request->session()->put($this->upsert_session_name, __FUNCTION__);

            // フォーム値を取得
            islog($this->class_name, __FUNCTION__, 'm21');
            $free_word = $request->input('input_free_word');
            dslog($this->class_name, __FUNCTION__, 'm3', [
                'id' => $id,
                'free_word' => $free_word
            ]);

            // DBまたはセッションから値を取得
            islog($this->class_name, __FUNCTION__, 'm28');
            islog($this->class_name, __FUNCTION__, 'm10');
            $form_data = ! is_null($request->session()->get($this->company_branch_news_session_name)) ? $request->session()->get($this->company_branch_news_session_name) : $this->company_branch_news_service->getCompanyBranchNewsData($company_no, $company_branch_no, $id);
            if (empty($form_data)) {
                // セッションエラー
                eslog($this->class_name, __FUNCTION__, 'm6', $form_data);
                abort(500, dispExceptionMessage(500));
            }

            // ビューパラメータ作成
            $view_param = [
                'form_data' => $form_data,
                'free_word' => $free_word,
                'login_info' => $login_info,
                'image_category' => getImageCompanyCategory()
            ];

            // ビューへレンダリング
            dslog($this->class_name, __FUNCTION__, 'm4', $view_param);
            islog($this->class_name, __FUNCTION__, 'm24');
            return view('manage.company_branch_news_regist', $view_param);
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
                    $company_no = $login_info['company_no'];
                    $company_branch_no = $login_info['company_branch_no'];
                    $acnt_no = $login_info['acnt_no'];

                    // フォーム値を取得
                    islog($this->class_name, __FUNCTION__, 'm21');
                    dslog($this->class_name, __FUNCTION__, 'm3', $id);

                    // セッションからフォーム値を取得
                    islog($this->class_name, __FUNCTION__, 'm26');
                    $form_data = $request->session()->pull($this->company_branch_news_session_name);
                    dslog($this->class_name, __FUNCTION__, 'm11', $form_data);

                    if (! empty($form_data)) {
                        // 企業支店ニュース情報更新
                        islog($this->class_name, __FUNCTION__, 'm19');
                        $ret = $this->company_branch_news_service->updateCompanyBranchNewsData($form_data, $id, $company_no, $company_branch_no, $acnt_no);
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
                // 登録画面へ戻る
                return redirect()->route('company_branch_news.index')->with('disp_error_message', dispErrorMessage('m2'));
            }

            // 一覧画面へ戻る
            islog($this->class_name, __FUNCTION__, 'm30');
            islog($this->class_name, __FUNCTION__, 'm29');
            return redirect()->route('company_branch_news.index')->with('disp_message', dispInfoMessage('m2'));
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
            // パラメータ取得
            $company_no = $login_info['company_no'];
            $company_branch_no = $login_info['company_branch_no'];

            // フォーム値を取得
            islog($this->class_name, __FUNCTION__, 'm21');
            $select_no = $request->input('select_no');
            dslog($this->class_name, __FUNCTION__, 'm3', $select_no);

            // 企業支店ニュース情報削除
            islog($this->class_name, __FUNCTION__, 'm20');
            $ret = $this->company_branch_news_service->deleteCompanyBranchNewsData($company_no, $company_branch_no, $select_no);

            if (empty($ret)) {
                // 削除エラー
                // 一覧画面へ戻る
                eslog($this->class_name, __FUNCTION__, 'm9');
                islog($this->class_name, __FUNCTION__, 'm29');
                return redirect()->route('company_branch_news.index')->with('disp_error_message', dispErrorMessage('m3'));
            }

            // 一覧画面へ戻る
            islog($this->class_name, __FUNCTION__, 'm32');
            islog($this->class_name, __FUNCTION__, 'm29');
            return redirect()->route('company_branch_news.index')->with('disp_message', dispInfoMessage('m3'));
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
