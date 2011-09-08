<?php
$strat = microtime(true);
include '_init.php';

/*
$client = new \WAYF\Connector\Client\JakobClient();

$res = $client->doAsync('GetUser', '{"cpr":"1302822111"}');

while(!$result = $client->getResult($res)) {
    usleep(1000);
}

$end = microtime(true);

var_dump($result);
var_dump($end - $strat);
die();
 */


$jobs2 = array(
    'tasks' => array(
        array(
            '_id' => 'GetUser',    
            '_priority' => 'sync',
            '_options' => array(
                'userkey' => 'wayf',        
                'key' => 'wayf4ever',        
            ),
        ),
    ),
);


$jobs = array(
    'tasks' => array(
        array(
            '_id' => 'CPR1',    
            '_priority' => 'sync',
            '_options' => array(
                'uid' => '1302822111',        
            ),
            '_inattr' => array(
                'cpr'
            ),
            '_outattr' => array(
                'knr'
            ),
        ),
        array(
            '_id' => 'VIP2',    
            '_priority' => 'sync',
            '_options' => array(
                'uid' => '1302822111',        
            ),
        ),
        array(
            '_priority' => 'batch',
            'tasks' => array(
                array(
                    '_id' => 'CPR3',    
                    '_priority' => 'async',
                    '_options' => array(
                        'uid' => '1302822111',        
                    ),
                ),
                array(
                    '_id' => 'VIP4',    
                    '_priority' => 'async',
                    '_options' => array(
                        'uid' => '1302822111',        
                    ),
                ),
                array(
                    '_id' => 'VIP5',    
                    '_priority' => 'sync',
                    '_options' => array(
                        'uid' => '1302822111',        
                    ),
                ),
            ),    
        ),
    ),    
);

$client = new \WAYF\Connector\Client\TestClient();
$client = new \WAYF\Connector\Client\JakobClient();

$logger = new \WAYF\Logger\SysLogger();
$logger->log(LOG_ERR, "TESTER");

$attr_col = new \WAYF\AttributeCollector();
$attr_col->setLogger($logger);
$attr_col->setClient($client);

var_dump($attr_col->processTasks($jobs2['tasks'], array('cpr' => '1302822111')));
