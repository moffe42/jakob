<?php 
/* Disable caching of this response. */
header('Pragma: no-cache');
header('Cache-Control: no-cache, must-revalidate');
echo $attributes;
