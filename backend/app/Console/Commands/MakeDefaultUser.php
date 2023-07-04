<?php

namespace App\Console\Commands;


use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class MakeDefaultUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:make-default';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checks to ensure the default system user is created';

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
        $u = DB::table('users')->where('username', '=', ADMIN_USERNAME)->first();
        if ($u == null) {
            $this->warn("No default user exists, creating one now");
            $user = new User;
            $user->username = ADMIN_USERNAME;
            $user->password = Hash::make(ADMIN_PASSWORD);
            $user->super_admin = true;
            $user->admin = true;
            $user->saveOrFail();
            $this->info("Default user '".ADMIN_USERNAME."' created");
        } else {
            $this->info("Default system user ('".ADMIN_USERNAME."') exists");
        }
        return 0;
    }
}
