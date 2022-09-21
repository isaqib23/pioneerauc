<?php

$id = $this->uri->segment(5);
$min = $this->db->get_where('auction_items',['item_id'=>$id])->row_array();

$bid_end_time = strtotime($item['bid_end_time']);
$item_images_ids = explode(',', $item['item_images']);
$images = $this->db->where_in('id', $item_images_ids)->get('files')->result_array();
if($images){
    $image_src = base_url('uploads/items_documents/').$item['id'].'/'.$images[0]['name'];
}else{
    $image_src = '';
}

$favorite = 'fa-heart-o';
if($this->session->userdata('logged_in')){
    $user = $this->session->userdata('logged_in');
    $favt = $this->db->get_where('favorites_items', ['item_id' => $item['id'],'user_id' => $user->id])->row_array();
    if($favt){
        $favorite = 'fa-heart';
    }else{
        $favorite = 'fa-heart-o';
    }
} else {
    // echo "login to continue";
}

if($this->session->userdata('logged_in')) {                    
    $is_logged_in = $this->session->userdata('logged_in');
}else{
    $is_logged_in = NULL;
}
?>

<!-- <style type="text/css">
    .auto-biding .btn.btn-default {width: auto; max-width: 200px;}
    .auto-biding ul .btn {width: 70%; max-width: 100px; }
    .auto-biding ul {justify-content: center;}
    .auto-biding li {width: 60px !important; padding: 0 !important;}
    .auto-biding li:not(:last-child){margin-right: 12px !important;}
</style>
<style>
  .pro-box em {display: block; font-style: normal;}
  .pro-box .syotimer__body {display: flex; flex-wrap: wrap; justify-content: space-around; margin-top: 4px;}
</style> -->

<script>
    $(document).ready(function(){
        $("#start_bid").attr('disabled',true);
        $("#btn-toggle").on("click", function(){
            $(".auto-biding > ul").slideToggle();
        });
    });
</script>

<!-- PNotify -->
<link href="<?php echo base_url(); ?>assets_admin/vendors/pnotify/dist/pnotify.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>assets_admin/vendors/pnotify/dist/pnotify.buttons.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>assets_admin/vendors/pnotify/dist/pnotify.nonblock.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets_admin/sweetalert/dist/sweetalert.css">


<div class="main gray-bg detail-page copy">
<?= $this->load->view('template/auction_cat', ['category_id', $category_id]);?>
    <div class="container">
        <div class="content-box">
            <div class="row stack-on-1200 col-gap-20">
                <div class="col left">
                    <div class="pro-slider"> 
                        <div class="main">
                            <?php 
                            if ($images):
                                foreach ($images as $key => $value): ?>
                                    <div class="image">
                                        <img src="<?= base_url('uploads/items_documents/').$item['id'].'/'.$value['name']; ?>" alt="">
                                    </div>
                                <?php endforeach; 
                            else: ?>
                                <img src="<?= base_url('assets_admin/images/product-default.jpg'); ?>" alt="">
                            <?php endif; ?>
                        </div>
                        <div class="thumb">
                            <?php foreach ($images as $key => $value): ?>
                                <div class="image">
                                    <img src="<?= base_url('uploads/items_documents/').$item['id'].'/'.$value['name']; ?>" alt="">
                                </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <div class="icons">
                            <?php 
                                $fav= 'fa fa-heart-o';
                                if(isset($fav_item) && !empty($fav_item)){
                                    $fav = 'fa fa-heart';
                                }
                            ?>
                            <span>
                                <a href="javascript:void(0)" id="fav_id" data-item="<?= $item['id'];?>" onclick="doFavt(this)" >
                                <i class="<?php echo $fav;?>"></i>
                                </a>
                            </span> 
                            <!-- <span>
                                <i class="fa fa-share"></i>
                            </span> -->
                        </div>
                    </div>
                </div>
                <div class="col right">
                    <!-- <div class="live-card">
                        <div class="head">
                            <h2>VOLKSWAGEN TOUAREG V6 MPV</h2>
                            <h3>Black, Automatic, 4 Door, MPV, Petrol</h3>
                        </div>
                        <div class="image">
                            <img src="<?//= base_url() ?>uploads/items_documents/31/pmzwIogvFt.jpg" alt="">
                        </div>
                        <ul class="counter">
                            <li class="active">
                                <p>Current Bid<span></span></p>
                                <h5>459,000<span>AED</span></h5>
                            </li>
                            <li>
                                <p>Lot<span>3948345</span></p>
                                <h5>459,000</h5>
                            </li>
                        </ul>
                    </div> -->
                    <div class="gray-box desc">
                        <ul class="h-list">
                            <li><h4><?php $item_name = json_decode($item['item_name']); echo strtoupper($item_name->$language);?></h4></li>
                            <br>
                            <li>
                                <span class="label">
                                    <!-- <img src="<?= NEW_ASSETS_IMAGES; ?>eye-icon.png" alt=""> -->
                                    <i class="eye-icon"></i>
                                    <?= $visit_count; ?>
                                </span>
                                <span class="label">
                                    <i class="calendar-icon"></i>
                                    <!-- <img src="<?= NEW_ASSETS_IMAGES; ?>calendar-icon.png" alt=""> -->
                                    <?= $item['year']; ?>
                                </span>
                                <?php if ($item['category_id'] == '1') {?>
                                <span class="label">
                                    <!-- <img src="<?= NEW_ASSETS_IMAGES; ?>metter-icon.png" alt=""> -->
                                    <i class="metter-icon"></i>
                                    <?= number_format($item['mileage']).' '.$item['mileage_type']; ?></span>
                            <?php } ?>
                            </li>
                        </ul>
                        <p class="bid-info">
                            <?= $this->lang->line('current_bid');?>
                            <span id="CBP" class="<?= $item['item_id']; ?>">AED <?= (isset($heighest_bid_data) && !empty($heighest_bid_data['current_bid'])) ? number_format($heighest_bid_data['current_bid'] , 0, ".", ",") : number_format($item['bid_start_price'] , 0, ".", ","); ?></span>
                            <i> <?= $this->lang->line('min_increment');?> <em>AED <?= $item['minimum_bid_price'] ?></em> </i>
                        </p>
                        <?php      
                        $user_have_bid = $this->db->get_where('bid', ['auction_id' => $auction_id,'user_id' => $user_id,'item_id' => $item_id]); ?>
                        <p class="bidder-name">
                            <span style="color: red;" id="bid_user_name<?= $item['item_id']; ?>">
                                <?php 
                                if ($user_have_bid->num_rows() > 0) { ?>
                                    <?php 
                                    if (isset($heighest_bid_data) && !empty($heighest_bid_data) && $this->session->userdata('logged_in'))  {
                                        if($heighest_bid_data['user_id'] == $user_id) {
                                            /*echo '<h style="color:green"><?= $this->lang->line("higher_bider");?>.</h>'; */
                                            $higher = '<h style="color:green">'.$this->lang->line('higher_bider').'</h>';
                                            echo $higher;
                                            
                                        }else{
                                            $not_higher = $this->lang->line('not_higher_bider');
                                            echo $not_higher;        
                                        } 
                                    }
                                    ?>
                                <?php } ?>
                            </span>
                        </p> 
                    </div>

                    <?php if($is_logged_in): ?>
                        <div class="bid-info-wrapper" >
                            <div class="bid-information">
                                <h4>
                                    <?=$this->lang->line('bid_info');?>
                                    <span>Bids<i id="no_of_bids<?= $item['item_id']; ?>"><?= (!empty($bid_count)) ? $bid_count : '0'; ?></i></span>
                                    <span>
                                        <!-- <div class="syotimers"> -->
                                            <div class="biding-timer" id="expiry-timer<?= $item['id']; ?>">
                                                <!-- <p>
                                                   <span>
                                                       <b>28 <i>d</i></b>
                                                       <b>23 <i>h</i></b>
                                                       <b>35 <i>m</i></b>
                                                       <b>25 <i>s</i></b>
                                                   </span>
                                                </p> -->
                                            </div>
                                            <script>
                                                $('#expiry-timer<?= $item['id']; ?>').syotimer({
                                                    year: <?= date('Y', $bid_end_time); ?>,
                                                    month: <?= date('m', $bid_end_time); ?>,
                                                    day: <?= date('d', $bid_end_time); ?>,
                                                    hour: <?= date('H', $bid_end_time); ?>,
                                                    minute: <?= date('i', $bid_end_time); ?>,
                                                 });
                                            </script>
                                        <!-- </div> -->
                                    </span>
                                </h4>
                                <!-- <p>Your Bid * <span>On Approval</span></p> -->
                            </div>
                            <div id="hideText" class="hideText<?= $item['item_id']; ?>"></div>
                            <?php 
                            $deposit_by_user = false;
                            if($item['security'] == 'yes'){
                                $user = $this->session->userdata('logged_in');
                                $user_deposit = $this->db->get_where('auction_item_deposits', [
                                    'user_id' => $user->id,
                                    'item_id' => $item['item_id'],
                                    'auction_id' => $item['auction_id'],
                                    'auction_item_id' => $item['auction_item_id']
                                ]);
                                //echo $this->db->last_query();
                                if($user_deposit->num_rows() > 0){
                                    $user_deposit = $user_deposit->row_array();
                                    if($user_deposit['deposit'] >= $item['deposit']){
                                        $deposit_by_user = true;
                                    }else{
                                        $deposit_by_user = false;
                                    }
                                }else{
                                    $deposit_by_user = false;
                                }
                            }else{
                                $deposit_by_user = true;
                            } 

                            if($deposit_by_user == true):
                            ?>
                                <div class="your-bid bid-div<?= $item['item_id']; ?>" id="hidebid_section"> 
                                    <ul class="h-list place-bid">
                                        <li>
                                            <h6 id="your-bid-amount<?= $item['item_id']; ?>">AED <?= (isset($heighest_bid_data) && !empty($heighest_bid_data['current_bid'])) ? number_format($heighest_bid_data['current_bid'] , 0, ".", ",") : number_format($item['bid_start_price'] , 0, ".", ","); ?> + &nbsp</h6>
                                            <input type="hidden" name="current_price" id="current_price<?= $item['item_id']; ?>" value="<?= (isset($heighest_bid_data) && !empty($heighest_bid_data['current_bid'])) ? $heighest_bid_data['current_bid'] : $item['bid_start_price']; ?>">
                                        </li>
                                        
                                        <li>
                                            <input type="number" min="0" placeholder="<?= $this->lang->line('enter_here');?>" class="form-control" id="bid_amount" name="bid_amount">
                                        </li>
                                        <li>
                                            <button disabled="" 
                                                onclick="placebid(this)" 
                                                data-auction-id="<?= $item['auction_id']; ?>" 
                                                data-item-id="<?= $item['item_id']; ?>" 
                                                data-seller-id="<?= $item['item_seller_id']; ?>" 
                                                data-min-bid="<?= $item['minimum_bid_price']; ?>" 
                                                data-lot-no="<?= $item['lot_no']; ?>" 
                                                data-start-price="<?= $item['bid_start_price']; ?>" 
                                                data-max-bid="<?= (float)$balance*10; ?>"
                                                class="btn btn-default" id="bid_btn"><?= $this->lang->line('place_bid');?></button>
                                        </li>
                                    </ul>
                                    <ul class="bid-labels" id="hide_lable">
                                        <li>
                                            <button class="btn btn-primary btn-counter bidder-button" onclick="bidButtons(this)" value="100">AED 100</button>
                                        </li>
                                        <li>
                                            <button class="btn btn-primary btn-counter bidder-button" onclick="bidButtons(this)" value="500">AED 500</button>
                                        </li>
                                        <li>
                                            <button class="btn btn-primary btn-counter bidder-button" onclick="bidButtons(this)" value="1000">AED 1000</button>
                                        </li>
                                        <li>
                                            <button class="btn btn-primary btn-counter bidder-button" onclick="bidButtons(this)" value="2000">AED 2000</button>
                                        </li>
                                        <li>
                                            <button class="btn btn-primary btn-counter bidder-button" onclick="bidButtons(this)" value="5000">AED 5000</button>
                                        </li>
                                    </ul>
                                    <div class="bid-form" id="autohide">
                                        <ul class="h-list place-bid">
                                            <li>
                                                <h6><?= $this->lang->line('auto_biding');?> &nbsp; 
                                                    <button class="info-btn" id="bid_help">
                                                        <i class="fa fa-question-circle"></i>
                                                    </button>
                                                </h6>
                                            </li>
                                            <li>
                                                <input type="number" class="form-control" placeholder="<?= $this->lang->line('your_limit');?>" id="auto_limit"  name="auto_limit">
                                            </li>
                                            <!-- <li>
                                               <button class="info-btn" id="bid_help">
                                                    <i class="fa fa-question-circle"></i>
                                                </button>
                                            </li> -->
                                            <!-- <li> -->
                                                <button class="btn btn-default"
                                                 id="start_bid"
                                                 onclick="autoBiding(this)"
                                                 ><?= $this->lang->line('start');?></button>
                                                 <!-- <span style="padding-left: 136px">Increment (AED <?= $item['minimum_bid_price'];?>)</span> -->
                                        </ul>
                                    </div>
                                </div>
                                <!-- <hr> -->
                            <?php endif; ?>
                            <?php if($item['security'] == 'yes' && !empty($item['deposit']) && $deposit_by_user == false): 
                            ?>
                                <div class="report">
                                    <h2 class="form-title"><?= $this->lang->line('deposit'); ?></h2>
                                    <ul>
                                        <li>
                                            <p class="toltip"><?= $this->lang->line('item_required_extra_deposit'); ?> <?= $item['deposit']; ?></p>
                                        </li>
                                        <li>
                                            <a class="btn btn-default" href="<?= base_url('customer/item_deposit?item_id=').$item['auction_item_id'].'&auction_id='.$item['auction_id'].'&rurl='.$rurl; ?>" class="file-link" ><?= $this->lang->line('deposit_now'); ?></a>
                                        </li>
                                    </ul>
                                </div>
                            <?php endif; ?>
                            <?php if ($balance == 0): ?>
                                <ul class="h-list deposit-links">
                                    <li>
                                        <h4><?= $this->lang->line('deposit_text');?>.</h4>
                                    </li>
                                    <li>
                                        <?= $this->lang->line('you_need_make_security_deposit'); ?><br> </br>
                                        <?php 
                                        $redirect = (isset($_GET['r'])) ? urldecode($_GET['r']) : array();
                                        if(!empty($redirect)){
                                            $redirect = json_decode($redirect);
                                        }
                                        array_push($redirect,$_SERVER['REQUEST_URI']);
                                        $json_redirect = json_encode($redirect);
                                        // $json_redirect = urlencode($json_redirect);
                                        // print_r($json_redirect);die('jhsdvfjsd ');
                                        ?>
                                        <a href="<?= base_url('deposit?').'r='.urlencode($json_redirect); ?>" class="btn btn-default sm" ><?= $this->lang->line('pay_deposit');?></a>
                                    </li>
                                </ul>
                            <?php endif; ?>
                        </div>
                        <?php $biding_limit = $balance*10; ?>
                    <?php else: ?>
                        <?php $biding_limit = 0; ?>
                        <div class="reports login-box">
                            <h3 class="after-login-h3"><?= $this->lang->line('login_first');?></h3>
                            <form>
                                <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <input type="text" class="form-control" placeholder="<?=$this->lang->line('email');?>"   name="email">
                                            <span class="valid-error text-danger email-error"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <input type="Password" class="form-control" placeholder="<?=$this->lang->line('password');?>"   name="password1">
                                            <span class="valid-error text-danger password1-error"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <script src="https://www.google.com/recaptcha/api.js?onload=myCallBack&render=explicit&hl=<?= $ln ?>" async defer></script>
                                    <div id="recaptcha1" 
                                        class="g-recaptcha" 
                                        data-sitekey="<?= $this->config->item('captcha_key');?>" 
                                        data-expired-callback="recaptchaExpired">
                                    </div>
                                    <span class=" text-warning" id="error-msgs" ></span>
                                </div>
                                <div class="button-row">
                                    <button type="button" id="loginBid" class="btn btn-default"><?= $this->lang->line('login');?></button>
                                    <a href="<?php echo base_url('home/register');?>" type="button" id="" class=""><?= $this->lang->line('register');?></a>
                                </div>
                            </form>
                            <script>
                                // Recaptcha Code
                                function enableBtn1(){
                                    document.getElementById("loginBid").disabled = false;
                                }
                                var recaptcha1;
                                var myCallBack = function() {
                                    recaptcha1 = grecaptcha.render('recaptcha1', {
                                      'sitekey' : '<?= $this->config->item('captcha_key');?>',
                                      'theme' : 'light', // can also be dark
                                      'callback' : 'enableBtn1' // function to call when successful verification for button 1
                                    });
                                };  
                                function recaptchaExpired(){
                                    document.getElementById("loginBid").disabled = true;
                                }

                                // Login button code
                                $(document).ready(function ($) {
                                    document.getElementById("loginBid").disabled = true;
                                });
                            </script>
                            
                        </div>
                    <?php endif; ?>
                </div>    
            </div>
            <div class="row">
                <div class="col left">
                    <div class="detail-tabs inner">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="detail-tab" data-toggle="tab" href="#detail" role="tab" aria-controls="detail" aria-selected="true"><?= $this->lang->line('detail');?></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="model-tab" data-toggle="tab" href="#model" role="tab" aria-controls="model" aria-selected="false"><?= $this->lang->line('description_small');?></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="location-tab" data-toggle="tab" href="#location" role="tab" aria-controls="location" aria-selected="false"><?= $this->lang->line('location');?></a>
                            </li>
                        </ul>
                        <div class="tab-content fix-height" id="myTabContent">
                            <div class="tab-pane fade show active" id="detail" role="tabpanel" aria-labelledby="detail-tab">
                                <div class="row">
                                    <div class="col-md-6">                                        
                                        <?php $j=5;
                                        if (! empty($item['item_model'])) { 
                                            $j=3;
                                            $model = $this->db->get_where('item_models', ['id' => $item['item_model']])->row_array(); 
                                            $make = $this->db->get_where('item_makes', ['id' => $item['item_make']])->row_array(); 
                                            $specs = $this->db->get_where('item', ['id' => $item['id']])->row_array(); 
                                            ?>
                                            <ul>
                                                <li><?= $this->lang->line('make');?>:</li>
                                                <?php $make_title = json_decode($make['title']);?>
                                                <li><?php if(!empty($make['title'])){echo $make_title->$language;} ?></li>
                                            </ul>
                                            <ul>
                                                <li><?= $this->lang->line('model');?>:</li>
                                                <?php $model_title = json_decode($model['title']);?>
                                                <li><?php if(!empty($model['title'])){echo $model_title->$language;}; ?></li>
                                            </ul>
                                            <ul>
                                                <li><?= $this->lang->line('specs');?>:</li>
                                                <li><?= $specs['specification']; ?></li>
                                            </ul>
                                        <?php } ?>
                                        <?php 
                                        $i=0; 
                                        foreach ($fields as $key => $value) { 
                                            if (!empty($value['data-value'])) {
                                                $i++;
                                                if($i<= $j) { ?>
                                                    <ul>
                                                        <!-- <li><?= $value['label']; ?>:</li> -->
                                                        <!-- <li><?= $value['data-value']; ?></li> -->
                                                        <li><?= $this->template->make_dual($value['label']); ?>:</li>
                                                        <li><?= $this->template->make_dual($value['data-value']); ?></li>
                                                        
                                                    </ul>
                                                    <?php 
                                                } 
                                            } 
                                        }
                                        ?>
                                        <ul class="h-list">
                            
                                            <!-- <li>
                                                <a href="<?= base_url().$item_test_report['path'].$item_test_report['name']; ?>" target="_blank" class="btn btn-gray" download class="file-link">Condition Report</a>
                                            </li> -->
                                            <li>
                                                <a href="<?= base_url('inspection_report/').$item['id']; ?>" class="btn btn-gray" class="file-link" target="_blank"><?= $this->lang->line('test_report');?></a>
                                                <?php $f = 0;
                                                if (!empty($item['item_test_report'])) {
                                                    $item_test_report = $this->db->where_in('id', $item['item_test_report'])->get_where('files')->result_array();
                                                    foreach ($item_test_report as $key1 => $document) { $f++;
                                                    ?>
                                                        <a href="<?= base_url().$document['path'].$document['name']; ?>" class="btn btn-gray" download class="file-link"><?php $path_parts = pathinfo($document['orignal_name']);
                                                        echo $path_parts['filename']; ?></a>
                                                <?php } } ?>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                        <?php 
                                        $i=0; 
                                        foreach ($fields as $key => $value) {
                                            if (!empty($value['data-value'])) {
                                                $K = $j+5;
                                                $i++;
                                                if($i> $j && $i<= $K) { ?>
                                                    <ul>
                                                       <!--  <li><?= $value['label']; ?>:</li>
                                                        <li><?= $value['data-value']; ?></li> -->
                                                        <li><?= $this->template->make_dual($value['label']); ?>:</li>
                                                        <li><?= $this->template->make_dual($value['data-value']); ?></li>
                                                    </ul>
                                                    <?php 
                                                } 
                                            } 
                                        } 
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="model" role="tabpanel" aria-labelledby="model-tab">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="desc-list">
                                        <?php
                                        $item_detail = json_decode($item['item_detail']);
                                        $detail_points = explode(',', $item_detail->$language);

                                        foreach ($detail_points as $key => $value) {
                                          ?>
                                            
                                                <span><?= $value; ?></span>
                                          <?php
                                        }
                                        ?>
                                        </div>
                                    </div>
                                    <!-- <div class="col-lg-6">
    
                                    </div> -->
                                </div>
                            </div>
                            <div class="tab-pane fade" id="location" role="tabpanel" aria-labelledby="location-tab">
                                <div class="row">
                                    <div dir="ltr" id="map" style="width: 100%; height: 300px;"></div> 
                                    <script src="<?php echo base_url(); ?>assets_admin/js/jquery.geocomplete.js"></script>
                                    <div class="col-lg-12">
                                        <ul>
                                            <li><?= $this->lang->line('location');?>:</li>
                                            <li><?= $item['item_location']; ?></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col right">
                    <div class="reports margin-top-30">
                        <?php if ($item['item_test_report']) {?>
                        <!-- <h3>Reports</h3> -->
                        <!-- <ul class="h-list">
                            
                            <li>
                                <a href="<?= base_url().$item_test_report['path'].$item_test_report['name']; ?>" target="_blank" class="btn btn-gray" download class="file-link">Condition Report</a>
                            </li>
                            <li>
                                <?php $item_test_report = $this->db->get_where('files', ['id' => $item['item_test_report']])->row_array(); ?>
                                <a href="<?= base_url().$item_test_report['path'].$item_test_report['name']; ?>" class="btn btn-gray" download class="file-link">Test Report</a>
                            </li>
                        </ul> -->
                        <?php }?>
                        <h2><?= $this->lang->line('terms');?></h2>
                        <p>
                            <?php $item_terms= json_decode($item['terms']); echo $item_terms->$language; ?>
                        </p>
                        <p><?= $this->lang->line('inquire_further');?></p>
                        <ul class="h-list">
                            <li>
                                <a href="#" class="btn btn-gray"><?= $this->lang->line('enquire');?></a>
                            </li>
                            <li>
                                <a href="#" class="ltr phone-link">
                                    <i class="icon"></i>
                                    <!-- <img src="<?= NEW_ASSETS_IMAGES?>phone-icon2.png" alt=""> -->
                                    <?= $contact['phone']; ?></a>
                            </li>
                        </ul>
                        <a href="<?= base_url('terms-conditions'); ?>" class="terms-link">
                            <?= $this->lang->line('sale_terms');?>
                            <!-- <img src="<?= NEW_ASSETS_IMAGES?>angle-right.png" alt=""> -->
                            <i class="arrow-right"></i>
                        </a>
                        
                    </div>
                </div> 
            </div>        
        </div>    
                
        <section class="related-items">
            <?php
            if (!empty($related_items)) {?>
               <h4 class="inner-title"><?= $this->lang->line('related_products');?></h4>
            <?php }?> 
            <div class="slider">
            <?php if (!empty($related_items)) {
                foreach ($related_items as $key => $related_item) {
                    $bid_end_time = strtotime($related_item['bid_end_time']);
                    $bid_info = $this->db->where('item_id',$related_item['item_id'])->where('auction_id',$related_item['auction_id'])->order_by('id', 'desc')->from('bid')->get()->row_array();
                    $visit_count = $this->db->where('item_id',$related_item['item_id'])->where('auction_id',$related_item['auction_id'])->from('online_auction_item_visits')->count_all_results();
                    $related_item_bids_count = $this->db->where('item_id',$related_item['item_id'])->where('auction_id',$related_item['auction_id'])->from('bid')->count_all_results();
                    $item_images_ids = explode(',', $related_item['item_images']);
                    $images = $this->db->where_in('id', $item_images_ids)->get('files')->row_array();
                    ?>
                    <div class="col">
                        <div class="pro-box">
                            <div class="image">
                                <?php if ($images) { ?>
                                    <img src="<?= base_url('uploads/items_documents/').$related_item['item_id'].'/'.$images['name']; ?>" alt="">
                                <?php } else { ?>
                                    <img src="<?php base_url('assets_admin/images/product-default.jpg'); ?>" alt="">
                                <?php } ?>
                            </div>
                            <div>
                                <h6><?php $related_item_name = json_decode($related_item['item_name']); echo $related_item_name->$language; ?></h6>
                                <p><?php $related_item_detail = json_decode($related_item['item_detail']); echo $related_item_detail->$language; ?></p>
                                <?php $time= $related_item['bid_end_time'];
                                $date = date('Y-m-d H:i:s');
                                if ($time > $date) { ?>
                                    <div class="button-row">
                                        <a href="<?= base_url('auction/online-auction/details/').$related_item['auction_id'].'/'.$related_item['item_id']; ?>" class="btn btn-default sm"><?= $this->lang->line('see_detail');?></a>
                                    </div>
                                <?php }else{?>
                                    <div class="button-row">
                                        <a href="javascript:void(0)" class="btn btn-default sm"><?= $this->lang->line('see_detail');?></a>
                                    </div>
                                <?php }?>
                            </div>
                            <div class="table-wrapper">
                                <table cellpadding="0" cellpadding="0">
                                    <tr>
                                        <td>
                                            <p>
                                               <?= $this->lang->line('c_price');?>
                                                <span>AED <?= (!empty($bid_info)) ? $bid_info['bid_amount'] : $related_item['bid_start_price']; ?></span>
                                            </p>
                                        </td>
                                        <td>
                                            <p><?= $this->lang->line('remain_time');?>
                                                <span id="expiryy-timer<?= $related_item['auction_item_id']; ?>"> 
                                                    <script>
                                                        $('#expiryy-timer<?= $related_item['auction_item_id'];?>').syotimer({
                                                            year: <?= date('Y', $bid_end_time); ?>,
                                                            month: <?= date('m', $bid_end_time); ?>,
                                                            day: <?= date('d', $bid_end_time); ?>,
                                                            hour: <?= date('H', $bid_end_time); ?>,
                                                            minute: <?= date('i', $bid_end_time); ?>,
                                                        });
                                                    </script>
                                              </span>
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p>
                                                <?= $this->lang->line('min_increment');?>
                                                <span>AED <?= $related_item['minimum_bid_price'];?></span>
                                            </p>
                                        </td>
                                        <td>
                                            <?php
                                              $new_time = date('h:i A j M Y', strtotime($time));
                                            ?>
                                            <p>
                                               <?= $this->lang->line('end_time');?>
                                               <span>
                                                    <?php echo $new_time;?>
                                               </span>
                                            </p>
                                        </td>
                                    </tr>
                                    <?php $auction = $this->db->get_where('item_category', ['id' => $related_item['category_id']])->row_array(); 
                                    if ($auction['include_make_model'] == 'yes') {
                                    ?>
                                        <tr>
                                            <td>
                                                <p>
                                                    <?= $this->lang->line('odometer');?>
                                                    <span>
                                                        <?php if(isset($related_item['mileage']) && !empty($related_item['mileage'])) { echo $related_item['mileage']; }else{
                                                          echo "N/A";
                                                        }?> 
                                                        <?php if(isset($related_item['mileage_type']) && !empty($related_item['mileage'])) { echo $related_item['mileage_type']; }?>
                                                    </span>
                                                </p>
                                            </td>
                                            <td>
                                            <?php 
                                            $specs = $this->db->get_where('item', ['id' => $item['id']])->row_array();
                                            ?>
                                                <p>
                                                   <?= $this->lang->line('specs');?>
                                                   <span><?= $specs['specification']; ?></span>
                                                </p>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                    <tr>
                                        <td>
                                            <p>
                                               <?= $this->lang->line('bid');?> #
                                               <span>
                                                   <?php if(($related_item_bids_count) && !empty($related_item_bids_count)) { echo $related_item_bids_count; }else{
                                                    echo "0";
                                                   }?>
                                               </span>
                                            </p>
                                        </td>
                                        <td>
                                            <p>
                                                <?= $this->lang->line('lot');?>#
                                                <span><?php if(isset($related_item) && !empty($related_item)) { echo $related_item['lot_no']; }else{ echo "N/A";}?></span>
                                            </p>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <ul class="h-list">
                                <!-- <li>
                                    <p>Bids#<span><?//= (!empty($related_item_bids_count)) ? $related_item_bids_count : '0'; ?></span></p>
                                </li> -->


                                <?php 
                                    $favorite = $this->lang->line('add_list');
                                    $user = $this->session->userdata('logged_in');
                                    if($user){
                                        $favt = $this->db->get_where('favorites_items', ['item_id' => $item['item_id'],'user_id' => $user->id])->row_array();
                                        if($favt){
                                            $favorite = $this->lang->line('remove_list');
                                        }else{
                                            $favorite = $this->lang->line('add_list');
                                        }
                                    }
                                if ($time > $date) {?>
                                    <li>
                                        <div class="actions">
                                            <!-- <a href="#" class="btn btn-primary sm"><?= $favorite;?></a> -->
                                             <a onclick="doFavt(this)" data-item="<?= $related_item['item_id']; ?>" data-auction-id="<?= $related_item['auction_id']; ?>" data-auction-item-id="<?= $related_item['auction_item_id']; ?>" href="javascript:void(0);" class="btn btn-primary sm">
                                             <?= $favorite; ?></a>

                                            <a href="<?= base_url('auction/online-auction/details/').$related_item['auction_id'].'/'.$related_item['item_id']; ?>" class="btn btn-default sm"><?= $this->lang->line('bid_now');?></a>
                                        </div>
                                    </li>
                                <?php }else{?>
                                    <li>
                                        <a href="javascript:void(0)"  class="btn btn-default sm"><?= $this->lang->line('expired');?></a>
                                    </li>
                                <?php }?>
                            </ul>
                        </div>
                    </div>
                    <?php 
                } 
            } ?>
            </div>
        </section>
    </div>

    <section class="our-app">
        <div class="container">
            <div class="inner">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <h3><?= $this->lang->line('download_app');?><br> <?= $this->lang->line('ios_android');?></h3>
                    </div>
                    <div class="col-lg-4">
                        <ul>
                            <li data-aos="fade-down" data-aos-anchor-placement="top-bottom" data-aos-duration="2000">
                                <a href="#">
                                    <img src="<?= NEW_ASSETS_IMAGES ?>app-store-icon.png" alt="">
                                </a>
                            </li>
                            <li data-aos="fade-up" data-aos-anchor-placement="top-bottom" data-aos-duration="2000">
                                <a href="#">
                                    <img src="<?= NEW_ASSETS_IMAGES?>play-store-icon.png" alt="">
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="app-img" data-aos="fade-right" data-aos-anchor-placement="top-bottom" data-aos-duration="2000">
                    <img src="<?= NEW_ASSETS_IMAGES?>app-img.png" alt="">
                </div>
            </div>
        </div>
    </section>
</div>

<script src="<?php echo base_url(); ?>assets_admin/vendors/pnotify/dist/pnotify.js"></script>
<script src="<?php echo base_url(); ?>assets_admin/vendors/pnotify/dist/pnotify.buttons.js"></script>
<script src="<?php echo base_url(); ?>assets_admin/vendors/pnotify/dist/pnotify.nonblock.js"></script>
<script src="https://js.pusher.com/6.0/pusher.min.js"></script>

<!-- <script src="https://www.google.com/recaptcha/api.js?onload=myCallBack&render=explicit" async defer></script> -->

<script>
    var token_name = '<?= $this->security->get_csrf_token_name();?>';
    var token_value = '<?=$this->security->get_csrf_hash();?>';

    $('#bid_help').on('click',function(){
        var increment = "<?= $item['minimum_bid_price']; ?>"
        swal("<?= $this->lang->line('what_is_auto_biding');?> ("+increment+") <?= $this->lang->line('what_is_auto_biding_text');?>");
        });

    $(document).ready(function(){
        $('#eye').tooltip({
             title:'users visit this item!',
             placement:'bottom'
        });
        
        $('.fa-heart-o').tooltip({
             title:'<?=$this->lang->line('fav');?>!',
             placement:'bottom'
        });

        $('.fa-heart').tooltip({
             title:'<?=$this->lang->line('r_fav');?>!',
             placement:'bottom'
        });

        var hidebtn ="<?= isset($hide_auto_bid['auto_status']) ? $hide_auto_bid['auto_status'] : ''; ?>";
        //console.log(hidebtn);
        if(hidebtn == "start"){
            //console.log('start if ', hidebtn);
           // $('#hidebid_section').hide();
            $('.bid-div<?= $item['item_id']; ?>').hide();
           // $('#bid_amount').prop("readonly",true);
           var incText = "<?= ($min['minimum_bid_price']) ? $min['minimum_bid_price'] : 0; ?>";
           
           $('#hideText').html('<div><h2>Auto Bidding Has Started</h2><span>Increment (AED '+incText+' )</span></div>');
           // $('#hide_lable').hide();
           // $('#hideA').hide();
       }else{
            //console.log('else ', hidebtn);
            // $('#hidebid_section').show();
            $('#autohide').show();
            $('.bid-div<?= $item['item_id']; ?>').show();

            $('#hideText').hide(); 
            $('#hide_lable').show();  
            $('#hideA').show();
        }
    });
    
    function doFavt(e){
        var heart = $(e);
        var itemID = $(e).data('item');
        var auctionId ='<?php echo $this->uri->segment(4);?>';
        var auctionItemid ="<?= $item['auction_item_id']; ?>";
        $.ajax({
            type: 'post',
            url: '<?= base_url('auction/OnlineAuction/do_favt'); ?>',
            data: {'item_id':itemID,'auction_id':auctionId,'auction_item_id':auctionItemid, [token_name] : token_value},
            success: function(msg){
                console.log(msg);
                //var response = JSON.parse(msg);
                if(msg == 'do_heart'){
                    heart.find('.fa-heart-o').addClass('fa-heart').removeClass('fa-heart-o');  
                    // heart.text('<?= $this->lang->line('remove_list');?>');  
                    // $('.fa-heart').tooltip({
                    //     title:'Remove from favorite!',
                    //     placement:'bottom'
                    // });     
                }
                if(msg == 'remove_heart'){
                    heart.find('.fa-heart').addClass('fa-heart-o').removeClass('fa-heart');
                    // heart.text('<?= $this->lang->line('add_list');?>'); 
                    // $('.fa-heart-o').tooltip({
                    //     title:'Add to favorite!',
                    //     placement:'bottom'
                    // });
                }
                if(msg == '0'){
                    window.location.replace('<?= base_url("home/login?rurl="); ?>'+encodeURIComponent(window.location.href));
                }
            }
        });   
    } 

    function placebid(e){
        //alert('dona done');
        var lotno = $(e).data('lot-no');
        var heart = $(e);
        var auctionid = $(e).data('auction-id');
        var itemID = $(e).data('item-id');
        var minimum_bid_price = $(e).data('min-bid');
        var sellerid = $(e).data('seller-id');
        var start_price = $(e).data('start-price');
        var maximum_bid_amount = $(e).data('max-bid');
        var bid_amount = $('#bid_amount').val();
        var current_price = $('#current_price<?= $item['item_id']; ?>').val();
        var your_bid_amount = Number(bid_amount) + Number(current_price);
        if (isNaN(your_bid_amount)) {
            your_bid_amount = 0;
            var total_bid = '';
        }else{
            your_bid_amount = numberWithCommas(your_bid_amount);
            var total_bid = "<?= $this->lang->line('bid_amount_now');?>" + your_bid_amount;
        }
        swal({
            title: "<?= $this->lang->line('you_are_placing');?>"+" AED " +numberWithCommas(bid_amount)+ " <?= $this->lang->line('increment');?>",
            text: total_bid,
            type: "info",
            showCancelButton: true,
            cancelButtonClass: 'btn-default btn-md waves-effect',
            confirmButtonClass: 'btn-danger btn-md waves-effect waves-light',
            confirmButtonText: "<?= $this->lang->line('confirm');?>",
            cancelButtonText: "<?= $this->lang->line('cancel');?>"
        },function(isConfirm) {
            if (isConfirm) {
                //console.log('itemID :'+itemID+' auctionid :'+auctionid+' lotno :'+lotno+' sellerid :'+sellerid+' minimum_bid_price :'+minimum_bid_price+' bid_amount :'+bid_amount+' maximum_bid_amount :'+maximum_bid_amount);   
                if (bid_amount < minimum_bid_price) {
                    $('.proxy-form .your-bid li p').fadeTo(0, 500).css('display','block !important');
                    window.setTimeout(function() {
                        $(".proxy-form .your-bid li p").fadeTo(500, 0).css('display','none');
                    }, 3000);
                }else{

                    if (your_bid_amount > maximum_bid_amount) {
                        new PNotify({
                            type: 'error',
                            addclass: 'custom-error',
                            text: "<?= $this->lang->line('insufficient_balance');?>",
                            styling: 'bootstrap3',
                            title: "<?= $this->lang->line('error');?>"
                        });
                    }else{
                        $.ajax({
                            type: 'post',
                            url: '<?= base_url('auction/OnlineAuction/placebid'); ?>',
                            data: {'item_id':itemID,'auction_id':auctionid,'seller_id':sellerid,'bid_amount':bid_amount,'start_price':start_price, [token_name] : token_value},
                            success: function(data){
                                console.log(data);
                                var response = JSON.parse(data);
                                if(response.status == 'success'){
                                    
                                    $('.current-btn p').html(response.current_bid);
                                    $('#bid_amount').val('');
                                    $('#bid_btn').attr("disabled" , "disabled");
                                }


                                if(response.status == 'fail'){
                                    new PNotify({
                                        text: ""+response.msg+"",
                                        type: 'error',
                                        addclass: 'custom-error',
                                        title: "<?= $this->lang->line('error');?>",
                                        styling: 'bootstrap3'
                                    });
                                }

                                if(data == '0'){
                                    window.location.replace('<?= base_url("home/login"); ?>');
                                }
                                if(response.status =='soldout'){
                                    new PNotify({
                                        text: ""+response.msg+"",
                                        type: 'error',
                                        addclass: 'custom-error',
                                        title: "<?= $this->lang->line('error');?>",
                                        styling: 'bootstrap3'
                                    });
                                    $('.your-bid').hide();
                                }
                            }
                        });
                    }
                }
                window.setTimeout(function() 
                {
                    $(".alert").fadeTo(500, 0).slideUp(500, function()
                    {
                        $(this).remove(); 
                    });
                }, 5000);
            }
        });
    }

    function initialize() {
        var lat = "<?php echo $item['item_lat']; ?>";
        var lng = "<?php echo $item['item_lng']; ?>";
        var latlng = new google.maps.LatLng(lat,lng);
        var address = "<?php echo $item['item_location']; ?>";
        var map = new google.maps.Map(document.getElementById('map'), {
            center:new google.maps.LatLng(lat,lng),
            zoom: 13
        });
        
        var marker = new google.maps.Marker({
            map: map,
            position: latlng,
            draggable: false,
            anchorPoint: new google.maps.Point(0, -29)
        });
        var infowindow = new google.maps.InfoWindow();   
        google.maps.event.addListener(marker, 'click', function() {
          var iwContent = '<div id="iw_container">' +
          '<div class="iw_title"><b>Location</b> : '+ address +' </div></div>';
          // including content to the infowindow
          infowindow.setContent(iwContent);
          // opening the infowindow in the current map and at the current marker location
          infowindow.open(map, marker);
        });
    }

    function bidButtons(id) {
        var x = $(id).val();
        var y = '<?= $item['minimum_bid_price'] ?>';
        var z = $('#bid_amount').val();
        if(!z){
            z=0;
        }
        $("#bid_amount").val(Number(z) + Number(x));
        let bid = $('#bid_amount').val();

        if(bid != '' && bid >= Number(y)){
            $('#bid_btn').removeAttr("disabled");
        }else{
            $('#bid_btn').attr("disabled" , "disabled");
        }
    }

    $(document).on("input" , "#bid_amount" , function(){
        var y = '<?= $item['minimum_bid_price'] ?>';
        let bid = $('#bid_amount').val();
        if(bid != '' && bid >= Number(y)){
            $('#bid_btn').removeAttr("disabled");
        }else{
            $('#bid_btn').attr("disabled" , "disabled");
        }
    });

    $(document).on("input" , "#auto_limit" , function(){
        var cbp = $('#current_price<?= $item['item_id']; ?>').val();
        let bids = $('#auto_limit').val();

        if(bids != '' && bids >= Number(cbp)){
            $('#start_bid').removeAttr("disabled");
        }else{
            $('#start_bid').attr("disabled" , "disabled");
        }
    });

    // $(document).on("input" , "#bid_amount" , function(){
    //     let bid_button = $('#bid_amount').val();

    //     if(bid_button != ''){
    //         $('#bid_btn').removeAttr("disabled");
    //     }else{
    //         $('#bid_btn').attr("disabled" , "disabled");
    //     }
    // });

    function autoBiding(e) {

        var cbp = $('#current_price<?= $item['item_id']; ?>').val();
        var item = "<?= $item['item_id']; ?>";
        var loginUser = "<?= ($user) ? $user->username : ''; ?>";
        var limit = "<?= $biding_limit; ?>";
        
        var auctionItem_id ="<?= $item['auction_item_id']; ?>";
        var auction = "<?= $item['auction_id']; ?>";
        var bids = $('#auto_limit').val();
        var increment = "<?= $item['minimum_bid_price']; ?>";
        var start_price = "$item['bid_start_price']; ?>";
        var sellerid = "<?= $item['item_seller_id']; ?>";

        if(bids != ''  && bids > Number(cbp)){
            $('#start_bid').removeAttr("disabled");
            if ( Number(limit) <= Number(bids)) {
                swal("<?= $this->lang->line('error');?>", "<?= $this->lang->line('limit_exceeds');?>", "error");
            }else{
                // $('#bid_amount').val($('#auto_increment').val());
                // $('#bid_btn').click();
                $.ajax({
                    type: 'post',
                    url: "<?= base_url('auction/OnlineAuction/placebid');?>",
                    data: {'bid_limit':bids,'bid_amount':increment, 'auction_id':auction,'item_id':item,'auction_item_id':auctionItem_id,'seller_id':sellerid,'start_price':start_price, [token_name] : token_value},

                    success: function(data)
                    {

                        var response = JSON.parse(data);
                        if(response.auto_bid_msg ='true') {
                            swal("<?= $this->lang->line('success');?>", "<?= $this->lang->line('auto_bid_activated');?>", "success");
                            window.location.reload(true);
                            // return false;
                        }
                        else{
                            swal("<?= $this->lang->line('error');?>", "<?= $this->lang->line('auto_bid_activated_not');?>", "error");
                        }
                    }
                });
            }


            // if (userId && userId == loginUser) {
                //auto bid////=
            // }else{
            //  //pusher code/////
                // $('#bid_amount').val($('#auto_increment').val());
                // $('#bid_btn').click();
            // }
                //place bid
                
        }else{
            swal("Error", "<?= $this->lang->line('bid_limit_should_greater_than');?> " +numberWithCommas(cbp), "error");
            $('#start_bid').attr("disabled" , "disabled");
        }
    }

    $('#loginBid').on('click', function(event){
        event.preventDefault();

        var validation = false;
        var language = '<?= $language; ?>';
        selectedInputs = ['email','password1'];
        validation = validateFields(selectedInputs,language);

        if (validation == true) {
            var form = $(this).closest('form').serializeArray();

            form[form.length] = { name: [token_name] , value: token_value};
            $.ajax({
                type: 'post',
                url: '<?= base_url('home/login_process'); ?>',
                data: form,
                success: function(msg){
                    console.log(msg);
                    var response = JSON.parse(msg);
                      
                    if(response.error == true){
                        $('#error-msgs').html('<div class="alert alert-danger alert-dismissible" id="valid-msgs"><button type="button" class="close" data-dismiss="alert">&times;</button>'+response.msg+'</div>');
                    }

                    if(response.msg == 'success'){
                        var rurl = '<?= isset($_GET['rurl']) ? $_GET['rurl'] : ''; ?>';
                        var id = '<?php echo $this->uri->segment(4);?>';
                        var id2 = '<?php echo $this->uri->segment(5);?>';
                        if (rurl == '') {
                            window.location.replace('<?= base_url('auction/online-auction/details/'); ?>'+id +'/'+id2);
                        }else{
                            window.location.replace(rurl);
                        }
                    }
                    // else {
                    //        window.location.replace('<?= base_url('home/login/'); ?>');
                    //    }
                }
            });
        }
    });

    //Enable pusher logging - don't include this in production
    Pusher.logToConsole = true;

    var pusher = new Pusher("<?= $this->config->item('pusher_app_key'); ?>", {
      cluster: 'ap1'
    });
    var user_id = "<?= isset($user) ? $user->id : ''; ?>";
    var channel = pusher.subscribe('ci_pusher');
    channel.bind('my-event', function(push) {
      // alert(JSON.stringify(push));
        if (push.status) {
            if (user_id == push.user_id) {
                $('.bid-div'+push.item_id).show();
                $('.hideText'+push.item_id).hide();
            }
        } else {
            var no_of_bids = parseInt($('#no_of_bids'+push.item_id).text());
            var no_of_bids = no_of_bids + 1;
            $('#no_of_bids'+push.item_id).html(no_of_bids);
            $('.'+push.item_id).html('AED '+numberWithCommas(push.bid_amount));
            $('#your-bid-amount'+push.item_id).html('AED '+numberWithCommas(push.bid_amount)+' + ');
            $('#current_price'+push.item_id).val(push.bid_amount);
            if (user_id == push.user_id) {
                $('#bid_amount').val('');
                $('#bid_user_name'+push.item_id).html('<h style="color:green"><?= $this->lang->line('higher_bider');?>.</h>');
                new PNotify({
                    text: "<?= $this->lang->line('bid_success');?>",
                    type: 'success',
                    addclass: 'custom-success',
                    title: "<?= $this->lang->line('success_');?>",
                    styling: 'bootstrap3'
                });
            } else {
                var user_have_bid = '<?= $user_have_bid->num_rows(); ?>';
                if (user_have_bid > 0) {
                    $('#bid_user_name'+push.item_id).html("<?= $this->lang->line('not_higher_bider');?>");
                } else {
                    $('#bid_user_name'+push.item_id).html('');
                }
            }
        }
    });
</script>

<?php 
if($language == 'arabic'){
    $ln = '&region=EG&language=ar';
}else{
    $ln = '';
}
?>
<script src="https://maps.googleapis.com/maps/api/js?key=<?= $this->config->item('google_map_key'); ?>&callback=initialize<?= $ln; ?>"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets_admin/sweetalert/dist/sweetalert.min.js"></script>