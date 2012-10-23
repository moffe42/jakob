<?php
include '../_init.php';

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
$table    = 'jakob__consumer';

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
        $query = "INSERT INTO `{$table}` (`consumerkey`, `consumersecret`, `email`) VALUES ('CHANGE ME',  'CHANGE ME',  'CHANGE ME');";
        $key = $db->insert($query);
        $key = 'CHANGE ME';
    case 'edit':
        if (isset($_REQUEST['key'])) {
            $key = $_REQUEST['key'];
        }
        
        $query = "SELECT * FROM `" . $table . "` WHERE `consumerkey` = :key;";

        $res = $db->fetch_one($query, array('key' => $key));

        $data = array(
            'config' => $res
        );
        
        $template->setTemplate('admin-consumer-edit')->setData($data)->render();
        break;
    case 'save':
        $query = "UPDATE  `" . $table . "` SET `consumerkey` = :key, `consumersecret` = :secret, `email` = :email WHERE `consumerkey` = :origkey;";

        $tmp = $db->modify($query, array(
            'key' => $_REQUEST['key'], 
            'origkey' => $_REQUEST['origkey'], 
            'secret' => $_REQUEST['secret'], 
            'email' => $_REQUEST['email'], 
        ));

        header('Location: /admin/index2.php');
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

        $template->setTemplate('admin-consumer-list')->setData($data)->render();
        break;
}
