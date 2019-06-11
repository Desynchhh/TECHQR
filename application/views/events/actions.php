    <!-- Title -->
<h2><?= $title ?></h2>
<h5>Se alle handlinger, der har fundet sted under dette event.<br>
Datoen læses: ÅÅÅÅ-MM-DD tt:mm:ss</h5>
<hr>

    <!-- Back Button -->
<div>
    <a href="<?= base_url("events/view/$event[e_id]"); ?>"><button type="button" class="btn btn-primary">Tilbage til event</button></a>
</div>

<br>

    <!-- Table -->
<div>
    <table class="table">
        <tbody>
                <!-- Table Headers -->
            <tr>
                <th><a href="<?= base_url("events/actions/$e_id/$per_page/$order_by/t_num/$offset"); ?>" >Hold #</a></th>
                <th><a href="<?= base_url("events/actions/$e_id/$per_page/$order_by/action/$offset"); ?>" >Handling</a></th>
                <th><a href="<?= base_url("events/actions/$e_id/$per_page/$order_by/title/$offset"); ?>" >Opgavenavn</a></th>
                <th><a href="<?= base_url("events/actions/$e_id/$per_page/$order_by/answer/$offset"); ?>" >Svar</a></th>
                <th><a href="<?= base_url("events/actions/$e_id/$per_page/$order_by/points/$offset"); ?>" >Point</a></th>
                <th><a href="<?= base_url("events/actions/$e_id/$per_page/$order_by/created_at/$offset"); ?>" >Tidspunkt</a></th>
            </tr>
            <?php foreach($actions as $action): ?>
                    <!-- Table Data -->
                <tr>
                    <td><?= $action['t_num'] ?></td>
                    <td><?= $action['action'] ?></td>
                    <td><?= (isset($action['ass_title'])) ? $action['ass_title'] : '';?></td>
                    <td><?= (isset($action['answer'])) ? $action['answer'] : '';?></td>
                    <td><?= (isset($action['points'])) ? $action['points'] : ''; ?></td>
                    <td><?= $action['created_at'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

    <!-- Back Button -->
<div>
    <a href="<?= base_url("events/view/$event[e_id]"); ?>"><button type="button" class="btn btn-primary">Tilbage til event</button></a>
</div>