<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <script>
		// NOTE: REPLACE WITH JS SO THE PAGE DOESNT HAVE TO REFRESH, RESULTING IN POSSIBLE DATA LOSS
            $(document).ready(function(){
            $('#answerAmount').change(function(){
                //Selected value
				var optionsAmount = $(this).val();
				var title = $('#title').val();
				var location = $('#location').val();
                window.location = '<?= base_url("assignments/create/"); ?>'+optionsAmount;
            });
        });
        </script>

<h2><?= $title ?></h2>
<hr>
<?= form_open('assignments/create'); ?>
<div class="row">
	<div class="col-md-3 offset-md-1">
		<div class="form-group">
			<label>Opgave titel:</label>
			<input type="text" id="title" name="title" placeholder="Opgave titel" value="<?= $assTitle ?>" class="form-control"/>
		</div>
		<div class="form-group">
			<label>Lokation:</label>
			<input type="text" id="location" name="location" placeholder="Lokation" value="<?= $assLocation ?>" class="form-control"/>
		</div>
		<div>
			<label>Antal svarmuligheder:</label>
			<select id="answerAmount">
				<option selected hidden><?= $optionsAmount ?></option>
				<?php foreach(range(1, $maxOptions) as $option):?>
					<option value="<?=$option?>"><?=$option?></option>
				<?php endforeach; ?> 
			</select>
		</div>
		<?php foreach(range(1, $optionsAmount) as $option):?>
		<div class="form-group">
			<label>Svarmulighed <?= $option ?>:</label>
			<input type="text" name="answer<?= $option ?>" placeholder="Svarmulighed <?= $option ?>" class="form-control"/>
			<br/>
			<label>Point <?= $option ?>:</label>
			<input type="text" name="points<?= $option ?>" placeholder="Point <?= $option ?>" class="form-control"/>
		</div>
		<?php endforeach; ?>
		<input type="submit" value="Opret" class="btn btn-secondary"/>
	</div>
</div>
<?= form_close(); ?>

<a href="<?= base_url('assignments'); ?>">Tilbage til oversigt</a>
