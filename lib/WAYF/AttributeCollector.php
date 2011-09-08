<?php
namespace WAYF;

class AttributeCollector {

    private $_logger = null;
    private $_client = null;
    private $_config = null;

    private $_async_jobs = array();

    public function __construct() {}

    public function setLogger($logger)
    {
        $this->_logger = $logger;
    }

    public function setClient(\WAYF\Client $client)
    {
        $this->_client = $client;
    }

    public function setConfig(array $config)
    {
        $this->_config = $config;
    }
    
    public function processTasks(array $tasks, array $attributes) {

        foreach($tasks AS $kj => $vj) {
            $workload = isset($vj['_options']) ? array('attributes' => $attributes, 'options' => $vj['_options']) : array('attributes' => $attributes);
            $taskid = isset($vj['_id']) ? $vj['_id'] : null;

            if($vj['_priority'] == 'sync') {
                $attributes = $this->fetchResults($attributes);
                $this->_async_jobs[] = $this->_client->doAsync($taskid, json_encode($workload));
                $attributes = $this->fetchResults($attributes);
            } else if($vj['_priority'] == 'async') {
                $this->_async_jobs[] = $this->_client->doAsync($taskid, json_encode($workload));
            }
        }
        $attributes = $this->fetchResults($attributes);

        return $attributes;
    }

    public function fetchResults($attributes)
    {
        while (!empty($this->_async_jobs)) {
            foreach ($this->_async_jobs AS $key => $jobid) {
                if ($job_res = $this->_client->getResult($jobid)) {
                    $attributes = array_merge_recursive($attributes, $job_res);
                    unset($this->_async_jobs[$key]);
                }
            }
            if (!empty($this->_async_jobs)) {
                usleep($this->_config['waittime']);
            }    
        }
        return $attributes;
    }
}
