<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RequestParameterToXSS
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
        // コントローラへ渡す値から、許可されていないHTMLタグを削除する。
        $form_data = $request->all();
        foreach ($form_data as $idx => $param) {
            switch ($idx) {
                case '_token':
                    // 変換NG
                    break;
                case 'input_no':
                    // タグ全削除、サニタイズ後に小文字変換
                    $param = preg_replace('/<("[^"]*"|\'[^\']*\'|[^\'">])*>/', '', $param);
                    $request->merge([
                        $idx => strtolower($param)
                    ]);
                    break;
                case 'input_text':
                    // 許可されていないHTMLタグとCSS、要素を削除
                    $request->merge([
                        $idx => convertHtmlText($param)
                    ]);
                    break;
                default:
                    // その他の要素
                    // 原則として全てのHTMLタグを削除、サニタイズする
                    if (is_array($param)) {
                        $array_param = [];
                        foreach ($param as $value) {
                            $array_param[] = preg_replace('/<("[^"]*"|\'[^\']*\'|[^\'">])*>/', '', $value);
                        }
                        $request->merge([
                            $idx => $array_param
                        ]);
                    } else {
                        $request->merge([
                            $idx => preg_replace('/<("[^"]*"|\'[^\']*\'|[^\'">])*>/', '', $param)
                        ]);
                    }
                    break;
            }
        }
        return $next($request);
    }
}
