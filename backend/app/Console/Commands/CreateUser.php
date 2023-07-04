<?php

namespace App\Console\Commands;


use App\Helpers\ScoreboardHelper;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CreateUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a new user';

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
        $username = $this->ask("Username: ");
        if ($username == null) {
            $this->error("Username cannot be empty");
            return 1;
        }
        $password = $this->ask("Password: ");
        $retyped = $this->ask("Retype Password: ");

        if ($password == null) {
            $this->error("Password cannot be empty");
            return 1;
        } else if ($password != $retyped) {
            $this->error("Passwords do not match");;
            return 1;
        }

        $admin = $this->ask("Admin? (y/N)", "n");

        $user = new User;
        $user->username = $username;
        $user->password = Hash::make($password);
        $user->admin = strtolower($admin) == "y";
        $user->saveOrFail();
        $this->info("User saved to the database");
        return 0;
    }
}
