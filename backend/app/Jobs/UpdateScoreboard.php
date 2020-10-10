<?php

namespace App\Jobs;

use App\Helpers\ScoreboardHelper;
use App\Helpers\SeasonHelper;
use App\Models\Scorecard;
use Illuminate\Queue\SerializesModels;

class UpdateScoreboard extends Job
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
