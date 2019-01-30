<h2><?= $title ?></h2>
<h5>Hvis du ændre en brugers brugernavn, skal de bruge det nye brugenavn til at logge ind med.<br/>
Efterlad kodeord felterne tomme hvis du ikke ønsker at ændre brugeres kodeord.</h5>
<hr>
<div class="row">
	<div class="col-md-4 offset-md-1">
		<?= validation_errors(); ?>
		<?= form_open('users/edit/'.$user['user_id']); ?>
        <input type="hidden" name="id" value="<?= $user['user_id'] ?>"/>
        <input type="hidden" name="old_username" value="<?= $user['username'] ?>" />
        <input type="hidden" name="old_email" value="<?= $user['email'] ?>" />
		<div class="form-group">
			<label>Brugernavn:</label>
			<input type="text" name="username" value="<?= $user['username'] ?>" placeholder="Brugernavn" class="form-control" />
		</div>
		<div class="form-group">
			<label>Email:</label>
			<input type="email" name="email" value="<?= $user['email'] ?>" placeholder="Email" class="form-control" />
		</div>
		<div class="form-group">
			<label>Nyt kodeord:</label>
			<input type="password" name="password" placeholder="Nyt kodeord" class="form-control"/>
		</div>
		<div class="form-group">
			<label>Bekræft kodeord:</label>
			<input type="password" name="password2" placeholder="Bekræft kodeord" class="form-control" />
		</div>
		<div>
			<label>Bruger type:</label>
			<select name="permissions">
				<?php if($user['permissions'] == 'Admin'):?>
				<option selected value="Admin">Admin</option>
				<option value="Bruger">Bruger</option>
				<?php else:?>
				<option selected value="Bruger">Bruger</option>
				<option value="Admin">Admin</option>
				<?php endif; ?>
			</select>
		</div>
		<div>
			<label>Tildel afdeling:</label>
			<select name="department_id">
				<option selected hidden value="<?= $user['d_id'] ?>"><?= $user['name'] ?></option>
				<?php foreach($departments as $department):?>
					<option value="<?= $department['id'] ?>"><?= $department['name'] ?></option>
				<?php endforeach;?>
			</select>
		</div>
		<input type="submit" value="Bekræft" class="btn btn-secondary" />
		<?= form_close(); ?>
	</div>
</div>

<a type="button" class="btn btn-primary" href="<?= base_url('users/view/'.$user['user_id']); ?>">Tilbage til bruger</a>
