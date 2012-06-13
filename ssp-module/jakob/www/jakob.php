<?php
// Restore session
$session = SimpleSAML_Session::getInstance();
$state = SimpleSAML_Auth_State::loadState($_POST['StateId'], 'jakob:request');

// Grab attributes
$attributes = $state['Attributes'];
$jakob_attr = json_decode($_POST['attributes'], true);

// Get authenticating IdP
$idp = $session->getIdP();

// build origin array
$origin_attr = array();
foreach ($attributes AS $key => $val) {
	foreach ($val AS $v) {
		$origin_attr[$key][sha1($v)] = $idp;
	}	
}

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

// Save origin info to session
$session->setData('consent', 'origin', $origin_attr);

// Resume processing
SimpleSAML_Auth_ProcessingChain::resumeProcessing($state);
