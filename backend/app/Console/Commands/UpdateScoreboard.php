<?php

namespace App\Console\Commands;


use App\Helpers\ScoreboardHelper;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateScoreboard extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'manual:update-scoreboard {season : Slug of the season to update}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates the scoreboard for a specific season';

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
     * @return mixed
     */
    public function handle()
    {
        $this->info('Beginning update scoreboard for season ' . $this->argument('season'));
        $season = DB::table('seasons')
            ->where('slug', '=', $this->argument('season'))
            ->select(['id', 'slug'])
            ->first();

        $this->info('Started update of scoreboard for season ' . $season->slug);
        if (ScoreboardHelper::calculateScoreboard($season->id)) {
            $this->info('Finished update of scoreboard for season ' . $season->slug);
        } else {
            $this->error('Failed update of scoreboard for season ' . $season->slug);
            return 1;
        }
        return 0;
    }
}
