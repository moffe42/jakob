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
namespace WAYF\Logger;

/**
 * @uses WAYF\Logger
 */
use WAYF\Logger;

/**
 * File logger
 *
 * Implements the \WAYF\Logger interface to provide an logger that will write 
 * all logging to disc.
 *
 * @author Jacob Christiansen <jach@wayf.dk>
 */
class FileLogger implements Logger
{
    private $_file;

    public function __construct(array $options)
    {
        if (isset($options['file']) && is_string($options['file'])) {
            $file = new \SplFileInfo(LOGROOT . $options['file']);

            if (!$file->isFile()) {
                // Check that log directory is writable
                $logpath = new \SplFileInfo(LOGROOT);
                if (!$logpath->isWritable()) {
                    throw new \WAYF\LoggerException('Log directory is not writable.');
                }

                // Filen eksisterer ikke, prøver at oprette den
                try {
                    $this->_file = $file->openFile('w+');
                } catch (\RuntimeException $e) {
                    throw new \WAYF\LoggerException('Log file could not be created: ' . $e->getMessage());
                }
            } else if ($file->isWritable()){
                // Filen eksisterer og er skrivbar
                $this->_file = $file->openFile('a+');
            } else {
                // Filen eksisterer, men er ikke srivbar
                throw new \WAYF\LoggerException('Log file not available for writing: ' . $file->getRealPath());
            }
        } else {
            throw new \WAYF\LoggerException('Log file configuration not correct');
        }
    }

    /**
     * Log message
     *
     * @param  $level   Severity level
     * @param  $message Log message
     * @return void
     */
    public function log($level, $message)
    {
        $line = sprintf("%s - %s - %s \n", strftime("%b %d %H:%M:%S"), $level, $message);

        $this->_file->fwrite($line);
    }
}
