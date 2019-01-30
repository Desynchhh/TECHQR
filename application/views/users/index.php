<h2><?= $title ?></h2>
<h5>Oversigt over alle brugere.<br/>Klik på en brugers brugernavn for at se flere detaljer eller redigere dem.</h5>
<hr>
<!-- link to register new user -->
<a type="button" class="btn btn-primary" href="<?= base_url('users/register');?>">Opret ny bruger</a>
<!-- form to search for a specific user in the table below -->
<?= form_open('users/search');?>
<div>
	<label>Søg på brugernavn og afdeling:</label>
	<input type="text" name="search_string" />
	<input type="submit" value="Søg" class="btn btn-secondary" />
</div>
<?= form_close(); ?>

<!-- table showing all users or user that has been searched for -->
<table class="table">
	<tbody>
		<tr>
			<th>Brugernavn</th>
			<th>Roller</th>
			<th>Afdelinger</th>
			<th>Sidste handling</th>
			<th>Email</th>
		</tr>
		<!-- create a <tr> with <td> children for each user in the database -->
		<?php foreach($users as $user): ?>
		<tr>
			<td><a href="<?= base_url('users/view/'.$user['u_id']); ?>"><?= $user['username'] ?></a></td>
			<td><?= $user['permissions'] ?></td>
			<td>
				<?php foreach($departments as $department):?>
					<?= $department ?>
				<?php endforeach;?>
			</td>
			<td><!-- INSERT LAST_ACTION --></td>
			<td><?= $user['email'] ?></td>
		</tr>
		<?php endforeach;?>
	</tbody>
</table>