<h2><?= $title ?></h2>
<hr>

    <!-- Info -->
<div>
    <dt>Eventnavn:</dt>
    <dd class="event-dd"><?= $event['e_name'] ?></dd>
    <dt>Afdeling:</dt>
    <dd class="event-dd"><?= $event['d_name'] ?></dd>
    <dt>Opgaver:</dt>
    <dd class="event-dd"><?= count($event_asses) ?> - <a class="btn btn-sm btn-primary" href="<?= base_url('events/assignments/view/'.$event['e_id']); ?>">Vis</a></dd>
    <dt>Maks point:</dt>
    <dd class="event-dd"><?= $max_points ?></dd>
    <dt>Antal Hold:</dt>
    <dd class="event-dd"><?= count($teams) ?> - <a class="btn btn-sm btn-primary" href="<?= base_url('teams/view/'.$event['e_id']); ?>">Vis</a></dd>
    <!-- Acions & PDF buttons -->
    <dd><a class="btn btn-primary" href="<?= base_url('events/actions/'.$event['e_id']); ?>">Se alle handlinger</a> 
    <a class="btn btn-warning" href="<?= base_url('events/pdf/'.$event['e_id']); ?>">Se PDF</a></dd>
</div>


<!--<div class="row">
    <div class="md-col-1" style="margin-left:1.33%;">
        <a href="<?= base_url('events/edit/'.$event['e_id']); ?>"><button type="button" class="btn btn-warning">Rediger event</button></a>
    </div>
</div>-->

    <!-- Manage button -->
<div><!-- class="md-col-1" style="margin-left:1%;" -->
    <a href="<?= base_url('events/manage/'.$event['e_id']); ?>"><button type="button" class="btn btn-warning">Manage</button></a>
</div>

<br>

    <!-- Back button -->
<div>
    <a class="btn btn-primary" href="<?= base_url('events'); ?>">Tilbage til oversigt</a>
</div>