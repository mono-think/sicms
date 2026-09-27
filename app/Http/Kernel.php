<?php
namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;
use Laravel;

class Kernel extends HttpKernel
{

    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @todo 全てのリクエストで実行される
     * @var array
     */
    protected $middleware = [
        // \App\Http\Middleware\TrustHosts::class, //信頼できるホスト名を返す。現状は特に使い道なし
        // \App\Http\Middleware\TrustProxies::class, //信頼できるプロキシ名を返す。現状は特に使い道なし
        // \Fruitcake\Cors\HandleCors::class, //外部からのリクエストでCORSヘッダーを設定する。現状はconfig/cors.php内でAPIのみに制限している。外部からのリクエストがないので今は使わない
        // \App\Http\Middleware\PreventRequestsDuringMaintenance::class, //メンテナンス中のリクエスト管理
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class, // POSTサイズの検証
        \App\Http\Middleware\TrimStrings::class, // 入力フィールドへの入力値前後の自動トリム処理
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class // 空文字列を自動NULL変換処理
    ];

    /**
     * The application's route middleware groups.
     *
     * @todo 指定グループ毎で実行するミドルウェアをまとめる
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [ // web.php全体で実行される
            \App\Http\Middleware\EncryptCookies::class, // クッキー暗号化
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class, // 指定したクッキーをレスポンスへ設定
            \Illuminate\Session\Middleware\StartSession::class, // セッションの利用
            \Illuminate\View\Middleware\ShareErrorsFromSession::class, // セッションへ保存のエラー情報をViewへ設定
            \App\Http\Middleware\VerifyCsrfToken::class, // CSRFトークンの管理、確認
            \Illuminate\Routing\Middleware\SubstituteBindings::class // 指定 modelのバインド処理
        ],
        'api' => [ // api.php全体で実行される
            'auth:sanctum',
            \App\Http\Middleware\EncryptCookies::class, // クッキー暗号化
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class, // 指定したクッキーをレスポンスへ設定
            \Illuminate\Session\Middleware\StartSession::class, // セッションの利用
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class
        ],
        'sicms-auth' => [
            'auth', // 認証中判定
            \Illuminate\Session\Middleware\AuthenticateSession::class, // 現在ログイン中のデバイスのセッションを保持しつつ、他デバイスのログインセッションを無効化
            /* ここから追加 */
            \App\Http\Middleware\RootingAuth::class, // ログインユーザーの認証画面チェック
            \App\Http\Middleware\RequestParameterToXSS::class // リクエスト内の指定カラムのサニタイズ処理
        ]
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class, // ログインセッションがなければ認証画面（変更可）へ遷移
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class, // Basic認証を設定値で切替
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class, // レスポンスをキャッシュするか指定
        'can' => \Illuminate\Auth\Middleware\Authorize::class, // ログインユーザーが指定のアクションを実行可能か認証
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class, // ログイン中なら指定HOMEディレクトリへ遷移
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class, // パスワード確認
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class, // 署名付きURLの検証（期間限定URLなど）
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class, // 指定時間辺りのリクエスト数を管理
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class // メール認証
    ];
}
