<!-- Import JavaScript -->
<script src="<?= base_url('assets/js/assignments.js')?>"></script>

<!-- Title -->
<h2><?= $title ?></h2>
<h5>Notats feltet er valgfri at udfylde. Alle Svarmuligheds- og Point felter SKAL udfyldes</h5>
<hr>

<!-- Form -->
<?= validation_errors(); ?>
<?= form_open("assignments/edit/$ass[ass_id]/$options[optionsAmount]", array('id' => 'formEdit')); ?>
	<div class="row">
		<!-- Title box -->
		<div class="col-md-3">
			<div class="form-group">
				<label>Opgavenavn:</label>
				<input type="text" name="title" id="title" placeholder="Opgavenavn" value="<?= $ass['ass_title'] ?>" class="form-control"/>
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
				<label>Notater:</label>
				<input type="text" name="notes" placeholder="Notater" value="<?= $ass['notes'] ?>" class="form-control"/>
			</div>
		</div>
			<!-- Department dropdown -->
			<div class="col-md-3">
			<div class="form-group">
				<label>Afdeling:</label>
				<select name="d_id" id="departmentbox" class="form-control" onchange="editDropdown()">
					<option selected hidden value="<?= $ass['d_id'] ?>"><?= $ass['department'] ?></option>
					<?php foreach($departments as $department):?>
						<option value="<?= $department['d_id'] ?>"><?= $department['d_name'] ?></option>
					<?php endforeach;?>
				</select>
			</div>
		</div>
	</div>

	<!-- Create as many input fields as the assignment has answers -->
	<div class="row" id="inputFields">
		<?php $count = 1; foreach(range(1, $options['optionsAmount']) as $option):?>
			<div class="col-md-4">
				<div class="form-group">
					<label>Svarmulighed <?= $count ?>:</label>
					<!-- Check if the answer's index exists. if it does, insert it in the value attribute -->
					<input type="text" id="answer<?= $count ?>" name="answer<?= $count ?>" placeholder="Svarmulighed <?= $count ?>" class="form-control ass-input" value="<?= (isset($ass[0][$count-1]) ? $ass[0][$count-1]['answer'] : '')?>"/>
					<label>Point <?= $count ?>:</label>	<!-- subtract one from $count in the above and below input fields value attribute to get the correct index for the answer -->
					<input type="text" id="points<?= $count ?>" name="points<?= $count ?>" placeholder="Point <?= $count ?>" class="form-control ass-input" value="<?= (isset($ass[0][$count-1]) ? $ass[0][$count-1]['points'] : '')?>"/>
				</div>
				<br>
			</div>
		<?php $count++; endforeach; ?>
	</div>
	<!-- Submit button -->
	<button type="button" id="submitBtn" class="btn btn-secondary" onclick="checkFields('formEdit')" >Bekræft</button>
<?= form_close(); ?>

<!-- Back button -->
<div>
	<a href="<?= base_url("assignments/view/$ass[ass_id]"); ?>"><button type="button" class="btn btn-primary">Fortryd</button></a>
</div>

<!-- Pass variable to JS -->
<script type="text/javascript">
	const originalAnswers = <?= json_encode($ass[0]) ?>;
</script>