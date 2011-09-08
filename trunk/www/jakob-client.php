<?php
$starttime = microtime(true);

include '_init.php';

$jobs2 = array(
    'tasks' => array(
        array(
            '_id' => 'CPR',    
            '_priority' => 'sync',
            '_options' => array(
                'userkey' => 'wayf',        
                'key' => 'wayf4ever',        
            ),
        ),
        array(
            '_id' => 'VIP',    
            '_priority' => 'async',
            '_options' => array(
                'userkey' => 'wayf',        
                'key' => 'wayf4evervip',        
            ),
        ),
        array(
            '_id' => 'CPR',    
            '_priority' => 'async',
            '_options' => array(
                'userkey' => 'wayf',        
                'key' => 'wayf4ever',        
            ),
        ),
    ),
);

//$client = new \WAYF\Client\TestClient();
$client = new \WAYF\Client\JakobClient();

$logger = new \WAYF\Logger\SysLogger();
$logger->log(LOG_ERR, "TESTER");

$attr_col = new \WAYF\AttributeCollector();
$attr_col->setLogger($logger);
$attr_col->setClient($client);

echo "<pre>";

print_r($attr_col->processTasks($jobs2['tasks'], array('cpr' => '1302822111')));

echo "\nExecution time: " . (microtime(true) - $starttime) . " seconds";
