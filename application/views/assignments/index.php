<h2><?= $title ?></h2>
<h5>Oversigt over alle opgaver.<br/>
Klik på en opgaves navn for at se flere detaljer eller redigere den.</h5>
<hr>
<a type="button" class="btn btn-warning" href="<?= base_url('assignments/create'); ?>">Opret ny opgave</a>
<?= form_open('assignments/index'); ?>
<div>
	<label>Søg på opgavenavn, brugernavn eller notat:</label>
	<input type="text" name="search_string" />
	<input type="submit" value="Søg" class="btn btn-secondary" />
</div>
<?= form_close(); ?>

<div>
	<table class="table">
		<tbody>
			<tr>
				<th><a href="<?= base_url('assignments/index/'.$offset.'/'.$order_by.'/title'); ?>">Opgavenavn</a></th>
				<th><a href="<?= base_url('assignments/index/'.$offset.'/'.$order_by.'/notes'); ?>">Notater</a></th>
				<th><a href="<?= base_url('assignments/index/'.$offset.'/'.$order_by.'/name'); ?>">Afdeling</a></th>
				<th><a href="<?= base_url('assignments/index/'.$offset.'/'.$order_by.'/created_by'); ?>">Oprettet af</a></th>
			</tr>
			
			<!-- create <tr> with <td> children for each assignment in the DB -->
			<?php foreach($asses as $ass): ?>
				<tr>
					<td><a href="<?= base_url('assignments/view/'.$ass['id']); ?>"><?= $ass['title'] ?></a></td>
					<td><?= $ass['notes'] ?></td>
					<td><?= $ass['name'] ?></td>
					<td><?= $ass['username'] ?></td>
				</tr>
			<?php endforeach;?>
		</tbody>
	</table>

	<!-- Pagination -->
	<div class="pagination-links">
		<?= $this->pagination->create_links(); ?>
		<select name="paginate">
			<option value="5">5</option>
			<option value="10">10</option>
			<option value="25">25</option>
			<option value="50">50</option>
			<option value="100">100</option>
			<option value="NULL">Alle</option>
		</select>
	</div>
</div>
