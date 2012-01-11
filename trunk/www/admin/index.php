<?php
include '../_init.php';

// Protection against session fixation attacks
session_regenerate_id(true);

$metadata = array(
    'idp_certificate' => 'MIIEZzCCA0+gAwIBAgILAQAAAAABID3xVZIwDQYJKoZIhvcNAQEFBQAwajEjMCEGA1UECxMaT3JnYW5pemF0aW9uIFZhbGlkYXRpb24gQ0ExEzARBgNVBAoTCkdsb2JhbFNpZ24xLjAsBgNVBAMTJUdsb2JhbFNpZ24gT3JnYW5pemF0aW9uIFZhbGlkYXRpb24gQ0EwHhcNMDkwMzI1MTMwNTE0WhcNMTIwNTA5MDcwNzU3WjCBgzELMAkGA1UEBhMCREsxETAPBgNVBAgTCE9kZW5zZSBNMREwDwYDVQQHEwhPZGVuc2UgTTEbMBkGA1UECxMSV0FZRiAtIFNlY3JldGFyaWF0MR0wGwYDVQQKExRTeWRkYW5zayBVbml2ZXJzaXRldDESMBAGA1UEAxQJKi53YXlmLmRrMIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDBsuiyO84OVwkKR0TL6w8viWV4jMg+Jy7LgiEtYfHdnVBCvdM9XJJetS0MiJtulBH4/4ZWrfeGeHgLPvSjp6FiRdI1nDg/33ofc0TdNytxX4tBCzvxM0C4yCCaEXda+tqXJmGua+mVubMhS8kizHjL+s7A8xUqXoEFqOMHtgqoAQIDAQABo4IBdjCCAXIwHwYDVR0jBBgwFoAUfW0q7Garp1E2qwJp8XCPxFkLmh8wSQYIKwYBBQUHAQEEPTA7MDkGCCsGAQUFBzAChi1odHRwOi8vc2VjdXJlLmdsb2JhbHNpZ24ubmV0L2NhY2VydC9vcmd2MS5jcnQwPwYDVR0fBDgwNjA0oDKgMIYuaHR0cDovL2NybC5nbG9iYWxzaWduLm5ldC9Pcmdhbml6YXRpb25WYWwxLmNybDAdBgNVHQ4EFgQUvlkjTc0iuzcvi752QgktLT01obgwCQYDVR0TBAIwADAOBgNVHQ8BAf8EBAMCBaAwKQYDVR0lBCIwIAYIKwYBBQUHAwEGCCsGAQUFBwMCBgorBgEEAYI3CgMDMEsGA1UdIAREMEIwQAYJKwYBBAGgMgEUMDMwMQYIKwYBBQUHAgEWJWh0dHA6Ly93d3cuZ2xvYmFsc2lnbi5uZXQvcmVwb3NpdG9yeS8wEQYJYIZIAYb4QgEBBAQDAgbAMA0GCSqGSIb3DQEBBQUAA4IBAQCKPVJYHjKOrzWtjPBTEJOwIzE0wSIcA+9+GNR5Pvk+6OTf2QTUDDHpXiiIEcYPL1kN/BEvA+N2y+7qyI5MlL7DNIu9clx1lcqhXiQ0lWcu7Bmb7VNPKq5WS1W81GhbZrO6BJtsQctU6odDXMoORay7FxnaxGHOaJlCSQDgT7QrRhzyd80X8NxrSV25byCTb31du8xoO+WagnqAp6xbKs6IsESDw2r/i3rLOXbL37B7lnbjcLC963xN6j7+kiyqiCjvrP0GLfSV4/FN9i9hWrdMlcbnvr23yz5Jflc1oFPtJx7GZqtV0uTijGxCr+aRaUzBPqc3kyavHJcCsn5TcL1t', 
    'sso' => 'https://testbridge.wayf.dk/saml2/idp/SSOService.php',
    'sp_key' => "INSERT OWN PRIVATE KEY",
    'asc' => 'http://jakob.test.wayf.dk/admin/',
    'sp' => 'http://jakob.test.wayf.dk/admin/'
);

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
    if ($attributes['eduPersonPrincipalName'] == 'jj@testidp.wayf.dk') {
        $_SESSION['loginok'] = true;
    } else {
        trigger_error('You are not authorized to access the JAKOB admin interface');
    }
}

$actions = array(
    'list',
    'edit',
    'save' ,
    'create'   
);

if (isset($_REQUEST['action']) && in_array($_REQUEST['action'], $actions)) {
    $action = $_REQUEST['action'];
} else {
    $action = 'list';
}


// Init DB
$dsn      = $jakob_config['database']['dsn'];
$username = $jakob_config['database']['username'];
$password = $jakob_config['database']['password'];
$table    = $jakob_config['database']['table'];

try {
    $db = new \WAYF\DB($dsn, $username, $password);
} catch (\PDOException $e) {
    echo "<pre>";
    var_dump($e);
    exit;
    //throw new JobConfigurationLoaderException('Error connecting to JAKOB database'); 
}


switch ($action) {
    case 'create':
        $query = "INSERT INTO `" . $table . "` (`id`, `jobid`, `name`, `configuration`) VALUES(NULL, '', 'New configuration', '');";
        $jobid = $db->insert($query);
    case 'edit':
        if (isset($_REQUEST['jobid'])) {
            $jobid = $_REQUEST['jobid'];
        }
        
        $query = "SELECT * FROM `" . $table . "` WHERE `id` = :jobid;";

        $res = $db->fetch_one($query, array('jobid' => $jobid));

        $data = array(
            'config' => $res
        );
        
        $spmetadata = file_get_contents('../../config/metadata/wayf-sp.xml');
        $idpmetadata = file_get_contents('../../config/metadata/wayf-idp.xml');

        $data['spmd'] = \WAYF\SAML\XmlToArray::xml2array($spmetadata);
        $data['idpmd'] = \WAYF\SAML\XmlToArray::xml2array($idpmetadata);

        $template->setTemplate('admin-edit')->setData($data)->render();
        break;
    case 'save':
        $stringconfig = '$config = ' . $_REQUEST['config'] . ';';
        eval($stringconfig);
        
        $query = "UPDATE  `" . $table . "` SET `jobid` = :jobid, `name` = :name, `targetsp` = :targetsp, `targetidp` = :targetidp, `configuration` = :config WHERE `id` = :id;";

        $tmp = $db->modify($query, array(
            'id' => (int)$_REQUEST['id'],    
            'jobid' => \WAYF\Utilities::getjobHash($_REQUEST['targetidp'], $_REQUEST['targetsp'], $jakob_config['salt']),    
            'name' => $_REQUEST['name'], 
            'targetsp' => $_REQUEST['targetsp'], 
            'targetidp' => $_REQUEST['targetidp'], 
            'config' => serialize($config)    
        ));

        header('Location: /admin/');
        exit;
    case 'list':
    default:
        // Grab job configuration
        $query = "SELECT * FROM `" . $table . "`;";

        try{
            $res = $db->fetch_all($query);
        } catch (\PDOException $e) {
            var_dump($e);
            exit;
        }

        $data = array(
            'configs' => $res    
        );

        $template->setTemplate('admin-list')->setData($data)->render();
        break;
}
