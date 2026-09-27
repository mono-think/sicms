<?php
namespace App\Http\Controllers;

use App\Services\SiteMapService;
use Illuminate\Http\Request;
use Exception;

class XmlSitemapFrontController extends Controller
{

    protected $sitemap_service;

    protected $class_name;

    /**
     * コンストラクタ
     *
     * @param
     */
    public function __construct(SiteMapService $sitemap_service)
    {
        $this->sitemap_service = $sitemap_service;
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

            // サイトマップ生成情報取得
            $ret = $this->sitemap_service->getSiteMapData();
            $view_param = [
                'form_data' => $ret
            ];

            // ビューへレンダリング
            dslog($this->class_name, __FUNCTION__, 'm4', $view_param);
            islog($this->class_name, __FUNCTION__, 'm24');
            return response()->view('common.sitemap', $view_param)->header('Content-Type', 'text/xml');
        } catch (Exception $e) {
            // 例外処理
            eslog($this->class_name, __FUNCTION__, 'm5', $e->getMessage());
            abort(500, dispExceptionMessage(500));
        }
    }
}
