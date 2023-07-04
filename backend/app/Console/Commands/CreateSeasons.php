<?php

namespace App\Console\Commands;


use App\Models\Season;
use App\Models\SeasonTeams;
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
     * TODO: Run the `update-scoreboard` command on the new season
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
                $this->info("Today ({$currentDate}) is in season #{$season->id}");
                $inSeason = true;
                break;
            }
        }

        if (! $inSeason) {
            // Create a new transaction so this is atomic
            DB::beginTransaction();
            $this->info("Today ({$currentDate}) is not in a season. Creating a new season");

            // Before we add the new season, get the latest one
            $latest = DB::table('seasons')
                ->orderBy('end', 'DESC')
                ->select(['id', 'slug'])
                ->first();
            $this->info("Most recent season was ".$latest->slug.". Rolling over teams");
            
            // Create a new season object
            $month = intval(date("m"));
            if ($month < 9) {
                $start = (int)date("Y") - 1;
                $start = $start."-09-01";
                $end = date("Y")."-08-31";
            } else {
                $start = date("Y")."-09-01";
                $end = (int)date("Y") + 1;
                $end = $end."-08-31";
            }
            $slug = substr($start, 2, 2).'-'.substr($end, 2, 2);
            $s = new Season;
            $s->start = $start;
            $s->end  = $end;
            $s->slug = $slug;
            $s->saveOrFail();
            $this->info("New season being created for period of '".$s->slug."'");

            // Get all the active teams from last season and assume they are active this season
            $activeTeams = DB::table('season_teams')
                ->where('season_id', '=', $latest->id)
                ->select(['team_id'])
                ->get();

            $this->info("Adding ".$activeTeams->count()." to the new season");

            foreach($activeTeams as $team) {
                $st = new SeasonTeams;
                $st->team_id = $team->team_id;
                $st->season_id = $s->id;
                $st->saveOrFail();
            }

            // Save the transaction
            DB:commit();
        }
        return 0;
    }
}
