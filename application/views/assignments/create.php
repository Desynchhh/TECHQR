	<!-- JS -->
<script>
	$(document).ready(function(){
		$('#answerAmount').change(function(){
			//Selected value
			var optionsAmount = $(this).val();
			window.location = '<?= base_url("assignments/create/"); ?>'+optionsAmount;
		});
	});
</script>

	<!-- Title -->
<h2><?= $title ?></h2>
<h5><strong>NOTE: Hvis du ændre på dit antal svar muligheder, forsvinder dine indtastede oplysninger!</strong><br>
Notats feltet er valgfri at udfylde. Alle Svarmuligheds- og Point felter SKAL udfyldes</h5>
<hr>

	<!-- Form -->
<?= validation_errors(); ?>
<?= form_open("assignments/create/$options[optionsAmount]", array('id' => 'formCreate')); ?>
	<div class="row">
			<!-- Title box -->
		<div class="col-md-3">
			<div class="form-group">
				<label>Opgavetitel:</label>
				<input type="text" id="title" name="title" placeholder="Opgave titel" class="form-control"/>
			</div>
		</div>
			<!-- Amount of answers dropdown -->
		<div class="col-md-3">
			<div class="form-group">
				<label>Antal svarmuligheder:</label>
					<!-- Fill the combobox with available number of answers an assignment can have -->
				<select id="answerAmount" class="form-control">
					<option selected hidden><?= $options['optionsAmount'] ?></option>
					<?php foreach(range(1, $options['maxOptions']) as $option):?>
						<option value="<?=$option?>"><?=$option?></option>
					<?php endforeach; ?> 
				</select>
			</div>
		</div>
			<!-- Note field box -->
		<div class="col-md-3">
			<div class="form-group">
				<label>Notater (Valgfri):</label>
				<input type="text" id="notes" name="notes" placeholder="Notater" class="form-control"/>
			</div>
		</div>
			<!-- Department dropdown -->
		<div class="col-md-3">
			<div class="form-group">
				<label>Afdeling:</label>
				<select name="d_id" id="departmentbox" class="form-control" onchange="editDropdown()">
					<option selected hidden value="<?= $departments[0]['d_id'] ?>"><?= $departments[0]['d_name'] ?></option>
					<?php foreach($departments as $department):?>
						<option value="<?= $department['d_id'] ?>"><?= $department['d_name'] ?></option>
					<?php endforeach;?>
				</select>
			</div>
		</div>
	</div>
		
		<!-- Create as many input fields as the user requests -->
	<div class="row">
		<?php foreach(range(1, $options['optionsAmount']) as $option):?>
			<div class="col-md-4">	<!-- Fit 3 answers per row -->
				<div class="form-group">
					<label>Svarmulighed <?= $option ?>:</label>
					<input type="text" name="answer<?= $option ?>" id="answer<?= $option ?>" placeholder="Svarmulighed <?= $option ?>" class="form-control"/>
					<label style="padding-top:1.8%;">Point <?= $option ?>:</label>
					<input type="text" name="points<?= $option ?>" id="points<?= $option ?>" placeholder="Point <?= $option ?>" class="form-control"/>
				</div>
				<br>
			</div>
		<?php endforeach; ?>
	</div>

	<!-- Dropdown for immediatly adding the assignment to an event -->
	<?php if(isset($events)): ?>
		<div class="row">
			<div class="col-md-4 form-group">
			<label for="eventbox">Tilføj til event (Valgfri):</label>
				<select name="eventbox" id="eventbox" class="form-control">
					<option selected value=""></option>
					<?php foreach($events as $event): ?>
						<?php if($departments[0]['d_id'] == $event['d_id']): ?>
							<option value="<?= $event['e_id'] ?>"><?= $event['e_name'] ?></option>
						<?php endif;?>
					<?php endforeach; ?>
				</select>
			</div>
		</div>
	<?php endif;?>
		<!-- Submit buttom -->
	<button onclick="checkFields(<?= $options['optionsAmount'] ?>, 'formCreate')" type="button" class="btn btn-secondary">Opret</button>
<?= form_close(); ?>

	<!-- Back button -->
<div>
	<a href="<?= base_url("assignments/index/10/asc/title"); ?>"><button type="button" class="btn btn-primary">Tilbage til oversigt</button></a>
</div>
<script type="text/javascript">
    //Convert PHP arrays to JSON objects, so JS can use it
	var events = <?= json_encode($events) ?>;
</script>
<!-- <script type="text/javascript" src="<?= ''//base_url('assets/js/create-assignment-event-dropdown.js')?>"></script> -->