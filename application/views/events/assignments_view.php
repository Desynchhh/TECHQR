    <!-- Title -->
<h2><?= $title ?></h2>

<br>
    <!-- Add assignments button -->
<div>
    <a href="<?= base_url("events/assignments/add/$e_id/5/0/ASC/title"); ?>"><button type="button" class="btn btn-warning">Tilføj opgave</button></a>
</div>

<br>
    <!-- Back button -->
<div>
    <a href="<?= base_url("events/view/$e_id"); ?>"><button type="button" class="btn btn-primary">Tilbage til event</button></a>
</div>

<br>

    <!-- Table -->
<div>
    <table class="table">
        <tbody>
                <!-- Table headers -->
            <tr>
                <th><a href="<?= base_url("events/assignments/view/$e_id/$per_page/$offset/$order_by/title"); ?>" >Opgavenavn</a></th>
                <th>Afdeling</th>
                <th><a href="<?= base_url("events/assignments/view/$e_id/$per_page/$offset/$order_by/notes"); ?>" >Notater</a></th>
                <th>Fjern</th>
            </tr>
                <!-- Table data -->
            <?php foreach($asses as $ass):?>
            <tr>
                <td><a target="_blank" href="<?= base_url('assignments/view/'.$ass['ass_id']); ?>"><?= $ass['title'] ?></a></td>
                <td><?= $ass['name'] ?></td>
                <td><?= $ass['notes'] ?></td>
                <td><a href="<?= base_url('events/remove_ass/'.$e_id.'/'.$ass['ass_id']); ?>"><button type="button" class="btn btn-sm btn-danger">Fjern</button></a></td>
            </tr>
            <?php endforeach;?>
        </tbody>
    </table>
</div>

    <!-- Back button -->
<div>
    <a href="<?= base_url('events/view/'.$e_id); ?>"><button type="button" class="btn btn-primary">Tilbage til event</button></a>
</div>