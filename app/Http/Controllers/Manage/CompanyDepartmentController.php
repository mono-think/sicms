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

use App\Http\Requests\Manage\CompanyDepartmentRegistRequest;
use App\Http\Requests\Manage\CommonSearchRequest;
use App\Http\Requests\Manage\CommonDeleteAlphaNumRequest;
use App\Services\Manage\CompanyDepartmentService;
use Illuminate\Http\Request;
use Exception;

class CompanyDepartmentController extends BaseController
{

    protected $company_department_service;

    protected $company_department_session_name;

    protected $upsert_session_name;

    protected $class_name;

    /**
     * コンストラクタ
     *
     * @param CompanyDepartmentService $company_department_service
     */
    public function __construct(CompanyDepartmentService $company_department_service)
    {
        $this->company_department_service = $company_department_service;
        $this->company_department_session_name = config('consts.session_info.department_auth');
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
            $request->session()->forget($this->company_department_session_name);
            $request->session()->forget($this->upsert_session_name);

            // フォーム値を取得
            islog($this->class_name, __FUNCTION__, 'm21');
            $free_word = $request->input('input_free_word');
            dslog($this->class_name, __FUNCTION__, 'm3', $free_word);

            // 企業部署情報一覧を取得
            islog($this->class_name, __FUNCTION__, 'm10');
            $form_data = $this->company_department_service->getCompanyDepartmentPagenationList($company_no, $company_branch_no, $free_word);

            // ビューパラメータ作成
            $view_param = [
                'form_data' => $form_data,
                'free_word' => $free_word,
                'login_info' => $login_info
            ];

            // ビューへレンダリング
            dslog($this->class_name, __FUNCTION__, 'm4', $view_param);
            islog($this->class_name, __FUNCTION__, 'm24');
            return view('manage.company_department_list', $view_param);
        } catch (Exception $e) {
            // 例外処理
            eslog($this->class_name, __FUNCTION__, 'm5', $e->getMessage());
            abort(500, dispExceptionMessage(500));
        }
    }

    /*
     * 企業部署登録画面
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
            $form_data = ! is_null($request->session()->get($this->company_department_session_name)) ? $request->session()->get($this->company_department_session_name) : [];

            // ビューパラメータ作成
            $view_param = [
                'form_data' => $form_data,
                'login_info' => $login_info
            ];

            // ビューへレンダリング
            dslog($this->class_name, __FUNCTION__, 'm4', $view_param);
            islog($this->class_name, __FUNCTION__, 'm24');
            return view('manage.company_department_regist', $view_param);
        } catch (Exception $e) {
            // 例外処理
            eslog($this->class_name, __FUNCTION__, 'm5', $e->getMessage());
            abort(500, dispExceptionMessage(500));
        }
    }

    /**
     * Confirm a newly created resource in storage.
     *
     * @param \App\Http\Requests\Manage\CompanyDepartmentRegistRequest $request
     * @return \Illuminate\Http\Response
     */
    public function confirm(CompanyDepartmentRegistRequest $request)
    {
        try {
            islog($this->class_name, __FUNCTION__, 'm1');

            // ログイン情報取得
            islog($this->class_name, __FUNCTION__, 'm28');
            $login_info = getLoginInfo($request);
            dslog($this->class_name, __FUNCTION__, 'm11', $login_info);

            // フォーム値を取得
            islog($this->class_name, __FUNCTION__, 'm21');
            $form_data = $request->all();
            if (empty($form_data)) {
                // セッションエラー
                eslog($this->class_name, __FUNCTION__, 'm6', $form_data);
                abort(500, dispExceptionMessage(500));
            }
            dslog($this->class_name, __FUNCTION__, 'm6', $form_data);

            // セッションへフォーム値を保存
            islog($this->class_name, __FUNCTION__, 'm28');
            $request->session()->put($this->company_department_session_name, $form_data);

            // ビューパラメータ作成
            $view_param = [
                'form_data' => $form_data,
                'login_info' => $login_info
            ];

            // ビューへレンダリング
            dslog($this->class_name, __FUNCTION__, 'm4', $view_param);
            islog($this->class_name, __FUNCTION__, 'm24');
            return view('manage.company_department_confirm_' . $request->session()->get($this->upsert_session_name), $view_param);
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
            $form_data = $request->session()->pull($this->company_department_session_name);
            dslog($this->class_name, __FUNCTION__, 'm11', $form_data);

            $ret = false;
            if (! empty($form_data)) {
                // 企業情報登録
                islog($this->class_name, __FUNCTION__, 'm18');
                $ret = $this->company_department_service->insertCompanyDepartmentData($form_data, $company_no, $company_branch_no, $acnt_no);
            }

            // 各使用セッションの削除
            islog($this->class_name, __FUNCTION__, 'm25');
            $request->session()->forget($this->upsert_session_name);

            if (empty($ret)) {
                // 登録エラー
                // 一覧画面へ戻る
                eslog($this->class_name, __FUNCTION__, 'm1');
                islog($this->class_name, __FUNCTION__, 'm29');
                return redirect()->route('company_department.index')->with('disp_error_message', dispErrorMessage('m1'));
            }

            // 一覧画面へ戻る
            islog($this->class_name, __FUNCTION__, 'm30');
            islog($this->class_name, __FUNCTION__, 'm29');
            return redirect()->route('company_department.index')->with('disp_message', dispInfoMessage('m1'));
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

            // DBから企業支店部署情報を取得
            islog($this->class_name, __FUNCTION__, 'm10');
            $form_data = $this->company_department_service->getCompanyDepartmentData($company_no, $company_branch_no, $id);
            if (empty($form_data)) {
                // 不明なキー指定、値が空だった場合はエラー処理
                eslog($this->class_name, __FUNCTION__, 'm6');
                abort(500, dispExceptionMessage(500));
            }

            // ログイン情報へ選択部署情報を設定(アドミニストレータ権限のみ)
            if (checkAuthAcntAdmin($login_info['acnt_auth_no'])){
                $login_info = $this->company_department_service->setCompanyDepartmentLoginInfo($login_info, $form_data);
                setLoginInfo($request, $login_info);
            }

            // ビューパラメータ作成
            $view_param = [
                'form_data' => $form_data,
                'login_info' => $login_info
            ];

            // ビューへレンダリング
            dslog($this->class_name, __FUNCTION__, 'm4', $view_param);
            islog($this->class_name, __FUNCTION__, 'm24');
            return view('manage.company_department_show', $view_param);
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
            dslog($this->class_name, __FUNCTION__, 'm3', $id);

            // DBまたはセッションから値を取得
            islog($this->class_name, __FUNCTION__, 'm28');
            islog($this->class_name, __FUNCTION__, 'm10');
            $form_data = ! is_null($request->session()->get($this->company_department_session_name)) ? $request->session()->get($this->company_department_session_name) : $this->company_department_service->getCompanyDepartmentData($company_no, $company_branch_no, $id);
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
            return view('manage.company_department_regist', $view_param);
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
            $company_no = $login_info['company_no'];
            $company_branch_no = $login_info['company_branch_no'];
            $acnt_no = $login_info['acnt_no'];

            // フォーム値を取得
            islog($this->class_name, __FUNCTION__, 'm21');
            dslog($this->class_name, __FUNCTION__, 'm3', $id);

            // セッションからフォーム値を取得
            islog($this->class_name, __FUNCTION__, 'm26');
            $form_data = $request->session()->pull($this->company_department_session_name);
            dslog($this->class_name, __FUNCTION__, 'm11', $form_data);

            $ret = false;
            if (! empty($form_data)) {
                // 企業情報更新
                islog($this->class_name, __FUNCTION__, 'm19');
                $ret = $this->company_department_service->updateCompanyDepartmentData($form_data, $id, $company_no, $company_branch_no, $acnt_no);
            }

            // 各使用セッションの削除
            islog($this->class_name, __FUNCTION__, 'm25');
            $request->session()->forget($this->upsert_session_name);

            if (empty($ret)) {
                // 登録エラー
                // 一覧画面へ戻る
                eslog($this->class_name, __FUNCTION__, 'm1');
                islog($this->class_name, __FUNCTION__, 'm29');
                return redirect()->route('company_department.index')->with('disp_error_message', dispErrorMessage('m2'));
            }

            // 一覧画面へ戻る
            islog($this->class_name, __FUNCTION__, 'm30');
            islog($this->class_name, __FUNCTION__, 'm29');
            return redirect()->route('company_department.index')->with('disp_message', dispInfoMessage('m2'));
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
            // パラメータ取得
            $company_no = $login_info['company_no'];
            $company_branch_no = $login_info['company_branch_no'];
            $company_department_no = $login_info['company_department_no'];
            $acnt_no = $login_info['acnt_no'];

            // フォーム値を取得
            islog($this->class_name, __FUNCTION__, 'm21');
            $select_no = $request->input('select_no');
            dslog($this->class_name, __FUNCTION__, 'm3', $select_no);

            // 選択中部署が削除対象となっているか判定
            $ret = false;
            if (! in_array($company_department_no, $select_no)) {
                // 企業部署情報削除
                islog($this->class_name, __FUNCTION__, 'm20');
                $ret = $this->company_department_service->deleteCompanyDepartmentData($company_no, $company_branch_no, $acnt_no, $select_no);
            }

            if (empty($ret)) {
                // 削除エラー
                // 一覧画面へ戻る
                eslog($this->class_name, __FUNCTION__, 'm9');
                islog($this->class_name, __FUNCTION__, 'm29');
                return redirect()->route('company_department.index')->with('disp_error_message', dispErrorMessage('m3'));
            }

            // 一覧画面へ戻る
            islog($this->class_name, __FUNCTION__, 'm32');
            islog($this->class_name, __FUNCTION__, 'm29');
            return redirect()->route('company_department.index')->with('disp_message', dispInfoMessage('m3'));
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
