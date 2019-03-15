<h2><?= $title ?></h2>
<br>
<!-- <div>
    <?= form_open('departments/add/'.$department['id']); ?>
        <label>Bruger:</label>
        <select name="u_id">
            <?php foreach($users as $user): ?>
                <option value="<?= $user['u_id'] ?>"><?= $user['username'] ?></option>
            <?php endforeach;?>
        </select><br>
        <input type="submit" value="Tilføj" class="btn btn-secondary" />
    <?= form_close(); ?>
    <br>
</div>-->

<table class="table">
	<tbody>
		<tr>
			<th>Brugernavn</th>
			<th>Afdelinger</th>
            <th>Tilføj</th>
		</tr>
		<!-- create a <tr> with <td> children for each user in the database -->
		<?php foreach($users as $user): ?>
		<tr>
			<td><?= $user['username'] ?></td>
			<!-- get all departments for each individual user and show them in the table -->
			<td>[Not yet implemented]</td>
            <td>
                <?= form_open('departments/add/'.$department['id'].'/'.$user['u_id']); ?>
                    <input type="submit" class="btn btn-sm btn-secondary" value="Tilføj" />
                <?= form_close(); ?>
            </td>
		</tr>
		<?php endforeach;?>
	</tbody>
</table>

<div>
    <a type="button" class="btn btn-primary" href="<?= base_url('departments/view/'.$department['id']); ?>">Tilbage til oversigt</a>
</div>