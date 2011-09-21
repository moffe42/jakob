<?php
namespace WAYF;

class AttributeCollector {

    private $_logger = null;
    private $_client = null;
    private $_config = null;

    private $_async_jobs = array();
    private $_attributes = array();
    private $_tasks = array();

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

    public function setAttributes(array $attributes)
    {
        $this->_attributes = $attributes;
    }

    public function setTasks(array $tasks)
    {
        $this->_tasks = $tasks;
    }

    public function getAttributes() 
    {
        return $this->_attributes;
    }

    public function getTasks()
    {
        return $this->_tasks;
    }

    public function getPendingJobs() 
    {
        return $this->_async_jobs;
    }

    public function setPendingJobs(array $pendingjobs)
    {
        $this->_async_jobs = $pendingjobs;
    }

    public function processTasks()
    {
        $this->startTimer();
        foreach($this->_tasks AS $kj => $vj) {
            $workload = isset($vj['_options']) ? array('attributes' => $this->_attributes, 'options' => $vj['_options']) : array('attributes' => $this->_attributes);
            $taskid = isset($vj['_id']) ? $vj['_id'] : null;

            if($vj['_priority'] == 'sync') {
                $this->fetchResults();
                $this->_async_jobs[] = $this->_client->doAsync($taskid, json_encode($workload));
                $this->fetchResults();
            } else if($vj['_priority'] == 'async') {
                $this->_async_jobs[] = $this->_client->doAsync($taskid, json_encode($workload));
            }
            unset($this->_tasks[$kj]);
        }
        $this->fetchResults();

        return $this->_attributes;
    }

    private function fetchResults()
    {
        while (!empty($this->_async_jobs)) {
            if ($this->timeElapsed() > 5) {
                throw new \WAYF\Exceptions\TimeoutException('Timeout');
            }
            foreach ($this->_async_jobs AS $key => $jobid) {
                if ($job_res = $this->_client->getResult($jobid)) {
                    $this->_attributes = array_merge_recursive($this->_attributes, $job_res);
                    unset($this->_async_jobs[$key]);
                }
            }
            if (!empty($this->_async_jobs)) {
                usleep($this->_config['waittime']);
            }    
        }
    }

    private function startTimer()
    {
        $this->_time = microtime(TRUE);
    }

    private function timeElapsed()
    {
        return microtime(TRUE) - $this->_time;
    }
}
