<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <script>
		// NOTE: REPLACE WITH JS SO THE PAGE DOESNT HAVE TO REFRESH, RESULTING IN POSSIBLE DATA LOSS
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
Lokations feltet er valgfri at udfylde. Alle genereret Svarmuligheds- og Point felter SKAL udfyldes</h5>
<hr>
<?= validation_errors(); ?>
<?= form_open('assignments/create/'.$optionsAmount); ?>
<div class="row">
		<div class="col-md-2">
			<label>Antal svarmuligheder:</label>
			<!-- fill the combobox with available number of answers an assignment can have -->
			<select id="answerAmount" class="form-control">
				<option selected hidden><?= $optionsAmount ?></option>
				<?php foreach(range(1, $maxOptions) as $option):?>
					<option value="<?=$option?>"><?=$option?></option>
				<?php endforeach; ?> 
			</select>
		</div>
	<div class="col-md-3">
		<div class="form-group">
			<label>Opgave titel:</label>
			<input type="text" id="title" name="title" placeholder="Opgave titel" value="<?= $assTitle ?>" class="form-control"/>
		</div>
	</div>
	<div class="col-md-3 offset-md-1">
		<div class="form-group">
			<label>Lokation:</label>
			<input type="text" id="location" name="location" placeholder="Lokation" value="<?= $assLocation ?>" class="form-control"/>
		</div>
	</div>
</div>
		<!-- Create as many input fields as the user wants -->
<div class="row">
	<?php foreach(range(1, $optionsAmount) as $option):?>
		<div class="col-md-4">
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
