    <!-- Title -->
<h2><?= $title ?></h2>
<h5>Benyt "Lav"-knapperne, hvis holdene eller opgaverne er blevet ændret, siden eventet sidst var brugt.<br>
OBS!: Det kan tage op til 1 minut at lave PDF'erne, hvis der er mange hold eller opgaver!</h5>
<hr>

<div class="row">

        <!-- Select / Open Team PDF -->
    <div class="col-md-4" style="border-right:1px solid lightgrey;">
        <?= form_open('events/open_pdf/'.url_title($event['e_name'].'-'.$e_id).'/team-pdf', array('target' => '_blank')); ?>
            <h4>Hold PDF</h4>
            <label>Vælg hold</label>
                <!-- PDF dropdown -->
            <select name="filename" class="form-control">
                    <!-- Add all PDFs from the events team-pdf folder as a selectable option  -->
                <?php foreach($team_pdf as $pdf): ?>
                    <option value="<?= $pdf ?>"><?= $pdf ?></option>
                <?php endforeach; ?>
            </select>
            <br>
            <?php if($team_pdf): ?>
                    <!-- Only show 'Open PDF' if there is any available -->
                <input type="submit" class="btn btn-secondary" value="Åben PDF" />
            <?php endif; ?>
        <?= form_close(); ?>
    </div>

        <!-- Select / Open Assignment PDF -->
    <div class="col-md-4" style="border-right:1px solid lightgrey;">
        <?= form_open('events/open_pdf/'.url_title($event['e_name'].'-'.$e_id).'/assignment-pdf', array('target' => '_blank')); ?>
            <h4>Opgave PDF</h4>
            <label>Vælg opgave</label>
            <select name="filename" class="form-control">
                    <!-- Add all PDFs from the events assignment-pdf folder as a selectable option -->
                <?php foreach($ass_pdf as $pdf):?>
                    <option value="<?= $pdf ?>"><?= $pdf ?></option>
                <?php endforeach;?>
            </select>
            <br>
            <?php if($ass_pdf): ?>
                <!-- Only show buttons if there are any files available -->
                <div class="row">
                    <div class="col-md-6">
                        <input type="submit" class="btn btn-secondary" value="Åben PDF" />
        <?= form_close(); ?>
                    </div>
                    <!-- Hidden form. Open all PDFs -->
                    <div class="col-md-6">
                        <?= form_open('events/open_pdf/'.url_title($event['e_name'].'-'.$e_id).'/all-assignments', array('target' => '_blank')); ?>
                            <input type="hidden" name="filename" value="ALLE-OPGAVER-<?= strtoupper(url_title($event['e_name'])) ?>.pdf">
                            <input type="submit" class="btn btn-secondary" value="Åben alle opgaver">
                        <?= form_close(); ?>
                    </div>
                </div>
            <?php endif; ?>
    </div>

        <!-- Create PDF Buttons -->
    <div class="col-md-4 form-group">
            <!-- Create team PDF -->
        <h4>Lav PDF</h4>
        <label>Lav PDF til ALLE hold</label>
        <br>
        <!--
        <a type="button" class="btn btn-secondary" id="create-team-btn" href="<?= base_url('events/create_team_pdf/'.$event['e_id']); ?>">
            Lav hold
        </a>
        -->
        <a href="<?= base_url('events/create_team_pdf/'.$event['e_id']); ?>">
            <button type="button" class="btn btn-secondary" id="create-team-btn">Lav hold</button>
        </a>
        <br><br>
            <!-- Create assignment PDF -->
        <label for="create-ass-btn">Lav PDF til ALLE opgaver</label>
        <br>
        <!--
        <a type="button" class="btn btn-secondary" id="create-ass-btn" href="<?= base_url('events/create_ass_pdf/'.$event['e_id']); ?>">
            Lav opgaver
        </a>
        -->
        <a href="<?= base_url('events/create_ass_pdf/'.$event['e_id']); ?>">
            <button type="button" class="btn btn-secondary" id="create-ass-btn">Lav opgaver</button>
        </a>
    </div>

</div>

    <!-- Back button -->
<div>
    <a href="<?= base_url("events/view/$event[e_id]"); ?>"><button class="btn btn-primary">Tilbage til event</button></a>
</div>