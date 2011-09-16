<?php
namespace WAYF;

class LoggerFactory
{
    // The parameterized factory method
    public static function createInstance($config)
    {
        $classname = "WAYF\Logger\\" . $config['type'] . "Logger";
        if (class_exists($classname, true)) {
            $logger = new $classname($config['options']);
            return $logger;
        }
        return null;
    }
}
