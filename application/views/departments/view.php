<h2><?= $title ?></h2>
<hr>

<div>
    <dl class="dl-horizontal">
        <dt>Afdelingsnavn:</dt>
        <dd class="department-dd"><?= $department['name'] ?></dd>
        <dt>Medlemmer:</dt>
        <dd class="department-dd"><?= count($users) ?></dd>
        <dt>Oprettet:</dt>
        <dd class="department-dd"><?= $department['created_at'] ?></dd>
    </dl>
</div>

<div class="row">
    <div class="md-col-1" style="margin-left:1.33%;">
    <a type="button" class="btn btn-secondary" href="<?= base_url('departments/add/'.$department['id']); ?>">Tilføj bruger</a>
    </div>
    <div class="md-col-1" style="margin-left:1%;">
    <?= form_open('departments/delete/'.$department['id']); ?>
        <input type="submit" value="Slet afdeling" class="btn btn-danger" />
    <?= form_close(); ?>
    </div>
</div>
<br/>

<div>
    <table class="table">
        <tbody>
            <tr>
                <th>Brugernavn</th>
                <th>Roller</th>
                <th>Email</th>
                <th>Værktøj</th>
            </tr>
            <!-- create a <tr> with <td> children for each user in this department -->
            <?php foreach($users as $user):?>
                <tr>
                    <td><?= $user['username'] ?></td>
                    <td><?= $user['permissions'] ?></td>
                    <td><?= $user['email'] ?></td>
                    <td><a href="<?= base_url('departments/remove/'.$user['u_id'].'/'.$department['id']); ?>">Fjern</a></td>
                </tr>
            <?php endforeach;?>
        </tbody>
    </table>
</div>

<div>
    <a type="button" class="btn btn-primary" href="<?= base_url('departments'); ?>">Tilbage til oversigt</a>
</div>