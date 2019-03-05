<h2><?= $title ?></h2>
<hr>

<div class="row">

    <div class="col-md-4" style="border-right:1px solid lightgrey;">
        <?= form_open('events/open_pdf/'.url_title($event['e_name']).'/team-pdf'); ?>
            <h4>Hold PDF</h4>
            <label>Vælg hold</label>
            <select name="filename" class="form-control">
                <!-- Add all PDFs from the events team-pdf folder as a selectable option  -->
                <?php foreach($team_pdf as $pdf): ?>
                    <option value="<?= $pdf ?>"><?= $pdf ?></option>
                <?php endforeach; ?>
            </select>
            <br>
            <?php if($team_pdf): ?>
                <input type="submit" class="btn btn-warning" value="Åben PDF" />
            <?php endif; ?>
        <?= form_close(); ?>
    </div>

    <div class="col-md-4" style="border-right:1px solid lightgrey;">
        <?= form_open('events/open_pdf/'.url_title($event['e_name']).'/assignment-pdf'); ?>
            <h4>Opgave PDF</h4>
            <label>Vælg Opgave</label>
            <select name="filename" class="form-control">
                <!-- Add all PDFs from the events assignment-pdf folder as a selectable option -->
                <?php foreach($ass_pdf as $pdf):?>
                    <option value="<?= $pdf ?>"><?= $pdf ?></option>
                <?php endforeach;?>
            </select>
            <br>
            <?php if($ass_pdf): ?>
                <input type="submit" class="btn btn-warning" value="Åben PDF" />
            <?php endif; ?>
        <?= form_close(); ?>
    </div>

    <div class="col-md-4 form-group">
        <h4>Lav PDF</h4>
        <label>Lav PDF til ALLE hold</label>
        <br>
        <a class="btn btn-secondary" href="<?= base_url('events/create_team_pdf/'.$event['e_id']); ?>">Lav Hold</a>
        <br><br>
        <label>Lav PDF til ALLE opgaver</label>
        <br>
        <a class="btn btn-secondary" href="<?= base_url('events/create_ass_pdf/'.$event['e_id']); ?>">Lav Opgaver</a>
    </div>

</div>

<div>
    <a class="btn btn-primary" href="<?= base_url('events/view/'.$event['e_id']); ?>">Tilbage til event</a>
</div>