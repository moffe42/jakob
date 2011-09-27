<?php
$attributes = json_decode($_POST['attributes'], true);

$id = $_POST['StateId'];
$state = SimpleSAML_Auth_State::loadState($id, 'jakob:request');

$state['Attributes'] = $attributes;

SimpleSAML_Auth_ProcessingChain::resumeProcessing($state);
