<h2><?= $title ?></h2>

<br>
<div>
    <a type="button" class="btn btn-warning" href="<?= base_url('events/assignments/add/'.$e_id); ?>">Tilføj opgave</a>
</div>
<br>
<div>
    <a type="button" class="btn btn-primary" href="<?= base_url('events/view/'.$e_id); ?>">Tilbage til event</a>
</div>
<br>

<div>
    <table class="table">
        <tbody>
            <tr>
                <th>Opgavenavn</th>
                <th>Afdeling</th>
                <th>Lokation</th>
                <th>Fjern</th>
            </tr>
            <?php foreach($asses as $ass):?>
            <tr>
                <td><a href="<?= base_url('assignments/view/'.$ass['ass_id']); ?>"><?= $ass['title'] ?></a></td>
                <td><?= $ass['name'] ?></td>
                <td><?= $ass['location'] ?></td>
                <td><a class="btn btn-sm btn-outline-danger" href="<?= base_url('events/remove_ass/'.$e_id.'/'.$ass['ass_id']); ?>" >Fjern</a></td>
            </tr>
            <?php endforeach;?>
        </tbody>
    </table>
</div>

<div>
    <a type="button" class="btn btn-primary" href="<?= base_url('events/view/'.$e_id); ?>">Tilbage til event</a>
</div>