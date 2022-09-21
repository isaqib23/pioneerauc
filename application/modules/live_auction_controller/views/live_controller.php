
<!-- PNotify -->
<link href="<?php echo base_url(); ?>assets_admin/vendors/pnotify/dist/pnotify.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>assets_admin/vendors/pnotify/dist/pnotify.buttons.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>assets_admin/vendors/pnotify/dist/pnotify.nonblock.css" rel="stylesheet">

<style>
    body {background: #f5f5f5}
    .main-wapper {padding: 40px 0;}
    .auction-nam h2 {font-size: 20px; background: #e68522; text-align: center; padding: 6px 15px; width: 100%; margin-bottom: 2px; }
    .auction-nam h3 {font-size: 15px; background: #f1c370; text-align: center; padding: 3px 15px; width: 100%; margin-bottom: 4px; }
    .summary h3 {color: #2828c1; font-size: 19px; line-height: 1.1; font-weight: normal;}
    .summary h2 {color: #d60e0e; font-size: 28px; line-height: 1; font-weight: normal; }
    .summary h4 {color: gray; font-size: 18px; line-height: 1; font-weight: normal; }

    fieldset {
        border: solid 1px #b3b3b3;
        padding: 0 1.4em 6px 1.4em;
        border-radius: 0px;
        margin: 0 0 15px 0;
        box-shadow: 0px 0px 0px 0px #000;
    }

    fieldset legend {
        font-size: 13px;
        text-align: left;
        width: auto;
        padding: 0 8px 0px 2px;
        border-bottom: none;
    }

    .detail-table {
        border: 2px solid gray;
        height: 250px;
        overflow: scroll;
    }
    .ozStarted{
        background: #1ABB9C;
        color: #fff;
    }
    .ozStoped{
        background: #d60e0e;
        color: #fff;
    }
    table { width: 100%;}

    table th {background: lightgray;}

    td, th {
        border: 1px solid #dddddd;
        text-align: left;
        padding: 8px;
        font-size: 13px;
        font-weight: normal;
    }

    table tbody tr:hover {
        background: #F7F8FB;
    }

    td {padding: 0px 9px}

    .lot,
    .reg,
    .make,
    .colour,
    .reverse{width: 12%;}

    .status {width: 40%;}

    .bolder {font-weight: bolder;}

    ul {list-style: none; padding: 0; margin: 0;}

    .bid-update ul {display: flex; align-items: center; flex-wrap: wrap; justify-content: space-between; width: 100%}
    .bid-update li {width: 31%; margin-bottom: 15px;}

    .btn-counter {font-size: 16px; padding: 8px 10px; color: gray; background: #f5f5f5; border-radius: 0; width: 100%; min-height: 44px; box-shadow: none; outline: none !important;  }

    tbody tr:hover {box-shadow: 0px 0px 6px inset gray}
    .detail-table tbody tr:hover .bolder {color: #000 !important;}
    .detail-table tbody tr {background: #FFF; }

    thead tr, th, td { border: 1px solid gray !important; }

    .table table {height: 200px; border: 1px solid lightgray; margin-bottom: 10px; width: 100%;}
    .table table tr th:first-child {width: 35%;}
    .table table tr th:nth-child(2) {width: 35%;}
    .table table tr th:nth-child(3) {width: 15%;}
    .table table tr th:nth-child(4) {width: 15%;}
    .table tbody .empty {background: #fff; height: 104px;}

    .table tbody .empty td{ min-height: 150px; padding: 20px 10px; font-size: 22px; font-weight: 600; line-height: 1.3;}

    select {width: 100%; padding: 5px 20px; font-size: 16px; font-weight: 600;}

    .sold-items {margin: 10px 0; }

    .sold-items ul {display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; width: 90%; margin: auto;}

    .sold-items li {width: 19.7%; border: 1px solid gray; text-align: center; font-size: 12px; background: rgba(234, 164, 34, 0.25);}

    .green-text {color: green;}
    .blue-text {color: blue;}
    .red-text {color: red;}

    .start-auction {width: 100%; font-size: 13px; line-height: 1; padding: 10px 10px; font-weight: 600;}

    /*.bids-increment {display: flex; align-items: center;}*/
    .bids-increment p {font-size: 15px; font-weight: 600;}
    .bids-increment input {width: 100%; padding: 4px 10px;}
    .bids-increment .btn-inc {padding: 6px 10px;}

    .btn-inc {width: 100%; color: gray; background: #f5f5f5; padding: 4px 10px; font-size: 12px; outline: none !important;}

    .box {height: 250px; background: #fff; overflow: scroll; border: 1.5px solid gray; padding: 5px 15px;}
    .box li {font-size: 14px; line-height: 1.4; position: relative; }
    .box li:before {content: ''; box-shadow: 0px 0px 1px inset; position: absolute; left: -10px; top: 0; width: 5px; height: 100%; background: lightblue; opacity: 0;}

    .box li:hover:before,
    .box li:focus:before,
    .box li.active:before {opacity: 1;}

    .btn-box li:not(:last-child) {margin-bottom: 15px;}

    @media screen and (max-width: 1180px){

        .table table tr th {vertical-align: middle;}
    }

    @media screen and (max-width: 1024px){

        .btn-box {margin: 20px 0;}

        select {margin-bottom: 20px;}

    }

    @media screen and (max-width: 991px){

        .bid-update li {width: 49%;}

        .sold-items ul {width: 100%; max-width: 100%;}
    }

    .aution-table select {width: 70px; padding: 0; font-size: 12px; margin: 10px auto;}

    @media only screen and (max-width: 1000px)
    {

        .table tbody .empty td {font-size: 18px}

        .responsive-on-1200 thead {display: none;}
        .responsive-on-1200 tr {display: block; border-bottom: 1px solid rgba(35, 57, 79, 0.2); margin-bottom: 10px;}
        .responsive-on-1200 tr td {width: 100% !important; display: flex; padding: 12px !important; text-align: left !important; border: 1px solid rgba(35, 57, 79, 0.2); border-bottom: 0;}
        .responsive-on-1200 tr td:before {content: attr(data-label); padding-left: 10px; font-size: 14px; text-align: left; width: 35%; border-right: 1px solid rgba(35, 57, 79, 0.2); display: inline-flex; align-items: center; margin-right: 15px; padding: 14px 0; margin: -12px 0; margin-right: 15px; color: #000 !important}
        .aution-table select {width: 120px; margin: 10px; padding: 2px;}
    }
    .yellow-box {background: yellow; border: 1px solid #ccc; padding: 20px 30px; display: table; margin: auto; margin-right: 0;}
    .summary .yellow-box + h4 {color: #000;}

    .bids-detail .box {height: auto !important; max-height: 230px;}

    .button-row {text-align: center;}

    .button-row button { width: 130px; margin: 30px auto; }

    .auction-detail ul {display: flex; align-items: center; justify-content: center; flex-wrap: wrap;}
    .auction-detail li {width: 48%; margin: auto; }

    table tbody tr.active {background: lightblue; color: #000;}

    .aution-table {height: 330px; overflow: scroll;}

    .search-inp {width: 150px !important;}


</style>

<main class="main-wapper">
    <div id="result"></div>
    <div id="status_start"></div>
    <div class="container">

        <div class="row">
            <div class="col-lg-7">
                <label>Show Online Users</label>
                <input type="checkbox" checked id="show_online_user" style="width: 30px;height: 20px;">
                <label>Show Bid Logs</label>
                <input type="checkbox" checked id="show_bid_logs" style="width: 30px;height: 20px;">
                <div class="auction-nam">
                    <input type="hidden" name="item_id" id="item_id">
                    <input type="hidden" name="order_lot_no" id="order_lot_no">
                    <h2  id="live_auction_active_item">
                        <!-- Auctions 1619-Vehicles -->
                        NA
                    </h2>
                    <h3 id="live_auction_start_time">
                        <!-- Tuesday 11/02/2020 at 19:30 -->
                        NA
                    </h3>
                </div>
                <div class="summary">
                    <fieldset>
                        <legend>Lot summary</legend>
                        <div class="row">
                            <div class="col-lg-7">
                                <h2>Current Bid : AED <span id="current_bid_amount">0</span></h2>
                                <h4>Bid Increment : AED <span id="bid_increment_latest">0</span></h4>
                                <input type="text" class="form-control search-inp" disabled name="search" id="search" placeholder="Search By Lot"> </span>
                            </div>
                            <div class="col-lg-5">
                                <div id="lot_number_div" cass="yellow-box">
                                    <h3>No Active Lot</h3>
                                </div>
                                <h4 id="reserve_price">Reserve:</h4>
                            </div>
                        </div>
                    </fieldset>
                </div>
                <div class="detail-table">
                    <table id="auctions_items_list_tab">
                        <thead>
                        <tr>
                            <th class="lot">Lot</th>
                            <th class="lot" style="width: 30%;" >Item Name</th>
                            <th class="lot" style="width: 30%;" >Seller</th>
                            <th class="reg">Reg No</th>
                            <th class="make">Make</th>
                            <th class="reverse">Reserve</th>
                            <th class="status">Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <div class="sold-items">
                    <ul>
                        <li class="entitycount">Ent: 0</li>
                        <li class="green-text" id="sold_item_count">Sold: 0</li>
                        <!-- <li class="blue-text">PB: 0</li> -->
                        <li class="red-text">Unsold: 0</li>
                        <!-- <li>O/S: 23</li> -->
                    </ul>
                </div>
                <div class="table" id="online_user_list"></div>
            </div>
            <div class="col-lg-5">
                <div class="row" id="bid_start_div">
                    <div class="col-md-8">
                        <select class="auction_list_option">
                            <option value="">Select Sales for auctions</option>
                            <?php
                            if(isset($active_live_auctions) && !empty($active_live_auctions)){
                                foreach ($active_live_auctions as $value) {
                                    $title = json_decode($value['title'], true);
                                    ?>

                                    <option value="<?= $value['id'] ?>" <?= ($value['start_status'] == 'start') ? 'selected' : ''; ?>><?= $title['english'] ?></option>
                                    <?php
                                }
                            }else{ ?>
                                <option value="">No Active Bid</option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <button disabled class="start-auction ozStarted" id="bid_start_button">START AUCTION</button>
                    </div>
                </div>
                <?php
                if(isset($active_live_auctions) && !empty($active_live_auctions)){
                    foreach ($active_live_auctions as $value) {
                        if ($value['start_status'] == 'start') { ?>
                            <script>
                                $('#bid_start_button').removeClass('ozStarted');
                                $('#bid_start_button').addClass('ozStoped');
                                $('.auction_list_option').attr('disabled',true);
                                $('#bid_start_button').attr('disabled',false);
                                $('#bid_start_button').html('STOP AUCTION');
                                $(document).ready(function(){
                                    get_auction_items();
                                    getLiveSoldItems();
                                });
                            </script>
                        <?php } } }?>
                <div class="bid-update">
                    <fieldset>
                        <legend>Hall bids updates</legend>
                        <ul>
                            <?php if(isset($auction_live_buttons) && !empty($auction_live_buttons)){
                                $bottons_str = $auction_live_buttons['buttons'];
                                $buttons_array = explode(",", $bottons_str);
                                rsort($buttons_array);
                                foreach ($buttons_array as $button_value) {
                                    ?>
                                    <li>
                                        <button disabled onclick="placeBid(this)" class="btn-counter bidder-button" id="<?= $button_value;?>">AED <?= $button_value;?></button>
                                    </li>
                                    <?php
                                }
                            }else{
                                ?>
                                <li>
                                    <button class="btn-counter">Button Settings are missing</button>
                                </li>
                            <?php } ?>
                        </ul>
                    </fieldset>
                </div>
                <div class="bids-increment">
                    <div class="row">
                        <div class="col-md-7">
                            <p><input type="text"
                                      name="initial_bid"
                                      oninput="this.value=this.value.replace(/[^0-9]/g,'');"
                                      id="initial_bid_field" />
                            </p>
                        </div>
                        <div class="col-md-5">
                            <button disabled class="btn-inc" id="initial_bid_btn">Initial Hall Bids</button>
                        </div>
                    </div>
                </div>
                <div class="bids-detail">
                    <fieldset>
                        <legend>Bids</legend>
                        <div class="row">
                            <div class="col-lg-8">
                                <div class="box" style="background: #fff;">
                                    <ul id="live_bid_log">
                                        <!-- <li class="active">Lot 321 Retracted</li>
                                          <li>Lot 321 Retracted</li>
                                         <li>Lot 321 Retracted</li>
                                         <li>Lot 321 Retracted</li>
                                         <li>Lot 321 Retracted</li>
                                         <li>Lot 321 Retracted</li>
                                         <li>Lot 321 Retracted</li>
                                         <li>Lot 321 Retracted</li>
                                         <li>Lot 321 Retracted</li>
                                         <li>Lot 321 Retracted</li>
                                         <li>Lot 321 Retracted</li>
                                         <li>Lot 321 Retracted</li>
                                         <li>Lot 321 Retracted</li>
                                         <li>Lot 321 Retracted</li> -->
                                    </ul>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="btn-box">
                                    <ul>
                                        <li>
                                            <button disabled class="btn-inc" id="retract_bid_btn">Retract</button>
                                        </li>
                                        <li>
                                            <button disabled class="btn-inc" id="sold_bid_btn">Sold</button>
                                        </li>
                                        <li>
                                            <button disabled class="btn-inc" id="notsold_bid_btn">Not Sold</button>
                                        </li>
                                        <!-- <li>
                                          <button disabled class="btn-inc" id="provisional_bid_btn">Provisional</button>
                                        </li> -->
                                        <li>
                                            <button disabled class="btn-inc" id="sold_approval_bid_btn">Sold On Aproval</button>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </div>
                <div class="auction-detail">
                    <fieldset>
                        <legend>Auction</legend>
                        <ul>
                            <li><button disabled class="btn-counter" id="retractall_bid_btn">Retract lot</button></li>
                            <!-- <li><button disabled class="btn-counter" id="stop_auction_btn">Close</button></li> -->
                        </ul>
                    </fieldset>
                </div>
            </div>
        </div>
        <hr>
        <div class="aution-table responsive-on-1200">
            <table>
                <thead>
                <tr>
                    <th>Entered</th>
                    <th>Lot</th>
                    <th>Item Name</th>
                    <th>Reg No</th>
                    <th>Make</th>
                    <th>Model</th>
                    <th>Seller code</th>
                    <th>Seller Name</th>
                    <th>Reserve</th>
                    <th>Sale price</th>
                    <th>B/No</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody class="sold_items_list" id="sold_items_list">
                <!-- <tr>
                  <td data-label="Entered">28/02/2020</td>
                  <td data-label="Lot">1</td>
                  <td data-label="Reg No">1161180</td>
                  <td data-label="Make">Office</td>
                  <td data-label="Model">Furnitures</td>
                  <td data-label="Saller code">001234</td>
                  <td data-label="Saller Name">Shaker Mohamed Ismail</td>
                  <td data-label="Reserve">6000</td>
                  <td data-label="Sale price">0.00</td>
                  <td data-label="Buyer">*PB* Mohamed Ali</td>
                  <td data-label="B/No">1171</td>
                  <td data-label="Status">
                    <select>
                      <option>Sold</option>
                      <option>Un-Sold</option>
                      <option>On approval </option>
                    </select>
                  </td>
                </tr> -->
                </tbody>
            </table>
        </div>
        <div class="button-row">
            <!-- <button class="btn-inc" disabled id="rollback_bid_btn">Roll Back</button> -->
        </div>
    </div>

</main>

<!-- Notify JS Files -->
<script src="<?php echo base_url(); ?>assets_admin/vendors/pnotify/dist/pnotify.js"></script>
<script src="<?php echo base_url(); ?>assets_admin/vendors/pnotify/dist/pnotify.buttons.js"></script>
<script src="<?php echo base_url(); ?>assets_admin/vendors/pnotify/dist/pnotify.nonblock.js"></script>

<script type="text/javascript">
    var token_name = '<?= $this->security->get_csrf_token_name();?>';
    var token_value = '<?=$this->security->get_csrf_hash();?>';

    $('.auction_list_option').on('change',function(){
        get_auction_items();
    });

    function get_auction_items(){
        // var auction_id = this.value;
        var auction_id = $('.auction_list_option').val();
        $('.bidder-button').attr("disabled", true);
        $('#initial_bid_btn').attr("disabled", true);
        $('#retract_bid_btn').attr("disabled", true);
        $('#retractall_bid_btn').attr("disabled", true);
        $('#sold_bid_btn').attr("disabled", true);
        $('#notsold_bid_btn').attr("disabled", true);

        $('#sold_approval_bid_btn').attr("disabled", true);
        $('#search').attr("disabled", true);
        $('#search').val("");
        // alert(auction_id);
        $.ajax({
            url: "<?php echo base_url(); ?>" + "livecontroller/getAuctionItems",
            type: 'post',
            // dataType: 'default: Intelligent Guess (Other values: xml, json, script, or html)',
            data: {auction_id: auction_id, [token_name]:token_value},
            beforeSend: function(){
                // $('#loading').show();
                $('#auctions_items_list_tab tbody').html('<img src="<?php echo base_url(); ?>assets_admin/images/load.gif">');
            },
            success: function(data) {
                var objData = jQuery.parseJSON(data);
                liveBidLog(auction_id);
                if(objData.error == false){
                    $('#auctions_items_list_tab tbody').html(objData.response);
                    $('#live_auction_start_time').html(objData.startTime);
                    $('.entitycount').html(objData.entitycount);
                    $('#bid_start_button').attr('disabled',false);
                    $('#search').attr("disabled", false);
                    $('#lot_number_div').html('<h3>No Active Lot</h3>');
                }else{
                    $('#auctions_items_list_tab tbody').html('');
                    $('#bid_start_button').attr('disabled',true);
                }
            }
        });
    }

    $('#search').on('keyup',function(){
        var auction_id = $('.auction_list_option').val();
        var search = $('#search').val();

        getActiveAuctionItems(auction_id, search);
    });

    function getActiveAuctionItems(auction_id,search=''){
        $.ajax({
            url: "<?php echo base_url(); ?>" + "livecontroller/getAuctionItems",
            type: 'post',
            data: {auction_id: auction_id, search: search, [token_name]:token_value},
            beforeSend: function(){
                $('#auctions_items_list_tab tbody').html('<img src="<?php echo base_url(); ?>assets_admin/images/load.gif">');
            },
            success: function(data) {
                var objData = jQuery.parseJSON(data);
                if(objData.error == false){
                    $('#auctions_items_list_tab tbody').html(objData.response);
                    $('#live_auction_start_time').html(objData.startTime);
                    $('.entitycount').html(objData.entitycount);
                    $('#bid_start_button').attr('disabled',false);
                }else{
                    $('#auctions_items_list_tab tbody').html('');
                    $('#bid_start_button').attr('disabled',true);
                }
            }
        });
    }

    window.setInterval(function(){
        // getSoldItemCount();
        getLiveUserList();
        // getLiveSoldItems();
        $('.col-hide').hide();
        $('.ststus_dropdown').attr('disabled', true);
    }, 5000);


    // getAuctionUsersList

    function getLiveUserList(){

        // getAuctionUsersList
        /*if ($('#bid_start_button').hasClass('ozStoped')){
          var auction_id = $('.auction_list_option').val();
          // var item_id = $('#item_id').val();
          if(auction_id){
            $.ajax({
              url: "<?php echo base_url(); ?>" + "livecontroller/getAuctionUsersList",
            type: 'post',
            data: {auction_id: auction_id},
            beforeSend: function(){
              $('#online_user_list').html('<img src="<?php echo base_url(); ?>assets_admin/images/load.gif">');
            },
            success: function(data) {
              var objData = jQuery.parseJSON(data);
              if(objData.error == false){
                $('#online_user_list').html(objData.response);
              }else{
                $('#online_user_list').html(objData.response);
              }
            }
          });
        }
      }*/
    }

    // sold_item_count

    // function getSoldItemCount(){

    //   if ($('#bid_start_button').hasClass('ozStoped')){
    //     $('#initial_bid_field').attr('disabled',false);
    //   var auction_id = $('.auction_list_option').val();
    //   // var item_id = $('#item_id').val();
    //   if(auction_id){

    //     $.ajax({
    //     url: "<?php echo base_url(); ?>" + "livecontroller/getSoldItemCount",
    //     type: 'post',
    //     data: {auction_id: auction_id},
    //     beforeSend: function(){
    //       //$('#auctions_items_list_tab tbody').html('<img src="<?php echo base_url(); ?>assets_admin/images/load.gif">');
    //     },
    //     success: function(data) {
    //       var objData = jQuery.parseJSON(data);
    //       if(objData.error == false){
    //         $('#sold_item_count').html('Sold: '+objData.response);
    //       }else{
    //         // $('#auctions_items_list_tab tbody').html('');
    //       }
    //     }
    //   });
    //   }
    //   }
    // }

    // getAuctionSoldItems

    function getLiveSoldItems(){

        if ($('#bid_start_button').hasClass('ozStoped')){
            var auction_id = $('.auction_list_option').val();
            // var item_id = $('#item_id').val();
            if(auction_id){

                $.ajax({
                    url: "<?php echo base_url(); ?>" + "livecontroller/getAuctionSoldItems",
                    type: 'post',
                    data: {auction_id: auction_id, [token_name]:token_value},
                    beforeSend: function(){
                        //$('.sold_items_list').html('<img src="<?php echo base_url(); ?>assets_admin/images/load.gif">');
                    },
                    success: function(data) {
                        var objData = jQuery.parseJSON(data);
                        if(objData.error == false){
                            $('.sold_items_list').html(objData.response);
                            $('#sold_item_count').html('Sold: '+objData.count);

                            $('.col-hide').hide();
                            $('.ststus_dropdown').attr('disabled', true);
                        }else{
                            // $('#auctions_items_list_tab tbody').html('');
                        }
                    }
                });
            }
        }
    }

    // place bid for auction
    function placeBid(e){
        $button = $(e).attr('id');
        // console.log($button);
        // alert($button);
        var auction_id = $('.auction_list_option').val();
        var bidAmount = $button;
        var itemid = $('#item_id').val();
        var order_lot_no = $('#order_lot_no').val();
        if($.trim(bidAmount.value).length){}
        // ajax for live auction bid log //live_auction_bid_log
        $.ajax({
            url: "<?php echo base_url(); ?>" + "livecontroller/initialAuctionBid",
            type: 'post',
            data: {auction_id: auction_id,bidAmount: bidAmount,itemid: itemid,lot_no: order_lot_no, bid_type: 'hall', [token_name]:token_value},
            //   beforeSend: function(){
            //      // $('#loading').show();
            // $('#auctions_items_list_tab tbody').html('<img src="<?php echo base_url(); ?>assets_admin/images/load.gif">');
            //   },
            success: function(data) {
                var objData = jQuery.parseJSON(data);
                console.log(objData);
                if (objData.error == false) {
                    var bid = 'bid';
                    // var val = $('#current_bid_amount').html();
                    //comment by mohsin
                    //liveBidLog(auction_id,itemid);
                    $(this).addClass('started');
                    // $('#status_start').html('<div class="alert" ><div class="alert alert-domain alert-success alert-dismissible fade in  msg-alert" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: black"><span aria-hidden="true">×</span> </button>' + objData.response + '</div></div>');
                    // window.setTimeout(function() {
                    //     $(".msg-alert").fadeTo(500, 0).slideUp(500, function(){
                    //         // $(this).remove();
                    //     });
                    // }, 3000);

                    // $('#current_bid_amount').html(objData.last_bid['bid_amount']);

                    // $('#bid_increment_latest').html(objData.bidAmount);
                    $('.bidder-button').removeAttr("disabled", false);

                }else{
                    PNotify.removeAll();
                    new PNotify({
                        title: 'Error!',
                        text: ""+ objData.response +"",
                        type: 'error',
                        addclass: 'custom-error',
                        styling: 'bootstrap3'
                    });
                }
            }
        });
    }

    function liveBidLog(auctionId,itemId=''){


        $.ajax({
            url: "<?php echo base_url(); ?>" + "livecontroller/bidLogAPi",
            type: 'post',
            // dataType: 'default: Intelligent Guess (Other values: xml, json, script, or html)',
            data: {auctionId: auctionId,itemId: itemId, [token_name]:token_value},
            beforeSend: function(){
                // $('#loading').show();
                $('#live_bid_log').html('<img src="<?php echo base_url(); ?>assets_admin/images/load.gif">');
            },
            success: function(data) {
                var objData = jQuery.parseJSON(data);
                if(objData.error == false){
                    $('#live_bid_log').html(objData.response);
                }else{
                    // $('#live_bid_log').html('');
                }
            }
        });

    }

    // to start live auction
    $('#bid_start_button').on('click',function(){
        // alert('Bid has been started');
        var status = '';
        if ($(this).hasClass('ozStarted')){

            $(this).removeClass('ozStarted');
            $(this).addClass('ozStoped');
            $(this).html('STOP AUCTION');
            $('.auction_list_option').attr("disabled", true);
            $('#initial_bid_field').attr("disabled", false);
            status = 'start';
        }else{
            $(this).removeClass('ozStoped');
            $(this).addClass('ozStarted');
            $(this).html('START AUCTION');
            status = 'stop';
            $('.auction_list_option').removeAttr("disabled", true);
            $('.bidder-button').attr("disabled", true);
            $('#retract_bid_btn').attr("disabled", true);
            $('#retractall_bid_btn').attr("disabled", true);
            $('#sold_bid_btn').attr("disabled", true);
            $('#notsold_bid_btn').attr("disabled", true);
            $('#sold_approval_bid_btn').attr("disabled", true);
            $('#initial_bid_field').attr("disabled", true);
            $('#initial_bid_field').val('');
        }

        // alert($('.auction_list_option').val());
        var auction_id = $('.auction_list_option').val();
        // ajax for live auction bid log //live_auction_bid_log
        $.ajax({
            url: "<?php echo base_url(); ?>" + "livecontroller/updateLiveAuctionStatus",
            type: 'post',
            data: {auction_id: auction_id,status: status, [token_name]:token_value},
            //   beforeSend: function(){
            //      // $('#loading').show();
            // $('#auctions_items_list_tab tbody').html('<img src="<?php echo base_url(); ?>assets_admin/images/load.gif">');
            //   },
            success: function(data) {
                var objData = jQuery.parseJSON(data);
                if (objData.error == false) {
                    getLiveSoldItems();
                    $(this).addClass('started');

                    // $('#initial_bid_field').attr("disabled", false);
                    //$('#status_start').html('<div class="alert" ><div class="alert alert-domain alert-success alert-dismissible fade in  msg-alert" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: black"><span aria-hidden="true">×</span> </button>' + objData.response + '</div></div>');
                }else{
                    // $('#initial_bid_field').attr("disabled", true);
                    //$('#status_start').html('<div class="alert" ><div class="alert alert-domain alert-danger alert-dismissible fade in  msg-alert" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: black"><span aria-hidden="true">×</span> </button>' + objData.response + '</div></div>');

                }
                // window.setTimeout(function() {
                //     $(".msg-alert").fadeTo(500, 0).slideUp(500, function(){
                //         // $(this).remove();
                //     });
                // }, 3000);
            }
        });

    });
    // to start live auction
    $('#initial_bid_btn').on('click',function(){
        // alert('Initial Bid');

        if ($('#bid_start_button').hasClass('ozStoped')){
            // alert('we can initilized bid');
            var auction_id = $('.auction_list_option').val();
            var bidAmount = $('#initial_bid_field').val();
            var itemid = $('#item_id').val();
            var order_lot_no = $('#order_lot_no').val();
            var initial = 'initial';
            if (bidAmount && bidAmount > 0) {

                // if($.trim(bidAmount.value).length){}
                // ajax for live auction bid log //live_auction_bid_log
                $.ajax({
                    url: "<?php echo base_url(); ?>" + "livecontroller/initialAuctionBid",
                    type: 'post',
                    data: {auction_id: auction_id,bidAmount: bidAmount,itemid: itemid,lot_no: order_lot_no,initial: initial, [token_name]:token_value},
                    //   beforeSend: function(){
                    //      // $('#loading').show();
                    // $('#auctions_items_list_tab tbody').html('<img src="<?php //echo base_url(); ?>assets_admin/images/load.gif">');
                    //   },
                    success: function(data) {
                        var objData = jQuery.parseJSON(data);
                        console.log(objData);
                        if (objData.error == false) {

                            //comment by mohsin
                            //liveBidLog(auction_id,itemid);
                            $(this).addClass('started');


                            $('#auctions_items_list_tab tbody tr').removeAttr('onclick');
                            //$('#current_bid_amount').html(objData.last_bid['bid_amount']);
                            //$('#bid_increment_latest').html(objData.bidAmount);
                            $('.bidder-button').removeAttr("disabled", false);
                            $('#retract_bid_btn').removeAttr("disabled", false);
                            $('#retractall_bid_btn').removeAttr("disabled", false);
                            $('#sold_bid_btn').removeAttr("disabled", false);
                            $('#notsold_bid_btn').removeAttr("disabled", false);
                            $('#sold_approval_bid_btn').removeAttr("disabled", false);
                            $('#initial_bid_field').val('');
                            $('.reauctionBtn').attr('disabled', true);
                        }else{

                        }

                    }
                });
            } else {
                PNotify.removeAll();
                new PNotify({
                    title: 'Error!',
                    text: "Please make sure bid amount is greater then 0.",
                    type: 'error',
                    addclass: 'custom-error',
                    styling: 'bootstrap3'
                });
            }
        }else{
            // alert('we can not');
            PNotify.removeAll();
            new PNotify({
                title: 'Error!',
                text: "You should start auction first.",
                type: 'error',
                addclass: 'custom-error',
                styling: 'bootstrap3'
            });
        }


    });
    // Retract live auction bid
    $('#retract_bid_btn').on('click',function(){
        // alert('retract Bid');

        if ($('#bid_start_button').hasClass('ozStoped')){
            // alert('we can retrect bid');
            var auction_id = $('.auction_list_option').val();
            var item_id = $('#item_id').val();

            // if($.trim(bidAmount.value).length){}

            // ajax for live auction bid log //live_auction_bid_log
            $.ajax({
                url: "<?php echo base_url(); ?>" + "livecontroller/retractAuctionBid",
                type: 'post',
                data: {auction_id: auction_id,item_id: item_id, [token_name]:token_value},
                //   beforeSend: function(){
                //      // $('#loading').show();
                // $('#auctions_items_list_tab tbody').html('<img src="<?php echo base_url(); ?>assets_admin/images/load.gif">');
                //   },
                success: function(data) {
                    var objData = jQuery.parseJSON(data);
                    if(objData.error == false){
                        liveBidLog(auction_id,item_id);
                        // $(this).addClass('started');
                        $('#current_bid_amount').html(objData.bidAmount);
                        $('#bid_increment_latest').html(objData.last_bid);
                        if (objData.bidAmount == 0) {
                            $('.bidder-button').attr("disabled", true);
                            $('.btn-inc').attr("disabled", true);
                            $('#initial_bid_btn').attr("disabled", false);
                            $('.reauctionBtn').attr('disabled', false);
                            $('#auctions_items_list_tab tbody tr').attr("onclick", "double_clk(this)");
                        }

                        PNotify.removeAll();
                        new PNotify({
                            title: 'Success',
                            text: ""+objData.response+"",
                            type: 'success',
                            addclass: 'custom-success',
                            styling: 'bootstrap3'
                        });

                    }else{
                        PNotify.removeAll();
                        new PNotify({
                            title: 'Error!',
                            text: ""+objData.response+"",
                            type: 'error',
                            addclass: 'custom-error',
                            styling: 'bootstrap3'
                        });
                    }
                }
            });
        }


    });

    // Retract all live auction bid of specific item
    $('#retractall_bid_btn').on('click',function(){
        if ($('#bid_start_button').hasClass('ozStoped')){
            var auction_id = $('.auction_list_option').val();
            var item_id = $('#item_id').val();

            // ajax for live auction bid log //retractAllAuctionBid
            $.ajax({
                url: "<?php echo base_url(); ?>" + "livecontroller/retractAllAuctionBid",
                type: 'post',
                data: {auction_id: auction_id,item_id: item_id, [token_name]:token_value},

                success: function(data) {
                    var objData = jQuery.parseJSON(data);
                    if(objData.error == false){
                        liveBidLog(auction_id,item_id);
                        // getActiveAuctionItems(auction_id);
                        // $(this).addClass('started');
                        $('#current_bid_amount').html('0');
                        $('#bid_increment_latest').html('0');
                        $('.bidder-button').attr("disabled", true);
                        $('.btn-inc').attr("disabled", true);
                        $('#initial_bid_btn').attr("disabled", false);
                        $('.reauctionBtn').attr('disabled', false);
                        $(item_id).addClass('active');
                        $('#auctions_items_list_tab tbody tr').attr("onclick", "double_clk(this)");

                        PNotify.removeAll();
                        new PNotify({
                            title: 'Success',
                            text: ""+objData.response+"",
                            type: 'success',
                            addclass: 'custom-success',
                            styling: 'bootstrap3'
                        });
                    }else{
                        $('.bidder-button').attr("disabled", true);
                        $('.btn-inc').attr("disabled", true);
                        $('#initial_bid_btn').attr("disabled", false);

                        PNotify.removeAll();
                        new PNotify({
                            title: 'Error!',
                            text: ""+objData.response+"",
                            type: 'error',
                            addclass: 'custom-error',
                            styling: 'bootstrap3'
                        });
                    }
                }
            });
        }


    });

    // rollback all live auction bid of specific item
    // $('#rollback_bid_btn').on('click',function(){
    //   if ($('#bid_start_button').hasClass('ozStoped')){
    //     var auction_id = $('.auction_list_option').val();
    //     var item_id = $('#item_id').val();
    //     $.ajax({
    //     url: "<?php echo base_url(); ?>" + "livecontroller/rollBackAuctionBid",
    //     type: 'post',
    //     data: {auction_id: auction_id,item_id: item_id},

    //       success: function(data) {
    //         var objData = jQuery.parseJSON(data);
    //         if(objData.error == false){
    //         liveBidLog(auction_id,item_id);
    //         getActiveAuctionItems(auction_id);
    //         // $(this).addClass('started');
    //           $('#current_bid_amount').html('0');
    //           $('#bid_increment_latest').html('0');
    //           $(item_id).addClass('active');
    //           $('#initial_bid_btn').attr("disabled", true);
    //         }else{
    //           PNotify.removeAll();
    //           new PNotify({
    //             title: 'Error!',
    //             text: ""+objData.response+"",
    //             type: 'error',
    //             styling: 'bootstrap3'
    //           });
    //         }
    //         $('.bidder-button').attr("disabled", true);
    //         $('#retract_bid_btn').attr("disabled", true);
    //         $('#retractall_bid_btn').attr("disabled", true);
    //         $('#sold_bid_btn').attr("disabled", true);
    //         $('#notsold_bid_btn').attr("disabled", true);
    //         $('#sold_approval_bid_btn').attr("disabled", true);
    //       }
    //     });
    //   }
    // });

    // sold on approval live auction bid
    $('#sold_approval_bid_btn').on('click',function(){

        if ($('#bid_start_button').hasClass('ozStoped')){

            var auction_id = $('.auction_list_option').val();
            var item_id = $('#item_id').val();
            // ajax for sale on approva live auction item
            $.ajax({
                url: "<?php echo base_url(); ?>" + "livecontroller/approvalSoldAuctionBid",
                type: 'post',
                data: {auction_id: auction_id,item_id: item_id, [token_name]:token_value},
                success: function(data) {
                    var objData = jQuery.parseJSON(data);
                    if(objData.error == false){
                        getLiveSoldItems();
                        getActiveAuctionItems(auction_id);
                        liveBidLog(auction_id,item_id);

                        PNotify.removeAll();
                        new PNotify({
                            title: 'Success',
                            text: ""+objData.response+"",
                            type: 'success',
                            addclass: 'custom-success',
                            styling: 'bootstrap3'
                        });

                        $('.bidder-button').attr("disabled", true);
                        $('#retract_bid_btn').attr("disabled", true);
                        $('#retractall_bid_btn').attr("disabled", true);
                        $('#sold_bid_btn').attr("disabled", true);
                        $('#notsold_bid_btn').attr("disabled", true);
                        $('#sold_approval_bid_btn').attr("disabled", true);
                        $('#initial_bid_btn').attr("disabled", true);
                        $('.reauctionBtn').attr('disabled', false);

                        //remove item details
                        $('#lot_number_div').html('<h3>No Auction Lot</h3>');
                        $('#order_lot_no').val('');
                        $('#live_auction_active_item').html('NA');
                        $('#reserve_price').html('Reserve:');
                        $('#item_id').val('');
                        $('#current_bid_amount').html('0');
                        $('#bid_increment_latest').html('0');
                    }else{
                        PNotify.removeAll();
                        new PNotify({
                            title: 'Error!',
                            text: ""+objData.response+"",
                            type: 'error',
                            addclass: 'custom-error',
                            styling: 'bootstrap3'
                        });
                    }
                }
            });
        }

    });
    // sold on approval live auction bid
    function updateSoldStatus(e){

        if ($('#bid_start_button').hasClass('ozStoped')){

            var auction_id = $(e).data('auctionids');
            var item_id = $(e).data('ids');
            var status = $(e).val();
            // alert(status);
            // ajax for sale on approva live auction item
            $.ajax({
                url: "<?php echo base_url(); ?>" + "livecontroller/updateSoldstatus",
                type: 'post',
                data: {auction_id: auction_id,item_id: item_id,status: status, [token_name]:token_value},
                success: function(data) {
                    var objData = jQuery.parseJSON(data);
                    if(objData.error == false){
                        getActiveAuctionItems(auction_id);
                    }else{

                    }
                }
            });
        }
    }

    $('#notsold_bid_btn').on('click',function(){

        if ($('#bid_start_button').hasClass('ozStoped')){

            var auction_id = $('.auction_list_option').val();
            var item_id = $('#item_id').val();
            // ajax for sale on approva live auction item
            $.ajax({
                url: "<?php echo base_url(); ?>" + "livecontroller/notSoldAuctionBid",
                type: 'post',
                data: {auction_id: auction_id,item_id: item_id, [token_name]:token_value},
                success: function(data) {
                    var objData = jQuery.parseJSON(data);
                    if(objData.error == false){
                        getLiveSoldItems();
                        getActiveAuctionItems(auction_id);
                        liveBidLog(auction_id,item_id);

                        PNotify.removeAll();
                        new PNotify({
                            title: 'Success',
                            text: ""+objData.response+"",
                            type: 'success',
                            addclass: 'custom-success',
                            styling: 'bootstrap3'
                        });

                        $('.bidder-button').attr("disabled", true);
                        $('#retract_bid_btn').attr("disabled", true);
                        $('#retractall_bid_btn').attr("disabled", true);
                        $('#sold_bid_btn').attr("disabled", true);
                        $('#notsold_bid_btn').attr("disabled", true);
                        $('#sold_approval_bid_btn').attr("disabled", true);
                        $('#initial_bid_btn').attr("disabled", true);
                        $('.reauctionBtn').attr('disabled', false);

                        //remove item details
                        $('#lot_number_div').html('<h3>No Auction Lot</h3>');
                        $('#order_lot_no').val('');
                        $('#live_auction_active_item').html('NA');
                        $('#reserve_price').html('Reserve:');
                        $('#item_id').val('');
                        $('#current_bid_amount').html('0');
                        $('#bid_increment_latest').html('0');
                    }else{
                        PNotify.removeAll();
                        new PNotify({
                            title: 'Error!',
                            text: ""+objData.response+"",
                            type: 'error',
                            addclass: 'custom-error',
                            styling: 'bootstrap3'
                        });
                    }
                }
            });
        }

    });

    // sold on approval live auction bid
    $('#sold_bid_btn').on('click',function(){

        if ($('#bid_start_button').hasClass('ozStoped')){
            var auction_id = $('.auction_list_option').val();
            var item_id = $('#item_id').val();
            // ajax for sale live auction item
            $.ajax({
                url: "<?php echo base_url(); ?>" + "livecontroller/soldAuctionBid",
                type: 'post',
                data: {auction_id: auction_id,item_id: item_id, [token_name]:token_value},
                success: function(data) {
                    var objData = jQuery.parseJSON(data);
                    if(objData.error == false){
                        getLiveSoldItems();
                        getActiveAuctionItems(auction_id);
                        liveBidLog(auction_id,item_id);

                        PNotify.removeAll();
                        new PNotify({
                            title: 'Success',
                            text: ""+objData.response+"",
                            type: 'success',
                            addclass: 'custom-success',
                            styling: 'bootstrap3'
                        });

                        $('.bidder-button').attr("disabled", true);
                        $('#retract_bid_btn').attr("disabled", true);
                        $('#retractall_bid_btn').attr("disabled", true);
                        $('#sold_bid_btn').attr("disabled", true);
                        $('#notsold_bid_btn').attr("disabled", true);
                        $('#sold_approval_bid_btn').attr("disabled", true);
                        $('#initial_bid_btn').attr("disabled", true);
                        $('.reauctionBtn').attr('disabled', false);

                        //remove item details
                        $('#lot_number_div').html('<h3>No Auction Lot</h3>');
                        $('#order_lot_no').val('');
                        $('#live_auction_active_item').html('NA');
                        $('#reserve_price').html('Reserve:');
                        $('#item_id').val('');
                        $('#current_bid_amount').html('0');
                        $('#bid_increment_latest').html('0');
                    }else{
                        PNotify.removeAll();
                        new PNotify({
                            title: 'Error!',
                            text: ""+objData.response+"",
                            type: 'error',
                            addclass: 'custom-error',
                            styling: 'bootstrap3'
                        });
                    }
                }
            });
        }

    });

    // select items for bid
    function double_clk(e){
        var id = $(e).attr('id');
        var item_id = $(e).attr('item_id');
        var auction_id = $(e).data('auctionid');
        //do something with id
        if ($(e).hasClass('active')){
        }else{

            $('#auctions_items_list_tab tbody tr').removeClass('active');
            $('#initial_bid_btn').attr('disabled',false);
            $('#initial_bid_field').attr("disabled", false);

            $(e).addClass('active');

            //comment by mohsin
            //liveBidLog(auction_id,id);
            // ajax for live auction bid log //live_auction_bid_log
            $.ajax({
                url: "<?php echo base_url(); ?>" + "livecontroller/getAuctionItemDetail",
                type: 'post',
                data: {'id': id, 'auction_id': auction_id, 'item_id':item_id, [token_name]:token_value},
                //   beforeSend: function(){
                //      // $('#loading').show();
                // $('#auctions_items_list_tab tbody').html('<img src="<?php echo base_url(); ?>assets_admin/images/load.gif">');
                //   },
                success: function(data) {
                    //console.log(data);
                    var objData = jQuery.parseJSON(data);
                    if(objData.error == false){

                        $.each(objData.response, function(k, v) {

                            // console.log(v);
                            /// do stuff
                            if(k == 'order_lot_no'){
                                $('#lot_number_div').html('<h3>Lot # '+v+'</h3>');
                                $('#order_lot_no').val(v);
                            }
                            if(k == 'name'){
                                var language = "<?= $language; ?>";
                                v = $.parseJSON(v);
                                $('#live_auction_active_item').html(v[language]);
                            }
                            if(k == 'reserve_price'){
                                $('#reserve_price').html('Reserve: '+v);
                            }
                            if(k == 'item_id'){
                                $('#item_id').val(v);
                                var value = $('#current_bid_amount').html();


                            }
                            // console.log(k+' '+v);

                        });

                        $('#result').html('');

                    }else{
                        PNotify.removeAll();
                        new PNotify({
                            title: 'Error!',
                            text: ""+objData.response+"",
                            type: 'error',
                            addclass: 'custom-error',
                            styling: 'bootstrap3'
                        });
                    }
                }
            });


        }
    }
</script>

<script>
    $(document).ready( function(){
        $('#initial_bid_field').attr('disabled',true);
        $(document).on("click", "#sold_items_list tr.selected", function() {
            var lot = $(this).find("td:nth-child(2)").text();
            var Reg_no = $(this).find("td:nth-child(3)").text();
            console.log(lot);
            console.log(Reg_no);

            // $("#selected_seller option:selected").text(text);
            // $("#selected_seller option:selected").val(id);
        });
    });
</script>

<script src="https://js.pusher.com/6.0/pusher.min.js"></script>

<script>

    function brodcast_pusher(itemID, auctionid){
        var url = '<?= base_url('cronjob/broadcast_pusher_low_load/'); ?>'+itemID+'/'+auctionid;
        console.log(url)
        $.ajax({
            type: 'post',
            url: url,
            data: {[token_name] : token_value},
            // data: {},
            success: function(data){
                var push = $.parseJSON(data)
                console.log(push);
                var language = "<?= $language; ?>"

                //hold current bid info
                //$("#auctionIdHolder").val(push.auction_id);
                //$("#itemIdHolder").val(push.item_id);

                //update bid amount if available
                if(push.cb_amount){
                    $("#current_bid_amount").html(numberWithCommas(push.cb_amount));
                    //$("#bidAmountHolder").val(push.cb_amount);
                }

                if(push.current_bid){
                    $("#bid_increment_latest").html(numberWithCommas(push.current_bid.bid_increment_amount));
                    //$("#bidAmountHolder").val(push.cb_amount);
                }

                //update lot number if available
                if(push.lot_number){
                    $("#lot_number_div").html(push.lot_number.order_lot_no);
                }

                //update bidder type Hall or On line if available
                if(push.current_bid){
                    var bidderTypePhrase = 'With '+push.current_bid.bid_type.toProperCase()+' Bidder';
                    $("#bidderType").html(bidderTypePhrase);
                }

                if ($('#show_bid_logs').is(':checked')) {
                    //update bid log
                    liveBidLog(push.auction_id, push.item_id);

                }
                //update online connected users
                if ($('#show_online_user').is(':checked')) {

                    onlineUsers(push.auction_id, push.item_id);
                }

                //update item images
                //updateImages(push.item_images);

                //update all lots table
                //allLots(push.auction_id);

                //update winning lots table
                //winningLots(push.auction_id);

                //update current lot tab
                //updateCurrentLot(push.item_id,push.auction_id);

                //upcoming auctions
                // updateUpcomingAuctions(push.auction_id);

                //enable bid buttons
                //$(".bid-btn").removeAttr("disabled");
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
    channel.bind('live-event', function(push) {
        brodcast_pusher(push.item_id, push.auction_id);
    });

    function numberWithCommas(x) {
        return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }

    String.prototype.toProperCase = function () {
        return this.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();});
    };

</script>

<script>
    function onlineUsers(auctionId,itemId){
        var postData = {'auction_id':auctionId, 'item_id':itemId, [token_name]:token_value};
        $.ajax({
            url: "<?= base_url('live_auction_controller/livecontroller/get_online_users'); ?>",
            type: 'post',
            data: postData,
            beforeSend: function(){
                $('#online_user_list').html('<img src="<?= base_url('assets_admin/images/load.gif'); ?>">');
            },
            success: function(res) {
                console.log(res);
                var objData = $.parseJSON(res);
                if(objData.status == true){
                    $('#online_user_list').html(objData.response);
                }else{
                    $('#online_user_list').html('<h4>No online user connected.</h4>');
                }
            }
        });
    }

    function reAuction(e){
        var auctionId = $(e).data('auctionids');
        var itemId = $(e).data('ids');
        console.log(auctionId);
        console.log(itemId);
        var postData = {'auction_id':auctionId, 'item_id':itemId, [token_name]:token_value};
        $.ajax({
            url: "<?= base_url('live_auction_controller/livecontroller/return_to_reauction'); ?>",
            type: 'post',
            data: postData,
            // beforeSend: function(){
            //     $('#online_user_list').html('<img src="<?= base_url('assets_admin/images/load.gif'); ?>">');
            // },
            success: function(res) {
                console.log(res);
                var objData = $.parseJSON(res);
                if(objData.status == true){
                    getLiveSoldItems();
                    getActiveAuctionItems(auctionId);
                    liveBidLog(auctionId,itemId);

                    PNotify.removeAll();
                    new PNotify({
                        title: 'Success',
                        text: ""+objData.msg+"",
                        type: 'success',
                        addclass: 'custom-success',
                        styling: 'bootstrap3'
                    });

                    $('.bidder-button').attr("disabled", true);
                    $('#retract_bid_btn').attr("disabled", true);
                    $('#retractall_bid_btn').attr("disabled", true);
                    $('#sold_bid_btn').attr("disabled", true);
                    $('#notsold_bid_btn').attr("disabled", true);
                    $('#sold_approval_bid_btn').attr("disabled", true);
                    $('#initial_bid_btn').attr("disabled", true);
                    $('.reauctionBtn').attr('disabled', false);

                    //remove item details
                    $('#lot_number_div').html('<h3>No Auction Lot</h3>');
                    $('#order_lot_no').val('');
                    $('#live_auction_active_item').html('NA');
                    $('#reserve_price').html('Reserve:');
                    $('#item_id').val('');
                    $('#current_bid_amount').html('0');
                    $('#bid_increment_latest').html('0');
                }else{
                    PNotify.removeAll();
                    new PNotify({
                        title: 'Error!',
                        text: ""+objData.msg+"",
                        type: 'error',
                        addclass: 'custom-error',
                        styling: 'bootstrap3'
                    });
                }
            }
        });
    }
</script>