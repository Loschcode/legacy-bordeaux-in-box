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

    $command = request()->input('command');
    $text = trim(request()->input('text'));

    if (empty($text)) {
      return 'Erreur: Il manque la personne à assigner ainsi que le nom de la task';
    }

    // Explode params
    $params = explode(' ', $text);

    if (count($params) === 1) {
      return 'Erreur: Il manque le titre de la task';
    }

    // Fetch username
    $username = $params[0];
        
    // Forget username
    unset($params[0]);

    // Fetch task description
    $task = implode(' ', $params);

    // Add task
    $trello = new Trello();
    $response = $trello->addTask('Bordeaux in Box - General', 'To do', $task);

    if ($response['success'] === FALSE) {
      return 'Erreur: ' . $response['message'];
    }

    return 'La task à été ajoutée !';

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
