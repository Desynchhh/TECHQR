<h2><?= $title ?></h2>
<h5>Klik på et events navn for at se flere detaljer.</h5>
<hr>

<div>
    <a type="button" class="btn btn-warning" href="<?= base_url('events/create'); ?>">Opret nyt event</a>
</div>

<br>

<div>
    <table class="table">
        <tbody>
            <tr>
                <th>Eventnavn</th>
                <th>Afdeling</th>
            </tr>
            <?php foreach($events as $event):?>
                <tr>
                    <td><a href="<?= base_url('events/view/'.$event['e_id']); ?>"><?= $event['e_name'] ?></a></td>
                    <td><?= $event['d_name'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
		<!-- Pagination -->
    <div class="btn-group mr-2" role="group" aria-label="First group">
		<?= $this->pagination->create_links(); ?>
	</div>
</div>