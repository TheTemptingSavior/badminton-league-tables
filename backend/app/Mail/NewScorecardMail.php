<?php

namespace App\Mail;

use App\Models\Scorecard;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewScorecardMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The scorecard that was added.
     *
     * @var \App\Models\Scorecard
     */
    public $scorecard;

    /**
     * The user the email is being sent to
     * 
     * @var \App\Models\User
     */
    public $user;

    /**
     * Create a new message instance.
     *
     * @param  \App\Models\Scorecard  $scorecard
     * @param  \App\Models\User  $user
     * @return void
     */
    public function __construct(Scorecard $scorecard, User $user)
    {
        $this->scorecard = $scorecard;
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from("no-reply@".API_HOST)
            ->markdown('emails.NewScorecard')
            ->with('scorecard', $this->scorecard)
            ->with('body', $this->body);
    }
}