<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

class RootingAuth
{

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // 当該画面のアクセス権限判定
        if (Auth::check()) { // 認証済ユーザーか判定
            //アクセス権限情報取得
            $login_auth_pattern = config('consts.auth_info.login_auth');
            $login_info = getLoginInfo($request);
            dslog('RootingAuth', __FUNCTION__, 'm3', $login_info);
            if (! empty($login_info)) {
                $current_route = Route::currentRouteName(); // カレントルート

                //遷移先の権限チェック
                if (isset($login_auth_pattern[$current_route][$login_info['company_type']]) && (in_array($login_info['acnt_auth_no'], $login_auth_pattern[$current_route][$login_info['company_type']]))) {
                    return $next($request);
                }

                //権限エラー後処理
                //企業一覧指定
                if ($current_route === 'company.index'){
                    if ($login_info['company_type'] === config('consts.auth_info.company_type_customer')){
                        return redirect()->intended(route('company.show',$login_info['company_no']));
                    }
                }

                // 一つ前のURLを取得
                $pre_url = url()->previous();
                if (! empty($pre_url)) {
                    switch ($pre_url) {
                        // ↓以下URLは全てログイン画面へ遷移させる
                        case route('login'):
                        case route('login.auth'):
                        case route('password.index'):
                        case route('password.sendmail'):
                        case route('password.reset'):
                        case route('password.update'):
                        case route('logout.index'):
                            break;
                        default:
                            // ↓一つ前の画面で戻る
                            return redirect($pre_url)->with('disp_error_message', dispErrorMessage('m11'));
                    }
                }
            }
        }

        // ログアウト処理の上ログイン画面へ
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('disp_error_message', dispErrorMessage('m10'));
    }
}
