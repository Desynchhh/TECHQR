<h2><?= $title ?></h2>
<hr>

    <!-- Back Button -->
<div>
    <a href="<?= base_url('events/view/'.$event['e_id']); ?>"><button type="button" class="btn btn-primary">Tilbage til event</button></a>
</div>

<br>

<div>
    <table class="table">
        <tbody>
                    <!-- Table Headers -->
            <tr>
                <th><a href="<?= base_url('events/actions/'.$e_id.'/'.$offset.'/'.$order_by.'/t_num'); ?>" >Hold #</a></th>
                <th><a href="<?= base_url('events/actions/'.$e_id.'/'.$offset.'/'.$order_by.'/action'); ?>" >Handling</a></th>
                <th><a href="<?= base_url('events/actions/'.$e_id.'/'.$offset.'/'.$order_by.'/title'); ?>" >Opgavenavn</a></th>
                <th><a href="<?= base_url('events/actions/'.$e_id.'/'.$offset.'/'.$order_by.'/answer'); ?>" >Svar</a></th>
                <th><a href="<?= base_url('events/actions/'.$e_id.'/'.$offset.'/'.$order_by.'/points'); ?>" >Point</a></th>
                <th><a href="<?= base_url('events/actions/'.$e_id.'/'.$offset.'/'.$order_by.'/created_at'); ?>" >Tidspunkt</a></th>
            </tr>
            <?php foreach($actions as $action): ?>
                    <!-- Table Data -->
                <tr>
                    <td><?= $action['t_num'] ?></td>
                    <td><?= $action['action'] ?></td>
                    <?php if(isset($action['ass_title'])): ?>
                        <td><?= $action['ass_title'] ?></td>
                        <td><?= $action['answer'] ?></td>
                        <td><?= $action['points'] ?></td>
                    <?php else: ?>
                        <td></td>
                        <td></td>
                        <td></td>
                    <?php endif;?>
                    <td><?= $action['created_at'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

        <!-- Pagination -->
    <div class="pagination-links">
        <?= $this->pagination->create_links(); ?>
    </div>
</div>