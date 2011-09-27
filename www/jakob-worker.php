<?php
include '_init.php';

try {
    $jakob_config = \WAYF\Configuration::getConfig();

    // Setup logger
    $logger = \WAYF\LoggerFactory::createInstance($jakob_config['logger']);

    // Setup shared storage
    $store = \WAYF\StoreFactory::createInstance($jakob_config['connector.storage']);
    $store->initialize();
} catch(\InvalidArgumentException $e) {
    echo $e->getMessage() . "\n";
    die();
}

// Get configuration for all connectors
$connector_configs = array();
foreach (new Directoryiterator(CONFIGROOT . DIRECTORY_SEPARATOR . 'connectors') AS $k => $v) {
    if($v->isFile()) {
        $connector_configs[] = \WAYF\Configuration::getConfig('connectors' . DIRECTORY_SEPARATOR . $v->getFilename());
    }
}

// Create a new worker
$worker = new \WAYF\Worker\JakobWorker($jakob_config['gearman.jobservers']);
$worker->setLogger($logger);

// Load all connectors into worker
foreach ($connector_configs AS $cconfig) {
    if (isset($cconfig['class'])) {
        $classname = 'WAYF\Connector\\' . $cconfig['class'];
        if (class_exists($classname, true)) {
            $func = new $classname();
            $func->setStore($store);
            $func->setConfig($cconfig);
            $worker->addWork($cconfig['id'], $func);

            $logger->log(JAKOB_INFO, "Starting connector: " . $cconfig['class']);
        }
    }
}

// Wait for work
$worker->work();

