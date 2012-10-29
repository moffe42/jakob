<?php
/**
 * JAKOB echo connector for testing
 *
 * @category   WAYF
 * @package    JAKOB
 * @subpackage Connector
 * @author     Jacob Christiansen <jach@wayf.dk>
 * @copyright  Copyright (c) 2011 Jacob Christiansen, WAYF (http://www.wayf.dk)
 * @license    http://www.opensource.org/licenses/mit-license.php MIT License
 * @version    $Id$
 * @link       $URL$
 */

/**
 * @namespace
 */
namespace WAYF\Connector;

/**
 * @uses
 */
use WAYF\Connector;

/**
 * Echo Connector
 *
 * Implementation of a job that fetches information from SB. 
 */
class EchoConnector extends AbstractConnector
{
    /**
     * Test connector
     *
     * This method will a logline, sleep for half a second and then return
     * success.
     *
     * @param \GearmanJob $job Gearman job
     */ 
    public function execute(\GearmanJob $job)
    {
        // Write log line
        $this->_logger->log(JAKOB_INFO, 'Echo Connector');

        // Sleep for half a second
        usleep(500000);

        // Handle request
        $handle = $job->handle();
        $response = new \WAYF\ConnectorResponse();
        $response->statuscode = 0;
        $response->statusmsg = "Echo Connector"; 
        $this->_store->set($handle, $response->toJSON());
    }

    public function setup() {}
}
