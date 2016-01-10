<?php

/**
 * 
 * Route config
 * Will load the Routes/[namespace]/routes.php accordingly
 * Everything in 'development' will be loaded only it's not in production environment
 * Everything in '*' will be systamatically loaded
 *
 * Processed in the RouteServiceProvider
 * 
 */
return [

  'development' => ['Sandbox'],
  '*' => ['Company', 'MasterBox'],

];