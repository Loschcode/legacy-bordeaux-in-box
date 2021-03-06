<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Defaults
    |--------------------------------------------------------------------------
    |
    | This option controls the default authentication "guard" and password
    | reset options for your application. You may change these defaults
    | as required, but they're a perfect start for most applications.
    |
    */

    'defaults' => [
        'guard' => 'customer',
        'passwords' => 'customer',
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    |
    | Next, you may define every authentication guard for your application.
    | Of course, a great default configuration has been defined for you
    | here which uses session storage and the Eloquent user provider.
    |
    | All authentication drivers have a user provider. This defines how the
    | users are actually retrieved out of your database or other storage
    | mechanisms used by this application to persist your user's data.
    |
    | Supported: "session", "token"
    |
    */

    'guards' => [

        'customer' => [
            'driver' => 'session',
            'provider' => 'customer',
        ],


        'administrator' => [
            'driver' => 'session',
            'provider' => 'administrator',
        ],

        /*
        'partner' => [
            'driver' => 'session',
            'provider' => 'partner',
        ],
        */

    ],

    /*
    |--------------------------------------------------------------------------
    | User Providers
    |--------------------------------------------------------------------------
    |
    | All authentication drivers have a user provider. This defines how the
    | users are actually retrieved out of your database or other storage
    | mechanisms used by this application to persist your user's data.
    |
    | If you have multiple user tables or models you may configure multiple
    | sources which represent each model / table. These sources may then
    | be assigned to any extra authentication guards you have defined.
    |
    | Supported: "database", "eloquent"
    |
    */

    'providers' => [

        'customer' => [
            'driver' => 'eloquent',
            'model' => App\Models\Customer::class,
             'table' => 'customers',
        ],

        
        'administrator' => [
            'driver' => 'eloquent',
            'model' => App\Models\Administrator::class,
             'table' => 'administrators',
        ],

        /*
        'partner' => [
            'driver' => 'eloquent',
            'model' => App\Models\Customer::class,
             'table' => 'partners',
        ],
        */

    ],

    /*
    |--------------------------------------------------------------------------
    | Resetting Passwords
    |--------------------------------------------------------------------------
    |
    | Here you may set the options for resetting passwords including the view
    | that is your password reset e-mail. You may also set the name of the
    | table that maintains all of the reset tokens for your application.
    |
    | You may specify multiple password reset configurations if you have more
    | than one user table or model in the application and you want to have
    | separate password reset settings based on the specific user types.
    |
    | The expire time is the number of minutes that the reset token should be
    | considered valid. This security feature keeps tokens short-lived so
    | they have less time to be guessed. You may change this as needed.
    |
    */

    'passwords' => [

        'customer' => [
            'provider' => 'customer',
            'email' => 'shared.emails.connect.reminder',
            'table' => 'password_resets',
            'expire' => 60,
        ],

        
        'administrator' => [
            'provider' => 'administrator',
            'email' => 'shared.emails.connect.reminder',
            'table' => 'password_resets',
            'expire' => 60,
        ],

        /*
        'partner' => [
            'provider' => 'partner',
            'email' => 'shared.emails.connect.reminder',
            'table' => 'password_resets',
            'expire' => 60,
        ],
        */

    ],

];
