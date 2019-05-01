    <!-- Title -->
<h2><?= $title ?></h2>
<hr>

    <!-- Back button -->
<div>
    <a href="<?= base_url("events/view/$e_id"); ?>"><button type="button" class="btn btn-primary">Tilbage til event</button></a>
</div>

<br>

    <!-- Table -->    
<?php foreach($event_ass as $ass):?>
    <div class="row">
            <!-- Info -->
        <div class="col-md-6">
            <dl class="dl-horizontal">
                <h3>Opgave: <?= $ass['title'] ?></h3>
                <?php foreach($event_ans[array_search($ass, $event_ass)] as $ans):?>
                    <dt><?= $ans['answer'] ?>:</dt>
                    <br>
                    <dd id="dd<?= $ans['id'] ?>" class="ass-dd">[VALG PROCENT]</dd>
                <?php endforeach;?>
                <dt>Sidst besvaret dato:</dt>
                <br>
                <dd><?php $date = ($ass['last_answered'] == NULL) ? 'Endnu ikke besvaret.' : $ass['last_answered']; echo $date; ?></dd>
            </dl>
        </div>
           
            <!-- Chart -->
        <div class="col-md-6">
            <canvas id="piechart<?= $ass['ass_id'] ?>"></canvas>
        </div>
    </div>
    <hr>
<?php endforeach;?>
    
    <!-- Back button -->
<div>
    <a href="<?= base_url("events/view/$e_id"); ?>"><button type="button" class="btn btn-primary">Tilbage til event</button></a>
</div>

    <!-- Scripts -->
<script type="text/javascript" src="<?= base_url('assets/js/imports/Chart.js')?>"></script>
<script type="text/javascript">
    var teamAns = <?= json_encode($team_ans) ?>;
    var eventAss = <?= json_encode($event_ass) ?>;
    var eventAns = <?= json_encode($event_ans) ?>;
</script>
<script type="text/javascript" src="<?= base_url('assets/js/chart.js')?>"></script>