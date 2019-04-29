            <script>
                $(document).ready(function(){
                    <?php if(isset($per_page)): ?>
                        $('#pag_per_page').change(function(){
                            //Selected value
                            var per_page = $(this).val();
                            <?php 
                                $uri_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
                                $uri_segments = explode('/', $uri_path);
                            ?>
                            window.location = '<?= base_url("$uri_segments[2]/$uri_segments[3]/"); ?>'+per_page+'/<?php echo $offset?>';
                        });
                    <?php endif;?>
                });
            </script>
            
                    <!-- Pagination -->
            <?php if(isset($per_page)): ?>
                <div class="pagination-links">
                    <?= $this->pagination->create_links(); ?>
                    <select id="pag_per_page">
                        <option selected hidden value="<?= $per_page ?>"><?= $per_page ?></option>
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="100">100</option>
                        <option value="Alle">Alle</option>
                    </select>
                </div>
            <?php endif;?>
            
            <hr>
            <p>&copy; 2019 - TECHCOLLEGE</p>
        </div>
    </body>
</html>