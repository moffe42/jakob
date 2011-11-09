<?php
class sspmod_jakob_Auth_Process_jakob extends SimpleSAML_Auth_ProcessingFilter
{
    private $_jConfig;

    public function __construct($config, $reserved)
    {
        $this->_jConfig = SimpleSAML_Configuration::getConfig('module_jakob.php');
    }

    public function process(&$state)
    {
        assert('is_array($state)');
        assert('array_key_exists("Destination", $state)');
        assert('array_key_exists("entityid", $state["Destination"])');

        if (isset($state['saml:sp:IdP'])) {
            $metadata        = SimpleSAML_Metadata_MetaDataStorageHandler::getMetadataHandler();
            $idpmeta         = $metadata->getMetaData($state['saml:sp:IdP'], 'saml20-idp-remote');
            $state['Source'] = $idpmeta;
        }

        assert('array_key_exists("Source", $state)');
        assert('array_key_exists("entityid", $state["Source"])');

        // Init DB
        $dsn      = $this->_jConfig->getString('dsn');
        $username = $this->_jConfig->getString('username');
        $password = $this->_jConfig->getString('password');
        $table    = $this->_jConfig->getString('table');

        try {
            $db = new sspmod_jakob_DB($dsn, $username, $password);
        } catch (PDOException $e) {
            SimpleSAML_Logger::error(
                'JAKOB: Connection to JAKOB database failed: ' .
                $e->getMessage()
            );
            throw new SimpleSAML_Error_Exception('Error connection to JAKOB');
        }

        // Get jaobhash value
        $source      = $state['Source']['entityid'];
        $destination = $state['Destination']['entityid'];
        $jobhash     = $this->getJobHash($source, $destination);
        
        // Grab job configuration
        $query = "SELECT * FROM `" . $table . "` WHERE `jobhash` = :jobhash;";

        try{
            $res = $db->fetch_one($query, array('jobhash' => $jobhash));
        } catch (PDOException $e) {
            SimpleSAML_Logger::error(
                'JAKOB: Running queryon JAKOB database failed: ' .
                $e->getMessage()
            );
            throw new SimpleSAML_Error_Exception('Error connection to JAKOB');
        }

        // redirect if job exists
        if ($res) {
            $jobid      = $res->jobid;
            $attributes = $state['Attributes'];
            $stateId    = SimpleSAML_Auth_State::saveState($state, 'jakob:request');
            $joburl     = $this->_jConfig->getString('joburl');
            $jakoburl   = $joburl . $jobid;

            SimpleSAML_Logger::info('Calling JAKOB with jobID: ' . $jobid);

            // Redirect to JAKOB
            SimpleSAML_Utilities::postRedirect($jakoburl,
                array(
                    'attributes' => json_encode($attributes),
                    'returnURL' => SimpleSAML_Module::getModuleURL('jakob/jakob.php'),
                    'returnMethod' => 'post',
                    'returnParams' => json_encode(array('StateId' => $stateId)),
                )    
            );
        }
    }

    /**
     * Calculate the jobhash value
     */
    public function getJobHash($source, $destination)
    {
        return hash('sha1', $source . '|' . $this->_jConfig->getString('salt') . '|' . $destination);
    }
}
