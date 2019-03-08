<h2><?= $title ?></h2>
<br>
<div>
    <?= form_open('teams/create/'.$e_id); ?>
        <label>Antal hold:</label>
        <input type="text" name="teams" placeholder="Antal hold" />
        <input type="submit" class="btn btn-secondary" value="Opret hold" />
    <?= form_close(); ?>
</div>
<div>
    <?= form_open('teams/delete/'.$e_id); ?>
        <input type="submit" class="btn btn-danger" value="Slet hold" />
    <?= form_close(); ?>
</div>

<div>
    <a class="btn btn-primary" href="<?= base_url('events/view/'.$e_id); ?>">Tilbage til event</a>
</div>

<br>

<div>
    <table class="table">
        <tbody>
            <tr>
                <th>Hold #</th>
                <th>Point</th>
                <th>Sidste handling</th>
                <th>Medlemmer</th>
            </tr>
            <?php $count = 1; foreach($teams as $team):?>
            <tr>
                <td><?= $team['t_num'] ?></td>
                <td><?= $team['t_score'] ?></td>
                <td>[Not yet implemented]</td>
                <td><?= count($students[$team['t_num']-1]) ?></td>
            </tr>
            <?php $count++; endforeach;?>
        </tbody>
    </table>
</div>

<div>
    <a class="btn btn-primary" href="<?= base_url('events/view/'.$e_id); ?>">Tilbage til event</a>
</div>