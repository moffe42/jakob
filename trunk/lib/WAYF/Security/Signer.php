<?php
/**
 * JAKOB
 *
 * @category   WAYF
 * @package    JAKOB
 * @subpackage Security
 * @author     Jacob Christiansen <jach@wayf.dk>
 * @copyright  Copyright (c) 2011 Jacob Christiansen, WAYF (http://www.wayf.dk)
 * @license    http://www.opensource.org/licenses/mit-license.php MIT License
 * @version    $Id$
 * @link       $URL$
 */

/**
 * @namespace
 */
namespace WAYF\Security;

/**
 * Signer interface
 *
 * @author Jacob Christiansen <jach@wayf.dk>
 */
interface Signer
{
    /**
     * Set up the signer
     * 
     * Method used for setting up the signer. Both the document and the key 
     * used for signing must be parsed. Additional options for the signer can 
     * be parsed in the options parameter.
     *
     * @param string $key The key used for signing
     * @param mixed  $document The document to sign
     * @param array  $options Additional options required by the signer
     * @return void
     */
    public function setUp($key, $document, array $options = NULL);

    /**
     * Sign method
     *
     * This method should return a valid signature on the parsed document.
     *
     * @return string A valid signature for the document
     */
    public function sign();

    /**
     * Validate signature
     *
     * This method should return true if the signature is valid
     *
     * @return bool True if signature is valid othervise false
     */
    public function validate($signature);
}
