<?php
$strat = microtime(true);
include '_init.php';

$client = new \WAYF\Connector\Client\JakobClient();

$res = $client->doAsync('GetUser', '{"cpr":"1302822111"}');

while(!$result = $client->getResult($res)) {
    usleep(1000);
}

$end = microtime(true);

var_dump($result);
var_dump($end - $strat);
die();
