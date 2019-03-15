<h2><?= $title ?></h2>
<h5><?= $event['e_name'] ?></h5>
<hr>

<div class="row">
    <div class="col-md-4">
    <?= form_open('teams/create/'.$e_id); ?>
        <label>Antal hold:</label>
        <input type="text" name="teams" placeholder="Antal hold" />
        <input type="submit" class="btn btn-secondary" value="Opret" />
    <?= form_close(); ?>
    </div>
</div>

<div>
    <?=form_open('teams/delete/'.$event['e_id']);?>
        <input type="submit" class="btn btn-danger" value="Slet alle hold" />
    <?=form_close();?>
</div>

<div>
    <a class="btn btn-primary" href="<?= base_url('events/view/'.$e_id); ?>" >Tilbage til event</a>
</div>

<br>

<div>
    <table class="table">
        <tbody>
            <tr>
                <th>Hold #</th>
                <th>Event navn</th>
                <th>Oprettet</th>
                <th>Slet</th>
            </tr>
            <?php $count = 1; foreach($teams as $team):?>
            <tr>
                <td><?= $count ?></td>
                <td><?= $team['e_name'] ?></td>
                <td><?= $team['t_created_at'] ?></td>
                <td><a class="btn btn-sm btn-danger" href="<?= base_url('teams/delete/'.$event['e_id'].'/'.$team['t_id']); ?>">Slet</a></td>
            </tr>
            <?php $count++; endforeach;?>
        </tbody>
    </table>
</div>