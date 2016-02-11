<?php namespace App\Http\Controllers\Shared\Service;

use App\Http\Controllers\MasterBox\BaseController;
use App\Libraries\Trello;

class SlackController extends BaseController {

  /*
  |--------------------------------------------------------------------------
  | Slack Controller
  |--------------------------------------------------------------------------
  |
  | Everything about slack commands
  |
  */
  public function postCommandGeneral()
  {

    return 'en cours de dev';

    $command = request()->input('command');
    $text = trim(request()->input('text'));

    /**
     * Marketing command
     */
    if ($command === '/marketing') {

      // Explode params
      $params = explode(' ', $text);

      // Fetch username
      $username = $params[0];
        
      // Forget username
      unset($params[0]);

      // Fetch task description
      $task = implode(' ', $params);

      // Add task
      $trello = new Trello();
      $response = $trello->addTask('test', 'List', $task);

    }
  }

    public function postCommandDev()
    {
      return 'en cours de dev';
    }

    public function postCommandTodoist()
    {
      return 'en cours de dev';
    }
 

}
