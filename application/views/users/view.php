<h2><?= $title ?></h2>
<hr>

<div>
    <dt>Brugernavn:</dt>
    <dd class="user-dd"><?= $user['username'] ?></dd>
    <dt>Afdelinger:</dt>
    <?php foreach($departments as $department): ?>
        <dd class="user-dd">
            <?= $department['name'] ?>
        </dd>
    <?php endforeach; ?>
    <dt>Rolle:</dt>
    <dd class="user-dd"><?= $user['permissions'] ?></dd>
    <dt>Email:</dt>
    <dd class="user-dd"><?= $user['email'] ?></dd>
    <dt>Oprettet:</dt>
    <dd class="user-dd"><?= $user['created_at'] ?></dd>
</div>

<br/>

<div class="row">
    <div class="md-col-1" style="margin-left:1.33%;">
        <?= form_open('users/edit/'.$user['u_id']); ?>
            <input type="submit" value="Rediger bruger" class="btn btn-secondary" />
        <?= form_close(); ?>
    </div>
    <div class="md-col-1" style="margin-left:1%;">
        <?= form_open('users/delete/'.$user['u_id']); ?>
            <input type="submit" value="Slet bruger" class="btn btn-danger" />
        <?= form_close(); ?>
    </div>
</div>
<a type="button" class="btn btn-primary" href="<?= base_url('users'); ?>">Tilbage til oversigt</a>
<hr>

<h3>Ændr kodeord</h3>
<h6>Kontakt en adminitstrator hvis du ikke kan huske dit kodeord.</h6>
<div class="row">
    <div class="col-md-4">
        <?= validation_errors(); ?>
        <?= form_open('users/change_password'); ?>
        <input type="hidden" name="id" value="<?= $user['u_id'] ?>" />
        <div class="form-group">
            <label>Gammelt kodeord:</label>
            <input type="password" name="old_password" placeholder="Gammelt kodeord" class="form-control" />
        </div>
        <div class="form-group">
            <label>Nyt kodeord:</label>
            <input type="password" name="new_password" placeholder="Nyt kodeord" class="form-control" />
        </div>
        <div class="form-group">
            <label>Bekræft kodeord:</label>
            <input type="password" name="new_password2" placeholder="Bekræft kodeord" class="form-control" />
        </div>
        <input type="submit" value="Ændre kodeord" class="btn btn-secondary" />
<?= form_close(); ?>
    </div>
</div>