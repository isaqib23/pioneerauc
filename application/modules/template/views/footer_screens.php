    <footer>
            <div class="container">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="logo">
                            <a href="#">
                                <img src="assets_user/images/logo.png" alt="">
                            </a>
                        </div>
                    </div>
                    <?php 
                    $data = $this->db->get('auction_items')->row_array();
                     ?>
                    <div class="col-sm-6">
                        <h3 class="approval-text"><?php echo $data['blink_text']?></h3>
                    </div>
                </div>
            </div>
        </footer>