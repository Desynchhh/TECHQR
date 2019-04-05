        <script>
            $(document).ready(function(){
                $('#answerAmount').change(function(){
                    //Selected value
                    var optionsAmount = $(this).val();
                    window.location = '<?= base_url("assignments/edit/".$ass["ass_id"]); ?>/'+optionsAmount;
                });
            });
        </script>

<h2><?= $title ?></h2>
<h5><strong>NOTE: Hvis du ændre på dit antal svar muligheder, forsvinder dine indtastede oplysninger!</strong><br>
Notats feltet er valgfri at udfylde. Alle Svarmuligheds- og Point felter SKAL udfyldes</h5>
<hr>
<?= validation_errors(); ?>
<?= form_open('assignments/edit/'.$ass['ass_id'].'/'.$options['optionsAmount']); ?>
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
			<input type="text" name="title" placeholder="Opgave titel" value="<?= $ass['ass_title'] ?>" class="form-control"/>
		</div>
	</div>
	<div class="col-md-3">
		<div class="form-group">
			<label>Notater:</label>
			<input type="text" name="notes" placeholder="Notater" value="<?= $ass['notes'] ?>" class="form-control"/>
		</div>
	</div>
	<div class="col-md-3">
		<div class="form-group">
			<label>Afdeling:</label>
			<select name="d_id" class="form-control">
				<option selected hidden value="<?= $ass['d_id'] ?>"><?= $ass['department'] ?></option>
				<?php foreach($this->session->userdata('departments') as $department):?>
					<option value="<?= $department['d_id'] ?>"><?= $department['name'] ?></option>
				<?php endforeach;?>
			</select>
		</div>
	</div>
</div>
		<!-- Create as many input fields as the assignment has answers -->
<div class="row">
	<?php $count = 1; foreach(range(1, $options['optionsAmount']) as $option):?>
		<div class="col-md-4">
			<div class="form-group">
				<label>Svarmulighed <?= $count ?>:</label>
				<!-- check if the answer's index exists. if it does, insert it in the value attribute -->
				<input type="text" name="answer<?= $count ?>" placeholder="Svarmulighed <?= $count ?>" value="<?= (isset($ass[0][$count-1]) ? $ass[0][$count-1]['answer'] : '')?>" class="form-control"/>
				<label style="padding-top:1.8%;">Point <?= $count ?>:</label>	<!-- subtract one from $count in the above and below input fields value attribute to get the correct index for the answer -->
				<input type="text" name="points<?= $count ?>" placeholder="Point <?= $count ?>" value="<?= (isset($ass[0][$count-1]) ? $ass[0][$count-1]['points'] : '')?>" class="form-control"/>
			</div>
			<br>
		</div>
	<?php $count++; endforeach; ?>
</div>
<input type="submit" value="Gem" class="btn btn-secondary"/>
<?= form_close(); ?>

<div>
	<a type="button" class="btn btn-primary" href="<?= base_url('assignments/view/'.$ass['ass_id']); ?>">Fortryd</a>
</div>
