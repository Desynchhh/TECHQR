<h2><?= $title ?></h2>
<hr>

<div>
    <dl class="dl-horizontal">
        <dt>Opgave navn:</dt>
        <dd class="ass-dd"><?= $ass['ass_title'] ?><br></dd>
        <dt>Tilhørende afdeling:</dt>
        <dd class="ass-dd"><?= $ass['department'] ?><br></dd>
        <dt>Oprettet af:</dt>
        <dd class="ass-dd"><?= $ass['ass_created_by'] ?><br></dd>
        <dt>Lokation:</dt>
        <dd class="ass-dd"><?= $ass['location'] ?><br></dd>
    </dl>
</div>
<br>
<div>
<a type="button" class="btn btn-primary" href="<?= base_url('assignments'); ?>">Tilbage til oversigt</a>
</div>
