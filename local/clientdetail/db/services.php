<?php
$functions = [
  'local_clientdetail_set_client_data' => [
    'classname'   => 'local_clientdetail_external',
    'methodname'  => 'set_client_data',
    'classpath'   => 'local/clientdetail/externallib.php',
    'description' => 'Set the client detail data for a course',
    'type'        => 'write',
  ],
  'local_clientdetail_get_client_data' => [
    'classname'   => 'local_clientdetail_external',
    'methodname'  => 'get_client_data',
    'classpath'   => 'local/clientdetail/externallib.php',
    'description' => 'Get the client detail data by course ID',
    'type'        => 'read',
  ]
];

$services = [
  'Client Detail Webservice' => [
    'functions' => ['local_clientdetail_set_client_data', 'local_clientdetail_get_client_data'],
    'restrictedusers' => 0,
    'enabled' => 1,
    'shortname' => 'wsclientdetail',
    'downloadfiles' => 1,
    'uploadfiles' => 1
  ]
];
