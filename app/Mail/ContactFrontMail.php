<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactFrontMail extends Mailable
{
    use Queueable, SerializesModels;

    // メールインスタンス
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
        return $this->from($this->mail_format['contact_mail'], $this->mail_format['contact_name'])
            ->view('emails.contactmail')
            ->subject('[Car Media REVOLT-IS]お問い合わせ')
            ->with([
            'contact_name' => $this->mail_format['contact_name'],
            'contact_mail' => $this->mail_format['contact_mail'],
            'contact_text' => $this->mail_format['contact_text']
        ]);
    }
}
