    <!-- Title -->
<h2><?= $title ?></h2>
<hr>

    <!-- Info -->
<div class="row">
    <div class="col-sm-12">
            <!-- Score -->
        <p>Dit holds score er: <?= $score ?></p>
            <!-- Action & Timestamp -->
        <?php if(isset($action['ass_title'])): ?>
        <div class="row">
            <div class="col-sm-12 task-container">
                <p>Seneste besvaret opgave: <?=$action['ass_title'] ?></p>
                <p>I tjente <?= (isset($action['points'])) ? $action['points'] : 0; ?> point på dette!</p>
            </div>
            <div class="col-sm-12">
                <p>Tidspunkt: <?= $action['created_at'] ?></p>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <!-- Message -->
        <?php if(!empty($message['message'])):?>
                <!-- Show message, if one has been sent -->
            <div class="message-container">
                <p>Besked:</p> 
                <p class="message">
                    <?= $message['message'] ?>
                </p> 
            </div>
        <?php endif;?>
    </div>
</div>