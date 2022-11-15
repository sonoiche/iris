<?php

namespace App\Jobs\Client;

use Illuminate\Bus\Queueable;
use App\Mail\Client\SendInviteEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class SendInviteEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    protected $authuser;
    protected $remarks;
    
    public function __construct($user, $authuser, $remarks)
    {
        $this->user = $user;
        $this->authuser = $authuser;
        $this->remarks = $remarks;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->user->email)->send(new SendInviteEmail($this->user, $this->authuser, $this->remarks));
    }
}
