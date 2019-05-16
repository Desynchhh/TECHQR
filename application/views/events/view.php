    <!-- Title -->
<h2><?= $title ?></h2>
<hr>

    <!-- Info -->
<div>
    <dt>Eventnavn:</dt>
    <dd class="event-dd"><?= $event['e_name'] ?></dd>
    <dt>Afdeling:</dt>
    <dd class="event-dd"><?= $event['d_name'] ?></dd>
    <dt>Opgaver:</dt>
    <dd class="event-dd">
        <?= count($event_asses) ?> - 
        <a href="<?= base_url("events/assignments/view/$event[e_id]/10/asc/title"); ?>">
            <button class="btn btn-sm btn-primary">Vis</button>
        </a>
    </dd>
    <dt>Maks point:</dt>
    <dd class="event-dd"><?= $max_points ?></dd>
    <dt>Hold & Score:</dt>
    <dd class="event-dd">
        <?= count($teams) ?> - 
        <a href="<?= base_url("teams/view/$event[e_id]/10/asc/number"); ?>">
            <button class="btn btn-sm btn-primary">Vis</button>
        </a>
    </dd>
        
        <!-- Event buttons -->
    <dd><a href="<?= base_url("events/actions/$event[e_id]/10/desc/created_at"); ?>"><button type="button" class="btn btn-primary">Se alle handlinger</button></a> 
    <a href="<?= base_url("events/stats/$event[e_id]/5"); ?>"><button type="button" class="btn btn-primary">Opgave statistik</button></a>
    <a href="<?= base_url("events/pdf/$event[e_id]"); ?>"><button type="button" class="btn btn-primary">Se PDF</button></a>
    </dd>
</div>

    <!-- Manage buttons -->
<div>
    <button class="btn btn-warning" onclick="submitHidden('inputRename', 'formRename')">Omdøb</button>
    <a href="<?= base_url('events/manage/'.$event['e_id']); ?>"><button type="button" class="btn btn-warning">Manage</button></a>
    <button class="btn btn-danger" onclick="submitHidden('inputDelete', 'formDelete', 'eventet')">Slet event</button>
</div>

<br>

    <!-- Back button -->
<div>
    <a class="btn btn-primary" href="<?= base_url("events/index/10/asc/e_name"); ?>">Tilbage til oversigt</a>
</div>

    <!-- Hidden delete form -->
<?= form_open('events/delete/'.$event['e_id'], array('id' => 'formDelete'));?>
    <input type="hidden" name="input" id="inputDelete" value="" />
<?= form_close();?>

    <!-- Hidden rename form -->
<?= form_open('events/edit/'.$event['e_id'], array('id' => 'formRename')); ?>
    <input type="hidden" name="input" id="inputRename" value="" />
<?= form_close(); ?>