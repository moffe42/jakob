<?php

namespace WAYF;

class JobConfigurationHelperException extends \Exception {}

class JobConfigurationHelper
{
    private $_config = null;
    private $_db = null;
    private $_table;

    public function __construct($config)
    {
        $this->_config = $config;
    }

    private function _init()
    {
        // Init DB
        $dsn          = $this->_config['dsn'];
        $username     = $this->_config['username'];
        $password     = $this->_config['password'];
        $this->_table = $this->_config['table'];

        try {
            $this->_db = new \WAYF\DB($dsn, $username, $password);
        } catch (\PDOException $e) {
            throw new JobConfigurationHelperException('Error connecting to JAKOB database'); 
        }
    }

    public function load($jobid)
    {
        if (is_null($this->_db)) {
            $this->_init();
        }

        // Grab job configuration
        if (is_int($jobid)) {
            $query = "SELECT * FROM `" . $this->_table . "` WHERE `id` = :jobid;";
        } else {
            $query = "SELECT * FROM `" . $this->_table . "` WHERE `jobid` = :jobid;";
        }

        try{
            $res = $this->_db->fetch_one($query, array('jobid' => $jobid));
        } catch (\PDOException $e) {
            throw new JobConfigurationHelperException('Could not find configuration ' . $jobid); 
        }

        $job = unserialize($res->configuration);

        if (!$job) {
            throw new JobConfigurationHelperException('Configuration ' . $jobid . ' is not valid'); 
        }

        $jobconfig = new \WAYF\JobConfiguration();
        $jobconfig->fromArray($job);

        return $jobconfig;
    }
}
