<h2><?= $title ?></h2>
<hr>

<div class="row">
    <div class="col-md-4" style="border-right:1px solid lightgrey;">
        <h4>Hold PDF</h4>
        <label>Vælg hold</label>
        <select class="form-control">
            <option selected class="form-control">Hold.pdf</option>
        </select>
        <br>
        <a class="btn btn-warning" href="">Åben PDF</a>
    </div>
    <div class="col-md-4" style="border-right:1px solid lightgrey;">
        <h4>Opgave PDF</h4>
        <label>Vælg Opgave</label>
        <select class="form-control">
            <option selected>Opgave.pdf</option>
        </select>
        <br>
        <a class="btn btn-warning" href="">Åben PDF</a>
    </div>
    <div class="col-md-4 form-group">
        <h4>Lav PDF</h4>
        <label class=>Lav PDF for ALLE hold</label>
        <br>
        <a class="btn btn-secondary" href="<?= base_url('events/create_team_pdf/'.$event['e_id']); ?>">Lav Hold</a>
        <br><br>
        <label>Lav PDF for ALLE opgaver</label>
        <br>
        <a class="btn btn-secondary" href="<?= base_url('events/create_ass_pdf/'.$event['e_id']); ?>">Lav Opgaver</a>
    </div>
</div>