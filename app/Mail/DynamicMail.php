<?php

namespace App\Mail;

use App\Models\EmailTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\HtmlString;

class DynamicMail extends Mailable
{
    use Queueable, SerializesModels;

    public $template;
    public $data;

    public function __construct(EmailTemplate $template, array $data = [])
    {
        $this->template = $template;
        $this->data = $data;
    }

    public function build()
    {
        $content = $this->template->content;

        // Substituir variáveis @{variavel}
        foreach ($this->data as $key => $value) {
            $content = str_replace("@{{$key}}", $value, $content);
        }

        $subject = $this->template->subject;
        foreach ($this->data as $key => $value) {
            $subject = str_replace("@{{$key}}", $value, $subject);
        }

        return $this->subject($subject)
            ->html(view('emails.layout', [
                'slot' => new HtmlString(nl2br($content)),
                'subject' => $subject
            ])->render());
    }
}
