<?php
/**
 * [管理]パスワード系画面のControllerクラス
 *
 * パスワード系画面の表示、発行、メール処理
 *
 * @access public
 * @author Seiji Ishikawa（メールアドレス）
 * @copyright Webじゃ軒 All Rights Reserved
 * @package controller
 */
namespace App\Http\Controllers\Manage;

use App\Http\Requests\Manage\PasswordForgetMailRequest;
use App\Http\Requests\Manage\PasswordUpdateRequest;
use App\Mail\Manage\PasswordResetMail;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Exception;

class PasswordController extends BaseController
{
    protected $class_name;

    /**
     * コンストラクタ
     *
     */
    public function __construct()
    {
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
     * パスワード忘れメール送信完了
     *
     * @param \App\Http\Requests\Manage\PasswordForgetMailRequest $request
     * @return \Illuminate\Http\Response
     */
    public function sendmail(PasswordForgetMailRequest $request)
    {
        try {
            islog($this->class_name, __FUNCTION__, 'm1');

            // フォーム値取得
            islog($this->class_name, __FUNCTION__, 'm22');
            $to_mail = $request->input('email');
            dslog($this->class_name, __FUNCTION__, 'm6', $to_mail);
            if (! empty($to_mail)) {
                // メール送信開始

                islog($this->class_name, __FUNCTION__, 'm37');
                $status = Password::sendResetLink($request->only('email'));

                // メールデータ設定
                // リセットURL
                // $mail_data['password_reset_url'] = route('password.reset');

                // パスワード確認メール送信完了画面
                // Mail::to($to_mail)->send(new PasswordResetMail($mail_data));

                // パスワード確認メールアドレス入力画面

                // ビューパラメータ
                $view_param = [
                    'mail_status' => $status
                ];

                // ビューへレンダリング
                dslog($this->class_name, __FUNCTION__, 'm4', $view_param);
                islog($this->class_name, __FUNCTION__, 'm24');
                return view('manage.password_forget_mail_comp', $view_param);
            }

            // メールアドレス無し、メール送信画面へリダイレクト
            eslog($this->class_name, __FUNCTION__, 'm11');
            islog($this->class_name, __FUNCTION__, 'm29');
            return redirect()->route('password.sendmail')->with('disp_error_message', dispErrorMessage('m8'));
        } catch (Exception $e) {
            // 例外処理
            eslog($this->class_name, __FUNCTION__, 'm5', $e->getMessage());
            abort(500, dispExceptionMessage(500));
        }
    }

    /**
     * パスワードリセット
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function reset(Request $request)
    {
        try {
            islog($this->class_name, __FUNCTION__, 'm1');

            // GET値を取得
            islog($this->class_name, __FUNCTION__, 'm21');
            $get_data['token'] = $request->input('token');
            $get_data['email'] = $request->input('email');
            dslog($this->class_name, __FUNCTION__, 'm3', $get_data);

            // ビューパラメータ
            $view_param = [
                'form_data' => $get_data
            ];

            // ビューへレンダリング
            dslog($this->class_name, __FUNCTION__, 'm4', $view_param);
            islog($this->class_name, __FUNCTION__, 'm24');
            return view('manage.password_reset', $view_param);
        } catch (Exception $e) {
            // 例外処理
            eslog($this->class_name, __FUNCTION__, 'm5', $e->getMessage());
            abort(500, dispExceptionMessage(500));
        }
    }

    /**
     * パスワード更新
     *
     * @param \App\Http\Requests\Manage\PasswordUpdateRequest $request
     * @return \Illuminate\Http\Response
     */
    public function update(PasswordUpdateRequest $request)
    {
        try {
            islog($this->class_name, __FUNCTION__, 'm1');

            islog($this->class_name, __FUNCTION__, 'm38');
            $status = Password::reset($request->only('email', 'password', 'password_confirmation', 'token'), function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            });

            // ビューパラメータ
            $view_param = [
                'update_status' => $status
            ];

            // ビューへレンダリング
            dslog($this->class_name, __FUNCTION__, 'm4', $view_param);
            islog($this->class_name, __FUNCTION__, 'm24');
            return view('manage.password_update', $view_param);
        } catch (Exception $e) {
            // 例外処理
            eslog($this->class_name, __FUNCTION__, 'm5', $e->getMessage());
            abort(500, dispExceptionMessage(500));
        }
    }
}