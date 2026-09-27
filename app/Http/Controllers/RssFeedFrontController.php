<?php
namespace App\Http\Controllers;

use App\Services\RssFeedService;
use Illuminate\Http\Request;
use Exception;

class RssFeedFrontController extends Controller
{

    protected $rss_service;

    protected $class_name;

    /**
     * コンストラクタ
     *
     * @param
     */
    public function __construct(RssFeedService $rss_service)
    {
        $this->rss_service = $rss_service;
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
        try {
            islog($this->class_name, __FUNCTION__, 'm1');

            // RSS生成情報取得
            $ret = $this->rss_service->getRssData();
            $view_param = [
                'form_data' => $ret
            ];

            // ビューへレンダリング
            dslog($this->class_name, __FUNCTION__, 'm4', $view_param);
            islog($this->class_name, __FUNCTION__, 'm24');
            return response()->view('common.rssfeed', $view_param)->header('Content-Type', 'text/xml');
        } catch (Exception $e) {
            // 例外処理
            eslog($this->class_name, __FUNCTION__, 'm5', $e->getMessage());
            abort(500, dispExceptionMessage(500));
        }
    }
}
