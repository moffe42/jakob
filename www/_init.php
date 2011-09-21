<?php
// Define the root path of JAKOB 
define('ROOT', dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR);
define('CONFIGROOT', dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR);

// System status
define('STATUS', 'development');

// Set error logging
ini_set('log_errors', TRUE);
ini_set('error_log', ROOT . DIRECTORY_SEPARATOR . 'log' . DIRECTORY_SEPARATOR . 'jakob_error.log');

// Check what status we have
switch (STATUS) {
    case 'production': {
        ini_set('display_errors', 'off');
        ini_set('display_startup_errors', FALSE);
        break;
    }
    case 'development': {
        // Display all errors in development
        ini_set('display_errors', 'on');
        ini_set('display_startup_errors', TRUE);
        ini_set('error_reporting', -1);
        break;
    }
    default: {
        die('Application status not set. Terminating execution.');
    }
}

// Include the autoloader
include ROOT . 'lib' . DIRECTORY_SEPARATOR . 'WAYF' . DIRECTORY_SEPARATOR . 'AutoLoader.php';

// Register all classes under WAYF
$classLoader = new \WAYF\AutoLoader('WAYF', ROOT . 'lib');
$classLoader->register();

// Start session
session_start();
