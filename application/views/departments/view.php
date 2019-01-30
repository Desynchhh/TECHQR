<h2><?= $title ?></h2>
<hr>

<div>
    <dl class="dl-horizontal">
        <dt>Afdelingsnavn:</dt>
        <dd class="department-dd"><?= $department['name'] ?></dd>
        <dt>Medlemmer:</dt>
        <dd class="department-dd">DUMMY DATA</dd>
        <dt>Oprettet:</dt>
        <dd class="department-dd"><?= $department['created_at'] ?></dd>
    </dl>
</div>

<div>
    <a type="button" class="btn btn-secondary" href="<?= base_url('departments/add_user'); ?>">Tilføj bruger</a>
</div>
<br/>

<div>
    <table class="table">
        <tbody>
            <tr>
                <th>Brugernavn</th>
                <th>Roller</th>
                <th>Sidste handling</th>
                <th>Email</th>
                <th>Værktøj</th>
            </tr>
            <!-- create a <tr> with <td> children for each user in this department -->
            <tr>
                <td></td>
            </tr>
        </tbody>
    </table>
</div>

<div>
    <a type="button" class="btn btn-primary" href="<?= base_url('departments'); ?>">Tilbage til oversigt</a>
</div>