<?php
class sspmod_jakob_Auth_Process_jakob extends SimpleSAML_Auth_ProcessingFilter
{
    private $_jobtable = array();

    public function __construct($config, $reserved)
    {
        $jConfig = SimpleSAML_Configuration::getConfig('module_jakob.php');
        $this->_jobtable = $jConfig->getArray('jobtable', array());
    }

    public function process(&$state)
    {
        assert('is_array($state)');
        assert('array_key_exists("Destination", $state)');
        assert('array_key_exists("entityid", $state["Destination"])');
        assert('array_key_exists("metadata-set", $state["Destination"])');		
        assert('array_key_exists("entityid", $state["Source"])');
        assert('array_key_exists("metadata-set", $state["Source"])');

        $metadata = SimpleSAML_Metadata_MetaDataStorageHandler::getMetadataHandler();

        if (isset($state['saml:sp:IdP'])) {
            $idpmeta         = $metadata->getMetaData($state['saml:sp:IdP'], 'saml20-idp-remote');
            $state['Source'] = $idpmeta;
        }

        assert('array_key_exists("Source", $state)');

        $source      = $state['Source']['metadata-set'] . '|' . $state['Source']['entityid'];
        $destination = $state['Destination']['metadata-set'] . '|' . $state['Destination']['entityid'];

        $jobhash     = self::getJobHash($source, $destination);
        $jobid       = $this->_jobtable[$jobhash];

        $attributes  = $state['Attributes'];

        $id          = SimpleSAML_Auth_State::saveState($state, 'jakob:request');

        SimpleSAML_Utilities::postRedirect('http://jakob.test.wayf.dk/job/' . $jobid . '/',
            array(
                'attributes' => json_encode($state['Attributes']),
                'returnURL' => SimpleSAML_Module::getModuleURL('jakob/jakob.php'),
                'returnMethod' => 'post',
                'returnParams' => json_encode(array('StateId' => $id)),
            )    
        );
    }

    public static function getJobHash($source, $destination)
    {
        return hash('sha1', $source . '|' . SimpleSAML_Utilities::getSecretSalt() . '|' . $destination);
    }
}
