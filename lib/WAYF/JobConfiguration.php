<?php
namespace WAYF;

class JobConfigurationException extends \Exception {}
/**
 * Binary tree
 *
 * Simple binary tree for storing basic binary trees. NOTE this is not a binary 
 * search tree. It is up to the user to insert and delete the correct nodes.
 */
class JobConfiguration {
    public $success = null;
    public $fail = null;
    public $data = null;
    public $timestart = null;

    public function toArray($i = 0, &$array = array()) {

        $array[$i]['data'] = $this->data;
        $array[$i]['timestart'] = $this->timestart;

        if (!is_null($this->success)) {
            $array[$i]['success'] = 2*$i+1;
            $this->success->toArray(2*$i+1, $array);
        }
        if (!is_null($this->fail)) {
            $array[$i]['fail'] = 2*$i+2;
            $this->fail->toArray(2*$i+2, $array);
        }
        ksort($array);
        return $array;
    }
    
    public function fromArray($array, $i = 0) {
        $this->data = $array[$i]['data'];

        if (isset($array[$i]['timestart'])) {
            $this->timestart = $array[$i]['timestart'];
        }

        if (isset($array[$i]['success'])) {
            $success = new JobConfiguration();
            $success->fromArray($array, $array[$i]['success']);
            $this->success =& $success;
        }
        if (isset($array[$i]['fail'])) {
            $fail = new JobConfiguration();
            $fail->fromArray($array, $array[$i]['fail']);
            $this->fail =& $fail;
        }
    }
}
