    <!-- Title -->
<h2><?= $title ?></h2>
<h5>Oversigt over alle afdelinger.<br/>
Klik på en afdelings navn for at se flere detaljer.</h5>
<hr>

    <!-- Create button -->
<div>
    <a href="<?= base_url('departments/create'); ?>"><button type="button" class="btn btn-warning">Opret ny afdeling</button></a>
</div>

<br>

    <!-- "Table" -->
<div>
<?php foreach($departments as $department): ?>
    <div style="margin-bottom:1%;">
            <!-- Name -->
        <a href="<?= base_url('departments/view/'.$department['d_id']); ?>" ><strong><?= $department['d_name'] ?>:</strong></a>
            <!-- Rename button -->
        <button type="button" class="btn btn-warning btn-sm"
        onclick="submitHidden('inputRename<?= $department['d_id'] ?>', 'formRename<?= $department['d_id'] ?>')">
        Omdøb</button>
            <!-- Delete button -->
        <button type="button" class="btn btn-danger btn-sm" 
        onclick="submitHidden('inputDelete<?= $department['d_id'] ?>', 'formDelete<?= $department['d_id'] ?>', 'afdelingen')">
        Slet</button>
    </div>

        <!-- Hidden rename form -->
    <?= form_open('departments/edit/'.$department['d_id'], array('id' => 'formRename'.$department['d_id']));?>
        <input type="hidden" name="input" id="inputRename<?= $department['d_id'] ?>" value="">
    <?= form_close(); ?>
        <!-- Hidden delete form -->
    <?= form_open('departments/delete/'.$department['d_id'], array('id' => 'formDelete'.$department['d_id']));?>
        <input type="hidden" name="input" id="inputDelete<?= $department['d_id'] ?>" value="">
    <?= form_close(); ?>
<?php endforeach; ?>
</div>

<!--
	 Pagination 
<div class="pagination-links">
	<?=  " "//$this->pagination->create_links(); ?>
</div>
-->