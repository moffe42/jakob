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
class CprConnector extends AbstractConnector
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
        $handle = $job->handle();
        
        // Process workload
        $workload = json_decode($job->workload(), true);

        $params['cid'] = md5($workload['attributes']['cpr'][0]);
        $params['ukey'] = $workload['options']['userkey'];

        // Init signer
        $signer = new \WAYF\Security\Signer\GetRequestSigner();
        $signer->setUp($workload['options']['key'], $params);

        $params['signature'] = $signer->sign();

        $query = http_build_query($params);

        // Get result from CPR
        $result = file_get_contents('http://cpr.test.wayf.dk/?' . $query);

        // Process the returned data and pu on right form
        $decodedresult = json_decode($result, true);

        $parsedresult = new \WAYF\ConnectorResponse();
        $parsedresult->statuscode = $decodedresult['status']['code']; 
        $parsedresult->responseid = $decodedresult['id'];
        $parsedresult->userid = $decodedresult['userid'];
        $parsedresult->attributes = $decodedresult['attributes'];

        // Store result
        $this->_store->set($handle, $parsedresult->toJSON());
    }
    
    public function setup() {}
}
