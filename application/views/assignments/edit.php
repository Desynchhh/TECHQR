<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <script>
            $(document).ready(function(){
                $('#answerAmount').change(function(){
                    //Selected value
                    var optionsAmount = $(this).val();
                    window.location = '<?= base_url("assignments/edit/"); ?>'+optionsAmount;
                });
            });
        </script>

<h2><?= $title ?></h2>
<h5><strong>NOTE: Hvis du ændre på dit antal svar muligheder, forsvinder dine indtastede oplysninger!</strong><br>
Lokations feltet er valgfri at udfylde. Alle Svarmuligheds- og Point felter SKAL udfyldes</h5>
<hr>
<?= validation_errors(); ?>
<?= form_open('assignments/edit/'.$ass['ass_id']); ?>
<div class="row">
		<!--<div class="col-md-2">
			<label>Antal svarmuligheder:</label>
			<!-- fill the combobox with available number of answers an assignment can have 
			<select id="answerAmount" class="form-control">
				<option selected hidden><?= count($ass[1]) ?></option>
				<?php foreach(range(1, $options['maxOptions']) as $option):?>
					<option value="<?=$option?>"><?=$option?></option>
				<?php endforeach; ?> 
			</select>
		</div>-->
	<div class="col-md-3 offset-md-2">
		<div class="form-group">
			<label>Opgave titel:</label>
			<input type="text" name="title" placeholder="Opgave titel" value="<?= $ass['ass_title'] ?>" class="form-control"/>
		</div>
	</div>
	<div class="col-md-3 offset-md-1">
		<div class="form-group">
			<label>Lokation:</label>
			<input type="text" name="location" placeholder="Lokation" value="<?= $ass['location'] ?>" class="form-control"/>
		</div>
	</div>
</div>
		<!-- Create as many input fields as the assignment has answers -->
<div class="row">
	<?php $count = 1; foreach($ass[1] as $answer):?>
		<div class="col-md-4">
			<div class="form-group">
				<label>Svarmulighed <?= $count ?>:</label>
				<input type="text" name="answer<?= $count ?>" placeholder="Svarmulighed <?= $count ?>" value="<?= $answer['answer'] ?>" class="form-control"/>
				<label style="padding-top:1.8%;">Point <?= $count ?>:</label>
				<input type="text" name="points<?= $count ?>" placeholder="Point <?= $count ?>" value="<?= $answer['points'] ?>" class="form-control"/>
			</div>
			<br>
		</div>
	<?php $count = $count+1; endforeach; ?>
</div>
<input type="submit" value="Gem" class="btn btn-secondary"/>
<?= form_close(); ?>

<div>
	<a type="button" class="btn btn-primary" href="<?= base_url('assignments/view/'.$ass['ass_id']); ?>">Fortryd</a>
</div>
