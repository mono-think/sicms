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
namespace App\Services\Manage;

use App\Repositories\Manage\EventRepositoryInterface;
use Carbon\Carbon;

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
     * イベント情報一覧ページネーション取得
     * 検索条件により全てのイベント情報を取得
     *
     * @access public
     * @param string $free_word
     * @return \Illuminate\Database\Eloquent\Collection
     * @see EventService
     * @throws
     * @todo
     */
    public function getEventPagenationList($free_word = null)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'free_word' => $free_word
        ]);

        // データ取得
        islog($this->class_name, __FUNCTION__, 'm10');
        $free_word = hscQuotes($free_word); //HTMLエンティティ変換
        $ret = $this->event_repository->getEventPagenationList($free_word);

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
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
        $ret = $this->event_repository->getEventData($event_no);
        $form_data = [];
        if (! empty($ret)) {
            // 配列化
            $form_data['input_no'] = $ret->event_mgr_no; // イベント管理番号
            $form_data['input_name'] = $ret->event_name; // イベントタイトル
            $form_data['input_description'] = $ret->event_description; // イベント紹介
            $form_data['input_date'] = convertDateType($ret->event_start_date); // イベント開催日
            $form_data['input_stage'] = $ret->event_stage; // イベント開催場所
            $form_data['input_weather'] = $ret->event_weather_type; // お天気種別
            $form_data['input_organizer'] = $ret->event_organizer; // イベント主催者
            $form_data['input_text'] = convertHtmlText($ret->event_text); // イベントテキスト
            $form_data['input_image_no'] = (int)$ret->event_image_no; // 製品画像番号
            $form_data['input_image_name'] = $ret->image_name; // 製品画像名称
            $form_data['input_image_folder'] = $ret->image_folder; // 製品画像フォルダ
            $form_data['input_image_type'] = $ret->image_type; // 製品画像種別
            $form_data['input_image_alt'] = $ret->image_alt; // 製品画像代替テキスト
            $form_data['input_image_text'] = $ret->image_text; // 製品画像キャプション
            $form_data['input_image_file'] = getImagePath($ret->image_folder, $ret->image_name, $ret->image_type); // imgタグのsrcパス
            $form_data['input_search_tag'] = $ret->search_tag; // タグ
            $form_data['input_release_start'] = convertDateType($ret->release_start); // 掲載開始
            $form_data['input_release_end'] = convertDateType($ret->release_end); // 掲載終了
        }

        dslog($this->class_name, __FUNCTION__, 'm2', $form_data);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $form_data;
    }

    /**
     * イベント情報登録
     * 入力したイベント情報を登録
     *
     * @access public
     * @param array $form_data
     * @param string $acnt_no
     * @return Boolean
     * @see EventService
     * @throws
     * @todo
     */
    public function insertEventData($form_data, $acnt_no)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'form_data' => $form_data,
            'acnt_no' => $acnt_no
        ]);

        // 新規登録
        // 登録データを設定
        islog($this->class_name, __FUNCTION__, 'm14');
        $insert_data['event_name'] = $form_data['input_name']; // イベントタイトル
        $insert_data['event_description'] = $form_data['input_description']; // イベント紹介
        $insert_data['event_start_date'] = $form_data['input_date']; // イベント開催日
        $insert_data['event_stage'] = $form_data['input_stage']; // イベント開催場所
        $insert_data['event_weather_type'] = $form_data['input_weather']; // お天気種別
        $insert_data['event_organizer'] = $form_data['input_organizer']; // イベント主催者
        $insert_data['event_text'] = convertHtmlText($form_data['input_text']); // イベントレポート
        $insert_data['event_image_no'] = (int)$form_data['input_image_no']; // イベント画像番号
        $insert_data['search_tag'] = $form_data['input_search_tag']; // タグ
        $insert_data['release_start'] = $form_data['input_release_start']; // 掲載開始
        $insert_data['release_end'] = $form_data['input_release_end']; // 掲載終了
        $insert_data['created_at'] = Carbon::now(); // 登録日
        $insert_data['updated_at'] = Carbon::now(); // 更新日
        $insert_data['last_acnt_no'] = $acnt_no; // 最終更新者NO
        dslog($this->class_name, __FUNCTION__, 'm13', $insert_data);

        islog($this->class_name, __FUNCTION__, 'm18');
        $ret = $this->event_repository->insertEventData($insert_data);

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * イベント情報更新
     * 入力したイベント情報を更新
     *
     * @access public
     * @param array $form_data
     * @param int $input_no
     * @param string $acnt_no
     * @return Boolean
     * @see EventService
     * @throws
     * @todo
     */
    public function updateEventData($form_data, $input_no, $acnt_no)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'form_data' => $form_data,
            'input_no' => $input_no,
            'acnt_no' => $acnt_no
        ]);

        // 既存更新
        // 更新データを設定
        islog($this->class_name, __FUNCTION__, 'm15');
        $update_data['event_name'] = $form_data['input_name']; // イベントタイトル
        $update_data['event_description'] = $form_data['input_description']; // イベント紹介
        $update_data['event_start_date'] = $form_data['input_date']; // イベント開催日
        $update_data['event_stage'] = $form_data['input_stage']; // イベント開催場所
        $update_data['event_weather_type'] = $form_data['input_weather']; // お天気種別
        $update_data['event_organizer'] = $form_data['input_organizer']; // イベント主催者
        $update_data['event_text'] = convertHtmlText($form_data['input_text']); // イベントレポート
        $update_data['event_image_no'] = (int)$form_data['input_image_no']; // イベント画像番号
        $update_data['search_tag'] = $form_data['input_search_tag']; // タグ
        $update_data['release_start'] = $form_data['input_release_start']; // 掲載開始
        $update_data['release_end'] = $form_data['input_release_end']; // 掲載終了
        $update_data['updated_at'] = Carbon::now(); // 更新日
        $update_data['last_acnt_no'] = $acnt_no; // 最終更新者NO
        dslog($this->class_name, __FUNCTION__, 'm14', $update_data);

        islog($this->class_name, __FUNCTION__, 'm19');
        $ret = $this->event_repository->updateEventData($update_data, $input_no);

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * イベント情報削除
     * 選択したイベント情報を削除
     *
     * @access public
     * @param array $event_no
     * @return Boolean
     * @see EventService
     * @throws
     * @todo
     */
    public function deleteEventData($event_no)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'event_no' => $event_no
        ]);

        // 削除開始
        islog($this->class_name, __FUNCTION__, 'm20');
        $ret = $this->event_repository->deleteEventData($event_no);

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }
}
