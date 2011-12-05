<?php
include '../_init.php';

// Protection against session fixation attacks
session_regenerate_id(true);


$template->setTemplate('admin')->render();
