<h2><?= $title ?></h2>
<hr>

<div>
    <dt>Eventnavn:</dt>
    <dd class="event-dd"><?= $event['e_name'] ?></dd>
    <dt>Afdeling:</dt>
    <dd class="event-dd"><?= $event['d_name'] ?></dd>
    <dt>Opgaver:</dt>
    <dd class="event-dd">[Not yet implemented] - <a class="btn btn-sm btn-outline-warning" href="<?= base_url(''); ?>">Tilføj</a></dd>
    <dt>Hold:</dt>
    <dd class="event-dd">[Not yet implemented] - <a class="btn btn-sm btn-outline-warning" href="<?= base_url(''); ?>">Tilføj</a></dd>
</div>
<br/>
<div class="row">
    <div class="md-col-1" style="margin-left:1.33%;">
        <a href="<?= base_url('events/edit/'.$event['e_id']); ?>"><button type="button" class="btn btn-warning">Rediger event</button></a>
    </div>
        <div class="md-col-1" style="margin-left:1%;">
            <?= form_open('events/delete/'.$event['e_id']); ?>
                <input type="submit" value="Slet event" class="btn btn-danger" />
            <?= form_close(); ?>
        </div>
</div>

<div>
    <a class="btn btn-primary" href="<?= base_url('events'); ?>">Tilbage til oversigt</a>
</div>

<br>

<div>
    <table class="table">
        <tbody>
            <tr>
                <th>Hold #</th>
                <th>Medlemmer</th>
                <th>Score</th>
            </tr>
            <?php $count = 1; foreach($teams as $team):?>
            <tr>
                <td><?= $count ?></td>
                <td><?= count($teams['s_id']) ?></td>
                <td><?= $teams['score'] ?></td>
            </tr>
            <?php $count++; endforeach;?>
        </tbody>
    </table>
</div>