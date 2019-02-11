<h2><?= $title ?></h2>
<hr>

<div>
    <dt>Brugernavn:</dt>
    <dd class="user-dd"><?= $user['username'] ?><br></dd>
    <dt>Afdelinger:</dt>
    <dd class="user-dd">
        <!-- Display all the users departments, if there are any -->
        <?php foreach($departments as $department): ?>
                <a href="<?= base_url('departments/view/'.$department['d_id']); ?>"><?= $department['name'] ?></a><br>
        <?php endforeach; ?>
        <!-- or this, if the user has no departments -->
    </dd>
    <dt>Type:</dt>
    <dd class="user-dd"><?= $user['permissions'] ?><br></dd>
    <dt>Email:</dt>
    <dd class="user-dd"><?= $user['email'] ?><br></dd>
    <dt>Oprettet:</dt>
    <dd class="user-dd"><?= $user['created_at'] ?><br></dd>
</div>

<br/>
<?php if($this->session->userdata('permissions') == 'Admin'): ?>
<div class="row">
    <div class="md-col-1" style="margin-left:1.33%;">
        <a href="<?= base_url('users/edit/'.$user['u_id']); ?>"><button type="button" class="btn btn-warning">Rediger bruger</button></a>
    </div>
    <?php if($this->session->userdata('u_id') != $user['u_id']):?>
        <div class="md-col-1" style="margin-left:1%;">
            <?= form_open('users/confirm_delete/'.$user['u_id']); ?>
                <input type="submit" value="Slet bruger" class="btn btn-danger" />
            <?= form_close(); ?>
        </div>
    <?php endif;?>
</div>
<div><br>
    <a type="button" class="btn btn-primary" href="<?= base_url('users'); ?>">Tilbage til oversigt</a>
</div>
<?php else:?>
<hr>

<h3>Ændr kodeord</h3>
<h6>Kontakt en administrator hvis du har glemt dit kodeord.</h6>
<div class="row">
    <div class="col-md-4">
        <?= validation_errors(); ?>
        <?= form_open('users/change_password/'.$user['username']); ?>
        <input type="hidden" name="username" value="<?= $user['username'] ?>" />
        <input type="hidden" name="id" value="<?= $user['u_id'] ?>" />
        <div class="form-group">
            <label>Nuværende kodeord:</label>
            <input type="password" name="old_password" placeholder="Nuværende kodeord" class="form-control" />
        </div>
        <div class="form-group">
            <label>Nyt kodeord:</label>
            <input type="password" name="new_password" placeholder="Nyt kodeord" class="form-control" />
        </div>
        <div class="form-group">
            <label>Bekræft nye kodeord:</label>
            <input type="password" name="new_password2" placeholder="Bekræft nye kodeord" class="form-control" />
        </div>
        <input type="submit" value="Bekræft" class="btn btn-secondary" />
<?= form_close(); ?>
    </div>
</div>
<?php endif;?>