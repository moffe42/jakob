<?php
include '_init.php';

include CONFIGROOT . DIRECTORY_SEPARATOR . 'connectors' . DIRECTORY_SEPARATOR . 'connector_cpr.php';

$store = \WAYF\StoreFactory::createInstance('Memcache');
$store->initialize();

$func = new \WAYF\Connector\Job\CprJob();
$func->setStore($store);
$func->setConfig($config);

$worker = new \WAYF\Connector\Worker\JakobWorker();

$worker->addWork('GetUser', $func);

$worker->work();
