<?php 
/* Set the location header. */
header('Location: ' . $url, TRUE);

/* Disable caching of this response. */
header('Pragma: no-cache');
header('Cache-Control: no-cache, must-revalidate');
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>JAKOB - Attribute collector by WAYF - Redirect</title>
        <meta charset="utf-8" />
        <meta name="application-name" content="JAKOB" />
        <meta http-equiv="Cache-Control" content="no-cache, must-revalidate" />
        <meta http-equiv="expires" content="Mon, 22 Jul 2002 11:12:01 GMT" />
        <meta http-equiv="pragma" content="no-cache" />
        <meta name="robots" content="none" />
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    </head>
    <body>
        <h1>Redirect</h1>
        <p>You were redirected to:</p>
        <a id="redirlink" href="<?php echo htmlspecialchars($url); ?>"><?php echo htmlspecialchars($url); ?></a>
        <script type="text/javascript">document.getElementById("redirlink").focus();</script>
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
