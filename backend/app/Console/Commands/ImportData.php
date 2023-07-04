<?php

namespace App\Console\Commands;


use App\Helpers\ScoreboardHelper;
use App\Models\Scorecard;
use App\Models\Season;
use App\Models\SeasonTeams;
use App\Models\Team;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ImportData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import {--d|directory=/import} {--sql : Data is an SQL dump} {--csv : Data is CSV}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports a set of data into the database';

    /**
     * Defines the data type to import
     * @var string
     */
    private $dataType = 'csv';

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
        if (! $this->checkFlags()) {
            $this->error("Incorrect flags. Must be EITHER --csv or --sql");
            return 1;
        }
        if (! $this->directoryExists()) {
            $this->error("Import directory does not exist. Cannot continue.");
            return 1;
        }
        $this->info("Importing data as '{$this->dataType}'");

        if ($this->dataType == 'csv') {
            return $this->importAsCsv();
        } else if ($this->dataType == 'sql') {
            return $this->importAsSql();
        } else {
            $this->error("Unknown import type '{$this->dataType}'");
            return 1;
        }
    }

    private function importAsCsv()
    {
        $directory = $this->option('directory');
        $files = Array(
            'teams.csv',
            'seasons.csv',
            'season_teams.csv',
            'scorecards.csv',
        );
        foreach($files as $file) {
            if (! is_file($directory.DIRECTORY_SEPARATOR.$file)) {
                $this->error("Expected to find a file called '{$file}' but didn't");
                return 1;
            }
        }

        DB::beginTransaction();
        // Process teams.csv
        $this->info("Loading data from file 'teams.csv'");
        $f = fopen($directory.DIRECTORY_SEPARATOR.'teams.csv', 'r');
        while (! feof($f)) {
            $row = fgetcsv($f);
            if (gettype($row) != "array") {
                continue;
            }
            $team = new Team;
            $team->id = $row[0];
            $team->name = $row[1];
            $team->slug = $row[2];
            try {
                $team->saveOrFail();
            } catch (\Exception $e) {
                $this->error("Failed saving team to the database: {$team->name}");
                $this->info($e);
                DB::rollBack();
                return 1;
            }
        }
        fclose($f);

        // Process seasons.csv
        $this->info('Loading data from file \'seasons.csv\'');
        $f = fopen($directory.DIRECTORY_SEPARATOR.'seasons.csv', 'r');
        while (! feof($f)) {
            $row = fgetcsv($f);
            if (gettype($row) != "array") {
                continue;
            }

            $season = new Season;
            $season->id = $row[0];
            $season->start = $row[1];
            $season->end = $row[2];
            $season->slug = $row[3];
            try {
                $season->saveOrFail();
            } catch (\Exception $e) {
                $this->error("Failed saving season to the database: {$season->slug}");
                $this->info($e);
                DB::rollBack();
                return 1;
            }
        }
        fclose($f);

        // Process seasons.csv
        $this->info('Loading data from file \'season_teams.csv\'');
        $f = fopen($directory.DIRECTORY_SEPARATOR.'season_teams.csv', 'r');
        while (! feof($f)) {
            $row = fgetcsv($f);
            if (gettype($row) != "array") {
                continue;
            }

            $seasonTeam = new SeasonTeams;
            $seasonTeam->season_id = $row[0];
            $seasonTeam->team_id = $row[1];
            try {
                $seasonTeam->saveOrFail();
            } catch (\Exception $e) {
                $this->error("Failed saving season_team to the database: {$seasonTeam->season_id} and {$seasonTeam->team_id}");
                $this->info($e);
                DB::rollBack();
                return 1;
            }
        }
        fclose($f);

        // Process scorecards.csv
        $this->info('Loading data from file \'scorecards.csv\'');
        $f = fopen($directory.DIRECTORY_SEPARATOR.'scorecards.csv', 'r');
        while (! feof($f)) {
            $row = fgetcsv($f);
            if (gettype($row) != "array") {
                continue;
            }
            $scorecard = new Scorecard;
            $scorecard->home_team = $row[0];
            $scorecard->away_team = $row[1];
            $scorecard->date_played = $row[2];
            $scorecard->home_points = $row[3];
            $scorecard->away_points = $row[4];
            $scorecard->home_player_1 = (strlen($row[5]) == 0) ? null : $row[5];
            $scorecard->home_player_2 = (strlen($row[6]) == 0) ? null : $row[6];
            $scorecard->home_player_3 = (strlen($row[7]) == 0) ? null : $row[7];
            $scorecard->home_player_4 = (strlen($row[8]) == 0) ? null : $row[8];
            $scorecard->home_player_5 = (strlen($row[9]) == 0) ? null : $row[9];
            $scorecard->home_player_6 = (strlen($row[10]) == 0) ? null : $row[10];
            $scorecard->away_player_1 = (strlen($row[11]) == 0) ? null : $row[11];
            $scorecard->away_player_2 = (strlen($row[12]) == 0) ? null : $row[12];
            $scorecard->away_player_3 = (strlen($row[13]) == 0) ? null : $row[13];
            $scorecard->away_player_4 = (strlen($row[14]) == 0) ? null : $row[14];
            $scorecard->away_player_5 = (strlen($row[15]) == 0) ? null : $row[15];
            $scorecard->away_player_6 = (strlen($row[16]) == 0) ? null : $row[16];
            $scorecard->game_one_v_one_home_one = ($row[17] == "NULL") ? null : $row[17];
            $scorecard->game_one_v_one_away_one = ($row[18] == "NULL") ? null : $row[18];
            $scorecard->game_one_v_one_home_two = ($row[19] == "NULL") ? null : $row[19];
            $scorecard->game_one_v_one_away_two = ($row[20] == "NULL") ? null : $row[20];
            $scorecard->game_one_v_one_home_three = ($row[21] == "NULL") ? null : $row[21];
            $scorecard->game_one_v_one_away_three = ($row[22] == "NULL") ? null : $row[22];
            $scorecard->game_one_v_two_home_one = ($row[23] == "NULL") ? null : $row[23];
            $scorecard->game_one_v_two_away_one = ($row[24] == "NULL") ? null : $row[24];
            $scorecard->game_one_v_two_home_two = ($row[25] == "NULL") ? null : $row[25];
            $scorecard->game_one_v_two_away_two = ($row[26] == "NULL") ? null : $row[26];
            $scorecard->game_one_v_two_home_three = ($row[27] == "NULL") ? null : $row[27];
            $scorecard->game_one_v_two_away_three = ($row[28] == "NULL") ? null : $row[28];
            $scorecard->game_one_v_three_home_one = ($row[29] == "NULL") ? null : $row[29];
            $scorecard->game_one_v_three_away_one = ($row[30] == "NULL") ? null : $row[30];
            $scorecard->game_one_v_three_home_two = ($row[31] == "NULL") ? null : $row[31];
            $scorecard->game_one_v_three_away_two = ($row[32] == "NULL") ? null : $row[32];
            $scorecard->game_one_v_three_home_three = ($row[33] == "NULL") ? null : $row[33];
            $scorecard->game_one_v_three_away_three = ($row[34] == "NULL") ? null : $row[34];

            $scorecard->game_two_v_one_home_one = ($row[35] == "NULL") ? null : $row[35];
            $scorecard->game_two_v_one_away_one = ($row[36] == "NULL") ? null : $row[36];
            $scorecard->game_two_v_one_home_two = ($row[37] == "NULL") ? null : $row[37];
            $scorecard->game_two_v_one_away_two = ($row[38] == "NULL") ? null : $row[38];
            $scorecard->game_two_v_one_home_three = ($row[39] == "NULL") ? null : $row[39];
            $scorecard->game_two_v_one_away_three = ($row[40] == "NULL") ? null : $row[40];
            $scorecard->game_two_v_two_home_one = ($row[41] == "NULL") ? null : $row[41];
            $scorecard->game_two_v_two_away_one = ($row[42] == "NULL") ? null : $row[42];
            $scorecard->game_two_v_two_home_two = ($row[43] == "NULL") ? null : $row[43];
            $scorecard->game_two_v_two_away_two = ($row[44] == "NULL") ? null : $row[44];
            $scorecard->game_two_v_two_home_three = ($row[45] == "NULL") ? null : $row[45];
            $scorecard->game_two_v_two_away_three = ($row[46] == "NULL") ? null : $row[46];
            $scorecard->game_two_v_three_home_one = ($row[47] == "NULL") ? null : $row[47];
            $scorecard->game_two_v_three_away_one = ($row[48] == "NULL") ? null : $row[48];
            $scorecard->game_two_v_three_home_two = ($row[49] == "NULL") ? null : $row[49];
            $scorecard->game_two_v_three_away_two = ($row[50] == "NULL") ? null : $row[50];
            $scorecard->game_two_v_three_home_three = ($row[51] == "NULL") ? null : $row[51];
            $scorecard->game_two_v_three_away_three = ($row[52] == "NULL") ? null : $row[52];

            $scorecard->game_three_v_one_home_one = ($row[53] == "NULL") ? null : $row[53];
            $scorecard->game_three_v_one_away_one = ($row[54] == "NULL") ? null : $row[54];
            $scorecard->game_three_v_one_home_two = ($row[55] == "NULL") ? null : $row[55];
            $scorecard->game_three_v_one_away_two = ($row[56] == "NULL") ? null : $row[56];
            $scorecard->game_three_v_one_home_three = ($row[57] == "NULL") ? null : $row[57];
            $scorecard->game_three_v_one_away_three = ($row[58] == "NULL") ? null : $row[58];
            $scorecard->game_three_v_two_home_one = ($row[59] == "NULL") ? null : $row[59];
            $scorecard->game_three_v_two_away_one = ($row[60] == "NULL") ? null : $row[60];
            $scorecard->game_three_v_two_home_two = ($row[61] == "NULL") ? null : $row[61];
            $scorecard->game_three_v_two_away_two = ($row[62] == "NULL") ? null : $row[62];
            $scorecard->game_three_v_two_home_three = ($row[63] == "NULL") ? null : $row[63];
            $scorecard->game_three_v_two_away_three = ($row[64] == "NULL") ? null : $row[64];
            $scorecard->game_three_v_three_home_one = ($row[65] == "NULL") ? null : $row[65];
            $scorecard->game_three_v_three_away_one = ($row[66] == "NULL") ? null : $row[66];
            $scorecard->game_three_v_three_home_two = ($row[67] == "NULL") ? null : $row[67];
            $scorecard->game_three_v_three_away_two = ($row[68] == "NULL") ? null : $row[68];
            $scorecard->game_three_v_three_home_three = ($row[69] == "NULL") ? null : $row[69];
            $scorecard->game_three_v_three_away_three = ($row[70] == "NULL") ? null : $row[70];

            try {
                $scorecard->saveOrFail();
            } catch (\Exception $e) {
                $this->error("Failed adding scorecard to the database: {$scorecard->home_team} vs {$scorecard->away_team} on {$scorecard->date_played}");
                print_r($scorecard);
                $this->info($e);
                DB::rollBack();
                return 1;
            }
        }

        DB::commit();
        $this->info("Data imported successully");
        return 0;
    }

    private function importAsSql()
    {
        $this->error("Import as SQL is not yet implemented");
        return 1;
    }

    /**
     * Checks the input data for conflicting input
     *
     * @return bool True if the flags are correct, false otherwise
     */
    private function checkFlags(): bool
    {
        $csv = $this->option('csv');
        $sql = $this->option('sql');

        if ($csv && $sql) {
            return false;
        } else if ($csv == null && $sql == null) {
            $this->dataType = 'csv';
        }

        if ($csv) {
            $this->dataType = 'csv';
        } else if ($sql) {
            $this->dataType = 'sql';
        }

        return true;
    }

    private function directoryExists(): bool
    {
        $directory = $this->option('directory');
        if (! $directory) {
            return false;
        }

        return is_dir($directory);
    }
}
