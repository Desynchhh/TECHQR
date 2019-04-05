    <!-- Title -->
<h2><?= $title ?></h2>
<hr>
<?php $index = 0; foreach($event_ass as $ass):?>
    <div class="row">
            <!-- Info -->
        <div class="col-md-6">
            <dl class="dl-horizontal">
                <h3>Opgave: <?= $ass['title'] ?></h3>
                <?php foreach($event_ans[$index] as $ans):?>
                    <dt><?= $ans['answer'] ?>:</dt>
                    <br>
                    <dd class="ass-dd">[VALG PROCENT]</dd>
                <?php endforeach;?>
            </dl>
        </div>
            <!-- Chart -->
        <div class="col-md-6">
            <canvas id="piechart<?= $ass['ass_id'] ?>"></canvas>
        </div>
    </div>
    <hr>
<?php $index++; endforeach;?>
    <!-- Back button -->
<div>
    <a href="<?= base_url('events/view/'.$e_id); ?>"><button type="button" class="btn btn-primary">Tilbage til event</button></a>
</div>
    <!-- Scripts -->
<script type="text/javascript" src="<?= base_url('assets/js/imports/Chart.js')?>"></script>
<script type="text/javascript">
    var eventAss = <?= json_encode($event_ass) ?>;
    var eventAns = <?= json_encode($event_ans) ?>;//, JSON_PRETTY_PRINT
    var teamAns = <?= json_encode($team_ans) ?>;
</script>
<script type="text/javascript" src="<?= base_url('assets/js/chart.js')?>"></script>