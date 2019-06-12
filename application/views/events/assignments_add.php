    <!-- Title -->
<h2><?= $title ?></h2>
<h5>Tilføj opgaver til eventet.<br>
NOTE: Man kan kun tilføje opgaver i samme afdeling som eventet.</h5>

<br>

    <!-- Create assignment button -->
<div>
    <a href="<?= base_url("assignments/create/"); ?>" target="_blank"><button type="button" class="btn btn-warning">Opret ny opgave</button></a>
</div>

<br>

    <!-- Back button -->
<div>
    <a href="<?= base_url("events/assignments/view/$e_id/10/asc/title"); ?>"><button type="button" class="btn btn-primary">Tilbage til oversigt</button></a>
</div>

<!-- Search field -->
<?= form_open(); ?>
    <label for="search_string">Søg efter opgavenavn eller notater:</label>
    <input type="text" id="search_string" name="search_string" placeholder="Søg" value="<?= (isset($search_string)) ? $search_string : ''; ?>">
    <input type="submit" class="btn btn-secondary" value="Søg">
<?= form_close(); ?>
              
<br>

    <!-- Table -->
<div>
    <table class="table">
        <tbody>
                <!-- Table headers -->
            <tr>
                <th><a href="<?= base_url("events/assignments/add/$e_id/$per_page/$order_by/title/$offset"); ?>" >Opgavenavn</a></th>
                <th>Afdeling</th>
                <th><a href="<?= base_url("events/assignments/add/$e_id/$per_page/$order_by/notes/$offset"); ?>" >Notater</a></th>
                <th>Tilføj</th>
            </tr>
                <!-- Table data -->
            <?php foreach($asses as $ass):?>
            <tr>
                <td><a target="_blank" href="<?= base_url("assignments/view/$ass[ass_id]"); ?>" ><?= $ass['title'] ?></a></td>
                <td><?= $ass['d_name'] ?></td>
                <td><?= $ass['notes'] ?></td>
                <td>
                    <a href="<?= base_url("events/add_ass/$e_id/$ass[ass_id]"); ?>"><button type="button" class="btn btn-sm btn-secondary">Tilføj</button></a>
                </td>
            </tr>
            <?php endforeach;?>
        </tbody>
    </table>

    <!-- Back button -->
<div>
    <a href="<?= base_url("events/assignments/view/$e_id/10/asc/title"); ?>"><button type="button" class="btn btn-primary">Tilbage til oversigt</button></a>
</div>