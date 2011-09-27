<?php
namespace WAYF;

class StoreFactory
{
    // The parameterized factory method
    public static function createInstance($config)
    {
        $classname = "WAYF\Store\\" . $config['type'] . "Store";
        if (!class_exists($classname, true)) {
            throw new \InvalidArgumentException($config['type'] . ' store do not exists');
        }
        $store = new $classname($config['options']);
        return $store;
    }
}
