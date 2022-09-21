<?php

//$id = $this->uri->segment(5);
//$min = $this->db->get_where('auction_items',['item_id'=>$item['id']])->row_array();

//$bid_end_time = strtotime($item['bid_end_time']);

// is user login or not and favorite item functionality
/*$favorite = 'fa-heart-o';
$is_logged_in = NULL;
if($this->session->userdata('logged_in')){
    $is_logged_in = $this->session->userdata('logged_in');
    $user = $is_logged_in;
    $favt = $this->db->get_where('favorites_items', ['item_id' => $item['id'],'user_id' => $user->id])->row_array();
    $favorite = ($favt) ? 'fa-heart' : 'fa-heart-o';
}*/
?>

<script>
    /*$(document).ready(function(){
        $("#start_bid").attr('disabled',true);
        $("#btn-toggle").on("click", function(){
            $(".auto-biding > ul").slideToggle();
        });
    });*/
</script>

<!-- PNotify and sweet alert -->
<link href="<?php echo base_url(); ?>assets_admin/vendors/pnotify/dist/pnotify.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>assets_admin/vendors/pnotify/dist/pnotify.buttons.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>assets_admin/vendors/pnotify/dist/pnotify.nonblock.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets_admin/sweetalert/dist/sweetalert.css">


<style>
    /*.detail-page {padding-top: 40px;}
    .detail-page.copy .bid-information h4 span {background: #ffffff !important;}
    .bid-information h4 {margin: 0 !important}
    .customform {margin-bottom: 15px;}
    .bid-auction .col-md-6 {margin-bottom: 5px;}*/

    .detail-page {padding-top: 40px;}
.detail-page.copy .bid-information h4 span {background: #ffffff !important;}
.bid-information h4 {margin: 0 !important}
.customform {margin-bottom: 15px;}
.bid-auction .col-md-6 {margin-bottom: 5px;}

.bid-info i {margin-bottom: 6px;}
.bid-info-wrapper {
    border-radius: 6px;
    background: #eeeff2;
    padding: 25px 10px 20px 10px;
    max-height: 300px;
    overflow: auto;
}

.bid-info {margin-bottom: 0;}
.bid-labels li .btn {padding: 9px 8px;}

.bid-info-wrapper h2 {font-size: 18px; margin: 0 0 8px 0; color: gray; font-weight: 700;}
.bid-info-wrapper h3 {font-size: 13px; margin: 0 0 20px 0; color: gray; font-weight: 500;}
.bid-info-wrapper p {font-family: var(--medium-font);
    font-weight: 600;
    font-size: 13px;
    line-height: 1.5;
    color: #8a8d97;
    margin: 20px 0 22px 0;}

  @media screen and (max-width: 1200px){
    .stack-on-1200 {margin-bottom: 40px;}
  } 
</style>

<div class="main gray-bg detail-page auction-page">
    <div class="container">
        <div class="content-box">
            <div class="row stack-on-1200 col-gap-20">
                <div class="col left">
                    <div class="pro-slider"> 
                        <?php
                        if(isset($item['item_images']) && !empty($item['item_images'])):
                            $item_images_ids = explode(',', $item['item_images']);
                            $images = $this->db->where_in('id', $item_images_ids)->get('files')->result_array();
                            if($images):
                            ?>
                                <div class="main" id="full_image">
                                    <?php foreach ($images as $key => $value): ?>
                                        <div class="image">
                                            <img src="<?= base_url('uploads/items_documents/').$item['id'].'/'.$value['name']; ?>" alt="">
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <div class="thumb" id="small_image">
                                    <?php foreach ($images as $key => $value): ?>
                                        <div class="image">
                                            <img src="<?= base_url('uploads/items_documents/').$item['id'].'/'.$value['name']; ?>" alt="">
                                        </div>
                                    <?php endforeach; ?>
                                </div>    
                            <?php 
                            endif;
                        else:
                        ?>
                            <div class="main" id="full_image">
                                <div class="image">
                                    <img src="<?= base_url('screen_assets/images/logo.jpg'); ?>" alt="Pioneer Auction Logo">
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="col right">
                    <div class="live-card">
                        <div class="head">
                            <?php
                            $auction_title = json_decode($auction['title']);
                            $item_name = json_decode($item['item_name']);
                            ?>
                            <h2 id="heading"><?= strtoupper($item_name->$language).' - '.$item['order_lot_no'] ?> <span dir="ltr"><?= date('h:i A', strtotime($auction['start_time'])); ?></span></h2>
                            <h3><?= $auction_title->$language; ?></h3>
                        </div>
                        <div class="image">
                            <iframe src="https://www.youtube.com/embed/iDO9J_3OVJ0" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"></iframe>
                        </div>
                        <ul class="counter">
                            <li class="active">
                                <p><?= $this->lang->line('current_bid');?><span></span></p>
                                <h5><i id="bidAmount" style="font-style: normal;"><?=number_format($last_bid['bid_amount'], 0, ".", ","); $last_bid['bid_amount']; ?> </i> <span> AED</span></h5>
                            </li>
                            <li> 
                                <p><?= $this->lang->line('lot');?><span id="lotNumber"><?= (isset($item['order_lot_no']) && !empty($item['order_lot_no'])) ? $item['order_lot_no'] : ''; ?></span></p>
                                <!-- <h5 class="overflow"><//?= (isset($item['item_name']) && !empty($item['item_name'])) ? strtoupper($item['item_name']) : ''; ?></h5> -->
                                <h5 class="overflow" id="bidderType"><?= $this->lang->line('with').' '.$last_bid['bid_type'].' '.$this->lang->line('bider'); ?></h5>
                            </li>
                        </ul>
                    </div>

                    <?php if($user): ?>
                        <div class="log" >
                            <h2 class="form-title"><?= $this->lang->line('quick_bids');?></h2> <span id="bidLoader"></span>
                            <ul class="bid-labels h-list" style="margin-bottom: -22px;">
                                <?php 
                                if($auction_live_settings): 
                                    $btns = explode(',', $auction_live_settings['buttons']);
                                    foreach ($btns as $btn):
                                        ?>
                                        <li>
                                            <button onclick="placeBidLive('<?= $btn; ?>');" class="btn btn-default bid-btn"> AED <?= $btn; ?> <?= $this->lang->line('bid_now');?></button>
                                        </li>
                                        <?php
                                    endforeach;
                                endif;
                                ?>
                            </ul>
                        </div> 
                    <?php else: ?>
                        <div class="reports login-box">  
                            <!-- <h3 class="after-login-h3">Please login first to place a bid</h3> -->
                            <form>
                                <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <input type="text" class="form-control" placeholder="Email"   name="email">
                                            <span class="valid-error text-danger email-error"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <input type="Password" class="form-control" placeholder="Enter Password"   name="password1">
                                            <span class="valid-error text-danger password1-error"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group m-0">
                                    <script src="https://www.google.com/recaptcha/api.js?onload=myCallBack&render=explicit&hl=<?= $ln; ?>" async defer></script>

                                    <div class="d-flex align-items-center">
                                        <div id="recaptcha1" 
                                            class="g-recaptcha" 
                                            data-sitekey="6LdtmdkUAAAAAES-wn1-rrOkB1SeJ1Ich1s7GPUx" 
                                            data-expired-callback="recaptchaExpired">
                                        </div>
                                        <button type="button" id="loginBid" class="btn btn-default"><?= $this->lang->line('login');?></button>
                                    </div>
                                    <span class=" text-warning" id="error-msgs" ></span>
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
                                    $(document).ready(function ($) {
                                        document.getElementById("loginBid").disabled = true;
                                    });
                                </script>
                                <!-- <div class="button-row">
                                    
                                    <a href="<?//= base_url() ?>home/register" type="button" id="" class="">Register</a>
                                </div> -->
                            </form>
                        </div>
                    <?php endif; ?>
            	
                </div>
            </div>	
            <div class="row">
                <div class="col left">
                    <div class="detail-tabs inner">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="detail-tab" data-toggle="tab" href="#detail" role="tab" aria-controls="detail" aria-selected="true"><?=$this->lang->line('current_lot');?></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="model-tab" data-toggle="tab" href="#model" role="tab" aria-controls="model" aria-selected="false"><?= $this->lang->line('all_lots');?></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="location-tab" data-toggle="tab" href="#location" role="tab" aria-controls="location" aria-selected="false"><?= $this->lang->line('winning_lot');?></a>
                            </li>

                            <!-- <li class="nav-item">
                                <a class="nav-link" id="bid-tab" data-toggle="tab" href="#bid_tab" role="tab" aria-controls="bid" aria-selected="false"><?= $this->lang->line('hall_auto_bids');?></a>
                            </li> -->
                        </ul>
                        <div class="tab-content fix-height" id="myTabContent">
                           
                            <div class="tab-pane fade show active" id="detail" role="tabpanel" aria-labelledby="detail-tab">
                                <h4 id="itemName"></h4>
                                <div class="row">
                                    <div class="col-md-6" style="<?= ($item_category['include_make_model'] == 'no') ? 'display: none' : ''; ?>">                                        
                                        <ul>
                                            <li><?= $this->lang->line('make');?>:</li>
                                            <li id="itemMake"></li>
                                        </ul>
                                        <ul>
                                            <li><?= $this->lang->line('model');?>:</li>
                                            <li id="itemModel"></li>
                                        </ul>
                                    </div>
                                    <div class="col-md-6">                                        
                                        <ul>
                                            <li><?= $this->lang->line('location');?>:</li>
                                            <li id="itemLoction"></li>
                                        </ul>
                                        <ul style="<?= ($item_category['include_make_model'] == 'no') ? 'display: none' : ''; ?>">
                                            <li><?= $this->lang->line('millage');?>:</li>
                                            <li id="itemMilage"></li>
                                        </ul>
                                    </div>
                                </div>
                                <p id="itemDetail"></p>
                            </div>

                            <div class="tab-pane fade" id="model" role="tabpanel" aria-labelledby="model-tab">
                                <div class="row">
                                    <div class="col-md-12" id="allLots"></div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="location" role="tabpanel" aria-labelledby="location-tab">
                                 <div class="row">
                                    <div class="col-md-12" id="winningLots"></div>    
                                </div>
                            </div>
                            <div class="tab-pane fade" id="bid_tab" role="tabpanel" aria-labelledby="bid-tab">
                                 <div class="row">
                                    <div class="col-md-12" id="hallAutoBids"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                 <div class="col right" style="margin-top: 33px;">
                    <div class="gray-box desc m-0">
	                    <div class="log">
	                        <h2 class="form-title"><?= $this->lang->line('log');?></h2>
                            <div class="log-wrapper" id="bidLog">
                                <!-- <ul>
                                    <li>10:00 AM :</li>
                                    <li>Placing bid</li>
                                </ul> -->
                            </div>
	                    </div>

                	</div>    
            	</div>
            </div>        
        </div>    
                
        <section class="related-items" id="auctionSlider">
            
        </section>
    </div>	

    <section class="our-app">
        <div class="container">
            <div class="inner">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <h3><?= $this->lang->line('download_app');?><br><?= $this->lang->line('ios_android');?></h3>
                    </div>
                    <div class="col-lg-4">
                        <ul>
                            <li data-aos="fade-down" data-aos-anchor-placement="top-bottom" data-aos-duration="2000">
                                <a href="#">
                                    <img src="<?= base_url(); ?>assets_user/images/app-store-icon.png" alt="">
                                </a>
                            </li>
                            <li data-aos="fade-up" data-aos-anchor-placement="top-bottom" data-aos-duration="2000">
                                <a href="#">
                                    <img src="<?= base_url(); ?>assets_user/images/play-store-icon.png" alt="">
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="app-img" data-aos="fade-right" data-aos-anchor-placement="top-bottom" data-aos-duration="2000">
                    <img src="<?= base_url(); ?>assets_user/images/app-img.png" alt="">
                </div>
            </div>
        </div>
    </section>

    <!-- these holder inputs used for holding current bid data -->
    <input type="hidden" id="bidAmountHolder" value="<?= isset($last_bid['bid_amount']) ? $last_bid['bid_amount'] : ''; ?>">
    <input type="hidden" id="auctionIdHolder" value="<?= isset($auction_id) ? $auction_id : ''; ?>">
    <input type="hidden" id="itemIdHolder" value="<?= isset($item_id) ? $item_id : ''; ?>">
    <input type="hidden" id="maxBidLimitHolder" value="<?= isset($max_bid_limit) ? $max_bid_limit : ''; ?>">

</div>

<script src="<?php echo base_url(); ?>assets_admin/vendors/pnotify/dist/pnotify.js"></script>
<script src="<?php echo base_url(); ?>assets_admin/vendors/pnotify/dist/pnotify.buttons.js"></script>
<script src="<?php echo base_url(); ?>assets_admin/vendors/pnotify/dist/pnotify.nonblock.js"></script>
<script src="https://js.pusher.com/6.0/pusher.min.js"></script>

<script>
    var token_name = '<?= $this->security->get_csrf_token_name();?>';
    var token_value = '<?=$this->security->get_csrf_hash();?>';
        
    $(document).ready(function() {
        var auctionId = "<?= isset($auction_id) ? $auction_id : ''; ?>";
        var itemId = "<?= isset($item_id) ? $item_id : ''; ?>";
        biddingLog(auctionId);
        allLots(auctionId);
        winningLots(auctionId);
        updateUpcomingAuctions(auctionId);
        hallAutoBids(auctionId);
        if(itemId){
            updateCurrentLot(itemId,auctionId);
        }

        //$(".bid-btn").attr("disabled", true);
    });

    //Enable pusher logging - don't include this in production
    Pusher.logToConsole = true;

    var pusher = new Pusher("<?= $this->config->item('pusher_app_key'); ?>", {
      cluster: 'ap1'
    });
    //var user_id = "<?php //isset($user) ? $user->id : ''; ?>";
    var channel = pusher.subscribe('ci_pusher');
    channel.bind('live-event', function(push) {
        var language = "<?= $language; ?>"

        //hold current bid info
        $("#auctionIdHolder").val(push.auction_id);
        $("#itemIdHolder").val(push.item_id);

        //update bid amount if available
        if(push.cb_amount){
            $("#bidAmount").html(numberWithCommas(push.cb_amount));
            $("#bidAmountHolder").val(push.cb_amount);
        }else{
            $("#bidAmount").html('0');
            $("#bidAmountHolder").val('0');
        }

        //update lot number if available
        if(push.lot_number){
            $("#lotNumber").html(push.lot_number);
        }else{
            $("#lotNumber").html('0');
        }

        //item heading name
        var itemName = $.parseJSON(push.data.item_name);
        var startTime = new Date(push.auction.start_time);
        
        $("#heading").html(itemName[language].toProperCase()+" - "+push.lot_number+" "+formatAMPM(startTime));

        //update bidder type Hall or On line if available
        if(push.current_bid){
            var bidderTypePhrase = 'With '+push.current_bid.bid_type.toProperCase()+' Bidder';
            $("#bidderType").html(bidderTypePhrase);
        }

        //update bid log
        biddingLog(push.auction_id);

        //update item images
        updateImages(push.item_images);

        //update all lots table
        allLots(push.auction_id);

        //update winning lots table
        winningLots(push.auction_id);

        //update hall auto bids table
        hallAutoBids(push.auction_id);

        //update current lot tab
        updateCurrentLot(push.item_id,push.auction_id);

        //upcoming auctions
        updateUpcomingAuctions(push.auction_id);

        //enable bid buttons
        $(".bid-btn").removeAttr("disabled");
    });

    /*function minutes_with_leading_zeros(dt) {
        return (dt.getMinutes() < 10 ? '0' : '') + dt.getMinutes();
    }

    function hours_with_leading_zeros(dt) { 
        return (dt.getHours() < 10 ? '0' : '') + dt.getHours();
    }*/

    function formatAMPM(date) {
        var hours = date.getHours();
        var minutes = date.getMinutes();
        var ampm = hours >= 12 ? 'pm' : 'am';
        hours = hours % 12;
        hours = hours ? hours : 12; // the hour '0' should be '12'
        minutes = minutes < 10 ? '0'+minutes : minutes;
        var strTime = hours + ':' + minutes + ' ' + ampm;
        console.log(strTime);
        return strTime;
    }

    function biddingLog(auctionId){
        var postData = {'auctionId':auctionId, [token_name]:token_value};
        $.ajax({
            url: "<?= base_url('auction/OnlineAuction/get_bid_log'); ?>",
            type: 'post',
            data: postData,
            beforeSend: function(){
                $('#bidLog').html('<img src="<?= base_url('assets_admin/images/load.gif'); ?>">');
            },
            success: function(res) {
                var objData = $.parseJSON(res);
                if(objData.error == false){
                    $('#bidLog').html(objData.response);
                }else{
                    $('#bidLog').html('<h4><?= $this->lang->line('no_data_available'); ?></h4>');
                }
            }
        });
    }

    function hallAutoBids(auctionId){
        var postData = {'auctionId':auctionId, [token_name]:token_value};
        $.ajax({
            url: "<?= base_url('auction/OnlineAuction/get_hall_auto_bids'); ?>",
            type: 'post',
            data: postData,
            beforeSend: function(){
                $('#hallAutoBids').html('<img src="<?= base_url('assets_admin/images/load.gif'); ?>">');
            },
            success: function(res) {
                //console.log(res);
                var objData = $.parseJSON(res);
                if(objData.status == true){
                    $('#hallAutoBids').html(objData.response);
                }else{
                    $('#hallAutoBids').html('<h4>No data available.</h4>');
                }
            }
        });
    }

    function allLots(auctionId){
        var postData = {'auctionId':auctionId, [token_name]:token_value};
        $.ajax({
            url: "<?= base_url('auction/OnlineAuction/get_all_lots'); ?>",
            type: 'post',
            data: postData,
            beforeSend: function(){
                $('#allLots').html('<img src="<?= base_url('assets_admin/images/load.gif'); ?>">');
            },
            success: function(res) {
                console.log(res);
                var objData = $.parseJSON(res);
                if(objData.status == true){
                    $('#allLots').html(objData.response);
                }else{
                    $('#allLots').html('<h4><?= $this->lang->line('no_data_available'); ?></h4>');
                }
            }
        });
    }

    function winningLots(auctionId){
        var postData = {'auctionId':auctionId, [token_name]:token_value};
        $.ajax({
            url: "<?= base_url('auction/OnlineAuction/get_winning_lots'); ?>",
            type: 'post',
            data: postData,
            beforeSend: function(){
                $('#winningLots').html('<img src="<?= base_url('assets_admin/images/load.gif'); ?>">');
            },
            success: function(res) {
                //console.log(res);
                var objData = $.parseJSON(res);
                if(objData.status == true){
                    $('#winningLots').html(objData.response);
                }else{
                    $('#winningLots').html('<h4><?= $this->lang->line('no_data_available'); ?></h4>');
                }
            }
        });
    }

    function updateImages(images){
        console.log(images);
        if(images){
            var baseURL = '<?= base_url(); ?>';
            $('#full_image,#small_image').slick('slickRemove',null ,null, true);
            $.each(images, function(index, value) {
                if(value.path.substring(0, 1) == '.'){ value.path.substring(1); }
                var img = '<div class="image"><img src="'+baseURL+value.path+value.name+'" alt=""></div>';
                $('#full_image,#small_image').slick('slickAdd',img);
            });
        }else{
            $('#full_image,#small_image').slick('slickRemove',null ,null, true);
        }
    }

    function updateCurrentLot(itemId,auctionId){
        var ln = "<?= $language; ?>";
        var postData = {'itemId':itemId, 'auctionId':auctionId, [token_name]:token_value};
        $.ajax({
            url: "<?= base_url('auction/OnlineAuction/get_current_lot'); ?>",
            type: 'post',
            data: postData,
            success: function(res) {
                //console.log(res);
                var objData = $.parseJSON(res);
                if(objData.status == true){
                    var itemMake = '';
                    var itemModel = '';
                    var item = objData.response;
                    var itemName = $.parseJSON(item.item_name);
                    var itemDetail = $.parseJSON(item.item_detail);
                    if (item.make) {
                        var itemMake = $.parseJSON(item.make.title);
                        var itemModel = $.parseJSON(item.model.title);
                    }
                    console.log('ln ',itemMake);
                    console.log('ln ',itemModel);

                    //make all divs empty
                    $("#itemName,#itemLoction,#itemDetail,#itemMake,#itemModel,#itemMilage").html('NA');

                    $("#itemName").html(itemName[ln].toUpperCase());
                    $("#itemLoction").html(item.item_location);
                    $("#itemDetail").html(itemDetail[ln]);

                    if(item.vehicle){
                        $("#itemMake").html(itemMake[ln]);
                        $("#itemModel").html(itemModel[ln]);
                        $("#itemMilage").html(item.mileage+' '+item.mileage_type);
                    }
                }
            }
        });
    }

    function updateToLiveController(auctionId){

        var postData = {'auctionId':auctionId, [token_name]:token_value};
        $.ajax({
            url: "<?= base_url('auction/OnlineAuction/get_all_lots'); ?>",
            type: 'post',
            data: postData,
            beforeSend: function(){
                $('#allLots').html('<img src="<?= base_url('assets_admin/images/load.gif'); ?>">');
            },
            success: function(res) {
                //console.log(res);
                var objData = $.parseJSON(res);
                if(objData.status == true){
                    $('#allLots').html(objData.response);
                }else{
                    $('#allLots').html('<h4><?= $this->lang->line('no_data_available'); ?></h4>');
                }
            }
        });
    
    }

    function updateUpcomingAuctions(auctionId){
        var postData = {'auctionId':auctionId, [token_name]:token_value};
        $.ajax({
            url: "<?= base_url('auction/OnlineAuction/get_upcoming_auctions'); ?>",
            type: 'post',
            data: postData,
            beforeSend: function(){
                $('#auctionSlider').html('<img src="<?= base_url('assets_admin/images/load.gif'); ?>">');
            },
            success: function(res) {
                //console.log(res);
                var objData = $.parseJSON(res);
                if(objData.status == true){
                    $('#auctionSlider').html(objData.response);
                }else{
                    //$('#auctionSlider').html('<h4>No data available.</h4>');
                }
            }
        });
    }

    function placeBidLive(amount){
        var auctionId = $("#auctionIdHolder").val();
        var itemId = $("#itemIdHolder").val();
        var maxBidLimitHolder = $("#maxBidLimitHolder").val();

        console.log('auctionId', auctionId);
        console.log('itemId', itemId);

        $.ajax({
            url: "<?= base_url('auction/OnlineAuction/place_bid_live'); ?>",
            type: 'post',
            data: {'bid_amount': amount, 'auction_id':auctionId, 'item_id':itemId, 'max_bid_limit':maxBidLimitHolder, [token_name]:token_value},
            beforeSend: function(){
                $('#bidLoader').html('<img src="<?= base_url('assets_admin/images/load.gif'); ?>">');
            },
            success: function(res) {
                console.log('res', res);
                $('#bidLoader').html('');
                var objData = $.parseJSON(res);
                console.log('objData', objData);
                
                if(objData.status =='soldout'){
                    new PNotify({
                        text: ""+objData.message+"",
                        type: 'error',
                        addclass: 'custom-error',
                        title: "<?= $this->lang->line('error');?>",
                        styling: 'bootstrap3'
                    });
                }

                if(objData.status =='limitCross'){
                    new PNotify({
                        text: ""+objData.message+"",
                        type: 'error',
                        addclass: 'custom-error',
                        title: "<?= $this->lang->line('error');?>",
                        styling: 'bootstrap3'
                    });
                }
                
                if(objData.status == true){
                    new PNotify({
                        text: " "+objData.message+" ",
                        type: 'success',
                        addclass: 'custom-success',
                        title: "<?= $this->lang->line('success_');?>",
                        styling: 'bootstrap3'
                    });
                }

                if(objData.status == false){
                    new PNotify({
                        text: " "+objData.message+" ",
                        type: 'error',
                        addclass: 'custom-error',
                        title: '<?= $this->lang->line('error'); ?>',
                        styling: 'bootstrap3'
                    });

                    if(objData.userNotLogin){
                        setTimeout(function(){
                            window.location.replace("<?= base_url('user-login?rurl='); ?>"+objData.rurl); 
                        }, 3000);
                    }
                }
            }
        });
    }

    $('#loginBid').on('click', function(event){
        event.preventDefault();

        var validation = false;
        var language = "<?= $language; ?>";
        selectedInputs = ['email','password1'];

        validation = validateFields(selectedInputs, language);

        if (validation == true) {
            var form = $(this).closest('form').serializeArray();

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
                        var auctionId = '<?php echo $this->uri->segment(2);?>';
                        if (rurl == '') {
                            window.location.replace('<?= base_url('live-online/'); ?>'+auctionId);
                        }else{
                            window.location.replace(rurl);
                        }
                    }
                }
            });
        }
    });
//     function numberWithCommas(x) {
//     var parts = x.toString().split(".");
//     parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
//     return parts.join(".");
// }

</script>
