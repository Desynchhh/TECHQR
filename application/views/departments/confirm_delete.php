<h2><?= $title ?></h2>
<hr>
<h3>Er du sikker på du vil slette <strong><?= $department['name'] ?></strong> fra systemet?<br>
    <strong><b>Dette vil også slette alle afdelingens events og opgaver!!</b></strong>
</h3>
<div class="col-md-4 offset-md-1">
    <div class="form-group">
        <?= form_open('departments/confirm_delete/'.$department['id']); ?>
            <label>Indtast afdelingsnavn for at bekræfte:</label>
            <input type="text" name="department" placeholder="Afdelingsnavn" class="form-control" />
            <br>
            <input type="submit" class="btn btn-danger" value="Ja, slet afdeling" />
            <a type="button" class="btn btn-primary" href="<?= base_url('departments/view/'.$department['id']); ?>">Nej, behold afdeling</a>
        <?= form_close(); ?>
    </div>
</div>