<h2><?= $title ?></h2>
<a href="<?= base_url('departments/create'); ?>">Ny afdeling</a>
<span class="same-line">|</span>
<a href="<?= base_url('departments/edit'); ?>" class="same-line">Rediger afdelinger</a>
<hr>

<div>
<?php foreach($departments as $department): ?>
    <div>
        <strong><?= $department['name'] ?></strong>
        <span class="same-line">|</span>
        <a class="same-line" href="<?= base_url('departments/delete/'.$department['id']); ?>">Slet</a>
        <span class="same-line">|</span>
        <a class="same-line" href="<?= base_url('departments/edit/'.$department['id']); ?>">Rediger</a>
    </div>
<?php endforeach; ?>
</div>