	<!-- Title -->
<h2><?= $title ?></h2>
<h5>Hvis du ændre en brugers brugernavn, skal de bruge det nye brugenavn til at logge ind med.<br/>
Efterlad kodeord felterne tomme hvis du ikke ønsker at ændre brugeres kodeord.<br>
Efterlad afdelings feltet tomt hvis du ikke ønsker at tildele brugeren endnu en afdeling.</h5>
<hr>

	<!-- Form -->
<div class="row">
	<div class="col-md-4 offset-md-1">
		<?= validation_errors(); ?>
		<?= form_open('users/edit/'.$user['u_id'], array('id' => 'editForm')); ?>
        <input type="hidden" name="u_id" value="<?= $user['u_id'] ?>"/>
        <input type="hidden" name="old_username" value="<?= $user['username'] ?>" />
        <input type="hidden" name="old_email" value="<?= $user['email'] ?>" />
			<!-- Username -->
		<div class="form-group">
			<label>Brugernavn:</label>
			<input type="text" name="username" value="<?= $user['username'] ?>" placeholder="Brugernavn" class="form-control" />
		</div>
			<!-- Email -->
		<div class="form-group">
			<label>Email:</label>
			<input type="email" name="email" value="<?= $user['email'] ?>" placeholder="Email" class="form-control" />
		</div>
			<!-- Password -->
		<div class="form-group">
			<label>Nyt kodeord:</label>
			<input type="password" name="password" placeholder="Nyt kodeord" class="form-control"/>
		</div>
			<!-- Confirm Password -->
		<div class="form-group">
			<label>Bekræft kodeord:</label>
			<input type="password" name="password2" placeholder="Bekræft kodeord" class="form-control" />
		</div>
			<!-- Permissions -->
		<div class="form-group">
			<label>Brugertype:</label>
			<select name="permissions" class="form-control">
				<option selected hidden value="<?= $user['permissions'] ?>"><?= $user['permissions'] ?></option>
				<option value="Admin">Admin</option>
				<option value="Bruger">Bruger</option>
			</select>
		</div>
			<!-- Department -->
		<div class="form-group">
			<label>Tildel afdeling:</label>
			<select name="d_id" class="form-control">
				<option selected value="0"></option>
				<?php foreach($departments as $department):?>
					<option value="<?= $department['d_id'] ?>"><?= $department['d_name'] ?></option>
				<?php endforeach;?>
			</select>
		</div>
			<!-- Submit button -->
		<input type="submit" id="submitBtn" value="Gem" class="btn btn-secondary" onclick="disableButton('submitBtn', 'editForm')" />
		<?= form_close(); ?>
	</div>
</div>

	<!-- Back button -->
<div>
	<a href="<?= base_url("users/view/$user[u_id]"); ?>"><button type="button" class="btn btn-primary">Tilbage til bruger</button></a>
</div>
