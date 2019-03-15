<h2><?= $title ?></h2>
<hr>
<div>
    <p>Dit holds score er: <?= $score ?></p>
    <p>Seneste besvaret spørgsmål: [handling]</p>
    <p>Tidspunkt: [datetime]</p>
</div>

<?php if(!empty($message['message'])):?>
    <div>
        <p>Besked:</p> 
        <?= $message['message'] ?>
    </div>
<?php endif;?>