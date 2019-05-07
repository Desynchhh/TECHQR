
	<!-- Title -->
<h2><?= $title ?></h2>
<h5>Oversigt over alle brugere.<br/>
Klik på en brugers brugernavn for at se flere detaljer om eller redigere dem.</h5>
<hr>

	<!-- link to register new user -->
<a href="<?= base_url('users/register');?>"><button type="button" class="btn btn-warning">Opret ny bruger</button></a>

	<!-- Search field -->
<?= form_open('users/search');?>
<br>
<div>
	<label>Søg på brugernavn:</label>
	<input type="text" name="search_string" placeholder="Brugernavn" />
	<input type="submit" value="Søg" class="btn btn-secondary" />
</div>
<?= form_close(); ?>

	<!-- Table -->
<div>
	<table class="table">
		<tbody>
				<!-- Table headers -->
			<tr>
				<th><a href="<?= base_url("users/index/$per_page/$order_by/username/$offset"); ?>">Brugernavn</a></th>
				<th><a href="<?= base_url("users/index/$per_page/$order_by/permissions/$offset"); ?>" >Type</a></th>
				<th>Afdelinger</th>
				<th><a href="<?= base_url("users/index/$per_page/$order_by/email/$offset"); ?>" >Email</a></th>
			</tr>
				<!-- Table data -->
			<?php foreach($users as $user): ?>
				<tr>
					<td><a href="<?= base_url("users/view/$user[u_id]"); ?>"><?= $user['username'] ?></a></td>
					<td><?= $user['permissions'] ?></td>
					<td>
						<?php foreach($user_depts[array_search($user, $users)] as $department):?>
							<a href="<?= base_url("departments/view/$department[d_id]"); ?>" ><?= $department['name'] ?></a><br>
						<?php endforeach;?>
					</td>
					<td><?= $user['email'] ?></td>
				</tr>
			<?php endforeach;?>
		</tbody>
	</table>
</div>