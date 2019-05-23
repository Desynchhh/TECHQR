
	<!-- Title -->
<h2><?= $title ?></h2>
<h5>Oversigt over alle brugere.<br/>
Klik på en brugers brugernavn for at se flere detaljer om eller redigere dem.</h5>
<hr>

	<!-- link to register new user -->
<div>
	<a href="<?= base_url('users/register');?>"><button type="button" class="btn btn-warning">Opret ny bruger</button></a>
</div>
<br>

	<!-- Insert search field -->
	<!--
	<?= form_open("users/index/$search_string/$per_page/$order_by/$sort_by/$offset"); ?>
		<label for="search_string">Søg efter brugernavn, type, eller email:</label>
		<input type="text" id="search_string" name="search_string" value="<?= $search_string ?>">
		<input type="submit" class="btn btn-secondary" value="Søg">
	<?= form_close(); ?>
	-->

	<!-- Table -->
<div>
	<table class="table">
		<tbody>
				<!-- Table headers -->
			<tr>
				<th><a href="<?= base_url("users/index/$search_string/$per_page/$order_by/username/$offset"); ?>">Brugernavn</a></th>
				<th><a href="<?= base_url("users/index/$search_string/$per_page/$order_by/permissions/$offset"); ?>" >Type</a></th>
				<th>Afdelinger</th>
				<th><a href="<?= base_url("users/index/$search_string/$per_page/$order_by/email/$offset"); ?>" >Email</a></th>
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