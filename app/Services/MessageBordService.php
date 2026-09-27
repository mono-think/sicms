<?php
/**
 * メッセージボードデータ処理のServiceクラス
 * 検索、登録、更新、削除処理
 *
 * @access public
 * @message_bordor Seiji Ishikawa（メールアドレス）
 * @copyright Webじゃ軒 All Rights Reserved
 * @package service
 */
namespace App\Services;

use App\Repositories\MessageBordRepositoryInterface;
use Illuminate\Support\Str;
use Carbon\Carbon;

class MessageBordService
{

    protected $message_bord_repository;

    protected $class_name;

    /**
     * コンストラクタ
     *
     * @access public
     * @param MessageBordRepositoryInterface $message_bord_repository
     *
     */
    public function __construct(MessageBordRepositoryInterface $message_bord_repository)
    {
        $this->message_bord_repository = $message_bord_repository;
        $this->class_name = get_class($this);
        islog($this->class_name, __FUNCTION__);
    }

    /**
     * メッセージボード情報一覧取得
     * 検索条件により全てのメッセージボード情報を取得
     *
     * @access public
     * @param int $start
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     * @see MessageBordService
     * @throws
     * @todo
     */
    public function getMessageBordList($start, $limit)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'start' => $start,
            'limit' => $limit
        ]);

        islog($this->class_name, __FUNCTION__, 'm10');
        // データ取得
        $ret_collection = $this->message_bord_repository->getMessageBordList($start, $limit);

        // Json化
        $ret = $ret_collection->toJson();
        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * メッセージボード情報（返信込み）一覧取得
     * 検索条件により全てのメッセージボード情報を取得
     *
     * @access public
     * @return array
     * @see MessageBordService
     * @throws
     * @todo
     */
    public function getMessageBordReplyAllList()
    {
        islog($this->class_name, __FUNCTION__, 'm1');

        islog($this->class_name, __FUNCTION__, 'm10');
        // データ取得
        $ret_data = $this->message_bord_repository->getMessageBordPagenationList();

        // ヘッダー情報作成
        list ($header_title, $header_description, $header_keywords) = get_site_mb_list();

        // 正規URL作成
        $url = route('front_message_bord.index');

        // リターンパラメータ作成
        islog($this->class_name, __FUNCTION__, 'm13');
        $form_data = [
            'view' => $ret_data,
            'sub_data' => [
                'breadcrumbs' => [], // パンくずリスト
                'title' => $header_title, // タイトルテキスト
                'description' => $header_description, // 紹介テキスト
                'keywords' => $header_keywords, // キーワード
                'url' => $url // 正規URL
            ]
        ];

        dslog($this->class_name, __FUNCTION__, 'm2', $form_data);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $form_data;
    }

    /**
     * メッセージボード情報登録
     * 入力したメッセージボード情報を登録
     *
     * @access public
     * @param array $form_data
     * @param string $ip_address
     * @return Boolean $ret or string $auth_no
     * @see MessageBordService
     * @throws
     * @todo
     */
    public function insertMessageBordData($form_data, $ip_address)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'form_data' => $form_data,
            '$ip_address' => $ip_address
        ]);

        // 登録データを設定
        islog($this->class_name, __FUNCTION__, 'm14');
        $insert_data['message_bord_text'] = $form_data['mb_text']; // メッセージボードテキスト
        $insert_data['message_bord_sender'] = $form_data['mb_name']; // 送信者
        $insert_data['message_bord_ip'] = $ip_address; // IPアドレス
        $insert_data['message_bord_email'] = $form_data['mb_mail']; // メールアドレス
        $insert_data['message_bord_auth_no'] = Str::Random(8); // 認証コード(8桁のランダムな文字列を渡す）
        $insert_data['updated_at'] = Carbon::now(); // 更新日
        dslog($this->class_name, __FUNCTION__, 'm13', $insert_data);

        // 登録
        islog($this->class_name, __FUNCTION__, 'm18');
        $ret = $this->message_bord_repository->insertMessageBordData($insert_data);
        $ret = $ret ? $insert_data['message_bord_auth_no'] : false; // 正常に登録できたら、認証コードを返す。

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * メッセージボード情報更新
     * 指定のメッセージボード情報を更新
     *
     * @access public
     * @param string $auth_no
     * @param int $bord_no
     * @return Boolean $ret;
     * @see MessageBordService
     * @throws
     * @todo
     */
    public function updateMessageBordData($auth_no, $bord_no)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'auth_no' => $auth_no,
            'bord_no' => $bord_no
        ]);

        // 更新データを設定
        islog($this->class_name, __FUNCTION__, 'm15');
        $update_data['end_flg'] = 1; // 解決フラグON
        $update_data['updated_at'] = Carbon::now(); // 更新日
        dslog($this->class_name, __FUNCTION__, 'm14', $update_data);

        // 更新
        islog($this->class_name, __FUNCTION__, 'm19');
        $ret = $this->message_bord_repository->updateMessageBordData($update_data, $auth_no, $bord_no);

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * メッセージボード情報削除
     * 選択した企業情報を削除
     *
     * @access public
     * @param string $auth_no
     * @param int $bord_no
     * @return Boolean $ret;
     * @see MessageBordService
     * @throws
     * @todo
     */
    public function deleteMessageBordData($auth_no, $bord_no)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'auth_no' => $auth_no,
            'bord_no' => $bord_no
        ]);

        islog($this->class_name, __FUNCTION__, 'm20');
        $ret = $this->message_bord_repository->deleteMessageBordData($bord_no, $auth_no);

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }
}
