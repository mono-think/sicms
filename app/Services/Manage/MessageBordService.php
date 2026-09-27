<?php
/**
 * メッセージボード情報処理のServiceクラス
 * 検索、登録、更新、削除処理
 *
 * @access public
 * @author Seiji Ishikawa（メールアドレス）
 * @copyright Webじゃ軒 All Rights Reserved
 * @package service
 */
namespace App\Services\Manage;

use App\Repositories\Manage\MessageBordRepositoryInterface;
use App\Repositories\Manage\MessageReplyBordRepositoryInterface;
use Carbon\Carbon;

class MessageBordService
{

    protected $message_bord_repository;

    protected $message_reply_bord_repository;

    protected $class_name;

    /**
     * コンストラクタ
     *
     * @access public
     * @param MessageBordRepositoryInterface $message_bord_repository
     *
     */
    public function __construct(MessageBordRepositoryInterface $message_bord_repository, MessageReplyBordRepositoryInterface $message_reply_bord_repository)
    {
        $this->message_bord_repository = $message_bord_repository;
        $this->message_reply_bord_repository = $message_reply_bord_repository;
        $this->class_name = get_class($this);
        islog($this->class_name, __FUNCTION__);
    }

    /**
     * メッセージボード情報一覧取得
     * 検索条件により全てのメッセージボード情報を取得
     *
     * @access public
     * @param string $free_word
     * @return array
     * @see MessageBordService
     * @throws
     * @todo
     */
    public function getMessageBordPagenationList($free_word)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'free_word' => $free_word
        ]);

        // 親データ取得
        islog($this->class_name, __FUNCTION__, 'm10');
        $free_word = hscQuotes($free_word); // HTMLエンティティ変換
        $main_data = $this->message_bord_repository->getMessageBordPagenationList($free_word);
        dslog($this->class_name, __FUNCTION__, 'm12', $main_data);
        // メッセージボード番号のみ取得
        $message_bord_no = $main_data->pluck('message_bord_no')->toArray();

        // 子データ取得
        islog($this->class_name, __FUNCTION__, 'm44');
        $sub_data = $this->message_reply_bord_repository->getMessageReplyBordList($message_bord_no);
        dslog($this->class_name, __FUNCTION__, 'm12', $sub_data);

        // 子データがあれば設定
        $rply_data = [];
        foreach ($sub_data as $s_data) {
            $rply_data[$s_data->message_bord_no][] = [
                'input_mr_no' => $s_data->message_reply_bord_no,
                'input_mr_text' => $s_data->message_reply_bord_text,
                'input_mr_date' => convertDateType($s_data->updated_at, 'al'),
                'input_mr_cpname' => $s_data->comp_name,
                'input_mr_cpbname' => $s_data->comp_branch_name
            ];
        }

        $form_data = [
            'm_data' => $main_data,
            'rply_data' => $rply_data
        ];

        dslog($this->class_name, __FUNCTION__, 'm2', $form_data);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $form_data;
    }

    /**
     * メッセージボード返信情報取得
     * メッセージボード管理番号によりメッセージボード返信情報を取得
     *
     * @access public
     * @param int $message_bord_no
     * @return array
     * @see MessageBordService
     * @throws
     * @todo
     */
    public function getMessageReplyBordData($message_reply_bord_no)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'message_reply_bord_no' => $message_reply_bord_no
        ]);

        // データ取得
        islog($this->class_name, __FUNCTION__, 'm10');
        $ret = $this->message_reply_bord_repository->getMessageReplyBordData($message_reply_bord_no);
        $form_data = [];
        if (! empty($ret)) {
            // 配列化
            $form_data['input_mr_no'] = $ret->message_reply_bord_no; // メッセージ返信ボード管理番号
            $form_data['input_no'] = $ret->message_bord_no; // メッセージボード管理番号
            $form_data['input_mr_text'] = $ret->message_reply_bord_text; // メッセージボード返信テキスト
        }

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
     * @param string $company_no
     * @param string $company_branch_no
     * @param string $acnt_no
     * @return Boolean
     * @see MessageBordService
     * @throws
     * @todo
     */
    public function insertMessageReplyBordData($form_data, $company_no, $company_branch_no, $acnt_no)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'form_data' => $form_data,
            'company_no' => $company_no,
            'company_branch_no' => $company_branch_no,
            'acnt_no' => $acnt_no
        ]);

        // 新規登録
        // 登録データを設定
        islog($this->class_name, __FUNCTION__, 'm14');
        $insert_data['message_bord_no'] = $form_data['input_no']; // メッセージボード管理番号
        $insert_data['message_reply_bord_text'] = $form_data['input_mr_text']; // メッセージボード返信テキスト
        $insert_data['comp_no'] = $company_no; // 企業番号
        $insert_data['comp_branch_no'] = $company_branch_no; // 企業支店番号
        $insert_data['updated_at'] = Carbon::now(); // 更新日
        $insert_data['acnt_no'] = $acnt_no; // 更新者NO
        $insert_data['last_acnt_no'] = $acnt_no; // 最終更新者NO
        dslog($this->class_name, __FUNCTION__, 'm13', $insert_data);

        islog($this->class_name, __FUNCTION__, 'm18');
        $ret = $this->message_reply_bord_repository->insertMessageReplyBordData($insert_data);

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * メッセージボード情報更新
     * 入力したメッセージボード情報を更新
     *
     * @access public
     * @param array $form_data
     * @param string $message_bord_no
     * @param string $company_no
     * @param string $company_branch_no
     * @param string $acnt_no
     * @return Boolean
     * @see MessageBordService
     * @throws
     * @todo
     */
    public function updateMessageReplyBordData($form_data, $message_reply_bord_no, $company_no, $company_branch_no, $acnt_no)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'form_data' => $form_data,
            'message_reply_bord_no' => $message_reply_bord_no,
            'company_no' => $company_no,
            'company_branch_no' => $company_branch_no,
            'acnt_no' => $acnt_no
        ]);

        // 既存更新
        // 更新データを設定
        islog($this->class_name, __FUNCTION__, 'm15');
        // フォーム値を取得
        islog($this->class_name, __FUNCTION__, 'm21');
        $update_data['message_reply_bord_text'] = $form_data['input_mr_text']; // メッセージボード返信テキスト
        $update_data['comp_no'] = $company_no; // 企業番号
        $update_data['comp_branch_no'] = $company_branch_no; // 企業支店番号
        $update_data['updated_at'] = Carbon::now(); // 更新日
        $update_data['acnt_no'] = $acnt_no; // 更新者NO
        $update_data['last_acnt_no'] = $acnt_no; // 最終更新者NO
        dslog($this->class_name, __FUNCTION__, 'm14', $update_data);

        islog($this->class_name, __FUNCTION__, 'm19');
        $ret = $this->message_reply_bord_repository->updateMessageReplyBordData($update_data, $message_reply_bord_no);

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * メッセージボード情報削除
     * 選択したメッセージボード情報を削除
     *
     * @access public
     * @param array $message_bord_no
     * @return Boolean
     * @see MessageBordService
     * @throws
     * @todo
     */
    public function deleteMessageBordData($message_bord_no)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'message_bord_no' => $message_bord_no
        ]);

        // 削除開始
        islog($this->class_name, __FUNCTION__, 'm20');
        // 親削除
        $ret = $this->message_bord_repository->deleteMessageBordData($message_bord_no);
        if (! empty($ret)) {
            // 子削除
            $this->message_reply_bord_repository->deleteMessageReplyBordData(null, $message_bord_no);
        }

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * メッセージボード返信情報削除
     * 選択したメッセージボード返信情報を削除
     *
     * @access public
     * @param array $message_reply_bord_no
     * @return Boolean
     * @see MessageBordService
     * @throws
     * @todo
     */
    public function deleteMessageReplyBordData($message_reply_bord_no)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            '$message_reply_bord_no' => $message_reply_bord_no
        ]);

        // パラメータ分割
        islog($this->class_name, __FUNCTION__, 'm16');
        $message_bord_no = [];
        $reply_bord_no = [];
        foreach ($message_reply_bord_no as $reply_data) {
            list ($message_bord_no[], $reply_bord_no[]) = explode(':', $reply_data);
        }
        $message_bord_no = array_unique($message_bord_no);
        dslog($this->class_name, __FUNCTION__, 'm20', [
            'message_bord_no' => $message_bord_no,
            'message_reply_bord_no' => $reply_bord_no
        ]);

        // 削除開始
        islog($this->class_name, __FUNCTION__, 'm20');
        $ret = $this->message_reply_bord_repository->deleteMessageReplyBordData($reply_bord_no, $message_bord_no);

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }
}
