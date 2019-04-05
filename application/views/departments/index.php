<h2><?= $title ?></h2>
<h5>Oversigt over alle afdelinger.<br/>
Klik på en afdelings navn for at se flere detaljer.</h5>
<hr>
    <!-- Create button -->
<div>
    <a type="button" class="btn btn-warning" href="<?= base_url('departments/create'); ?>">Opret ny afdeling</a>
</div>

<br>

<div>
<?php foreach($departments as $department): ?>
    <div style="margin-bottom:1%;">
            <!-- Name -->
        <a href="<?= base_url('departments/view/'.$department['id']); ?>" ><strong><?= $department['name'] ?>:</strong></a>
            <!-- Rename button -->
        <button type="button" class="btn btn-warning btn-sm"
        onclick="submitHidden('inputRename<?= $department['id'] ?>', 'formRename<?= $department['id'] ?>')">
        Omdøb</button>
            <!-- Delete button -->
        <button type="button" class="btn btn-danger btn-sm" 
        onclick="submitHidden('inputDelete<?= $department['id'] ?>', 'formDelete<?= $department['id'] ?>', 'afdelingen')">
        Slet</button>
    </div>
        <!-- Hidden rename form -->
    <?= form_open('departments/edit/'.$department['id'], array('id' => 'formRename'.$department['id']));?>
        <input type="hidden" name="input" id="inputRename<?= $department['id'] ?>" value="">
    <?= form_close(); ?>
        <!-- Hidden delete form -->
    <?= form_open('departments/delete/'.$department['id'], array('id' => 'formDelete'.$department['id']));?>
        <input type="hidden" name="input" id="inputDelete<?= $department['id'] ?>" value="">
    <?= form_close(); ?>
<?php endforeach; ?>
</div>
	<!-- Pagination -->
<div class="pagination-links">
	<?= $this->pagination->create_links(); ?>
</div>
