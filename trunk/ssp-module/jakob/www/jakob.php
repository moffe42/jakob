<?php
// Restore session
$session = SimpleSAML_Session::getInstance();
$state = SimpleSAML_Auth_State::loadState($_POST['StateId'], 'jakob:request');

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
