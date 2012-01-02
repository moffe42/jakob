<?php

namespace WAYF;

class ConnectorResponseException extends \Exception {}

/**
 * Return status fra connectors, should be set to one of the following options.
 */
define('STATUS_SUCCESS', 0);
define('STATUS_ERROR', 1);
define('STATUS_TIMEOUT', 2);
define('STATUS_FATAL', 3);

class ConnectorResponse{

    public $statuscode = null;

    public $statusmsg = null;

    public $attributes = array();

    public $userid = null;

    public $responseid = null;

    public function __construct()
    {}

    public function addAttribute($name, $value) {
        $this->attributes[$name][] = $value;
    }

    public function fromJSON($json)
    {
        $dejson = json_decode($json, true);
        $this->statuscode = $dejson['status']['code'];
        if (isset($dejson['status']['message'])) {
            $this->statusmsg = $dejson['status']['message'];
        }
        if (isset($dejson['attributes'])) {
            $this->attributes = $dejson['attributes'];
        }
        if (isset($dejson['userid'])) {
            $this->userid = $dejson['userid'];
        }
        if (isset($dejson['responseid'])) {
            $this->responseid = $dejson['responseid'];
        }
    }

    public function toJSON()
    {
        $json = array(
            'id' => sha1(uniqid('', true))    
        );

        if (is_null($this->statuscode)) {
            throw new ConnectorResponseException('No status code set');
        }
        $json['status']['code'] = $this->statuscode;

        if (!is_null($this->statusmsg)) {
            $json['status']['message'] = $this->statusmsg;
        }
        if (!empty($this->attributes)) {
            $json['attributes'] = $this->attributes;
        }
        if (!is_null($this->userid)) {
            $json['userid'] = $this->userid;
        }
        if (!is_null($this->responseid)) {
            $json['responseid'] = $this->responseid;
        }

        return json_encode($json);
    }

    public function __toString()
    {
        $this->toJSON();
    }
}
