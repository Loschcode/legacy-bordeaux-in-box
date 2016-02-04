<?php

use GuzzleHttp\Client;
use Faker\Factory as Faker;

class TestCase extends Illuminate\Foundation\Testing\TestCase
{    
    public function __construct()
    {
      parent::__construct();

      ini_set('memory_limit', '512M');

      $this->baseUrl = env('BASE_URL');

      \Stripe\Stripe::setApiKey(getenv('STRIPE_API_KEY'));

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
