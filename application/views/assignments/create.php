	<!-- Import JavaScript -->
<script src="<?= base_url('assets/js/assignments.js')?>"></script>

<!-- Title -->
<h2><?= $title ?></h2>
<h5>Notatfeltet er valgfrit at udfylde. Alle svarmuligheds- og pointfelter SKAL udfyldes.</h5>
<hr>

	<!-- Form -->
<?= validation_errors(); ?>
<?= form_open("assignments/create/$options[optionsAmount]", array('id' => 'formCreate')); ?>
	<div class="row">
			<!-- Title box -->
		<div class="col-md-3">
			<div class="form-group">
				<label>Opgavetitel:</label>
				<input type="text" id="title" name="title" placeholder="Opgavetitel" class="form-control"/>
			</div>
		</div>
			<!-- Amount of answers dropdown -->
		<div class="col-md-3">
			<div class="form-group">
				<label>Antal svarmuligheder:</label>
					<!-- Fill the combobox with available number of answers an assignment can have -->
				<select id="answerAmount" name="answerAmount" class="form-control" onchange="changeFields()">
					<option selected hidden><?= $options['optionsAmount'] ?></option>
					<option value="1">1</option>
					<option value="2">2</option>
					<option value="3">3</option>
					<option value="4">4</option>
					<option value="5">5</option>
					<option value="6">6</option>
					<option value="7">7</option>
					<option value="8">8</option>
					<option value="9">9</option>
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
	<div class="row" id="inputFields">
		<div class="col-md-4">	<!-- Fit 3 answers per row -->
			<div class="form-group">
				<label>Svarmulighed 1:</label>
				<input type="text" id="answer1" name="answer1" placeholder="Svarmulighed 1" class="form-control ass-input">
				<label>Point 1:</label>
				<input type="text" id="points1" name="points1" placeholder="Point 1" class="form-control ass-input">
			</div>
			<br>
		</div>
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
	<button onclick="checkFields('formCreate')" type="button" class="btn btn-secondary">Opret</button>
<?= form_close(); ?>

	<!-- Back button -->
<div>
	<a href="<?= base_url("assignments/index/10/asc/title"); ?>"><button type="button" class="btn btn-primary">Tilbage til oversigt</button></a>
</div>

<script type="text/javascript">
    //Convert PHP arrays to JSON objects, so JS can use it
	const events = <?= json_encode($events) ?>;
</script>