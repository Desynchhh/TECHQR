<h2><?= $title ?></h2>
<h5>Oversigt over alle afdelinger.<br/>
Klik på en afdelings navn for at se flere detaljer.</h5>
<hr>
<div>
    <a type="button" class="btn btn-warning" href="<?= base_url('departments/create'); ?>">Opret ny afdeling</a>
</div>

<br>

<div>
<?php foreach($departments as $department): ?>
    <div style="margin-bottom:1%;">
        <a href="<?= base_url('departments/view/'.$department['id']); ?>" ><strong><?= $department['name'] ?>:</strong></a>
        <a type="button" class="same-line btn btn-danger btn-sm" href="<?= base_url('departments/confirm_delete/'.$department['id']); ?>">Slet</a>
        <a type="button" class="same-line btn btn-warning btn-sm" href="<?= base_url('departments/edit/'.$department['id']); ?>">Omdøb</a>
    </div>
<?php endforeach; ?>
</div>
	<!-- Pagination -->
<div class="pagination-links">
	<?= $this->pagination->create_links(); ?>
</div>