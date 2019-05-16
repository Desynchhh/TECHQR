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
                <dd class="department-dd"><?= $department['name'] ?><br></dd>
                <dt>Medlemmer:</dt>
                <dd class="department-dd"><?= count($users) ?><br></dd>
                <dt>Oprettet:</dt>
                <dd class="department-dd"><?= $department['created_at'] ?><br></dd>
            </dl>
        </div>

            <!-- Buttons -->
        <div class="row">
                <!-- Add user to department -->
            <div class="md-col-1" style="margin-left:1.33%;">
            <a href="<?= base_url('departments/add/'.$department['id']); ?>"><button type="button" class="btn btn-warning">Tilføj bruger</button></a>
            </div>
                <!-- Delete department -->
            <div class="md-col-1" style="margin-left:1%;">
                <button type="button" class="btn btn-danger" onclick="deleteDepartment()">Slet afdeling</button>
                    
                    <!-- Hidden form to submit name when deleting the department -->
                <?= form_open('departments/delete/'.$department['id'], array('id' => 'inputForm')); ?>
                    <input type="hidden" name="input" id="input" value="" class="btn btn-danger" />
                <?= form_close(); ?>
                
            </div>
        </div>
            <!-- Back button -->
        <div class="row">
            <div style="margin-left:1.33%">
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
                        <th>Brugernavn</th>
                        <th>Type</th>
                        <th>Email</th>
                        <th>Værktøj</th>
                    </tr>
                        <!-- Table data -->
                    <?php foreach($users as $user):?>
                        <tr>
                            <td><a href="<?= base_url('users/view/'.$user['u_id']); ?>"><?= $user['username'] ?></a></td>
                            <td><?= $user['permissions'] ?></td>
                            <td><?= $user['email'] ?></td>
                            <td><a class="btn btn-sm btn-danger" href="<?= base_url("departments/remove/$user[u_id]/$department[id]"); ?>">Fjern</a></td>
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
