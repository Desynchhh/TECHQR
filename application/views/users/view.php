    <!-- Title -->
<h2><?= $title ?></h2>
<hr>

    <!-- Info -->
<div>
    <dt>Brugernavn:</dt>
    <dd class="user-dd"><?= $user['username'] ?><br></dd>
    <dt>Afdelinger:</dt>
    <dd class="user-dd">
        <?php foreach($departments as $department): ?>
                <a target="_blank" href="<?= base_url('departments/view/'.$department['d_id']); ?>"><?= $department['name'] ?></a><br>
        <?php endforeach; ?>
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
    <!-- Admin control panel -->
<div class="row">
        <!-- Edit button -->
    <div class="md-col-1" style="margin-left:1.33%;">
        <a href="<?= base_url('users/edit/'.$user['u_id']); ?>"><button type="button" class="btn btn-warning">Rediger bruger</button></a>
    </div>
        <!-- Delete button (admins can't delete themselves)-->
    <?php if($this->session->userdata('u_id') != $user['u_id']):?>
        <div class="md-col-1" style="margin-left:1%;">
            <button class="btn btn-danger" onclick="submitHidden('inputDelete', 'formDelete', 'brugeren')">Slet Bruger</button>
        </div>
            <!-- Hidden delete form -->
        <?= form_open('users/delete/'.$user['u_id'], array('id' => 'formDelete')); ?>
            <input type="hidden" name="input" id="inputDelete" value="">
        <?= form_close(); ?>
    <?php endif;?>
</div>

<br>

    <!-- Back button -->
<div>
    <a href="<?= base_url('users/index/10/asc/username'); ?>"><button type="button" class="btn btn-primary">Tilbage til oversigt</button></a>
</div>

<!-- User control panel / change password -->
<?php else:?>
<hr>

<h3>Ændr kodeord</h3>
<h6>Kontakt en administrator hvis du har glemt dit kodeord.</h6>
<div class="row">
    <div class="col-md-4">
            <!-- Form -->
        <?= validation_errors(); ?>
        <?= form_open('users/change_password/'.$user['username']); ?>
                <!-- Hidden stuff, used as comparison on server side -->
            <input type="hidden" name="username" value="<?= $user['username'] ?>" />
            <input type="hidden" name="id" value="<?= $user['u_id'] ?>" />
                <!-- Current password -->
            <div class="form-group">
                <label>Nuværende kodeord:</label>
                <input type="password" name="old_password" placeholder="Nuværende kodeord" class="form-control" />
            </div>
                <!-- New password -->
            <div class="form-group">
                <label>Nyt kodeord:</label>
                <input type="password" name="new_password" placeholder="Nyt kodeord" class="form-control" />
            </div>
                <!-- Confirm password -->
            <div class="form-group">
                <label>Bekræft nye kodeord:</label>
                <input type="password" name="new_password2" placeholder="Bekræft nye kodeord" class="form-control" />
            </div>
                <!-- Submit button -->
            <input type="submit" value="Bekræft" class="btn btn-secondary" />
        <?= form_close(); ?>
    </div>
</div>
<?php endif;?>