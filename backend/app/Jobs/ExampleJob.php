<?php

namespace App\Jobs;

class ExampleJob extends Job
{
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        echo "\n\nNew job created\n\n";
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        echo "\n\nThis came from the job function\n\n";
    }
}
