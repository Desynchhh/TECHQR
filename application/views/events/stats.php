<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
<h2><?= $title ?></h2>
<hr>

<div class="row">
    <div class="col-md-8">
        <canvas id="piechart"></canvas>
    </div>
</div>
<div>
    <a href="<?= base_url('events/view/'.$e_id); ?>"><button type="button" class="btn btn-primary">Tilbage til event</button></a>
</div>

<script>
    var color1 = 'rgba(0, 255, 0, 0)';
    var answers = ['svar 1', 'svar 2', 'svar 3'];
    var data = [0, 10, 25];
    var ctx = document.getElementById('piechart').getContext('2d');
    var chart = new Chart(ctx, {
        //Chart Type
        type: 'line',

        //Data for dataset
        data: {
            labels: answers,
            datasets: [{
                label: 'Første Opgave',
                backgroundColor: color1,
                borderColor: 'rgb(132, 99, 255)',
                data: data
            }]
        },
        
        //Config options
        options: {}
    });
</script>