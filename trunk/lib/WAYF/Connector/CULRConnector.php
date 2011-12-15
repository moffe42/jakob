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
class CULRConnector implements Connector
{
    private $_store = null;
    
    private $_config = null;

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

        if (!isset($workload['attributes']['shacPersonalUniqueID'][0])) {
            // If CPr is not set, then the connector should return an error 
            // response to the AttributeCollector
        }

        if (preg_match('/^urn:mace:terena.org:schac:personalUniqueID:dk:CPR:[0-9]{10}$/', $workload['attributes']['shacPersonalUniqueID'][0])) {
            var_dump($workload);
            exit;
        } 
        $cpr = substr($workload['attributes']['shacPersonalUniqueID'][0], -10);

        $params['cpr'] = $workload['attributes']['shacPersonalUniqueID'][0];
        $params['ukey'] = $workload['options']['userkey'];

        // Init signer
        $signer = new \WAYF\Security\Signer\GetRequestSigner();
        $signer->setUp($workload['options']['key'], $params);

        $params['signature'] = $signer->sign();

        $query = http_build_query($params);

        // Get result from CPR
        $result = file_get_contents('http://culr.test.wayf.dk/?' . $query);

        // Process the returned data and pu on right form
        $decodedresult = json_decode($result, true);

        $parsedresult = new \WAYF\ConnectorResponse();
        $parsedresult->statuscode = $decodedresult['status']['code']; 
        $parsedresult->responseid = $decodedresult['id'];

        if ($parsedresult->statuscode == 0) {
            $parsedresult->userid = $decodedresult['userid'];
            $parsedresult->attributes['Provider-ID'] = $decodedresult['attributes']['Provider']['Provider-ID'];
            $parsedresult->attributes['Provider-ID-type'] = $decodedresult['attributes']['Provider']['Provider-ID-type'];
            $parsedresult->attributes['Local-ID-value'] = $decodedresult['attributes']['Local-ID']['Local-ID-value'];
            $parsedresult->attributes['Local-ID-type'] = $decodedresult['attributes']['Local-ID']['Local-ID-type'];
            $parsedresult->attributes['Muncipality-number'] = $decodedresult['attributes']['Muncipality-number'];
        } else if (isset($decodedresult['status']['message'])) {
            $parsedresult->statusmsg = $decodedresult['status']['message']; 
        }

        // Store result
        $this->_store->set($handle, $parsedresult->toJSON());
    }

    public function setStore(\WAYF\Store $store)
    {
        $this->_store = $store;
    }

    public function setConfig(array $config)
    {
        $this->_config = $config;
    }

    public function setup() {}
}
