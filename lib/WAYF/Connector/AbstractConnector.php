<?php
/**
 * JAKOB
 *
 * @category   WAYF
 * @package    JAKOB
 * @subpackage Connector
 * @author     Jacob Christiansen <jach@wayf.dk>
 * @copyright  Copyright (c) 2011 Jacob Christiansen, WAYF (http://www.wayf.dk)
 * @license    http://www.opensource.org/licenses/mit-license.php MIT License
 * @version    $Id$
 * @link       $URL$
 */

/**
 * @namespace
 */
namespace WAYF\Connector;

/**
 * @uses
 */
use WAYF\Connector;

abstract class AbstractConnector implements Connector
{
    protected $_store;
    
    protected $_config;
    
    protected $_logger;

    public function setStore(\WAYF\Store $store)
    {
        $this->_store = $store;
    }

    public function setConfig(array $config)
    {
        $this->_config = $config;
    }

    public function setLogger(\WAYF\Logger $logger)
    {
        $this->_logger = $logger;
    }
}
