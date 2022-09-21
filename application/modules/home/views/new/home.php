<script src="<?= NEW_ASSETS_USER; ?>/new/js/jquery.countdown.js"></script>
<?php
function get_lang_base_text($language , $_iTxt, $_iPrice){
    if ($language == 'english') {
        $_iValue = $_iTxt.' '.$_iPrice;
    }else{
         $_iValue = 'السعر الحالي  '.$_iPrice.' درهم إماراتي   ';
    }
    return $_iValue;
}

?>
<style type="text/css">
    .products>.slick-slide{
        width: 323px !important;
    }
    .blink {
   animation: blink 1s steps(1, end) infinite;
   }
   @keyframes blink {
   0% {
   opacity: 1;
   }
   50% {
   opacity: 0;
   }
   100% {
   opacity: 1;
   }
   }
   .auction-desc-heading-down{
    white-space: pre-wrap !important;
   }
</style><div class="main-wrapper">
    <div class="home-banner position-relative">
        <div class="slider">
            <?php 

            if (isset($slider) && !empty($slider)) {
              foreach ($slider as $key => $value) {
                if ($value['category_id'] == $categoryId) {
                    $slideNo = $key;
                }
                $small_title= json_decode($value['small_title']);
                $title = json_decode($value['title']);
                $description = json_decode($value['description']);
                @$mobile_description = json_decode($value['mobile_description']);
                

                 if($language=='arabic'):                    
                    @$pfile = $this->db->get_where('files', ['id' => $value['image_arabic']])->row_array();
                     @$pfileMobile = $this->db->get_where('files', ['id' => $value['mobile_image_arabic']])->row_array();
                 else:
                    @$pfile = $this->db->get_where('files', ['id' => $value['image']])->row_array();
                    @$pfileMobile = $this->db->get_where('files', ['id' => $value['mobile_image_english']])->row_array();
                endif;

                $return_string = substr($value['link'], 0, 4);
                if ($return_string == 'http') {
                    $url = $value['link']; 
                } else {
                    $url = 'https://'.$value['link'];
                }

                if ($device == 'mobile') {
                   $bgimg = (!empty($pfileMobile)) ? (base_url($pfileMobile['path'] . $pfileMobile['name'])) :  (base_url($pfile['path'] . $pfile['name'])) ;
                } else {
                    $bgimg = (!empty($pfile)) ? (base_url($pfile['path'] . $pfile['name'])) :  ASSETS_ADMIN."images/product-default.jpg";
                }

                ?>
                <div class="slide">
                     <a href="<?= $url ?>" class=" ">
                    <?php if ($pfile) {

                       
                            $showImage = (!empty($pfile)) ? (base_url($pfile['path'] . $pfile['name'])) :  ASSETS_ADMIN."images/product-default.jpg"; 
                        ?>
                        <div class="image img_dskt position-relative background-cover" style="background-image: url(<?=$bgimg; ?>);">



                            <div class="caption">
                                <h1><?= $small_title->$language; ?><br> <?= $title->$language; ?>

                                <?php
                                if ($device == 'mobile') {
                                 echo @$mobile_description->$language;
                             }else{
                                echo $description->$language;

                             }?></h1>                               
                                   
                                   
                            </div>
                        </div>
                    <?php } else{ ?>
                        <div class="image position-relative background-cover" style="background-image: url(<?= NEW_ASSETS_USER; ?>/new/images/banner-img.jpg);">
                            <div class="caption">
                                <h1><?= $small_title->$language; ?><br> <?= $title->$language; ?><?= $description->$language; ?></h1>
                                <span style="display: none;"><?= @$mobile_description->$language; ?></span>
                                
                            </div>
                        </div>
                    <?php }  ?>
                </a>
                </div>
            <?php }  }?>
        </div>
        <div class="banner-steps">
            <div class="inner flex">
                <div class="item">
                    <div class="icon flex">
                        <img src="<?= NEW_ASSETS_USER; ?>/new/images/icons/step-icon1.svg" alt="">
                    </div>
                    <div class="desc">
                        <p><?= $this->lang->line('get_free_valuation'); ?></p>
                    </div>
                </div>
                <div class="item">
                    <div class="icon flex">
                        <img src="<?= NEW_ASSETS_USER; ?>/new/images/icons/step-icon2.svg" alt="">
                    </div>
                    <div class="desc">
                        <p><?= $this->lang->line('select_date_for_auction'); ?></p>
                    </div>
                </div>
                <div class="item">
                    <div class="icon flex">
                        <img src="<?= NEW_ASSETS_USER; ?>/new/images/icons/step-icon3.svg" alt="">
                    </div>
                    <div class="desc">
                        <p><?= $this->lang->line('sell_your_car');?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="our-bids">
        <?php if ((isset($featured_items) && !empty($featured_items))) : ?>
            <div class="container">
                <div class="title-head align-items-center justify-content-between">
                    <h3 class="seciton-title" ><?= $this->lang->line('featured_bids'); ?></h3>
                    
                </div>

                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    
                    <?php 
                    $i=1;
                    foreach ($active_auction_categories as $categories) :?>

                        <li class="nav-item">
                            <a class="nav-link <?php if($i==1){ echo 'active'; } ?> " id="list<?= $i;?>-tab" data-toggle="tab" href="#list<?= $i;?>" role="tab" aria-controls="list<?= $i;?>" aria-selected="true">
                            <?= json_decode($categories['title'])->$language; ?>
                                
                            </a>
                        </li>
                   <?php 
                    $i++;
                    endforeach; ?>
                   
                </ul>
                <div class="tab-content" id="myTabContent">
                 <?php 
                 $j=1;
                 foreach ($active_auction_categories as $categories) :
                    $current_category = $categories['id']; ?>
                           <div class="tab-pane  <?php if($j==1) { echo 'show active'; } ?> " id="list<?= $j;?>" role="tabpanel" aria-labelledby="list<?= $j;?>-tab">

                            <div class="products">
                                <div class="customer-logos slider">
                                    <?php 
                                    foreach (@$featured_items[$current_category] as $featured_item) :
                                //  foreach ($featured_items as $key => $featured_item) :
                                //  if ($key < 3 && isset($featured_item['auction_id'])) :
                                 
                                    $bid_data = '';
                                    $image = '';
                                    $query = $this->db->query('Select bid.bid_amount, bid.bid_status ,bid.user_id from bid inner join ( Select max(bid_time) as LatestDate, item_id  from bid  Group by bid.item_id ) SubMax on bid.bid_time = SubMax.LatestDate and bid.item_id = SubMax.item_id  WHERE bid.item_id = '.$featured_item['item_id'].' AND bid.auction_id = '.$featured_item['auction_id'].';');
                                    // print_r($query->num_rows());die();
                                        if ($query->num_rows() > 0) {
                                            $bid_data =  $query->row_array();
                                        }
                                        if (!empty($featured_item['item_images'])) {
                                            $item_images = explode(',', $featured_item['item_images']);
                                            $image = $this->db->get_where('files', ['id' => $item_images[0]])->row_array();
                                        }?>
<div class="slide">
<div class="col-xl-12 col-md-12">
<div class="product">
    <div class="body">
        <a href="<?= base_url('auction/online-auction/details/').$featured_item['auction_id'].'/'.$featured_item['item_id'] ?>">
            <div class="image background-cover" style="background-image: url(<?= (!empty($image)) ? $image['path'].$image['name'] : base_url('assets_admin/images/product-default.jpg'); ?>);" >
                <span class="tag">
                    <?= get_lang_base_text($language , $this->lang->line('original_price'), (!empty($bid_data)) ? number_format($bid_data['bid_amount']) : number_format($featured_item['bid_start_price']));?>
                  
                        
                    </span>
            </div>
            <h3>
                    <?= json_decode($featured_item['name'])->$language; ?></h3>
        </a>
        <div class="listcardetail">
            <?php
            if ($current_category==1) :
                ?>
                    
                    <?php if (!empty($featured_item['itemMileage'])) : ?>
                        <span>
                    
                        <?php 
                            if ($language == 'english') {
                                if ($featured_item['mileageType'] == 'km') {
                                    $mileageType = 'Km';
                                }else{
                                    $mileageType = 'Miles';
                                }
                            }else{
                                if ($featured_item['mileageType'] == 'km') {
                                    $mileageType = 'كم ';
                                }else{
                                    $mileageType = ' ميل ';
                                }
                            }
                        ?>
                        <?= number_format($featured_item['itemMileage']).' '.$mileageType; ?> 
                    </span>
                    <span>
                        <b>.</b>
                    </span>
            
                    <?php endif; ?>
                    <span>
                        <?= $featured_item['itemYear']; ?>
                    </span>
                    <span>
                    <b>.</b>
                    </span>

                <?php   if (!empty($featured_item['itemSpecification'])) : ?>
                <span>
                    
                            <?php 
                                if ($language == 'english') {
                                    if ($featured_item['itemSpecification'] == 'GCC') {
                                        $specsType = 'GCC';
                                    }else{
                                        $specsType = 'IMPORTED';
                                    }
                                }else{
                                    if ($featured_item['itemSpecification'] == 'GCC') {
                                        $specsType = 'خليجية';
                                    }else{
                                        $specsType = 'وارد';
                                    }
                                }
                            ?>

                        </svg> <?= $specsType ?>
                </span>
                    <?php endif;
                    ?>
                <?php else : ?>

                    <p><?= json_decode($featured_item['item_datail'])->$language; ?></p>
                
                    
                <?php endif;


                 $endObj = new DateTime(date('Y-m-d H:i:s', strtotime($featured_item['bid_end_time'])));
                $now = new DateTime();

                // The diff-methods returns a new DateInterval-object...
                $diff = $endObj->diff($now);

                $days = $diff->format('%a');
                $hours = $diff->format('%h');
                $showTime = ' %a d %h hrs';
                
               if($days == '0' && $hours < '4'){


                              $_alrt =  'blink';
                            }else{
                             $_alrt =  '';

                          }
                           ?>
                
        </div>

       
<p data-countdown="<?= date('Y-m-d H:i:s', strtotime($featured_item['bid_end_time'])) ?>"  class="timer auction-desc-heading-down <?= $_alrt?>"></p>

    
    </div>
    <div class="footer viewdetailbtn">
        <a href="<?= base_url('auction/online-auction/details/').$featured_item['auction_id'].'/'.$featured_item['item_id'] ?>"><?= $this->lang->line('view_details');?></a>
    </div>
</div>
</div>
</div>
                                <?php //endif; ?>
                                <?php endforeach; ?>
                            </div>
                            <div class="viewallbtns">
                                <a href="<?= base_url('search/').$categories['id']; ?>" class="btn-link"><?= $this->lang->line('view_all');?></a>
                            </div> 
                        </div>



                        
                    </div>
                    <?php 
                $j++;
                endforeach; ?>
                  
                </div>
            </div>
        <?php endif; ?>
    </div>

    
    <?php if (isset($nearlyCloseAuctions) && !empty($nearlyCloseAuctions) ) : ?>
        <div class="close-items">
            <div class="container">
                <div class="title-head align-items-center justify-content-between">
                    <h3 class="seciton-title" ><?= $this->lang->line('auctions_about_to_close'); ?></h3>
                    
                </div>
                <div class="products">
                
                    <div class="customer-logos slider">
                        
                        <?php 
                        $jj=1;
                        foreach ($nearlyCloseAuctions as $key => $nearlyClose) :
                            $image = '';
                            if (!empty($nearlyClose['item_images'])) {
                                $image_ids = explode(',', $nearlyClose['item_images']);
                                $image = $this->db->get_where('files', ['id' => $image_ids[0]])->row_array();
                            } 
                           $endObj = new DateTime(date('Y-m-d H:i:s', strtotime($nearlyClose['bid_end_time'])));
                $now = new DateTime();

                // The diff-methods returns a new DateInterval-object...
                $diff = $endObj->diff($now);

                $days = $diff->format('%a');
                $hours = $diff->format('%h');
                $showTime = ' %a d %h hrs';
                
               if($days == '0' && $hours < '4'){


                              $_alrt =  'blink';
                            }else{
                             $_alrt =  '';

                          }
                          ?>
                            <div class="slide">
                                <div class="col-xl-12 col-md-12">
                                    
                                    <div class="product">
                                        <div class="body">
                                            <a href="<?= base_url('auction/online-auction/details/').$nearlyClose['auction_id'].'/'.$nearlyClose['item_id'] ?>">
                                                <div class="image background-cover" style="background-image: url(<?= (!empty($image)) ? $image['path'].$image['name'] : base_url('assets_admin/images/product-default.jpg'); ?>);" >
                                                </div>
                                                <h3 class="text-truncate">
                                                    <?= json_decode($nearlyClose['name'])->$language; ?>
                                                </h3>
                                            </a>
                                            <p class="text-truncate"><?= json_decode($nearlyClose['detail'])->$language; ?></p>
                                           <p data-countdown="<?= date('Y-m-d H:i:s', strtotime($nearlyClose['bid_end_time'])) ?>"  class="timer auction-desc-heading-down <?= $_alrt?>"></p>
                        
                                        </div>
                                        <div class="footer style-2">
                                            <ul class="actions flex">
                                               
                                                <li class="flex">
                                                    <a style="color:#5c02b5;" href="<?= base_url('auction/online-auction/details/').$nearlyClose['auction_id'].'/'.$nearlyClose['item_id'] ?>"><?= $this->lang->line('view_details'); ?></a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php 
                        $jj++;
                    endforeach; ?>
                    </div>
                     <!-- <div class="viewallbtns">
                        <a href="<?= base_url('search/').$categoryId; ?>" class="btn-link sm m-0"><?= $this->lang->line('view_all'); ?></a>
                    </div>  -->
                </div>
            </div>
        </div>
    <?php endif; ?>
    <!-- <div class="avail-auciton">
        <div class="item static flex">
            <div class="caption text-center">
                <div class="logo">
                   <?php if ($language == 'english') {
                        $logo = 'pioneer-logo-english-2.svg';
                   }else{
                        $logo = 'pioneer-logo-arabic-2.svg';    
                   } ?>
                    <img src="<?= NEW_ASSETS_USER; ?>/new/images/logo/<?= $logo;?>" alt="">
                </div>
                <p> &nbsp;<?=$this->lang->line('checkout_available_auctions')?></p>
                <div class="button-row">
                    <a href="<?= base_url('search/').$categoryId; ?>" class="btn btn-default"><?= $this->lang->line('view_all');?></a>
                </div>
            </div>
        </div>
        <?php if ($live_auctions || $online_auctions) : ?>
            <?php if ($live_auctions) : ?>
                <div class="item background-cover" style="background-image: url(<?= (!empty($live_auctions)) ? $live_auctions[0]['path'].$live_auctions[0]['name'] : base_url('assets_admin/images/product-default.jpg'); ?>);">
                    <a href="<?= base_url('search?categoryId=').$live_auctions[0]['categoryId'].'&auctionType=live'; ?>" class="stretched-link" >
                        <div class="caption">
                            <h4><?= json_decode($live_auctions[0]['auction_title'])->$language; ?><br> </h4>
                            <p><?=  json_decode($live_auctions[0]['auctionDetail'])->$language;?></p>
                        </div>
                    </a>
                </div>
            <?php else : ?>
                <div class="item background-cover" style="background-image: url(<?= base_url('assets_user/new/images/sm-hal-image.png'); ?>);">
                    <div class="caption">
                        <h4><?= $this->lang->line('Stay_tuned_for_upcoming_auctions'); ?><br></h4>
                        <p><?= $this->lang->line('Get_best_deals_on_vehicles_general_material_more'); ?></p>
                    </div>
                </div>
            <?php endif; ?>
            <?php if ($online_auctions): ?>
                <div class="item background-cover" style="background-image: url(<?= (!empty($online_auctions)) ? $online_auctions['path'].$online_auctions['name'] : base_url('assets_admin/images/product-default.jpg'); ?>);">
                    <a href="<?= base_url('search?categoryId=').$online_auctions['categoryId']; ?>" class="stretched-link" >
                        <div class="caption">
                            <h4><?= json_decode($online_auctions['auction_title'])->$language; ?></h4>
                            <p><?=  json_decode($online_auctions['auctionDetail'])->$language;?></p>
                        </div>
                    </a>
                </div>
            <?php else : ?>
                <div class="item background-cover" style="background-image: url(<?= base_url('assets_user/new/images/sm-hal-image.png'); ?>);">
                    <div class="caption">
                        <h4><?= $this->lang->line('Stay_tuned_for_upcoming_auctions'); ?><br></h4>
                        <p><?= $this->lang->line('Get_best_deals_on_vehicles_general_material_more'); ?></p>
                    </div>
                </div>
            <?php endif; ?>
        <?php else : ?>
            <div class="item sec-item background-cover" style="background-image: url(<?= base_url('assets_user/new/images/hal-image.png'); ?>);">
                    <div class="caption">
                        <h4><?= $this->lang->line('Stay_tuned_for_upcoming_auctions'); ?></h4>
                        <p><?= $this->lang->line('Get_best_deals_on_vehicles_general_material_more'); ?></p>
                    </div>
                <a href="<?= base_url('search?categoryId=').$online_auctions['categoryId']; ?>" class="stretched-link" ></a>
            </div>
        <?php endif; ?>
    </div> -->
    <?php if (isset($media) && !empty($media)) : ?>
        <div class="our-media">
            <div class="container">
                <div class="title-head align-items-center justify-content-between">
                    <h3 class="seciton-title" ><?= $this->lang->line('new_media'); ?></h3>
                </div>
                <div class="row no-gutters">
                    <?php if (isset($media[0])) :
                        if (!empty($media[0]['item_images'])) {
                            $image_ids = explode(',', $media[0]['item_images']);
                            $first_item_image = $this->db->get_where('files', ['id' => $image_ids[0]])->row_array();
                        } ?>
                        <div class="col-lg-7">
                            <div class="media-box">
                                <a  href="<?= base_url('visitor/gallery_detail/').$media[0]['id']; ?>">
                                    <div class="image background-cover" style="background-image: url(<?= (!empty($first_item_image)) ? $first_item_image['path'].$first_item_image['name'] : base_url('assets_admin/images/product-default.jpg'); ?>);"></div>
                                    <div class="caption">
                                        <h3><?= json_decode($media[0]['name'])->$language ?></h3>
                                        <p><?= json_decode($media[0]['detail'])->$language ?></p>
                                    </div>
                                </a>
                            
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="col-lg-5">
                        <div class="row no-gutters">
                            <?php foreach ($media as $key => $value) :
                            $item_image = '';
                                if ($key > 0) :
                                    if (!empty($value['item_images'])) {
                                        $image_ids = explode(',', $value['item_images']);
                                        $item_image = $this->db->get_where('files', ['id' => $image_ids[0]])->row_array();
                                    } ?>
                                    <div class="col-lg-12">
                                        <div class="media-box sm">
                                            <a href="<?= base_url('visitor/gallery_detail/').$value['id']; ?>">
                                                <div class="image background-cover" style="background-image: url(<?= (!empty($item_image)) ? $item_image['path'].$item_image['name'] : base_url('assets_admin/images/product-default.jpg'); ?>);">
                                                </div>


                                                <div class="caption">
                                                    <h3><?= json_decode($value['name'])->$language ?></h3>
                                                    <p><?= json_decode($value['detail'])->$language ?></p>
                                                </div>
                                            </a>
                                            <!-- <a href="#" class="stretched-link"></a> -->
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                            <!-- <div class="col-lg-12">
                                <div class="media-box sm">
                                    <div class="image background-cover" style="background-image: url(<?= NEW_ASSETS_USER; ?>/new/images/media-img3.jpg);"></div>
                                    <div class="caption">
                                        <div class="vide-duration">
                                            <img src="<?= NEW_ASSETS_USER; ?>/new/images/icons/play-icon.svg" alt="">
                                            <span>20:15</span>
                                        </div>
                                        <h3>Volkswagen Touareg V8 2009 MPV</h3>
                                        <p>Lorem ipsum dolor sit amet, consectetu.</p>
                                    </div>
                                    <a href="#" class="stretched-link"></a>
                                </div>
                            </div> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>


    <!-- <div class="testimonial-slider">
        <div class="container">
            <h3 class="seciton-title"><?= $this->lang->line('what_all_people_saying')?></h3>
            <div class="slidertesti">
                
                    <div class="testimonials">
                        <div class="box">
                            <div class="icon">
                                <img src="<?= NEW_ASSETS_USER; ?>new/images/Badge.svg" alt="">
                            </div>
                            <div class="user">
                                <div class="image">    
                                    <img src="<?= NEW_ASSETS_USER; ?>new/images/customer1.jpg" alt="">
                                </div>
                                 <h5><?= $this->lang->line('testimonial_name2')?></h5>
                               <p><?= $this->lang->line('testimonial_pa_customer')?></p>
                            </div>
                            <p><?= $this->lang->line('testimonial2'); ?> </p>
                        </div>
                    </div>

                    <div class="testimonials">
                        <div class="box">
                            <div class="icon">
                                <img src="<?= NEW_ASSETS_USER; ?>new/images/Badge.svg" alt="">
                            </div>
                            <div class="user">
                                <div class="image">    
                                    <img src="<?= NEW_ASSETS_USER; ?>new/images/customer5.jpg" alt="">
                                </div>
                                 <h5><?= $this->lang->line('testimonial_name5')?></h5>
                                <p><?= $this->lang->line('testimonial_pa_customer')?></p>
                            </div>
                            <p> <?= $this->lang->line('testimonial5'); ?></p>
                        </div>
                    </div>

                    <div class="testimonials">
                        <div class="box">
                            <div class="icon">
                                <img src="<?= NEW_ASSETS_USER; ?>new/images/Badge.svg" alt="">
                            </div>
                            <div class="user">
                                <div class="image">    
                                    <img src="<?= NEW_ASSETS_USER; ?>new/images/customer3.jpg" alt="">
                                </div>
                                <h5><?= $this->lang->line('testimonial_name1')?></h5>
                             
                                <p><?= $this->lang->line('testimonial_pa_customer')?></p>
                            </div>
                            <p> <?= $this->lang->line('testimonial1'); ?></p>
                        </div>
                    </div>
                    
                    <div class="testimonials">
                        <div class="box">
                            <div class="icon">
                                <img src="<?= NEW_ASSETS_USER; ?>new/images/Badge.svg" alt="">
                            </div>
                            <div class="user">
                                <div class="image">    
                                    <img src="<?= NEW_ASSETS_USER; ?>new/images/customer2.jpg" alt="">
                                </div>
                                 <h5><?= $this->lang->line('testimonial_name3')?></h5>
                                <p><?= $this->lang->line('testimonial_pa_customer')?></p>
                            </div>
                            <p> <?= $this->lang->line('testimonial3'); ?></p>
                        </div>
                    </div>
                      <div class="testimonials">
                        <div class="box">
                            <div class="icon">
                                <img src="<?= NEW_ASSETS_USER; ?>new/images/Badge.svg" alt="">
                            </div>
                            <div class="user">
                                <div class="image">    
                                    <img src="<?= NEW_ASSETS_USER; ?>new/images/customer4.jpg" alt="">
                                </div>
                                 <h5><?= $this->lang->line('testimonial_name4')?></h5>
                                <p><?= $this->lang->line('testimonial_pa_customer')?></p>
                            </div>
                            <p> <?= $this->lang->line('testimonial4'); ?></p>
                        </div>
                    </div>


                     


            </div>
        </div>
    </div> -->

    


    <div class="faq">
        <div class="container">
            <div class="title-head align-items-center justify-content-between">
                <h3 class="seciton-title" ><?=$this->lang->line('faq');?></h3>
            </div>
            <?php $j = 0; if (isset($faqs) && !empty($faqs)) :?>
                <div id="accordion">
                    <?php foreach ($faqs as $key => $value) : $j++;
                        $question = json_decode($value['question']);
                        $answer = json_decode($value['answer']);?>
                        <div class="card border-0 rounded-0">
                            <div class="card-header bg-white border-0 p-0" id="headingOne<?= $j; ?>">
                                <button class="btn collapsed" data-toggle="collapse" data-target="#collapseOne<?= $j; ?>" aria-expanded="true" aria-controls="collapseOne">
                                    <?= $question->$language; ?>
                                </button>
                            </div>
                            <div id="collapseOne<?= $j; ?>" class="collapse" aria-labelledby="headingOne<?= $j; ?>" data-parent="#accordion">
                                <div class="card-body">
                                    <p><?= $answer->$language; ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        $('.customer-logos').slick({
            slidesToShow: 4,
            slidesToScroll: 4,
            autoplay: false,
            autoplaySpeed: 500,
            speed: 500,
            arrows: false,
            dots: false,
            pauseOnHover: false,
            arrows: true,
            rtl: false,
            pauseOnHover: true,
            pauseOnFocus: true,
            responsive: [{
                breakpoint: 1024,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 3,
                }
            },{
                breakpoint: 768,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 2,
                }
            }, {
                breakpoint: 520,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1,
                }
            }]
        });

        
    });
</script>

<script>
	$(document).ready(function(){
        $('.slidertesti').slick({
            slidesToShow: 3,
            slidesToScroll: 3,
            autoplay: false,
            autoplaySpeed: 500,
            speed: 500,
            arrows: false,
            dots: true,
            arrows: false,
            // rtl: true,
            
            responsive: [{
                breakpoint: 991,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 2,
                }
            }, {
                breakpoint: 767,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1,
                }
            }]
        });

        
    });
</script>

<script>
    $("#list1-tab").click(function(){
        $('.customer-logos').slick('refresh');
    });

    $("#list2-tab").click(function(){
        $('.customer-logos').slick('refresh');
    });

    $("#list3-tab").click(function(){
        $('.customer-logos').slick('refresh');
    });

    $("#list4-tab").click(function(){
        $('.customer-logos').slick('refresh');
    });

    $("#list5-tab").click(function(){
        $('.customer-logos').slick('refresh');
    });

    $("#list6-tab").click(function(){
        $('.customer-logos').slick('refresh');
    });

    $("#list7-tab").click(function(){
        $('.customer-logos').slick('refresh');
    });
</script>

<?php if(isset($slideNo)) : ?>
    <script type="text/javascript">
        $(document).ready(function(){
            var sliderNo = '<?= $slideNo; ?>';
            console.log(sliderNo);
            $('.slider').slick('slickGoTo', sliderNo);
        })
    </script>
<?php endif; ?>
<script type="text/javascript">
  var lng = '<?=$language;?>';
 $('p[data-countdown]').each(function() {
  var $this = $(this), finalDate = $(this).data('countdown');
  $this.countdown(finalDate, function(event) {

    if(lng=='arabic'){

         if (event.elapsed) {
              $this.html(event.strftime("<?= $this->lang->line('time_expired'); ?>"));
              $(this).parent().parent().parent().parent().remove();
      } else {
         if(event.strftime('%D') == '00'){    

            if (event.strftime('%H') <= '23' && event.strftime('%M') <= '59' && event.strftime('%S') <= '59') {
              $this.html(event.strftime("<?= $this->lang->line('ends_in_new'); ?>: %H <?= $this->lang->line('ends_hours'); ?> %M <?= $this->lang->line('ends_mins'); ?> %S <?= $this->lang->line('ends_sec'); ?>"));
              $this.addClass('blink');
            }else{
              $this.html(event.strftime("<?= $this->lang->line('ends_in_new'); ?>: %H <?= $this->lang->line('ends_hours'); ?> %M <?= $this->lang->line('ends_mins'); ?> %S <?= $this->lang->line('ends_sec'); ?>"));

            }

          }else{
            $this.html(event.strftime("<?= $this->lang->line('ends_in_new'); ?>: %D <?= $this->lang->line('ends_day'); ?> %H <?= $this->lang->line('ends_hours'); ?> %M <?= $this->lang->line('ends_mins'); ?> %S <?= $this->lang->line('ends_sec'); ?>"));
          }
      }
    }else{

      if (event.elapsed) {
              $this.html(event.strftime("<?= $this->lang->line('time_expired'); ?>"));
              $(this).parent().parent().parent().parent().remove();
      } else {
         if(event.strftime('%D') == '00'){    

            if (event.strftime('%H') <= '23' && event.strftime('%M') <= '59' && event.strftime('%S') <= '59') {
              $this.html(event.strftime("<?= $this->lang->line('ends_in'); ?>: %H hrs %M min %S sec"));
              $this.addClass('blink');
            }else{
              $this.html(event.strftime("<?= $this->lang->line('ends_in'); ?>: %H hrs %M min %S sec"));

            }

          }else{
            $this.html(event.strftime("<?= $this->lang->line('ends_in'); ?>: %D days %H hrs %M min %S sec"));
          }
      }


      }
  });
});



</script>


