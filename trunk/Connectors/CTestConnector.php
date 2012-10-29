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
 * JAKOB Consent Test job
 *
 * Implementation of a job that fetches information from CPR. 
 */
class CTestConnector extends AbstractConnector
{
    /**
     * Fetch data from JAKOB Conset Test
     *
     * @param \GearmanJob $job Gearman job
     */ 
    public function execute(\GearmanJob $job)
    {
        $this->_logger->log(JAKOB_INFO, 'CTest - Calling CTest connector');
        $handle = $job->handle();
        
        // Process workload
        $workload = json_decode($job->workload(), true);
        
        $response = new \WAYF\ConnectorResponse();

        // The identifing attribute is missing
        if (!isset($workload['options']['userkey']) || !isset($workload['options']['key'])) { 
            $response->statuscode = STATUS_ERROR;
            $response->statusmsg = 'Missing required configuration option'; 
            $this->_store->set($handle, $response->toJSON());
            $this->_logger->log(JAKOB_ERROR, 'CTest - Missing required configuration option');
            return;
        }
        // The identifing attribute is missing
        if (!isset($workload['attributes']['eduPersonPrincipalName'][0]['value'])) { 
            $response->statuscode = STATUS_ERROR;
            $response->statusmsg = 'Missing identifing attribute: eduPersonPrincipalName'; 
            $this->_store->set($handle, $response->toJSON());
            $this->_logger->log(JAKOB_ERROR, 'CTest - Missing identifing attribute: eduPersonPrincipalName');
            return;
        }
        
        $params['ePPN'] = $workload['attributes']['eduPersonPrincipalName'][0]['value'];
        $params['ukey'] = $workload['options']['userkey'];

        // Init signer
        $signer = new \WAYF\Security\Signer\GetRequestSigner();
        $signer->setUp($workload['options']['key'], $params);
        $params['signature'] = $signer->sign();

        // Build query
        $query = http_build_query($params);

        $this->_logger->log(JAKOB_DEBUG, 'CTest - Calling: http://jakob-consent-test.test.wayf.dk/?' . $query);

        // Get result from CULR
        $result = file_get_contents('http://jakob-consent-test.test.wayf.dk/?' . $query);

        $this->_logger->log(JAKOB_DEBUG, 'CTest - Result: ' . var_export($result, true));

        sleep(4);

        // Process the returned data and pu on right form
        $decodedresult = json_decode($result, true);

        $response->statuscode = $decodedresult['status']['code']; 
        $response->responseid = $decodedresult['id'];

        if ($response->statuscode == 0) {
            $response->userid = $decodedresult['userid'];
            $response->addAttribute('schacCountryOfCitizenship',
                array(
                    'value' => $decodedresult['attributes']['schacCountryOfCitizenship'],
                    'origin' => 'http://jakob-consent-test.test.wayf.dk/',
                ) 
            );
            $response->addAttribute('eduPersonEntitlement',
                array(
                    'value' => "Din_mor",
                    'origin' => 'http://jakob-consent-test.test.wayf.dk/',
                ) 
            );
        } else if (isset($decodedresult['status']['message'])) {
            $response->statusmsg = $decodedresult['status']['message']; 
            $this->_logger->log(JAKOB_ERROR, $decodedresult['status']['message']);
        }

        // Store result
        $this->_store->set($handle, $response->toJSON());
    }
    
    public function setup() {}
}
