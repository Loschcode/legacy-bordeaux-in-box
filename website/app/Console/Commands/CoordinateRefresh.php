<?php namespace App\Console\Commands;

use Illuminate\Console\Command;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use App\Models\Coordinate;

/**
 * Generate the admins
 */
class CoordinateRefresh extends Command {

  /**
   * The console command name.
   *
   * @var string
   */
  protected $name = 'coordinate:refresh';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Refresh the coordinates of different addresses if needed';

  /**
   * Execute the console command.
   *
   * @return mixed
   */
  public function handle()
  {

    $this->line('We will refresh the coordinates ... ');

    /**
     * Let's do it
     */
    $coordinates = Coordinate::get();

    foreach ($coordinates as $coordinate) {

      $this->info('We normalize the order `'.$coordinate->id.'`');

      if ((!$coordinate->latitude) && (!$coordinate->longitude)) {

      $this->error('Coordinate are incorrect or empty for this entry.');

       $coordinate->changeFromGeocoding();
       $coordinate->save();

      // The API has limited access per second
      sleep(3);

      $this->info('Coordinate supposedly refreshed.');


      } else {

        $this->info('Nothing to do here.');

      }


    }

    $this->line('End of process.');

  }

}