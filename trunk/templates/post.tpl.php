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
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    </head>
    <body onload="document.forms[0].submit()">
        <noscript>
            <p><strong>Note:</strong> Since your browser does not support JavaScript, you must press the button below once to proceed.</p> 
            <hr />
            <address>
                WAYF - Where Are You From<br />
                H. C. Andersens Boulevard 2<br />
                DK-1553 København V<br />
                Web: <a href="http://www.wayf.dk">www.wayf.dk</a><br />
                E-mail: <a href="mailto:sekretariat@wayf.dk">sekretariat@wayf.dk</a>
            </address>
        </noscript> 
        <form method="post" action="<?php echo htmlspecialchars($destination); ?>">
<?php
/**
 * Write out one or more INPUT elements for the given name-value pair.
 *
 * If the value is a string, this function will write a single INPUT element.
 * If the value is an array, it will write multiple INPUT elements to
 * recreate the array.
 *
 * @param string $name  The name of the element.
 * @param string|array $value  The value of the element.
 */
function printItem($name, $value) {
    assert('is_string($name)');
    assert('is_string($value) || is_array($value)');

    if (is_string($value)) {
        echo '<input type="hidden" name="' . htmlspecialchars($name) . '" value="' . htmlentities($value) . '" />';
        return;
    }

    /* This is an array... */
    foreach ($value as $index => $item) {
        printItem($name . '[' . $index . ']', $item);
    }
}

foreach ($post as $name => $value) {
    printItem($name, $value);
}
?>
            <noscript>
                <input type="submit" value="Submit" />
            </noscript>
        </form>
    </body>
</html>
