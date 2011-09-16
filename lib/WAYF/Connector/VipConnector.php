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
 * VIP job
 *
 * Implementation of a job that fetches information from CPR. 
 */
class VipConnector implements Connector
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
        
        $params['cid'] = md5($workload['attributes']['cpr']);
        $params['ukey'] = $workload['options']['userkey'];

        // Init signer
        $signer = new \WAYF\Security\Signer\GetRequestSigner();
        $signer->setUp($workload['options']['key'], $params);

        $params['signature'] = $signer->sign();

        $query = http_build_query($params);

        // Get result from CPR
        $result = file_get_contents('http://vip.test.wayf.dk/?' . $query);

        // Pick put the relevant data
        $decodedresult = json_decode($result, true);

        // Store result
        $this->_store->set($handle, json_encode($decodedresult));
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
