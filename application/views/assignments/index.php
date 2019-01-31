<h2><?= $title ?></h2>
<hr>
<a type="button" class="btn btn-primary" href="<?= base_url('assignments/create'); ?>">Opret ny opgave</a>
<?= form_open('assignments/index'); ?>
<div>
	<label>Søg på opgave navn, brugernavn og lokation:</label>
	<input type="text" name="search_string" />
	<input type="submit" value="Søg" class="btn btn-secondary" />
</div>
<?= form_close(); ?>

<div>
	<table class="table">
		<tbody>
			<tr>
				<th>Opgave navn</th>
				<th>Lokation</th>
				<th>Brugernavn</th>
			</tr>
			<!-- create <tr> with <td> children for each assignment in the DB -->
			<?php foreach($asses as $ass): ?>
				<tr>
					<td><?= $ass['title'] ?></td>
					<td><?= $ass['location'] ?></td>
					<td><?= $ass['username'] ?></td>
				</tr>
			<?php endforeach;?>
		</tbody>
	</table>
</div>
