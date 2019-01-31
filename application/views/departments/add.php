<h2><?= $title ?></h2>
<hr>
<div>
    <?= form_open('departments/add/'.$department['id']); ?>
        <label>Bruger:</label>
        <select name="u_id">
            <?php foreach($users as $user): ?>
                <option value="<?= $user['u_id'] ?>"><?= $user['username'] ?></option>
            <?php endforeach;?>
        </select><br>
        <input type="submit" value="Tilføj" class="btn btn-secondary" />
    <?= form_close(); ?>
    <br>
</div>
<div>
    <a type="button" class="btn btn-primary" href="<?= base_url('departments/view/'.$department['id']); ?>">Tilbage til oversigt</a>
</div>