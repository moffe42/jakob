<?php
$starttime = microtime(true);

include '_init.php';

$request = new \WAYF\Request();
$request->handleRequest();

$jakob_config = \WAYF\Configuration::getConfig();

// Setup logger
$logger = \WAYF\LoggerFactory::createInstance($jakob_config['logger']);

//$client = new \WAYF\Client\TestClient();
$client = new \WAYF\Client\JakobClient();

$attr_col = new \WAYF\AttributeCollector();
$attr_col->setLogger($logger);
$attr_col->setClient($client);

// Get job configuration
$jc = new \WAYF\JobConfiguration();
$job = $jc->load($request->getJobid());

echo "<pre>";

print_r($attr_col->processTasks($job['tasks'], array('cpr' => '1302822111')));

echo "\nExecution time: " . (microtime(true) - $starttime) . " seconds";
