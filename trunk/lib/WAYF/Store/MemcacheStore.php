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
namespace WAYF\Store;

/**
 * @uses
 */
use \WAYF\Store;

/**
 * Memcahce storage
 */
class MemcacheStore implements Store
{
    /**
     * Memcache object
     * @var \Memcache
     */
    private $_mc = null;

    /**
     * Memcache servers
     * @var array
     */
    private $_servers = array();

    /**
     * Constructor
     *
     * @param array|null $config configuration array
     */
    public function __construct($config = null)
    {
        if (!isset($config['servers']) || !is_array($config['servers'])) {
            // Add default server if config is not parsed or has error
            $this->_servers[] = array(
                'host' => 'localhost', 
                'port' => '11211'
            );
            return;
        }

        foreach ($config['servers'] AS $server) {
            if (!isset($server['host'])) {
                continue;
            }

            if (!isset($config['port'])) {
                $server['port'] = '11211';
            }
            $this->_servers[] = $server;
        }
    }

    /**
     * Add a server to the memcache server pool
     *
     * @param string $host Host name/IP address
     * @param string $port Post number for the host
     */
    public function addServer($host, $port)
    {
        if ($this->_mc instanceof \Memcache) {
            $this->_mc->addServer($host, $port);
        }
    }

    /**
     * Initialize the memcache instance
     *
     * @return bool True if instanciated correctly otherwise false
     */
    public function initialize()
    {
        $this->_mc = new \Memcache();

        if (empty($this->_servers)) {
            return false;
        }

        foreach ($this->_servers AS $server) {
            $this->addServer($server['host'], $server['port']);
        }

        return true;
    }

    /**
     * Get item from memcache
     *
     * @param string $key Item key
     * @return string The item
     */ 
    public function get($key)
    {
        return $this->_mc->get($key);
    }

    /**
     * Store iotem in memcache
     *
     * @param string $key The item key
     * @param string @value The item to be stored
     * @return bool True if stored otherwise false
     */ 
    public function set($key, $value)
    {
        return $this->_mc->set($key, $value);
    }
}
