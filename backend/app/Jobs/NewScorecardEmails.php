<?php

namespace App\Jobs;

use App\Mail\NewScorecardMail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class NewScorecardEmails extends Job
{

    /**
     * The scorecard that has just been added
     * 
     * @var App\Models\Scorecard
     */
    public $scorecard;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($scorecard)
    {
        $this->scorecard = $scorecard;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach(User::all() as $user) {
            Mail::to($user->email)->send(
                new NewScorecardMail($this->scorecard, $user)
            );
        }   
    }
}
