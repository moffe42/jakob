<?php
// Signal handler function for gracefull exit capability
declare(ticks=1);
pcntl_signal(SIGTERM, "sig_handler");
function sig_handler($signo)
{
    global $logger, $connector_config, $stopwork;
    switch ($signo) {
        case SIGTERM:
            // Workers is to terminate
            $stopwork = true;
            $logger->log(JAKOB_DEBUG, $connector_config['class'] . ' was forced to quit - SIGTERM');
            break;
    }
}

include '../www/_init.php';

$stopwork = false;

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

// Write pid file for start-stop-daemon
$pid = getmypid();
file_put_contents($jakob_config['pid.directory'] . DIRECTORY_SEPARATOR . $pid . '.pid', $pid);

// Set timeout to 5 seconds
$worker->_gworker->setTimeout(5000);

// Wait for work
while(@$worker->work() || $worker->_gworker->returnCode() == GEARMAN_TIMEOUT) {
    if (($worker->_gworker->returnCode() != GEARMAN_SUCCESS) && ($worker->_gworker->returnCode() != GEARMAN_TIMEOUT)) {
        $logger->log(JAKOB_ERROR, $connector_config['class'] . ' did not return success on work. Return cod was: ', $worker->_gworker->returnCode());
    }
    // Check to see if the workers have been killed
    if ($stopwork) {
        $logger->log(JAKOB_DEBUG, $connector_config['class'] . ' stopping work loop');
        break;
    }
}

$worker->_gworker->unregisterAll();
$logger->log(JAKOB_INFO, $connector_config['class'] . ' disconnecting nicly from job server and exiting');
exit(0);
