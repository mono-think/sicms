<?php
/**
 * お問い合わせ処理のServiceクラス
 * メール送信処理
 *
 * @access public
 * @author Seiji Ishikawa（メールアドレス）
 * @copyright Webじゃ軒 All Rights Reserved
 * @package service
 */
namespace App\Services;

use App\Mail\ContactFrontMail;
use App\Mail\ContactFrontMailSendFrom;
use Illuminate\Support\Facades\Mail;

class ContactService
{
    protected $class_name;

    /**
     * コンストラクタ
     *
     * @access public
     *
     */
    public function __construct()
    {
        $this->class_name = get_class($this);
        islog($this->class_name, __FUNCTION__);
    }

    /**
     * メール送信処理
     * メール作成と送信処理
     *
     * @access public
     * @param array $form_data
     * @return array
     * @see ContactService
     * @throws
     * @todo
     */
    public function sendContactMail($form_data)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'form_data' => $form_data
        ]);

        // メール送信
        islog($this->class_name, __FUNCTION__, 'm59');
        // お客様から管理者へ
        Mail::to(env('MAIL_ADMIN'))->send(new ContactFrontMail($form_data));
        // 管理者からお客様へ
        Mail::to($form_data['contact_mail'])->send(new ContactFrontMailSendFrom($form_data));

        dslog($this->class_name, __FUNCTION__, 'm2', true);
        islog($this->class_name, __FUNCTION__, 'm3');
        return true;
    }
}
