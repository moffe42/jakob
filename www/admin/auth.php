<?php

// Protection against session fixation attacks
session_regenerate_id(true);

$metadata = \WAYF\configuration::getConfig('adminauthmd.php');
$users = \WAYF\configuration::getConfig('adminusers.php');

function sporto($md, $providerids){
    $res = new \WAYF\SAML();
    if (isset($_POST['SAMLResponse'])) {
        $res->receiveResponse($_POST['SAMLResponse'], $md);
        return $res->getAttributes();
    } else {
        $res->sendRequest($providerids, $md);
    }
}

if (!isset($_SESSION['loginok'])) {
    $attributes = sporto($metadata, null);
    if (in_array($attributes['eduPersonPrincipalName'][0], $users)) {
        $_SESSION['loginok'] = true;
    } else {
        trigger_error('You are not authorized to access the JAKOB admin interface');
    }
}
