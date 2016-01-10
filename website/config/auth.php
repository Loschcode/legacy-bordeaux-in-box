<?php

return array(

  /**
   * Multiple Authentication by Ollieread
   */
  'multi' => [

    /**
     * Customer
     */
    'customer' => [

      'driver' => 'eloquent',
      'model' => App\Models\Customer::class,
      'table' => 'customers',

    ],

    /**
     * Partner
     */
    'partner' => [

      'driver' => 'eloquent',
      'model' => App\Models\Partner::class,
      'table' => 'partners',

    ],

    /**
     * Admin
     */
    'admin' => [

      'driver' => 'eloquent',
      'model' => App\Models\User::class,
      'table' => 'users',

    ],
    
  ],

  'password' => [

    'email' => 'emails.user.reminder',
    'table' => 'password_reminders',
    'expire' => 60,

  ],

);
