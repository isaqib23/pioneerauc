<!-- PNotify and sweet alert -->
<link href="<?php echo base_url(); ?>assets_admin/vendors/pnotify/dist/pnotify.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>assets_admin/vendors/pnotify/dist/pnotify.buttons.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>assets_admin/vendors/pnotify/dist/pnotify.nonblock.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets_admin/sweetalert/dist/sweetalert.css">

<script>

    $(document).ready(function () {
        var bid_status = "<?= isset($bid_status) ? $bid_status : ''; ?>";
        var _bid_type = "<?= isset($bid_type) ? $bid_type : ''; ?>";
        var last_user_id = "<?= isset($last_user_id) ? $last_user_id : ''; ?>";
        var uid = "<?= $user['id'] ?? '';?>";
        // for condtions
        if (bid_status == 'win') {
            $("#soldText").show();
            $("#currentBid").hide();
        }
        if (_bid_type == 'online' && last_user_id == uid) {
            $(".live_hall_auction .footer").css('background-color', '#fa8500');
            $("#withU").text('(<?= $this->lang->line('with_u');  ?>)');

        } else if (_bid_type == 'online' && last_user_id != uid) {
            $(".live_hall_auction .footer").css('background-color', '#5C02B5');
            $("#withU").text(' ( With Online )');
        } else {
            $(".live_hall_auction .footer").css('background-color', '#5C02B5');
            $("#withU").text(' (<?= $this->lang->line('with_bidder'); ?>)');
        }


        // end conditions

    });
</script>
<div class="main-wrapper vehicles">
    <div class="container">
        <a href="javascript:void(0)" onclick="goBack()" class="back-link">
            <span class="material-icons">
                chevron_left
            </span>
            <?= $this->lang->line('back_to_result'); ?>
        </a>
        <div class="description-listing">
            <?php
            $auction_title = json_decode($auction['title']);
            $item_name = json_decode($item['item_name']);
            ?>
            <h1 class="page-title" id="heading"><?= strtoupper($item_name->$language); ?></h1>
            <input type="hidden" name="item_name" id="heading_hide" value="">
            <ul class="item-stats d-flex align-items-center">
                <li>
                    <span><?= $this->lang->line('lot'); ?> # </span>
                    <qa id="lotNumber">
                        <?= (isset($item['order_lot_no']) && !empty($item['order_lot_no'])) ? $item['order_lot_no'] : ''; ?>
                    </qa>
                </li>
                <li>
                    <span><?= $this->lang->line('sale_location'); ?>: </span>
                    <b><?= $item['item_location']; ?> </b>
                </li>
                <li>
                    <span><?= $this->lang->line('sale_date'); ?>: </span>
                    <?= date('D M d, Y', strtotime($auction['start_time'])) ?> -
                    <?= date('h:i A', strtotime($auction['start_time'])); ?><!--GST--></li>
            </ul>
            <div class="live-reverse-listing inner-listing">
                <div class="left-side">
                    <?php
                    if (isset($item['item_images']) && !empty($item['item_images'])):
                        $item_images_ids = explode(',', $item['item_images']);
                        $images = $this->db->where_in('id', $item_images_ids)->order_by('file_order', 'ASC')->get('files')->result_array();
                        if ($images):
                            ?>
                            <div class="detail-slider">
                                <div class="bg-black for-live">
                                    <!-- <ul class="action-links flex justify-content-end pr-3 mb-4">
                                        <li class="add_to_wishlist">
                                            <a href="#">
                                                <span class="material-icons">
                                                    favorite_border
                                                </span>
                                                Add to wishlist
                                            </a>
                                        </li>
                                        <li class="share">
                                            <a href="#">
                                                <span class="material-icons">
                                                    share
                                                </span>
                                                Share
                                            </a>
                                        </li>
                                    </ul> -->
                                    <div class="slider-for" id="full_image">

                                        <?php foreach ($images as $key => $value): ?>
                                            <div class="img">
                                                <img class="watermarks" data-toggle="modal" onclick="sliderRefresh()"
                                                     data-target="#imgModal"
                                                     src="<?= base_url('uploads/items_documents/') . $item['id'] . '/' . $value['name']; ?>">
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                <div class="slider-nav" id="small_image">
                                    <?php foreach ($images as $key => $value): ?>
                                        <div class="img">
                                            <img class="watermarks"

                                                 src="<?= base_url('uploads/items_documents/') . $item['id'] . '/' . $value['name']; ?>">
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <?php
                        endif;
                    else:
                        ?>
                        <div class="detail-slider">
                            <div class="bg-black for-live">
                                <!-- <ul class="action-links flex justify-content-end pr-3 mb-4">
                                    <li class="add_to_wishlist">
                                        <a href="#">
                                            <span class="material-icons">
                                                favorite_border
                                            </span>
                                            Add to wishlist
                                        </a>
                                    </li>
                                    <li class="share">
                                        <a href="#">
                                            <span class="material-icons">
                                                share
                                            </span>
                                            Share
                                        </a>
                                    </li>
                                </ul> -->
                                <div class="slider-for" id="full_image">
                                    <div class="img">
                                        <img src="<?= base_url('screen_assets/images/logo.jpg'); ?>"
                                             alt="Pioneer Auction Logo">
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if (($item_category['include_make_model'] == 'yes')) : ?>
                        <ul class="detail-icons" id="lotsBoxsData">

                        </ul>
                    <?php endif; ?>
                    <div class="detail-desc">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab"
                                   aria-controls="home"
                                   aria-selected="true"><?= $this->lang->line('current_lot'); ?></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab"
                                   aria-controls="profile"
                                   aria-selected="false"><?= $this->lang->line('all_lots'); ?></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab"
                                   aria-controls="contact"
                                   aria-selected="false"><?= $this->lang->line('winning_lot'); ?></a>
                            </li>

                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                                <ul style="<?= ($item_category['include_make_model'] == 'no') ? 'display: none' : ''; ?>">
                                    <li><?= $this->lang->line('make'); ?></li>
                                    <li id="itemMake"></li>
                                </ul>
                                <ul style="<?= ($item_category['include_make_model'] == 'no') ? 'display: none' : ''; ?>">
                                    <li><?= $this->lang->line('model'); ?></li>
                                    <li id="itemModel"></li>
                                </ul>
                                <ul style="<?= ($item_category['include_make_model'] == 'no') ? 'display: none' : ''; ?>">
                                    <li><?= $this->lang->line('vin_number'); ?></li>
                                    <li id="item_vin_number"></li>
                                </ul>
                                <div id="lotsFieldsData"></div>


                            </div>
                            <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                <div id="allLots"></div>
                            </div>
                            <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                                <div class="col-md-12" id="winningLots"></div>
                            </div>
                            <div class="tab-pane fade" id="bid_tab" role="tabpanel" aria-labelledby="bid_tab-tab">
                                <div class="col-md-12" id="hallAutoBids"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="right-side">
                    <div class="live_hall_auction">
                        <div class="head">
                            <p>
                                <span><?= $this->lang->line('live_hall_auction'); ?></span>
                                <?php
                                $startTime = strtotime($auction['start_time']);
                                if ($language == 'arabic') {
                                    $fmt = datefmt_create("ar_AE", IntlDateFormatter::LONG, IntlDateFormatter::MEDIUM, 'Asia/Dubai', IntlDateFormatter::GREGORIAN, "d MMM");
                                    echo datefmt_format($fmt, $startTime);
                                } else {
                                    echo date('d M', $startTime);
                                    // echo date('dS M Y H:i', $startTime);
                                } ?>
                                <!-- 26 Jan --> <?= $this->lang->line('live_auction'); ?>
                            </p>
                        </div>
                        <div class="live-aution">
                            <?php if (isset($lvl) && !empty($lvl)) { ?>
                                <iframe width="415" height="315" src="<?= $lvl; ?>" frameborder="0"
                                        allowfullscreen></iframe>
                                <!-- <iframe width="560" height="315" src="http://65.1.155.248:5080/WebRTCAppEE/play.html?name=831084719652829118746440" frameborder="0" allow="autoplay" allowfullscreen></iframe> -->
                            <?php } else { ?>
                                <div class="video">
                                    <img src="<?= NEW_ASSETS_USER; ?>/new/images/liveauction2.png">
                                </div>
                                <div class="icons d-flex">
                                    <div class="video-play flex">
                                        <a href="#">
                                                <span class="material-icons">
                                                    play_arrow
                                                </span>
                                        </a>
                                    </div>
                                    <div class="label flex">Live</div>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="footer">
                            <p id="soldText" style="display: none;">
                                <span> <?= $this->lang->line('sold_out_new'); ?> </span>
                            </p>
                            <p id="currentBid">
                                <?= $this->lang->line('current_bid'); ?>:
                                <span><i id="bidAmount"
                                         style="font-style: normal;"><?= number_format($last_bid['bid_amount'], 0, ".", ",");
                                        $last_bid['bid_amount']; ?> </i> <?= $this->lang->line('aed'); ?>
                                    <bid id="withU"></bid></span>
                            </p>
                        </div>
                    </div>
                    <?php if ($user): ?>
                        <div class="your-bid">
                            <h4><?= $this->lang->line('your_bid_new'); ?>:</h4>
                            <div class="form-group">
                                <ul class="aed-buttons">
                                    <?php
                                    if ($auction_live_settings):
                                        $btns = explode(',', $auction_live_settings['buttons']);
                                        $count = 0;
                                        foreach ($btns as $btn):
                                            $count++;
                                            ?>
                                            <li>
                                                <button type="button" class="btn-counter bidder-button"
                                                        onclick="placeBidLive('<?= $btn; ?>');"
                                                        value="100"> <?= $btn; ?> <?= $this->lang->line('aed_small'); ?></button>
                                            </li>
                                            <?php
                                            // if ($count == 4) {
                                            //     echo '</ul><br><ul class="aed-buttons">';
                                            //     $count = 0;
                                            // }
                                        endforeach;
                                    endif;
                                    ?>
                                </ul>
                            </div>
                            <!-- <form> -->
                            <!-- <div class="form-group">
                                <label>Direct Offer:</label>
                                <input type="number" name="" placeholder="AED">
                            </div> -->
                            <!-- <div class="form-group">
                                    <label>
                                        <img src="<?= NEW_ASSETS_USER; ?>/new/images/Frame.png">
                                        Auto Bidding:
                                    </label>
                                    <input type="text" name="" placeholder="Maximum Amount">
                                </div>
                                <p>
                                    <img src="<?= NEW_ASSETS_USER; ?>/new/images/error-popup.png">
                                    Place your maximum amount here and the system will <br>place the minimum increments on your behalf.
                                </p> -->
                            <!-- <div class="button-row">
                                <button type="submit" class="btn btn-primary">Place Bid</button>
                            </div> -->
                            <!-- </form> -->
                        </div>
                    <?php else: ?>
                        <div class="bid-now">
                            <p><?= $this->lang->line('available_for_live_auction'); ?></p>
                            <p>
                                <a href="javascript:void(0)" data-toggle="modal"
                                   data-target="#loginModal"><?= $this->lang->line('login_or_register'); ?></a>
                                <?= $this->lang->line('now_to_start_bidding'); ?>
                            </p>
                            <a href="javascript:void(0)" data-toggle="modal" data-target="#loginModal"
                               class="btn btn-primary"><?= $this->lang->line('bid_now'); ?>!</a>
                        </div>
                    <?php endif; ?>
                    <div class="log-list">
                        <h3><?= $this->lang->line('log_sm'); ?>:</h3>
                        <div class="log-wrapper" id="bidLog">
                            <!-- <ul>
                                <li class="text-primary">
                                    <span>03:00 PM</span>
                                    Live Bid AED 5,000
                                </li>
                                <li class="text-default">
                                    <span>03:00 PM</span>
                                    Live Bid AED 5,000
                                </li>
                                <li class="text-primary">
                                    <span>03:00 PM</span>
                                    Live Bid AED 5,000
                                </li>
                            </ul> -->
                        </div>

                    </div>
                    <!-- <div class="add-space">
                        <?= $this->lang->line('ad_space'); ?>
                    </div> -->
                </div>
            </div>
            <div class="similar-vehicles" id="auctionSlider">
            </div>
        </div>

        <!-- these holder inputs used for holding current bid data -->
        <input type="hidden" id="bidAmountHolder"
               value="<?= isset($last_bid['bid_amount']) ? $last_bid['bid_amount'] : ''; ?>">
        <input type="hidden" id="auctionIdHolder" value="<?= isset($auction_id) ? $auction_id : ''; ?>">
        <input type="hidden" id="itemIdHolder" value="<?= isset($item_id) ? $item_id : ''; ?>">
        <input type="hidden" id="maxBidLimitHolder" value="<?= isset($max_bid_limit) ? $max_bid_limit : ''; ?>">
    </div>


    <!-- limit Exceed Model. -->
    <div class="modal fade login-modal style-2" id="limitExceedModel" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <div class="increment-modal">
                        <div class="img">
                            <img src="<?= NEW_ASSETS_USER; ?>/new/images/error-popup.png">
                        </div>
                        <!-- <h3><? //= $this->lang->line('dont_have_enough_balance');?></h3> -->
                        <p id="limit_exceed_txt"></p>
                        <div class="button-row">
                            <a href="<?= base_url('customer/deposit'); ?>"
                               class="btn btn-primary"><?= $this->lang->line('take_me_to_the_payments'); ?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade login-modal style-2 image-modal" id="imgModal" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body show-img">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <div class="img-modal detail-slider">

                        <div class="slider-for-img">
                            <?php
                            if ($images):
                                foreach ($images as $key => $img_value): ?>
                                    <div class="img">
                                        <img class="watermarks" width="100"
                                             src="<?= base_url('uploads/items_documents/') . $item['id'] . '/' . $img_value['name']; ?>">
                                    </div>
                                <?php endforeach;
                            else: ?>
                                <img src="<?= base_url('assets_admin/images/product-default.jpg'); ?>" alt="">
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- You are placing AED 10,000 Increment. -->
    <div class="modal fade login-modal style-2" id="enough-balance" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <div class="increment-modal">
                        <div class="img">
                            <img src="<?= NEW_ASSETS_USER; ?>/new/images/error-popup.png">
                        </div>
                        <h3><?= $this->lang->line('dont_have_enough_balance'); ?></h3>
                        <p id="deposit_txt"><?= $this->lang->line('deposite_more_money'); ?>?</p>
                        <div class="button-row">
                            <a href="<?= base_url('customer/deposit'); ?>"
                               class="btn btn-primary"><?= $this->lang->line('take_me_to_the_payments'); ?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- You are placing AED 10,000 Increment. -->
    <div class="modal fade login-modal style-2" id="initialBid" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <div class="increment-modal">
                        <div class="img">
                            <img src="<?= NEW_ASSETS_USER; ?>/new/images/error-popup.png">
                        </div>
                        <h3><?= $this->lang->line('please_wait'); ?></h3>
                        <p><?= $this->lang->line('waiting_for_initial_bid'); ?></p>
                        <div class="button-row">
                            <button class="btn btn-primary" data-dismiss="modal">Ok</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script src="<?php echo base_url(); ?>assets_admin/vendors/pnotify/dist/pnotify.js"></script>
    <script src="<?php echo base_url(); ?>assets_admin/vendors/pnotify/dist/pnotify.buttons.js"></script>
    <script src="<?php echo base_url(); ?>assets_admin/vendors/pnotify/dist/pnotify.nonblock.js"></script>
    <script src="https://js.pusher.com/7.0.3/pusher.min.js"></script>

    <script>

        var token_name = '<?= $this->security->get_csrf_token_name();?>';
        var token_value = '<?=$this->security->get_csrf_hash();?>';

        function sliderRefresh() {
            $('.show-img .slider-for-img')[0].slick.refresh()
        }

        var token_name = '<?= $this->security->get_csrf_token_name();?>';
        var token_value = '<?=$this->security->get_csrf_hash();?>';

        $(document).ready(function () {
            var auctionId = "<?= isset($auction_id) ? $auction_id : ''; ?>";
            var itemId = "<?= isset($item_id) ? $item_id : ''; ?>";
            biddingLog(auctionId);
            //  showWinnerModel(auctionId);
            lotsFieldsData(auctionId, itemId);
            lotsBoxsData(auctionId, itemId);
            allLots(auctionId);
            winningLots(auctionId);
            updateUpcomingAuctions(auctionId);
            hallAutoBids(auctionId);
            if (itemId) {
                updateCurrentLot(itemId, auctionId);
            }

            //$(".bid-btn").attr("disabled", true);


        });

        function brodcast_pusher(itemID, auctionid) {
            var url = '<?= base_url('cronjob/broadcast_pusher/'); ?>' + itemID + '/' + auctionid;
            //  console.log(url)
            $.ajax({
                type: 'post',
                url: url,
                data: {[token_name]: token_value},
                // data: {},
                success: function (data) {
                    var push = $.parseJSON(data)
                    //     console.log(push);
                    var language = "<?= $language; ?>"

                    //    console.log(push.current_bid);
                    // change bid colors
                    $("#withU").text('');

                    //update bid amount if available
                    if (push.cb_amount) {
                        $("#bidAmount").html(numberWithCommas(push.cb_amount));
                        $("#bidAmountHolder").val(push.cb_amount);
                    } else {
                        $("#bidAmount").html('0');
                        $("#bidAmountHolder").val('0');
                    }

                    $(".live_hall_auction .footer").css('background-color', '#5C02B5');
                    if (push.current_bid) {
                        var b_uid = push.current_bid.user_id;
                        var bid_type = push.current_bid.bid_type;
                        var priority_type = push.current_bid.initial_priority_type;
                        var uid = "<?= $user['id'] ?? '';?>";

                        if (b_uid == uid && bid_type == 'online') {
                            $(".live_hall_auction .footer").css('background-color', '#fa8500');
                            $("#withU").text('(<?= $this->lang->line('with_u'); ?>)');

                        } else if (b_uid != uid && bid_type == 'online') {
                            $(".live_hall_auction .footer").css('background-color', '#5C02B5');
                            $("#withU").text(' ( With Online )');

                        } else {
                            $(".live_hall_auction .footer").css('background-color', '#5C02B5');
                            $("#withU").text(' (<?= $this->lang->line('with_bidder'); ?>)');

                        }
                    }

                    setTimeout(function () {
                        var old_item_id = $("#itemIdHolder").val();

                        $("#auctionIdHolder").val(push.auction_id);
                        $("#itemIdHolder").val(push.item_id);


                        //update lot number if available
                        if (push.lot_number) {
                            $("#lotNumber").html(push.lot_number);
                        } else {
                            $("#lotNumber").html('0');
                        }

                        //item heading name
                        var itemName = $.parseJSON(push.data.item_name);
                        var startTime = new Date(push.auction.start_time);

                        //$("#heading").html(itemName[language].toProperCase()+" - "+push.lot_number+" "+formatAMPM(startTime));
                        $("#heading").html(itemName[language].toProperCase());
                        $("#heading_hide").val(itemName[language].toProperCase());

                        //update bidder type Hall or On line if available
                        if (push.current_bid) {
                            var bidderTypePhrase = 'With ' + push.current_bid.bid_type.toProperCase() + ' Bidder';
                            $("#bidderType").html(bidderTypePhrase);
                        }


                        mix_function(push.auction_id, push.item_id, old_item_id);

                        //update bid log

                        //biddingLog(push.auction_id);

                        //show winner log

                        //showWinnerModel(push.auction_id);

                        //update item images
                        if (old_item_id != push.item_id) {

                            updateImages(push.item_images);
                        }

                        //update all lots table

                        //allLots(push.auction_id);

                        //update auction, item fields data
                        //lotsFieldsData(push.auction_id,push.item_id);

                        //update auction, item box data

                        // lotsBoxsData(push.auction_id,push.item_id);

                        //update winning lots table

                        //winningLots(push.auction_id);

                        //update hall auto bids table

                        //hallAutoBids(push.auction_id);

                        //update current lot tab

                        //updateCurrentLot(push.item_id, push.auction_id);

                        //upcoming auctions

                        //updateUpcomingAuctions(push.auction_id);

                        //enable bid buttons
                        $(".bid-btn").removeAttr("disabled");
                    }, 2000);
                }
            });
        }


        function brodcast_pusher_without_image(itemID, auctionid) {
            var url = '<?= base_url('cronjob/broadcast_pusher_without_image/'); ?>' + itemID + '/' + auctionid;
            //  console.log(url)
            $.ajax({
                type: 'post',
                url: url,
                data: {[token_name]: token_value},
                // data: {},
                success: function (data) {
                    var push = $.parseJSON(data)
                    //     console.log(push);
                    var language = "<?= $language; ?>"

                    //    console.log(push.current_bid);
                    // change bid colors
                    $("#withU").text('');

                    //update bid amount if available
                    if (push.cb_amount) {
                        $("#bidAmount").html(numberWithCommas(push.cb_amount));
                        $("#bidAmountHolder").val(push.cb_amount);
                    } else {
                        $("#bidAmount").html('0');
                        $("#bidAmountHolder").val('0');
                    }

                    $(".live_hall_auction .footer").css('background-color', '#5C02B5');
                    if (push.current_bid) {
                        var b_uid = push.current_bid.user_id;
                        var bid_type = push.current_bid.bid_type;
                        var priority_type = push.current_bid.initial_priority_type;
                        var uid = "<?= $user['id'] ?? '';?>";

                        if (b_uid == uid && bid_type == 'online') {
                            $(".live_hall_auction .footer").css('background-color', '#fa8500');
                            $("#withU").text('(<?= $this->lang->line('with_u'); ?>)');

                        } else if (b_uid != uid && bid_type == 'online') {
                            $(".live_hall_auction .footer").css('background-color', '#5C02B5');
                            $("#withU").text(' ( With Online )');

                        } else {
                            $(".live_hall_auction .footer").css('background-color', '#5C02B5');
                            $("#withU").text(' (<?= $this->lang->line('with_bidder'); ?>)');

                        }
                    }

                    setTimeout(function () {
                        var old_item_id = $("#itemIdHolder").val();

                        $("#auctionIdHolder").val(push.auction_id);
                        $("#itemIdHolder").val(push.item_id);


                        //update lot number if available
                        if (push.lot_number) {
                            $("#lotNumber").html(push.lot_number);
                        } else {
                            $("#lotNumber").html('0');
                        }

                        //item heading name
                        var itemName = $.parseJSON(push.data.item_name);
                        var startTime = new Date(push.auction.start_time);

                        //$("#heading").html(itemName[language].toProperCase()+" - "+push.lot_number+" "+formatAMPM(startTime));
                        $("#heading").html(itemName[language].toProperCase());
                        $("#heading_hide").val(itemName[language].toProperCase());

                        //update bidder type Hall or On line if available
                        if (push.current_bid) {
                            var bidderTypePhrase = 'With ' + push.current_bid.bid_type.toProperCase() + ' Bidder';
                            $("#bidderType").html(bidderTypePhrase);
                        }


                        mix_function(push.auction_id, push.item_id, old_item_id);

                        //update bid log

                        //biddingLog(push.auction_id);

                        //show winner log

                        //showWinnerModel(push.auction_id);

                        //update item images
                        if (old_item_id != push.item_id) {

                            updateImages(push.item_images);
                        }

                        //update all lots table

                        //allLots(push.auction_id);

                        //update auction, item fields data
                        //lotsFieldsData(push.auction_id,push.item_id);

                        //update auction, item box data

                        // lotsBoxsData(push.auction_id,push.item_id);

                        //update winning lots table

                        //winningLots(push.auction_id);

                        //update hall auto bids table

                        //hallAutoBids(push.auction_id);

                        //update current lot tab

                        //updateCurrentLot(push.item_id, push.auction_id);

                        //upcoming auctions

                        //updateUpcomingAuctions(push.auction_id);

                        //enable bid buttons
                        $(".bid-btn").removeAttr("disabled");
                    }, 2000);
                }
            });
        }


        //Enable pusher logging - don't include this in production
        Pusher.logToConsole = true;

        var pusher = new Pusher("<?= $this->config->item('pusher_app_key'); ?>", {
            cluster: 'ap1'
        });
        //var user_id = "<?php //isset($user) ? $user->id : ''; ?>";
        var channel = pusher.subscribe('ci_pusher');

        channel.bind('live-event', function (push) {
            $("#soldText").hide();
            $("#currentBid").show();
            var old_item_id = $("#itemIdHolder").val();
            if(old_item_id === push.item_id) {
                brodcast_pusher_without_image(push.item_id, push.auction_id);
            }else{
                brodcast_pusher(push.item_id, push.auction_id);
            }
        });

        channel.bind('live-price', function (push) {
            console.log(push);
            price = push.price;
            b_uid = push.bidder_id;
            bid_type = push.bid_type;
            uid = "<?= $user['id'] ?? '';?>";
            log = push.log;
            if (b_uid == uid && bid_type == 'online') {
                $(".live_hall_auction .footer").css('background-color', '#fa8500');
                $("#withU").text('(<?= $this->lang->line('with_u'); ?>)');

            } else if (b_uid != uid && bid_type == 'online') {
                $(".live_hall_auction .footer").css('background-color', '#5C02B5');
                $("#withU").text(' ( With Online )');

            } else {
                $(".live_hall_auction .footer").css('background-color', '#5C02B5');
                $("#withU").text(' (<?= $this->lang->line('with_bidder'); ?>)');
            }

            $("#bidAmount").text(price);
            $("#bidLog ul").prepend(log);
        });

        channel.bind('stop-event', function (push) {
            window.location.replace("<?= base_url('search/') . $item_category['id']; ?>")
        });

        channel.bind('sold-event', function (push) {
            // console.log('sold-event: '+push)
            // console.log(push)
            // console.log('Lets show Winner sold-event: '+push)
            $("#soldText").show();
            $("#currentBid").hide();
            var old_item_id = $("#itemIdHolder").val();

            mix_function_for_sold(push.auction_id, push.item_id, old_item_id)


            //showWinnerModel(push.auction_id, push.item_id);

            //update bid log

            //biddingLog(push.auction_id);

            //update auction, item fields data

            //lotsFieldsData(push.auction_id,push.item_id);

            //update auction, item box data

            //lotsBoxsData(push.auction_id,push.item_id);

            //update all lots table


            //allLots(push.auction_id);


            //update winning lots table

            //winningLots(push.auction_id);


            //update current lot tab

            //updateCurrentLot(push.item_id, push.auction_id);


            //upcoming auctions


            //updateUpcomingAuctions(push.auction_id);
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
            minutes = minutes < 10 ? '0' + minutes : minutes;
            var strTime = hours + ':' + minutes + ' ' + ampm;
            console.log(strTime);
            return strTime;
        }

        /* Mohsin*/

        function mix_function(auctionId, item_id, old_item_id) {
            var user_id = "<?= $user['id'] ?? '';?>";

            var postData = {'auctionId': auctionId, 'itemId': item_id, [token_name]: token_value};

            if (old_item_id == item_id) {

                $.ajax({
                    url: "<?= base_url('auction/OnlineAuction/mix_function2'); ?>",
                    type: 'post',
                    data: postData,
                    beforeSend: function () {
                        $('#bidLog').html('<img src="<?= base_url('assets_admin/images/load.gif'); ?>">');
                        $('#hallAutoBids').html('<img src="<?= base_url('assets_admin/images/load.gif'); ?>">');

                    },
                    success: function (res) {
                        var objData = $.parseJSON(res);
                        if (objData) {

                            //biddingLog function data

                            if (objData.get_bid_log2.error == false) {
                                //alert(objData.get_bid_log2.response);
                                $('#bidLog').html(objData.get_bid_log2.response);
                            } else {
                                $('#bidLog').html('<h4><?= $this->lang->line('no_data_available'); ?></h4>');
                            }


                            //showWinnerModel data

                            var itemName2 = $("#heading_hide").val();

                            if (objData.get_winner_status_model2.status == false) {
                            } else {
                                if (objData.get_winner_status_model2.uid == user_id) {
                                    $("h1 #heading").text();
                                    //  if(ln=='arabic'){
                                    $('#showWinningText').text('<?= $this->lang->line('winner_model_line_1');?>' + itemName2 + '<?= $this->lang->line('winner_model_line_');?>');

                                    //  }else{

                                    //   }
                                    $("#WinnerModelShow").modal('show');

                                }

                            }


                            //Hall AutoBids

                            if (objData.get_hall_auto_bids2.status == true) {
                                //alert(objData.get_hall_auto_bids2.response);
                                $('#hallAutoBids').html(objData.get_hall_auto_bids2.response);
                            } else {
                                $('#hallAutoBids').html('<h4><?= $this->lang->line("no_data_available"); ?></h4>');
                            }


                        }
                    }
                });

            }
            else {

                $.ajax({
                    url: "<?= base_url('auction/OnlineAuction/mix_function'); ?>",
                    type: 'post',
                    data: postData,
                    beforeSend: function () {
                        $('#bidLog').html('<img src="<?= base_url('assets_admin/images/load.gif'); ?>">');
                        $('#winningLots').html('<img src="<?= base_url('assets_admin/images/load.gif'); ?>">');
                        $('#hallAutoBids').html('<img src="<?= base_url('assets_admin/images/load.gif'); ?>">');

                    },
                    success: function (res) {
                        var objData = $.parseJSON(res);
                        if (objData) {

                            //biddingLog function data

                            if (objData.get_bid_log2.error == false) {
                                //alert(objData.get_bid_log2.response);
                                $('#bidLog').html(objData.get_bid_log2.response);
                            } else {
                                $('#bidLog').html('<h4><?= $this->lang->line('no_data_available'); ?></h4>');
                            }


                            //showWinnerModel data

//                            var itemName2 = $("#heading_hide").val();
//                            if (objData.get_winner_status_model2.status == false) {
//                            } else {
//                                if (objData.get_winner_status_model2.uid == user_id) {
//                                    $("h1 #heading").text();
//                                    //  if(ln=='arabic'){
//                                    $('#showWinningText').text('<?//= $this->lang->line('winner_model_line_1');?>//' + itemName2 + '<?//= $this->lang->line('winner_model_line_');?>//');
//                                    $("#WinnerModelShow").modal('show');
//                                }
//                            }


                            // alllots function data


                            $('#allLots').html('<img src="<?= base_url('assets_admin/images/load.gif'); ?>">');
                            if (objData.get_all_lots2.status == true) {
                                //alert(objData.get_all_lots2.response);
                                $('#allLots').html(objData.get_all_lots2.response);
                            } else {
                                $('#allLots').html('<h4><?= $this->lang->line('no_data_available'); ?></h4>');
                            }


                            $('#lotsFieldsData').html('<img src="<?= base_url('assets_admin/images/load.gif'); ?>">');
                            //lotsFieldsData
                            if (objData.get_item_fields_data2.status == true) {
                                //alert(objData.get_item_fields_data2.response);
                                $('#lotsFieldsData').html(objData.get_item_fields_data2.response);
                            } else {
                                $('#lotsFieldsData').html('<h4><?= $this->lang->line('no_data_available'); ?></h4>');
                            }


                            $('#lotsBoxsData').html('<img src="<?= base_url('assets_admin/images/load.gif'); ?>">');
                            //lotsBoxsData
                            if (objData.get_item_box_data2.status == true) {
                                //alert(objData.get_item_box_data2.response);
                                $('#lotsBoxsData').html(objData.get_item_box_data2.response);
                            } else {
                                $('#lotsBoxsData').html('<h4><?= $this->lang->line('no_data_available'); ?></h4>');
                            }


                            //winninglot function

                            if (objData.get_winning_lots2.status == true) {
                                //alert(objData.get_winning_lots2.response);
                                $('#winningLots').html(objData.get_winning_lots2.response);
                            } else {
                                $('#winningLots').html('<h4><?= $this->lang->line('no_data_available'); ?></h4>');
                            }


                            //Hall AutoBids

                            if (objData.get_hall_auto_bids2.status == true) {
                                //alert(objData.get_hall_auto_bids2.response);
                                $('#hallAutoBids').html(objData.get_hall_auto_bids2.response);
                            } else {
                                $('#hallAutoBids').html('<h4><?= $this->lang->line("no_data_available"); ?></h4>');
                            }

                            //Update Current Lot data


                            if (objData.get_current_lot2.status == true) {

                                var ln = "<?= $language; ?>";
                                var itemMake = '';
                                var itemModel = '';
                                var itemVinNnumber = '';
                                var item = objData.get_current_lot2.response;

                                var itemName = $.parseJSON(item.item_name);
                                var itemDetail = $.parseJSON(item.item_detail);


                                //      console.log(item)
                                //    console.log(item.specification)
                                if (item.make) {
                                    var itemMake = $.parseJSON(item.make.title);
                                    var itemModel = $.parseJSON(item.model.title);
                                }
                                //     console.log('ln ',itemMake);
                                //     console.log('ln ',itemModel);

                                //make all divs empty
                                $("#itemName,#itemLoction,#itemDetail,#itemMake,#itemModel,#item_vin_number,#itemMilage").html('NA');

                                $("#itemName").html(itemName[ln].toUpperCase());
                                $("#itemLoction").html(item.item_location);
                                $("#itemDetail").html(itemDetail[ln]);

                                if (item.vehicle) {
                                    //  alert('change')

                                    var mileageType = '';
                                    $("#itemMake").html(itemMake[ln]);
                                    $("#itemModel").html(itemModel[ln]);
                                    $("#item_vin_number").text(item.item_vin_number);
                                    $("#specification").text(item.specification);
                                    $("#ItemYear").text(item.year);
                                    $("#Itemmileage").text(numberWithCommas(item.mileage));
                                    if (ln == 'english') {
                                        if (item.mileage_type == 'km') {
                                            mileageType = 'Km';
                                        } else {
                                            mileageType = 'miles';
                                        }
                                    } else {
                                        if (item.mileage_type == 'km') {
                                            mileageType = ' كيلومتر ';
                                        } else {
                                            mileageType = 'ميل';
                                        }
                                    }
                                    $("#itemMilage").html(item.mileage + ' ' + mileageType);
                                }
                            }


                            //update Upcoming Auction data


                            $('#auctionSlider').html('<img src="<?= base_url('assets_admin/images/load.gif'); ?>">');
                            if (objData.get_upcoming_auctions2.status == true) {
                                //alert(objData.get_upcoming_auctions2.response);
                                $('#auctionSlider').html(objData.get_upcoming_auctions2.response);
                            } else {
                                //$('#auctionSlider').html('<h4>No data available.</h4>');
                            }


                        }
                    }
                });

            }


        }

        function mix_function_for_sold(auctionId, item_id, old_item_id) {


            var user_id = "<?= $user['id'] ?? '';?>";


            var postData = {'auctionId': auctionId, 'itemId': item_id, [token_name]: token_value};
            $.ajax({
                url: "<?= base_url('auction/OnlineAuction/mix_function'); ?>",
                type: 'post',
                data: postData,
                beforeSend: function () {
                    $('#bidLog').html('<img src="<?= base_url('assets_admin/images/load.gif'); ?>">');
                    $('#winningLots').html('<img src="<?= base_url('assets_admin/images/load.gif'); ?>">');

                },
                success: function (res) {
                    var objData = $.parseJSON(res);
                    if (objData) {


                        //showWinnerModel data

                        var itemName2 = $("#heading_hide").val();

                        if (objData.get_winner_status_model2.status == false) {
                        } else {
                            if (objData.get_winner_status_model2.uid == user_id) {
                                $("h1 #heading").text();
                                //  if(ln=='arabic'){
                                $('#showWinningText').text('<?= $this->lang->line('winner_model_line_1');?>' + itemName2 + '<?= $this->lang->line('winner_model_line_');?>');

                                //  }else{

                                //   }
                                $("#WinnerModelShow").modal('show');

                            }

                        }


                        //biddingLog function data

                        if (objData.get_bid_log2.error == false) {
                            $('#bidLog').html(objData.get_bid_log2.response);
                        } else {
                            $('#bidLog').html('<h4><?= $this->lang->line('no_data_available'); ?></h4>');
                        }


                        $('#lotsFieldsData').html('<img src="<?= base_url('assets_admin/images/load.gif'); ?>">');
                        //lotsFieldsData
                        if (objData.get_item_fields_data2.status == true) {
                            $('#lotsFieldsData').html(objData.get_item_fields_data2.response);
                        } else {
                            $('#lotsFieldsData').html('<h4><?= $this->lang->line('no_data_available'); ?></h4>');
                        }


                        $('#lotsBoxsData').html('<img src="<?= base_url('assets_admin/images/load.gif'); ?>">');
                        //lotsBoxsData
                        if (objData.get_item_box_data2.status == true) {
                            $('#lotsBoxsData').html(objData.get_item_box_data2.response);
                        } else {
                            $('#lotsBoxsData').html('<h4><?= $this->lang->line('no_data_available'); ?></h4>');
                        }


                        // alllots function data


                        $('#allLots').html('<img src="<?= base_url('assets_admin/images/load.gif'); ?>">');
                        if (objData.get_all_lots2.status == true) {
                            $('#allLots').html(objData.get_all_lots2.response);
                        } else {
                            $('#allLots').html('<h4><?= $this->lang->line('no_data_available'); ?></h4>');
                        }


                        //winninglot function

                        if (objData.get_winning_lots2.status == true) {
                            $('#winningLots').html(objData.get_winning_lots2.response);
                        } else {
                            $('#winningLots').html('<h4><?= $this->lang->line('no_data_available'); ?></h4>');
                        }


                        //Update Current Lot data


                        if (objData.get_current_lot2.status == true) {
                            var itemMake = '';
                            var itemModel = '';
                            var itemVinNnumber = '';
                            var item = objData.get_current_lot2.response;
                            var ln = "<?= $language; ?>";

                            var itemName = $.parseJSON(item.item_name);
                            var itemDetail = $.parseJSON(item.item_detail);

                            //      console.log(item)
                            //    console.log(item.specification)
                            if (item.make) {
                                var itemMake = $.parseJSON(item.make.title);
                                var itemModel = $.parseJSON(item.model.title);
                            }
                            //     console.log('ln ',itemMake);
                            //     console.log('ln ',itemModel);

                            //make all divs empty
                            $("#itemName,#itemLoction,#itemDetail,#itemMake,#itemModel,#item_vin_number,#itemMilage").html('NA');

                            $("#itemName").html(itemName[ln].toUpperCase());
                            $("#itemLoction").html(item.item_location);
                            $("#itemDetail").html(itemDetail[ln]);

                            if (item.vehicle) {
                                //  alert('change')

                                var mileageType = '';
                                $("#itemMake").html(itemMake[ln]);
                                $("#itemModel").html(itemModel[ln]);
                                $("#item_vin_number").text(item.item_vin_number);
                                $("#specification").text(item.specification);
                                $("#ItemYear").text(item.year);
                                $("#Itemmileage").text(numberWithCommas(item.mileage));
                                if (ln == 'english') {
                                    if (item.mileage_type == 'km') {
                                        mileageType = 'Km';
                                    } else {
                                        mileageType = 'miles';
                                    }
                                } else {
                                    if (item.mileage_type == 'km') {
                                        mileageType = ' كيلومتر ';
                                    } else {
                                        mileageType = 'ميل';
                                    }
                                }
                                $("#itemMilage").html(item.mileage + ' ' + mileageType);
                            }
                        }


                        //update Upcoming Auction data


                        $('#auctionSlider').html('<img src="<?= base_url('assets_admin/images/load.gif'); ?>">');
                        if (objData.get_upcoming_auctions2.status == true) {
                            $('#auctionSlider').html(objData.get_upcoming_auctions2.response);
                        } else {
                            //$('#auctionSlider').html('<h4>No data available.</h4>');
                        }


                    }
                }
            });


        }

        /* Mohsin end*/

        function biddingLog(auctionId) {
            var postData = {'auctionId': auctionId, [token_name]: token_value};
            $.ajax({
                url: "<?= base_url('auction/OnlineAuction/get_bid_log'); ?>",
                type: 'post',
                data: postData,
                beforeSend: function () {
                    $('#bidLog').html('<img src="<?= base_url('assets_admin/images/load.gif'); ?>">');
                },
                success: function (res) {
                    var objData = $.parseJSON(res);
                    if (objData.error == false) {
                        $('#bidLog').html(objData.response);
                    } else {
                        $('#bidLog').html('<h4><?= $this->lang->line('no_data_available'); ?></h4>');
                    }
                }
            });
        }


        // show winner model
        function showWinnerModel(auctionId, item_id) {
            var itemName = $("#heading_hide").val();
            var ln = "<?= $language; ?>";
            var user_id = "<?= $user['id'] ?? '';?>";
            var postData = {'auctionId': auctionId, 'item_id': item_id, [token_name]: token_value};
            $.ajax({
                url: "<?= base_url('auction/OnlineAuction/get_winner_status_model'); ?>",
                type: 'post',
                data: postData,
                success: function (res) {
                    var objData = $.parseJSON(res);
                    if (objData.status == false) {
                    } else {
                        if (objData.uid == user_id) {
                            $("h1 #heading").text();
                            //  if(ln=='arabic'){
                            $('#showWinningText').text('<?= $this->lang->line('winner_model_line_1');?>' + itemName + '<?= $this->lang->line('winner_model_line_');?>');

                            //  }else{

                            //   }
                            $("#WinnerModelShow").modal('show');

                        }

                    }
                }
            });
        }

        // end

        function hallAutoBids(auctionId) {
            var postData = {'auctionId': auctionId, [token_name]: token_value};
            $.ajax({
                url: "<?= base_url('auction/OnlineAuction/get_hall_auto_bids'); ?>",
                type: 'post',
                data: postData,
                beforeSend: function () {
                    $('#hallAutoBids').html('<img src="<?= base_url('assets_admin/images/load.gif'); ?>">');
                },
                success: function (res) {
                    //console.log(res);
                    var objData = $.parseJSON(res);
                    if (objData.status == true) {
                        $('#hallAutoBids').html(objData.response);
                    } else {
                        $('#hallAutoBids').html('<h4><?= $this->lang->line("no_data_available"); ?></h4>');
                    }
                }
            });
        }

        function allLots(auctionId) {
            var postData = {'auctionId': auctionId, [token_name]: token_value};
            $.ajax({
                url: "<?= base_url('auction/OnlineAuction/get_all_lots'); ?>",
                type: 'post',
                data: postData,
                beforeSend: function () {
                    $('#allLots').html('<img src="<?= base_url('assets_admin/images/load.gif'); ?>">');
                },
                success: function (res) {
                    //    console.log(res);
                    var objData = $.parseJSON(res);
                    if (objData.status == true) {
                        $('#allLots').html(objData.response);
                    } else {
                        $('#allLots').html('<h4><?= $this->lang->line('no_data_available'); ?></h4>');
                    }
                }
            });
        }

        function lotsFieldsData(auctionId, itemId) {
            var postData = {'auctionId': auctionId, 'itemId': itemId, [token_name]: token_value};
            $.ajax({
                url: "<?= base_url('auction/OnlineAuction/get_item_fields_data'); ?>",
                type: 'post',
                data: postData,
                beforeSend: function () {
                    $('#lotsFieldsData').html('<img src="<?= base_url('assets_admin/images/load.gif'); ?>">');
                },
                success: function (res) {
                    //     console.log(res);
                    var objData = $.parseJSON(res);
                    if (objData.status == true) {
                        $('#lotsFieldsData').html(objData.response);
                    } else {
                        $('#lotsFieldsData').html('<h4><?= $this->lang->line('no_data_available'); ?></h4>');
                    }
                }
            });
        }

        function lotsBoxsData(auctionId, itemId) {
            var postData = {'auctionId': auctionId, 'itemId': itemId, [token_name]: token_value};
            $.ajax({
                url: "<?= base_url('auction/OnlineAuction/get_item_box_data'); ?>",
                type: 'post',
                data: postData,
                beforeSend: function () {
                    $('#lotsBoxsData').html('<img src="<?= base_url('assets_admin/images/load.gif'); ?>">');
                },
                success: function (res) {
                    //   console.log(res);
                    var objData = $.parseJSON(res);
                    if (objData.status == true) {
                        $('#lotsBoxsData').html(objData.response);
                    } else {
                        $('#lotsBoxsData').html('<h4><?= $this->lang->line('no_data_available'); ?></h4>');
                    }
                }
            });
        }

        function winningLots(auctionId) {
            //  alert('winner')
            var postData = {'auctionId': auctionId, [token_name]: token_value};
            $.ajax({
                url: "<?= base_url('auction/OnlineAuction/get_winning_lots'); ?>",
                type: 'post',
                data: postData,
                beforeSend: function () {
                    $('#winningLots').html('<img src="<?= base_url('assets_admin/images/load.gif'); ?>">');
                },
                success: function (res) {
                    //console.log(res);
                    var objData = $.parseJSON(res);
                    if (objData.status == true) {
                        $('#winningLots').html(objData.response);
                    } else {
                        $('#winningLots').html('<h4><?= $this->lang->line('no_data_available'); ?></h4>');
                    }
                }
            });
        }

        function updateImages(images) {
            //  console.log(images);
            if (images) {
                var baseURL = '<?= base_url(); ?>';
                $('#full_image,#small_image, .slider-for-img').slick('slickRemove', null, null, true);
                $.each(images, function (index, value) {
                    if (value.path.substring(0, 1) == '.') {
                        value.path.substring(1);
                    }
                    var img = '<div class="img"><img data-toggle="modal" onclick="sliderRefresh()" data-target="#imgModal" width="100" src="' + baseURL + value.path + value.name + '" alt=""></div>';
                    $('#full_image,#small_image, .slider-for-img').slick('slickAdd', img);
                });
            } else {
                $('#full_image,#small_image, .slider-for-img').slick('slickRemove', null, null, true);
            }
        }

        function updateCurrentLot(itemId, auctionId) {
            var ln = "<?= $language; ?>";
            var postData = {'itemId': itemId, 'auctionId': auctionId, [token_name]: token_value};
            $.ajax({
                url: "<?= base_url('auction/OnlineAuction/get_current_lot'); ?>",
                type: 'post',
                data: postData,
                success: function (res) {
                    //console.log(res);
                    var objData = $.parseJSON(res);
                    if (objData.status == true) {
                        var itemMake = '';
                        var itemModel = '';
                        var itemVinNnumber = '';
                        var item = objData.response;

                        var itemName = $.parseJSON(item.item_name);
                        var itemDetail = $.parseJSON(item.item_detail);

                        //      console.log(item)
                        //    console.log(item.specification)
                        if (item.make) {
                            var itemMake = $.parseJSON(item.make.title);
                            var itemModel = $.parseJSON(item.model.title);
                        }
                        //     console.log('ln ',itemMake);
                        //     console.log('ln ',itemModel);

                        //make all divs empty
                        $("#itemName,#itemLoction,#itemDetail,#itemMake,#itemModel,#item_vin_number,#itemMilage").html('NA');

                        $("#itemName").html(itemName[ln].toUpperCase());
                        $("#itemLoction").html(item.item_location);
                        $("#itemDetail").html(itemDetail[ln]);

                        if (item.vehicle) {
                            //  alert('change')
                            var mileageType = '';
                            $("#itemMake").html(itemMake[ln]);
                            $("#itemModel").html(itemModel[ln]);
                            $("#item_vin_number").text(item.item_vin_number);
                            $("#specification").text(item.specification);
                            $("#ItemYear").text(item.year);
                            $("#Itemmileage").text(numberWithCommas(item.mileage));
                            if (ln == 'english') {
                                if (item.mileage_type == 'km') {
                                    mileageType = 'Km';
                                } else {
                                    mileageType = 'miles';
                                }
                            } else {
                                if (item.mileage_type == 'km') {
                                    mileageType = ' كيلومتر ';
                                } else {
                                    mileageType = 'ميل';
                                }
                            }
                            $("#itemMilage").html(item.mileage + ' ' + mileageType);
                        }
                    }
                }
            });
        }

        function updateToLiveController(auctionId) {

            var postData = {'auctionId': auctionId, [token_name]: token_value};
            $.ajax({
                url: "<?= base_url('auction/OnlineAuction/get_all_lots'); ?>",
                type: 'post',
                data: postData,
                beforeSend: function () {
                    $('#allLots').html('<img src="<?= base_url('assets_admin/images/load.gif'); ?>">');
                },
                success: function (res) {
                    //console.log(res);
                    var objData = $.parseJSON(res);
                    if (objData.status == true) {
                        $('#allLots').html(objData.response);
                    } else {
                        $('#allLots').html('<h4><?= $this->lang->line('no_data_available'); ?></h4>');
                    }
                }
            });

        }

        function updateUpcomingAuctions(auctionId) {
            var postData = {'auctionId': auctionId, [token_name]: token_value};
            $.ajax({
                url: "<?= base_url('auction/OnlineAuction/get_upcoming_auctions'); ?>",
                type: 'post',
                data: postData,
                beforeSend: function () {
                    $('#auctionSlider').html('<img src="<?= base_url('assets_admin/images/load.gif'); ?>">');
                },
                success: function (res) {
                    //console.log(res);
                    var objData = $.parseJSON(res);
                    if (objData.status == true) {
                        $('#auctionSlider').html(objData.response);
                    } else {
                        //$('#auctionSlider').html('<h4>No data available.</h4>');
                    }
                }
            });
        }

        function placeBidLive(amount) {
            var auctionId = $("#auctionIdHolder").val();
            var itemId = $("#itemIdHolder").val();
            var maxBidLimitHolder = $("#maxBidLimitHolder").val();

            console.log('auctionId', auctionId);
            console.log('itemId', itemId);

            $.ajax({
                url: "<?= base_url('auction/OnlineAuction/place_bid_live'); ?>",
                type: 'post',
                data: {
                    'bid_amount': amount,
                    'auction_id': auctionId,
                    'item_id': itemId,
                    'max_bid_limit': maxBidLimitHolder,
                    [token_name]: token_value
                },
                beforeSend: function () {
                    $('#bidLoader').html('<img src="<?= base_url('assets_admin/images/load.gif'); ?>">');
                },
                success: function (res) {
                    //  console.log('res', res);
                    $('#bidLoader').html('');
                    var objData = $.parseJSON(res);
                    //  console.log('objData', objData);

                    if (objData.status == 'soldout') {
                        PNotify.removeAll();
                        new PNotify({
                            text: "" + objData.message + "",
                            type: 'error',
                            addclass: 'custom-error',
                            title: "<?= $this->lang->line('error');?>",
                            styling: 'bootstrap3'
                        });
                    }

                    if (objData.status == 'limitCross') {
                        if (maxBidLimitHolder == 0) {
                            $('#deposit_txt').html('<?= $this->lang->line('deposite_money'); ?>');
                        }
                        $('#enough-balance').modal('show');
                        // new PNotify({
                        //     text: ""+objData.message+"",
                        //     type: 'error',
                        //     title: "<?//= $this->lang->line('error');?>",
                        //     styling: 'bootstrap3'
                        // });
                    }

                    if (objData.status == 'not_initialized') {
                        $('#initialBid').modal('show');
                        // new PNotify({
                        //     text: ""+objData.message+"",
                        //     type: 'error',
                        //     title: "<?//= $this->lang->line('error');?>",
                        //     styling: 'bootstrap3'
                        // });
                    }

                    if (objData.status == 'limitExceed') {
                        $('#limit_exceed_txt').html(objData.msg);
                        $("#limitExceedModel").modal('show');
                    }

                    if (objData.status == true) {
                        PNotify.removeAll();
                        new PNotify({
                            text: " " + objData.message + " ",
                            type: 'success',
                            addclass: 'custom-success',
                            title: "<?= $this->lang->line('success_');?>",
                            styling: 'bootstrap3'
                        });
                    }

                    if (objData.status == 'info' || objData.status == 'false') {
                        PNotify.removeAll();
                        new PNotify({
                            text: " " + objData.message + " ",
                            type: 'info',
                            title: "<?= $this->lang->line('info_cap'); ?>",
                            styling: 'bootstrap3',
                            addclass: 'custom-info'
                        });
                    }

                    if (objData.status == false) {
                        PNotify.removeAll();
                        new PNotify({
                            text: " " + objData.message + " ",
                            type: 'error',
                            addclass: 'custom-error',
                            title: '<?= $this->lang->line('error'); ?>',
                            styling: 'bootstrap3'
                        });

                        if (objData.userNotLogin) {
                            setTimeout(function () {
                                window.location.replace("<?= base_url('user-login?rurl='); ?>" + objData.rurl);
                            }, 3000);
                        }
                    }
                }
            });
        }

        $('#loginBid').on('click', function (event) {
            event.preventDefault();

            var validation = false;
            var language = "<?= $language; ?>";
            selectedInputs = ['email', 'password1'];

            validation = validateFields(selectedInputs, language);

            if (validation == true) {
                var form = $(this).closest('form').serializeArray();

                $.ajax({
                    type: 'post',
                    url: '<?= base_url('home/login_process'); ?>',
                    data: form,
                    success: function (msg) {
                        //  console.log(msg);
                        var response = JSON.parse(msg);

                        if (response.error == true) {
                            $('#error-msgs').html('<div class="alert alert-danger alert-dismissible" id="valid-msgs"><button type="button" class="close" data-dismiss="alert">&times;</button>' + response.msg + '</div>');
                        }

                        if (response.msg == 'success') {
                            var rurl = '<?= isset($_GET['rurl']) ? $_GET['rurl'] : ''; ?>';
                            var auctionId = '<?php echo $this->uri->segment(2);?>';
                            if (rurl == '') {
                                window.location.replace('<?= base_url('live-online/'); ?>' + auctionId);
                            } else {
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

        function goBack() {
            window.history.back();
        }

    </script>
    <!--
    winner-modal .modal-dialog .modal-content .modal-body .increment-modal img -->


    <!-- You are placing AED 10,000 Increment. -->
    <div class="modal fade login-modal style-2" id="WinnerModelShow" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close _bidCancelled" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <div class="increment-modal">
                        <div class="img">
                            <img src="<?= NEW_ASSETS_USER; ?>/new/images/winner.png">
                        </div>
                        <h2 id="showWinningText"></h2>
                        <p id="winnerDetail"><?= $this->lang->line('winner_model_line_2'); ?></p>
                        <div id="submitBtn" class="row">
                            <!-- <div class="col-6 pr-0"> -->
                            <button data-dismiss="modal" class="btn btn-primary _bidCancelled">
                                <?= $this->lang->line('ok'); ?></button>
                            <!-- </div> -->

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>// path to the watermark image
        // $(function(){
        //  //add text water mark;  background-cover
        //  $('img.watermarks').watermark({
        //  path: '<?= NEW_ASSETS_USER; ?>/new/images/PA.png',
        //   textWidth: 100,
        //   textColor: 'white'
        //  });

        // })

    </script>
