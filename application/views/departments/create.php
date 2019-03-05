<h2><?= $title ?></h2>
<hr>
<?= validation_errors(); ?>
<?= form_open('departments/create'); ?>
<div class="row">
	<div class="col-md-3 offset-md-1">
		<!-- <div class="from-group"> -->
			<label>Afdelingsnavn:</label>
			<input type="text" name="name" placeholder="Afdelingsnavn"/> <!-- class="form-control" -->
			<input type="submit" value="Opret" class="btn btn-secondary" />
		<!-- </div> -->
	</div>
</div>
<?= form_close(); ?>
<a type="button" class="btn btn-primary" href="<?= base_url('departments'); ?>">Tilbage til oversigt</a>
