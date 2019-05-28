    <!-- Title -->
<h2><?= $title ?></h2>
<h5>Tilføj en bruger til afdelingen.</h5>
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
                <td><?= $department['d_name'] ?></td>
                <td>
                    <?= form_open("departments/add/$department[d_id]/$per_page/0/$user[u_id]"); ?>
                        <input type="submit" class="btn btn-sm btn-secondary" value="Tilføj" />
                    <?= form_close(); ?>
                </td>
            </tr>
		<?php endforeach;?>
	</tbody>
</table>

    <!-- Back button -->
<div>
    <a href="<?= base_url("departments/view/$department[d_id]"); ?>"><button type="button" class="btn btn-primary">Tilbage til oversigt</button></a>
</div>