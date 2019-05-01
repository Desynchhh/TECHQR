    <!-- JS function to delete the department -->
<script>
    function deleteDepartment(){
        
        var input = prompt('Er du sikker på du vil slette denne afdeling?\nIndtast afdelingens navn for at bekræfte:');
        if(input != null && input != ""){
            document.getElementById("input").value = input;
            document.getElementById("inputForm").submit();
        }
    }
</script>

    <!-- Title -->
<h2><?= $title ?></h2>
<hr>

    <!-- Department Info -->
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
    <a type="button" class="btn btn-warning" href="<?= base_url('departments/add/'.$department['id']); ?>">Tilføj bruger</a>
    </div>
        <!-- Delete department -->
    <div class="md-col-1" style="margin-left:1%;">
        <button class="btn btn-danger" onclick="deleteDepartment()">Slet afdeling</button>
            <!-- Hidden form to submit name when deleting the department -->
        <?= form_open('departments/delete/'.$department['id'], array('id' => 'inputForm')); ?>
            <input type="hidden" name="input" id="input" value="" class="btn btn-danger" />
        <?= form_close(); ?>
    </div>
</div>

<br>

    <!-- Back button -->
<div>
    <a href="<?= base_url('departments/index'); ?>"><button type="button" class="btn btn-primary">Tilbage til oversigt</button></a>
</div>

<br/>

    <!-- Table of users -->
<div>
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
                    <td><a class="btn btn-sm btn-danger" href="<?= base_url('departments/remove/'.$user['u_id'].'/'.$department['id']); ?>">Fjern</a></td>
                </tr>
            <?php endforeach;?>
        </tbody>
    </table>

        <!-- Pagination -->
    <div class="pagination-links">
        <?= $this->pagination->create_links(); ?>
    </div>
</div>

    <!-- Back button -->
<div>
    <a href="<?= base_url('departments/index'); ?>"><button type="button" class="btn btn-primary">Tilbage til oversigt</button></a>
</div>