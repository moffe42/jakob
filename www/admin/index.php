<?php
include '../_init.php';

// Protection against session fixation attacks
session_regenerate_id(true);

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
