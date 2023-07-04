<?php

namespace App\Console\Commands;


use App\Models\Season;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'manual:check-database '
                           .'{--a|attempts=5 : How many times to attempt to connect}'
                           .'{--t|timeout=3 : How many seconds to leave before trying again}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check if the database is ready and Lumen can get a connection';

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
        $tries = $this->option('attempts');
        $timeout = $this->option('timeout');

        if ($tries < 1) {
            $this->error("The number of attempts must be greater than or equal to 1.");
            return 1;
        } else if ($timeout < 0) {
            $this->error("Timeout must be greater than 0");
        }

        $connected = false;
        for ($i = 0; $i < $tries; $i++) {
            try {
                DB::connection()->getPdo();
                $connected = true;
                break;
            } catch (\Exception $e) {
                $attemptNumber = $i + 1;
                $this->warn("Failed connecting on attempt {$attemptNumber}");
            }
            sleep($timeout);
        }

        if ($connected) {
            $this->info("Connection to the database was successful.");
            return 0;
        } else {
            $this->error("Connection to the database failed.");
            return 1;
        }
    }
}
