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
                    'port' => '11211',
                ),
            ),
        ),     
    ),

    'logger' => array(
        'type' => 'File',  
        'options' => array('file' => 'jakob.log'),
    ),

    // Time to wait if jobs are not done
    'waittime' => 1000,

    'gearman.jobservers' => '127.0.0.1',
);
