<?php
include '_init.php';

$jakob_config = \WAYF\Configuration::getConfig();

$template = new \WAYF\Template();

// Process the rewuest
try {
    $request = new \WAYF\Request();
    $request->handleRequest();
} catch(\WAYF\RequestException $re) {
    $data = array('errortitle' => 'Request error', 'errormsg' => $re->getMessage());
    $template->setTemplate('error')->setData($data)->render();
}

// Setup the attribute collector
$attr_col = new \WAYF\AttributeCollector();
$logger = \WAYF\LoggerFactory::createInstance($jakob_config['logger']);
$attr_col->setLogger($logger);
$storage = new \WAYF\Store\MemcacheStore();
$storage->initialize();
$client = new \WAYF\Client\JakobClient($jakob_config['gearman.jobservers']);
$client->setStorage($storage);
$attr_col->setClient($client);

// Get job configuration
$jc = new \WAYF\JobConfiguration();
$job = $jc->load($request->getJobid());

$attributes = $attr_col->processTasks($job['tasks'], $request->getAttributes());

// Return the result
$data = array('post' => array('attributes' => json_encode($attributes)), 'destination' => $request->getReturnURL());
$template->setTemplate($request->getReturnMethod())->setData($data)->render();
