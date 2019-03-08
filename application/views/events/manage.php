<h2><?= $title ?></h2>
<hr>
<!-- Get CKEditor (used for sending messages to the students) -->
<script src="http://cdn.ckeditor.com/4.11.3/standard/ckeditor.js"></script>

<div class="row">
    <!-- Tildel/Fratag Point -->
    <div class="col-md-4" style="border-right:solid lightgrey;">
    <h4>Tildel/Fratag Point</h4>
    <h5>Indtast et negativt tal for at fratage point.</h5>
        <div>
            <?= form_open() ?>
                <p class="same-line">Tildel </p>
                <input style="width:50px;" type="text" name="points" placeholder="point" class="same-line" /> 
                <p class="same-line"> point til hold </p>
                <select class="same-line">
                    <?php foreach($teams as $team): ?>
                        <option value="<?= $team['t_id'] ?>">Hold <?= $team['t_num'] ?></option>
                    <?php endforeach; ?>
                </select>
                <br>
                <input type="submit" class="btn btn-secondary" value="Tildel" />
            <?= form_close(); ?>
        </div>

        <!--
        <table class="table">
            <tbody>
                <tr>
                    <th>Hold #</th>
                    <th>Point</th>
                    <th></th>
                    <th>Manage Point</th>
                </tr>
                <?php foreach($teams as $team): ?>
                <tr>
                    <td><?= $team['t_num'] ?></td>
                    <td><?= $team['t_score'] ?></td>
                    <td></td>
                    <td>
                        <?= form_open(); ?>
                            <input type="submit" class="btn btn-sm btn-danger" value="-"/>
                            <input type="text" name="point" placeholder="point" style="width:6%;" />
                            <input type="submit" class="btn btn-sm btn-success" value="+" />
                        <?= form_close(); ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        -->
    </div>

    <!-- Check Hold -->
    <div class="col-md-4">
        <h4>Check Hold</h4>
        <h5>Få en liste af alle hold uden medlemmer.</h5>
        <br>
        <div>
            <a href="<?= base_url('events/check_teams/'.$e_id); ?>"><button type="button" class="btn btn-secondary">Check Hold</button></a>
        </div>
    </div>

    <!-- Team List -->
    <div class="col-md-4">
    <?php if(isset($empty_teams)): ?>
        <h3>Disse hold har ingen medlemmer!</h3>
        <div class="row">
        <?php foreach($empty_teams as $team): ?>
            <p style="color:red;">Hold <?= $team['t_num'] ?></p>
        <?php endforeach; ?>
        </div>
    <?php else: ?>
    <?php endif; ?>
    </div>

</div>

<hr>

<div class="row">
    <div class="col-md-8 offset-md-2">
        <!-- Besked -->
        <h4>Besked</h4>
        <h5>Send en besked ud til alle hold.</h5>
        <?= form_open(); ?>
            <textarea id="editor1" class="form-control" name="message" placeholder="Indtast besked.."></textarea>
            <br>
            <input type="submit" class="btn btn-secondary" value="Send besked" />
        <?= form_close(); ?>
    </div>
</div>

<hr>

<div>
    <a href="<?= base_url('events/view/'.$e_id); ?>"><button type="button" class="btn btn-primary">Tilbage til event</button></a>
</div>

<script>CKEDITOR.replace('editor1');</script>