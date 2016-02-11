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
 

  public function postAddTask()
  {
    dd(request()->all());

    $trello = new Trello();

    $trello->addTask('test', 'List', 'Je suis enorme putain', 'Superbe description');


  }


 

}
