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
namespace WAYF;

class ConnectorException extends \Exception {}

/**
 * Job interface
 */
interface Connector
{
    /**
     * Default method called by worker
     * @param \GearmanJob $job Job Gearman job
     */
    public function execute(\GearmanJob $job);

    public function setStore(\WAYF\Store $store);

    public function setConfig(array $config);
    
    public function setLogger(\WAYF\Logger $logger);

    public function setup();
}

