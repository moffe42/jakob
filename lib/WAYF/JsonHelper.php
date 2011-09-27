<?php

namespace WAYF;

class JsonHelper
{
    public static function errornoToString($errorno)
    {
        switch ($errorno) {
            case JSON_ERROR_NONE:
                $errormsg = 'No errors';
                break;
            case JSON_ERROR_DEPTH:
                $errormsg = 'Maximum stack depth exceeded';
                break;
            case JSON_ERROR_STATE_MISMATCH:
                $errormsg = 'Underflow or the modes mismatch';
                break;
            case JSON_ERROR_CTRL_CHAR:
                $errormsg = 'Unexpected control character found';
                break;
            case JSON_ERROR_SYNTAX:
                $errormsg = 'Syntax error, malformed JSON';
                break;
            case JSON_ERROR_UTF8:
                $errormsg = 'Malformed UTF-8 characters, possibly incorrectly encoded';
                break;
            default:
                $errormsg = 'Unknown error';
                break;
        }

        return $errormsg;
    }
}
