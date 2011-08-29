<?php
/**
 * JAKOB
 *
 * @category   WAYF
 * @package    JAKOB
 * @subpackage Storage
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
 * Store interface
 *
 * Generic interface for a key/value based store
 */
interface Store
{
    /**
     * Retrive an entry
     *
     * @param int|string $key Entry key
     * @return mixed Entry value
     */
    public function get($key);

    /**
     * Set an entry
     *
     * @param int|string $key Entry key
     * @param mixed $value Entry value
     */
    public function set($key, $value);
}
