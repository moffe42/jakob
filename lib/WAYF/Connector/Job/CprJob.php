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
namespace WAYF\Connector\Job;

/**
 * @uses
 */
use WAYF\Connector\Job;

/**
 * CPR job
 *
 * Implementation of a job that fetches information from CPR. 
 */
class CprJob implements Job
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
        $mc = new \WAYF\Store\MemcacheStore();
        $mc->initialize();

        $signer = new \WAYF\Security\Signer\GetRequestSigner();

        $workload = $job->workload();
        $workloadd = json_decode($workload, true);
        $md5cpr = md5($workloadd['cpr']);
        $ukey  ='wayf';
        $key = 'wayf4ever';

        $params['cid'] = $md5cpr;
        $params['ukey'] = $ukey;

        $signer->setUp($key, $params);

        $params['signature'] = $signer->sign();

        $query = http_build_query($params);

        $result = file_get_contents('http://cpr.test.wayf.dk/?' . $query);

        $mc->set($job->handle(), $result);
    }
}
