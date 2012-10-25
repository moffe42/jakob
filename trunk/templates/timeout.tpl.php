<h1><?= $this->t('TITLE') ?></h1>
<h1>Zzzzzzz!!!</h1>
<p>Det ser ud til at det tager lidt længere tid at hente alle dine oplysninger end forventet.</p>
<p>Hav tålmodighed systemet arbejder. Tryk på knappen "Er den klar nu?"</p>
<form method="post" action="">
<input type="hidden" name="token" value="<?php echo $token; ?>" />
    <input type="submit" value="Er den klar nu?" />
</form>
