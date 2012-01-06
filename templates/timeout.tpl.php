<?php
include 'header.tpl.php';
?>
<h1>Time out</h1>
<form method="post" action="">
<input type="hidden" name="token" value="<?php echo $token; ?>" />
    <input type="submit" value="Submit" />
</form>
<?php
include 'footer.tpl.php';
?>
