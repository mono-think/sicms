<?php
/**
 * イベント情報処理のServiceクラス
 * 検索、登録、更新、削除処理
 *
 * @access public
 * @author Seiji Ishikawa（メールアドレス）
 * @copyright Webじゃ軒 All Rights Reserved
 * @package service
 */
namespace App\Services;

use App\Repositories\EventRepositoryInterface;

class EventService
{

    protected $event_repository;

    protected $class_name;

    /**
     * コンストラクタ
     *
     * @access public
     * @param EventRepositoryInterface $event_repository
     *
     */
    public function __construct(EventRepositoryInterface $event_repository)
    {
        $this->event_repository = $event_repository;
        $this->class_name = get_class($this);
        islog($this->class_name, __FUNCTION__);
    }

    /**
     * イベント情報一覧取得
     * 検索条件により全てのイベント情報を取得
     *
     * @access public
     * @param string $search_tag
     * @return array
     * @see EventService
     * @throws
     * @todo
     */
    public function getEventPagenationList($search_tag)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'tag' => $search_tag
        ]);

        // データ取得
        islog($this->class_name, __FUNCTION__, 'm10');
        $ret_collection = $this->event_repository->getEventPagenationList($search_tag);

        // ヘッダー情報作成
        list ($header_title, $header_description, $header_keywords) = getSiteEventList();

        // 正規URL作成
        $url = route('front_event.index');

        // データが取得できたら、ヘッダー情報作成
        // ヘッダー画像
        $ret_obj = $ret_collection->first();
        $image_no = isset($ret_obj->event_image_no) ? $ret_obj->event_image_no : null; // 画像番号
        $image_folder = isset($ret_obj->image_folder) ? $ret_obj->image_folder : null;
        $image_name = isset($ret_obj->image_name) ? $ret_obj->image_name : null;
        $image_type = isset($ret_obj->image_type) ? $ret_obj->image_type : null;
        $image_path = getImagePath($image_folder, $image_name, $image_type); // ヘッダー画像パス
        $image_width = 0; // ヘッダー画像幅
        $image_height = 0; // ヘッダー画像高
        if (! empty($ret_obj->image_name) && ! empty($ret_obj->image_type)) {
            $image_local_path = getUploadImageFullPath($ret_obj->image_folder, $ret_obj->image_name, $ret_obj->image_type); // ローカル画像パス
            if (file_exists($image_local_path)){
                list ($image_width, $image_height) = getimagesize($image_local_path);
            }
        }

        // リターンパラメータ作成
        islog($this->class_name, __FUNCTION__, 'm13');
        $form_data = [
            'view' => $ret_collection,
            'sub_data' => [
                'breadcrumbs' => [], // パンくずリスト
                'nav_event' => 'active', // ナビメニュー表示用
                'title' => $header_title, // タイトルテキスト
                'description' => $header_description, // 紹介テキスト
                'keywords' => $header_keywords, // キーワード
                'url' => $url, // 正規URL
                'image_no' => $image_no,
                'image_path' => $image_path, // 画像パス
                'image_width' => $image_width, // 画像幅
                'image_height' => $image_height // 画像高
            ]
        ];

        dslog($this->class_name, __FUNCTION__, 'm2', $form_data);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $form_data;
    }

    /**
     * イベント情報取得
     * イベント管理番号によりイベント情報を取得
     *
     * @access public
     * @param string $event_no
     * @return array
     * @see EventService
     * @throws
     * @todo
     */
    public function getEventData($event_no)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'event_no' => $event_no
        ]);

        // データ取得
        islog($this->class_name, __FUNCTION__, 'm10');
        $ret_collection = $this->event_repository->getEventData($event_no);

        // 正規URL作成
        $url = route('front_event.show', $event_no);

        // データが取得できたら、パンくずデータとヘッダ情報も設定
        $breadcrumbs_data = [];
        $header_title = "";
        $header_description = "";
        $header_keywords = "";
        $image_no = null;
        $image_path = null; // ヘッダー画像パス
        $image_width = 0; // ヘッダー画像幅
        $image_height = 0; // ヘッダー画像高

        if (! is_null($ret_collection)) {

            // パンくずデータ作成
            islog($this->class_name, __FUNCTION__, 'm11');
            $breadcrumbs_data = [
                'event' => $ret_collection->event_mgr_no,
                'event_name' => $ret_collection->event_name
            ];

            // ヘッダー情報作成
            islog($this->class_name, __FUNCTION__, 'm34');
            $header_title = $ret_collection->event_name; // タイトル
            $header_description = $ret_collection->event_description; // 紹介
            $header_keywords = $ret_collection->search_tag; // タグ;

            // ヘッダー画像
            $image_no = $ret_collection->event_image_no; // 画像番号
            $image_path = getImagePath($ret_collection->image_folder, $ret_collection->image_name, $ret_collection->image_type); // 画像パス
            if (! empty($ret_collection->image_name) && ! empty($ret_collection->image_type)) {
                $image_local_path = getUploadImageFullPath($ret_collection->image_folder, $ret_collection->image_name, $ret_collection->image_type); // ローカル画像パス
                if (file_exists($image_local_path)){
                    list ($image_width, $image_height) = getimagesize($image_local_path);
                }
            }
        }

        $form_data = [
            'view' => $ret_collection,
            'sub_data' => [
                'breadcrumbs' => $breadcrumbs_data, // パンくずリスト
                'nav_event' => 'active', // ナビメニュー表示用
                'title' => $header_title, // タイトルテキスト
                'description' => $header_description, // 紹介テキスト
                'keywords' => $header_keywords, // キーワード
                'url' => $url, // 正規URL
                'image_no' => $image_no, // 画像番号
                'image_path' => $image_path, // 画像パス
                'image_width' => $image_width, // 画像幅
                'image_height' => $image_height // 画像高
            ]
        ];

        dslog($this->class_name, __FUNCTION__, 'm2', $form_data);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $form_data;
    }
}
