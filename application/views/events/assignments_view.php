<h2><?= $title ?></h2>

<br>
<div>
    <a href="<?= base_url('events/assignments/add/'.$e_id); ?>"><button type="button" class="btn btn-warning">Tilføj opgave</button></a>
</div>
<br>
<div>
    <a href="<?= base_url('events/view/'.$e_id); ?>"><button type="button" class="btn btn-primary">Tilbage til event</button></a>
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
                <td><a href="<?= base_url('events/remove_ass/'.$e_id.'/'.$ass['ass_id']); ?>"><button type="button" class="btn btn-sm btn-danger">Fjern</button></a></td>
            </tr>
            <?php endforeach;?>
        </tbody>
    </table>
        <!-- Pagination -->
    <div class="pagination-links">
        <?= $this->pagination->create_links(); ?>
    </div>
</div>

<div>
    <a href="<?= base_url('events/view/'.$e_id); ?>"><button type="button" class="btn btn-primary">Tilbage til event</button></a>
</div>