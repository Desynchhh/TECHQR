    <!-- Title -->
<h2><?= $title ?></h2>
<h5>Klik på et events navn for at se flere detaljer.</h5>
<hr>

    <!-- Create event button -->
<div>
    <a href="<?= base_url('events/create'); ?>"><button type="button" class="btn btn-warning">Opret nyt event</button></a>
</div>

<br>

    <!-- Table -->
<div>
    <table class="table">
        <tbody>
                <!-- Table headers -->
            <tr>
                <th><a href="<?= base_url("events/index/$per_page/$order_by/e_name/$offset"); ?>">Eventnavn</a></th>
                <th><a href="<?= base_url("events/index/$per_page/$order_by/d_name/$offset"); ?>">Afdeling</a></th>
            </tr>
                <!-- Table data -->
            <?php foreach($events as $event):?>
                <tr>
                    <td><a href="<?= base_url('events/view/'.$event['e_id']); ?>"><?= $event['e_name'] ?></a></td>
                    <td><?= $event['d_name'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>