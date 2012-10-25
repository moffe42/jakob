<h1><?= $this->t('TIMEOUT_TITLE') ?></h1>
<p><?= $this->t('TIMEOUT_DESC1') ?></p>
<p><?= $this->t('TIMEOUT_DESC2') ?></p>
<form method="post" action="">
<input type="hidden" name="token" value="<?php echo $token; ?>" />
    <input type="submit" value="<?= $this->t('TIMEOUT_BUTTON') ?>" />
</form>
