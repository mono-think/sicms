<?php
namespace App\Mail\Manage;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TwoStepVerificationMail extends Mailable
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
        return $this->view('manage.emails.twostep_verificationmail')
            ->subject('[SiCMS管理]二段階認証コード')
            ->with([
                'two_step_verification' => $this->mail_format['two_step_verification']
        ]);
    }
}
