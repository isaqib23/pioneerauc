<script src="<?= NEW_ASSETS_USER; ?>/new/js/jquery.countdown.js"></script>
<?php
function get_lang_base_text($lng, $_iTxt, $_iPrice)
{
    if ($lng == 'english') {
        $_iValue = $_iTxt . ' ' . $_iPrice;
    } else {
        $_iValue = 'السعر الحالي   ' . $_iPrice . ' درهم إماراتي   ';
    }
    return $_iValue;
}

function get_lang_base_text_item($lng, $_iTxt, $_iCurrency,  $_iPrice)
{
    if ($lng == 'english') {
        $_iValue = $_iCurrency . ' ' . $_iPrice;
    } else {
        $_iValue = $_iPrice . ' ' . 'درهم إماراتي  ';
    }
    return $_iValue;
}

?>

<style type="text/css">
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

    .auction-desc-heading-down {
        white-space: pre-wrap !important;
    }

    .switchonoff {
        display: flex;
        width: 100%;
        margin: 2px 0;
        align-items: center;
    }

    .switchonoff span {
        color: #000;
        padding-left: 10px;
    }

    .switch {
        position: relative;
        display: inline-block;
        width: 50px;
        height: 28px;
        margin: 0;
    }

    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider.round {
        border-radius: 34px;
    }

    element.style {}

    .product .footer {
        text-align: center;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #fff;
        -webkit-transition: .4s;
        transition: .4s;
        border: 1px solid #ccc;
    }

    .slider.round:before {
        border-radius: 50%;
    }

    input:checked+.slider {
        background-color: #7c2dca;
    }

    input:checked+.slider:before {
        -webkit-transform: translateX(26px);
        -ms-transform: translateX(26px);
        transform: translateX(26px);
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 26px;
        width: 26px;
        left: 0px;
        bottom: 0px;
        background-color: white;
        -webkit-transition: .4s;
        transition: .4s;
        border: 1px solid #ccc;
    }

    .social.top.active {
        transform: scale(1) translateY(-10px);
    }

    .social.active {
        opacity: 1;
        transition: all 0.4s ease 0s;
        visibility: visible;
    }

    .social.networks-5 {}

    .social.top {
        margin-top: 0px;
        transform-origin: 0 0 0;
    }

    .social {
        margin-left: -20%;
        opacity: 0;
        transition: all 0.4s ease 0s;
        visibility: hidden;
    }

    .networks-5 {
        position: absolute;
        margin: 0;
        right: 18px;
        top: -1%;
        z-index: 5 !important;
    }

    @media screen and (max-width: 1000px) {
        .networks-5 {
            right: auto;
            top: auto;
            bottom: 70px;
            transform: translateX(-50%) !important;
            left: 50%;
        }
    }

    button.plus {
        color: #fff;
        font-size: 22px;
        height: 30px;
        line-height: 30px;
        display: inline-block;
        width: 30px;
        vertical-align: sub;
        background: #5c03b5;
    }

    button.minus {
        color: #fff;
        font-size: 30px;
        height: 30px;
        display: inline-block;
        width: 30px;
        line-height: 18px;
        vertical-align: sub;
        background: #5c03b5;
    }

    /* Chrome, Safari, Edge, Opera */
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    /* Firefox */
    input[type=number] {
        -moz-appearance: textfield;
    }


    .your-bid .bid-items button.noofpax {
        cursor: pointer
    }

    .your-bid .bid-items button.noofpax i {
        pointer-events: none;
    }
</style>
<?php
$biding_limit = 0;
$id = $this->uri->segment(5);
$auctionId = $this->uri->segment(4);
$min = $this->db->get_where('auction_items', ['item_id' => $id, 'auction_id' => $auctionId])->row_array();


$bid_end_time = strtotime($item['bid_end_time']);
$item_images_ids = explode(',', $item['item_images']);
$images = $this->db->where_in('id', $item_images_ids)->order_by('file_order', 'ASC')->get('files')->result_array();
if ($images) {
    $image_src = base_url('uploads/items_documents/') . $item['id'] . '/' . $images[0]['name'];
} else {
    $image_src = '';
}

$favorite = 'fa-heart-o';
if ($this->session->userdata('logged_in')) {
    $user = $this->session->userdata('logged_in');
    $favt = $this->db->get_where('favorites_items', ['item_id' => $item['id'], 'user_id' => $user->id])->row_array();
    if ($favt) {
        $favorite = 'fa-heart';
    } else {
        $favorite = 'fa-heart-o';
    }
} else {
    // echo "login to continue";
}

if ($this->session->userdata('logged_in')) {
    $is_logged_in = $this->session->userdata('logged_in');
} else {
    $is_logged_in = NULL;
}
if ($min['live_status'] == 'yes') {
    $tab = 'online';
} else {
    $tab = 'live';
}


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
$en_month = date("M", strtotime($item['bid_end_time']));
// $en_month = date("M", strtotime($bid_end_time));
$mday = date("d", strtotime($item['bid_end_time']));
$myear = date("Y", strtotime($item['bid_end_time']));
?>

<script>
    var shareTitle = '';
    var shareImage = '';
    var shareDescription = '';
    $(document).ready(function() {
        $("#start_bid").attr('disabled', true);
        $("#btn-toggle").on("click", function() {
            $(".auto-biding > ul").slideToggle();
        });
        shareTitle = '<?= addslashes(json_decode($item['item_name'])->english); ?>';
        shareImage = '<?= $image_src; ?>';
        shareDescription = '<?= addslashes(json_decode($item['item_detail'])->english); ?>';
    });
</script>


<!-- PNotify -->
<link href="<?php echo base_url(); ?>assets_admin/vendors/pnotify/dist/pnotify.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>assets_admin/vendors/pnotify/dist/pnotify.buttons.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>assets_admin/vendors/pnotify/dist/pnotify.nonblock.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets_admin/sweetalert/dist/sweetalert.css">



<div class="main-wrapper vehicles">
    <div class="container">
        <a href="javascript:void(0)" onclick="goBack()" class="back-link">
            <span class="material-icons">
                chevron_left
            </span>
            <?= $this->lang->line('back_to_result'); ?>
        </a>
        <div class="description-listing">
            <h1 class="page-title"><?php $item_name = json_decode($item['item_name']);
                                    echo strtoupper($item_name->$language); ?></h1>
            <ul class="item-stats d-flex align-items-center">
                <li>
                    <span><?= $this->lang->line('lot'); ?> # </span>
                    <?= $item['order_lot_no']; ?>
                </li>
                <li>
                    <span><?= $this->lang->line('sale_location'); ?>: </span>
                    <b><?= $item['item_location']; ?> </b>
                </li>
                <li>
                    <span><?= $this->lang->line('end_date'); ?>: </span>
                    <?php
                    if ($language == 'arabic') {
                        $ar_month = $months[$en_month];
                        echo $mday . ' ' . $ar_month . ' ' . $myear . ' - ' . date('h:i ', $bid_end_time);
                    } else {
                        echo  date('jS M Y h:i', $bid_end_time);
                    }
                    ?>
                </li>
            </ul>
            <div class="inner-listing">
                <div class="left-side">
                    <div class="detail-slider">
                        <div class="bg-black">
                            <ul class="action-links flex justify-content-end pr-3 mb-4">
                                <?php
                                $fav = 'favorite_border';
                                if ($language == 'english') {
                                    $favText = 'Add to wishlist';
                                } else {
                                    $favText = 'أضف إلى قائمة الامنيات';
                                }
                                if (isset($fav_item) && !empty($fav_item)) {
                                    $fav = 'favorite';
                                    if ($language == 'english') {
                                        $favText = 'Added to wishlist';
                                    } else {
                                        $favText = 'أضيف لقائمة الأماني';
                                    }
                                }
                                ?>
                                <li class="add_to_wishlist">
                                    <a href="javascript:void(0)" id="fav_id" data-item="<?= $item['id']; ?>" onclick="doFavt(this)">
                                        <span class="material-icons">
                                            <?= $fav ?>
                                        </span>
                                        <qa class="favText"><?= $favText; ?></qa>
                                    </a>
                                </li>
                                <li class="share">
                                    <a href="javascript:void(0)" class="share-btn">
                                        <span class="material-icons">
                                            share
                                        </span>
                                        <?= $this->lang->line('share'); ?>
                                    </a>
                                </li>
                            </ul>
                            <div class="social top center networks-5" id="my-inline-buttons"></div>
                            <script>
                                $(document).ready(function() {
                                    //custom button for homepage
                                    $(".share-btn").click(function(e) {
                                        e.preventDefault();
                                        console.log('share me');
                                        $('.networks-5').not($(".networks-5")).each(function() {
                                            $(this).removeClass("active");
                                        });
                                        $(".networks-5").toggleClass("active");
                                    });
                                });
                            </script>
                            <div class="slider-for">
                                <?php
                                if ($images) :
                                    foreach ($images as $key => $value) : ?>
                                        <div class="img">
                                            <img class="watermarks " onclick="sliderRefresh()" data-toggle="modal" data-target="#imgModal" src="<?= base_url('uploads/items_documents/') . $item['id'] . '/' . $value['name']; ?>">
                                        </div>
                                    <?php endforeach;
                                else : ?>
                                    <img class="watermarks " data-toggle="modal" onclick="sliderRefresh()" data-target="#imgModal" src="<?= base_url('assets_admin/images/product-default.jpg'); ?>" alt="">
                                <?php endif; ?>
                            </div>
                            <img class="watermarks " style="position: absolute;bottom: 22px;left: 7px;" class="full-screen" data-toggle="modal" onclick="sliderRefresh()" data-target="#imgModal" width="40" src="<?= NEW_ASSETS_USER; ?>/new/images/full-screen-selector.svg">
                        </div>
                        <div class="slider-nav">
                            <?php foreach ($images as $key => $value) : ?>
                                <div class="img">
                                    <img class="watermarks " src="<?= base_url('uploads/items_documents/') . $item['id'] . '/' . $value['name']; ?>">
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <div class="right-side">
                    <?php
                    $user_have_bid = $this->db->get_where('bid', ['auction_id' => $auction_id, 'user_id' => $user_id, 'item_id' => $item_id]); ?>
                    <?php if ($min['sold_status'] == 'not') : ?>
                        <div class="bid-detail">
                            <p> <?= $this->lang->line('current_bid'); ?>:




                                <b id="CBP" class="<?= $item['item_id']; ?>">
                                    <?= get_lang_base_text_item($language, $this->lang->line('current_bid'), $this->lang->line('aed'), (isset($heighest_bid_data) && !empty($heighest_bid_data['current_bid'])) ? number_format($heighest_bid_data['current_bid'], 0, ".", ",") : number_format($item['bid_start_price'], 0, ".", ",")); ?>
                                </b>
                            </p>
                            <p> <?= $this->lang->line('min_increment'); ?>:
                                <span>
                                    <?= get_lang_base_text_item($language, 0, $this->lang->line('aed'),  number_format($item['minimum_bid_price'], 0, ".", ",")); ?>

                                </span>
                            </p>
                            <span style="color: #5C02B5;" class="big-bid" id="bid_user_name<?= $item['item_id']; ?>">
                                <?php
                                if ($user_have_bid->num_rows() > 0) { ?>
                                    <?php
                                    if (isset($heighest_bid_data) && !empty($heighest_bid_data) && $this->session->userdata('logged_in')) {
                                        if ($heighest_bid_data['user_id'] == $user_id) {
                                            /*echo '<h style="color:green"><?= $this->lang->line("higher_bider");?>.</h>'; */
                                            $higher = '<h style="color: #5C02B5 !important;">' . $this->lang->line('higher_bider') . '</h>';
                                            echo $higher;
                                        } else {
                                            $not_higher = '<h style="color: #FA8500;" class="blink">' . $this->lang->line('not_higher_bider');
                                            echo $not_higher;
                                        }
                                    }
                                    ?>
                                <?php } ?>
                            </span>
                        </div>
                        <div class="time-remaining">
                            <ul class="d-flex justify-content-between align-items-center">
                                <li>
                                    <?= $this->lang->line('time_remaining_for_bid'); ?>:
                                    <b>
                                        <?php
                                        $endTime = $bid_end_time;
                                        if ($language == 'arabic') {
                                            $ar_month = $months[$en_month];
                                            echo $mday . ' ' . $ar_month . ' ' . $myear . ' - ' . date('h:i ', $bid_end_time);
                                        } else {
                                            echo date('jS M Y h:i', $bid_end_time);
                                        }
                                        ?>
                                    </b>
                                </li>
                                <li>
                                    <img src="<?= NEW_ASSETS_USER; ?>/new/images/clock-circle-o.png">
                                </li>
                            </ul>
                        </div>
                        <?php if ($is_logged_in) : ?>
                            <div class="your-bid">
                                <div id="hideText" class="hideText<?= $item['item_id']; ?>"></div>
                                <?php
                                $deposit_by_user = false;
                                if ($item['security'] == 'yes') {
                                    $user = $this->session->userdata('logged_in');
                                    $user_deposit = $this->db->get_where('auction_item_deposits', [
                                        'user_id' => $user->id,
                                        'item_id' => $item['item_id'],
                                        'auction_id' => $item['auction_id'],
                                        'status' => 'approved',
                                        'auction_item_id' => $item['auction_item_id']
                                    ]);
                                    //echo $this->db->last_query();
                                    if ($user_deposit->num_rows() > 0) {
                                        $user_deposit = $user_deposit->row_array();
                                        if ($user_deposit['deposit'] >= $item['deposit']) {
                                            $deposit_by_user = true;
                                        } else {
                                            $deposit_by_user = false;
                                        }
                                    } else {
                                        $deposit_by_user = false;
                                    }
                                } else {
                                    $deposit_by_user = true;
                                }

                                if ($deposit_by_user == true) :
                                ?>

                                    <?php
                                    $crrnt = (isset($heighest_bid_data) && !empty($heighest_bid_data['current_bid'])) ? $heighest_bid_data['current_bid'] : $item['bid_start_price'];


                                    //$minBid = number_format($item['minimum_bid_price']);
                                    $minBid = ($item['minimum_bid_price']);
                                    ?>
                                    <input type="hidden" name="crrnt" id="crrnt" value="<?= $crrnt; ?>">
                                    <input type="hidden" name="minBid" id="minBid" value="<?= $minBid; ?>">



                                    <div class="your-bid bid-div<?= $item['item_id']; ?>" id="hidebid_section">
                                        <h4><?= $this->lang->line('your_bid'); ?>:</h4>
                                        <div class="form-group bid-items">
                                            <input type="hidden" name="current_price" id="current_price<?= $item['item_id']; ?>" value="<?= (isset($heighest_bid_data) && !empty($heighest_bid_data['current_bid'])) ? $heighest_bid_data['current_bid'] : $item['bid_start_price']; ?>">
                                            <!-- <label><?= $this->lang->line('direct_offer'); ?>:</label> -->
                                            <!-- <input type="number" min="0" step="100" id="bid_amount" name="bid_amount" placeholder="<?= $this->lang->line('place_direct_offer'); ?>" class="bid_amount_" value="<?php echo ($crrnt + $minBid); ?>" > -->

                                            <?php
                                            if ($balance > 0) :
                                                $nBalance = (float)$balance * 10;
                                            else :
                                                $nBalance = 5000;
                                            endif; ?>


                                            <p class="paddinflft0" data-increment="<?php echo $minBid; ?>" data-max="<?= $nBalance; ?>" data-price="<?php echo ((float)$crrnt + (float)$minBid); ?>">
                                                <input class="quantity" type="hidden" value='0' readonly>
                                                <button disabled="disabled" class="noofpax minus" style="background: #b1b1b1; border: #b1b1b1;"><i class="fa fa-minus"></i></button>
                                                <span class="inputlabelcurr">
                                                    <?php if ($language == 'arabic') { ?>



                                                        <input type="number" min="<?php echo ((float)$crrnt + (float)$minBid); ?>" step="<?php echo $minBid; ?>" id="bid_amount" name="bid_amount" placeholder="<?= $this->lang->line('place_direct_offer'); ?>" class="total bid_amount_" value="<?php echo ((float)$crrnt + (float)$minBid); ?>" maxlength="10">

                                                        <label> <?= $this->lang->line('aed_small_new'); ?> </label>


                                                    <?php } else { ?>
                                                        <label> <?= $this->lang->line('aed_small_new'); ?> </label>

                                                        <input type="number" min="<?php echo ((float)$crrnt + (float)$minBid); ?>" step="<?php echo $minBid; ?>" id="bid_amount" name="bid_amount" placeholder="<?= $this->lang->line('place_direct_offer'); ?>" class="total bid_amount_" value="<?php echo ((float)$crrnt + (float)$minBid); ?>" maxlength="10">



                                                    <?php } ?>

                                                </span>
                                                <button class="noofpax plus"><i class="fa fa-plus"></i></button>

                                            </p>


                                            <button onclick="placebid(this)" data-auction-id="<?= $item['auction_id']; ?>" data-item-id="<?= $item['item_id']; ?>" data-seller-id="<?= $item['item_seller_id']; ?>" data-min-bid="<?= $item['minimum_bid_price']; ?>" data-lot-no="<?= $item['lot_no']; ?>" data-start-price="<?= $item['bid_start_price']; ?>" data-max-bid="<?= (float)$balance * 10; ?>" id="bid_btn" class="btn btn-primary smplce"><?= $this->lang->line('place_bid'); ?>
                                            </button>
                                        </div>


                                        <div class="switchonoff">
                                            <label class="switch">
                                                <!-- <input onclick="autoBiding(this)"  id="auto_limit"  name="auto_limit" value="<?php echo ($crrnt + $minBid); ?>" type="checkbox"> -->

                                                <input id="_auto_bid" name="_auto_bid" value="1" type="checkbox">

                                                <span class="slider round"></span>
                                            </label>
                                            <span><?= $this->lang->line('switch_on_auto_bid'); ?></span>
                                        </div>
                                        <div class="placemaxoffer">
                                            <p class="mb-0">
                                                <img src="<?= NEW_ASSETS_USER; ?>/new/images/error-popup.png">
                                                <?= $this->lang->line('place_max_amount'); ?>
                                            </p>
                                        </div>
                                        <div class="button-row">


                                        </div>
                                    </div>
                                <?php endif; ?>
                                <?php if ($item['security'] == 'yes' && !empty($item['deposit']) && $deposit_by_user == false) :
                                ?>
                                    <div class="report">
                                        <h2 class="form-title"><?= $this->lang->line('deposit'); ?></h2>
                                        <ul>
                                            <li>
                                                <p class="toltip"><?= $this->lang->line('item_required_extra_deposit'); ?> <?= $item['deposit']; ?></p>
                                            </li>
                                            <li>
                                                <a class="btn btn-default" href="<?= base_url('customer/item_deposit?item_id=') . $item['auction_item_id'] . '&auction_id=' . $item['auction_id'] . '&rurl=' . $rurl; ?>" class="file-link"><?= $this->lang->line('deposit_now'); ?></a>
                                            </li>
                                        </ul>
                                    </div>
                                <?php endif; ?>
                                <?php if ($balance == 0) : ?>
                                    <!--  <ul class="h-list deposit-links">
                                        <li>
                                            <h4><? //= $this->lang->line('deposit_text');
                                                ?>.</h4>
                                        </li>
                                        <li>
                                            <? //= $this->lang->line('you_need_make_security_deposit'); 
                                            ?><br> </br>
                                            <?php
                                            // $redirect = (isset($_GET['r'])) ? urldecode($_GET['r']) : array();
                                            // if(!empty($redirect)){
                                            //     $redirect = json_decode($redirect);
                                            // }
                                            // array_push($redirect,$_SERVER['REQUEST_URI']);
                                            // $json_redirect = json_encode($redirect);
                                            // $json_redirect = urlencode($json_redirect);
                                            // print_r($json_redirect);die('jhsdvfjsd ');
                                            ?>
                                            <a href="<? //= base_url('/customer/deposit?'); 
                                                        ?>" class="btn btn-default sm" ><? //= $this->lang->line('pay_deposit');
                                                                                        ?></a>
                                        
                                        </li>
                                    </ul>
                                    <script>
                                        $("#no-deposit").model('show');
                                    </script> -->
                                <?php endif; ?>
                            </div>
                            <?php $biding_limit = $balance * 10; ?>
                        <?php else : ?>
                            <?php $biding_limit = 0; ?>
                            <div class="bid-now">
                                <!-- <p><? //= $this->lang->line('available_for_live_auction');
                                        ?></p> -->
                                <p>
                                    <a href="javascript:void(0)" data-toggle="modal" data-target="#loginModal"><?= $this->lang->line('login_or_register'); ?></a>
                                    <?= $this->lang->line('now_to_start_bidding'); ?>
                                </p>
                                <a href="javascript:void(0)" data-toggle="modal" data-target="#loginModal" class="btn btn-primary"><?= $this->lang->line('bid_now'); ?>!</a>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
            <div class="inner-listing">
                <div class="left-side">
                    <?php
                    $customClass = 'mb-0';
                    if (($category['include_make_model'] == 'yes')) {
                        $customClass = 'mb-4';
                    } ?>
                    <ul class="detail-icons <?= $customClass; ?> ">
                        <?php if (($category['include_make_model'] == 'yes') && isset($item['mileage'])) : ?>
                            <li>
                                <h4><?= $this->lang->line('report'); ?></h4>
                                <img src="<?= NEW_ASSETS_USER; ?>/new/images/detail-icons/driving-test-icons.svg">
                                <p><?= ($item['inspected'] == 'yes') ? $this->lang->line('available') : $this->lang->line('not_available'); ?></p>
                            </li>
                        <?php endif; ?>
                        <?php if (($category['include_make_model'] == 'yes')) : ?>
                            <li>
                                <h4><?= $this->lang->line('odometer'); ?></h4>
                                <img src="<?= NEW_ASSETS_USER; ?>/new/images/detail-icons/odometer-icons.svg">
                                <p><?= number_format($item['mileage']); ?></p>
                            </li>
                        <?php endif; ?>
                        <?php if (($category['include_make_model'] == 'yes') && isset($item['specification'])) : ?>
                            <li>
                                <h4><?= $this->lang->line('specifications'); ?></h4>
                                <img src="<?= NEW_ASSETS_USER; ?>/new/images/detail-icons/specification-icons.svg">
                                <?php
                                if ($language == 'english') {
                                    if ($item['specification'] == 'GCC') {
                                        $specsType = 'GCC';
                                    } else {
                                        $specsType = 'IMPORTED';
                                    }
                                } else {
                                    if ($item['specification'] == 'GCC') {
                                        $specsType = 'خليجية';
                                    } else {
                                        $specsType = 'وارد';
                                    }
                                }
                                ?>
                                <p><?= $specsType; ?></p>
                            </li>
                        <?php endif; ?>
                        <!-- <li>
                            <h4>Color</h4>
                            <img src="<? //= NEW_ASSETS_USER; 
                                        ?>/new/images/detail-icons/color-icons.svg">
                            <p>Black</p>
                        </li> -->

                        <?php if (($category['include_make_model'] == 'yes')) : ?>
                            <li>
                                <h4><?= $this->lang->line('year'); ?></h4>
                                <img src="<?= NEW_ASSETS_USER; ?>/new/images/detail-icons/year-icons.svg">
                                <p><?= $item['year'] ?></p>
                            </li>
                        <?php endif; ?>

                        <?php if (($category['include_make_model'] == 'yes')) : ?>
                            <li>
                                <h4><?= $this->lang->line('vat'); ?></h4>
                                <img src="<?= NEW_ASSETS_USER; ?>/new/images/detail-icons/vat-icons.svg">
                                <p><?= $this->lang->line('applicable'); ?></p>
                            </li>
                        <?php endif; ?>
                    </ul>
                    <div class="detail-desc">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true"><?= $this->lang->line('details'); ?></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false"><?= $this->lang->line('description'); ?></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false"><?= $this->lang->line('terms_and_conditions'); ?></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="additional-tab" data-toggle="tab" href="#additional" role="tab" aria-controls="additional" aria-selected="false"><?= $this->lang->line('additional_info'); ?></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="enquiry-tab" data-toggle="tab" href="#enquiry" role="tab" aria-controls="enquiry" aria-selected="false"><?= $this->lang->line('enquiry'); ?></a>
                            </li>

                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                                <?php $j = 11;
                                if ($category['include_make_model'] && !empty($item['item_model'])) {
                                    $j = 9;
                                    $model = $this->db->get_where('item_models', ['id' => $item['item_model']])->row_array();
                                    $make = $this->db->get_where('item_makes', ['id' => $item['item_make']])->row_array();
                                    $specs = $this->db->get_where('item', ['id' => $item['id']])->row_array();
                                ?>
                                    <ul>
                                        <li><?= $this->lang->line('make'); ?></li>
                                        <li><?php if (!empty($make['title'])) {
                                                echo json_decode($make['title'])->$language;
                                            } ?></li>
                                    </ul>
                                    <ul>
                                        <li><?= $this->lang->line('model'); ?></li>
                                        <li><?php if (!empty($model['title'])) {
                                                echo json_decode($model['title'])->$language;
                                            }; ?></li>
                                    </ul>
                                <?php } ?>
                                <ul>
                                    <li><?= $this->lang->line('vin_number'); ?></li>
                                    <li><?= $item['item_vin_number']; ?></li>
                                </ul>
                                <?php
                                $i = 0;
                                foreach ($fields as $key => $value) {
                                    if (!empty($value['data-value'])) {
                                        $i++;
                                        if ($i <= $j) { ?>
                                            <ul>
                                                <li><?= $this->template->make_dual($value['label']); ?></li>
                                                <li><?= $this->template->make_dual($value['data-value']); ?></li>
                                            </ul>
                                <?php
                                        }
                                    }
                                }
                                ?>
                                <div class="button-row report-btn">
                                    <ul>
                                        <?php if (($category['include_make_model'] == 'yes') && ($item['inspected'] == 'yes')) : ?>
                                            <li>
                                                <a href="<?= base_url('inspection_report/') . $item['id']; ?>" class="btn-primary" target="_blank"><?= $this->lang->line('report'); ?></a>
                                            </li>
                                        <?php endif; ?>
                                        <?php $f = 0;
                                        if (!empty($item['item_test_report'])) {
                                            $doc_ids = explode(',', $item['item_test_report']);
                                            $item_test_report = $this->db->where_in('id', $doc_ids)->get('files')->result_array();
                                            foreach ($item_test_report as $key1 => $document) {
                                                $f++;
                                        ?>
                                                <li>
                                                    <a href="<?= base_url() . $document['path'] . $document['name']; ?>" class="btn-primary" download><?php $path_parts = pathinfo($document['orignal_name']);
                                                                                                                                                        echo $path_parts['filename']; ?></a>
                                                </li>
                                        <?php }
                                        } ?>
                                    </ul>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                <!-- <ul>
                                    <li>Make</li>
                                    <li>NISSAN</li>
                                </ul> -->
                                <ul>
                                    <li>
                                        <?php
                                        $item_detail = json_decode($item['item_detail']);
                                        $detail_points = explode(',', $item_detail->$language);

                                        foreach ($detail_points as $key => $value) {
                                        ?>

                                            <span><?= $value; ?></span>
                                        <?php
                                        }
                                        ?>
                                    </li>
                                </ul>
                            </div>
                            <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                                <ul>
                                    <li><?= json_decode($item['terms'])->$language; ?></li>
                                </ul>
                            </div>
                            <div class="tab-pane fade" id="enquiry" role="tabpanel" aria-labelledby="enquiry-tab">
                                <ul>
                                    <li><?= $this->lang->line('inquire_further'); ?></li>
                                </ul>
                                <ul>
                                    <li><?= $this->lang->line('enquire'); ?></li>
                                    <li><?= $contact['phone']; ?></a></li>
                                </ul>
                                <ul>
                                    <li><?= $this->lang->line('terms_and_conditions'); ?></li>
                                    <li class="terms-purple"><a href="<?= base_url('terms-conditions'); ?>"><?= $this->lang->line('sales_info_terms'); ?></a></li>
                                </ul>
                            </div>

                            <div class="tab-pane fade" id="additional" role="tabpanel" aria-labelledby="additional-tab">
                                <ul>
                                    <li><?= json_decode($item['additional_info'])->$language ?? ''; ?></li>
                                </ul>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="right-side">
                    <div class="google-map">
                        <ul class="flex justify-content-between">
                            <li>
                                <?= $this->lang->line('lot_location'); ?>
                            </li>
                            <li>
                                <a href="https://www.google.com/maps/@<?= $item['item_lat']; ?>,<?= $item['item_lng']; ?>,15z" target="_blank"><?= $this->lang->line('open_google_maps'); ?></a>
                            </li>
                        </ul>
                        <div class="map">
                            <div dir="ltr" id="map" style="width: 100%; height: 100%;"></div>
                            <script src="<?php echo base_url(); ?>assets_admin/js/jquery.geocomplete.js"></script>
                            <!-- <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d13594.266313237882!2d74.3000874!3d31.590931400000002!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2s!4v1612689739457!5m2!1sen!2s" width="100%" height="100%" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe> -->
                        </div>
                    </div>
                    <!-- <div class="add-space">
						<?= $this->lang->line('ad_space'); ?>
                    </div> -->
                </div>
            </div>
        </div>
        <div class="similar-vehicles">
            <?php
            if (!empty($related_items)) : ?>
                <h2 class="page-title">
                    <?php if ($category['id'] == 1) :
                        $mainCatHeading =  $this->lang->line('upcoming_new');
                    else :
                        $mainCatHeading =  $this->lang->line('similar_new');
                    endif
                    ?>

                    <?php
                    if ($language == 'arabic') :
                        echo json_decode($category['title'])->$language . ' ' . $mainCatHeading;
                    else :
                        echo $mainCatHeading . ' ' . json_decode($category['title'])->$language;
                    endif;

                    ?>

                </h2>
                <div class="products">
                    <div class="customer-logos">
                        <?php
                        foreach ($related_items as $key => $related_item) :
                            $bid_end_time = strtotime($related_item['bid_end_time']);
                            $bid_info = $this->db->where('item_id', $related_item['item_id'])->where('auction_id', $related_item['auction_id'])->order_by('id', 'desc')->from('bid')->get()->row_array();
                            $visit_count = $this->db->where('item_id', $related_item['item_id'])->where('auction_id', $related_item['auction_id'])->from('online_auction_item_visits')->count_all_results();
                            $related_item_bids_count = $this->db->where('item_id', $related_item['item_id'])->where('auction_id', $related_item['auction_id'])->from('bid')->count_all_results();
                            $item_images_ids = explode(',', $related_item['item_images']);
                            $rel_images = $this->db->where_in('id', $item_images_ids)->get('files')->row_array();
                        ?>
                            <div class="slide">
                                <div class="col-xl-12">
                                    <div class="product">
                                        <div class="body">
                                            <?php $time = $related_item['bid_end_time'];
                                            $date = date('Y-m-d H:i:s');
                                            if ($time > $date) {
                                                $url = base_url('auction/online-auction/details/') . $related_item['auction_id'] . '/' . $related_item['item_id'];
                                            } else {
                                                $url = "javascript:void(0)";
                                            } ?>
                                            <a href="<?= $url; ?>">
                                                <?php if ($rel_images) { ?>
                                                    <div class="image background-cover watermarks" style="background-image: url(<?= base_url('uploads/items_documents/') . $related_item['item_id'] . '/' . $rel_images['name']; ?>);">
                                                        <span class="tag">
                                                            <?= get_lang_base_text($language, $this->lang->line('original_price'), (!empty($bid_info)) ? number_format($bid_info['bid_amount']) : number_format($related_item['bid_start_price'])); ?>

                                                        </span>
                                                    </div>
                                                <?php } else { ?>
                                                    <div class="image background-cover " style="background-image: url(<?= base_url('assets_admin/images/product-default.jpg'); ?>);">
                                                        <span class="tag"><?= $this->lang->line('original_price'); ?> <?= (!empty($bid_info)) ? number_format($bid_info['bid_amount']) : number_format($related_item['bid_start_price']); ?></span>
                                                    </div>
                                                <?php }
                                                $endObj = new DateTime(date('Y-m-d H:i:s', strtotime($related_item['bid_end_time'])));
                                                $now = new DateTime();

                                                // The diff-methods returns a new DateInterval-object...
                                                $diff = $endObj->diff($now);

                                                $days = $diff->format('%a');
                                                $hours = $diff->format('%h');
                                                $showTime = ' %a d %h hrs';

                                                if ($days == '0' && $hours < '4') {


                                                    $_alrt =  'blink';
                                                } else {
                                                    $_alrt =  '';
                                                } ?>
                                                <h3>
                                                    <?= json_decode($related_item['item_name'])->$language; ?>

                                                </h3>
                                            </a>
                                            <p class="text-truncate"><?= json_decode($related_item['item_detail'])->$language; ?></p>


                                            <p data-countdown="<?= date('Y-m-d H:i:s', strtotime($related_item['bid_end_time'])) ?>" class="timer auction-desc-heading-down <?= $_alrt ?>"></p>

                                        </div>
                                        <div class="footer">

                                            <a href="<?= $url; ?>" class="">

                                                <?= $this->lang->line('view_details'); ?>

                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>


<!-- You are placing AED 10,000 Increment. -->
<div class="modal fade login-modal style-2" id="PlacingAutoBid" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close _bidCancelled" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <div class="increment-modal">
                    <div class="img">
                        <img src="<?= NEW_ASSETS_USER; ?>/new/images/hammer-icon.png">
                    </div>
                    <h3>

                        <?php
                        if ($language == 'arabic') {
                        ?>
                            <?= $this->lang->line('you_placing_bid'); ?>
                            <span class="incrementAmount"> 10,000 </span> <?= $this->lang->line('aed'); ?>

                        <?php } else { ?>
                            <?= $this->lang->line('you_placing_bid'); ?>
                            <span class="incrementAmount"> 10,000 </span>
                            <?= $this->lang->line('and'); ?>
                        <?php }
                        ?>


                    </h3>
                    <p class="totalAmount"></p>

                    <p><?= $this->lang->line('would_you_like_to_continue'); ?><span>?</span></p>
                    <div id="confirmBidBtn" class="row">
                        <div class="col-6 pr-0">
                            <button data-dismiss="modal" class="btn btn-default _bidCancelled">
                                <?= $this->lang->line('cancel'); ?></button>
                        </div>
                        <div class="col-6 pr-0">
                            <button class="btn btn-primary" onclick="autobidconfirm()"><?= $this->lang->line('confirm'); ?></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- You are placing AED 10,000 Increment. -->
<div class="modal fade login-modal style-2" id="PlacingBid" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <div class="increment-modal">
                    <div class="img">
                        <img src="<?= NEW_ASSETS_USER; ?>/new/images/hammer-icon.png">
                    </div>
                    <h3>
                        <?php
                        if ($language == 'arabic') {
                        ?>
                            <?= $this->lang->line('you_are_placing_new'); ?> <span class="incrementAmount"> 10,000 <?= $this->lang->line('aed'); ?> </span> <?= $this->lang->line('aed'); ?>

                        <?php } else { ?>
                            <?= $this->lang->line('you_are_placing'); ?> <?= $this->lang->line('aed'); ?> <span class="incrementAmount"> 10,000 </span> <?= $this->lang->line('increment'); ?>
                        <?php }
                        ?>

                    </h3>
                    <p class="totalAmount"></p>
                    <p><?= $this->lang->line('would_you_like_to_continue'); ?><span>?</span></p>
                    <div id="confirmBidBtn"  class="row">
                        <div class="col-6 pr-0">
                            <button data-dismiss="modal" class="btn btn-default"><?= $this->lang->line('cancel'); ?></button>
                        </div>
                        <div class="col-6 pr-0">
                            <button class="btn btn-primary" onclick="bidconfirm()"><?= $this->lang->line('confirm'); ?></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- limit Exceed Model. -->
<div class="modal fade login-modal style-2" id="limitExceedModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                    <!-- <h3><? //= $this->lang->line('dont_have_enough_balance');
                                ?></h3> -->
                    <p id="limit_exceed_txt"></p>
                    <div class="button-row">
                        <a href="<?= base_url('customer/deposit'); ?>" class="btn btn-primary"><?= $this->lang->line('take_me_to_the_payments'); ?></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- You are placing AED 10,000 Increment. -->
<div class="modal fade login-modal style-2" id="enough-balance" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                        <a href="<?= base_url('customer/deposit'); ?>" class="btn btn-primary"><?= $this->lang->line('take_me_to_the_payments'); ?></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /*Success incremnet */ -->


<div class="modal fade login-modal style-2" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body success">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <div class="increment-modal">
                    <div class="img">
                        <img src="<?= NEW_ASSETS_USER; ?>/new/images/hammer-icon.png">
                    </div>
                    <h3>
                        <?php
                        if ($language == 'arabic') {
                        ?>
                            لقد قمت بالمزايدة بمبلغ <span class="bidSuccessAmount"> 10,000 </span> درهم إماراتي بنجاح!

                        <?php } else { ?>
                            <?= $this->lang->line('succrss_bid_text'); ?> <span class="bidSuccessAmount"> 10,000 </span> <?= $this->lang->line('after_successs_bid_text'); ?>
                        <?php }
                        ?>

                    </h3>
                    <div class="button-row">
                        <button class="btn btn-primary" id="confirmBid" onclick="bidsuccess()"><?= $this->lang->line('ok'); ?></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- fail to place bid model -->
<div class="modal fade login-modal style-2" id="tryAgainModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body success">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <div class="increment-modal">
                    <div class="img">
                        <img src="<?= NEW_ASSETS_USER; ?>/new/images/hammer-icon.png">
                    </div>
                    <h3><?= $this->lang->line('bid_not_placed_try_again'); ?> </h3>
                    <div class="button-row">
                        <button class="btn btn-primary" id="confirmBid" onclick="tryModal()"><?= $this->lang->line('ok'); ?></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Slider image modal -->

<div class="modal fade login-modal style-2 image-modal" id="imgModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body show-img">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <div class="img-modal detail-slider">

                    <div class="slider-for-img">
                        <?php
                        if ($images) :
                            foreach ($images as $key => $img_value) : ?>
                                <div class="img">
                                    <img class="watermarks " src="<?= base_url('uploads/items_documents/') . $item['id'] . '/' . $img_value['name']; ?>">
                                </div>
                            <?php endforeach;
                        else : ?>
                            <img src="<?= base_url('assets_admin/images/product-default.jpg'); ?>" alt="">
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- no deposit model -->
<div class="modal" id="no-deposit">
    <div class="modal-dialog alert-box">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="alert-modal">
                    <img src="<?= NEW_ASSETS_USER; ?>/new/images/alert-icon.png" alt="">
                    <div class="alert-detail">
                        <h1><?= $this->lang->line('dont_enogh_balance'); ?></h1>
                        <p><?= $this->lang->line('would_you_like_deposit'); ?></p>
                    </div>
                    <a href="<?= base_url('/customer/deposit'); ?>" class="btn-primary"><?= $this->lang->line('take_me_to_the_payments'); ?></a>
                </div>
            </div>
            <div class="modal-footer">
            </div>

        </div>
    </div>
</div>

<script src="<?php echo base_url(); ?>assets_admin/vendors/pnotify/dist/pnotify.js"></script>
<script src="<?php echo base_url(); ?>assets_admin/vendors/pnotify/dist/pnotify.buttons.js"></script>
<script src="<?php echo base_url(); ?>assets_admin/vendors/pnotify/dist/pnotify.nonblock.js"></script>
<script src="https://js.pusher.com/6.0/pusher.min.js"></script>

<script>
    // load the buttons

    $(".slider-for").on("afterChange", function(event, slick, currentSlide) {
        $('.show-img .slider-for-img').slick('slickGoTo', currentSlide);
    });

    function sliderRefresh() {
        return
    }

    function goBack() {
        // window.location = '?tab=<?= $tab; ?>';
        // window.location = document.referrer + '?tab=<?= $tab; ?>';
        // window.history.back();

        window.history.back();


    }

    var token_name = '<?= $this->security->get_csrf_token_name(); ?>';
    var token_value = '<?= $this->security->get_csrf_hash(); ?>';

    $('#bid_help').on('click', function() {
        var increment = "<?= $item['minimum_bid_price']; ?>"
        swal("<?= $this->lang->line('what_is_auto_biding'); ?> (" + increment + ") <?= $this->lang->line('what_is_auto_biding_text'); ?>");
    });

    $(document).ready(function() {
        var lng = '<?= $language; ?>';
        $('#eye').tooltip({
            title: 'users visit this item!',
            placement: 'bottom'
        });

        $('.fa-heart-o').tooltip({
            title: '<?= $this->lang->line('fav'); ?>!',
            placement: 'bottom'
        });

        $('.fa-heart').tooltip({
            title: '<?= $this->lang->line('r_fav'); ?>!',
            placement: 'bottom'
        });

        var hidebtn = "<?= isset($hide_auto_bid['auto_status']) ? $hide_auto_bid['auto_status'] : ''; ?>";
        //console.log(hidebtn);
        if (hidebtn == "start") {
            //console.log('start if ', hidebtn);
            // $('#hidebid_section').hide();
            $('.bid-div<?= $item['item_id']; ?>').hide();
            // $('#bid_amount').prop("readonly",true);
            var incText = "<?= ($min['minimum_bid_price']) ? $min['minimum_bid_price'] : 0; ?>";

            if (lng == 'arabic') {

                $('#hideText').html('<div class="bid-now"><p>لقد قمت بتفعيل المزايدة التلقائية بمبلغ' + incText + 'درهم إماراتي  </p></div>');
            } else {
                $('#hideText').html('<div class="bid-now"><p><?= $this->lang->line('you_have_started_auto_bidding'); ?> <?= $this->lang->line('aed'); ?> ' + incText + ' </p></div>');
            }



            // $('#hide_lable').hide();
            // $('#hideA').hide();
        } else {
            //console.log('else ', hidebtn);
            // $('#hidebid_section').show();
            $('#autohide').show();
            $('.bid-div<?= $item['item_id']; ?>').show();

            $('#hideText').hide();
            $('#hide_lable').show();
            $('#hideA').show();
        }
    });

    function doFavt(e) {
        var heart = $(e);
        var itemID = $(e).data('item');
        var auctionId = '<?php echo $this->uri->segment(4); ?>';
        var language = '<?= $language; ?>';
        var auctionItemid = "<?= $item['auction_item_id']; ?>";
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
                console.log(msg);
                //var response = JSON.parse(msg);
                if (msg == 'do_heart') {
                    // heart.find('.fa-heart-o').addClass('fa-heart').removeClass('fa-heart-o');  
                    heart.find('.material-icons').text("favorite");
                    if (language == 'english') {
                        heart.find('.favText').text("Added to wishlist");
                    } else {
                        heart.find('.favText').text("أضيف لقائمة الأماني");
                    }
                }
                if (msg == 'remove_heart') {
                    heart.find('.material-icons').text("favorite_border");
                    if (language == 'english') {
                        heart.find('.favText').text("Add to wishlist");
                    } else {
                        heart.find('.favText').text("أضف إلى قائمة الامنيات");
                    }
                }
                if (msg == '0') {
                    // window.location.replace('<?= base_url("home/login?rurl="); ?>'+encodeURIComponent(window.location.href));
                    $('#loginModal').modal('show');
                }
            }
        });
    }
    var _lng = '<?= $language; ?>';
    var lotno = '';
    var auctionid = '';
    var itemID = '';
    var minimum_bid_price = '';
    var sellerid = '';
    var start_price = '';
    var maximum_bid_amount = '';
    var bid_amount = '';
    var current_price = '';
    var your_bid_amount = '';
    var total_bid = '';

    function placebid(e) {
        // alert('dona done'+ _lng);
        lotno = $(e).data('lot-no');
        heart = $(e);
        auctionid = $(e).data('auction-id');
        itemID = $(e).data('item-id');
        minimum_bid_price = $(e).data('min-bid');
        sellerid = $(e).data('seller-id');
        start_price = $(e).data('start-price');
        maximum_bid_amount = $(e).data('max-bid');
        bid_amount = $('#bid_amount').val();
        current_price = $('#current_price<?= $item['item_id']; ?>').val();
        // your_bid_amount = Number(bid_amount) + Number(current_price);
        your_bid_amount = Number(bid_amount) - Number(current_price);
        // your_bid_amount = Number(bid_amount);
        if (isNaN(your_bid_amount)) {
            your_bid_amount = 0;
            total_bid = '';
        } else {
            if (_lng == 'arabic') {
                var your_bid_amount_with_commas = numberWithCommas(your_bid_amount);
                total_bid = "<?= $this->lang->line('bid_amount_now_new') ?>" + bid_amount + " <?= $this->lang->line('aed'); ?>";


            } else {
                var your_bid_amount_with_commas = numberWithCommas(your_bid_amount);
                total_bid = "<?= $this->lang->line('bid_amount_now_new') . " " . $this->lang->line('aed'); ?> " + bid_amount;

            }

        }
        $(".incrementAmount").text(numberWithCommas(your_bid_amount));
        $(".bidSuccessAmount").text(numberWithCommas(bid_amount));
        $(".totalAmount").text(numberWithCommas(total_bid));
        $("#PlacingBid").modal('show');
    }

    function autoBiding(e) {
        //alert('dona done');
        lotno = $(e).data('lot-no');
        heart = $(e);
        auctionid = $(e).data('auction-id');
        itemID = $(e).data('item-id');
        minimum_bid_price = $(e).data('min-bid');
        sellerid = $(e).data('seller-id');
        bid_amount = $('#auto_limit').val();
        your_bid_amount = Number(bid_amount);
        if (isNaN(your_bid_amount)) {
            your_bid_amount = 0;
            total_bid = '';
        } else {
            var your_bid_amount_with_commas = numberWithCommas(your_bid_amount);
            // total_bid = "<?= $this->lang->line('bid_amount_now'); ?>" + your_bid_amount_with_commas;
            total_bid = "<?= $this->lang->line('place_auto_bid_amount'); ?>"
        }
        $(".incrementAmount").text(numberWithCommas(bid_amount));
        $(".bidSuccessAmount").text(numberWithCommas(bid_amount));
        $(".totalAmount").text(numberWithCommas(total_bid));
        $("#PlacingAutoBid").modal('show');
    }

    function bidsuccess() {
        $("#myModal").modal('hide');
        // $("#confirmBid").modal('show');
    }

    function tryModal() {
        $("#tryAgainModal").modal('hide');
    }

    function bidconfirm() {
        //console.log('itemID :'+itemID+' auctionid :'+auctionid+' lotno :'+lotno+' sellerid :'+sellerid+' minimum_bid_price :'+minimum_bid_price+' bid_amount :'+bid_amount+' maximum_bid_amount :'+maximum_bid_amount);  
        var incmnt = $('.paddinflft0').attr('data-increment');
        var new_bid_amount = Number(bid_amount) + Number(incmnt);
        $("#PlacingBid").modal('hide');
        if (bid_amount < minimum_bid_price) {
            $("#PlacingBid").modal('hide');
            new PNotify({
                type: 'error',
                addclass: 'custom-error',
                text: "<?= $this->lang->line('invalid_attempt'); ?> <?= $this->lang->line('aed'); ?> " + minimum_bid_price,
                styling: 'bootstrap3',
                title: "<?= $this->lang->line('error'); ?>"
            });
        } else {

            if (your_bid_amount > maximum_bid_amount) {
                if (maximum_bid_amount == 0) {
                    $('#deposit_txt').html('<?= $this->lang->line('deposite_money'); ?>');
                }
                $("#enough-balance").modal('show');

                // new PNotify({
                //     type: 'error',
                //     text: "<?= $this->lang->line('insufficient_balance'); ?>",
                //     styling: 'bootstrap3',
                //     title: "<?= $this->lang->line('error'); ?>"
                // });
            } else {
                $.ajax({
                    type: 'post',
                    url: '<?= base_url('auction/OnlineAuction/placebid'); ?>',
                    data: {
                        'item_id': itemID,
                        'auction_id': auctionid,
                        'seller_id': sellerid,
                        'bid_amount': (bid_amount - current_price),
                        'current_price': current_price,
                        'start_price': start_price,
                        [token_name]: token_value
                    },
                    success: function(data) {
                        console.log(data);
                        var response = JSON.parse(data);
                        if (response.status == 'success') {

                            $('.current-btn p').html(response.current_bid);
                            $('#bid_amount').val(new_bid_amount);
                            $('input.quantity').val('0');
                            $('.paddinflft0').attr('data-price', new_bid_amount);
                            $('input#bid_amount').attr('min', new_bid_amount);
                            $('input#bid_amount').val(new_bid_amount);
                            //$('#bid_btn').attr("disabled" , "disabled");
                            $('.minus').prop('disabled', true);
                            $('.minus').css('background', '#b1b1b1');
                            $('.minus').css('border', '#b1b1b1');
                            $("#myModal").modal('show');
                            // noofpaxinputInitVal = parseInt($(noofpaxinput).val()) + parseInt($('.paddinflft0').data("increment"))
                            noofpaxinputInitVal = parseInt($(noofpaxinput).val())
                        }

                        if (response.status == 'bidAmountChanged') {
                            $("#tryAgainModal").modal('show');
                        }

                        if (response.status == 'limitExceed') {
                            $('#limit_exceed_txt').html(response.msg);
                            $("#limitExceedModel").modal('show');
                        }


                        if (response.status == 'fail') {
                            new PNotify({
                                text: "" + response.msg + "",
                                type: 'error',
                                addclass: 'custom-error',
                                title: "<?= $this->lang->line('error'); ?>",
                                styling: 'bootstrap3'
                            });
                        }

                        if (data == '0') {
                            window.location.replace('<?= base_url("home/login"); ?>');
                        }
                        if (response.status == 'soldout') {
                            new PNotify({
                                text: "" + response.msg + "",
                                type: 'info',
                                title: "<?= $this->lang->line('info_cap'); ?>",
                                styling: 'bootstrap3'
                            });
                            $('.your-bid').hide();
                        }
                    }
                });
            }
        }
        window.setTimeout(function() {
            $(".alert").fadeTo(500, 0).slideUp(500, function() {
                $(this).remove();
            });
        }, 5000);
    }

    function initialize() {
        var lat = "<?php echo $item['item_lat']; ?>";
        var lng = "<?php echo $item['item_lng']; ?>";
        var latlng = new google.maps.LatLng(lat, lng);
        var address = "<?php echo $item['item_location']; ?>";
        var map = new google.maps.Map(document.getElementById('map'), {
            center: new google.maps.LatLng(lat, lng),
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
                '<div class="iw_title"><b>Location</b> : ' + address + ' </div></div>';
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
        if (!z) {
            z = 0;
        }
        $("#bid_amount").val(Number(z) + Number(x));
        let bid = $('#bid_amount').val();

        if (bid != '' && bid >= Number(y)) {
            $('#bid_btn').removeAttr("disabled");
            $('button.minus').removeAttr("disabled");
        } else {
            $('#bid_btn').attr("disabled", "disabled");
            $('button.minus').attr("disabled", "disabled");
        }
    }

    $(document).on("input", "#bid_amount", function() {
        var y = '<?= $item['minimum_bid_price'] ?>';
        let bid = $('#bid_amount').val();
        if (bid != '' && bid >= Number(y)) {
            $('#bid_btn').removeAttr("disabled");
            $('button.minus').removeAttr("disabled");
            $('.inputlabelcurr').removeAttr("style")
            $(".noofpax.plus").removeAttr("style")
            $(".noofpax.plus").attr("disable", false)
        } else {
            $('#bid_btn').attr("disabled", "disabled");
            $('button.minus').attr("disabled", "disabled");
            $('button.minus').attr("style", "background: rgb(177, 177, 177);border: rgb(177, 177, 177);");
            $('.inputlabelcurr').removeAttr("style")
            $(".noofpax.plus").removeAttr("style")
            $(".noofpax.plus").attr("disable", false)
        }

        var minVal = parseInt($('.paddinflft0').data("price"))

        if (bid >= minVal) {
            $(".quantity").val(bid - minVal)
        } else {
            $(".quantity").val("0")
            // $(".noofpax.plus").attr("style", "background: rgb(177, 177, 177); border: rgb(177, 177, 177);border-color:red")
            // $(".noofpax.plus").attr("disable", true)
        }

        // if(bid > minVal) {
        // $('.inputlabelcurr').removeAttr("style")
        // $(".noofpax.plus").removeAttr("style")
        // $(".noofpax.plus").attr("disable", false)

        // if(bid > parseInt($(".paddinflft0").data("max"))) {
        //     $(".bid_amount_").val($(".paddinflft0").data("max"))
        //     $('.inputlabelcurr').css("border-color","red")
        // }


        // }

        if ($(this).val().length > 7) {
            $(this).val($(this).val().substr(0, 7))
            $(".quantity").val($(this).val() - minVal)
        }
    });

    $(document).on("input", ".bid_amount_", function() {
        var y = $('.paddinflft0').attr('data-price');
        let bid = $('.bid_amount_').val();
        if (bid != '' && bid >= Number(y)) {
            $('#bid_btn').prop('disabled', false);
            //  $('#bid_btn').removeAttr("disabled");
        } else {
            $('#bid_btn').prop('disabled', true);
            $('#bid_btn').attr("disabled", "disabled");
        }
    });


    $(document).on("input", "#auto_limit", function() {
        var cbp = $('#current_price<?= $item['item_id']; ?>').val();
        let bids = $('#auto_limit').val();

        if (bids != '' && bids >= Number(cbp)) {
            $('#start_bid').removeAttr("disabled");
        } else {
            $('#start_bid').attr("disabled", "disabled");
        }
    });


    // $(document).on("keyup" , "#bid_amount" , function(e){
    //     alrt(e.key)

    // });


    // $(document).on("input" , "#bid_amount" , function(){
    //     let bid_button = $('#bid_amount').val();

    //     if(bid_button != ''){
    //         $('#bid_btn').removeAttr("disabled");
    //     }else{
    //         $('#bid_btn').attr("disabled" , "disabled");
    //     }
    // });

    function autobidconfirm(e) {


        // $("#PlacingBid").modal('show');

        var cbp = $('#current_price<?= $item['item_id']; ?>').val();
        var item = "<?= $item['item_id']; ?>";
        var loginUser = "<?= ($user) ? $user->username : ''; ?>";
        var limit = "<?= $biding_limit; ?>";

        var auctionItem_id = "<?= $item['auction_item_id']; ?>";
        var auction = "<?= $item['auction_id']; ?>";
        //  var bids = $('#auto_limit').val();
        var bids = $('#bid_amount').val();
        var increment = "<?= $item['minimum_bid_price']; ?>";
        var start_price = "$item['bid_start_price']; ?>";
        var sellerid = "<?= $item['item_seller_id']; ?>";

        if (bids != '' && bids > Number(cbp)) {
            //   $('#start_bid').removeAttr("disabled");
            if (Number(limit) <= Number(bids)) {
                swal("<?= $this->lang->line('error'); ?>", "<?= $this->lang->line('limit_exceeds'); ?>", "error");
            } else {
                // $('#bid_amount').val($('#auto_increment').val());
                // $('#bid_btn').click();
                $.ajax({
                    type: 'post',
                    url: "<?= base_url('auction/OnlineAuction/placebid'); ?>",
                    data: {
                        'bid_limit': bids,
                        'bid_amount': increment,
                        'auction_id': auction,
                        'item_id': item,
                        'auction_item_id': auctionItem_id,
                        'seller_id': sellerid,
                        'start_price': start_price,
                        'current_price': cbp,
                        [token_name]: token_value
                    },

                    success: function(data) {

                        var response = JSON.parse(data);
                        if (response.auto_bid_msg = 'true') {

                            window.location.reload(true);
                            // return false;

                            // $('.current-btn p').html(response.current_bid);
                            // $('#auto_limit').val('');
                            // $('#start_bid').attr("disabled" , "disabled");
                            // $("#myModal").modal('show');

                        } else {
                            swal("<?= $this->lang->line('error'); ?>", "<?= $this->lang->line('auto_bid_activated_not'); ?>", "error");
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

        } else {
            swal("Error", "<?= $this->lang->line('bid_limit_should_greater_than'); ?> " + numberWithCommas(cbp), "error");
            $('#start_bid').attr("disabled", "disabled");
        }
    }

    $('#loginBid').on('click', function(event) {
        event.preventDefault();

        var validation = false;
        var language = '<?= $language; ?>';
        selectedInputs = ['email', 'password1'];
        validation = validateFields(selectedInputs, language);

        if (validation == true) {
            var form = $(this).closest('form').serializeArray();

            form[form.length] = {
                name: [token_name],
                value: token_value
            };
            $.ajax({
                type: 'post',
                url: '<?= base_url('home/login_process'); ?>',
                data: form,
                success: function(msg) {
                    console.log(msg);
                    var response = JSON.parse(msg);

                    if (response.error == true) {
                        $('#error-msgs').html('<div class="alert alert-danger alert-dismissible" id="valid-msgs"><button type="button" class="close" data-dismiss="alert">&times;</button>' + response.msg + '</div>');
                    }

                    if (response.msg == 'success') {
                        var rurl = '<?= isset($_GET['rurl']) ? $_GET['rurl'] : ''; ?>';
                        var id = '<?php echo $this->uri->segment(4); ?>';
                        var id2 = '<?php echo $this->uri->segment(5); ?>';
                        if (rurl == '') {
                            window.location.replace('<?= base_url('auction/online-auction/details/'); ?>' + id + '/' + id2);
                        } else {
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

    var user_have_bid = '<?= $user_have_bid->num_rows(); ?>';

    //Enable pusher logging - don't include this in production
    Pusher.logToConsole = true;

    var pusher = new Pusher("<?= $this->config->item('pusher_app_key'); ?>", {
        cluster: 'ap1'
    });
    var user_id = "<?= isset($user) ? $user->id : ''; ?>";
    var lng = "<?= $language; ?>";
    var channel = pusher.subscribe('ci_pusher');
    channel.bind('my-event', function(push) {
        // alert(JSON.stringify(push));
        if (push.status) {
            if (user_id == push.user_id) {
                $('.bid-div' + push.item_id).show();
                $('.hideText' + push.item_id).hide();
            }
        } else {
            /* $('#bid_amount').val(new_bid_amount);
                            $('input.quantity').val('0');
                            $('.paddinflft0').attr('data-price', new_bid_amount);
                 */
            var no_of_bids = parseInt($('#no_of_bids' + push.item_id).text());
            var no_of_bids = no_of_bids + 1;
            $('#no_of_bids' + push.item_id).html(no_of_bids);
            if (lng === 'arabic') {
                $('.' + push.item_id).html(numberWithCommas(push.bid_amount) + ' درهم إماراتي');
            } else {
                $('.' + push.item_id).html('<?= $this->lang->line('aed'); ?> ' + numberWithCommas(push.bid_amount));
            }

            $('#your-bid-amount' + push.item_id).html('<?= $this->lang->line('aed'); ?> ' + numberWithCommas(push.bid_amount) + ' + ');

            //show price+increment on confirm pop-up
            your_bid_amount = Number($("#bid_amount").val()) + Number(push.bid_amount);
            var your_bid_amount_with_commas = numberWithCommas(your_bid_amount);
            total_bid = "<?= $this->lang->line('bid_amount_now'); ?>" + your_bid_amount_with_commas;

            //  $(".totalAmount").text(numberWithCommas(total_bid));

            $('#current_price' + push.item_id).val(push.bid_amount);
            current_price = push.bid_amount;
            var incmnt = $('.paddinflft0').attr('data-increment');
            var new_bid_amounts = Number(current_price) + Number(incmnt);
            //alert(new_bid_amounts)
            // $('#bid_amount').val(new_bid_amounts);
            $('input.bid_amount_').val(new_bid_amounts);
            $('input.quantity').val('0');
            $('.paddinflft0').attr('data-price', new_bid_amounts);
            total_bid_new = "<?= $this->lang->line('bid_amount_now'); ?>" + new_bid_amounts;
            $(".totalAmount").text(numberWithCommas(total_bid_new));


            if (user_id == push.user_id) {



                user_have_bid = parseInt(user_have_bid) + 1;
                // $('#bid_amount').val('');
                $('#bid_user_name' + push.item_id).html('<h style="color:#5C02B5"><?= $this->lang->line('higher_bider'); ?>.</h>');

                // new PNotify({
                //     text: "<?= $this->lang->line('bid_success'); ?>",
                //     type: 'success',
                //     title: "<?= $this->lang->line('success_'); ?>",
                //     styling: 'bootstrap3'
                // });
                // $("#myModal").modal('show');
            } else {

                console.log('num rows: ' + user_have_bid);
                if (user_have_bid > 0) {
                    $('#bid_user_name' + push.item_id).html('<h style="color:#FA8500"><?= $this->lang->line('not_higher_bider'); ?>');
                } else {
                    $('#bid_user_name' + push.item_id).html('');
                }
            }
        }
    });
</script>

<?php
if ($language == 'arabic') {
    $ln = '&region=EG&language=ar';
} else {
    $ln = '';
}
?>
<script src="https://maps.googleapis.com/maps/api/js?key=<?= $this->config->item('google_map_key'); ?>&callback=initialize<?= $ln; ?>"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets_admin/sweetalert/dist/sweetalert.min.js"></script>

<script>
    //comment
    $(document).ready(function() {
        window.setTimeout(function() {
            window.__sharethis__.load('inline-share-buttons', {
                alignment: 'left',
                id: 'my-inline-buttons',
                enabled: true,
                font_size: 11,
                padding: 8,
                radius: 0,
                networks: ['facebook', 'whatsapp', 'twitter', 'email'],
                size: 32,
                show_mobile_buttons: true,
                spacing: 0,
                url: "<?php //echo base_url(); 
                        ?>", // custom url
                title: shareTitle,
                image: shareImage, // useful for pinterest sharing buttons
                description: shareDescription,
                username: "PioneerAuctions" // custom @username for twitter sharing
            });
        }, 3000);
    });



    document
        .querySelectorAll('button.minus')
        .forEach(bt => {
            bt.onclick = e => {
                let P_parent = e.target.parentNode,
                    Inc = parseInt(P_parent.dataset.increment),
                    U_price = parseFloat(P_parent.dataset.price),
                    val = parseInt(P_parent.querySelector('.quantity').value)

                if (val > 0) {
                    val -= Inc
                    P_parent.querySelector('.quantity').value = val
                    //  P_parent.querySelector('.total').textContent = '$ '+ (val + U_price)
                    P_parent.querySelector('.bid_amount_').value = (val + U_price)
                }
            }
        })

    document
        .querySelectorAll('button.plus')
        .forEach(bt => {
            bt.onclick = e => {
                let P_parent = e.target.parentNode,
                    Inc = parseInt(P_parent.dataset.increment),
                    U_price = parseFloat(P_parent.dataset.price),
                    Max = parseInt(P_parent.dataset.max),
                    val = parseInt(P_parent.querySelector('.quantity').value)

                //   if (val < Max)
                //     {
                val += Inc
                P_parent.querySelector('.quantity').value = val
                //  P_parent.querySelector('.total').textContent = '$ '+ (val + U_price)
                P_parent.querySelector('.bid_amount_').value = (val + U_price)
                // }
                $("#bid_btn").is(":disabled") && $("#bid_btn").attr("disabled", false)
            }
        })
</script>
<script>
    $(document).ready(function() {
        $('.customer-logos').slick({
            slidesToShow: 4,
            slidesToScroll: 4,
            autoplay: false,
            autoplaySpeed: 500,
            speed: 500,
            arrows: false,
            dots: false,
            arrows: true,
            pauseOnHover: true,
            pauseOnFocus: true,
            responsive: [{
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


    $(document).ready(function() {
        $("._bidCancelled").click(function() {
            $("#_auto_bid").prop('checked', false);
        });





    });

    $('#_auto_bid').click(function(e) {
        // var b = $("#_auto_bid").val();

        if ($('#_auto_bid').is(":checked")) {
            // console.log('ok')

            //alert('dona done');
            lotno = $(e).data('lot-no');
            heart = $(e);
            auctionid = $(e).data('auction-id');
            itemID = $(e).data('item-id');
            minimum_bid_price = $(e).data('min-bid');
            sellerid = $(e).data('seller-id');
            bid_amount = parseInt($('#bid_amount').val()) < parseInt($('#bid_amount').attr("min")) ? parseInt($('#bid_amount').attr("min")) + parseInt($('#bid_amount').attr("step")) : $("#bid_amount").val();
            your_bid_amount = Number(bid_amount);
            if (isNaN(your_bid_amount)) {
                your_bid_amount = 0;
                total_bid = '';
            } else {
                var your_bid_amount_with_commas = numberWithCommas(your_bid_amount);
                total_bid = "<?= $this->lang->line('place_auto_bid_amount'); ?>"
            }
            $(".incrementAmount").text(numberWithCommas(bid_amount));
            $(".bidSuccessAmount").text(numberWithCommas(bid_amount));
            $(".totalAmount").text(numberWithCommas(total_bid));
            $("#PlacingAutoBid").modal('show');




        } else {
            console.log('oak')
        }

    });
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
                    $(this).parent().parent().parent().parent().remove();
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
                    $(this).parent().parent().parent().parent().remove();
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



    var noofpaxinput = $('#bid_amount'),
        noofpaxinputInitVal = $(noofpaxinput).val();

    $(noofpaxinput).on("input", function() {
        checkNoofpaxinput($(noofpaxinput).val(), noofpaxinputInitVal);
    });

    $('.noofpax').click(function() {
        var bid_amountValue = $(noofpaxinput).val();

        if ($(this).hasClass('plus')) {
            $(noofpaxinput).val(parseInt(bid_amountValue));
        } else if ($(this).hasClass('minus')) {
            $(noofpaxinput).val(parseInt(bid_amountValue));
        }

        checkNoofpaxinput($(noofpaxinput).val(), noofpaxinputInitVal);
    });

    function checkNoofpaxinput(fromValue, Tovalue) {
        if (parseInt(fromValue) <= parseInt(Tovalue) + parseInt($(noofpaxinput).attr("step") - 1)) {
            $('.minus').prop('disabled', true);
            $('.minus').css('background', '#b1b1b1');
            $('.minus').css('border', '#b1b1b1');
        } else {
            $('.minus').prop('disabled', false);
            $('.minus').css('background', '#5c03b5');
            $('.minus').css('border', '#5c03b5');
        }
    }
</script>

<!-- <script>
//comment
    //$(document).ready(function(){
        window.__sharethis__.load('inline-share-buttons', {
            alignment: 'left',
            id: 'my-inline-buttons',
            enabled: true,
            font_size: 11,
            padding: 8,
            radius: 0,
            networks: ['facebook', 'whatsapp', 'twitter', 'email'],
            size: 32,
            show_mobile_buttons: true,
            spacing: 0,
            url: "<?php //echo base_url(); 
                    ?>", // custom url
            title: shareTitle,
            image: shareImage, // useful for pinterest sharing buttons
            description: shareDescription,
            username: "PioneerAuctions" // custom @username for twitter sharing
        });
    //});
</script> -->

<!-- <script src="https://platform-api.sharethis.com/js/sharethis.js#property=6034b2ec5b508c00110259bf&product=sop" async="async"></script> -->


<script>// path to the watermark image
// $(function(){
//  //add text water mark;  background-cover
//  $('img.watermarks').watermark({
//  path: '<?= NEW_ASSETS_USER; ?>/new/images/logo/logo_header_english.svg',
//   textWidth: 100,
//   textColor: 'white'
//  });
   
// })
 
</script>