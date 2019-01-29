<div class="row">
<div class="col-md-4 offset-md-1">
	<h2><?= $title ?></h2>
	<hr>
	<?= validation_errors(); ?>
	<?= form_open('users/login'); ?>
	<div class="form-group">
		<label>Brugernavn:</label>
		<input type="text" name="username" placeholder="Brugernavn (eks: lmkr)" class="form-control" />
	</div>
	<div class="form-group">
		<label>Kodeord:</label>
		<input type="password" name="password" placeholder="Kodeord" class="form-control" />
	</div>
	<input type="submit" value="Log Ind" class="btn btn-secondary" />
	<?= form_close(); ?>
</div>
</div>