<?php
/**
 * JAKOB
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
 * CPR job
 *
 * Implementation of a job that fetches information from CPR. 
 */
class SBConnector extends AbstractConnector
{
    /**
     * Fetch data from CPR
     *
     * The method will make a request to CPR by calculating a signarure for the 
     * request. The result will be saved to a Memcache instance with the job 
     * handler as the reference.
     *
     * @param \GearmanJob $job Gearman job
     */ 
    public function execute(\GearmanJob $job)
    {
        $this->_logger->log(JAKOB_INFO, 'SB - Calling SB connector');
        $handle = $job->handle();
        
        // Process workload
        $workload = json_decode($job->workload(), true);
        
        $response = new \WAYF\ConnectorResponse();

        // The identifing attribute is missing
        if (!isset($workload['options']['userkey'])) { 
            $response->statuscode = STATUS_ERROR;
            $response->statusmsg = 'Missing required configuration option'; 
            $this->_store->set($handle, $response->toJSON());
            $this->_logger->log(JAKOB_ERROR, 'SB - Missing required configuration option');
            return;
        }
        // The identifing attribute is missing
        if (!isset($workload['attributes']['schacPersonalUniqueID'][0])) { 
            $response->statuscode = STATUS_ERROR;
            $response->statusmsg = 'Missing identifing attribute: schacPersonalUniqueID'; 
            $this->_store->set($handle, $response->toJSON());
            $this->_logger->log(JAKOB_ERROR, 'SB - Missing identifing attribute: schacPersonalUniqueID');
            return;
        }
        
        // The identifing attribute has wrong format
        if (preg_match('/^urn:mace:terena.org:schac:personalUniqueID:dk:CPR:([0-9]{10})$/', $workload['attributes']['schacPersonalUniqueID'][0], $matches) != 1) {
            $response->statuscode = STATUS_ERROR;
            $response->statusmsg = 'Identifing attribute: schacPersonalUniqueID has wrong format'; 
            $this->_store->set($handle, $response->toJSON());
            $this->_logger->log(JAKOB_ERROR, 'SB - Identifing attribute: schacPersonalUniqueID has wrong format');
            return;
        }

        $params['cpr'] = $matches[1];
        $params['ukey'] = $workload['options']['userkey'];

        // Build query
        $query = http_build_query($params);

        $this->_logger->log(JAKOB_DEBUG, 'SB - Calling: https://developer.statsbiblioteket.dk/attribute-store/index.php' . $query);

        // Get result from CULR
        $result = file_get_contents('https://<URL>/index.php?' . $query);

        $this->_logger->log(JAKOB_DEBUG, 'SB - Result: ' . var_export($result, true));

        // Process the returned data and pu on right form
        $decodedresult = json_decode($result, true);

        $response->statuscode = $decodedresult['status']['code']; 
        $response->responseid = $decodedresult['id'];

        if ($response->statuscode == 0) {
            $response->userid = $decodedresult['userid'];
            $response->addAttribute('eduPersonScopedAffiliation', $decodedresult['attributes']['affiliation']);
        } else if (isset($decodedresult['status']['message'])) {
            $response->statusmsg = $decodedresult['status']['message']; 
            $this->_logger->log(JAKOB_ERROR, $decodedresult['status']['message']);
        } else {
            $response->statusmsg = "An error have occured"; 
            $this->_logger->log(JAKOB_ERROR, 'SB - An error have occured');
        }

        // Store result
        $this->_store->set($handle, $response->toJSON());
    }
    
    public function setup() {}
}
