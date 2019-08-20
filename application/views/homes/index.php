<!-- Title -->
<h2><?= $title ?></h2>
<hr>
<!-- HOME PAGE TEXT GOES HERE -->
<div class="row">
	<div class="col-md-12">
		<div class="intro">
			<h2>Velkommen til TECHQR!</h2>
			<h5>TECHQR er den førende teknologi indenfor QR-kode-baseret tourneringshåndteringssystem i Nordjylland!<br>
			Mangler De et login, kan De kontakte Kommunikation og marketing på tlf. nr. XXXX XXXX.<br>
			Hvis De allerede er i besiddelse af et login, kan De simpelt klikke på "Log ind" knappen øverst i højre hjørne på siden!
			</h5>
		</div>
	</div>
</div>

<!-- User manual button -->
<div class="row">

	<div class="col-md-4">
		<div class="user-manual">
			<h4>Download brugermanual</h4>
			<h5>Knappen åbner manualen i en ny fane.<br>Du skal derefter selv downloade den ved at højre-klikke og så klikke på "Gem som...".</h5>
			<a href="<?= base_url("homes/user_manual"); ?>" target="_blank" type="button" class="btn btn-secondary" id="user-man-btn">Åben brugermanual</a>
		</div>
	</div>

<?php if($this->session->userdata('permissions') === 'Admin'):?>
	<div class="col-md-4 offset-md-4">
		<div class="dl-user-manual">
			<?= $error ?>
			<h4>Upload brugermanual</h4>
			<h5>Vælg en fil at uploade nedenfor. Det er kun muligt at uploade .pdf filer<br>NOTE: Den gamle brugermanual vil blive overskrevet.</h5>
			<?= form_open_multipart("homes/upload_manual"); ?>
				<input type="file" name="fileUpload" id="fileUpload">
				<input type="submit" value="Upload brugermanual" class="btn btn-warning" id="user-man-btn">
			<?= form_close(); ?>
		</div>
	</div>
<?php endif;?>
</div>