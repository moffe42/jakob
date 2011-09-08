<?php
/**
 * JAKOB
 *
 * @category   WAYF
 * @package    JAKOB
 * @subpackage Configuration
 * @author     Jacob Christiansen <jach@wayf.dk>
 * @copyright  Copyright (c) 2011 Jacob Christiansen, WAYF (http://www.wayf.dk)
 * @license    http://www.opensource.org/licenses/mit-license.php MIT License
 * @version    $Id$
 * @link       $URL$
 */

/**
 * @namespace
 */
namespace WAYF;

/**
 * Configuration class
 *
 * Class for holding and processing configuration.
 *
 * @author Jacob Christiansen <jach@wayf.dk>
 */
class Configuration
{

    /**
     * Holds the configuration options
     *
     * @var array|NULL
     */
    protected $_configuration = NULL;

    /**
     * Loads a parsed configuration
     *
     * @param  array $config Configuration
     * @return void
     */
    public static function getConfig($config = 'config.php')
    {
        require CONFIGROOT . DIRECTORY_SEPARATOR . $config;

        return $config;
    }
}
