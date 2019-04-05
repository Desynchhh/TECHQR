<h2><?= $title ?></h2>
<br>

<table class="table">
	<tbody>
		<tr>
			<th>Brugernavn</th>
			<th>Afdelinger</th>
            <th>Tilføj</th>
		</tr>
		<!-- create a <tr> with <td> children for each user in the database -->
		<?php foreach($users as $user): ?>
            <tr>
                <td><?= $user['username'] ?></td>
                <!-- get all departments for each individual user and show them in the table -->
                <td>NYI</td>
                <td>
                    <?= form_open('departments/add/'.$department['id'].'/0/'.$user['u_id']); ?>
                        <input type="submit" class="btn btn-sm btn-secondary" value="Tilføj" />
                    <?= form_close(); ?>
                </td>
            </tr>
		<?php endforeach;?>
	</tbody>
</table>

	<!-- Pagination -->
<div class="pagination-links">
	<?= $this->pagination->create_links(); ?>
</div>

<div>
    <a type="button" class="btn btn-primary" href="<?= base_url('departments/view/'.$department['id']); ?>">Tilbage til oversigt</a>
</div>