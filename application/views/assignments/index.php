	<!-- Title -->
<h2><?= $title ?></h2>
<h5>Oversigt over alle opgaver.<br/>
Klik på en opgaves navn for at se flere detaljer eller redigere den.</h5>
<hr>

	<!-- Create button -->
<a href="<?= base_url('assignments/create'); ?>"><button type="button" class="btn btn-warning">Opret ny opgave</button></a>

	<!-- Search field -->
<?= form_open('assignments/index'); ?>
<div>
	<label>Søg på opgavenavn, brugernavn eller notat:</label>
	<input type="text" name="search_string" />
	<input type="submit" value="Søg" class="btn btn-secondary" />
</div>
<?= form_close(); ?>

	<!-- Table -->
<div>
	<table class="table">
		<tbody>
				<!-- Table headers -->
			<tr>
				<th><a href="<?= base_url("assignments/index/$per_page/$order_by/title/$offset"); ?>">Opgavenavn</a></th>
				<th><a href="<?= base_url("assignments/index/$per_page/$order_by/notes/$offset"); ?>">Notater</a></th>
				<th><a href="<?= base_url("assignments/index/$per_page/$order_by/name/$offset"); ?>">Afdeling</a></th>
				<th><a href="<?= base_url("assignments/index/$per_page/$order_by/created_by/$offset"); ?>">Oprettet af</a></th>
			</tr>
			<?php foreach($asses as $ass): ?>
					<!-- Table data -->
				<tr>
					<td><a href="<?= base_url('assignments/view/'.$ass['id']); ?>"><?= $ass['title'] ?></a></td>
					<td><?= $ass['notes'] ?></td>
					<td><?= $ass['name'] ?></td>
					<td><?= $ass['username'] ?></td>
				</tr>
			<?php endforeach;?>
		</tbody>
	</table>
</div>
