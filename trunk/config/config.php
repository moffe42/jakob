<?php
/**
 * JAKOB global configuration file
 *
 * @category   WAYF
 * @package    JAKOB
 * @author     Jacob Christiansen <jach@wayf.dk>
 * @copyright  Copyright (c) 2011 Jacob Christiansen, WAYF (http://www.wayf.dk)
 * @license    http://www.opensource.org/licenses/mit-license.php MIT License
 * @version    $Id$
 * @link       $URL$
 */
$config = array(
    /**
     * Configuration for connectors
     *
     * All configuration that is shared between all connector are prefixed 
     * `connector.`
     */
    'connector.storage' => array(
        'type' => 'Memcache',
        'options' => array(
            'servers' => array(
                array(
                    'host' => '127.0.0.1',
                    'port' => '11212',
                ),
            ),
        ),     
    ),
    
    // Database configuration
    'database' => array(
        'dsn'      => 'mysql:host=192.36.171.27;dbname=jakob',
        'username' => 'jakob',
        'password' => '5H6W2R8xymuH7RdG',
        'table'    => 'jakob__configuration',
    ),

    // Logger configuration
    'logger' => array(
        'type' => 'File',  
        'options' => array(
            'file' => 'jakob.log',
            'level' => JAKOB_INFO,
        ),
    ),
    
    // Salt used when calculating jobhash values
    'salt'     => 'pezo340fkvd3afnywz3ab2fuwf5enj8h',

    // Time to wait if jobs are not done (in micro seconds)
    'waittime' => 100000, // 100 ms

    // German configuration
    'gearman.jobservers' => '127.0.0.1',

    // Directory for pid files for start-stop-daemon
    'pid.directory' => '/var/jakob',

    // Languages supported
    'languages' => array('da', 'en'),
);
