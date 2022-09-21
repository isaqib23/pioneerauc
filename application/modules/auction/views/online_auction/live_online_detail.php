
<style>

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
.bid-info-wrapper p {
    font-size: 15px;
    line-height: 1.5;
    color: #8a8d97;
    margin: 20px 0 0 0;
}
.bid-info-wrapper p a {
    color: var(--primary-color);
}

  @media screen and (max-width: 1200px){
    .stack-on-1200 {margin-bottom: 20px;}
  }  

</style>


<script>
    /*$(document).ready(function(){
        $("#start_bid").attr('disabled',true);
        $("#btn-toggle").on("click", function(){
            $(".auto-biding > ul").slideToggle();
        });
    });*/
</script>

<!-- PNotify -->
<link href="<?php echo base_url(); ?>assets_admin/vendors/pnotify/dist/pnotify.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>assets_admin/vendors/pnotify/dist/pnotify.buttons.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>assets_admin/vendors/pnotify/dist/pnotify.nonblock.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets_admin/sweetalert/dist/sweetalert.css">


<?php
    // $bid_end_time = strtotime($item['bid_end_time']);
    $item_images_ids = explode(',', $item['item_images']);
    $images = $this->db->where_in('id', $item_images_ids)->get('files')->result_array();
    if($images){
        $image_src = base_url('uploads/items_documents/').$item['id'].'/'.$images[0]['name'];
    }else{
        $image_src = '';
    }

    $favorite = 'fa-heart-o';
    if ($this->session->userdata('logged_in')) {
        $user = $this->session->userdata('logged_in');
        $favt = $this->db->get_where('favorites_items', ['item_id' => $item['id'],'user_id' => $user->id])->row_array();
        if($favt){
            $favorite = 'fa-heart';
        }else{
            $favorite = 'fa-heart-o';
        }
    }

    if($this->session->userdata('logged_in')) {                    
        $is_logged_in = $this->session->userdata('logged_in');
    }else{
        $is_logged_in = NULL;
    }


    $start_date = new DateTime($auction_details['start_time']);
    $current_time= new DateTime('now');
    // print_r($current_time);
    $time_left = $start_date->diff($current_time);
    $days = $time_left->d;
    $hours = $days*24 + $time_left->h;
    $minuts = 0;
    if ($time_left->invert == 1) {
        $minuts = $days*24*60 + ($time_left->h-1)*60+$time_left->i;
    }
    $deadline = date('Y-m-d H:i:s', strtotime("-30 minutes", strtotime($auction_details['start_time'])));
?>

<div class="main gray-bg detail-page auction-page">
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
                        
                        <!-- <div class="icons">
                            <span>
                                <?php 
                                    $fav= 'fa fa-heart-o';
                                    if(isset($fav_item) && !empty($fav_item)){
                                        $fav = 'fa fa-heart';
                                    }
                                ?>
                                <a href="javascript:void(0)" id="fav_id" data-item="<?= $item['id']; ?>" onclick="doFavt(this)" >
                                <i class="<?php echo $fav;?>"></i>
                                </a>
                            </span> 
                        </div> -->

                    </div>
                </div>

                <div class="col right">
                    <div class="gray-box desc" style="height: auto;">
                        <ul class="h-list">
                            <?php $item_name = json_decode($item['item_name']); ?>
                            <li><h4><?= strtoupper($item_name->$language);?></h4></li>
                            <br>
                            <li>
                                <span class="label">
                                    <i class="eye-icon"></i>
                                    <?= $visit_count; ?>
                                </span>
                                <span class="label">
                                    <i class="calendar-icon"></i>
                                    <?= $item['year']; ?>
                                </span>
                                <?php
                                    if (!empty($auction_details['category_id'])) {
                                       
                                       $get_catagory =  $this->db->get_where('item_category',['id' => $auction_details['category_id'],'include_make_model' => 'yes'])->row_array();
                                       if (!empty($get_catagory)) {
                                            $hide = '';
                                       }else{
                                           $hide = 'visibility: hidden;';
                                       }
                                    }
                                ?>
                                <span class="label" style="<?= $hide;?>">
                                    <i class="metter-icon"></i>
                                    <?= number_format($item['mileage']).' '.$item['mileage_type']; ?>
                                </span>
                            </li>
                        </ul>
                        <p class="bid-info">
                            <!-- <span id="CBP" class="173">AED 10,000</span> -->
                            <i><?= $this->lang->line('hall_date');?> <em><?= date('d M Y', strtotime($auction_details['start_time'])) ?> <!-- 22 September 2020 --></em> </i>
                            <i><?= $this->lang->line('start_time');?> <em><?= date('h:i A', strtotime($auction_details['start_time'])); ?><!-- 7:30 pm --></em> </i>
                            <i><?= $this->lang->line('location');?> <em><?= $item['item_location']; ?></em> </i>
                        </p>
                    </div>

                    <?php if($is_logged_in): ?>
                        <div class="bid-info-wrapper">
                            <h2><?= $this->lang->line('final_order');?></h2>
                            <h3><?= $this->lang->line('multiple_bid');?> AED <?= $min_increment['min_increment']; ?></h3>
                            <div class="your-bid bid-div173" id="hidebid_section"> 
                                <ul class="h-list place-bid" id="hidebid_section">                                
                                    <input type="hidden" name="b_a_id" id="b_a_id" value="<?= (isset($auto_bid_data) && !empty($auto_bid_data)) ? $auto_bid_data['id'] : ''; ?>">
                                    <li>
                                        <input type="number" min="0" placeholder="Leave maximun price" class="form-control" id="bid_amount" name="bid_amount" value="<?= !empty($auto_bid_data) ? $auto_bid_data['bid_limit'] : ''; ?>">
                                    </li>
                                    <li>
                                        <button 
                                        onclick="proxybid(this)" 
                                        data-auction-id="<?= $item['auction_id']; ?>" 
                                        data-item-id="<?= $item['item_id']; ?>" 
                                        data-seller-id="<?= $item['item_seller_id']; ?>"
                                        data-lot-no="<?= $item['lot_no']; ?>" 
                                        data-max-bid="<?= (float)$balance*10; ?>"
                                        class="btn btn-default" id="bid_btn"><?= $this->lang->line('hall_auto_bid');?></button>
                                        <button class="info-btn" id="bid_help">
                                            <i class="fa fa-question-circle" style="color: red"></i>
                                        </button>
                                        <?php if (isset($auto_bid_data) && !empty($auto_bid_data)){?>
                                            <button onclick="cancel_bid(this)" class="btn btn-default"><?= $this->lang->line('cancel');?></button>
<script>
    function cancel_bid(e){
        //alert('dona done');
        var b_a_id = $('#b_a_id').val();
        swal({
            title: "<?= $this->lang->line('cancel_proxy');?>",
            text:  "<?= $this->lang->line('sure_proxy_bid_cancel');?>",
            type: "info",
            showCancelButton: true,
            cancelButtonClass: 'btn-default btn-md waves-effect',
            confirmButtonClass: 'btn-danger btn-md waves-effect waves-light',
            confirmButtonText: "<?= $this->lang->line('confirm');?>",
            cancelButtonText: "<?= $this->lang->line('cancel');?>"
        },function(isConfirm) {
            if (isConfirm) {
                $.ajax({
                    type: 'post',
                    url: '<?= base_url('auction/OnlineAuction/cancel_auto_bid'); ?>',
                    data: {'b_a_id':b_a_id},
                    success: function(data){
                        console.log(data);
                        var response = JSON.parse(data);
                        if(response.success == 'true') {
                            swal("Success!", response.msg, "success");
                            window.setTimeout(function() 
                            {
                                window.location.reload(true);
                            }, 2000);
                            // return false;
                        }
                        else{
                            swal("error!", response.msg, "error");
                        }
                    }
                });
            }
        });
    }
    
</script>
                                        <?php } ?>
                                        
                                    </li>
                                                
                                    <div class="text-danger bid-amount-error" style="display: none;"></div>
                                </ul>
                                <ul class="bid-labels" id="hide_lable">
                                    <!-- <li>
                                        <button class="btn btn-primary btn-counter bidder-button" onclick="bidButtons(this)" value="100">AED 100</button>
                                    </li> -->
                                    <li>
                                        <button class="btn btn-primary btn-counter bidder-button" onclick="bidButtons(this)" value="500">AED 500</button>
                                    </li>
                                    <li>
                                        <button class="btn btn-primary btn-counter bidder-button" onclick="bidButtons(this)" value="1000">AED 1000</button>
                                    </li>
                                    <li>
                                        <button class="btn btn-primary btn-counter bidder-button" onclick="bidButtons(this)" value="2000">AED 2000</button>
                                    </li>
                                </ul>
                                <?php if ($auction_details['start_status'] == 'start') { ?>
                                    <!-- <ul class="bid-labels" id="hide_lable">
                                        <li>
                                            <a href="<?= base_url('live-online./').$auction_details['id']; ?>" class="btn btn-default">Go to Hall Bid</a>
                                        </li>
                                    </ul> -->

                                    <p class="hall-bid" >This auction is live now please <a href="<?= base_url('live-online./').$auction_details['id']; ?>">Go to Hall Bid</a></p>

                                <?php } ?>
                            </div>
                             <p>
                               <!--  Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. -->
                            </p>
                        </div>
                    <?php else: ?>
                        <div class="reports login-box">
                            <h3 class="after-login-h3"><?= $this->lang->line('login_first');?></h3>
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
                                <div class="form-group">
                                    <script src="https://www.google.com/recaptcha/api.js?onload=myCallBack&render=explicit&hl=<?= $ln; ?>" async defer></script>
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
                                                <?php $make_title = json_decode($make['title']); ?>
                                                <li><?= $this->lang->line('make');?>:</li>
                                                <li><?= $make_title->$language; ?></li>
                                            </ul>
                                            <ul>
                                                <li><?php $model_title = json_decode($model['title']); ?><?= $this->lang->line('model');?>:</li>
                                                <li><?= $model_title->$language; ?></li>
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
                                                        <!-- <li><?= $value['label']; ?>:</li>
                                                        <li><?= $value['data-value']; ?></li> -->
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
                                            <?php if (!empty($item['item_test_report'])) { ?>
                                                <li>
                                                    <?php $item_test_report = $this->db->get_where('files', ['id' => $item['item_test_report']])->row_array(); ?>
                                                    <a href="<?= base_url().$item_test_report['path'].$item_test_report['name']; ?>" class="btn btn-gray" download class="file-link"><?= $this->lang->line('test_report');?></a>
                                                </li>
                                            <?php } ?>
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
                                </div>
                            </div>
                            <div class="tab-pane fade" id="location" role="tabpanel" aria-labelledby="location-tab">
                                <div class="row">
                                    <div id="map" style="width: 100%; height: 300px;"></div> 
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
                        <h2> <?= $this->lang->line('terms');?></h2>
                        <p>
                            <?php $terms_cndition = json_decode($item['terms']);?>
                            <?= $terms_cndition->$language; ?>
                            <!-- 1- Administrative fees shall apply to each lot as follows: 300AED if the sale price of the lot is 2000AED or less, or 500AED if the sale price of the lot exceeds 2000AED. You must complete payment and collection of the Lot within 48 hours of the date of the auction’s conclusion, after acquiring the supplier’s approval. -->
                        </p>
                        <p><?= $this->lang->line('inquire_further');?></p>
                        <ul class="h-list">
                            <li>
                                <a href="#" class="btn btn-gray"><?= $this->lang->line('enquire');?></a>
                            </li>
                            <li>
                                <a href="#" class="phone-link">
                                    <i class="icon"></i>
                                    <?= $contact['phone']; ?><!-- +97142860099 -->
                                </a>
                            </li>
                        </ul>
                        <a href="<?= base_url('terms-conditions'); ?>" class="terms-link">
                            <?= $this->lang->line('sale_terms');?>
                            <i class="arrow-right"></i>
                        </a>
                    </div>  
                </div>
            </div>        
        </div>    
                
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

</div>

<script src="<?php echo base_url(); ?>assets_admin/vendors/pnotify/dist/pnotify.js"></script>

<script>
    var token_name = '<?= $this->security->get_csrf_token_name();?>';
    var token_value = '<?=$this->security->get_csrf_hash();?>';
  
    $('#bid_help').on('click',function(){
        // var increment = "<?= $item['minimum_bid_price']; ?>"
        var increment = "<?= $min_increment['min_increment']; ?>"
        swal("<?= $this->lang->line('what_is_auto_biding');?> ("+increment+") <?= $this->lang->line('what_is_auto_biding_text');?>");
    });

    $(document).ready(function(){
        var deadline = "<?= $deadline; ?>";
        var time_now = "<?= date('Y-m-d H:i:s'); ?>";
        if (time_now > deadline) {
            $('#bid_btn').attr('disabled', true);
        }
    });

    function bidButtons(id) {
        var x = $(id).val();
        // var y = '<?= $item['minimum_bid_price'] ?>';
        var z = $('#bid_amount').val();
        if(!z){
            z=0;
        }
        $("#bid_amount").val(Number(z) + Number(x));
        let bid = $('#bid_amount').val();

        // if(bid != '' && bid >= Number(y)){
        //     $('#bid_btn').removeAttr("disabled");
        // }else{
        //     $('#bid_btn').attr("disabled" , "disabled");
        // }
    }

    function proxybid(e){
        //alert('dona done');

        var heart = $(e);
        var auctionid = $(e).data('auction-id');
        var itemID = $(e).data('item-id');
        var sellerid = $(e).data('seller-id');
        var maximum_bid_amount = $(e).data('max-bid');
        var bid_amount = $('#bid_amount').val();
        var b_a_id = $('#b_a_id').val();
        var bid_increment = "<?= $min_increment['min_increment']; ?>";
        var auction_item_id = "<?= $item['auction_item_id']; ?>";
        var deadline = "<?= $deadline; ?>";
        var time_now = "<?= date('Y-m-d H:i:s'); ?>";
        if (time_now > deadline) {
            swal("<?= $this->lang->line('error');?> ", "<?= $this->lang->line('time_over');?> ", "error");
            window.setTimeout(function() 
            {
                location.replace("<?= base_url('auction/online-auction/').$auction_details['category_id']; ?>");
                // window.location.reload(true);
            }, 3000);
            return false;
        }

        e = $('#bid_amount');

        if(($.trim(e.val()) == "") || (e.val() == null) || (typeof e.val() === 'undefined')){
            e.focus();
            $('.bid-amount-error').html('This value is required.').show();
            validation = false;
            return false;
        }else{
            validation = true;
            $('.bid-amount-error').html('').hide();
        }

        swal({
            title: "<?= $this->lang->line('placing_auto_bid  ');?> " +numberWithCommas(bid_amount)+ " AED.",
            text: "<?= $this->lang->line('sure_auto_bid  ');?> " +numberWithCommas(bid_amount),
            type: "info",
            showCancelButton: true,
            cancelButtonClass: 'btn-default btn-md waves-effect',
            confirmButtonClass: 'btn-danger btn-md waves-effect waves-light',
            confirmButtonText:  "<?= $this->lang->line('confirm');?> ",
            cancelButtonText:  "<?= $this->lang->line('cancel');?> "
        },function(isConfirm) {
            if (isConfirm) {
                //console.log('itemID :'+itemID+' auctionid :'+auctionid+' lotno :'+lotno+' sellerid :'+sellerid+' minimum_bid_price :'+minimum_bid_price+' bid_amount :'+bid_amount+' maximum_bid_amount :'+maximum_bid_amount);   
                if ((bid_amount%bid_increment) != 0) {
                    new PNotify({
                        type: 'error',
                        addclass: 'custom-error',
                        text: "<?= $this->lang->line('multiple');?> "+bid_increment+".",
                        styling: 'bootstrap3',
                        title: "<?= $this->lang->line('error');?>"
                    });
                }else{

                    if (bid_amount > maximum_bid_amount) {
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
                            url: '<?= base_url('auction/OnlineAuction/online_live_auto_bid'); ?>',
                            data: {'item_id':itemID,'auction_id':auctionid,'bid_limit':bid_amount,'bid_increment':bid_increment,'auction_item_id':auction_item_id,'b_a_id':b_a_id, [token_name]:token_value},
                            success: function(data){
                                console.log(data);
                                var response = JSON.parse(data);
                                if(response.success == 'true') {
                                    if (response.msg == 'activated') {
                                        swal("<?= $this->lang->line('success');?>", "<?= $this->lang->line('auto_bid_activated');?>", "success");
                                    } else {
                                        swal("<?= $this->lang->line('success');?>", "<?= $this->lang->line('auto_bid_activated_update');?>", "success");
                                    }
                                    window.setTimeout(function() 
                                    {
                                        window.location.reload(true);
                                    }, 2000);
                                    // return false;
                                }
                                else{
                                    swal("<?= $this->lang->line('error');?>", "<?= $this->lang->line('auto_bid_activated_not');?>", "error");
                                }
                            }
                        });
                    }
                }
            }
        });
    }

    $('#loginBid').on('click', function(event){
        event.preventDefault();

        var validation = false;
        var language = '<?= $language; ?>';
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
                        new PNotify({
                            type: 'error',
                            addclass: 'custom-error',
                            text: response.msg,
                            styling: 'bootstrap3',
                            title: 'Error'
                        });
                    }

                    if(response.msg == 'success'){
                        var rurl = '<?= isset($_GET['rurl']) ? $_GET['rurl'] : ''; ?>';
                        if (rurl == '') {
                            location.reload();
                        }else{
                            window.location.replace(rurl);
                        }
                    }
                }
            });
        }
    });


    // function doFavt(e){
    //     var heart = $(e);
    //     var itemID = $(e).data('item');
    //     var auctionId ='<?php echo $this->uri->segment(4);?>';
    //     var auctionItemid ="<?= $item['auction_item_id']; ?>";
    //     $.ajax({
    //         type: 'post',
    //         url: '<?= base_url('auction/OnlineAuction/do_favt'); ?>',
    //         data: {'item_id':itemID,'auction_id':auctionId,'auction_item_id':auctionItemid, [token_name]:token_value},
    //         success: function(msg){
    //             console.log(msg);
    //             //var response = JSON.parse(msg);
    //             if(msg == 'do_heart'){
    //                 heart.find('.fa-heart-o').addClass('fa-heart').removeClass('fa-heart-o');   
    //                 $('.fa-heart').tooltip({
    //                     title:'Remove from favorite!',
    //                     placement:'bottom'
    //                 });     
    //             }
    //             if(msg == 'remove_heart'){
    //                 heart.find('.fa-heart').addClass('fa-heart-o').removeClass('fa-heart');
    //                 $('.fa-heart-o').tooltip({
    //                     title:'Add to favorite!',
    //                     placement:'bottom'
    //                 });
    //             }
    //             if(msg == '0'){
    //                 window.location.replace('<?= base_url("home/login?rurl="); ?>'+encodeURIComponent(window.location.href));
    //             }
    //         }
    //     });   
    // }

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

<script src="https://maps.googleapis.com/maps/api/js?key=<?= $this->config->item('google_map_key'); ?>&callback=initialize"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets_admin/sweetalert/dist/sweetalert.min.js"></script>