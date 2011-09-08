<?php
namespace WAYF;

class AttributeCollector {

    private $_logger = null;
    private $_client = null;
    private $_config = null;

    public function __construct() {}

    public function setLogger($logger)
    {
        $this->_logger = $logger;
    }

    public function setClient(\WAYF\Connector\Client $client)
    {
        $this->_client = $client;
    }

    public function setConfig(array $config)
    {
        $this->_config = $config;
    }
    
    public function processTasks(array $tasks, array $attributes) {
        $async_jobs = array();
        $waitforresult = false;

        foreach($tasks AS $kj => $vj) {
            $workload = isset($vj['_options']) ? array('attributes' => $attributes, 'options' => $vj['_options']) : array('attributes' => $attributes);
            $taskid = isset($vj['_id']) ? $vj['_id'] : null;

            if($vj['_priority'] == 'sync') {
                $async_jobs[] = $this->_client->doAsync($taskid, json_encode($workload));
                $waitforresult = true;
            } else if($vj['_priority'] == 'async') {
                $async_jobs[] = $this->_client->doAsync($taskid, json_encode($workload));
                $waitforresult = false;
            } else if ($vj['_priority'] == 'batch') {
                $attributes = $this->processTasks($vj['tasks'], $attributes);
            } 
            // Wait for async jobs to finish
            if ($waitforresult) {
                while (!empty($async_jobs)) {
                    foreach ($async_jobs AS $key => $jobid) {
                        if ($job_res = $this->_client->getResult($jobid)) {
                            $attributes = array_merge_recursive($attributes, $job_res);
                            unset($async_jobs[$key]);
                        }
                    }
                    if (!empty($async_jobs)) {
                        echo "Sleeping\n";
                        usleep(300);
                    }    
                }
            }
        }
        return $attributes;
    }
}
