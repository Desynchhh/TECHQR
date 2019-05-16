	<!-- Title -->
<h2><?= $title ?></h2>
<h5>Opret ny afdeling i systemet.</h5>
<hr>

	<!-- Form -->
<?= validation_errors(); ?>
<?= form_open('departments/create'); ?>
<div class="row">
	<div class="col-md-3 offset-md-1">
			<label>Afdelingsnavn:</label>
			<input type="text" name="name" placeholder="Afdelingsnavn"/>
			<input type="submit" value="Opret" class="btn btn-secondary" />
	</div>
</div>
<?= form_close(); ?>

	<!-- Back button -->
<div>
	<a href="<?= base_url('departments/index'); ?>"><button type="button" class="btn btn-primary">Tilbage til oversigt</button></a>
</div>
