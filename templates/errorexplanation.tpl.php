<?php
include 'header.tpl.php';

echo '<h1>Error-pedia</h1>';

echo '<h2><u>' . $errortitle . '</u></h2>';

echo '<p>' . $errordescription . '</p>';

echo '<p>See complete list of errors <a href="/errorlist.php">here</a></p>';
include 'footer.tpl.php';
