<script src="https://js.pusher.com/6.0/pusher.min.js"></script>
<style>
    .active2 {
        filter: invert(20%) sepia(100%) saturate(5283%) hue-rotate(268deg) brightness(50%) contrast(80%);

    }

    .grecaptcha-badge {
        visibility: hidden;
    }

    .auction-box {
        background: #FFFFFF;
        border: 1px solid #DCDCDC;
        box-sizing: border-box;
        border-radius: 4px;
        position: relative;
    }

    .paddingBox {
        padding: 0 10px 0 0;
    }

    .auction-images {
        width: 100%;
        height: 200px;
        border-top-right-radius: 5px;
        border-top-left-radius: 5px;
    }

    .auction-featured {
        display: flex;
        flex-direction: row;
        align-items: flex-start;
        padding: 8px;
        position: absolute;
        right: 10px;
        top: 10px;
        background: #5C02B5;
        border-radius: 4px;
    }

    .feature-text {
        font-family: Roboto;
        font-style: normal;
        font-weight: normal;
        font-size: 12px;
        line-height: 14px;
        color: #FFFFFF;
        flex: none;
        order: 1;
        flex-grow: 0;
        margin: 0px 4px;
    }

    .bid-text-cont {
        position: absolute;
        left: 0px;
        bottom: 0px;
        background: #FA8500;
        border-top-right-radius: 5px
    }

    .bid-text {
        font-family: Montserrat;
        font-style: normal;
        font-weight: 500;
        font-size: 15px;
        line-height: 18px;
        letter-spacing: -0.18px;
        color: #FFFFFF;
        margin: 0;
        padding: 15px;
        text-align: : center;
    }

    .heart-icon-cont {
        position: absolute;
        right: 10px;
        bottom: -20px;
        background: #FFF;
        width: 40px;
        height: 40px;
        border-radius: 20px;
        border: 1px solid #DCDCDC;
        padding: 10px 3px;
    }

    .heart-icon-image {
        width: 20px;
        height: 20px;
        margin: 9px;
        color: red;
    }

    .auction-desc-cont {
        width: 100%;
        background: #FFF;

    }

    .list-group-item .auction-desc-cont .auction-desc-padding {
        padding: 10px 6px;
    }

    .list-group-item .auction-desc-cont .auction-desc-padding .listrow1 {
        float: left;
        width: 50%;
        padding-right: 10px;
    }

    .list-group-item .auction-desc-cont .auction-desc-padding .listrow2 {
        display: inline-block;
        width: 50%;
    }

    .list-group-item .bidlst {
        display: block;
        width: 130px;
        border-radius: 4px;
        background: #fa8502;
        margin: 8px auto;
    }

    .list-group-item .auction-bid-button-text {
        padding: 2px 0;
        margin: 0;
    }

    .auction-desc-padding {
        padding: 15px
    }

    .auction-desc-heading {
        font-family: Montserrat;
        font-style: normal;
        font-weight: 600;
        font-size: 16px;
        line-height: 28px;
        letter-spacing: -0.18px;
        color: #000000;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .auction-desc-inner {
        font-family: Montserrat;
        font-style: normal;
        font-weight: 500;
        font-size: 14px;
        line-height: 28px;
        letter-spacing: -0.18px;
        color: #646464;
        margin-bottom: 5px;
    }

    .auction-desc-inner-color {
        color: #FA8500;
    }

    .auction-desc-heading-down {
        font-family: Montserrat;
        font-style: normal;
        font-weight: 500;
        font-size: 16px;
        line-height: 28px;
        letter-spacing: -0.18px;
        color: #FF2828;
        margin-bottom: 0
    }

    .auction-bid-button {
        background: #5C02B5;
        width: 100%;
        border-bottom-left-radius: 5px;
        border-bottom-right-radius: 5px;
    }

    .auction-bid-button-text {
        font-family: Montserrat;
        font-style: normal;
        font-weight: 600;
        font-size: 16px;
        line-height: 28px;
        text-align: center;
        letter-spacing: -0.18px;
        color: #FFFFFF;
        padding: 15px
    }

    .auction-bid-button-red {
        background: #FF2828;
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

    .view-group {
        display: -ms-flexbox;
        display: flex;
        -ms-flex-direction: row;
        flex-direction: row;
        padding-left: 0;
        margin-bottom: 0;
    }

    .thumbnail {
        margin-bottom: 30px;
        padding: 0px;
        -webkit-border-radius: 0px;
        -moz-border-radius: 0px;
        border-radius: 0px;
    }

    .item.list-group-item {
        float: none;
        width: 100%;
        background-color: #fff;
        margin-bottom: 30px;
        -ms-flex: 0 0 100%;
        flex: 0 0 100%;
        max-width: 100%;
        border-top: 1px solid #ccc;
        border-bottom: 1px solid #ccc;
    }

    .item.list-group-item .img-event {
        float: left;
        width: 30%;
    }

    .item.list-group-item .list-group-image {
        margin-right: 10px;
    }

    .item.list-group-item .thumbnail {
        margin-bottom: 0px;
        display: inline-block;
    }

    .item.list-group-item .caption {
        float: left;
        width: 70%;
        margin: 0;
    }

    .list-group-item .auction-box {
        display: inline-block;
        width: 280px;
        vertical-align: top;
    }

    .list-group-item .auction-box .auction-images {
        height: 190px;
    }

    .list-group-item .auction-box .heart-icon-cont {
        bottom: 4px;
        right: 0;
    }

    .list-group-item .auction-desc-heading-down {
        text-align: left;
        display: inline-block;
        width: 100%;
    }

    .list-group-item .auction-desc-cont {
        width: 47%;
        display: inline-block;
    }

    .item.list-group-item:before,
    .item.list-group-item:after {
        display: table;
        content: " ";
    }

    .list-group-item {
        padding: 0 !important;
    }

    .item.list-group-item:after {
        clear: both;
    }

    .bidlistbtn {
        width: 18%;
        float: right;
        height: 191px;
        background: #5c03b5;
        text-align: center;
        padding: 14px 0;
    }

    .bidlistbtn img {
        width: 110px;
        margin: 3px 0;
    }
</style>
<?php
if ($auctionType == 'live') {
    if (isset($auctionDetail) && !empty($auctionDetail)) {
        $auctionDetail = $auctionDetail[0];
        $auctionTitle = json_decode($auctionDetail['title']);
        $auctionDetailsTxt = json_decode($auctionDetail['detail']);
        $auctionPopupTxt = json_decode($auctionDetail['popup_message']);

        $f = '2021-05-25 22:32:59';


 $liveEndDate = new DateTime(date('Y-m-d H:i:s', strtotime($f )));
                $liveNnow = new DateTime();

                // The diff-methods returns a new DateInterval-object...
                $diffl = $liveEndDate->diff($liveNnow);

                $daysl = $diffl->format('%a');
                $hoursl = $diffl->format('%h');
                if($daysl < '2' && $hoursl == '0'){
                    $clShow = 'blink';
                }else{
                  $clShow = ' ';
                }

$comeDay =  date("Y-m-d" ,strtotime($auctionDetail['start_time'] . ' +1 day'));
$lessDay =  date("Y-m-d" ,strtotime($auctionDetail['start_time']. ' -1 day'));
$_curDateTime =  date("Y-m-d" ,strtotime($auctionDetail['start_time']));
$_curDate =  date("Y-m-d" ,strtotime($auctionDetail['start_time']));
$toDayl =  date("Y-m-d" ,time());

/*print_r($lessDay);
print_r('<br/>');
print_r( $_curDate);
print_r('<br/>');
print_r($toDayl);
*/
$clasLive ='';
// if($toDayl > $lessDay){
//     $clasLive = ' blink ';
// }else{
//     $clasLive = ' ';
// }


if($toDayl == $lessDay || $toDayl == $_curDateTime){
    @$clasLive = ' blink ';
}else{
   @$clasLive = ' ';
}

/*if($lessDay < $_curDateTime && $toDayl < $_curDate){
    print_r('show blink');
}*/

?>
<script>
    var aucId = '<?=$auctionDetail['id'];?>';
    $(document).ready(function() {       
       $('#myTab .wll').addClass('<?=$clasLive?>');
    });
    
</script>
        <div class="liveacution-banner">
            <div class="row align-items-center">
                <div class="col-lg-3 col-md-5">
                    <div class="image">
                        <img src="<?= NEW_ASSETS_USER; ?>/new/images/liveauction.png" />
                    </div>
                </div>
                <div class="col-lg-6 col-md-7">
                    <div class="detail">
                        <h2><?= $auctionDetailsTxt->$language; ?></h2>
                        <ul>
                            <li><?= $this->lang->line('auction_location'); ?>:</li>
                            <li><?= $this->lang->line('dubai'); ?></li>
                        </ul>
                        <ul>
                            <li><?= $this->lang->line('start_date_time'); ?>:</li>
                            <li>
                                <?php
                                $startTime = strtotime($auctionDetail['start_time']);
                                $months = array(
    "Jan" => "يناير",
    "Feb" => "فبراير",
    "Mar" => "مارس",
    "Apr" => "أبريل",
    "May" => "مايو",
    "Jun" => "يونيو",
    "Jul" => "يوليو",
    "Aug" => "أغسطس",
    "Sep" => "سبتمبر",
    "Oct" => "أكتوبر",
    "Nov" => "نوفمبر",
    "Dec" => "ديسمبر"
);
    $en_month = date("M", strtotime($auctionDetail['start_time']));
   
                         $mday = date("d", strtotime($auctionDetail['start_time']));
                         $myear = date("Y", strtotime($auctionDetail['start_time']));

                                
                               /* if ($language == 'arabic') {
                                    $fmt = datefmt_create("ar_AE", IntlDateFormatter::LONG, IntlDateFormatter::MEDIUM, 'Asia/Dubai', IntlDateFormatter::GREGORIAN);
                                    echo datefmt_format($fmt, $startTime);
                                } else {
                                    echo date('dS M Y H:i', $startTime);
                                }
*/
                                 if($language == 'arabic'){
                                    $ar_month = $months[$en_month];
                                    echo $mday.' '.$ar_month.' '.$myear .'  '.date('h:i ' ,$startTime);
                                }else{
                                    echo date('dS M Y H:i', $startTime); 
                                  }

                                ?>
                            </li>
                        </ul>
                        <!-- add new -->
                        <ul class="show-on-1000">
                            <li>
                                <a class="btn btn-default" href="<?= base_url('search/catalog/') . $auctionDetail['id']; ?>">
                                    <?= $this->lang->line('print_c'); ?>
                                    <img src="<?= NEW_ASSETS_USER; ?>/new/images/breadcrumb-arrow.png">
                                </a>
                            </li>

                           
                            <button type="button" class="btn btn-default redirectAuction " ><?= $this->lang->line('open_live'); ?>
                            </button>
                       
                        </ul>
                        <!-- add new-end -->
                    </div>
                </div>
                <div class="col-lg-3 col-md-12">
                    <div class="button-row">
                        <a href="<?= base_url('search/catalog/') . $auctionDetail['id']; ?>" class="btn btn-default" target="_blank"> <?= $this->lang->line('print_c'); ?></a>
                      
                           
                            <button type="button" class="btn btn-default redirectAuction" ><?= $this->lang->line('open_live'); ?>
                            </button>
                       
                    </div>
                </div>
            </div>
        </div>
        <h2 class="inner-title"><?= $this->lang->line('catalog_small'); ?>:</h2>
<?php }
} ?>
<?php if (empty($items)) { ?>
    <div class="norecordfound  ntfnd">
        <img src="/assets_admin/images/comingsoonimg.png" align="center" />
        <h1 class="no-record">
            <?= $not_found_message; ?>
        </h1>
    </div>
<?php } else { ?>
    <?php
    $categoryData1 = $this->db->get_where('item_category', ['id' => $categoryId])->row_array();
    $titCat = json_decode($categoryData1['title']);
    ?>
    <div style="margin: 20px 0;" class="sorting d-flex align-items-center justify-content-between">
        <p><?= $this->lang->line('showing'); ?> <?= $totalDisplayRecords; ?> <?= $this->lang->line('of'); ?> <?= $totalRecords; ?> <?= $titCat->$language; ?></p>

        <div class="sort-list" style="position:relative" ;>
            <!-- <div style="display:inline-block">
        <div class="btn-group">
            <button class="btn active2 btnlstgrdvew" id="grid" style="font-size: 20px;padding: 0 4px;background: transparent;color:#ccc">
                <i class="fa fa-th-large"></i>
            </button>
            <button class="btn ml-2 btnlstgrdvew" id="list" style="font-size: 20px;padding: 0 4px;background: transparent;color:#ccc">
                <i class="fa fa-bars"></i>
            </button>
            
        </div>
    </div> -->

            <div class="btn-group">
                <button class="btn  btnlstgrdvew active2" id="grid" style="font-size: 20px;padding: 0 4px;background: transparent;color:#ccc">
                    <img src="/assets_user/new/images/gridimg.svg">

                </button>
                <button class="btn ml-2 btnlstgrdvew" id="list" style="font-size: 20px;padding: 0 4px;background: transparent;color:#ccc">
                    <img src="/assets_user/new/images/listimg.svg">
                </button>

            </div>

            <div class="dropdown bootstrap-select">
                <select id="sortBy" class="selectpicker">
                    <option <?= ($orderBy == 'featured') ? 'selected' : ''; ?> value="featured"><?= $this->lang->line('featured'); ?></option>
                    <option <?= ($orderBy == 'latest') ? 'selected' : ''; ?> value="latest"><?= $this->lang->line('latest'); ?></option>
                    <?php if ($auctionType != 'live') { ?>
                        <option <?= ($orderBy == 'hp') ? 'selected' : ''; ?> value="hp"><?= $this->lang->line('high_price'); ?></option>
                        <option <?= ($orderBy == 'lp') ? 'selected' : ''; ?> value="lp"><?= $this->lang->line('low_price'); ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>
    </div>
    <div class="container" style="margin-top:20px;">
        <!--  <div class="pagination d-flex align-items-center justify-content-end">
      <nav aria-label="Page navigation">
         <?= $paginationLinks; ?>
      </nav>
   </div> -->
        <div id="products" class="productstb">

            <div class="row">
                <?php
                $col = 1;
                foreach ($items as $key => $auction_item) {
                    $auction_id = $auction_item['auction_id'];
                    $category_id = $auction_item['category_id'];
                    $categoryData = $this->db->get_where('item_category', ['id' => $category_id])->row_array();
                    $visit_count = $this->db->where('item_id', $auction_item['item_id'])->where('auction_id', $auction_item['auction_id'])->from('online_auction_item_visits')->count_all_results();
                    /*$bid_info = $this->db->where('item_id',$auction_item['item_id'])
                        ->where('auction_id',$auction_item['auction_id'])
                        ->order_by('id', 'desc')
                        ->from('bid')->get()->result_array();
                    $count = count($bid_info);*/

                    //$sold = $this->db->get_where('auction_items',['sold_status' =>'sold'])->result_array();
                    //$bid_end_time = strtotime($auction_item['bid_end_time']);
                    $item_images_ids = explode(',', $auction_item['item_images']);
                    $images = $this->db->where_in('id', $item_images_ids)->get('files')->result_array();
                    $imagesCount = count($images);
                    if ($images) {
                        $image_src = base_url('uploads/items_documents/') . $auction_item['item_id'] . '/' . $images[0]['name'];
                    } else {
                        $image_src = base_url('assets_admin/images/product-default.jpg');
                    }

                    /*$ratings = 0;
                    $user = $this->session->userdata('logged_in');
                    if($user){
                        $ratings = $this->db->get_where('auction_item_ratings', ['auction_item_id' => $auction_item['auction_item_id'],'user_id' => $user->id])->row_array();
                        if($ratings){
                            $ratings = $ratings['ratings'];
                        }else{
                            $ratings = 0;
                        }
                    }*/
                    if ($language == 'english') {
                        $favorite = "Add to wishlist";
                    } else {
                        $favorite = "أضف إلى قائمة الامنيات";
                    }
                    $favoriteIcon = "favorite_border";
                    $f_icon = '<span class="material-icons">favorite_border</span>';
                    $user = $this->session->userdata('logged_in');
                    if ($user) {
                        $favt = $this->db->get_where('favorites_items', [
                            'item_id' => $auction_item['item_id'],
                            'user_id' => $user->id,
                            'auction_id' => $auction_id
                        ])->row_array();

                        if ($favt) {
                            if ($language == 'english') {
                                $favorite = "Added to wishlist";
                            } else {
                                $favorite = "أضيف لقائمة الأماني";
                            }
                            $f_icon = '<span class="material-icons">favorite</span>';
                            $favoriteIcon = "favorite";
                        } else {
                            if ($language == 'english') {
                                $favorite = "Add to wishlist";
                            } else {
                                $favorite = "أضف إلى قائمة الامنيات";
                            }
                            $f_icon = '<span class="material-icons">favorite_border</span>';

                            $favoriteIcon = "favorite_border";
                        }
                    }
                    if ($auction_item['sold_status'] != 'not') {
                        $_class = 'auction-bid-button-red';
                    } else {
                        $_class = '';
                    }

                    if ($auction_item['item_test_report'] == 'yes') {
                        $_test_report = 'Available';
                    } else {
                        $_test_report = 'Not Available';
                    }


                    $time = $auction_item['bid_end_time'];
                    $date = date('Y-m-d H:i:s');
                ?>
                    <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12 item ">
                        <div class="auction-box">
                            <?php
                            if ($auction_item['item_feature'] == 'yes') { ?>
                                <div class="auction-featured">
                                    <svg width="16" height="14" viewBox="0 0 16 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M14.9376 5.22646C14.7751 4.74209 14.361 4.39365 13.8563 4.31709L10.6579 3.82803L9.22662 0.781152C9.11255 0.537402 8.9313 0.33584 8.70162 0.196777C8.49068 0.0686523 8.24693 0.00146484 8.00005 0.00146484C7.75162 0.00146484 7.50943 0.0686523 7.29849 0.196777C7.0688 0.33584 6.88755 0.537402 6.77349 0.781152L5.34224 3.82803L2.1438 4.31709C1.63912 4.39365 1.22505 4.74209 1.06255 5.22646C0.900055 5.71084 1.02193 6.2374 1.37818 6.60303L3.73287 9.01709L3.17662 12.4233C3.11255 12.8171 3.22349 13.2155 3.4813 13.5187C3.74068 13.8233 4.11724 13.9983 4.51568 13.9983C4.7438 13.9983 4.97037 13.939 5.17193 13.828L8.00005 12.2655L10.8282 13.8296C11.0297 13.9405 11.2563 13.9999 11.4844 13.9999C11.8829 13.9999 12.2594 13.8249 12.5188 13.5202C12.7766 13.2171 12.8876 12.8187 12.8235 12.4249L12.2672 9.01865L14.6219 6.60459C14.9782 6.23896 15.0985 5.71084 14.9376 5.22646Z" fill="#FA8500" />
                                    </svg>
                                    <p class="feature-text"><?= $this->lang->line('featured'); ?></p>
                                </div>
                            <?php } ?>



                            <?php if ($auctionType != 'live') {
                                $item_url = base_url('auction/online-auction/details/') . $auction_item['auction_id'] . '/' . $auction_item['item_id'];
                                $btn = $this->lang->line('bid_now');
                            } else {
                                $item_url = base_url('live-online-detail/') . $auction_item['auction_id'] . '/' . $auction_item['item_id'];
                                $btn = $this->lang->line('view_details');
                            } ?>



                            <?php //if($auctionType != 'live'){ 
                            ?>
                            <a href="<?= $item_url; ?>">
                                <img class="auction-images" src="<?= $image_src; ?>">
                            </a>
                            <?php if ($auctionType != 'live') { ?>
                                <div class="bid-text-cont">
                                    <?php
                                    $displayPrice = $auction_item['final_price'];
                                    /*if(isset($auction_item['current_bid_price']) && !empty($auction_item['current_bid_price'])){
                                $displayPrice = $auction_item['current_bid_price'];
                            }elseif (isset($auction_item['bid_start_price']) && !empty($auction_item['bid_start_price'])){
                                $displayPrice = $auction_item['bid_start_price'];
                            }*/
                                    //    echo $this->lang->line('aed').' '.number_format($displayPrice , 0, ".", ",");

                                    if ($language == 'arabic') :
                                        echo '<p class="bid-text">' . $this->lang->line('current_bid') . ': ' . number_format($displayPrice, 0, ".", ",") . ' ' . $this->lang->line('aed') . '</p>';
                                    else :
                                        echo '<p class="bid-text">' . $this->lang->line('current_bid') . ': ' . $this->lang->line('aed') . ' ' . number_format($displayPrice, 0, ".", ",") . '</p>';

                                    endif;
                                    ?>
                                </div>
                            <?php } ?>
                            <div class="heart-icon-cont">
                                <a href="javascript:void(0);" onclick="doFavt(this)" data-item="<?= $auction_item['item_id']; ?>" data-auction-id="<?= $auction_item['auction_id']; ?>" data-auction-item-id="<?= $auction_item['auction_item_id']; ?>" class="wishlist">
                                    <?= $f_icon; ?>
                                </a>
                            </div>
                            <?php //} 
                            ?>
                        </div>
                        <div class="auction-desc-cont">
                            <div class="auction-desc-padding">
                                <p class="auction-desc-heading">
                                    <?php
                                    $itemName = json_decode($auction_item['item_name']);
                                    echo ($language == 'english') ? strtoupper($itemName->$language) : $itemName->$language;
                                    ?>
                                </p>

                                <div class="listrow1">
                                    <div class="row">
                                        <div class="col">
                                            <p class="auction-desc-inner"><?= $this->lang->line('lot'); ?> </p>
                                        </div>
                                        <div class="col">
                                            <p class="auction-desc-inner auction-desc-inner-color"><?= $auction_item['order_lot_no']; ?></p>
                                        </div>
                                    </div>
                                    <?php if ($categoryData['include_make_model'] == 'yes') { ?>
                                        <div class="row">
                                            <div class="col">
                                                <p class="auction-desc-inner"><?= $this->lang->line('report'); ?></p>
                                            </div>
                                            <div class="col">
                                                <p class="auction-desc-inner auction-desc-inner-color">
                                                    <?= $auction_item['item_test_report'] == 'yes' ? $this->lang->line('available') : $this->lang->line('not_available'); ?>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col">
                                                <p class="auction-desc-inner"><?= $this->lang->line('odometer'); ?></p>
                                            </div>
                                            <div class="col">
                                                <p class="auction-desc-inner auction-desc-inner-color">
                                                    <?php if (isset($auction_item['mileage']) && !empty($auction_item['mileage'])) {
                                                        echo number_format($auction_item['mileage'], 0, ".", ",");
                                                    } else {
                                                        echo "N/A";
                                                    }
                                                    if (isset($auction_item['mileage_type']) && !empty($auction_item['mileage'])) {
                                                        if ($language == 'english') {
                                                            if ($auction_item['mileage_type'] == 'km') {
                                                                $mileageType = ' Km';
                                                            } else {
                                                                $mileageType = ' Mi';
                                                            }
                                                        } else {
                                                            if ($auction_item['mileage_type'] == 'km') {
                                                                $mileageType = ' كيلومتر ';
                                                            } else {
                                                                $mileageType = ' ميل ';
                                                            }
                                                        }
                                                        echo  $mileageType;
                                                    } ?>
                                                </p>
                                            </div>
                                        </div>
                                </div>
                                <div class="listrow2">
                                    <div class="row">
                                        <div class="col">
                                            <p class="auction-desc-inner"><?= $this->lang->line('specifications'); ?></p>
                                        </div>
                                        <div class="col">
                                            <p class="auction-desc-inner auction-desc-inner-color">
                                                <?php
                                                if ($language == 'english') {
                                                    if ($auction_item['specification'] == 'GCC') {
                                                        $specsType = 'GCC';
                                                    } else {
                                                        $specsType = 'IMPORTED';
                                                    }
                                                } else {
                                                    if ($auction_item['specification'] == 'GCC') {
                                                        $specsType = 'خليجية';
                                                    } else {
                                                        $specsType = 'وارد';
                                                    }
                                                }
                                                ?>
                                                <?= $specsType; ?>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <p class="auction-desc-inner"><?= $this->lang->line('year'); ?></p>
                                        </div>
                                        <div class="col">
                                            <p class="auction-desc-inner auction-desc-inner-color"><?= $auction_item['year']; ?></p>
                                        </div>
                                    </div>
                                <?php } ?>
                                <?php
                                //Sorted category fields added by admin
                                $select = "sort_catagories.sort_id AS sortId, item_category_fields.label AS fieldName, item_fields_data.value AS fieldValue";
                                $sortedCateFields = $this->db->select($select)->from('sort_catagories')
                                    ->join('item_category_fields', 'sort_catagories.field_id = item_category_fields.id')
                                    ->join('item_fields_data', 'item_category_fields.id = item_fields_data.fields_id')
                                    ->where(['sort_catagories.category_id' => $category_id, 'item_fields_data.item_id' => $auction_item['item_id']])
                                    ->order_by('sort_catagories.sort_id', 'ASC')
                                    ->get()->result_array();

                                //echo $this->db->last_query();
                                //print_r($sortedCateFields);
                                if ($sortedCateFields && $categoryData['include_make_model'] == 'no') {
                                    $k = 1;
                                    foreach ($sortedCateFields as $key => $field) {
                                        $fieldNames = explode("|", $field['fieldName']);
                                        $fieldValues = explode("|", $field['fieldValue']);

                                        if ($language == 'english') {
                                            $fieldName = isset($fieldNames[0]) ? $fieldNames[0] : '';
                                            $fieldValue = isset($fieldValues[0]) ? $fieldValues[0] : '';
                                        } else {
                                            $fieldName = isset($fieldNames[1]) ? $fieldNames[1] : $fieldNames[0];
                                            $fieldValue = isset($fieldValues[1]) ? $fieldValues[1] : $fieldValues[0];
                                        }
                                        if ($k == 3) { ?>
                                </div>
                                <div class="listrow2">
                                <?php } ?>

                                <div class="row">
                                    <div class="col">
                                        <p class="auction-desc-inner"><?= $fieldName; ?></p>
                                    </div>
                                    <div class="col">
                                        <p class="auction-desc-inner auction-desc-inner-color"><?= $fieldValue; ?></p>
                                    </div>
                                </div>



                        <?php
                                        $k++;
                                    }
                                } ?>
                        <!-- <div class="row">
                            <div class="col">
                                <p class="auction-desc-inner"><?//= $this->lang->line('views'); ?></p>
                            </div>
                            <div class="col">
                                <p class="auction-desc-inner "><i class="fa fa-eye" aria-hidden="true"></i> <?//= $visit_count; ?> </p>
                            </div>
                        </div> -->
                                </div>
                                <?php if ($auctionType != 'live') {
                                    $_alrt =  '';
                                ?>
                                    <p data-countdown="<?= date('Y-m-d H:i:s', strtotime($auction_item['bid_end_time'])) ?>" class="timer auction-desc-heading-down <?= $_alrt ?>"></p>
                                <?php
                                } ?>



                            </div>
                            <div class="bidgridbtn bidgridbtn3">
                                <?php if ($auctionType != 'live') {
                                    $item_url = base_url('auction/online-auction/details/') . $auction_item['auction_id'] . '/' . $auction_item['item_id'];
                                    $btn = $this->lang->line('bid_now');
                                } else {
                                    $item_url = base_url('live-online-detail/') . $auction_item['auction_id'] . '/' . $auction_item['item_id'];
                                    $btn = $this->lang->line('view_details');
                                } ?>
                                <?php if ($auctionType != 'live') { ?>
                                    <a href="<?= $item_url; ?>">
                                        <div class="auction-bid-button">
                                            <p class="auction-bid-button-text"><?= $btn; ?></p>
                                        </div>
                                    </a>
                            </div>
                            <div class="bidgridbtn bidgridbtn4">
                            <?php } else { ?>
                                <a href="<?= $item_url; ?>">
                                    <div class="auction-bid-button <?php echo $_class; ?>">
                                        <p class="auction-bid-button-text"><?= $btn; ?></p>
                                    </div>
                                </a>
                            <?php } ?>
                            </div>
                        </div>
                        <div class=" bidlistbtn1">


                            <?php if ($auctionType != 'live') {
                                $item_url = base_url('auction/online-auction/details/') . $auction_item['auction_id'] . '/' . $auction_item['item_id'];
                                $btn = $this->lang->line('bid_now');
                            } else {
                                $item_url = base_url('live-online-detail/') . $auction_item['auction_id'] . '/' . $auction_item['item_id'];
                                $btn = $this->lang->line('view_details');
                            } ?>
                            <?php if ($auctionType != 'live') { ?>
                                <img class="lst1" src="<?= NEW_ASSETS_USER; ?>new/images/bidpimg.png" alt="">
                                <a href="<?= $item_url; ?>">
                                    <div class="auction-bid-button bidlst">
                                        <p class="auction-bid-button-text"><?= $btn; ?></p>
                                    </div>
                                </a>
                        </div>
                        <div class=" bidlistbtn2">

                        <?php } else { ?>
                            <img class="lst2" src="<?= NEW_ASSETS_USER; ?>new/images/bidpimg.png" alt="">
                            <a href="<?= $item_url; ?>">

                                <div class="bidlst auction-bid-button <?php echo $_class; ?>">
                                    <p class="auction-bid-button-text"><?= $btn; ?></p>
                                </div>
                            </a>
                        <?php } ?>
                        </div>
                    </div>
                    <!--col end-->
                <?php $col++;
                } ?>
            </div>
        </div>
    </div>
    <!--container end-->
<?php } // else end
?>
<div class="pagination d-flex align-items-center justify-content-end">
    <nav aria-label="Page navigation">
        <?= $paginationLinks; ?>
    </nav>
</div>
<script>

    $('button.redirectAuction').on('click',function(event) {
        event.preventDefault();
      //      window.location.href = "<?= base_url('live-online/'); ?>"+aucId; 
        $.ajax({
            url: "<?= base_url('search/getAucStatus'); ?>",
            type: 'post',
             data: {
                'aucId': aucId,
                "<?= $this->security->get_csrf_token_name(); ?>": "<?= $this->security->get_csrf_hash(); ?>"
            },
           success: function(res) {
                console.log(res);
                var objData = $.parseJSON(res);
                var balance = <?=$balance;?>;
                if(objData.data == 'start'){
                    <?php if ($user && $balance > 0) {?>
                            window.location.href = "<?= base_url('live-online/'); ?>"+aucId;
                        <?php }else{ ?>
                    window.location.href = "<?= base_url('visitor/livestream'); ?>"; 
                    <?php } ?>

                }else{
                    $("#auctionStartAlert").modal('show');

                }
            }
        });


    });

  
    $('.pagination_link').on('click', 'a', function(event) {
        event.preventDefault();
        var pageHref = $(this).attr('href');
        var offset = pageHref.replace('#/', '');
        //console.log(offset);

        var auctionType = $('.nav-tabs a.active').data('auction');
        getAuctionItems(auctionType, offset);
        scrollSmoothToTop();
    });

    $('#sortBy').on('change', function(event) {
        event.preventDefault();
        var auctionType = $('.nav-tabs a.active').data('auction');
        getAuctionItems(auctionType);
    });

    $("#live #sortBy").on('change', function(event) {
        event.preventDefault();
        var auctionType = $('.nav-tabs a.active').data('auction');
        getAuctionItems(auctionType);
    });

    $('#listView').on('click', function(event) {
        event.preventDefault();
        var auctionType = $('.nav-tabs a.active').data('auction');
        getAuctionItems(auctionType);
    });

    $(document).ready(function() {
        $('.selectpicker').selectpicker();
    });

    function doFavt(e) {
        var heart = $(e);
        var itemID = $(e).data('item');
        var auctionId = $(e).data('auction-id');
        var language = '<?= $language; ?>';
        var auctionItemid = $(e).data('auction-item-id');
        $.ajax({
            type: 'post',
            url: '<?= base_url('auction/OnlineAuction/do_favt'); ?>',
            data: {
                'item_id': itemID,
                'auction_id': auctionId,
                'auction_item_id': auctionItemid,
                [token_name]: token_value
            },
            success: function(msg) {
                if (msg == 'do_heart') {
                    if (language == 'english') {
                        heart.html('<span class="material-icons">favorite</span>');
                    } else {
                        heart.html('<span class="material-icons">favorite</span>');
                    }
                }
                if (msg == 'remove_heart') {
                    if (language == 'english') {
                        heart.html('<span class="material-icons">favorite_border</span>');
                    } else {
                        heart.html('<span class="material-icons">favorite_border</span>');
                    }
                }
                if (msg == '0') {
                    window.location.replace('<?= base_url("home/login?rurl="); ?>' + encodeURIComponent(window.location.href));
                }
            }
        });
    }
</script>
<script type="text/javascript">
    var lng = '<?= $language; ?>';
    $('p[data-countdown]').each(function() {
        var $this = $(this),
            finalDate = $(this).data('countdown');
        $this.countdown(finalDate, function(event) {

            if (lng == 'arabic') {

                if (event.elapsed) {
                    $this.html(event.strftime("<?= $this->lang->line('time_expired'); ?>"));
                     $(this).parent().parent().parent().hide();
                } else {
                    if (event.strftime('%D') == '00') {

                        if (event.strftime('%H') <= '23' && event.strftime('%M') <= '59' && event.strftime('%S') <= '59') {
                            $this.html(event.strftime("<?= $this->lang->line('ends_in_new'); ?>: %H <?= $this->lang->line('ends_hours'); ?> %M <?= $this->lang->line('ends_mins'); ?> %S <?= $this->lang->line('ends_sec'); ?>"));
                            $this.addClass('blink');
                        } else {
                            $this.html(event.strftime("<?= $this->lang->line('ends_in_new'); ?>: %H <?= $this->lang->line('ends_hours'); ?> %M <?= $this->lang->line('ends_mins'); ?> %S <?= $this->lang->line('ends_sec'); ?>"));

                        }

                    } else {
                        $this.html(event.strftime("<?= $this->lang->line('ends_in_new'); ?>: %D <?= $this->lang->line('ends_day'); ?> %H <?= $this->lang->line('ends_hours'); ?> %M <?= $this->lang->line('ends_mins'); ?> %S <?= $this->lang->line('ends_sec'); ?>"));
                    }
                }
            } else {

                if (event.elapsed) {
                    $this.html(event.strftime("<?= $this->lang->line('time_expired'); ?>"));
                     $(this).parent().parent().parent().hide();
                } else {
                    if (event.strftime('%D') == '00') {

                       if (event.strftime('%H') <= '23' && event.strftime('%M') <= '59' && event.strftime('%S') <= '59') {
                            $this.html(event.strftime("<?= $this->lang->line('ends_in'); ?>: %H hrs %M min %S sec"));
                            $this.addClass('blink');
                        } else {
                            $this.html(event.strftime("<?= $this->lang->line('ends_in'); ?>: %H hrs %M min %S sec"));

                        }

                    } else {
                        $this.html(event.strftime("<?= $this->lang->line('ends_in'); ?>: %D days %H hrs %M min %S sec"));
                    }
                }


            }
        });
    });
</script>

<script>
    $(document).ready(function() {
        $(document).on('click', '#list', function(event) {
            event.preventDefault();
            $('.productstb .item').addClass('list-group-item');
            $("img.lst2").show();
            $("input#typeCss").val('list');
        });
        $(document).on("click", '#grid', function(event) {
            event.preventDefault();
            $('.productstb .item').removeClass('list-group-item');
            $('.productstb .item').addClass('grid-group-item');
            $("img.lst2").hide();
            $("input#typeCss").val('grid');
        });
    });
</script>

<script>
    $('.btnlstgrdvew').click(function() {
        $('.active2').removeClass('active2');
        $(this).addClass('active2');

    });
</script>
<script>
    // $(".bidlistbtn").hide();
    $('#grid').click(function() {
        $(".bidlistbtn2").hide();
        $(".bidgridbtn3").show();
        $(".bidlistbtn1").hide();
        $(".lst1").hide();


    });

    $('#list').click(function() {
        $(".bidgridbtn3").hide();
        $(".bidlistbtn1").show();
        $(".lst1").show();

    });
</script>

<script>
    $("#onlineResults").click(function() {
        $('.productstb').load('refresh');
    });

    $("#liveResults").click(function() {
        $('.productstb').load('refresh');
    });

    $("#closedResults").click(function() {
        $('.productstb').load('refresh');
    });

    $("document").ready(function() {
        $("button#list").trigger('click');
    });
    scrollingElement = (document.scrollingElement || document.body);

    function scrollSmoothToTop(id) {
        $(scrollingElement).animate({
            scrollTop: 0
        }, 500);
    }
</script>


<script>// path to the watermark image
// $(function(){
//  //add text water mark;  background-cover
//  $('img.auction-images').watermark({
//  path: '<?= NEW_ASSETS_USER; ?>/new/images/logo/logo_header_english.svg',
//   textWidth: 100,
//   textColor: 'white'
//  });
   
// })
 
</script>

<script>
    $(document).ready(function() {
        $('#myTab .wll').addClass('<?=$clasLive; ?>');
    });


     //Enable pusher logging - don't include this in production
    Pusher.logToConsole = false;

    var pusher = new Pusher("<?= @$this->config->item('pusher_app_key'); ?>", {
      cluster: 'ap1'
    });
    var channel = pusher.subscribe('ci_pusher');

    channel.bind('live-event', function(push) {
      //  alert('live')
        window.location.href = "<?= base_url('live-online/'); ?>"+aucId;


    });
     channel.bind('stop-event', function(push) {
       // alert('stop')
         //  $('button.redirectAuction').removeClass('redirectAuction');
      ///  $('button#_addRmv').addClass('showPopupAlert');
   

    });


     channel.bind('start-event', function(push) {
         window.location.href = "<?= base_url('live-online/'); ?>"+aucId;   

    });



</script>