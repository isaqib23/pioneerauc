
    <!-- <img src="<?= NEW_ASSETS_IMAGES;?>home-banner.png"> -->
<div class="main">
  <div class="banner-slider">
    <?php if (isset($slider) && !empty($slider)) {
      foreach ($slider as $key => $value) {
        $small_title= json_decode($value['small_title']);
        $title = json_decode($value['title']);
        $description = json_decode($value['description']);
        $pfile = $this->db->get_where('files', ['id' => $value['image']])->row_array(); 
        ?>
      <div class="home-banner">
        <div class="image">
          <?php if ($pfile) {?>
            <img src="<?= base_url($pfile['path'] . $pfile['name']); ?>" alt="">
          <?php } else{ ?>
            <img src="<?= NEW_ASSETS_IMAGES ?>home-banner.jpg" alt="">
          <?php }  ?>
            <div class="caption" >
                <div class="container" data-aos="fade-up" data-aos-anchor-placement="top-bottom" data-aos-duration="2500">
                    <div class="inner">
                       <?php 
                      // $data= $this->db->get('home_info')->row_array();
                        
                    ?>
                        <h1>
                            <span> <?= $small_title->$language; ?> </span>
                            <?= $title->$language; ?>
                        </h1>
                        <p><?= $description->$language; ?></p>
                        <div class="button-row">
                          <?php $url = $value['link']; ?>
                            <a href="<?= $url ?>" class="btn btn-default"><?= $this->lang->line('view_detail');?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
      </div>
    <?php } ?>
    <?php } ?>
  </div>
  

  <section class="auctions">
      <div class="container">
          <h2 class="section-title" data-aos="fade-down" data-aos-anchor-placement="top-bottom" data-aos-duration="1500"><!-- Online --> <?= $this->lang->line('auction_catagories');?></h2>
          <div class="row">
            <?php 
                $show_banner_small = 'extended-slider';
                if(isset($banners_small) && !empty($banners_small)){
                  $show_banner_small = '';
                }
            ?>
              <div class="col left <?= $show_banner_small;?>" data-aos="fade-up" data-aos-anchor-placement="top-bottom" data-aos-duration="1500">
                  <div class="row cols-5 col-gap-20">
                    <?php if($active_auction_categories): 
                      foreach ($active_auction_categories as $key => $category):
                        // $expiry_time = strtotime($category['expiry_time']);
                        $cat_title = json_decode($category['title']);
                        $icon = $this->db->get_where('files', ['id' => $category['category_icon']])->row_array();
                      ?>
                        <div class="col-md-3">
                          <div class="box">
                            <?php if(isset($category['item_count']) && !empty($category['item_count'])) { ?>
                              <a href="<?= base_url('auction/online-auction/').$category['id']; ?>">
                                <!-- <span class="counter"><?= $category['item_count']; ?></span> -->
                            <?php } ?>
                              <div class="icon">
                                  <img src="<?= base_url('uploads/category_icon/').$icon['name']; ?>" alt="">
                              </div>
                              <h4><?= $cat_title->$language; ?></h4>
                              <?php if(isset($category['item_count']) && !empty($category['item_count'])) { 
                                // $auction_end_time = strtotime($category['expiry_time']);
                                // print_r(date('i',$auction_end_time));die();
                                ?>
                              <!-- <div  class="timer" id="expiry-timer<?//= $category['id']; ?>">
                                <span>
                                  
                                <em>  
                                  <script>
                                    //  $('#expiry-timer<?//= $category['id']; ?>').syotimer({
                                    //   year: <?//= date('Y', $auction_end_time); ?>,
                                    //   month: <?//= date('m', $auction_end_time); ?>,
                                    //   day: <?//= date('d', $auction_end_time); ?>,
                                    //   hour: <?//= date('H', $auction_end_time); ?>,
                                    //   minute: <?//= date('i', $auction_end_time); ?>,
                                    //   // dayVisible: false
                                    // });
                                  </script>
                                </em>
                                </span>
                    
                              </div> -->
                              <?php } else { ?>
                              <!-- <h6>Items will be available soon.</h6> -->
                              <?php } ?>
                            <?php if(isset($category['item_count']) && !empty($category['item_count'])) { ?>
                              </a>
                            <?php } ?>
                          </div>
                        </div>
                      <?php endforeach; ?>
                      <?php endif; ?>
                      <div class="col-md-3">
                        <a href="<?= base_url('valuation'); ?>">
                          <div class="box fixed" href="<?= base_url('valuation'); ?>">
                              <div class="icon">
                                  <img src="<?= NEW_ASSETS_IMAGES;?>auction-icons/car-valuation-icon.png" alt="">
                              </div>
                              <h4><?= $this->lang->line('valuation_home');?></h4>
                          </div>
                        </a>
                      </div>
                  </div>
              </div>
              <?php if(!empty($banners_small)){ 
                  $banner_titles = json_decode($banners_small['title']);
                  $banner_desc = json_decode($banners_small['description']);
                ?>
              <div class="col right" data-aos="fade-left" data-aos-anchor-placement="top-bottom" data-aos-duration="2500">
                <?php
                  $banner_file = $this->db->get_where('files', ['id' => $banners_small['image']])->row_array(); 
                 ?>
                  <div class="add-banner">
                    <img src="<?= base_url($banner_file['path'] . $banner_file['name']); ?>" alt="">
                    <div class="desc">
                            <h4><?php if(isset($banners_small) && !empty($banners_small)) { echo $banner_titles->english; } ?></h4>
                      </div>
                  </div>
              </div>
            <?php }?>
          </div>
      </div>
  </section>
    <?php 
    $show_banner = 'extended-slider';
    if(isset($banners) && !empty($banners)){
      $show_banner = '';
    }
    ?>
  <section class="live-auction <?= $show_banner; ?>">
      <div class="container">
          <h2 class="section-title" data-aos="fade-down" data-aos-anchor-placement="top-bottom" data-aos-duration="1500"><?= $this->lang->line('featured_items');?></h2>
          <div class="row">
            <!--  -->
              <div class="col left" data-aos="fade-up" data-aos-anchor-placement="top-bottom" data-aos-duration="1500">
                  <div class="upcomming-slider">
                    <?php 
                   
                    foreach ($featured_items as $key => $value) {
                       $expiry_time_formated = date('l d F Y H:i', strtotime($value['expiry_time']));
                      ?>
                      <div class="col">
                          <div class="live-box">
                            <!-- <?php $file = $this->db->get_where('files', ['id' => $value['item_images']])->row_array();?> --> 
                              <div class="image">
                                  <img src="<?= $file['path'].$file['name'];?>" alt="">
                              </div>
                              <?php ?>
                                <div class="desc">
                                    <h4>
                                        <a href="#"><?php if(isset($value['name']) && !empty($value['name'])){$item_name = json_decode($value['name']);} echo @$item_name->$language; ?></a>
                                    </h4>

                                    <!-- <p>2:30 PM<span>15 July 2020</span></p> -->
                                    <?php if(isset($value['expiry_time']) && !empty($value['expiry_time'])) echo $expiry_time_formated;?>
                                    <p><span></span></p>
                                    <div class="button-row">
                                      <?php
                                      if ($value['access_type'] =='live') {?>
                                        <a href="#" class="btn btn-default"><?= $this->lang->line('start_bidding');?></a>
                                      <?php }  else {?>
                                         <a href="<?php echo base_url('auction/online-auction/details/'.$value['auction_id'].'/'.$value['item_id']);?>" class="btn btn-default"><?= $this->lang->line('start_bidding');?></a>
                                      <?php }?>
                   
                                    </div>
                                </div>
                          </div>
                      </div>
                    <?php }?>
                  </div>
              </div>

            <?php if(empty($show_banner)){ ?>
               <div class="col right" data-aos="fade-left" data-aos-anchor-placement="top-bottom" data-aos-duration="2500">
                <?php if (isset($banners) && !empty($banners)) {
                  $banner_title = json_decode($banners['title']);
                  $banner_des = json_decode($banners['description']);
                  $banner_file = $this->db->get_where('files', ['id' => $banners['image']])->row_array(); 
                 ?>
                  <div class="add-banner">
                      <img src="<?= base_url($banner_file['path'] . $banner_file['name']); ?>" alt="">
                      <div class="desc">
                            <h4><?php if(isset($banners) && !empty($banners)) { echo $banner_title->english; } ?></h4>
                            <p><?php if(isset($banners) && !empty($banners)) { echo $banner_des->english; } ?></p>
                      </div>
                  </div>
                <?php } ?>
              </div> 
            <?php } ?>
          </div>
      </div>
  </section>

  <section class="our-work">
    <div class="container">
        <h2 class="section-title" data-aos="fade-down" data-aos-anchor-placement="top-bottom" data-aos-duration="1500"><?= $this->lang->line('how_it_works');?></h2>
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true"><?= $this->lang->line('seller');?></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false"><?= $this->lang->line('buyer');?></a>
            </li>
        </ul>
    </div>
    <div class="tab-content" id="myTabContent">
      <div class="container">
        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
          <ul class="bidding-steps">
            <li data-aos="fade-up" data-aos-anchor-placement="top-bottom" data-aos-duration="500">
              <div class="icon">
                <img src="<?= NEW_ASSETS_IMAGES;?>register-icon.png" alt="">
              </div>
              <p><?= $this->lang->line('register');?></p>
            </li>
            <li data-aos="fade-up" data-aos-anchor-placement="top-bottom" data-aos-duration="1500">
              <div class="icon">
                <img src="<?= NEW_ASSETS_IMAGES;?>select-icon.png" alt="">
              </div>    
              <p><?= $this->lang->line('select');?></p>
            </li>
            <li data-aos="fade-up" data-aos-anchor-placement="top-bottom" data-aos-duration="2000">
              <div class="icon">
                <img src="<?= NEW_ASSETS_IMAGES;?>bid-icon.png" alt="">
              </div>    
              <p><?= $this->lang->line('bid');?></p>
            </li>
            <li data-aos="fade-up" data-aos-anchor-placement="top-bottom" data-aos-duration="2500">
              <div class="icon">
                <img src="<?= NEW_ASSETS_IMAGES;?>win_aution-icon.png" alt="">
              </div>    
              <p><?= $this->lang->line('win');?></p>
            </li>
          </ul>
          <div class="button-row">
              <a href="<?php echo base_url('home/register');?>" class="btn btn-default"><?= $this->lang->line('get_started_now');?></a>
          </div>
        </div>
        <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
            <ul class="bidding-steps">
              <li data-aos="fade-up" data-aos-anchor-placement="top-bottom" data-aos-duration="500">
                <div class="icon">
                  <img src="<?= NEW_ASSETS_IMAGES;?>register-icon.png" alt="">
                </div>
                <p><?= $this->lang->line('register');?></p>
              </li>
              <li data-aos="fade-up" data-aos-anchor-placement="top-bottom" data-aos-duration="1500">
                <div class="icon">
                  <img src="<?= NEW_ASSETS_IMAGES;?>select-icon.png" alt="">
                </div>    
                <p><?= $this->lang->line('select');?></p>
              </li>
              <li data-aos="fade-up" data-aos-anchor-placement="top-bottom" data-aos-duration="2000">
                <div class="icon">
                  <img src="<?= NEW_ASSETS_IMAGES;?>bid-icon.png" alt="">
                </div>    
                <p><?= $this->lang->line('bid');?></p>
              </li>
              <li data-aos="fade-up" data-aos-anchor-placement="top-bottom" data-aos-duration="2500">
                <div class="icon">
                  <img src="<?= NEW_ASSETS_IMAGES;?>win_aution-icon.png" alt="">
                </div>    
                <p><?= $this->lang->line('win');?></p>
              </li>
            </ul>
            <div class="button-row">
              <a href="<?php echo base_url('home/register');?>" class="btn btn-default"><?= $this->lang->line('get_started_now');?></a>
            </div>
        </div>
      </div>
    </div>
  </section>
  <?php if(isset($partners) && !empty($partners)) : ?>

    <section class="our-partner">
        <div class="container">
            <h2 class="section-title" data-aos="fade-down" data-aos-anchor-placement="top-bottom" data-aos-duration="1500"><?= $this->lang->line('our_partners');?></h2>
            <div class="partner-slider row align-items-center">
              <?php foreach ($partners as $key => $partner) { ?>
                <div class="col">
                  <div class="image">
                    <?php if(isset($partner['image']) && !empty($partner['image'])){ 
                      $file = $this->db->get_where('files', ['id' => $partner['image']])->row_array(); ?>
                      <img src="<?= $file['path'].$file['name'];?>" alt="">
                    <?php } ?>
                  </div>
                </div>
              <?php } ?>
            </div>
        </div>
    </section>
  <?php endif; ?>
  <section class="our-app">
      <div class="container">
          <div class="inner">
              <div class="row align-items-center">
                  <div class="col-lg-8">
                      <h3><?= $this->lang->line('download_app');?><br><?= $this->lang->line('ios_android');?></h3>
                  </div>
                  <?php $social = $this->db->get('store_links')->row_array();?>
                  <div class="col-lg-4">
                      <ul>
                          <li data-aos="fade-down" data-aos-anchor-placement="top-bottom" data-aos-duration="2000">
                              <a href="<?php echo $social['apple_store']?>"target="_blank">
                                  <img src="<?= NEW_ASSETS_IMAGES;?>app-store-icon.png" alt="">
                              </a>
                          </li>
                          <li data-aos="fade-up" data-aos-anchor-placement="top-bottom" data-aos-duration="2000">
                              <a href="<?php echo $social['google_play']?>"target="_blank">
                                  <img src="<?= NEW_ASSETS_IMAGES;?>play-store-icon.png" alt="">
                              </a>
                          </li>
                      </ul>
                  </div>
              </div>
              <div class="app-img" data-aos="fade-right" data-aos-anchor-placement="top-bottom" data-aos-duration="2000">
                  <img src="<?= NEW_ASSETS_IMAGES;?>app-img.png" alt="">
              </div>
          </div>
      </div>
  </section>
</div>