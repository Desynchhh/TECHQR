<h2><?= $title ?></h2>
<br>
    <!-- Create field & button -->
<div>
    <?= form_open('teams/create/'.$e_id); ?>
        <label>Antal hold:</label>
        <input type="text" name="teams" placeholder="Antal hold" />
        <input type="submit" class="btn btn-secondary" value="Opret hold" />
    <?= form_close(); ?>
</div>
    <!-- Delete button -->
<div>
    <?= form_open('teams/delete/'.$e_id); ?>
        <input type="submit" class="btn btn-danger" value="Slet hold" />
    <?= form_close(); ?>
</div>
    <!-- Back button -->
<div>
    <a class="btn btn-primary" href="<?= base_url('events/view/'.$e_id); ?>">Tilbage til event</a>
</div>

<br>
    <!-- Table -->
<div>
    <table class="table">
        <tbody>
            <tr>
                <th>Hold #</th>
                <th>Point</th>
                <th>Sidste handling</th>
                <th>Medlemmer</th>
            </tr>
            <?php foreach($teams as $team):?>
                <tr>
                    <td><?= $team['t_num'] ?></td>
                    <td><?= $team['t_score']?></td>
                    <td>[Not yet implemented]</td>
                    <td><?= $students[array_search($team['t_num']-$offset, array_keys($teams))]; ?></td>
                </tr>
            <?php endforeach;?>
        </tbody>
    </table>
</div>

	<!-- Pagination -->
<div class="pagination-links">
	<?= $this->pagination->create_links(); ?>
</div>

<div>
    <a class="btn btn-primary" href="<?= base_url('events/view/'.$e_id); ?>">Tilbage til event</a>
</div>
