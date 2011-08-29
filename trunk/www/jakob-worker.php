<?php
include '_init.php';

$func = new \WAYF\Connector\Job\CprJob();

$worker = new \WAYF\Connector\Worker\JakobWorker();

$worker->addWork('GetUser', $func);

$worker->work();
