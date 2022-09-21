<div class="main-wrapper about-us">
        <!-- <div class="about-banner background-cover" style="background-image: url('<?= NEW_ASSETS_USER; ?>/new/images/about-us-banner.png'); padding-bottom: 0%;"> -->
        <div class="about-banner background-cover" style="background-color: #5C02B5;">
            <div class="caption">
                <h1><?= $this->lang->line('about_us_new');?></h1>
                <p><?= $this->lang->line('one_stop_platform');?></p>
            </div>
        </div>
        <div class="about-inner">
            <div class="container small">
                <div class="row align-items-top">
                    <div class="col-lg-6">
                        <div class="image">
                            <?php
                            if($language=='arabic'){?>
                                <img src="<?= NEW_ASSETS_USER; ?>/new/images/about-inner-item-arabic.png">

                                <?php }else{?>
                                    <img src="<?= NEW_ASSETS_USER; ?>/new/images/about-inner-item.png">

                              <?php }?>
                            
                            
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="desc ">
                            <h3><?= $this->lang->line('fvrt_auction_platform');?></h3>
                            <p class="v-height">
                                <?= $this->lang->line('about_us_info'); ?>
                                
                            </p>
                            <a class="view-more-ar"><?= $this->lang->line('view_more');?></a>
                            <!-- <a href="#"><?//= $this->lang->line('more_info_new');?></a> -->
                        </div>
                    </div>
                </div>        
            </div>
            <div class="container">
                <div class="our-services">
                    <h2><?= $this->lang->line('our_services');?></h2>
                    <div class="row">
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="box">
                                <div class="icon">
                                    <img src="<?= NEW_ASSETS_USER; ?>/new/images/money.svg">
                                </div>
                                <h4><?= $this->lang->line('free_car_val');?></h4>
                                <p>
                                   <?= $this->lang->line('about_us_car_desc'); ?> 
                                </p>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="box">
                                <div class="icon">
                                    <img src="<?= NEW_ASSETS_USER; ?>/new/images/tick.svg">
                                </div>
                                <h4><?= $this->lang->line('online_bidding');?></h4>
                                <p>
                                    <?= $this->lang->line('about_us_bidding_desc');?>
                                </p>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="box">
                                <div class="icon">
                                    <img src="<?= NEW_ASSETS_USER; ?>/new/images/money.svg">
                                </div>
                                <h4><?= $this->lang->line('live_bidding');?></h4>
                                <p>
                                    <?= $this->lang->line('about_us_live_bidding_desc');?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mission-vision hide-on-mobile">
                <div class="row no-gutters">
                    <div class="col-xl-6 d-flex">
                        <div class="image background-cover" style="background-image: url('<?= NEW_ASSETS_USER; ?>/new/images/Mission.jpg');background-size: 110% 102%;"></div>
                        <div class="desc">
                            <h4><?= $this->lang->line('our_mission');?></h4>
                            <p>
                                <?= $this->lang->line('about_us_our_mission_desc');?>
                            </p>
                        </div>
                    </div>
                    <div class="col-xl-6 d-flex m-l-2">
                        <div class="image background-cover" style="background-image: url('<?= NEW_ASSETS_USER; ?>/new/images/Vision.jpg');background-size: 110% 102%;  "></div>
                        <div class="desc">
                            <h4><?= $this->lang->line('about_us_our_vision');?></h4>
                            <p>
                                <?= $this->lang->line('about_us_our_vision_desc');?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
                <!-- <h2 class="mb-60"><?//= $this->lang->line('hear_from_us');?></h2> -->
           <!--  <div class="container">
                <div class="about-desc">
                        <div class="content">
                            <div class="image background-cover" style="background-image: url('<?= NEW_ASSETS_USER; ?>/new/images/person.png');"></div>
                            <div class="desc">
                                <h4><?= $this->lang->line('yasir_khushi');?></h4>
                                <h6><?= $this->lang->line('executive_director');?></h6>
                                <p>
                                   
                                </p>
                            </div>
                        </div>
                    </div>
                </div> -->
            </div>
        </div>
    </div>
<script type="text/javascript">
    

     $(document).on("click", ".view-more-ar", function() {
            $(".about-inner .desc p").toggleClass('v-height l-height');
            // console.log($(".view-more1").text());
            // var txt = $(".about-inner .desc p").is(':visible') ? 'View More' : 'View Less';
              <?php if($language=='arabic'): ?>
                     var txt = ($(".view-more-ar").text() == 'عرض المزيد') ? 'عرض أقل' : 'عرض المزيد';

                     <?php else: ?>

            var txt = ($(".view-more-ar").text() == 'View More') ? 'View Less' : 'View More';
                <?php endif;?>
            $(".view-more-ar").text(txt);

        });

</script>