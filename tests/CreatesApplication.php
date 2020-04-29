<?php

namespace Tests;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Contracts\Console\Kernel;

trait CreatesApplication
{
    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->loadEnvironmentFrom('.env.testing'); // specify the file to use for environment, must be run before boostrap

        $app->make(Kernel::class)->bootstrap();

        return $app;
    }

//    public function setUp()
//    {
//        parent::setUp();
//        Artisan::call('migrate');
//
//    }
//
//    public function tearDown()
//    {
//        Artisan::call('migrate:reset');
//        parent::tearDown();
//    }
}
