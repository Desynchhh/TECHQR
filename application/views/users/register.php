	<!-- div to offset the fields a bit -->
<div class="row">
	<div class="col-md-4 offset-md-1">
			<!-- Title -->
		<h2><?= $title ?></h2>
		<hr>
			<!-- Form -->
		<?= validation_errors(); ?>
		<?= form_open('users/register', array('id' => 'createForm')); ?>
			<!-- Username -->
		<div class="form-group">
			<label>Brugernavn:</label>
			<input type="text" name="username" placeholder="Brugernavn" class="form-control" />
		</div>
			<!-- Email -->
		<div class="form-group">
			<label>Email:</label>
			<input type="email" name="email" placeholder="Email" class="form-control" />
		</div>
			<!-- Password -->
		<div class="form-group">
			<label>Kodeord:</label>
			<input type="password" name="password" placeholder="Kodeord" class="form-control" />
		</div>
			<!-- Confirm password -->
		<div class="form-group">
			<label>Bekræft kodeord:</label>
			<input type="password" name="password2" placeholder="Bekræft kodeord" class="form-control" />
		</div>
			<!-- Permissions -->
		<div class="form-group">
			<label>Brugertype:</label>
			<select name="permissions" class="form-control">
				<option selected value="Bruger">Bruger</option>
				<option value="Admin">Admin</option>
			</select>
		</div>
			<!-- Department -->
		<div class="form-group">
			<label>Tildel afdeling:</label>
			<select name="d_id" class="form-control">
				<?php foreach($departments as $department):?>
					<option value="<?= $department['d_id'] ?>"><?= $department['d_name'] ?></option>
				<?php endforeach;?>
			</select>
		</div>
			<!-- Submit button -->
		<input type="submit" id="submitBtn" value="Opret" class="btn btn-secondary" onclick="disableButton('submitBtn', 'createForm')" />
		<?= form_close(); ?>
	</div>
</div>

	<!-- Back button -->
<div>
	<a href="<?= base_url("users/index/10/asc/username/0"); ?>"><button type="button" class="btn btn-primary">Tilbage til oversigt</button></a>
</div>
