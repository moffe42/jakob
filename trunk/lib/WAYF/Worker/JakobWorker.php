<?php
/**
 * JAKOB
 *
 * @category   WAYF
 * @package    JAKOB
 * @subpackage Worker
 * @author     Jacob Christiansen <jach@wayf.dk>
 * @copyright  Copyright (c) 2011 Jacob Christiansen, WAYF (http://www.wayf.dk)
 * @license    http://www.opensource.org/licenses/mit-license.php MIT License
 * @version    $Id$
 * @link       $URL$
 */

/**
 * @namespace
 */
namespace WAYF\Worker;

/**
 * @uses
 */
use WAYF\Worker;

/**
 * Worker class based on Gearman
 */
class JakobWorker implements Worker
{
    /**
     * Gearman worker
     * @var \GearmanWorker
     */
    public $_gworker = null;

    private $_logger = null;

    /**
     * constructor
     */
    public function __construct($servers = '127.0.0.1')
    {
        $this->_gworker = new \GearmanWorker();
        $this->_gworker->addServer($servers);
    }

    public function setLogger($logger)
    {
        $this->_logger = $logger;
    }

    /**
     * Register work for the worker
     *
     * @param string The name of the work
     * @param \WAYF\Connector\Job Job object
     */
    public function addWork($name, \WAYF\Connector $obj)
    {
        $this->_gworker->addFunction($name, array($obj, 'execute'));
    }

    /**
     * Perform work
     */
    public function work()
    {
        while ($this->_gworker->work()) {
            if (GEARMAN_SUCCESS != $this->_gworker->returnCode()) {
                $this->_logger->log(JAKOB_ERROR, "Worker failed: " . $this->_gworker->error());
            }
        }
    }
}
