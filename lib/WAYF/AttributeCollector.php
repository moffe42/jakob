<?php
namespace WAYF;

class AttributeCollectorException extends \Exception {}

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
    
    public function setTasks(\WAYF\jobConfiguration $tasks = null)
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

        // Fetch results in case there are sync jobs waiting
        $this->fetchResults();

        while(!is_null($this->_tasks)) {

            $data = $this->_tasks->data;

            $workload = isset($data['_options']) ? array('attributes' => $this->_attributes, 'options' => $data['_options']) : array('attributes' => $this->_attributes);
            if (isset($data['_id'])) {
                $taskid = $data['_id'];
            } else {
                throw new \WAYF\AttributeCollectorException('Task Id non set on job');
            }
            
            if($data['_priority'] == 'sync') {
                $this->fetchResults();
                $this->_async_jobs[$this->_client->doAsync($taskid, json_encode($workload))] = $this->_tasks;
                $this->fetchResults();
            } else if($data['_priority'] == 'async') {
                $this->_async_jobs[$this->_client->doAsync($taskid, json_encode($workload))] = $this->_tasks;
                $this->_tasks = $this->_tasks->success;
            }
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
                try {
                    $job_res = $this->_client->getResult($key);
                    if ($job_res) {
                        $this->_attributes = array_merge_recursive($job_res->attributes, $this->_attributes);
                        if (is_null($this->_tasks)) {
                            $this->_tasks = null;
                        } else {
                            $this->_tasks = $jobid->success;
                        }
                        unset($this->_async_jobs[$key]);
                    }
                } catch(\WAYF\ClientException $e) {
                    // Handle the exception
                    // Some kind of error have happend. Jump to failure task
                    if ($e->getCode() == STATUS_FATAL) {
                        // Throw exception on fatal error. This should maybe be 
                        // a little more advanced
                        throw new \WAYF\AttributeCollectorException($e->getMessage());
                    }
                    $this->_tasks = $jobid->fail;
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
