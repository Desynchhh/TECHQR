    <!-- Title -->
<h2><?= $title ?></h2>
<h5>Opret et nyt event i systemet.</h5>
<hr>

    <!-- Form -->
<div class="row">
    <div class="col-md-3 offset-md-1">
        <?= form_open('events/create', array('id' => 'createForm')); ?>
            <div class="form-group">
                    <!-- Event name -->
                <label>Eventnavn:</label>
                <input type="text" name="event_name" placeholder="Eventnavn" class="form-control" />
            </div>
            <div class="form-group">
                    <!-- Department dropdown -->
                <label>Afdeling</label>
                <select name="d_id" class="form-control">
                    <option selected hidden value="<?= $departments[0]['d_id'] ?>">
                        <?= $departments[0]['d_name'] ?>
                    </option>
                    <?php foreach($departments as $department): ?>
                        <option value="<?= $department['d_id'] ?>"><?=$department['d_name']?></option>
                    <?php endforeach; ?> -->
                    <!-- <option selected hidden value="<?= $this->session->userdata['departments'][0]['d_id']; ?>">
                        <?= $this->session->userdata['departments'][0]['d_name']; ?>
                    </option>
                    <?php foreach($this->session->userdata('departments') as $department): ?>
                        <option value="<?= $department['d_id'] ?>"><?=$department['d_name']?></option>
                    <?php endforeach; ?> -->
                </select>
            </div>
                <!-- Submit button -->
            <input type="submit" id="submitBtn" class="btn btn-secondary" value="Opret" onclick="disableButton('submitBtn', 'createForm')" />
        <?= form_close(); ?>
    </div>
</div>

    <!-- Back button -->
<div>
    <a href="<?= base_url("events/index/10/asc/e_name"); ?>"><button type="button" class="btn btn-primary">Tilbage til oversigt</button></a>
</div>