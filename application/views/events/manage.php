    <!-- Title -->
<h2><?= $title ?></h2>
<hr>

<!-- Get CKEditor (used for sending messages to the students) -->
<script src="http://cdn.ckeditor.com/4.11.3/standard/ckeditor.js"></script>

    <!-- Back button -->
<div class="row">
    <a href="<?= base_url("events/view/$e_id"); ?>"><button type="button" class="btn btn-primary">Tilbage til event</button></a>
</div>

<br/>

    <!-- Tools -->
<div class="row">
        <!-- Tildel/Fratag Point -->
    <div class="col-md-4">
    <h4>Tildel/fratag point</h4>
    <h5>Indtast et negativt tal for at fratage point.</h5>
        <div>
                <!-- Form -->
            <?= form_open('events/manage_points/'.$e_id); ?>
                <p class="same-line">Tildel </p>
                <input style="width:50px;" type="text" name="points" placeholder="point" class="same-line" /> 
                <p class="same-line"> point til hold </p>
                    <!-- Team dropdown -->
                <select name="t_id" class="same-line">
                    <option selected hidden value=""></option>
                    <?php foreach($teams as $team): ?>
                        <option value="<?= $team['t_id'] ?>"><?= $team['t_num'] ?></option>
                    <?php endforeach; ?>
                </select>
                <br>
                    <!-- Submit button -->
                <input type="submit" class="btn btn-secondary" value="Tildel" />
            <?= form_close(); ?>
        </div>
    </div>

        <!-- Check Hold -->
    <div class="col-md-4" style="border-right:solid lightgrey;border-left:solid lightgrey;">
        <div>
            <h4>Disse hold har ingen medlemmer:</h4>
            <h5>Genindlæs siden for at opdatere listen.</h5>
            <a href="<?= base_url("events/manage/$e_id") ?>"><button type="button" class="btn btn-secondary">Opdater</button></a>
        </div>
        <br>
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
        <p>Dette vil ubemande alle hold, fjerne alle point, slette alle handlinger, og gøre alle opgaver besvarlige igen.</p>
        <button class="btn btn-danger" onclick="resetEvent('<?= base_url('events/reset/'.$e_id); ?>')">Reset</button>
    </div>
</div>

<hr>

<div class="row">
        <!-- Send Message -->
    <div class="col-md-8">
        <h4>Besked</h4>
        <h5>Send en besked ud til alle hold. Beskeden kan slettes ved at sende en tom besked.</h5>
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

    <!-- Back button -->
<div class="row">
    <a href="<?= base_url("events/view/$e_id"); ?>"><button type="button" class="btn btn-primary">Tilbage til event</button></a>
</div>

<!-- replace textarea with the ckeditor -->
<script>CKEDITOR.replace('editor1');</script>