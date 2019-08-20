<!-- div to offset the login fields a bit-->
<div class="row">
	<div class="col-md-4 offset-md-1">
		<!-- Title -->
		<h2><?= $title ?></h2>
		<hr>
		<!-- Form -->
		<?= validation_errors(); ?>
		<?= form_open('users/login'); ?>
			<!-- Username -->
			<div class="form-group">
				<label>Brugernavn:</label>
				<input type="text" name="username" placeholder="Brugernavn (eks: jodo)" class="form-control" />
			</div>
			<!-- Password -->
			<div class="form-group">
				<label>Kodeord:</label>
				<input type="password" name="password" placeholder="Kodeord" class="form-control" />
			</div>
			<!-- Submit button -->
			<input type="submit" value="Log Ind" class="btn btn-secondary" />
		<?= form_close(); ?>
	</div>
</div>