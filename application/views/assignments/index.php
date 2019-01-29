<h2><?= $title ?></h2>
<hr>
<a href="<?= base_url('assignments/create'); ?>">Opret ny opgave</a>
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
				<th>ID</th>
				<th>Brugernavn</th>
				<th>Opgave navn</th>
				<th>Lokation</th>
				<th>Værktøj</th>
			</tr>
			<!-- create <tr> with <td> children for each assignment in the DB -->
		</tbody>
	</table>
</div>
