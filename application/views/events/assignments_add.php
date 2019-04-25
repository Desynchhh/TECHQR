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
                <th><a href="<?= base_url('events/assignments_add/'.$e_id.'/'.$offset.'/'.$order_by.'/title'); ?>" >Opgavenavn</a></th>
                <th>Afdeling</th>
                <th><a href="<?= base_url('events/assignments_add/'.$e_id.'/'.$offset.'/'.$order_by.'/notes'); ?>" >Notater</a></th>
                <th>Tilføj</th>
            </tr>
            <?php foreach($asses as $ass):?>
            <tr>
                <td><?= $ass['title'] ?></td>
                <td><?= $ass['d_name'] ?></td>
                <td><?= $ass['notes'] ?></td>
                <td><a href="<?= base_url('events/add_ass/'.$e_id.'/'.$ass['ass_id']); ?>"><button type="button" class="btn btn-sm btn-secondary">Tilføj</button></a></td>
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
    <a href="<?= base_url('events/assignments/view/'.$e_id); ?>"><button type="button" class="btn btn-primary">Tilbage til oversigt</button></a>
</div>