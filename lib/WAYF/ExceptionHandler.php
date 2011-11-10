<?php

namespace WAYF;

class ExceptionHandler {
    private $_logger = null;

    public function setLogger(\WAYF\Logger $logger)
    {
        $this->_logger = $logger;
    }

    public function handleException($exception)
    {
        if (!is_null($this->_logger)) {
            $trace = $this->_buildTrace($exception);

            $this->_logger->log(JAKOB_ERROR, $exception->getMessage());
            foreach ($trace AS $line) {
                $this->_logger->log(JAKOB_ERROR, $line);
            }
        }

        $data = array(
            'errortitle' => 'Unhandled error',
            'errormsg' => $exception->getMessage(),    
        );
        $template = new \WAYF\Template();
        $template->setTemplate('error')->setData($data)->render(); 
    }

    private function _buildTrace(\Exception $exception)
    {
        $backtrace = array();

        /* Position in the top function on the stack. */
        $pos = $exception->getFile() . ':' . $exception->getLine();

        foreach($exception->getTrace() as $t) {

            $function = $t['function'];
            if(array_key_exists('class', $t)) {
                $function = $t['class'] . '::' . $function;
            }

            $backtrace[] = $pos . ' (' . $function . ')';

            if(array_key_exists('file', $t)) {
                $pos = $t['file'] . ':' . $t['line'];
            } else {
                $pos = '[builtin]';
            }
        }

        $backtrace[] = $pos . ' (N/A)'; 
        return $backtrace;
    }
}
