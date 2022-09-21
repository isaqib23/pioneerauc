<?php
$id = $this->uri->segment(5);
$min = $this->db->get_where('auction_items',['item_id'=>$id])->row_array();
?>


<!-- <style>
#hideText{
    color: green;
font-size: x-large;
text-align: center;
border: 4px solid green;
width: 272px;
align-content: center;
text-align: center;
margin-left: 104px;
text-shadow: 0 0 1px #20b347;
}
</style> -->
<style type="text/css">
    .auto-biding .btn.btn-default {width: auto; max-width: 200px;}
    .auto-biding ul .btn {width: 70%; max-width: 100px; }
    .auto-biding ul {justify-content: center;}
    .auto-biding li {width: 60px !important; padding: 0 !important;}
    .auto-biding li:not(:last-child){margin-right: 12px !important;}
</style>
<style>
  .pro-box em {display: block; font-style: normal;}
  .pro-box .syotimer__body {display: flex; flex-wrap: wrap; justify-content: center; margin-top: 4px; padding: 0;}
</style>

<script type="text/javascript">
    $(document).ready(function(){
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

    <script type="text/javascript" src="<?php echo base_url();?>assets_admin/sweetalert/dist/sweetalert.min.js"></script>
    <!-- <ipt type="text/javascript" src="<?php echo base_url();?>assets_admin/js/deletesweetalert.js"></script> -->

<?php
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


?>
    <div class="auction-list" style="padding: 14px; margin-bottom: 2px;">
        <div class="container">
            <div class="wrapper">
                <div class="left d-flex">
                    <?php if($active_auction_categories):
                     ?>
                    <?php foreach ($active_auction_categories as $key => $value):
                    ?>
                    <div class="icon">
                        <?php 
                        if(isset($value['item_count']) && !empty($value['item_count'])){
                        ?>
                        <span class="counter" style="background-color: black"><?php echo $value['item_count'];?></span>
                        <?php }?>
                        <?php if(isset($value)){
                        $file = $this->db->get_where('files', ['id' => $value['category_icon']])->row_array(); ?>
                        <input type="hidden" name="old_file" value="<?= $file['name']; ?>">
                        <img src="<?= base_url($file['path'] . $file['name']); ?>" alt="">
                         <?php }?>
                         <?php if (!empty($value['auction_id'])) {?>
                        <a href="<?= base_url('auction/online-auction/'.$value['auction_id']); ?>" class="link"></a>
                    <?php }?>
                    </div>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <div class="right">
                    <a href="<?= base_url('contact-us')?>" class="btn btn-default">Contact Us</a>
                </div>
            </div>
        </div>
    </div>
    <hr>
<div class="main gray-bg detail-page" style="background: #eeeff2">
    <div class="auction-list">
        <div class="container">
            <div class="content-box">
                <div class="row with-banner col-gap-20">
                    <div class="left col-md-8">
                        <div class="pro-slider">
                            <div class="main">
                            <?php foreach ($images as $key => $value) { ?>
                                <div class="image">
                                    <img src="<?= base_url('uploads/items_documents/').$item['id'].'/'.$value['name']; ?>">
                                </div>
                            <?php } ?>
                            </div>

                              <div class="thumb">
                                 <?php foreach ($images as $key => $value) { ?>
                                <div class="image">
                                    <img src="<?= base_url('uploads/items_documents/').$item['id'].'/'.$value['name']; ?>"alt="">
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <div class="right col-md-4">
                        <div class="add-banner">
                            <img src="<?= NEW_ASSETS_IMAGES?>detail-banner.png" alt="">
                        </div>
                    </div>
                </div>

                <div class="row stack-on-1200 margin-top-30">
                    <div class="col-md-6">
                        <div class="gray-box desc">
                            <ul class="h-list">
                                <li><h4><?= $item['item_name'];?></h4></li>
                                <li>
                                    <span class="label"><img src="<?= NEW_ASSETS_IMAGES?>eye-icon.png" alt=""><?= $visit_count; ?></span>
                                    <?php if (isset($fields[0]['data-value'])) {  ?>
                                    <span class="label"><img src="<?= NEW_ASSETS_IMAGES?>calendar-icon.png" alt=""><?= $fields[0]['data-value']; ?></span>
                                    <?php } ?>   
                                    <?php if (isset($fields[0]['data-value'])) {  ?> 
                                    <span class="label"><img src="<?= NEW_ASSETS_IMAGES?>metter-icon.png" alt=""><?= number_format($fields[0]['data-value']);?>&nbsp<?php echo $item['mileage_type']?> </span>
                                    <?php } ?>
                                </li>
                            </ul>
                            <p class="bid-info">
                                Current Bid
                                <span  id="CBP" class="<?= $item['item_id']; ?>">AED <?= (isset($heighest_bid_data) && !empty($heighest_bid_data['current_bid'])) ? $heighest_bid_data['current_bid'] : $item['bid_start_price']; ?></span>
                                <i>(AED<?= $item['minimum_bid_price'];?> Min. Increment)</i>
                            </p>
                            <?php
                             // $auction_id =$this->uri->se     
                                 $user_have_bid = $this->db->get_where('bid', ['auction_id' => $auction_id,'user_id' => $user_id,'item_id' => $item_id]); ?>
                                <p class="bidder-name">Bidding Info: 
                                    <span style="color: red;" id="bid_user_name<?= $item['item_id']; ?>">
                                        <?php if ($user_have_bid->num_rows() > 0) { ?>
                                            <?php 
                                                if (isset($heighest_bid_data) && !empty($heighest_bid_data) && $this->session->userdata('logged_in'))  {
                                                    if($heighest_bid_data['user_id'] == $user_id) {
                                                        echo 'You are the highest bidder.'; 
                                                    }else{
                                                        echo 'You are not the highest bidder.';     
                                                    } 
                                                }
                                                ?>
                                        <?php } ?>
                                    </span>
                                </p> 
                        </div>
                    </div>
                    <?php if ($this->session->userdata('logged_in')) {
                    ?>
                    <div class="col-md-6">
                        <div class="gray-box bid-info-wrapper">
                            <div class="bid-information">
                                <h4>
                                    Bid Information
                                    <span>Bids<i id="no_of_bids<?= $item['item_id']; ?>"><?= (!empty($bid_count)) ? $bid_count : '0'; ?></i></span>
                                </h4>
                                <!-- <p>Your Bid * <span>On Approval</span></p> -->
                            </div>
                             <div id="hideText" class="hideText<?= $item['item_id']; ?>">
                             </div>
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
                                    } ?>
                             <?php if($deposit_by_user == true): ?>
                             <div class="your-bid bid-div<?= $item['item_id']; ?>" id="hidebid_section">
                            <ul class="h-list place-bid" id="hidebid_section">
                                <li>
                                    <h6 id="your-bid-amount<?= $item['item_id']; ?>"><?= (isset($heighest_bid_data) && !empty($heighest_bid_data['current_bid'])) ? $heighest_bid_data['current_bid'] : $item['bid_start_price']; ?> + &nbsp
                                </li>
                                <input type="hidden" name="current_price" id="current_price<?= $item['item_id']; ?>" value="<?= (isset($heighest_bid_data) && !empty($heighest_bid_data['current_bid'])) ? $heighest_bid_data['current_bid'] : $item['bid_start_price']; ?>">
                                <li>
                                    <input type="number" min="0" placeholder="Enter here" class="form-control" id="bid_amount" name="bid_amount">
                                    <!-- <p>You are the lower bidder, please higher</p> -->
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
                                            class="btn btn-default" id="bid_btn">PLACE BID</button>
                                </li>
                            </ul>
                            <ul class="bid-labels" id="hide_lable">
                                <li>
                                    <button  id="myBtn" class="btn btn-primary btn-counter bidder-button" onclick="bidButtons(this)" value="100">AED 100</button>
                                </li>
                                <li>
                                    <button  id="myBtn" class="btn btn-primary btn-counter bidder-button" onclick="bidButtons(this)" value="500">AED 500</button>
                                </li>
                                <li>
                                    <button  id="myBtn" class="btn btn-primary btn-counter bidder-button" onclick="bidButtons(this)" value="1000">AED 1000</button>
                                </li>
                                <li>
                                    <button  id="myBtn" class="btn btn-primary btn-counter bidder-button" onclick="bidButtons(this)" value="2000">AED 2000</button>
                                </li>
                                <li>
                                    <button  id="myBtn" class="btn btn-primary btn-counter bidder-button" onclick="bidButtons(this)" value="5000">AED 5000</button>
                                </li>
                            </ul>
                            <!-- <button class="btn btn-default auto-bid">Auto Biding</button> -->
                            
                            <div id="hideA">
                            <a href="#" class=" auto-bid" style="color: red"><h5>Show/Hide Auto Biding</h5></a>
                            <div class="bid-form" id="autohide">
                                <ul class="h-list place-bid">
                                    <li>
                                        <input type="number" class="form-control" placeholder="Your Limit" id="auto_limit"  name="auto_limit">
                                    </li>
                                    <li>
                                        <button class="btn btn-default"
                                             id="start_bid"
                                             onclick="autoBiding(this)"
                                             >Start</button>
                                             <span>Increment (AED <?= $item['minimum_bid_price'];?>)</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                     </div>
                     <?php endif; ?>
                     <?php if($item['security'] == 'yes' && !empty($item['deposit']) && $deposit_by_user == false): 
                            ?>
                            <div class="report">
                                <h2 class="form-title">DEPOSIT</h2>
                                <ul>
                                    <li>
                                        <p class="toltip">This item required extra deposit of amount <?= $item['deposit']; ?></p>
                                    </li>
                                    <li>
                                        <a class="btn btn-default" href="<?= base_url('customer/item_deposit?item_id=').$item['auction_item_id'].'&auction_id='.$item['auction_id'].'&rurl='.$rurl; ?>" class="file-link" >Deposit Now</a>
                                    </li>
                                </ul>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                
                <?php $biding_limit = $balance*10;?>
            <?php } else{?>
                <?php $biding_limit = 0;?>
                 <div class="col-md-6">
                        <div class="gray-box bid-info-wrapper">
                            <h2>Please login first to place a bid</h2>
                            <hr>
                            <form>
                                <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
                                <div class="bid-formm">
                                    <ul class="h-list place-bid">
                                        <li>
                                            <input type="text" class="form-control" placeholder="Email/Password"   name="email1">
                                            <span class="valid-error text-danger email1-error"></span>
                                        </li>
                                        <li>
                                             <input type="Password" class="form-control" placeholder="Enter Password"   name="password1">
                                             <span class="valid-error text-danger password1-error"></span>
                                        </li>
                                    </ul>
                                </div>
                                <div style="width: 302px;" id="recaptcha1" class="g-recaptcha" data-sitekey="<?= $this->config->item('captcha_key');?>" data-expired-callback="recaptchaExpired"></div>
                                <br>
                                <span class=" text-warning" id="error-msgs" ></span>
                                <button type="button" id="loginBid" class="btn btn-default">Login</button>
                                <a href="<?php echo base_url('home/register');?>" type="button" id="" class="">Register</a>
                            </form>
                        </div>
                    </div>
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
                        jQuery(document).ready(function ($) {
                            document.getElementById("loginBid").disabled = true;
                        });

                    </script>
           <?php }?>
                </div>

                <div class="row margin-top-30">
                    <div class="col left">
                        <div class="detail-tabs">
                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="detail-tab" data-toggle="tab" href="#detail" role="tab" aria-controls="detail" aria-selected="true">Detail</a>
                                </li>
                                <?php if (! empty($item['item_model'])) { 
                                $model = $this->db->get_where('item_models', ['id' => $item['item_model']])->row_array(); 
                                $make = $this->db->get_where('item_makes', ['id' => $item['item_make']])->row_array(); 
                                ?>
                                <li class="nav-item">
                                    <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">MODEL</a>
                                </li>
                                <?php } ?>
                                <li class="nav-item">
                                    <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">LOCATION</a>
                                </li>
                            </ul>
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show active" id="detail" role="tabpanel" aria-labelledby="detail-tab">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <?php $i=0; 
                                        foreach ($fields as $key => $value) {
                                        if (!empty($value['data-value'])) {
                                        $i++;
                                        if($i<= 5) { ?>
                                            <ul>
                                                <li><?= $value['label']; ?>:</li>
                                                <li><?= $value['data-value']; ?></li>
                                            </ul>
                                        <?php } } }?>
                                        </div>
                                        <div class="col-md-6">
                                           <?php $i=0; 
                                        foreach ($fields as $key => $value) {
                                        if (!empty($value['data-value'])) {
                                        $i++;
                                        if($i> 5 && $i<= 10) { ?>
                                            <ul>
                                                <li><?= $value['label']; ?>:</li>
                                                <li><?= $value['data-value']; ?></li>
                                            </ul>
                                        <?php } } } ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <ul>
                                            <li>Make:</li>
                                            <li><?= $make['title']; ?></li>
                                        </ul>
                                        <ul>
                                            <li>Model:</li>
                                            <li><?= $model['title']; ?></li>
                                        </ul>
                                    </div>
                                    <div class="col-lg-6">
    
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                                <div class="row">
                                    <div id="map" style="width: 100%; height: 300px;"></div> 
                                    <script src="<?php echo base_url(); ?>assets_admin/js/jquery.geocomplete.js"></script>
                                    <div class="col-lg-12">
                                        <ul>
                                            <li>Location:</li>
                                            <li><?= $item['item_location']; ?></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                                <!-- <div class="tab-pane fade" id="Location" role="tabpanel" aria-labelledby="Location-tab">
                                    
                                </div> -->
                            </div>
                        </div>
                    </div>
                    <div class="col right">
                        <div class="reports">
                            <h3>Reports</h3>
                            <ul class="h-list">
                                <?php $item_test_report = $this->db->get_where('files', ['id' => $item['item_test_report']])->row_array(); ?>
                                <li>
                                    <a href="<?= base_url().$item_test_report['path'].$item_test_report['name']; ?>" target="_blank" class="btn btn-gray" download class="file-link">Condition Report</a>
                                </li>
                                <li>
                                    <a href="<?= base_url().$item_test_report['path'].$item_test_report['name']; ?>" class="btn btn-gray" download class="file-link">Test Report</a>
                                </li>
                            </ul>
                            <p>If you require further assistance, please contact us</p>
                            <ul class="h-list">
                                <li>
                                    <a href="#" class="btn btn-gray">Enquire</a>
                                </li>
                                <li>
                                    <a href="#" class="phone-link"><img src="<?= NEW_ASSETS_IMAGES?>phone-icon2.png" alt=""><?= $contact['phone']; ?></a>
                                </li>
                            </ul>
                            <a href="<?= base_url('customer/terms'); ?>" class="terms-link">
                                Sales Information fees & Terms <img src="<?= NEW_ASSETS_IMAGES?>angle-right.png" alt="">
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <section class="related-items">
                <h4 class="inner-title">Related Products</h4>
                <div class="slider">
                    <?php if (!empty($related_items)) {
                    foreach ($related_items as $key => $related_item) {
                        $bid_end_time = strtotime($related_item['bid_end_time']);
                        $bid_info = $this->db->where('item_id',$related_item['item_id'])->where('auction_id',$related_item['auction_id'])->order_by('id', 'desc')->from('bid')->get()->row_array();
                        $visit_count = $this->db->where('item_id',$related_item['item_id'])->where('auction_id',$related_item['auction_id'])->from('online_auction_item_visits')->count_all_results();
                        $item_images_ids = explode(',', $related_item['item_images']);
                        $images = $this->db->where_in('id', $item_images_ids)->get('files')->row_array();
                      ?>
                    <div class="col">
                        <div class="pro-box">
                            <div class="image">
                                <img src="<?= base_url('uploads/items_documents/').$related_item['item_id'].'/'.$images['name']; ?>" alt="">
                            </div>
                            <div>
                                <h6><a href="#"><?= $related_item['item_name']; ?> </a></h6>
                                <p><?= $related_item['item_detail']; ?></p>
                                <div class="button-row">
                                    <a href="<?= base_url('auction/online-auction/details/').$related_item['auction_id'].'/'.$related_item['item_id']; ?>" class="btn btn-default sm">See Details</a>
                                </div>
                            </div>
                            <div class="table-wrapper">
                                <table cellpadding="0" cellpadding="0">
                                    <tr>
                                        <td>
                                            <p>
                                                Current Price
                                                <span>AED <?= (!empty($bid_info)) ? $bid_info['bid_amount'] : $related_item['bid_start_price']; ?></span>
                                            </p>
                                        </td>
                                        <td>
                                            <p>Time Remaining<em id="expiryy-timer<?= $related_item['auction_item_id']; ?>"> 
                                                   <script>
                                                   $('#expiryy-timer<?= $related_item['auction_item_id'];?>').syotimer({
                                                    year: <?= date('Y', $bid_end_time); ?>,
                                                    month: <?= date('m', $bid_end_time); ?>,
                                                    day: <?= date('d', $bid_end_time); ?>,
                                                    hour: <?= date('H', $bid_end_time); ?>,
                                                    minute: <?= date('i', $bid_end_time); ?>,
                                                  });
                                              </script>
                                              </em>
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p>
                                                Min  Increment
                                                <span>AED <?= $related_item['minimum_bid_price'];?></span>
                                            </p>
                                        </td>
                                        <td>
                                            <?php
                                              $time= $related_item['bid_end_time'];
                                              $new_time = date('h:i A', strtotime($time));
                                            ?>
                                            <p>
                                               End Time
                                               <span class="ltr">
                                                    <?php echo $new_time;?>
                                               </span>
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p>
                                                Lot#
                                                <span><?php if(isset($related_item) && !empty($related_item)) { echo $related_item['lot_no']; }else{ echo "N/A";}?></span>
                                            </p>
                                        </td>
                                        <td>
                                            <!-- <?php 
                                            $millage = $this->db->get_where('milleage',['item_id' => $related_item['item_id']])->row_array();
                                             ?> -->
                                            <p>
                                               Odometer
                                               <span>
                                                   <?php if(isset($related_item['mileage']) && !empty($related_item['mileage'])) { echo $related_item['mileage']; }else{
                                                    echo "N/A";
                                                   }?>
                                               </span>
                                            </p>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <ul class="h-list">
                                <li>
                                    <p>Bids#<span><?= (!empty($visit_count)) ? $visit_count : '0'; ?></span></p>
                                </li>
                                <li>
                                    <div class="actions">
                                        <a href="#" class="btn btn-primary sm">Add List</a>
                                        <a href="<?= base_url('auction/online-auction/details/').$related_item['auction_id'].'/'.$related_item['item_id']; ?>" class="btn btn-default sm">Bid Now</a>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <?php } }?>
                </div>
            </section>
        </div>
    </div>
    <section class="our-app">
            <div class="container">
                <div class="inner">
                    <div class="row align-items-center">
                        <div class="col-lg-8">
                            <h3>Download the Pioneer app for<br> iOS or Android</h3>
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

<script src="<?php echo base_url(); ?>assets_admin/vendors/pnotify/dist/pnotify.js"></script>
<script src="<?php echo base_url(); ?>assets_admin/vendors/pnotify/dist/pnotify.buttons.js"></script>
<script src="<?php echo base_url(); ?>assets_admin/vendors/pnotify/dist/pnotify.nonblock.js"></script>



<script src="https://js.pusher.com/6.0/pusher.min.js"></script>

<script>  
    $(document).ready(function(){
        $('#eye').tooltip({
             title:'users visit this item!',
             placement:'bottom'
        });
        // $('[data-toggle="tooltip"]').tooltip({
        //   title:'Hooray!'
        // });

        
        $('.fa-heart-o').tooltip({
             title:'Add to favorite!',
             placement:'bottom'
        });

        $('.fa-heart').tooltip({
             title:'Remove from favorite!',
             placement:'bottom'
        });
    });
    
    

    function doFavt(e){
        //alert('dona done');
        var heart = $(e);
        var itemID = $(e).data('item');
        $.ajax({
            type: 'post',
            url: '<?= base_url('auction/OnlineAuction/do_favt'); ?>',
            data: {'item_id':itemID},
            success: function(msg){
                console.log(msg);
                //var response = JSON.parse(msg);
                if(msg == 'do_heart'){
                    heart.find('.fa-heart-o').addClass('fa-heart').removeClass('fa-heart-o');   
                    $('.fa-heart').tooltip({
                        title:'Remove from favorite!',
                        placement:'bottom'
                    });     
                }
                if(msg == 'remove_heart'){
                    heart.find('.fa-heart').addClass('fa-heart-o').removeClass('fa-heart');
                    $('.fa-heart-o').tooltip({
                        title:'Add to favorite!',
                        placement:'bottom'
                    });
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
    console.log('your bid'+bid_amount);
    console.log('current price'+current_price);
    var your_bid_amount = Number(bid_amount) + Number(current_price);
    
    swal({
        title: "Are you sure to place a bid?",
        text: "Your bid amount amount will be " +(Number(bid_amount)+ Number(current_price)),
        type: "info",
        showCancelButton: true,
        cancelButtonClass: 'btn-default btn-md waves-effect',
        confirmButtonClass: 'btn-danger btn-md waves-effect waves-light',
        confirmButtonText: 'Confirm!'
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
                        text: "Insufficient Balance! Please Recharge.",
                        styling: 'bootstrap3',
                        title: 'Error'
                    });
                }else{
                    $.ajax({
                        type: 'post',
                        url: '<?= base_url('auction/OnlineAuction/placebid'); ?>',
                        data: {'item_id':itemID,'auction_id':auctionid,'seller_id':sellerid,'bid_amount':bid_amount,'start_price':start_price},
                        success: function(data){
                            console.log(data);
                            var response = JSON.parse(data);
                            if(response.status == 'success'){
                                
                                $('.current-btn p').html(response.current_bid);
                                $('#bid_amount').val('');
                            }


                            if(response.status == 'fail'){
                                new PNotify({
                                    text: ""+response.msg+"",
                                    type: 'error',
                                    addclass: 'custom-error',
                                    title: 'Error',
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
                                    title: 'Error',
                                    styling: 'bootstrap3'
                                });
                                $('.your-bid').hide();
                            }
                  //    //


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
            $('.'+push.item_id).html(push.bid_amount);
            $('#your-bid-amount'+push.item_id).html(push.bid_amount+' + ');
            $('#current_price'+push.item_id).val(push.bid_amount);
            if (user_id == push.user_id) {
                $('#bid_user_name'+push.item_id).html('You are the highest bidder.');
                new PNotify({
                    text: "Bid successfully.",
                    type: 'success',
                    addclass: 'custom-success',
                    title: 'Success',
                    styling: 'bootstrap3'
                });
            } else {
                var user_have_bid = '<?= $user_have_bid->num_rows(); ?>';
                if (user_have_bid > 0) {
                    $('#bid_user_name'+push.item_id).html('You are not the highest bidder.');
                } else {
                    $('#bid_user_name'+push.item_id).html('');
                }
            }
        }
    });


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
</script>

<script>

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
</script>

<script>
$(document).ready( function() {
    var hidebtn ="<?= $hide_auto_bid['auto_status'];?>";
    // alert(hidebtn);
    console.log(hidebtn);
    if(hidebtn == "start"){
        //console.log('start if ', hidebtn);
       $('#hidebid_section').hide();
       // $('#bid_amount').prop("readonly",true);
       var incText = "<?= ($min['minimum_bid_price']) ? $min['minimum_bid_price'] : 0; ?>";
       
       $('#hideText').html('<div><h2>Auto Biding Has Started</h2><span>Increment (AED '+incText+' )</span></div>');
       // $('#hide_lable').hide();
       // $('#hideA').hide();
   }
   else{
    //console.log('else ', hidebtn);
    $('#hidebid_section').show();
    $('#autohide').show();
    $('#hideText').hide(); 
    $('#hide_lable').show();  
    $('#hideA').show();
   }
});
</script>

<script>
function autoBiding(e) {

    var cbp = $('#current_price<?= $item['item_id']; ?>').val();
    var item = "<?= $item['item_id']; ?>";
    var loginUser = "<?= $user->username; ?>";
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
            swal("Error!", "Your Deposit Limit Exceeds.", "error");
        }else{
            // $('#bid_amount').val($('#auto_increment').val());
            // $('#bid_btn').click();
            $.ajax({
                type: 'post',
                url: "<?= base_url('auction/OnlineAuction/placebid');?>",
                data: {'bid_limit':bids,'bid_amount':increment, 'auction_id':auction,'item_id':item,'auction_item_id':auctionItem_id,'seller_id':sellerid,'start_price':start_price},

                success: function(data)
                {

                    var response = JSON.parse(data);
                    if(response.auto_bid_msg ='true') {
                        swal("Success!", "Auto bid is activated.", "success");
                        window.location.reload(true);
                        // return false;
                    }
                    else{
                        swal("error!", "Auto bid is not activated.", "error");
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
            swal("Error", "Bid limit should be greater than "+cbp, "error");
            $('#start_bid').attr("disabled" , "disabled");
    }
}


    $(document).on("input" , "#auto_limit" , function(){
        var cbp = $('#current_price<?= $item['item_id']; ?>').val();
        let bids = $('#auto_limit').val();

        if(bids != '' && bids >= Number(cbp)){
            $('#start_bid').removeAttr("disabled");
        }else{
            $('#start_bid').attr("disabled" , "disabled");
        }
    });

</script>

<script src="https://maps.googleapis.com/maps/api/js?key=<?= $this->config->item('google_map_key'); ?>&callback=initialize"></script>
<style type="text/css">
    .five-items {display: flex; align-items: center; flex-wrap: wrap;}
    .five-items li {width: 130px !important; padding-right: 0 !important; margin: 0 10px 7px 0;}
    .five-items .btn.btn-primary  {font-size: 14px !important}
</style>

<script src="https://www.google.com/recaptcha/api.js?onload=myCallBack&render=explicit" async defer></script>


<!-- <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script> -->

 <script type="text/javascript">

    $('#loginBid').on('click', function(event){
      event.preventDefault();

      var validation = false;
      var errorMsg = "This value is required.";
      var errorClass = ".valid-error";
      var e;
      selectedInputs = ['email1','password1'];

      $.each(selectedInputs, function(index, value){
        e = $('input[name='+value+']');

        if(value == 'email1'){
          if( ! isEmail(e.val())){
            e.focus().siblings(errorClass).html('Email is not valid.').show();
            validation = false;
            return false;
          }
        }

        if(($.trim(e.val()) == "") || (e.val() == null) || (typeof e.val() === 'undefined')){
            e.focus();
            $('.'+value+'-error').html(errorMsg).show();
            validation = false;
            return false;
        }else{
            validation = true;
            $('.'+value+'-error').html('').hide();
        }
      });

        

      if (validation == true) {
        var form = $(this).closest('form').serializeArray();

        //console.log(form);
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
        </script>