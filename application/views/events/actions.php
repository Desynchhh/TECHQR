<h2><?= $title ?></h2>
<hr>

<div>
    <a href="<?= base_url('events/view/'.$event['e_id']); ?>"><button type="button" class="btn btn-primary">Tilbage til event</button></a>
</div>

<br>

<div>
    <table class="table">
        <tbody>
            <tr>
                <th>Hold #</th>
                <th>Handling</th>
                <th>Opgavenavn</th>
                <th>Svar</th>
                <th>Point</th>
                <th>Tidspunkt</th>
            </tr>
            <?php foreach($actions as $action): ?>
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
</div>