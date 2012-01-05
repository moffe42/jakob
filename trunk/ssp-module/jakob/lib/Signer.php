<?php
/**
 * JAKOB
 *
 * @category   WAYF
 * @package    JAKOB
 * @subpackage Sequrity
 * @author     Jacob Christiansen <jach@wayf.dk>
 * @copyright  Copyright (c) 2011 Jacob Christiansen, WAYF (http://www.wayf.dk)
 * @license    http://www.opensource.org/licenses/mit-license.php MIT License
 * @version    $Id: GetRequestSigner.php 36 2011-08-26 06:32:17Z jach@wayf.dk $
 * @link       $URL: https://jakob.googlecode.com/svn/trunk/lib/WAYF/Security/Signer/GetRequestSigner.php $
 */

/**
 * GET request signer
 *
 * Implements the \WAYF\Security\Signer interface to provide a Signer for a GET 
 * request.
 *
 * @author Jacob Christiansen <jach@wayf.dk>
 */
class sspmod_jakob_Signer
{
    /**
     * Parameters to be signed
     * @var array
     */
    private $_params = null;

    /**
     * Key used for signing
     * @var string
     */
    private $_key = '';

    /**
     * Glue used to concatinate the parameters
     * @var string
     */
    private $_glue = '';

    /**
     * Set up the signer
     * 
     * Set and validate the parsed key, associative array of parameters and 
     * options.
     *
     * The following options are allowed:
     *  * 'glue' - A string used as glue when concatinating the parameters. 
     *  Default is an empty string
     *
     * @param string $key      The key used for signing
     * @param array  $document Associative array of parameters 
     * @param array  $options  Additional options
     * @throws \InvalidArgumentsException Thrown if either the key or the 
     * document parameters have wrong format/type
     */
    public function setUp($key, $document, array $options = null)
    {
        if (!is_string($key)) {
            throw new \InvalidArgumentException('The key must be of type string.');
        }
        $this->_key = $key;
        
        if (!is_array($document)) {
            throw new \InvalidArgumentException('The document to be signed must be an assotiative array.');
        }
        $this->_params = $document;

        if (isset($options['glue']) && is_string($options['glue'])) {
            $this->_glue = $options['glue'];
        }
    }


    /**
     * Sign the parameters
     *
     * This method will glue the parsed parameters together, prepend the kay 
     * and the make a SHA-512 digest of the result.
     *
     * @return string A valid signature for the parameters
     */
    public function sign()
    {
        ksort($this->_params);

        $glued_params = Array();
        foreach($this->_params AS $key => $value) {
            $glued_params[] = $key . $this->_glue . $value;
        }

        $glued_params = implode($this->_glue, $glued_params);
        
        $message = $this->_key . $glued_params;
        $signature = hash('sha512', $message);

        return $signature;
    }

    /**
     * Validate the signature
     *
     * Validates the parsed signature against the key and parameters supplied 
     * via the setUp method.
     *
     * @param string $signature Signature string
     *
     * @return bool True if signature is valid othervise false
     */
    public function validate($signature)
    {
        if (!is_string($signature)) {
            return false;
        }

        if (is_null($this->_params)) {
            return false;
        }

        return ($signature == $this->sign());
    }
}
