<h2><?= $title ?></h2>
<hr>

<div>
    <dl class="dl-horizontal">
        <dt>Opgave navn:</dt>
        <dd class="ass-dd"><?= $ass['ass_title'] ?><br></dd>
        <dt>Tilhørende afdeling:</dt>
        <dd class="ass-dd"><?= $ass['department'] ?><br></dd>
        <dt>Lokation:</dt>
        <dd class="ass-dd"><?= $ass['location'] ?><br></dd>
        <dt>Events:</dt>
        <dd class="ass-dd">0<br></dd>
        <dt>Sidst redigeret:</dt>
        <dd class="ass-dd"><?= $ass['edited_at'] ?><br></dd>
        <dt>Redigeret af:</dt>
        <dd class="ass-dd"><?= $ass[0]['ass_edited_by'] ?><br></dd>
        <dt>Oprettet:</dt>
        <dd class="ass-dd"><?= $ass['ass_created_at'] ?><br></dd>
        <dt>Oprettet af:</dt>
        <dd class="ass-dd"><?= $ass['ass_created_by'] ?><br></dd>
        <!-- view each answer to the user -->
        <!-- <?php $count = 1; foreach($ass[1] as $answer):?>
            <br>
            <dt>Svarmulighed <?= $count ?></dt>
            <dd class="ass-dd"><?= $answer['answer'] ?><br></dd>
            <dt>Point <?= $count ?></dt>
            <dd class="ass-dd"><?= $answer['points'] ?><br></dd>
        <?php $count++; endforeach;?> -->
    </dl>
</div>

<div class="row">
    <div class="md-col-1" style="margin-left:1.33%;">
        <a href="<?= base_url('assignments/edit/'.$ass['ass_id']); ?>"><button class="btn btn-warning">Rediger opgave</button></a>
    </div>
    <div class="md-col-1" style="margin-left:1%;">
        <?= form_open('assignments/confirm_delete/'.$ass['ass_id']); ?>
            <input type="submit" value="Slet opgave" class="btn btn-danger" />
        <?= form_close(); ?>
    </div>
</div>
<div>
    <a type="button" class="btn btn-primary" href="<?= base_url('assignments'); ?>">Tilbage til oversigt</a>
</div>
<br/>

<div>
    <table class="table">
        <tbody>
            <tr>
                <th>Svar #</th>
                <th>Svar</th>
                <th>Point</th>
            </tr>
            <!-- create a <tr> with <td> children for each answer in this assignment -->
            <?php $count = 1; foreach($ass[1] as $answer):?>
                <tr>
                    <td><?= $count ?></td>
                    <td><?= $answer['answer'] ?></td>
                    <td><?= $answer['points'] ?></td>
                </tr>
            <?php $count++; endforeach;?>
        </tbody>
    </table>
</div>
<br>