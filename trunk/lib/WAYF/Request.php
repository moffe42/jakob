<?php
namespace WAYF;

class RequestException extends \Exception {}

class Request
{
    private $_jobid = '';
    private $_attributes = '';
    private $_returnURL = '';
    private $_returnParams = '';
    private $_returnMethod = 'post';
    private $_options = '';
    private $_config = array();

    public function __construct($config)
    {
        $this->_config = $config;
    }

    public function handleRequest()
    {
        // Parse the request URI
        $str = preg_replace("'/'", "", $_SERVER['REQUEST_URI'], 1);
        $dele = explode("/", $str);

        // Check syntax for request
        if ((string)$dele[0] != 'job') {
            throw new RequestException('Syntax for JAKOB request not valid');
        }

        // Extract job id
        $tmp = (string)$dele[1];

        if (ctype_alnum($tmp) && (strlen($tmp) <= 100)) {
            $this->_jobid = $tmp;
        } else {
            throw new RequestException('Jobid "' . $tmp . '" do not have the correct form');
        }

        // Get request method
        $request_method = strtolower($_SERVER['REQUEST_METHOD']);  

        $data = array();  

        switch ($request_method)  {  
            case 'get':  
                $data = $_GET;  
                break;  
            case 'post':  
            default:
                $data = $_POST;        
        }  

        // Validate signature on request
        if (!isset($data['consumerkey'])) {
            throw new RequestException('Consumer key not set on request');
        }
        
        if (!isset($data['signature'])) {
            throw new RequestException('Signature not set on request');
        }

        try {
            $consumer = new \WAYF\Consumer($this->_config);
            $consumer->consumerkey = $data['consumerkey'];
            $consumer->load();
        } catch(\WAYF\ConsumerException $e) {
            throw new RequestException($e->getMessage());
        }

        $signparams = $data;
        unset($signparams['consumerkey']);
        unset($signparams['signature']);

        $signer = new \WAYF\Security\Signer\GetRequestSigner();
        $signer->setUp($consumer->consumersecret, $signparams);

        if (!$signer->validate($data['signature'])) {
            throw new RequestException('Signature on request is not valid');
        }

        // Grab the attributes
        $this->_attributes = (isset($data['attributes']) && !empty($data['attributes'])) ? json_decode($data['attributes'], true) : array();  
        if ((json_last_error() != JSON_ERROR_NONE ) && is_null($this->_attributes)) {
            throw new RequestException('Attributes - ' . JsonHelper::errornoToString(json_last_error()));
        }
        // Transform attributes to internal format
        $this->_attributes = array_map(
            function ($val) {
                $return = array();
                foreach ($val AS $attr) {
                    $return[] = array('value' => $attr);
                }
                return $return;
            }, 
            $this->_attributes
        );
        
        // Grab return URL parameter
        if (!isset($data['returnURL']) || !($this->_returnURL = urldecode($data['returnURL']))) {
            throw new RequestException('No return URL found');
        }
        /*
         * FILTER_VALIDATE_URL has a bug. Fixed in PHP > 5.3.2
        if (!filter_var($this->_returnURL, FILTER_VALIDATE_URL)) {
            throw new RequestException('Supplied return URL is not valid');
        }
         */
        // Grab optional return parameters
        $this->_returnParams = (isset($data['returnParams']) && !empty($data['returnParams'])) ? json_decode($data['returnParams'], true) : array();
        if ((json_last_error() != JSON_ERROR_NONE ) && is_null($this->_returnParams)) {
            throw new RequestException('Return parameters - ' . JsonHelper::errornoToString(json_last_error()));
        }

        // Get return method
        $this->_returnMethod = isset($data['returnMethod']) ? $data['returnMethod'] : 'post';  

        /* 
        $this->_options = (isset($data['options']) && !empty($data['options'])) ? json_decode($data['options'], true) : array();
        if ((json_last_error() != JSON_ERROR_NONE ) && is_null($this->_options)) {
            throw new RequestException('Options - ' . JsonHelper::errornoToString(json_last_error()));
        }
         */

        return $this; 
    }

    public function getJobid()
    {
        return $this->_jobid;
    }
    
    public function getAttributes()
    {
        return $this->_attributes;
    }
    
    public function getReturnURL()
    {
        return $this->_returnURL;
    }
    
    public function getReturnMethod()
    {
        return $this->_returnMethod;
    }
    
    public function getReturnParams()
    {
        return $this->_returnParams;
    }
} 
