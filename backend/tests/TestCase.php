<?php

use Laravel\Lumen\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan;

abstract class TestCase extends BaseTestCase
{
    /**
     * Creates the application.
     *
     * @return \Laravel\Lumen\Application
     */
    public function createApplication()
    {
        return require __DIR__.'/../bootstrap/app.php';
    }

    /**
     * Determine what directory is being used for the import files
     * 
     * @return String
     */
    public function getImportDirectory()
    {
        if (is_dir('/import')) {
            return '/import';
        } else if (is_dir('../docker/backend/import')) {
            return '../docker/backend/import';
        } else {
            $this->assert(false, "Could not determine an import directory");
        }
    }

    public function importData()
    {
        $output = Artisan::call('import', ['--directory' => $this->getImportDirectory()]);
        $this->assertEquals(0, $output);
    }
}
