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
 * @version    $Id: JakobClient.php 37 2011-08-29 10:52:06Z jach@wayf.dk $
 * @link       $URL: https://jakob.googlecode.com/svn/trunk/lib/WAYF/Connector/Client/JakobClient.php $
 */

/**
 * @namespace
 */
namespace WAYF\Client;

/**
 * @uses
 */
use WAYF\Client;

/**
 * Client implementation using Gearman
 */
class TestClient implements Client
{
    public function __construct()
    {
    }

    /**
     * Add an async job to the client.
     *
     * Th client will dispatch the job to the GearmanJob server as a 
     * background job. The rresult of the job can be fetched by passing the 
     * returned job handler to the getResult method.
     *
     * @param string $name Name of the work to be done
     * @param string $workload Additional data required for the worker to 
     * complete the job. JSON encode complex data.
     *
     * @return string Job handler for dispatched job 
     */
    public function doAsync($name, $workload)
    {
        return uniqid();
    }

    public function doSync($name, $workload)
    {
        return array(uniqid() . "-Result" => uniqid());
    }

    /**
     * Check if job is done
     *
     * @param string $job_handler Gearman job handler
     * @return bool True if job is done othervise false
     */
    public function isDone($job_handler)
    {
        return true;
    }

    /**
     * Check if all jobs are done
     *
     * Check the status of all jobs parsed to the client. If at least one job 
     * is not finished, false is returned. If all jobs are finished, true is 
     * returned.
     *
     * @param bool $removedone If set to true, that all finished jobs are 
     * removed from job queue
     * @return bool True is all jobs are done
     */
    public function isAllDone($removedone = false)
    {
        return true;
    }

    /**
     * Reset client
     *
     * Removes all internal job handlers
     */
    public function reset()
    {
    }

    /**
     * Get result of job
     *
     * If the job is done, then the result of the job is returned. If the job 
     * is not done, then false is returned.
     *
     * @param string $job_handler Gearman job handler
     * @return bool|mixed Result from job or false
     */
    public function getResult($job_handler)
    {
       return array("Sync-Result" => $job_handler);
    }
}
