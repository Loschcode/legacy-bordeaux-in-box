<?php

namespace App\Libraries;

/**
 * Wrapper for API Slack
 * @uses Guzzle Perform curl requests
 */
class Slack {

  /**
   * Token to perform requests on the slack api
   * @var string
   */
  const TOKEN = 'xoxp-20506674819-20554262691-21079003184-6b765dcb2a';

  /**
   * Init an instance of the class
   */
  public function __construct()
  {
    // Init guzzle to perform curl requests
    $this->client = new \GuzzleHttp\Client();

    // Add variable signature to sign requests and avoid repitition
    $this->signature = 'token=' . self::TOKEN;

  }

  /**
   * Check if someone is online on slack
   * @return boolean
   */
  public function isSomeoneOnline()
  {
    // Fetch users of the team
    $request = $this->client->request('GET', 'https://slack.com/api/users.list?' . $this->signature);

    // Fetch response of the request as json
    $response = $this->getResponseJson($request);

    if ($response['ok'] !== TRUE) return FALSE;

    $members = $response['members'];

    foreach ($members as $member) {

      // For slack, slackbot is not a bot .. yeah, sure.
      if ($member['is_bot'] === false && $member['name'] !== 'slackbot' && $this->isUserOnline($member['id'])) {

        // Someone is online
        return TRUE;

      }

    }

    return FALSE;

  }

  /**
   * Check if the user given is online on slack
   * @param  string  $user_id The slack id of the user
   * @return boolean
   */
  public function isUserOnline($user_id)
  {
    // Fetch presence of the user 
    $request = $this->client->request('GET', 'https://slack.com/api/users.getPresence?user=' . $user_id . '&' . $this->signature);

    $response = $this->getResponseJson($request);

    if ($response['ok'] !== TRUE) return FALSE;

    if ($response['presence'] !== 'active') return FALSE;

    return TRUE;
  }

  /**
   * Fetch body of request and convert response as json
   * @param  string $request The request returned by guzzle
   * @return json The response as JSON
   */
  private function getResponseJson($request)
  {
    return json_decode($request->getBody(), true);
  }

}
