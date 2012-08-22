<?php
// Restore session
$session = SimpleSAML_Session::getInstance();
$state = SimpleSAML_Auth_State::loadState($_POST['StateId'], 'jakob:request');

$jakob_config = SimpleSAML_Configuration::getConfig('module_jakob.php');

// Validate signature on response
$signer = new sspmod_jakob_Signer();
$signparams = $_POST;
unset($signparams['signature']);
$signer->setUp($jakob_config->getString('consumersecret'), $signparams);
if (!$signer->validate($_POST['signature'])) {
	throw new SimpleSAML_Error_Exception('Signature on JAKOB response is invalid');
}

// Grab attributes
$attributes = $state['Attributes'];
$jakob_attr = json_decode($_POST['attributes'], true);
$origin_attr = (isset($state['AttributeOrigin']) && is_array($state['AttributeOrigin'])) ? $state['AttributeOrigin'] : array();

// Merge attributes recived from JAKOB
foreach ($jakob_attr AS $key => $val) {
	foreach ($val AS $k => $v) {
		if (!isset($attributes[$key]) || !in_array($v['value'], $attributes[$key])) {
			$attributes[$key][] = $v['value'];
			$origin_attr[$key][sha1($v['value'])] = isset($v['origin']) ? $v['origin'] : null;
		}
	}
}

// Save attributes to state
$state['Attributes'] = $attributes;
$state['AttributeOrigin'] = $origin_attr;

// Resume processing
SimpleSAML_Auth_ProcessingChain::resumeProcessing($state);
