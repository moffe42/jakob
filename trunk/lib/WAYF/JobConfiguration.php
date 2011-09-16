<?php

namespace WAYF;

class JobConfigurationException extends \Exception {}

class JobConfiguration
{
    private $_path = '';

    public function __construct($path = null)
    {
        if (is_null($path)) {
            $this->_path = CONFIGROOT . DIRECTORY_SEPARATOR . 'jobs' . DIRECTORY_SEPARATOR;
        } else if(is_dir($path)) {
            $this->_path = $path;
        } else {
            throw new JobConfigurationException($path . ' si not a valid configuration path');
        }
    }

    public function load($id)
    {
        $file = $this->_path . $id . '.php';
        if (file_exists($file)) {
            include $file;
        } else {
            throw new JobConfigurationException('Job configuration "' . $id . '" does not exists'); 
        }
        return $job;
    }
}
