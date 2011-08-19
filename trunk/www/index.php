<?php
include '_init.php';
var_dump(ROOT);

error_log('bkjdn kfdh lsfd lkds l');
$logger = new \WAYF\Logger\FileLogger();
$logger->log(1,'dd');

var_dump($_REQUEST);
