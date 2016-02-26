<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\BoxQuestion;
use App\Models\CustomerProfile;

class ProfileCheckDataConsistency extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'profile:check-data-consistency';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check the data consistency of the different profiles';

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
     * @return mixed
     */
    public function handle()
    {

        $subscribed_profiles = CustomerProfile::where('status', '!=', 'not-subscribed')->where('status', '!=', 'in-progress')->get();

        $this->line('Checking the `payment_profiles` linked to them');

        foreach ($subscribed_profiles as $profile) {

          if ($profile->payment_profile()->first() === NULL)
            $this->error("Profile #".$profile->id." got no `payment_profile`");
          else
            $this->info('Everything alright for profile #'.$profile->id);

        }

        $this->line('End of process.');
    }
}
