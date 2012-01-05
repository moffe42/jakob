<?php
$config = array(
    // Database configuration
    'dsn'      => 'mysql:host=localhost;dbname=jakob_db',
    'username' => 'USERNAME',
    'password' => 'PASSWORD',
    'table'    => 'jakob__configuration',

    // Salt used when calculating jobhash values
    'salt'     => 'pezo340fkvd3afnywz3ab2fuwf5enj8h',

    // URL for JAKOB jobs
    'joburl'   => 'http://jakob.test.wayf.dk/job/',

    // Consumer
    'consumerkey' => 'wayf',
    'consumersecret' => '09984b3e4aa39d21f68b3d751fb4fa5b93a6ddb9',
);
