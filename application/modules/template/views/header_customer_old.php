<header class="after-login">
    <div class="container">
        <div class="row flex-row-reverse">
            <div class="col-lg-9">
                <ul class="top-links">
                    <?php 
                    $date = date('Y-m-d');
                    $this->db->select('access_type , start_time , expiry_time');
                    $this->db->from('auctions');
                    $this->db->where('access_type =','live');
                    $this->db->where('start_status =','start');
                    $this->db->where('expiry_time >=', $date) OR $this->db->where('start_time <=', $date);
                    $this->db->where('start_time <=', $date);
                    $result = $this->db->get()->result_array();
                    
                    ?>
                    <?php if (!empty($result)) { ?>
                    <li>
                        <a href="<?= base_url('auction/live-auction'); ?>" class="wifi-signal on-live">
                            <svg viewBox="0 0 40 32">
                                <path d="M17.98 28.12c0-1.1.9-2.02 2.02-2.02s2.02.9 2.02 2.02-.9 2.02-2.02 2.02-2.02-.9-2.02-2.02"/>
                                <path d="M20 18.02c3.34 0 6.37 1.36 8.57 3.55l-2.86 2.86c-1.45-1.46-3.47-2.37-5.7-2.37s-4.25.9-5.7 2.37l-2.87-2.86c2.2-2.2 5.23-3.55 8.57-3.55"/>
                                <path d="M5.7 15.86c3.83-3.82 8.9-5.92 14.3-5.92s10.47 2.1 14.3 5.92l-2.87 2.85C28.38 15.67 24.33 14 20 14s-8.38 1.68-11.43 4.73L5.7 15.87"/>
                                <path d="M31 4.08c3.38 1.43 6.4 3.47 9 6.06L37.14 13C32.56 8.42 26.48 5.9 20 5.9S7.44 8.42 2.86 13L0 10.14c2.6-2.6 5.62-4.63 9-6.06 3.48-1.47 7.18-2.22 11-2.22s7.52.74 11 2.22"/>
                            </svg>
                            <img src="<?= ASSETS_IMAGES;?>wifi-signal.png">
                            LIVE AUCTIONS
                        </a>
                    </li>   
                <?php } 
                 else{ ?>
                      <li>
                        <a href="#" class="wifi-signal on">
                            <svg viewBox="0 0 40 32">
                                <path d="M17.98 28.12c0-1.1.9-2.02 2.02-2.02s2.02.9 2.02 2.02-.9 2.02-2.02 2.02-2.02-.9-2.02-2.02"/>
                                <path d="M20 18.02c3.34 0 6.37 1.36 8.57 3.55l-2.86 2.86c-1.45-1.46-3.47-2.37-5.7-2.37s-4.25.9-5.7 2.37l-2.87-2.86c2.2-2.2 5.23-3.55 8.57-3.55"/>
                                <path d="M5.7 15.86c3.83-3.82 8.9-5.92 14.3-5.92s10.47 2.1 14.3 5.92l-2.87 2.85C28.38 15.67 24.33 14 20 14s-8.38 1.68-11.43 4.73L5.7 15.87"/>
                                <path d="M31 4.08c3.38 1.43 6.4 3.47 9 6.06L37.14 13C32.56 8.42 26.48 5.9 20 5.9S7.44 8.42 2.86 13L0 10.14c2.6-2.6 5.62-4.63 9-6.06 3.48-1.47 7.18-2.22 11-2.22s7.52.74 11 2.22"/>
                            </svg>
                            <img src="<?= ASSETS_IMAGES;?>wifi-signal.png">
                            LIVE AUCTIONS
                        </a>
                    </li> 
                 <?php }
                ?>
                
                    <li>
                        <a href="<?= base_url('customer/sell_item')?>" class="wifi-signal">
                            <img src="<?= ASSETS_IMAGES;?>header-notification.png">
                            SELL WITH US
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url('visitor/faqs')?>">
                            <img src="<?= ASSETS_IMAGES;?>help-icon.png">
                            HELP
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <img src="<?= ASSETS_IMAGES;?>mobile-icon.png">
                            <?php 
                $data=$this->db->get('contact_us')->row_array();
                ?>
                <?php echo $data['mobile'];?>
                        </a>
                    </li>
                    <li class="lang-selector">
                        <!-- <a href="#">
                            العربية
                        </a> -->
                         <?php 
                                $arabic_link = '<a href="'.base_url('language/arabic/?url=').current_url().'" class="lang-link arabic"> العربية  </a>';
                                $english_link = '<a href="'.base_url('language/english/?url=').current_url().'" class="lang-link enlish"> English </a>';

                                if($this->session->userdata('site_lang')){
                                    $language = $this->session->userdata('site_lang');
                                    if($language == 'arabic'){
                                        echo $english_link;
                                    }else{
                                        echo $arabic_link;
                                    }
                                }else{  
                                    echo $arabic_link;
                                }
                            ?>
                    </li>
                </ul>
                <ul class="navigation">
                    <li>
                        <a href="<?= base_url('visitor/gallery'); ?>">AUCTIONS</a>
                    </li>
                    <li>
                        <a href="<?= base_url('valuation')?>">FREE CAR VALUATION</a>
                    </li>
                    <li>
                        <a href="<?= base_url('visitor/about_us')?>">ABOUT US</a>
                    </li>
                    <li>
                        <?php 
                        $user = $this->session->userdata('logged_in');
                        $login_class = '';
                        if(!empty($this->session->userdata('logged_in'))){
                            
                        $login_class = 'hide-on-login';
                        ?>
                        <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="image">
                                <?php 
                                if(isset($user->picture) && $user->picture != ''){?> 
                                    <img src="<?php echo base_url();?>uploads/profile_picture/<?php echo $user->id.'/'.$user->picture;?>" class="">
                                <?php }else{ ?>
                                    <img src="<?= ASSETS_IMAGES;?>no_image.png">
                                <?php }?>    
                                </span>
                                <span class="name">
                                    <?= (isset($user->fname) && !empty($user->fname)) ? $user->fname : '';?>
                                    <?= (isset($user->lname) && !empty($user->lname)) ? $user->lname : '';?>
                                    
                                </span>
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item" href="<?= base_url('customer/dashboard');?>">Dashboard</a>
                                <a class="dropdown-item" href="<?= base_url('customer/profile');?>">Profile</a>
                                <a class="dropdown-item" href="<?= base_url('customer/change_password');?>">Change Password</a>
                                <a class="dropdown-item" href="<?= base_url('customer/logout');?>">Logout</a>
                             </div>
                        </div>
                        <?php
                        }else{
                        ?>
                        <div class="<?= (!empty($login_class)) ? $login_class : '';?>" >
                            <a href="<?= base_url('home/login');?>">LOGIN</a>
                            <a href="<?= base_url('home/register');?>" class="register-link">REGISTER</a>
                        </div>
                    <?php
                        }
                    ?>
                          
                    </li>
                </ul>
            </div>
            <div class="col-lg-3">
                <div class="logo">
                    <a href="<?= base_url();?>">
                        <img src="<?= ASSETS_IMAGES;?>logo.png">
                    </a>
                    <div class="show-on-991">
                        <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="image">
                                    <img src="<?= ASSETS_IMAGES;?>no_image.png">
                                </span>
                                <span class="name">
                                    Join Doe
                                </span>
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item" href="#">Profile</a>
                                
                                <a class="dropdown-item" href="#">Logout</a>
                             </div>
                        </div>
                        <div class="close-icon">
                            <span></span>
                            <span></span>
                        </div>
                        <div class="menu-icon">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>  