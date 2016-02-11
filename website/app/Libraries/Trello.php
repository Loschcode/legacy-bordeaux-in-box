<?php

namespace App\Libraries;

/**
 * Wrapper for API trello 
 * @uses Guzzle Perform curl requests
 */
class Trello {

  /**
   * User persists token (uses jeremie account)
   * @var string
   */
  const USER_TOKEN = '094efc4262cebc7e0405f76f077be3d77291881ba30bbb4010724133273b1f7a';

  /**
   * Developer key (uses jeremie account)
   * @var string
   */
  const KEY = '3b1235112c4bd774767d4e89b1f8edd5';

  /**
   * Init an instance of the class
   */
  public function __construct()
  {
    // Init guzzle to perform curl requests
    $this->client = new \GuzzleHttp\Client();

    // Add variable signature to sign requests and avoid repitition
    $this->signature = 'key=' . self::KEY . '&token=' . self::USER_TOKEN;

  }

  /**
   * Add a new task in trello
   * @param string $board_name  The board name
   * @param string $list_name   The list name
   * @param string $title       Title of the task
   * @param string $member_name      The member to assign
   * @return array The response
   */
  public function addTask($board_name, $list_name, $title, $member_name='')
  {
    // Fetch board id 
    $board_id = $this->getBoardIdByName($board_name);

    if ($board_id === FALSE) return ['success' => FALSE, 'message' => 'Impossible de trouver la board ' + $board_name];

    // Fetch member id 
    $member_id = $this->getMemberIdByName($board_id, $member_name);

    if ($member_id === FALSE) return ['success' => FALSE, 'message' => 'Impossible de trouver le membre ' + $member_name];


    // Fetch list id 
    $list_id = $this->getListIdByName($board_id, $list_name);

    if ($list_id === FALSE) return ['success' => FALSE, 'message' => 'Impossible de trouver la liste ' + $list_name + ' dans la board ' + $board_name];

    // Add task 
    $request = $this->client->request('POST', 'https://api.trello.com/1/cards/?idList=' . $list_id . '&pos=top&idMembers=' . $member_id . '&name=' . $title . '&' . $this->signature);


    if ($request->getStatusCode() !== 200) return ['success' => FALSE, 'message' => 'Board trouvée, Liste trouvée, mais impossible d\'ajouter la task'];


    return ['success' => TRUE, 'message' => 'La task a bien été ajoutée'];

  }

  /**
   * Find the member id for the member name given
   * @param  string $board_id The id of the board
   * @param  string $member_name Name of the member
   * @return mixed  Id / FALSE
   */
  public function getMemberIdByName($board_id, $member_name)
  {
    $request = $this->client->request('GET', 'https://api.trello.com/1/boards/' . $board_id . '/members?' . $this->signature);

    if ($request->getStatusCode() !== 200) return FALSE;

    $members = $this->getResponseJson($request);

    foreach ($members as $member) {

      if ($member['username'] === $member_name) {
        return $member['id'];
      }

    }

    return FALSE;

  }
  /**
   * Find the board id for the board name given
   * @param  string $board_name Name of the board
   * @return mixed  Id / FALSE
   */
  public function getBoardIdByName($board_name)
  {
    $request = $this->client->request('GET', 'https://api.trello.com/1/members/me/boards?' . $this->signature);

    if ($request->getStatusCode() !== 200) return FALSE;

    $boards = $this->getResponseJson($request);

    foreach ($boards as $board) {

      if ($board['name'] === $board_name) {
        return $board['id'];
      }

    }

    return FALSE;

  }

  /**
   * Find the list id for the list name given
   * @param  string $board_id The board id
   * @param  string $list_name The name of the list
   * @return mixed  Id / FALSE
   */
  public function getListIdByName($board_id, $list_name)
  {
    $request = $this->client->request('GET', 'https://api.trello.com/1/boards/' . $board_id . '/lists?' . $this->signature);

    if ($request->getStatusCode() !== 200) return FALSE;

    $lists = $this->getResponseJson($request);

    foreach ($lists as $list) {

      if ($list['name'] === $list_name) {
        return $list['id'];
      }

    }

    return FALSE;

  }

  /**
   * Fetch body of request and response as json
   * @param  string $request The request returned by guzzle
   * @return json The response as JSON
   */
  private function getResponseJson($request)
  {
    return json_decode($request->getBody(), true);
  }

}