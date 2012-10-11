<?php
declare(ticks=1);
include '../www/_init.php';

$stopwork = false;

// Signal handler function
function sig_handler($signo)
{
    switch ($signo) {
        case SIGTERM:
            // Workers is to terminate
            $stopwork = true;
            $logger->log(JAOKB_INFO, $connector_config['class'] . ' was forced to quit - SIGTERM');
        }
}
pcntl_signal(SIGTERM, "sig_handler");

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

// Get connector configuration
$connector_config = \WAYF\Configuration::getConfig('connectors' . DIRECTORY_SEPARATOR . urldecode($argv['1']));

// Create a new worker
$worker = new \WAYF\Worker\JakobWorker($jakob_config['gearman.jobservers']);
$worker->setLogger($logger);

if (isset($connector_config['class'])) {
    $classname = 'WAYF\Connector\\' . $connector_config['class'];
    if (class_exists($classname, true)) {
        $func = new $classname();
        $func->setStore($store);
        $func->setConfig($connector_config);
        $func->setLogger($logger);
        $worker->addWork($connector_config['id'], $func);

        $logger->log(JAKOB_INFO, $connector_config['class'] . ' was initialized successful');
    }
}

// Set timeout to 5 seconds
$worker->setTimeout(5000);

// Wait for work
while(@$gmworker->work() || $gmworker->returnCode() == GEARMAN_TIMEOUT) {
    if ($worker->_gworker->returnCode() != GEARMAN_SUCCESS) {
        $logger->log(JAOKB_ERROR, $connector_config['class'] . ' did not return success on work. Return cod was: ', $worker->_gworker->returnCode());
    }
    // Check to see if the workers have been killed
    if ($stopwork) {
        break;
    }
}

$worker->unregisterAll();
