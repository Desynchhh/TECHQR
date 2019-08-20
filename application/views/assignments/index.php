<!-- Title -->
<h2><?= $title ?></h2>
<h5>Oversigt over alle opgaver.<br/>
		Klik på en opgaves navn for at se flere detaljer eller redigere den.</h5>
<hr>

<!-- Create button -->
<div>
	<a href="<?= base_url('assignments/create'); ?>"><button type="button" class="btn btn-warning">Opret ny opgave</button></a>
</div>
<br>

<!-- Insert search field -->
<?= form_open("assignments/index/$per_page/$order_by/$sort_by/0"); ?>
	<label for="search_string">Søg efter opgavenavn, notater, afdeling, eller brugernavn:</label>
	<input type="text" id="search_string" name="search_string" placeholder="Søg" value="<?= (isset($search_string)) ? $search_string: ''; ?>">
	<input type="submit" value="Søg" class="btn btn-secondary">
<?= form_close(); ?>

<!-- Table -->
<div>
	<table class="table">
		<tbody>
			<!-- Table headers -->
			<tr>
				<?php foreach($fields as $header => $data): ?>
					<th>
						<a href="<?=base_url("assignments/index/$per_page/". (($order_by == 'asc' && $sort_by == $data) ? 'desc' : 'asc' ) ."/$data/$offset"); ?>">
							<?= $header ?>
						</a>
					</th>
				<?php endforeach; ?></tr>
			<?php foreach($asses as $ass): ?>
				<!-- Table data -->
				<tr>
					<?php foreach($fields as $header => $data): ?>
						<?php if($data == 'title'): ?>
							<td><a href="<?= base_url("assignments/view/$ass[id]"); ?>"><?= $ass[$data] ?></a></td>
						<?php else:?>
							<td><?= $ass[$data] ?></td>
						<?php endif;?>
					<?php endforeach; ?>
				</tr>
			<?php endforeach;?>
		</tbody>
	</table>
</div>
