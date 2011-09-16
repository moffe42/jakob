<!DOCTYPE html>
<html lang="en">
    <head>
        <title>JAKOB - Attribute collector by WAYF</title>
        <meta charset="utf-8" />
        <meta name="application-name" content="JAKOB" />
        <meta http-equiv="Cache-Control" content="no-cache" />
        <meta http-equiv="expires" content="Mon, 22 Jul 2002 11:12:01 GMT" />
        <meta http-equiv="pragma" content="no-cache" />
        <meta name="robots" content="none" />
    </head>
    <body>
        <h1>Error</h1>
        <?php
        if (isset($errortitle) ) {
            echo '<h2>' . $errortitle . '</h2>';
        }
        if (isset($errormsg) ) {
            echo '<p>' . $errormsg . '</p>';
        } else {
            echo '<p>An error occured. Please contact the system administrator.</p>';
        }
        ?>
        <hr />
        <address>
            WAYF - Where Are You From<br />
            H. C. Andersens Boulevard 2<br />
            DK-1553 København V<br />
            Web: <a href="http://www.wayf.dk">www.wayf.dk</a><br />
            E-mail: <a href="mailto:sekretariat@wayf.dk">sekretariat@wayf.dk</a>
        </address>
    </body>
</html>
