<?php
include '_init.php';

$jakob_config = \WAYF\Configuration::getConfig();

// Setup logger
$loggertype = 'WAYF\Logger\\' . $jakob_config['logger']['type'];
$logger = new $loggertype();

// Get configuration for all connectors
$connector_configs = array();
foreach (new Directoryiterator(CONFIGROOT . DIRECTORY_SEPARATOR . 'connectors') AS $k => $v) {
    if($v->isFile()) {
        $connector_configs[] = \WAYF\Configuration::getConfig('connectors' . DIRECTORY_SEPARATOR . $v->getFilename());
    }
}

// Setup shared storage
$store = \WAYF\StoreFactory::createInstance($jakob_config['connector.storage']['type'], $jakob_config['connector.storage']['options']);
$store->initialize();

$worker = new \WAYF\Worker\JakobWorker();

// Load all connectors
foreach ($connector_configs AS $cconfig) {
    if (isset($cconfig['class'])) {
        $classname = 'WAYF\Connector\\' . $cconfig['class'];
        if (class_exists($classname, true)) {
            $func = new $classname();
            $func->setStore($store);
            $func->setConfig($cconfig);
            $worker->addWork($cconfig['id'], $func);

            $logger->log(1, "Starting connector: " . $cconfig['class']);
        }
    }
}

$worker->work();
