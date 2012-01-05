<?php
include '_init.php';

// Protection against session fixation attacks
session_regenerate_id(true);

/**
 * If JAKOB.id is set and $_POST['token'] is equale, then we are resuming 
 * execution
 */
if ((isset($_SESSION['JAKOB.id']) && isset($_POST['token'])) && $_SESSION['JAKOB.id'] == $_POST['token']) {
    unset($_SESSION['JAKOB.id']);
    $session = unserialize($_SESSION['JAKOB_Session']);
    $tasks = $session['tasks'];
    $attributes = $session['attributes'];
    $returnurl = $session['returnURL'];
    $returnmethod = $session['returnMethod'];
    $pendingjobs = $session['pendingjobs'];
    $returnparams = $session['returnParams'];
} else {
    // Process the request
    try {
        $request = new \WAYF\Request($jakob_config['database']);
        $request->handleRequest();
        // Get job configuration
        $jc = new \WAYF\JobConfigurationHelper($jakob_config['database']);
        $tasks = $jc->load($request->getJobid());
        $attributes = $request->getAttributes();
        $returnurl = $request->getReturnURL();
        $returnmethod = $request->getReturnMethod();
        $returnparams = $request->getReturnParams();
    } catch(\WAYF\RequestException $re) {
        $data = array('errortitle' => 'Request error', 'errormsg' => $re->getMessage());
        $template->setTemplate('error')->setData($data)->render();
    }
}

// Setup the attribute collector
$attr_col = new \WAYF\AttributeCollector();
$attr_col->setLogger($logger);
$storage = new \WAYF\Store\MemcacheStore();
$storage->initialize();
$client = new \WAYF\Client\JakobClient($jakob_config['gearman.jobservers']);
$client->setStorage($storage);
$attr_col->setClient($client);

try {
    $attr_col->setAttributes($attributes);
    $attr_col->setTasks($tasks);
    if (isset($pendingjobs)) {
        $attr_col->setPendingJobs($pendingjobs);
    }
    $attributes = $attr_col->processTasks();
} catch(WAYF\Exceptions\TimeoutException $e) {
    $session = array(
        'attributes' => $attr_col->getAttributes(),
        'tasks' => $attr_col->getTasks(),
        'pendingjobs' => $attr_col->getPendingJobs(),
        'returnURL' => $returnurl,
        'returnMethod' => $returnmethod,
        'returnParams' => $returnparams
    );
    $_SESSION['JAKOB_Session'] = serialize($session);
    $_SESSION['JAKOB.id'] = \WAYF\Utilities::generateID();
    $template->setTemplate('timeout')->setData(array('token' => $_SESSION['JAKOB.id']))->render();
} catch(\Exception $e) {
    var_dump($e);
    $data = array('errortitle' => 'An error has occured', 'errormsg' => $e->getMessage());
    $template->setTemplate('error')->setData($data)->render();
}

// Destroy session
$_SESSION = array();
// Set-Cookie to invalidate the session cookie
if (isset($_COOKIES[session_name()])) { 
    $params = session_get_cookie_params();
    setcookie(session_name(), '', 1, $params['path'], $params['domain'], $params['secure'], isset($params['httponly']));
}
session_destroy();

$data = array();

foreach($returnparams AS $k => $v) {
    $data[$k] = $v;
}
$data['attributes'] = json_encode($attributes);

// Return the result
if ($returnmethod == 'post') {
    $data = array('post' => $data, 'destination' => $returnurl);
} else if ($returnmethod == 'get') {
    $data = array('url' => $returnurl . '?' . http_build_query($data));
}
$template->setTemplate($returnmethod)->setData($data)->render();
