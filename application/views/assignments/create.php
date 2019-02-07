        <script>
            $(document).ready(function(){
				$('#answerAmount').change(function(){
					//Selected value
					var optionsAmount = $(this).val();
					window.location = '<?= base_url("assignments/create/"); ?>'+optionsAmount;
				});
			});
        </script>

<h2><?= $title ?></h2>
<h5><strong>NOTE: Hvis du ændre på dit antal svar muligheder, forsvinder dine indtastede oplysninger!</strong><br>
Lokations feltet er valgfri at udfylde. Alle Svarmuligheds- og Point felter SKAL udfyldes</h5>
<hr>
<?= validation_errors(); ?>
<?= form_open('assignments/create/'.$options['optionsAmount']); ?>
<div class="row">
		<div class="col-md-3">
			<label>Antal svarmuligheder:</label>
			<!-- fill the combobox with available number of answers an assignment can have -->
			<select id="answerAmount" class="form-control">
				<option selected hidden><?= $options['optionsAmount'] ?></option>
				<?php foreach(range(1, $options['maxOptions']) as $option):?>
					<option value="<?=$option?>"><?=$option?></option>
				<?php endforeach; ?> 
			</select>
		</div>
	<div class="col-md-3">
		<div class="form-group">
			<label>Opgave titel:</label>
			<input type="text" id="title" name="title" placeholder="Opgave titel" class="form-control"/>
		</div>
	</div>
	<div class="col-md-3">
		<div class="form-group">
			<label>Lokation:</label>
			<input type="text" id="location" name="location" placeholder="Lokation" class="form-control"/>
		</div>
	</div>
	<div class="col-md-3">
		<div class="form-group">
			<label>Afdeling:</label>
			<select name="d_id" class="form-control">
				<option selected hidden value="<?= $this->session->userdata['departments'][0]['d_id'] ?>"><?= $this->session->userdata['departments'][0]['name'] ?></option>
				<?php foreach($this->session->userdata('departments') as $department):?>
					<option value="<?= $department['d_id'] ?>"><?= $department['name'] ?></option>
				<?php endforeach;?>
			</select>
		</div>
	</div>
</div>
		<!-- Create as many input fields as the user wants -->
<div class="row">
	<?php foreach(range(1, $options['optionsAmount']) as $option):?>
		<div class="col-md-4">	<!-- Fit 3 answers per row -->
			<div class="form-group">
				<label>Svarmulighed <?= $option ?>:</label>
				<input type="text" name="answer<?= $option ?>" placeholder="Svarmulighed <?= $option ?>" class="form-control"/>
				<label style="padding-top:1.8%;">Point <?= $option ?>:</label>
				<input type="text" name="points<?= $option ?>" placeholder="Point <?= $option ?>" class="form-control"/>
			</div>
			<br>
		</div>
	<?php endforeach; ?>
</div>
<input type="submit" value="Opret" class="btn btn-secondary"/>
<?= form_close(); ?>

<a type="button" class="btn btn-primary" href="<?= base_url('assignments'); ?>">Tilbage til oversigt</a>
