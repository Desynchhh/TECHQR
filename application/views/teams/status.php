<h2><?= $title ?></h2>
<hr>
<p>Dit holds score er: <?= $score ?></p>
<p>Seneste besvaret spørgsmål: [handling]</p>
<p>Tidspunkt: [datetime]</p>
<br>
<?php if(isset($message)): ?>
    <p>Besked: <b><?= $message ?></b> </p>
<?php else:?>
    <p>Besked: Ingen nye beskeder</p>
<?php endif;?>
<h1><?= $test ?></h1>