<?php

use GuzzleHttp\Client;
use Faker\Factory as Faker;

class TestCase extends Illuminate\Foundation\Testing\TestCase
{

    public function __construct()
    {
      parent::__construct();

      $this->baseUrl = getenv('BASE_URL');

    }

    /**
     * Hook setup with new things
     */
    public function setUp()
    {

        parent::setUp();
        // Create connexion mailtrap.io
        $this->mailtrap = new Client([
              'base_uri' => getenv('MAILTRAP_API_BASE_URI'),
              'headers' => [
                  'Api-Token' => getenv('MAILTRAP_API_TOKEN')
              ]
          ]);
        $this->mailtrap_inbox = getenv('MAILTRAP_API_INBOX');
        
        // Clean messages of mailtrap between each tests
        $this->mailtrapCleanMessages();

        // Faker
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

    /*
    |--------------------------------------------------------------------------
    | Super simple mailtrap API used in new asserts created below
    |--------------------------------------------------------------------------
    |
    */
   
    /**
     * Fetch messages of the mailtrap inbox
     * @return json The messages of the inbox given
     */
    public function mailtrapGetMessages()
    {
        $response = $this->mailtrap->request('GET', "inboxes/$this->mailtrap_inbox/messages");
        return json_decode((string) $response->getBody());
    }

    /**
     * Fetch the last message received in mailtrap inbox
     * @return object Message
     */
    public function mailtrapGetLastMessage()
    {
        $messages = $this->mailtrapGetMessages();
        if (empty($messages))
        {
            $this->fail('Api Mailtrap: No messages found.');
        }
        return $messages[0];
    }

    /**
     * Clean Messages of the mailtrap inbox
     * @return void
     */
    public function mailtrapCleanMessages()
    {
        $response = $this->mailtrap->request('PATCH', "inboxes/$this->mailtrap_inbox/clean");
    }

    /*
    |--------------------------------------------------------------------------
    | Asserts for mails (mailtrap)
    |--------------------------------------------------------------------------
    |
    */
   
    public function assertEmailIsSent($description = 'No email sent')
    {
        $this->assertNotEmpty($this->mailtrapGetMessages(), $description);
    }
    public function assertEmailSubjectContains($needle, $email, $description = 'Email subject do not contains')
    {
        $this->assertContains($needle, $email->subject, $description);
    }
    public function assertEmailSubjectEquals($expected, $email, $description = 'Email subject do not equals')
    {
        $this->assertEquals($expected, $email->subject, $description);
    }
    public function assertEmailSenderEquals($expected, $email, $description = 'Email sender do not equals')
    {
        $this->assertEquals($expected, $email->from_email, $description);
    }
    public function assertEmailNameSenderEquals($expected, $email, $description = 'Name sender do not equals')
    {
        $this->assertEquals($expected, $email->from_name, $description);
    }
    public function assertEmailNameReceiverEquals($expected, $email, $description = 'Name receiver do not equals')
    {
        $this->assertEquals($expected, $email->to_name, $description);
    }
    public function assertEmailReceiverEquals($expected, $email, $description = 'Email receveiver do not equals')
    {
        $this->assertEquals($expected, $email->to_email, $description);
    }
    public function assertEmailHtmlContains($needle, $email, $description = 'Email html do not contains')
    {
        $this->assertContains($needle, $email->html_body, $description);
    }
    public function assertEmailTextContains($needle, $email, $description = 'Email text do not contains')
    {
        $this->assertContains($needle, $email->text_body, $description);
    }


}
