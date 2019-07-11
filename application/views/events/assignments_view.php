    <!-- Title -->
<h2><?= $title ?></h2>
<h5>Se alle opgaver i eventet eller fjern dem herfra.</h5>

<br>
    <!-- Add assignments button -->
<div>
    <a href="<?= base_url("events/assignments/add/$e_id/10/asc/title"); ?>"><button type="button" class="btn btn-warning">Tilføj opgave</button></a>
</div>

<br>
    <!-- Back button -->
<div>
    <a href="<?= base_url("events/view/$e_id"); ?>"><button type="button" class="btn btn-primary">Tilbage til event</button></a>
</div>

<br>

<!-- Search bar -->
<?= form_open("events/assignments/view/$e_id/$per_page/$order_by/$sort_by/0"); ?>
    <label for="search_string">Søg efter opgavenavn eller notater:</label>
    <input type="text" id="search_string" name="search_string" placeholder="Søg" value="<?= ((isset($search_string)) ? $search_string : '') ?>">
    <input type="submit" value="Søg" class="btn btn-secondary">
<?= form_close(); ?>

    <!-- Table -->
<div>
    <table class="table">
        <tbody>
                <!-- Table headers -->
            <tr>
                <th><a href="<?= base_url("events/assignments/view/$e_id/$per_page/$order_by/title/$offset"); ?>" >Opgavenavn</a></th>
                <th>Afdeling</th>
                <th><a href="<?= base_url("events/assignments/view/$e_id/$per_page/$order_by/notes/$offset"); ?>" >Notater</a></th>
                <th>Fjern</th>
            </tr>
                <!-- Table data -->
            <?php foreach($asses as $ass):?>
                <tr>
                    <td><a href="<?= base_url("assignments/view/$ass[ass_id]"); ?>"><?= $ass['title'] ?></a></td>
                    <td><?= $ass['name'] ?></td>
                    <td><?= $ass['notes'] ?></td>
                    <td>
                        <a href="<?= base_url("events/remove_ass/$e_id/$ass[ass_id]"); ?>">
                            <button type="button" id="assRm<?= $ass['ass_id'] ?>" class="btn btn-sm btn-danger" onclick="disableButton('assRm<?= $ass['ass_id'] ?>')">Fjern</button>
                        </a>
                    </td>
                </tr>
            <?php endforeach;?>
        </tbody>
    </table>
</div>

    <!-- Back button -->
<div>
    <a href="<?= base_url("events/view/$e_id"); ?>"><button type="button" class="btn btn-primary">Tilbage til event</button></a>
</div>