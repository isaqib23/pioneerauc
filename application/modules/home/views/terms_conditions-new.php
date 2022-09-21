<?= $this->load->view('template/auction_cat') ?>
<div class="main gray-bg about-us">
    <div class="container">
        <?php 
            $title = json_decode($terms_info['title']);
            $description = json_decode($terms_info['description']);
        ?> 
        <h1 class="section-title"><?= $title->$language; ?></h1>
        <div class="content-box">
            <div class="inner-banner">
                <!-- <div class="image">
	            	<?php if(isset($about) && !empty($about['image'])){
	                    $file = $this->db->get_where('files', ['id' => $about['image']])->row_array(); ?>
	                    <img src="<?= base_url($file['path'] . '1110x276_' . $file['name']); ?>" alt="">
	                <?php }else{ ?>	
	                    <img src="<?= NEW_ASSETS_IMAGES; ?>home-banner.jpg" alt="">
	                <?php } ?>
                </div> -->
                <div class="desc" style="padding-top: 0">
                    <p>
                    	<?= $description->$language; ?>
                       <!--  Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                        tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                        quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                        consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
                        cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
                        proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                        Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                        tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                        quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                        consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
                        cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
                        proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                        Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                        tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                        quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                        consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
                        cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
                        proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                    </p>    
                    <p>
                        Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                        tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                        quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                        consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
                        cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
                        proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                        Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                        tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                        quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                        consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
                        cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
                        proident, sunt in culpa qui officia deserunt mollit anim id est laborum. -->
                    </p>
                </div>
            </div>    
        </div>
        <!-- <div class="content-box">
            <h2 class="section-title">OUR VALUES</h2>
            <ul class="values">
                <li>
                    <span>
                        <img src="<?= NEW_ASSETS_IMAGES; ?>value-icon1.png">
                    </span>
                    <p>COMMITMENT</p>
                </li>
                <li>
                    <span>
                        <img src="<?= NEW_ASSETS_IMAGES; ?>value-icon2.png">
                    </span>
                    <p>QUALITY</p>
                </li>
                <li>
                    <span>
                        <img src="<?= NEW_ASSETS_IMAGES; ?>value-icon3.png">
                    </span>
                    <p>PASSION</p>
                </li>
                <li>
                    <span>
                        <img src="<?= NEW_ASSETS_IMAGES; ?>value-icon4.png">
                    </span>
                    <p>CUSTOMER CARE</p>
                </li>
            </ul>
        </div> -->
    </div> 
</div>
