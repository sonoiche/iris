<?php

namespace App\Mail\Client;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendEmailApplicant extends Mailable
{
    use Queueable, SerializesModels;

    public $template;
    public $content;
    
    public function __construct($template, $content)
    {
        $this->template  = $template;
        $this->content   = $content;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->template->title)
            ->view('emails.applicant')
            ->with([
                'template'  => $this->template,
                'content'   => $this->content
            ]);
    }
}
