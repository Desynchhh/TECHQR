    <!-- Title -->
<h2><?= $title ?></h2>
<br>

    <!-- Table -->
<table class="table">
	<tbody>
            <!-- Table headers -->
		<tr>
			<th>Brugernavn</th>
			<th>Afdelinger</th>
            <th>Tilføj</th>
		</tr>
            <!-- Table data -->
		<?php foreach($users as $user): ?>
            <tr>
                <td><?= $user['username'] ?></td>
                    <!-- get all departments for each individual user and show them in the table -->
                <td><?= $department['name'] ?></td>
                <td>
                    <?= form_open("departments/add/$department[id]/$per_page/0/$user[u_id]"); ?>
                        <input type="submit" class="btn btn-sm btn-secondary" value="Tilføj" />
                    <?= form_close(); ?>
                </td>
            </tr>
		<?php endforeach;?>
	</tbody>
</table>

    <!-- Back button -->
<div>
    <a href="<?= base_url("departments/view/$department[id]"); ?>"><button type="button" class="btn btn-primary">Tilbage til oversigt</button></a>
</div>

    <!-- Temporary warning until issue is solved -->
<h2><b>
    NOTE: Du kan endnu ikke selv vælge hvor mange brugere du vil se per side.
    <br>
    Du vil ødelægge siden hvis du forsøger på dette.
</b></h2>