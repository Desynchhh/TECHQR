    <!-- Title -->
<h2><?= $title ?></h2>
<h5>Se detaljer omkring opgaven samt rediger eller slet den.</h5>
<hr>

    <!-- Info -->
<div class="row">
    <div class="col-md-6">
    <h3>Info</h3>
        <div>
            <dl class="dl-horizontal">
                <dt>Opgavenavn:</dt>
                <dd class="ass-dd"><?= $ass['ass_title'] ?><br></dd>
                <dt>Tilhørende afdeling:</dt>
                <dd class="ass-dd"><?= $ass['department'] ?><br></dd>
                <dt>Notater:</dt>
                <dd class="ass-dd"><?= $ass['notes'] ?><br></dd>
                <dt>Events:</dt>
                <dd class="ass-dd">
                        <!-- Link to all events the assignment is in, if any -->
                    <?php if($events): ?>
                        <?php foreach($events as $event): ?>
                            <a target="_blank" href="<?= base_url('events/view/'.$event['e_id']); ?>"><?= $event['e_name'] ?></a><br>
                        <?php endforeach; ?>
                    <?php else: ?>
                        Ingen events
                    <?php endif; ?>
                </dd>
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
            <div class="view-btn">
                <a href="<?= base_url('assignments/edit/'.$ass['ass_id']); ?>"><button type="button" class="btn btn-warning">Rediger opgave</button></a>
            </div>
                <!-- Delete button -->
            <div class="view-btn">
                <button type="button" class="btn btn-danger" onclick="submitHidden('input', 'inputForm', 'opgaven');">Slet opgave</button>
                    
                    <!-- Hidden form to be submitted in case the assignments title contains an illegal URI character -->
                <?= form_open('assignments/delete/'.$ass['ass_id'], array('id'=>'inputForm', 'method'=>'post')); ?>
                    <input type="hidden" name="input" id="input" value="" />
                <?= form_close(); ?>
            </div>
        </div>
        <div class="row">
            <!-- Back button -->
            <div class="view-btn">
                <a href="<?= base_url("assignments/index/10/asc/title"); ?>"><button type="button" class="btn btn-primary">Tilbage til oversigt</button></a>
            </div>
        </div>
    </div>
        
    <div class="col-md-6">
        <h3>Svarmuligheder</h3>
        <!-- Table -->
        <div>
            <table class="table">
                <tbody>
                        <!-- Table headers -->
                    <tr>
                        <th>Svar #</th>
                        <th>Svar</th>
                        <th>Point</th>
                    </tr>
                        <!-- Table data -->
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
    </div>
</div>
