
<h2><?= $title ?></h2>
<hr>

<!-- Get CKEditor (used for sending messages to the students) -->
<script src="http://cdn.ckeditor.com/4.11.3/standard/ckeditor.js"></script>

<!-- JS function to show a dialog box, which will call the reset function in Controllers/Events.php -->
<script>
    function reset(){
        if(window.confirm('Er du sikker på du vil resette dette event?')){
            window.location = '<?= base_url("events/reset/".$e_id); ?>'
        }
    }
</script>

    <!-- HTML -->

<div class="row">
    <!-- Tildel/Fratag Point -->
    <div class="col-md-4" style="border-right:solid lightgrey;">
    <h4>Tildel/Fratag Point</h4>
    <h5>Indtast et negativt tal for at fratage point.</h5>
        <div>
            <?= form_open('events/manage_points/'.$e_id); ?>
                <p class="same-line">Tildel </p>
                <input style="width:50px;" type="text" name="points" placeholder="point" class="same-line" /> 
                <p class="same-line"> point til hold </p>
                <select name="t_num" class="same-line">
                    <option selected hidden value=""></option>
                    <?php foreach($teams as $team): ?>
                        <option value="<?= $team['t_num'] ?>"><?= $team['t_num'] ?></option>
                    <?php endforeach; ?>
                </select>
                <br>
                <input type="submit" class="btn btn-secondary" value="Tildel" />
            <?= form_close(); ?>
        </div>
    </div>

    <!-- Check Hold -->
    <div class="col-md-4" style="border-right:solid lightgrey;">
        <!--
        <h4>Check Hold</h4>
        <h5>Få en liste af alle hold uden medlemmer.</h5>
        <div>
            <a href="<?= base_url('events/check_teams/'.$e_id); ?>"><button type="button" class="btn btn-secondary">Check Hold</button></a>
        </div>
        <br>
        -->
        <h4>Disse hold har ingen medlemmer:</h4>
        <h5>Genindlæs siden for at opdatere.</h5>
        <?php if(!empty($empty_teams)): ?>
        <div class="row">
        <?php foreach($empty_teams as $team): ?>
            <div class="col-md-3">
                <p style="color:red;">Hold <?= $team ?></p>
            </div>
            <br>
        <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p style="color:green;">Alle <?= count($teams) ?> hold bemandet.</p>
    <?php endif; ?>
    </div>

    <!-- Reset event -->
    <div class="col-md-4">
        <h4>Reset event</h4>
        <h5>Start eventet forfra.</h5>
        <p>Dette vil: Ubemande alle hold, fjerne alle point, slette alle handlinger, og gøre alle opgaver besvarlige igen.</p>
        <!-- <a href="<?= base_url('events/confirm_reset/'.$e_id); ?>"><button type="button" class="btn btn-danger">Reset</button></a> -->
        <button class="btn btn-danger" onclick="reset()">Reset</button>
    </div>

    <!-- Team List -->
    <!--
    <div class="col-md-4" style="border-left:solid lightgrey;">
    <?php if(isset($empty_teams)): ?>
        <h3>Disse hold har ingen medlemmer!</h3>
        <div class="row">
        <?php foreach($empty_teams as $team): ?>
            <div class="col-md-3">
                <p style="color:red;">Hold <?= $team ?></p>
            </div>
            <br>
        <?php endforeach; ?>
        </div>
    <?php else: ?>
        <h3>Alle hold har medlemmer!</h3>
        <p style="color:green;">Alle hold bemandet.</p>
    <?php endif; ?>
    </div>
    -->

</div>

<hr>

<div class="row">
        <!-- Send Message -->
    <div class="col-md-8">
        <h4>Besked</h4>
        <h5>Send en besked ud til alle hold. Det er også muligt at sende en tom besked.</h5>
        <?= form_open('events/message/'.$e_id); ?>
            <textarea id="editor1" class="form-control" name="message" placeholder="Indtast besked.."></textarea>
            <br>
            <input type="submit" class="btn btn-secondary" value="Send besked" />
        <?= form_close(); ?>
    </div>

        <!-- Current Message -->
    <div class="col-md-4">
        <h4>Nuværende besked</h4>
        <h5>Beskeden eleverne sidst har fået:</h5>
        <?= $message['message'] ?>
    </div>
</div>

<hr>

<div class="row">
    <a href="<?= base_url('events/view/'.$e_id); ?>"><button type="button" class="btn btn-primary">Tilbage til event</button></a>
</div>

<!-- replace textarea with the ckeditor -->
<script>CKEDITOR.replace('editor1');</script>