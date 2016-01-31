<?php

return [

	/*
	|--------------------------------------------------------------------------
	| oAuth Config
	|--------------------------------------------------------------------------
	*/

	/**
	 * Storage
	 */
	'storage' => 'Session',

	/**
	 * Consumers
	 */
	'consumers' => [

		'Facebook' => [

			'client_id'     => env('FACEBOOK_LOGIN_ID'),
			'client_secret' => env('FACEBOOK_LOGIN_SECRET'),
			'scope'         => ['email', 'public_profile', 'user_friends'],

		],

    'Google' => [

      'client_id'     => 'Your Google client ID',
      'client_secret' => 'Your Google Client Secret',
      'scope'         => ['userinfo_email', 'userinfo_profile'],

    ],  

    'Twitter' => [

      'client_id'     => 'Your Twitter client ID',
      'client_secret' => 'Your Twitter Client Secret',

    ],

	]

];