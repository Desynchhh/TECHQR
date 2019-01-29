<div class="row">
	<div class="col-md-4 offset-md-1">
		<h2><?= $title ?></h2>
		<hr>
		<?= validation_errors(); ?>
		<?= form_open('users/register'); ?>
		<div class="form-group">
			<label>Brugernavn:</label>
			<input type="text" id="input_username" name="username" placeholder="Brugernavn" class="form-control" />
		</div>
		<div class="form-group">
			<label>Email:</label>
			<input type="email" name="email" placeholder="Email" class="form-control" />
		</div>
		<div class="form-group">
			<label>Kodeord:</label>
			<input type="password" name="password" placeholder="Kodeord" class="form-control" />
		</div>
		<div class="form-group">
			<label>Bekræft kodeord:</label>
			<input type="password" name="password2" placeholder="Bekræft kodeord" class="form-control" />
		</div>
		<div>
			<label>Bruger type:</label>
			<select name="permissions">
				<option selected value="user">Bruger</option>
				<option value="admin">Admin</option>
			</select>
		</div>
		<div>
			<label>Tildel afdeling:</label>
			<select name="department_id">
				<option selected hidden value="1"></option>
				<?php foreach($departments as $department):?>
					<option value="<?= $department['id'] ?>"><?= $department['name'] ?></option>
				<?php endforeach;?>
			</select>
		</div>
		<input type="submit" value="Opret" class="btn btn-secondary" />
		<?= form_close(); ?>
	</div>
</div>

<a href="<?= base_url('users'); ?>">Tilbage til oversigt</a>
