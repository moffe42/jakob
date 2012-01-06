<?php
include '_init.php';

if (!isset($_GET['ecode']) || !ctype_digit($_GET['ecode'])) {
    $data = array('errortitle' => 'Missing error code', 'errormsg' => 'No error code was set');
    $template->setTemplate('error')->setData($data)->render();
}

$errorcode = (int)$_GET['ecode'];

include CONFIGROOT . 'errordescriptions.php';

$data = array(
    'errortitle' => $errordescriptions[$errorcode]['title'],
    'errordescription' => $errordescriptions[$errorcode]['description'],
);

$template->setTemplate('errorexplanation')->setData($data)->render();
