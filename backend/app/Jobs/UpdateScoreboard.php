<?php

namespace App\Jobs;

use App\Helpers\ScoreboardHelper;
use App\Helpers\SeasonHelper;
use App\Models\Scorecard;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class UpdateScoreboard extends Job
{
    use SerializesModels;

    protected Scorecard $scorecard;

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
        if ($seasonId === null) {
            Log::critical(
                "Could not find a season for the data '" .
                $this->scorecard->date_played .
                "'. Scorecard ID = " .
                $this->scorecard->id
            );
            return;
        }

        // Not interested in the return value
        ScoreboardHelper::calculateScoreboard($seasonId);
    }
}
