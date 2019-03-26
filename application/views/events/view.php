    <!-- JS script  -->
<script>
    function deleteEvent(){
        input = prompt('Er du sikker på du vil slette dette event?\nIndtast eventnavnet for at bekræfte:');
        if(input){// != null && input != ""
            document.getElementById("inputDelete").value = input;
            document.getElementById("inputFormDelete").submit();
        }
    }

    function renameEvent(){
        input = prompt("Indtast nyt navn til eventet:");
        if(input){
            document.getElementById("inputRename").value = input;
            document.getElementById("inputFormRename").submit();
        }
    }
</script>

    <!-- Title -->
<h2><?= $title ?></h2>
<hr>

    <!-- Info -->
<div>
    <dt>Eventnavn:</dt>
    <dd class="event-dd"><?= $event['e_name'] ?></dd>
    <dt>Afdeling:</dt>
    <dd class="event-dd"><?= $event['d_name'] ?></dd>
    <dt>Opgaver:</dt>
    <dd class="event-dd"><?= count($event_asses) ?> - <a class="btn btn-sm btn-primary" href="<?= base_url('events/assignments/view/'.$event['e_id']); ?>">Vis</a></dd>
    <dt>Maks point:</dt>
    <dd class="event-dd"><?= $max_points ?></dd>
    <dt>Hold & Score:</dt>
    <dd class="event-dd"><?= count($teams) ?> - <a class="btn btn-sm btn-primary" href="<?= base_url('teams/view/'.$event['e_id']); ?>">Vis</a></dd>
    <!-- Acions, PDF, & Delete buttons -->
    <dd><a href="<?= base_url('events/actions/'.$event['e_id']); ?>"><button type="button" class="btn btn-primary">Se alle handlinger</button></a> 
    <a href="<?= base_url('events/stats/'.$event['e_id']); ?>"><button type="button" class="btn btn-primary">Opgave statistik</button></a>
    <a href="<?= base_url('events/pdf/'.$event['e_id']); ?>"><button type="button" class="btn btn-primary">Se PDF</button></a>
    </dd>
</div>

    <!-- Manage buttons -->
<div>
    <button class="btn btn-warning" onclick="renameEvent()">Omdøb</button>
    <a href="<?= base_url('events/manage/'.$event['e_id']); ?>"><button type="button" class="btn btn-warning">Manage</button></a>
    <button class="btn btn-danger" onclick="deleteEvent()">Slet event</button>
</div>

<div>
</div>

<br>

    <!-- Back button -->
<div>
    <a class="btn btn-primary" href="<?= base_url('events'); ?>">Tilbage til oversigt</a>
</div>

    <!-- Hidden deletion form -->
<?= form_open('events/delete/'.$event['e_id'], array('id' => 'inputFormDelete'));?>
    <input type="hidden" name="input" id="inputDelete" value="" />
<?= form_close();?>

    <!-- Hidden renaming form -->
<?= form_open('events/edit/'.$event['e_id'], array('id' => 'inputFormRename')); ?>
    <input type="hidden" name="input" id="inputRename" value="" />
<?= form_close(); ?>