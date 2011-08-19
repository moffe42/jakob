<?php
/**
 * JAKOB
 *
 * @category   WAYF
 * @package    JAKOB
 * @subpackage Logger
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
 * Logger interface
 *
 * @author Jacob Christiansen <jach@wayf.dk>
 */
interface Logger
{
    /**
     * Log message
     * 
     * @param  $level   Severity level
     * @param  @message Log message
     * @return void
     */
    public function log($level, $message);
}
