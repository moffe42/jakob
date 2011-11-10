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

        $this->_attributes = (isset($data['attributes']) && !empty($data['attributes'])) ? json_decode($data['attributes'], true) : array();  
        if ((json_last_error() != JSON_ERROR_NONE ) && is_null($this->_attributes)) {
            throw new RequestException('Attributes - ' . JsonHelper::errornoToString(json_last_error()));
        }
        if (!isset($data['returnURL']) || $this->_returnURL = urldecode($data['returnURL'])) {
            throw new RequestException('No return URL found');
        }
        /*
         * FILTER_VALIDATE_URL has a bug. Fixed in PHP > 5.3.2
        if (!filter_var($this->_returnURL, FILTER_VALIDATE_URL)) {
            throw new RequestException('Supplied return URL is not valid');
        }
         */
        $this->_returnParams = (isset($data['returnParams']) && !empty($data['returnParams'])) ? json_decode($data['returnParams'], true) : array();
        if ((json_last_error() != JSON_ERROR_NONE ) && is_null($this->_returnParams)) {
            throw new RequestException('Return parameters - ' . JsonHelper::errornoToString(json_last_error()));
        }
        $this->_returnMethod = isset($data['returnMethod']) ? $data['returnMethod'] : 'post';  
        $this->_options = (isset($data['options']) && !empty($data['options'])) ? json_decode($data['options'], true) : array();
        if ((json_last_error() != JSON_ERROR_NONE ) && is_null($this->_options)) {
            throw new RequestException('Options - ' . JsonHelper::errornoToString(json_last_error()));
        }

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
