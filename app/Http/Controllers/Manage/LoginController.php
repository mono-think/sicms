<?php
/**
 * [管理]ログイン系画面のControllerクラス
 *
 * ログイン系画面の表示、ログイン処理
 *
 * @access public
 * @author Seiji Ishikawa（メールアドレス）
 * @copyright Webじゃ軒 All Rights Reserved
 * @package controller
 */
namespace App\Http\Controllers\Manage;

use App\Services\Manage\AcntService;
use App\Services\Manage\VerificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;

class LoginController extends BaseController
{

    protected $acnt_service;

    protected $verification_service;

    protected $verification_session_name;

    protected $class_name;

    /**
     * コンストラクタ
     *
     * @param AcntService $acnt_service
     * @param VerificationService $verification_service
     */
    public function __construct(AcntService $acnt_service, VerificationService $verification_service)
    {
        $this->verification_service = $verification_service;
        $this->verification_session_name = config('consts.session_info.twostep_auth');
        $this->acnt_service = $acnt_service;
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
     * ログイン認証処理
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function authenticate(Request $request)
    {
        try {
            islog($this->class_name, __FUNCTION__, 'm1');

            // 二段階認証（メールアドレス）セッションの削除
            islog($this->class_name, __FUNCTION__, 'm25');
            $request->session()->forget($this->verification_session_name);

            // フォーム値取得
            islog($this->class_name, __FUNCTION__, 'm22');
            $acnt_id = $request->input('acnt_id');
            $password = $request->input('password');
            $password_confirmation = $request->input('password_confirmation');
            $remember = $request->input('rememberme');
            $credentials = [
                'acnt_id' => $acnt_id,
                'password' => $password,
                'password_confirmation' => $password_confirmation
            ];
            dslog($this->class_name, __FUNCTION__, 'm6', [
                'info' => $credentials,
                'rememberme' => $remember
            ]);

            // バリデート
            $ret = $this->acnt_service->validationtLoginInfo($credentials);
            dslog($this->class_name, __FUNCTION__, 'm2', $ret);
            if ($ret !== true){
                eslog($this->class_name, __FUNCTION__, 'm10');
                return json_encode($ret);
            }

            // ログイン判定
            if (! Auth::attempt($credentials, $remember)) {
                // ログインエラー
                eslog($this->class_name, __FUNCTION__, 'm10');
                return json_encode([
                    'result' => false,
                    'data' => 'ログインに失敗しました。'
                ]);
            } else {
                // 最初のログイン判定OK
                islog($this->class_name, __FUNCTION__, 'm35');

                // DBからアカウント情報を取得
                islog($this->class_name, __FUNCTION__, 'm10');
                $login_data = $this->acnt_service->getLoginAcntData($acnt_id);
                dslog($this->class_name, __FUNCTION__, 'm12', $login_data);

                // 二段階認証情報を取得
                list($twostep_name, $twostep_value) = getTwostepValue();
                // 二段階認証省略チェック
                if ($request->hasCookie($twostep_name) && ($request->cookie($twostep_name) == $twostep_value)) {
                    // 二段階認証省略
                    islog($this->class_name, __FUNCTION__, 'm55');
                    $request->session()->regenerate(); // セッション再生成

                    // トークン生成
                    $token = $request->user()->createToken(getTokenName())->plainTextToken;

                    // ログイン情報をセッションへ設定
                    islog($this->class_name, __FUNCTION__, 'm28');
                    $login_info = $this->acnt_service->setLoginAcntData($login_data, getLoginInfo($request), chkIntendedUrl($request));
                    setLoginInfo($request, $login_info);
                    dslog($this->class_name, __FUNCTION__, 'm8', $login_info);

                    // ログイン結果返却
                    islog($this->class_name, __FUNCTION__, 'm3');
                    return json_encode([
                        'result' => true,
                        'data' => $token
                    ]);
                } else {
                    // 二段階認証開始
                    islog($this->class_name, __FUNCTION__, 'm56');
                    // 二段階認証コードを登録メールアドレスへ送信、認証コードをDB登録
                    $ret = $this->verification_service->upsertVerificationData($request->ip(), $login_data->email);
                    if (! empty($ret)) {
                        // 認証コード送信完了
                        islog($this->class_name, __FUNCTION__, 'm57');

                        // ログインIDとメールアドレスをセッションへ設定
                        islog($this->class_name, __FUNCTION__, 'm28');
                        $verify_info = [
                            $acnt_id,
                            $login_data->email
                        ];
                        $request->session()->put($this->verification_session_name, $verify_info);
                        dslog($this->class_name, __FUNCTION__, 'm8', $verify_info);

                        // ログイン結果返却
                        islog($this->class_name, __FUNCTION__, 'm29');
                        return json_encode([
                            'result' => true,
                            'data' => 'twoverify'
                        ]);
                    }

                    // 有効なメールアドレスが存在しない
                    // メール送信失敗
                    // 二段階認証エラー
                    // ログインエラー
                    eslog($this->class_name, __FUNCTION__, 'm21');

                    // ログアウト処理
                    Auth::guard('web')->logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();
                    return json_encode([
                        'result' => false,
                        'data' => '二段階認証開始に失敗しました。'
                    ]);
                }
            }
        } catch (Exception $e) {
            // 例外処理
            eslog($this->class_name, __FUNCTION__, 'm5', $e->getMessage());
            return json_encode(false);
        }
    }
}