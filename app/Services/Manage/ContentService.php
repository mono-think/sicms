<?php
/**
 * コンテンツ情報処理のServiceクラス
 * 検索、登録、更新、削除処理
 *
 * @access public
 * @author Seiji Ishikawa（メールアドレス）
 * @copyright Webじゃ軒 All Rights Reserved
 * @package service
 */
namespace App\Services\Manage;

use App\Repositories\Manage\ContentRepositoryInterface;
use Carbon\Carbon;

class ContentService
{

    protected $content_repository;

    protected $class_name;

    /**
     * コンストラクタ
     *
     * @access public
     * @param ContentRepositoryInterface $content_repository
     *
     */
    public function __construct(ContentRepositoryInterface $content_repository)
    {
        $this->content_repository = $content_repository;
        $this->class_name = get_class($this);
        islog($this->class_name, __FUNCTION__);
    }

    /**
     * コンテンツ情報一覧ページネーション取得
     * 検索条件により全てのコンテンツ情報を取得
     *
     * @access public
     * @param string $free_word
     * @return \Illuminate\Database\Eloquent\Collection
     * @see ContentService
     * @throws
     * @todo
     */
    public function getContentPagenationList($free_word = null)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'free_word' => $free_word
        ]);

        // データ取得
        islog($this->class_name, __FUNCTION__, 'm10');
        $free_word = hscQuotes($free_word); //HTMLエンティティ変換
        $ret = $this->content_repository->getContentPagenationList($free_word);

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * コンテンツ情報取得
     * コンテンツ管理番号によりコンテンツ情報を取得
     *
     * @access public
     * @param int $content_no
     * @return array
     * @see ContentService
     * @throws
     * @todo
     */
    public function getContentData($content_no)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'content_no' => $content_no
        ]);

        // データ取得
        islog($this->class_name, __FUNCTION__, 'm10');
        $ret = $this->content_repository->getContentData($content_no);
        $form_data = [];
        if (! empty($ret)) {
            // 配列化
            $form_data['input_no'] = $ret->contents_mgr_no; // コンテンツ管理番号
            $form_data['input_type'] = $ret->contents_type; // コンテンツ種別
            $form_data['input_name'] = $ret->contents_title; // コンテンツタイトル
            $form_data['input_description'] = $ret->contents_description; // コンテンツ紹介
            $form_data['input_text'] = convertHtmlText($ret->contents_text); // コンテンツテキスト
            $form_data['input_image_no'] = (int)$ret->contents_image_no; // 製品画像番号
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
     * コンテンツ情報登録
     * 入力したコンテンツ情報を登録
     *
     * @access public
     * @param array $form_data
     * @param string $acnt_no
     * @return Boolean
     * @see ContentService
     * @throws
     * @todo
     */
    public function insertContentData($form_data, $acnt_no)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'form_data' => $form_data,
            'acnt_no' => $acnt_no
        ]);

        // 新規登録
        // 登録データを設定
        islog($this->class_name, __FUNCTION__, 'm14');
        $insert_data['contents_type'] = $form_data['input_type']; // コンテンツ種別
        $insert_data['contents_title'] = $form_data['input_name']; // コンテンツタイトル
        $insert_data['contents_description'] = $form_data['input_description']; // コンテンツ紹介
        $insert_data['contents_text'] = convertHtmlText($form_data['input_text']); // コンテンツテキスト
        $insert_data['contents_image_no'] = (int)$form_data['input_image_no']; // イベント画像番号
        $insert_data['search_tag'] = $form_data['input_search_tag']; // タグ
        $insert_data['release_start'] = $form_data['input_release_start']; // 掲載開始
        $insert_data['release_end'] = $form_data['input_release_end']; // 掲載終了
        $insert_data['created_at'] = Carbon::now(); // 登録日
        $insert_data['updated_at'] = Carbon::now(); // 更新日
        $insert_data['last_acnt_no'] = $acnt_no; // 最終更新者NO
        dslog($this->class_name, __FUNCTION__, 'm13', $insert_data);

        islog($this->class_name, __FUNCTION__, 'm18');
        $ret = $this->content_repository->insertContentData($insert_data);

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * コンテンツ情報更新
     * 入力したコンテンツ情報を更新
     *
     * @access public
     * @param array $form_data
     * @param int $input_no
     * @param string $acnt_no
     * @return Boolean
     * @see ContentService
     * @throws
     * @todo
     */
    public function updateContentData($form_data, $input_no, $acnt_no)
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
        $update_data['contents_type'] = $form_data['input_type']; // コンテンツ種別
        $update_data['contents_title'] = $form_data['input_name']; // コンテンツタイトル
        $update_data['contents_description'] = $form_data['input_description']; // コンテンツ紹介
        $update_data['contents_text'] = convertHtmlText($form_data['input_text']); // コンテンツテキスト
        $update_data['contents_image_no'] = (int)$form_data['input_image_no']; // イベント画像番号
        $update_data['search_tag'] = $form_data['input_search_tag']; // タグ
        $update_data['release_start'] = $form_data['input_release_start']; // 掲載開始
        $update_data['release_end'] = $form_data['input_release_end']; // 掲載終了
        $update_data['updated_at'] = Carbon::now(); // 更新日
        $update_data['last_acnt_no'] = $acnt_no; // 最終更新者NO
        dslog($this->class_name, __FUNCTION__, 'm14', $update_data);

        islog($this->class_name, __FUNCTION__, 'm19');
        $ret = $this->content_repository->updateContentData($update_data, $input_no);

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * コンテンツ情報削除
     * 選択したコンテンツ情報を削除
     *
     * @access public
     * @param array $content_no
     * @return Boolean $ret;
     * @see ContentService
     * @throws
     * @todo
     */
    public function deleteContentData($content_no)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'content_no' => $content_no
        ]);

        // 削除開始
        islog($this->class_name, __FUNCTION__, 'm20');
        $ret = $this->content_repository->deleteContentData($content_no);

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }
}
