<?php

namespace WAYF;

class JobConfigurationLoaderException extends \Exception {}

class JobConfigurationLoader
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

            $jobconfig = new \WAYF\JobConfiguration();
            $jobconfig->fromArray($job);

        } else {
            throw new JobConfigurationLoaderException('Job configuration "' . $id . '" does not exists'); 
        }
        return $jobconfig;
    }
}
