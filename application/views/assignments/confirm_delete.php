<h2><?= $title ?></h2>
<hr>
<h3>Er du sikker på du vil slette opgaven <strong><?= $ass['ass_title'] ?></strong> fra systemet?<br>
    <strong><b>Du vil ikke længere have adgang til denne opgave!</b></strong>
</h3>
<div>
    <div>
        <?= form_open('assignments/delete/'.$ass['ass_id']); ?>
            <input type="submit" value="Ja, slet opgave" class="btn btn-danger" />
        <?= form_close(); ?>
    </div>
    <div>
        <a href="<?= base_url('assignments/view/'.$ass['ass_id']); ?>"><button type="button" class="btn btn-primary">Nej, behold opgave</button></a>
    </div>
</div>