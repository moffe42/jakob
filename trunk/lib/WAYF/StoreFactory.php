<?php
namespace WAYF;

class StoreFactory
{
    // The parameterized factory method
    public static function createInstance($type, $config)
    {
        $classname = "WAYF\Store\\" . $type . "Store";
        if (class_exists($classname, true)) {
            $store = new $classname($config);
            return $store;
        }
        return null;
    }
}
