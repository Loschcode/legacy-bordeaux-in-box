<?php namespace App\Http\Controllers\Shared\Service;

use App\Http\Controllers\MasterBox\BaseController;
use App\Libraries\Trello;
use Mail;

class SlackController extends BaseController {

  private $general_board = 'Bordeaux in Box - General';
  private $general_list = 'To do';
  private $dev_board = 'Bordeaux in Box - Dev';
  private $dev_list = 'To do';

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
    return $this->processCommandTrello('general');
  }

  public function postCommandDev()
  {
    return $this->processCommandTrello('dev');
  }

  public function processCommandTrello($type)
  {

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

    // Find right username
    // Can be better to put that in an array.
    switch($username) {
      case 'jeremie':
        $username = 'jeremieges';
      break;

      case 'jérémie':
        $username = 'jeremieges';
      break;

      case 'laurent':
        $username = 'loschcode';
      break;

      case 'snowboarder':
        $username = 'loschcode';
      break;

      case 'lolo':
        $username = 'loschcode';
      break; 

      case 'ges':
        $username = 'jeremieges';
      break;

      default:
        return 'Impossible de trouver le membre (' . $username . ')';
      break;
    }

    // Add task
    $trello = new Trello();
    $response = $trello->addTask($this->{$type . '_board'}, $this->{$type . '_list'}, $task, $username);

    if ($response['success'] === FALSE) {
      return 'Erreur: ' . $response['message'];
    }

    return 'La task à été ajoutée !';

  }

  public function postCommandTodoist()
  {
    
    $text = trim(request()->input('text'));

    if (empty($text)) {
      return 'Erreur: Aucun texte pour la task';
    }

    $send = Mail::raw('', function($message) use ($text) {
      $message->to('project.152585713.4929638@todoist.net')
        ->subject($text . ' @slack');
    });

    if ($send) {
      return 'Task ajoutée avec succès';
    }

    return 'Erreur: Impossible d\'ajouter la task';

  }
 

}
