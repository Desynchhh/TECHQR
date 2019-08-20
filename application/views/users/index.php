<!-- Title -->
<h2><?= $title ?></h2>
<h5>Oversigt over alle brugere.<br/>
Klik på en brugers brugernavn for at se flere detaljer om eller redigere dem.</h5>
<hr>

<!-- Link to register new user -->
<div>
	<a href="<?= base_url('users/register');?>"><button type="button" class="btn btn-warning">Opret ny bruger</button></a>
</div>

<br>

	<!-- Search field -->
	<?= form_open("users/index/$per_page/$order_by/$sort_by/0"); ?>
		<label for="search_string">Søg efter brugernavn, type eller email:</label>
		<input type="text" id="search_string" name="search_string" placeholder="Søg" value="<?= (isset($search_string)) ? $search_string : ''; ?>">
		<input type="submit" class="btn btn-secondary" value="Søg">
	<?= form_close(); ?>

	<!-- Table -->
<div>
	<table class="table">
		<tbody>
			<!-- Table headers -->
			<tr>
				<?php foreach($fields as $header => $data): ?>
					<th>
						<a href="<?= base_url("users/index/$per_page/". (($order_by == 'asc' && $sort_by == $data) ? 'desc' : 'asc' ) ."/$data/$offset"); ?>">
							<?= $header ?>
						</a>
					</th>
				<?php endforeach;?>
				<th>Afdeling</th>
			</tr>
			<!-- Table data -->
			<?php foreach($users as $user): ?>
				<tr>
					<?php foreach($fields as $header => $data): ?>
						<?php if($data == 'username'): ?>
							<td>
								<a href="<?= base_url("users/view/$user[u_id]") ?>">
									<?= $user[$data] ?>	
								</a>
							</td>
						<?php else: ?>
							<td><?= $user[$data] ?></td>
						<?php endif; ?>
					<?php endforeach;?>
					<td>
						<?php foreach($user_depts[array_search($user, $users)] as $department):?>
							<a href="<?= base_url("departments/view/$department[d_id]"); ?>" ><?= $department['d_name'] ?></a><br>
						<?php endforeach;?>
					</td>
				</tr>
			<?php endforeach;?>
		</tbody>
	</table>
</div>