<?php
class ConfigurationTest extends PHPUnit_Framework_TestCase
{
    public function testLoadConfig()
    {
        $config = array();

        $configObj = new WAYF\Configuration();

        $this->assertTrue($configObj->loadConfig($config));
    }
}
