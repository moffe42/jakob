<?php

namespace WAYF;

class ConsumerException extends \Exception {}

class Consumer
{
    private $_config = null;
    private $_db = null;

    public $consumerkey;
    public $consumersecret;

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

        try {
            $this->_db = new \WAYF\DB($dsn, $username, $password);
        } catch (\PDOException $e) {
            throw new ConsumerException('Error connecting to JAKOB database'); 
        }
    }

    public function load()
    {
        if (empty($this->consumerkey)) {
            throw new ConsumerException('Consumer key not set');  
        }

        if (is_null($this->_db)) {
            $this->_init();
        }

        $query = "SELECT * FROM `jakob__consumer` WHERE `consumerkey` = :consumerkey;";

        try {
            $res = $this->_db->fetch_one($query, array('consumerkey' => $this->consumerkey));
        } catch (\PDOException $e) {
            throw new ConsumerException('Could not find consumer: ' . $this->consumerkey); 
        }

        if (!$res) {
            throw new ConsumerException('Could not find consumer: ' . $this->consumerkey); 
        }

        $this->consumersecret = $res->consumersecret;

        return $this;
    }
}
