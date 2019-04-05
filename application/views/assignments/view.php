    <!-- Title -->
<h2><?= $title ?></h2>
<hr>

<!-- Info -->
<div>
    <dl class="dl-horizontal">
        <dt>Opgave navn:</dt>
        <dd class="ass-dd"><?= $ass['ass_title'] ?><br></dd>
        <dt>Tilhørende afdeling:</dt>
        <dd class="ass-dd"><?= $ass['department'] ?><br></dd>
        <dt>Notater:</dt>
        <dd class="ass-dd"><?= $ass['notes'] ?><br></dd>
        <dt>Events:</dt>
        <dd class="ass-dd">0<br></dd>
        <dt>Sidst redigeret:</dt>
        <dd class="ass-dd"><?= $ass['edited_at'] ?><br></dd>
        <dt>Redigeret af:</dt>
        <dd class="ass-dd"><?= $ass['ass_edited_by'] ?><br></dd>
        <dt>Oprettet:</dt>
        <dd class="ass-dd"><?= $ass['ass_created_at'] ?><br></dd>
        <dt>Oprettet af:</dt>
        <dd class="ass-dd"><?= $ass['ass_created_by'] ?><br></dd>
    </dl>
</div>
    <!-- Buttons -->
<div class="row">
    
        <!-- Edit button -->
    <div class="md-col-1" style="margin-left:1.33%;">
        <a href="<?= base_url('assignments/edit/'.$ass['ass_id']); ?>"><button type="button" class="btn btn-warning">Rediger opgave</button></a>
    </div>
        <!-- Delete button -->
    <div class="md-col-1" style="margin-left:1%;">
        <button type="button" class="btn btn-danger" onclick="submitHidden('input', 'inputForm', 'opgaven');">Slet opgave</button>
            <!-- Hidden form to be submitted in case the assignments title contains an illegal URI character -->
        <?= form_open('assignments/delete/'.$ass['ass_id'], array('id'=>'inputForm', 'method'=>'post')); ?>
            <input type="hidden" name="input" id="input" value="" />
        <?= form_close(); ?>
    </div>

</div>
<br>
    <!-- Back button -->
<div>
    <a href="<?= base_url('assignments'); ?>"><button type="button" class="btn btn-primary">Tilbage til oversigt</button></a>
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
            <?php $count = 1; foreach($ass[0] as $answer):?>
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