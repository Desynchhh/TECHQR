	<!-- Title -->
<h2><?= $title ?></h2>
<h5>Se alle brugere i afdelingen, fjern dem herfra, eller slet afdelingen.</h5>
<hr>
<div class="row">
	<div class="col-md-6">
	<!-- Department Info -->
	<h3>Info</h3>
		<div>
			<dl class="dl-horizontal">
				<dt>Afdelingsnavn:</dt>
				<dd class="department-dd"><?= $department['d_name'] ?><br></dd>
				<dt>Medlemmer:</dt>
				<dd class="department-dd"><?= $member_count ?><br></dd>
				<dt>Oprettet:</dt>
				<dd class="department-dd"><?= $department['created_at'] ?><br></dd>
			</dl>
		</div>

			<!-- Buttons -->
		<div class="row">
				<!-- Add user to department -->
			<div class="md-col-1 view-btn">
			<a href="<?= base_url('departments/add/'.$department['d_id']); ?>"><button type="button" class="btn btn-warning">Tilføj bruger</button></a>
			</div>
				<!-- Delete department -->
			<div class="md-col-1 view-btn">
				<button type="button" class="btn btn-danger" onclick="submitHidden('input', 'inputForm', 'afdelingen')">Slet afdeling</button>
					
					<!-- Hidden form to submit name when deleting the department -->
				<?= form_open('departments/delete/'.$department['d_id'], array('id' => 'inputForm')); ?>
					<input type="hidden" name="input" id="input" value="" class="btn btn-danger" />
				<?= form_close(); ?>
				
			</div>
		</div>
			<!-- Back button -->
		<div class="row">
			<div class="view-btn">
				<a href="<?= base_url('departments/index'); ?>"><button type="button" class="btn btn-primary">Tilbage til oversigt</button></a>
			</div>
		</div>
	</div>

	<div class="col-md-6">
	<!-- Table of users -->
		<div>
		<h3>Medlemmer</h3>
			<table class="table">
				<tbody>
						<!-- Table headers -->
					<tr>
						<?php foreach($fields as $header => $data): ?>
							<th><?= $header ?></th>
						<?php endforeach; ?>
						<th>Værktøj</th>
					</tr>
						<!-- Table data -->
					<?php foreach($users as $user):?>
						<tr>
							<?php foreach($fields as $header => $data): ?>
								<?php if($data == 'username'): ?>
									<td><a href="<?= base_url("users/view/$user[u_id]"); ?>"><?= $user[$data] ?></a></td>
								<?php else:?>
									<td><?= $user[$data] ?></td>
								<?php endif;?>
							<?php endforeach; ?>
							<td>
								<a href="<?= base_url("departments/remove/$user[u_id]/$department[d_id]"); ?>">
									<button type="button" id="btnRm<?=$user['u_id']?>" class="btn btn-sm btn-danger" onclick="disableButton('btnRm<?= $user['u_id'] ?>')">Fjern</button>
								</a>
							</td>
						</tr>
					<?php endforeach;?>
				</tbody>
			</table>

				<!-- Pagination -->
			<div class="pagination-links">
				<?= $this->pagination->create_links(); ?>
			</div>
		</div>
	</div>
</div>
