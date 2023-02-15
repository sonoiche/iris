<?php

namespace App\Jobs\Client;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use App\Mail\Client\SendEmailApplicant;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class SendEmailApplicantJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $applicant;
    protected $template;
    protected $content;
    
    public function __construct($applicant, $template, $content)
    {
        $this->applicant = $applicant;
        $this->template  = $template;
        $this->content   = $content;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->applicant->email)->send(new SendEmailApplicant($this->template, $this->content));
    }
}
