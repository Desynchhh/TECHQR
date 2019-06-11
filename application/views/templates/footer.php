
            
                    <!-- Pagination -->	
            <?php if(isset($per_page) && $per_page != NULL): ?>
            <p>Side <?= $offset / $per_page + 1 ?> af <?= ceil($total_rows / $per_page) ?></p>
                <div class="pagination-links">
                    <?= $this->pagination->create_links(); ?>
                        <!-- Elements per page dropdown -->
                    <select id="pag_per_page" onchange="pagPerPage(<?= (isset($id)) ? $id : NULL; ?>)">
                        <option selected hidden value="<?= $per_page ?>"><?= $per_page ?></option>
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="100">100</option>
                        <!-- <option value="Alle">Alle</option> -->
                    </select>
                </div>
            <?php endif;?>
            
            <hr>
                <!-- Actual footer -->
            <p>&copy; 2019 - TECHCOLLEGE</p>
        </div>
    </body>
</html>