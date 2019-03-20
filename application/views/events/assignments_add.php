<h2><?= $title ?></h2>

<br>
<div>
    <a href="<?= base_url('events/assignments/view/'.$e_id); ?>"><button type="button" class="btn btn-primary">Tilbage til oversigt</button></a>
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
                <td><a href="<?= base_url('events/add_ass/'.$e_id.'/'.$ass['id']); ?>"><button type="button" class="btn btn-sm btn-secondary">Tilføj</button></a></td>
            </tr>
            <?php endforeach;?>
        </tbody>
    </table>
</div>

<div>
    <a href="<?= base_url('events/assignments/view/'.$e_id); ?>"><button type="button" class="btn btn-primary">Tilbage til oversigt</button></a>
</div>