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
    <button class="btn btn-danger" onclick="deleteTeam('<?= base_url('teams/delete/'.$e_id); ?>')">Slet hold</button>
</div>
<br>

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
                <th><a href="<?= base_url('teams/view/'.$e_id.'/'.$page_offset.'/'.$order_by.'/number'); ?>">Hold #</a></th>
                <th><a href="<?= base_url('teams/view/'.$e_id.'/'.$page_offset.'/'.$order_by.'/score'); ?>">Point</a></th>
                <th>Sidste handling</th>
                <th>Medlemmer</th>
            </tr>
            <?php foreach($teams as $team):?>
                <tr>
                    <td><?= $team['t_num'] ?></td>
                    <td><?= $team['t_score']?></td>
                    <td><?php $action = ($team['action']) ? $team['action'] : 'Ingen handlinger'; echo $action ?></td>
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

    <!-- Back button -->
<div>
    <a class="btn btn-primary" href="<?= base_url('events/view/'.$e_id); ?>">Tilbage til event</a>
</div>
