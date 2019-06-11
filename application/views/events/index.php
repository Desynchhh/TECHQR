    <!-- Title -->
<h2><?= $title ?></h2>
<h5>Klik på et events navn for at se flere detaljer.</h5>
<hr>

    <!-- Create event button -->
<div>
    <a href="<?= base_url('events/create'); ?>"><button type="button" class="btn btn-warning">Opret nyt event</button></a>
</div>

<br>

    <!-- Search bar -->
<?= form_open("events/index/$per_page/$order_by/$sort_by/0"); ?>
    <label for="search_string">Søg efter eventnavn eller afdeling:</label>
    <input type="text" id="search_string" name="search_string" placeholder="Søg" value="<?= (isset($search_string)) ? $search_string : ''; ?>">
    <input type="submit" value="Søg" class="btn btn-secondary">
<?= form_close(); ?>

    <!-- Table -->
<div>
    <table class="table">
        <tbody>
                <!-- Table headers -->
            <tr>
                <?php foreach($fields as $header => $data): ?>
                    <th>
                        <a href="<?= base_url("events/index/$per_page/". (($order_by == 'asc' && $sort_by == $data) ? 'desc' : 'asc' ) ."/$data/$offset"); ?>">
                            <?= $header ?>
                        </a>
                    </th>
                <?php endforeach;?>
            </tr>
                <!-- Table data -->
            <?php foreach($events as $event):?>
                <tr>
                    <?php foreach($fields as $header => $data): ?>
                        <?php if($data == 'e_name'):?>
                            <td>
                                <a href="<?= base_url("events/view/$event[e_id]"); ?>">
                                    <?= $event[$data] ?>
                                </a>
                            </td>
                        <?php else: ?>
                            <td><?= $event[$data] ?></td>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>