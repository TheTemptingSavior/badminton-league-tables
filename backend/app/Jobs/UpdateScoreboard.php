<?php

namespace App\Jobs;

use App\Helpers\ScoreboardHelper;
use App\Helpers\SeasonHelper;
use App\Models\Scorecard;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateScoreboard extends Job implements SelfHandling, ShouldQueue
{
    use SerializesModels;

    protected $scorecard;

    /**
     * Create a new job instance.
     *
     * @param Scorecard $scorecard
     */
    public function __construct(Scorecard $scorecard)
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
        // Find the season instance
        $seasonId = SeasonHelper::getSeasonFromDate($this->scorecard->date_played);

        // Not interested in the return value

        ScoreboardHelper::calculateScoreboard($seasonId);
    }
}
