    <!-- Title -->
<h2><?= $title ?></h2>
<hr>

    <!-- Info -->
<div>
    <p>Dit holds score er: <?= $score ?></p>
    <p>Seneste besvaret spørgsmål: [handling]</p>
    <p>Tidspunkt: [datetime]</p>
</div>

<?php if(!empty($message['message'])):?>
        <!-- Show message, if one has been sent -->
    <div>
        <p>Besked:</p> 
        <?= $message['message'] ?>
    </div>
<?php endif;?>