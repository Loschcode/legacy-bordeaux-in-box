<?php

use GuzzleHttp\Client;
use Faker\Factory as Faker;

class TestCase extends Illuminate\Foundation\Testing\TestCase
{

    public function __construct()
    {
      parent::__construct();

      $this->baseUrl = env('BASE_URL');

    }

    /**
     * Hook setup with new things
     */
    public function setUp()
    {

        parent::setUp();

        // Create Faker instance
        $this->faker = Faker::create();

    }

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

        return $app;
    }

}
