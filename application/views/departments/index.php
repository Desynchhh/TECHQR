<h2><?= $title ?></h2>
<hr>
<div>
    <a type="button" class="btn btn-primary" href="<?= base_url('departments/create'); ?>">Opret ny afdeling</a>
    <a type="button" class="btn btn-warning" href="<?= base_url('departments/edit'); ?>" class="same-line">Rediger afdelinger</a>
</div>
<br>
<div>
<?php foreach($departments as $department): ?>
    <div style="margin-bottom:1%;">
        <a href="<?= base_url('departments/view/'.$department['id']); ?>" ><strong><?= $department['name'] ?>:</strong></a>
        <a type="button" class="same-line btn btn-danger btn-sm" href="<?= base_url('departments/delete/'.$department['id']); ?>">Slet</a>
        <!--<span class="same-line">|</span>-->
        <a type="button" class="same-line btn btn-warning btn-sm" href="<?= base_url('departments/edit/'.$department['id']); ?>">Omdøb</a>
    </div>
<?php endforeach; ?>
</div>