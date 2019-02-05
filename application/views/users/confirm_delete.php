<h2><?= $title ?></h2>
<hr>
<h3>Er du sikker på du vil slette <strong><?= $user['username'] ?></strong> fra systemet?<br>
    <strong><b>Du vil ikke længere have adgang til deres oprettede opgaver!</b></strong>
</h3>
<div>
    <div>
        <?= form_open('users/delete/'.$user['u_id']); ?>
            <input type="submit" class="btn btn-danger" value="Ja, slet bruger" />
        <?= form_close(); ?>
    </div>
    <div>
        <a type="button" class="btn btn-primary" href="<?= base_url('users/view/'.$user['u_id']); ?>">Nej, behold bruger</a>
    </div>
</div>