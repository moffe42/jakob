<?php
// Restore session
$state = SimpleSAML_Auth_State::loadState($_POST['StateId'], 'jakob:request');

// Grab the attributes returned from JAKOB
$state['Attributes'] = json_decode($_POST['attributes'], true);

// Resume processing
SimpleSAML_Auth_ProcessingChain::resumeProcessing($state);
