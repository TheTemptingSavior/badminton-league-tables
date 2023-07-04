<?php

namespace App\Console\Commands;


use App\Models\Season;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CreateSeasons extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:create-seasons';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check if there are new seasons that need to be created';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int Returns 0 on success of 1 otherwise
     */
    public function handle(): int
    {
        // Get the current date
        $currentDate = date("Y-m-d");
        // Assume it is not in season until proven otherwise
        $inSeason = false;

        $seasons = Season::all();
        foreach($seasons as $season) {
            if ($season->start <= $currentDate && $currentDate <= $season->end) {
                $this->info("Today ($currentDate) is in season #$season->id");
                $inSeason = true;
                break;
            }
        }

        if (! $inSeason) {
            // TODO: Calculate the correct season information
            // We need to create a new season object
            $this->info("Today ($currentDate) is not in a season. Creating a new season");
            $s = new Season;
            $s->start = null;
            $s->end  = null;
            $s->slug = null;
            $s->saveOrFail();

            // Get all the active teams from last season and assume they are active this season

        }
        return 0;
    }
}
