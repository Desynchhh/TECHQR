<h2><?= $title ?></h2>
<hr>
<?php if($department):?>
<!-- if the user is editing a specific department -->
<?= validation_errors(); ?>
<?= form_open('departments/edit/'.$department['id']); ?>
<div class="row">
    <div class="col-md-3 offset-md-1">
        <input type="hidden" value="<?= $department['id'] ?>"/>
        <label>Afdelingsnavn:</label>
        <input type="text" name="name" placeholder="Afdelingsnavn" value="<?= $department['name'] ?>"/>
        <input type="submit" value="Bekræft" class="btn btn-secondary"/>
    </div>
</div>
<?= form_close(); ?>
<?php else:?>
<!-- if the user is trying to get an overview of all departments -->
<?php endif;?>