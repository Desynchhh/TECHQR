    <!-- Title -->
<h2><?= $title ?></h2>
<hr>

    <!-- Info -->
<div>
    <p>Dit holds score er: <?= $score ?></p>
    <?php if($action['ass_title']): ?>
        <p>Seneste besvaret spørgsmål: <?=$action['ass_title'] ?></p>
        <p>Tidspunkt: <?= $action['created_at'] ?></p>
    <?php else: ?>
        <p>Seneste besvaret spørgsmål:</p>
        <p>Tidspunkt:</p>
    <?php endif; ?>
</div>

<?php if(!empty($message['message'])):?>
        <!-- Show message, if one has been sent -->
    <div>
        <p>Besked:</p> 
        <?= $message['message'] ?>
    </div>
<?php endif;?>