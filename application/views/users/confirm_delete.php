<h2><?= $title ?></h2>
<hr>
<h3>Er du sikker på du vil slette <strong><?= $user['username'] ?></strong> fra systemet?<br>
    <strong><b>Du vil ikke længere have adgang til deres oprettede opgaver!</b></strong>
</h3>
<div class="col-md-4 offset-md-1">
    <div class="form-group">
        <?= form_open('users/confirm_delete/'.$user['u_id']); ?>
            <label>Indtast brugerens navn for at bekræfte:</label>
            <input type="text" name="username" placeholder="Brugernavn" class="form-control" />
            <br>
            <input type="submit" class="btn btn-danger" value="Ja, slet bruger" />
            <a type="button" class="btn btn-primary" href="<?= base_url('users/view/'.$user['u_id']); ?>">Nej, behold bruger</a>
        <?= form_close(); ?>
    </div>
</div>