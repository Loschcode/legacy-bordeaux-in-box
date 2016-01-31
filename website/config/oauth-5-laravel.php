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

      'client_id'     => env('GOOGLE_LOGIN_ID'),
      'client_secret' => env('GOOGLE_LOGIN_SECRET'),
      'scope'         => ['userinfo_email', 'userinfo_profile'],

    ],  

    'Twitter' => [

      'client_id'     => env('TWITTER_LOGIN_ID'),
      'client_secret' => env('TWITTER_LOGIN_SECRET'),

    ],

	]

];