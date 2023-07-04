<?php

namespace App\Jobs;

use App\Helpers\ScoreboardHelper;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

/**
 * Class UpdateScoreboard
 * @package App\Jobs
 * Recalculates the scoreboards for this season. This job is called whenever a
 * new scorecard is added to the system (inside the route logic
 * App\Http\Controllers\ScorecardController)
 */
class UpdateScoreboard extends Job
{
    use SerializesModels;

    protected int $seasonId;

    /**
     * Create a new job instance.
     *
     * @param int $sid
     */
    public function __construct(int $sid)
    {
        $this->seasonId = $sid;
        Log::debug("New update scoreboard job created.");
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Not interested in the return value
        $returnValue = ScoreboardHelper::calculateScoreboard($this->seasonId);
        if (! $returnValue) {
            Log::critical("Failed to update the scoreboard for season #{$this->seasonId}");
        }
    }
}
