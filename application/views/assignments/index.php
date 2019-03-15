<h2><?= $title ?></h2>
<h5>Oversigt over alle opgaver.<br/>
Klik på en opgaves navn for at se flere detaljer eller redigere den.</h5>
<hr>
<a type="button" class="btn btn-warning" href="<?= base_url('assignments/create'); ?>">Opret ny opgave</a>
<?= form_open('assignments/index'); ?>
<div>
	<label>Søg på opgavenavn, brugernavn eller lokation:</label>
	<input type="text" name="search_string" />
	<input type="submit" value="Søg" class="btn btn-secondary" />
</div>
<?= form_close(); ?>

<div>
	<table class="table">
		<tbody>
			<tr>
				<th>Opgavenavn</th>
				<th>Lokation</th>
				<th>Afdeling</th>
				<th>Oprettet af</th>
			</tr>
			<!-- create <tr> with <td> children for each assignment in the DB -->
			<?php foreach($asses as $ass): ?>
				<tr>
					<td><a href="<?= base_url('assignments/view/'.$ass['id']); ?>"><?= $ass['title'] ?></a></td>
					<td><?= $ass['location'] ?></td>
					<td><?= $ass['name'] ?></td>
					<td><?= $ass['username'] ?></td>
				</tr>
			<?php endforeach;?>
		</tbody>
	</table>
	<div class="btn-group mr-2" role="group" aria-label="First group">
		<?= $this->pagination->create_links(); ?>
	</div>
</div>
