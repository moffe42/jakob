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

        $this->_attributes = json_decode($data['attributes'], true);  
        $this->_returnURL = urldecode($data['returnURL']);  
        $this->_returnParams = isset($data['returnParams']) ? json_decode($data['returnParams'], true) : array();
        $this->_returnMethod = isset($data['returnMethod']) ? $data['returnMethod'] : 'post';  
        $this->_options = isset($data['options']) ? json_decode($data['options'], true) : array();

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
} 
