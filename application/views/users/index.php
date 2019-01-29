<h2><?= $title ?></h2>
<hr>
<!-- link to register new user -->
<a href="<?= base_url('users/register');?>">Opret ny bruger</a>
<!-- form to search for a specific user in the table below -->
<?= form_open('users/search');?>
<div>
	<label>Søg på navn og afdeling:</label>
	<input type="text" name="search_string" />
	<input type="submit" value="Søg" class="btn btn-secondary" />
</div>
<?= form_close(); ?>

<!-- table showing all users or user that has been searched for -->
<table class="table">
	<tbody>
		<tr>
			<th>Bruger navn</th>
			<th>Roller</th>
			<th>Afdelinger</th>
			<th>Sidste handling</th>
			<th>Email</th>
			<th>Værktøj</th> <!-- consists of an "Edit" and "Delete" button separated by a pipe ( | ) -->
		</tr>
		<!-- create a <tr> with <td> children for each user in the database -->
		<?php foreach($users as $user): ?>
		<tr>
			<td><a href="<?= base_url('users/view/'.$user['user_id']); ?>"><?= $user['username'] ?></a></td>
			<td><?= $user['permissions'] ?></td>
			<td><?= $user['name'] ?></td>
			<td><!-- INSERT LAST_ACTION --></td>
			<td><?= $user['email'] ?></td>
			<td>
				<a href="<?= base_url('users/view/'.$user['user_id']); ?>">Vis</a>
			</td>
		</tr>
		<?php endforeach;?>
	</tbody>
</table>
