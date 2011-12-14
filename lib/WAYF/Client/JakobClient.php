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
namespace WAYF\Client;

/**
 * @uses
 */
use WAYF\Client;

/**
 * Client implementation using Gearman
 */
class JakobClient implements Client
{
    private $_gclient = null;

    private $_storage = null;

    private $_jobs = array();
    
    public function __construct($servers = '127.0.0.1')
    {
        $this->_gclient = new \GearmanClient();
        $this->_gclient->addServers($servers);
    }

    public function setStorage(\WAYF\Store $storage) 
    {
        $this->_storage = $storage;
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
        $this->_jobs[] = $this->_gclient->doBackground($name, $workload);
        return end($this->_jobs);
    }

    /**
     * Check if job is done
     *
     * @param string $job_handler Gearman job handler
     * @return bool True if job is done othervise false
     */
    public function isDone($job_handler)
    {
        $stat = $this->_gclient->jobStatus($job_handler);
        return !$stat[0];
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
        // Loop throug all job and check if they are done
        foreach($this->_jobs AS $jkey => $job) {
            $stat = $this->_gclient->jobStatus($job);
            if ($stat[0]) {
                return false;
            } else {
                if ($removedone) {
                    unset($this->_jobs[$jkey]);
                }
            }
        }

        return true;
    }

    /**
     * Reset client
     *
     * Removes all internal job handlers
     */
    public function reset()
    {
        $this->_jobs = Array();
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
        if ($this->isDone($job_handler)) {
            //$result =  json_decode($this->_storage->get($job_handler), true);
            $result = $this->_storage->get($job_handler);
            $response = new \WAYF\ConnectorResponse();
            $response->fromJSON($result);
            //if ($result['status']['code'] == STATUS_SUCCESS) {
            if ($response->statuscode == STATUS_SUCCESS) {
                return $response->attributes;
            } else {
                throw new \WAYF\ClientException($response->statusmsg, $response->statuscode);
            }
        }
        return false;
    }
}
