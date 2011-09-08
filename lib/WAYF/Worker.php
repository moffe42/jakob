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

/**
 * Worker interface
 */
interface Worker
{
    /**
     * Register function 
     *
     * @params string $name Remote name of the function
     * @param callback $obj Callback function 
     */
    public function addWork($name, \WAYF\Connector $obj);

    /**
     * Start the worker
     */
    public function work();
}
