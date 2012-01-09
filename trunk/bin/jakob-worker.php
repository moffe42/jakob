#!/usr/bin/env php
<?php
include '../www/_init.php';

echo "Starting JAKOB worker initializing script\n";

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
        $connector_config = \WAYF\Configuration::getConfig('connectors' . DIRECTORY_SEPARATOR . $v->getFilename());
        $cmd = 'nohup php ' . ROOT .  'bin' . DIRECTORY_SEPARATOR . 'connector-worker.php ' . urlencode($v->getFilename()) . ' 1> ' . LOGROOT .'nohup.out 2> ' . LOGROOT . 'nohup.error &';
        echo "Running: " . $cmd . "\n";
        $logger->log(JAKOB_INFO, 'Starting ' . $connector_config['class'] . ' connector with ID: ' . $connector_config['id']);
        $proc = Proc_Open($cmd, array(), $foo);
        if ($proc === false) {
            $logger->log(JAKOB_ERROR, 'Could not start ' . $connector_config['class'] . ' connector');
            echo 'Could not start ' . $connector_config['class'] . " connector\n";
        } else { 
            Proc_close($proc);
            $logger->log(JAKOB_INFO, $connector_config['class'] . ' connector with ID: ' . $connector_config['id'] . ' started');
            echo $connector_config['class'] . ' connector with ID: ' . $connector_config['id'] . " started\n";
        }
    }
}

echo "JAKOB initialization script is done\n";
