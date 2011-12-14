<?php

namespace WAYF;

class Utilities
{
    public static function generateID()
    {
        return '_' . sha1(mt_rand(0, 1000000));
    }
    
    public static function getJobHash($source, $destination, $salt)
    {
        return hash('sha1', $source . '|' . $salt . '|' . $destination);
    }
}
