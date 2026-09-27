<?php
namespace App\Mail\Manage;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordResetMail extends Mailable
{
    use Queueable, SerializesModels;
    
    //メールインスタンス
    protected $mail_format;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($mail_data)
    {
        $this->mail_format = $mail_data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('manage.emails.password_resetmail')
            ->subject('[SiCMS管理]パスワードリセット確認')
            ->with([
                'password_reset_url' => $this->mail_format['password_reset_url']
        ]);
    }
}
