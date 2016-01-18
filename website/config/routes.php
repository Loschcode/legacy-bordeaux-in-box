<?php

/**
 * 
 * Route config
 * Will load the Routes/[namespace]/routes.php accordingly
 * Everything in 'local' will be loaded only it's not in production environment
 * Everything in '*' will be systamatically loaded
 *
 * Processed in the RouteServiceProvider
 * 
 */
return [

  'local' => ['Sandbox'],
  '*' => ['Company', 'MasterBox', 'Shared'],

];