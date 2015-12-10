<?php

return array(

	/*
	|--------------------------------------------------------------------------
	| Third Party Services
	|--------------------------------------------------------------------------
	|
	| This file is for storing the credentials for third party services such
	| as Stripe, Mailgun, Mandrill, and others. This file provides a sane
	| default location for this type of information, allowing packages
	| to have a conventional place to find your various credentials.
	|
	*/

	'mailgun' => array(
		'domain' => 'bordeauxinbox.fr',
		'secret' => 'key-de5ae8cc880075dbc0d9780dd6a262b9',
	),

	'mandrill' => array(
		'secret' => '',
	),

	'stripe' => array(
		'model'  => 'UserPaymentProfile',
		'secret' => 'sk_test_3t1faNpmJVWM1ZWehtPZbLFn',
	),

);
