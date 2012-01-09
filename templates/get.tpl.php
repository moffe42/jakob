<?php 
/* Set the location header. */
header('Location: ' . $url, TRUE);

/* Disable caching of this response. */
header('Pragma: no-cache');
header('Cache-Control: no-cache, must-revalidate');
?>
<h1>Redirect</h1>
<p>You were redirected to:</p>
<a id="redirlink" href="<?php echo htmlspecialchars($url); ?>"><?php echo htmlspecialchars($url); ?></a>
<script type="text/javascript">document.getElementById("redirlink").focus();</script>
