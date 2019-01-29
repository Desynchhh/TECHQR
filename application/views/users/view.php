<h2><?= $title ?></h2>
<hr>

<div class="row">
    <div class="col-md-1">
        <label><strong>Brugernavn:</strong></label>
    </div>
    <div class="col-md-11">
        <p><?= $user['username'] ?></p>
    </div>
</div>
<div class="row">
    <div class="col-md-1">
        <label><strong>Afdeling:</strong></label>
    </div>
    <div class="col-md-11">
        <p><?= $user['name'] ?></p>
    </div>
</div>
<div class="row">
    <div class="col-md-1">
        <label><strong>Rolle:</strong></label>
    </div>
    <div class="col-md-11">
        <p><?= $user['permissions'] ?></p>
    </div>
</div>
<div class="row">
    <div class="col-md-1">
        <label class=""><strong>Email:</strong></label>
    </div>
    <div class="col-md-11">
        <p><?= $user['email'] ?></p>
    </div>
</div>
<div class="row">
    <div class="col-md-1">
        <label><strong>Oprettet:</strong></label>
    </div>
    <div class="col-md-11">
        <p><?= $user['created_at'] ?></p>
    </div>
</div>

<div class="row">
    <div class="md-col-1" style="margin-left:1.2%;">
        <?= form_open('users/edit/'.$user['user_id']); ?>
            <input type="submit" value="Rediger bruger" class="btn btn-secondary" />
        <?= form_close(); ?>
    </div>
    <div class="md-col-1" style="margin-left:1%;">
        <?= form_open('users/delete/'.$user['user_id']); ?>
            <input type="submit" value="Slet bruger" class="btn btn-danger" />
        <?= form_close(); ?>
    </div>
</div>
<hr>

<div class="row">
    <div class="col-md-4">
        <?= validation_errors(); ?>
        <?= form_open('users/user_change_password'); ?>
        <input type="hidden" name="id" value="<?= $user['user_id'] ?>" />
        <h3>Ændr kodeord</h3>
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
<a href="<?= base_url('users'); ?>">Tilbage til oversigt</a>