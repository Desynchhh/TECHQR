<h2><?= $title ?></h2>

<br>
<div>
    <a type="button" class="btn btn-primary" href="<?= base_url('events/assignments/view/'.$e_id); ?>">Tilbage til oversigt</a>
</div>
<br>

<div>
    <table class="table">
        <tbody>
            <tr>
                <th>Opgavenavn</th>
                <th>Afdeling</th>
                <th>Lokation</th>
                <th>Tilføj</th>
            </tr>
            <?php foreach($asses as $ass):?>
            <tr>
                <td><?= $ass['title'] ?></td>
                <td><?= $ass['name'] ?></td>
                <td><?= $ass['location'] ?></td>
                <td><a class="btn btn-sm btn-outline-secondary" href="<?= base_url('events/add_ass/'.$e_id.'/'.$ass['id']); ?>" >Tilføj</a></td>
            </tr>
            <?php endforeach;?>
        </tbody>
    </table>
</div>

<div>
    <a type="button" class="btn btn-primary" href="<?= base_url('events/assignments/view/'.$e_id); ?>">Tilbage til oversigt</a>
</div>