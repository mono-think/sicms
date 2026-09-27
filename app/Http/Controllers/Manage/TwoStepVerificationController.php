<?php
/**
 * [管理]二段階認証系画面のControllerクラス
 *
 * 二段階認証系画面の表示、認証、メール処理
 *
 * @access public
 * @author Seiji Ishikawa（メールアドレス）
 * @copyright Webじゃ軒 All Rights Reserved
 * @package controller
 */
namespace App\Http\Controllers\Manage;

use App\Http\Requests\Manage\TwoStepVerificationRequest;
use App\Services\Manage\AcntService;
use App\Services\Manage\VerificationService;
use Illuminate\Http\Request;
use Exception;

class TwoStepVerificationController extends Controller
{

    protected $acnt_service;

    protected $verification_service;

    protected $verification_session_name;

    protected $class_name;

    /**
     * コンストラクタ
     *
     * @param VerificationService $verification_service
     */
    public function __construct(AcntService $acnt_service, VerificationService $verification_service)
    {
        $this->acnt_service = $acnt_service;
        $this->verification_service = $verification_service;
        $this->verification_session_name = config('consts.session_info.twostep_auth');
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
     * 二段階認証処理
     *
     * @param \App\Http\Requests\Manage\TwoStepVerificationRequest $request
     * @return \Illuminate\Http\Response
     */
    public function verification(TwoStepVerificationRequest $request)
    {
        try {
            islog($this->class_name, __FUNCTION__, 'm1');

            // セッションから認証email取得
            islog($this->class_name, __FUNCTION__, 'm26');
            $verify_data = $request->session()->pull($this->verification_session_name);
            list ($id, $email) = $verify_data;
            dslog($this->class_name, __FUNCTION__, 'm11', $verify_data);

            // フォーム値取得
            islog($this->class_name, __FUNCTION__, 'm22');
            $verify_id = $request->input('verify_id');
            $omit = $request->input('omit');
            dslog($this->class_name, __FUNCTION__, 'm6', [
                'verify_id' => $verify_id,
                'omit' => $omit
            ]);

            // 認証開始
            islog($this->class_name, __FUNCTION__, 'm56');
            $ret = $this->verification_service->getVerificationData($request->ip(), $email, $verify_id);
            if (empty($ret)) {
                // 二段階認証画面へ戻る
                eslog($this->class_name, __FUNCTION__, 'm21');
                return back()->with('disp_error_message', dispErrorMessage('m6'));
            }

            // 二段階認証OK
            islog($this->class_name, __FUNCTION__, 'm58');
            $request->session()->regenerate(); // セッション再生成

            // トークン生成
            $token = $request->user()->createToken(getTokenName())->plainTextToken;

            // DBから認証データ削除
            islog($this->class_name, __FUNCTION__, 'm20');
            $ret = $this->verification_service->deleteVerificationData($email);
            if (empty($ret)) {
                // 削除失敗
                // エラーログのみ出力して続行
                eslog($this->class_name, __FUNCTION__, 'm3', [
                    'email' => $email
                ]);
            }

            // ログイン情報を取得設定
            islog($this->class_name, __FUNCTION__, 'm10');
            $login_data = $this->acnt_service->getLoginAcntData($id);
            dslog($this->class_name, __FUNCTION__, 'm12', $login_data);

            // ログイン情報をセッションへ設定
            islog($this->class_name, __FUNCTION__, 'm28');
            $login_info = $this->acnt_service->setLoginAcntData($login_data, getLoginInfo($request), chkIntendedUrl($request));
            setLoginInfo($request, $login_info);
            dslog($this->class_name, __FUNCTION__, 'm8', $login_info);

            // トップ画面へリダイレクト
            // 省略フラグにチェックがあればクッキー設定（期限は一週間）でリダイレクト
            islog($this->class_name, __FUNCTION__, 'm29');
            if (! empty($omit)) {
                // 二段階認証情報を取得
                list($twostep_name, $twostep_value) = getTwostepValue(); 
                // アクセス権限によりリダイレクト先を変更
                // 認証値をクッキー保存、クッキーの有効期限は7日間
                return redirect()->intended(chkCompanyLink($login_data->comp_type, $login_data->comp_no))
                ->cookie($twostep_name, $twostep_value, time() + 604800)
                    ->with('siapi', $token);
            }

            // アクセス権限によりリダイレクト先を変更
            return redirect()->intended(chkCompanyLink($login_data->comp_type, $login_data->comp_no))->with('siapi', $token);
        } catch (Exception $e) {
            // 例外処理
            eslog($this->class_name, __FUNCTION__, 'm5', $e->getMessage());
            abort(500, dispExceptionMessage(500));
        }
    }

    /**
     * 二段階認証コード再取得
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function send(Request $request)
    {
        try {
            islog($this->class_name, __FUNCTION__, 'm1');

            // セッションから認証email取得
            islog($this->class_name, __FUNCTION__, 'm26');
            $verify_data = $request->session()->get($this->verification_session_name);
            dslog($this->class_name, __FUNCTION__, 'm11', $verify_data);

            // 認証コード再発行とメール送信
            $ret = $this->verification_service->upsertVerificationData($request->ip(), $verify_data[1]);
            if (empty($ret)) {
                // 失敗したらエラーメッセージ設定後に二段階認証画面へリダイレクト
                islog($this->class_name, __FUNCTION__, 'm29');
                return redirect()->intended(route('twoverify.index'))->with('disp_error_message', dispErrorMessage('m7'));
            } else {
                // 二段階認証画面へリダイレクト
                islog($this->class_name, __FUNCTION__, 'm29');
                return redirect()->intended(route('twoverify.index'))->with('disp_message', dispInfoMessage('m4'));
            }
        } catch (Exception $e) {
            // 例外処理
            // 二段階認証（メールアドレス）セッションの削除
            islog($this->class_name, __FUNCTION__, 'm25');
            $request->session()->forget($this->verification_session_name);
            eslog($this->class_name, __FUNCTION__, 'm5', $e->getMessage());
            abort(500);
        }
    }
}
