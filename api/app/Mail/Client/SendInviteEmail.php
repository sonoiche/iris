<?php

namespace App\Mail\Client;

use App\Models\Client\Configuration;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendInviteEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $authuser;
    public $remarks;

    public function __construct($user, $authuser, $remarks)
    {
        $this->user = $user;
        $this->authuser = $authuser;
        $this->remarks = $remarks;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $agency = Configuration::find(1);
        $invite_link = config('app.url')."/auth/register?token=".$this->user->invite_token;
        return $this->subject('Invitation from IRIS Online')
            ->view('emails.invite')
            ->with([
                'user' => $this->user,
                'agency' => $agency,
                'invite_link' => $invite_link,
                'authuser' => $this->authuser,
                'remarks' => $this->remarks
            ]);
    }
}
