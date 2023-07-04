<?php

namespace App\Console\Commands;


use App\Helpers\ScoreboardHelper;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateScoreboards extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'manual:update-scoreboards';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates the scoreboards for each season in the database';

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
     * @return int Returns 0 on success of 1 on failure
     */
    public function handle()
    {
        $this->info('Beginning update of all league scoreboards.');

        $seasons = DB::table('seasons')->select(['id', 'slug'])->get();
        $this->info('Found '.$seasons->count().' seasons to update.');
        foreach($seasons as $s) {
            $this->info('Starting update of season '.$s->slug);
            if (ScoreboardHelper::calculateScoreboard($s->id)) {
                $this->info('Finished update of season '.$s->slug);
            } else {
                $this->error('Failed update of season '.$s->slug.'. Please check the logs.');
                return 1;
            }
        }

        return 0;
    }
}
