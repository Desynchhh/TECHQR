    <!-- Title -->
<h2><?= $title ?></h2>
<h5>Kør musen hen over en portion i cirkel diagrammet for at se hvor mange hold, der har valgt den svarmulighed.<br>
Datoen læses: ÅÅÅÅ-MM-DD tt:mm:ss</h5>
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
        <div class="col-md-5">
            <h3>Opgave: <?= $ass['title'] ?></h3>
            <div>
                <p><b>Sidst besvaret dato:</b> <?php $date = ($ass['last_answered'] == NULL) ? 'Endnu ikke besvaret.' : $ass['last_answered']; echo $date; ?></p>
                <p>Besvaret af <?= $answered_array[array_search($ass, $event_ass)] ?> ud af <?= $total_teams ?> hold</p>
            </div>
            <table class="table">
                    <!-- Table headers -->
                <tr>
                    <th>Svarmulighed</th>
                    <th>Besvarelsesprocent</th>
                </tr>
                    <!-- Table data -->
                <?php foreach($event_ans[array_search($ass, $event_ass)] as $ans):?>
                    <tr>
                        <td><?= $ans['answer'] ?></td>
                        <td id="dd<?= $ans['id'] ?>">[VALG PROCENT]</td>
                    </tr>
                <?php endforeach;?>
            </table>
        </div>
           
            <!-- Chart -->
        <div class="col-md-7">
            <canvas id="piechart<?= $ass['ass_id'] ?>"></canvas>
        </div>
    </div>
    <hr class="thick-hr">
<?php endforeach;?>
    
    <!-- Back button -->
<div>
    <a href="<?= base_url("events/view/$e_id"); ?>"><button type="button" class="btn btn-primary">Tilbage til event</button></a>
</div>

    <!-- Scripts -->
<script type="text/javascript" src="<?= base_url('assets/js/imports/Chart.js')?>"></script>
<script type="text/javascript">
        //Convert PHP arrays to JSON objects, so JS can use it
    var teamAns = <?= json_encode($team_ans) ?>;
    var eventAss = <?= json_encode($event_ass) ?>;
    var eventAns = <?= json_encode($event_ans) ?>;
</script>
<script type="text/javascript" src="<?= base_url('assets/js/chart.js')?>"></script>