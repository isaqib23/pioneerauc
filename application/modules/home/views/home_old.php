<div class="home-banner">
    <!-- <img src="<?= ASSETS_IMAGES;?>home-banner.png"> -->
    <div class="banner-slider">
       <?php if (isset($slider) && !empty($slider)) 
                foreach ($slider as $key => $value) {
                  $pfile = $this->db->get_where('files', ['id' => $value['image']])->row_array(); 
                 ?>
      <div class="image">
        <img src="<?= base_url($pfile['path'] . $pfile['name']); ?>" alt="">
      </div>
      <?php } ?>
    </div>
    <div class="skitter">
      <?php if (isset($slider) && !empty($slider)) ?>
                <?php foreach ($slider as $key => $value) {
                  $pfile = $this->db->get_where('files', ['id' => $value['image']])->row_array(); 
                 ?>
      <ul>
        <li>
          <a href="#hideBars">
            <img src="<?= ASSETS_USER ;?>images/home-banner-new.png" class="hideBars">
          </a>
        </li>
        <li>
          <a href="#hideBars">
            <img src="<?= base_url($pfile['path'] . $pfile['name']); ?>" class="hideBars">
          </a>
        </li>
        <li>
          <a href="#hideBars">
            <img src="<?= base_url($pfile['path'] . $pfile['name']); ?>" class="hideBars">
          </a>
        </li>
        
       <!--  <li>
          <a href="#hideBars">
            <img src="<?= base_url($pfile['path'] . $pfile['name']); ?>" class="hideBars">
          </a>
        </li> -->
      </ul>
        <?php }?> 
    </div>
     <?php if (isset($slider) && !empty($slider)) ?>
                <?php foreach ($slider as $key => $value) {
                  $pfile = $this->db->get_where('files', ['id' => $value['image']])->row_array(); 
                 ?>
    <span class="person-img">
       <img src="<?= base_url($pfile['path'] . $pfile['name']); ?>" alt="">
    </span>
  <?php }?>
    <div class="home-caption wow fadeInUp" data-wow-duration="0.3" data-wow-delay="0.20s">
      <div class="container">
        <div class="heading">
          <?php 
          $data= $this->db->get('home_info')->row_array();
            $heading= json_decode($data['heading']);
            $title = json_decode($data['title']);
          ?>
           <?php if(isset($heading) && !empty($heading)){ ?>
            <h2><?php echo $heading->english;?></h2>
          <?php } ?>
          <?php if(isset($title) && !empty($title)){ ?>
            <p><?php echo $title->english;?></p>
          <?php } ?>
        </div>  
        <form>
          <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
          <ul>
            <li class="see-aution">
              <a href="<?php echo base_url('visitor/gallery');?>">See Live Auction</a>
            </li>

            <a href="link" download="download"></a>


            <li class="filters">
              <ul>
                <li>
                  <input type="text" class="form-control" placeholder="What are you looking for ?">
                </li>
                <li class="droplist">
                  <select class="selectpicker">
                    <option>Live Auction</option>
                  </select>
                </li>
                <li class="search-btn">
                  <button type="submit">
                    <img src="<?= ASSETS_IMAGES;?>search-icon-black.png" alt="">
                  </button>
                </li>
              </ul>
            </li>
          </ul>
        </form>
      </div>
    </div>
    <a href="#" class="scroll-arrow">
      <img src="<?= ASSETS_IMAGES;?>yellow-arrow.png" alt="">
    </a>
  </div>  
  <div class="gray-bg"> 
    <section class="online-aution">
      <div class="our-services">
        <h2 class="wow fadeInDown" data-wow-duration="0.4" data-wow-delay="0.40s">ONLINE AUCTIONS</h2>
        <div class="slider-wapper">
          <div class="slide">
            
            <?php if($auctions_online): 
              foreach ($auctions_online as $key => $auction):
                $expiry_time = strtotime($auction['expiry_time']);
                $cat_title = json_decode($auction['cat_title']);
                $icon = $this->db->get_where('files', ['id' => $auction['cat_icon']])->row_array();
                ?>
                <a href="<?= base_url('auction/online-auction/'.$auction['id']); ?>">
                  <div class="box">
                    <div class="image">
                      <img src="<?= base_url('uploads/category_icon/').$icon['name']; ?>">
                    </div>
                    <span><?= (isset($auction['item_count']) && !empty($auction['item_count'])) ? $auction['item_count']: 0; ?></span>
                    <p><?= $cat_title->english; ?></p>
                    <div class="timer" id="expiry-timer<?= $key; ?>"></div>
                    <script>
                      $('#expiry-timer<?= $key; ?>').syotimer({
                        year: <?= date('Y', $expiry_time); ?>,
                        month: <?= date('m', $expiry_time); ?>,
                        day: <?= date('d', $expiry_time); ?>,
                        hour: <?= date('H', $expiry_time); ?>,
                        minute: <?= date('i', $expiry_time); ?>
                      });
                    </script>
                  </div>
                </a>
              <?php endforeach; ?>
            <?php endif; ?>

          </div>
        </div>
      </div>
    </section>  

    <section class="upcoming-events">
        <div class="container">
          <h2><span>UPCOMING</span>LIVE AUCTIONS</h2>
        </div>
        <div class="slider-container">
          <div class="slider-wapper">
            <div class="slide">
              <?php if (isset($auctions) && !empty($auctions))
                 foreach ($auctions as $key => $value) {
                 ?>              
              <div class="box wow fadeIn" data-wow-duration="0.4" data-wow-delay="0.20s">
                <div class="image-wrap">
                  <div class="image">
                    <div class="icon">
                      <div class="outer">
                        <img src="<?= ASSETS_IMAGES;?>upcoming.png">
                        <a href="#">General Material <br>Auction</a>
                      </div>
                      <div class="carousel carousel-fade" data-ride="carousel">
                        <div class="carousel-inner">
                          <?php if(isset($auctions) && !empty($auctions))
                            // foreach ($auctions2 as $key => $value2) {    
                            // print_r($auctions);die();  
                            $pfile = $this->db->get_where('files', ['id' => $value['item_images']])->row_array(); 
                        ?>
                          <div class="carousel-item active">
                            <img class="d-block" src="<?= base_url($pfile['path'] . $pfile['name']); ?>" height="140" width="254" alt="First slide">
                          </div>
                        <?php  ?> 

                          <div class="carousel-item">
                            <img class="d-block" src="<?= ASSETS_IMAGES;?>upcoming.png" alt="Second slide">
                          </div>
                          <div class="carousel-item">
                            <img class="d-block" src="<?= ASSETS_IMAGES;?>services-1-hover.png" alt="Third slide">
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <?php
                    $data= explode(' ', $value['start_time']);
                    $dataa= explode('-', $data[0]);
                    $month = $dataa[1];
                    $days = $dataa[2];
                ?>
                <div class="button-row">
                  <a href="#" class="btn btn-default">Live Auction at<p><?php echo $month."-".$days;?></p> </a>
                </div>
              </div>
              
              <?php } ?>
  
            </div>
          </div>
        </div>
    </section>
  </div>
  <section class="buyer-sellar">
    <div class="buy-sallers">
      <ul class="head">
        <li>
          <h2>SELLER</h2>
        </li> 
        <li class="icon">
          <img src="<?= ASSETS_IMAGES;?>buy-salles.png">
        </li> 
        <li>
          <h2>BUYER</h2>
        </li> 
      </ul>
      <div class="container">
        <div class="info-box">
          <div class="box  wow fadeInUp" data-wow-duration="0.2" data-wow-delay="0.20s">
            <div class="image">
              <img src="<?= ASSETS_IMAGES;?>buy-icon1.png">
            </div>
            <p>REGISTER</p>
          </div>
          <div class="box wow fadeInUp" data-wow-duration="0.2" data-wow-delay="0.40s">
            <div class="image">
              <img src="<?= ASSETS_IMAGES;?>select-icon.png">
            </div>
            <p>SELECT</p>
          </div>
          <div class="box wow fadeInUp" data-wow-duration="0.2" data-wow-delay="0.60s">
            <div class="image">
              <img src="<?= ASSETS_IMAGES;?>buy-icon2.png">
            </div>
            <p>VEHICLE</p>
          </div>
          <div class="box wow fadeInUp" data-wow-duration="0.2" data-wow-delay="0.80s">
            <div class="image">
              <img src="<?= ASSETS_IMAGES;?>win-icon.png">
            </div>
            <p>WIN</p>
          </div>  
        </div>
      </div>
      <div class="button-row">
        <a href="<?php echo base_url('home/register');?>" class="btn btn-default">GET STARTED NOW</a>
      </div>    
    </div>
  </section>  
  <section class="insta-feed">
      <div class="yellow-bar">
        <img src="<?= ASSETS_IMAGES;?>insta-icon.png">
        <h2>INSTAGRAM FEED</h2>
      </div>
      <div class="bg-arrow">
        <div class="container">
          <div class="wrapper">
            <div class="feed-slider row col-gap-94">
              <div class="col-md-12">
                <div class="image">
                  <img src="<?= ASSETS_IMAGES;?>insta-feed.png">
                </div>
              </div>
              <div class="col-md-12">
                <div class="image">
                  <img src="<?= ASSETS_IMAGES;?>insta-feed.png">
                </div>
              </div>
              <div class="col-md-12">
                <div class="image">
                  <img src="<?= ASSETS_IMAGES;?>insta-feed.png">
                </div>
              </div>
              <div class="col-md-12">
                <div class="image">
                  <img src="<?= ASSETS_IMAGES;?>insta-feed.png">
                </div>
              </div>
              <div class="col-md-12">
                <div class="image">
                  <img src="<?= ASSETS_IMAGES;?>insta-feed.png">
                </div>
              </div>
              <div class="col-md-12">
                <div class="image">
                  <img src="<?= ASSETS_IMAGES;?>insta-feed.png">
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>  
  </section>
  <section class="auction-on-go">
    <div class="container">
      <?php $social = $this->db->get('store_links')->row_array();
                    ?>
      <ul>
        <li>
          <h2>AUCTIONS ON THE GO</h2>
        </li>
        <li>
          <img src="<?= ASSETS_IMAGES;?>auction-on-go.png">
        </li>
        <li>
          <a href="<?php echo $social['google_play']?>"target="_blank"><img src="<?= ASSETS_IMAGES;?>play-store_img.png"></a>
          <a href="<?php echo $social['apple_store']?>"target="_blank"><img src="<?= ASSETS_IMAGES;?>app-store_img.png"></a>
        </li>
      </ul>
    </div>    
  </section>

