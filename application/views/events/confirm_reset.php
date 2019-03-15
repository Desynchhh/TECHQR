<h2><?= $title ?></h2>
<hr>
<h3>Er du sikker på du vil genstarte <strong><?= $event['e_name'] ?></strong>?<br>
    <strong><b>Dette vil ubemande alle hold, fjerne alle point, slette alle handlinger, og gøre alle opgaver besvarlige igen.</b></strong>
</h3>
<div class="col-md-4 offset-md-1">
    <div class="form-group">
        <?= form_open('events/confirm_reset/'.$event['e_id']); ?>
            <label>Indtast eventets navn for at bekræfte:</label>
            <input type="text" name="e_name" placeholder="Eventnavn" class="form-control" />
            <br>
            <input type="submit" class="btn btn-danger" value="Ja, reset event" />
            <a type="button" class="btn btn-primary" href="<?= base_url('events/manage/'.$event['e_id']); ?>">Nej, behold event</a>
        <?= form_close(); ?>
    </div>
</div>