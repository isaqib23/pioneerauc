<!-- PNotify -->
<link href="<?php echo base_url(); ?>assets_admin/vendors/pnotify/dist/pnotify.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>assets_admin/vendors/pnotify/dist/pnotify.buttons.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>assets_admin/vendors/pnotify/dist/pnotify.nonblock.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets_admin/sweetalert/dist/sweetalert.css">
<style>
    .product .footer{
        text-align:center;
    }
    .mt-20{
        margin-top: 20px;
    }
</style>
<?php
    // $bid_end_time = strtotime($item['bid_end_time']);
    $item_images_ids = explode(',', $item['item_images']);
    $images = $this->db->where_in('id', $item_images_ids)->order_by('file_order', 'ASC')->get('files')->result_array();
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
$en_month = date("M", strtotime($auction_details['start_time']));
$mday = date("d", strtotime($auction_details['start_time']));
$myear = date("Y", strtotime($auction_details['start_time']));

?>

<div class="main-wrapper vehicles">
    <div class="container">
        <a href="javascript:void(0)" onclick="goBack()" class="back-link">
            <span class="material-icons">
                chevron_left
            </span>
            <?= $this->lang->line('back_to_result'); ?>
        </a>
        <div class="description-listing">
            <?php $item_name = json_decode($item['item_name']); ?>
            <h1 class="page-title"><?= strtoupper($item_name->$language);?></h1>
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
                    <span><?= $this->lang->line('sale_date'); ?>: </span>
                    <?php
                    if($language == 'arabic'){
                        $ar_month = $months[$en_month];
                        echo $mday.' '.$ar_month.' '.$myear .' - '.date('h:i ' ,strtotime($auction_details['start_time']));
                    }else{
                       echo  date('jS M Y h:i ', strtotime($auction_details['start_time']));
                    }
                     ?><!--GST--></li>
            </ul>
            <div class="inner-listing">
                <div class="left-side">
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
                            <div class="slider-for">
                                <?php
                                if ($images):
                                    foreach ($images as $key => $value): ?>
                                        <div class="img">
                                            <img class="watermarks" onclick="livesliderRefresh()" data-toggle="modal" data-target="#liveModal" src="<?= base_url('uploads/items_documents/').$item['id'].'/'.$value['name']; ?>">
                                        </div>
                                    <?php endforeach;
                                else: ?>
                                    <img onclick="livesliderRefresh()" data-toggle="modal" data-target="#liveModal" src="<?= base_url('assets_admin/images/product-default.jpg'); ?>" alt="">
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="slider-nav">
                            <?php foreach ($images as $key => $value): ?>
                                <div class="img">
                                    <img class="watermarks" src="<?= base_url('uploads/items_documents/').$item['id'].'/'.$value['name']; ?>">
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php if (($category['include_make_model'] == 'yes')) : ?>
                    <ul class="detail-icons">

                        <li>
                            <h4><?= $this->lang->line('report'); ?></h4>
                            <img src="<?= NEW_ASSETS_USER; ?>/new/images/detail-icons/driving-test-icons.svg">
                            <p><?= ($item['inspected']=='yes')?$this->lang->line('available'):$this->lang->line('not_available');?></p>
                        </li>

                        <li>
                            <h4><?= $this->lang->line('odometer'); ?></h4>
                            <img src="<?= NEW_ASSETS_USER; ?>/new/images/detail-icons/odometer-icons.svg">
                            <p><?= number_format($item['mileage']); ?></p>
                        </li>

                        <li>
                            <h4><?= $this->lang->line('specifications'); ?></h4>
                            <img src="<?= NEW_ASSETS_USER; ?>/new/images/detail-icons/specification-icons.svg">
                            <p><?= $item['specification']; ?></p>
                        </li>
                        <!-- <li>
                            <h4>Color</h4>
                            <img src="<?= NEW_ASSETS_USER; ?>/new/images/detail-icons/color-icons.svg">
                            <p>Black</p>
                        </li> -->
                        <li>
                            <h4><?= $this->lang->line('year'); ?></h4>
                            <img src="<?= NEW_ASSETS_USER; ?>/new/images/detail-icons/year-icons.svg">
                            <p><?= $item['year'] ?></p>
                        </li>
                        <li>
                            <h4><?= $this->lang->line('vat'); ?></h4>
                            <img src="<?= NEW_ASSETS_USER; ?>/new/images/detail-icons/vat-icons.svg">
                            <p><?= $this->lang->line('applicable'); ?></p>
                        </li>
                    </ul>
                    <?php endif; ?>
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
                                <a class="nav-link" id="additional-tab" data-toggle="tab" href="#additional" role="tab" aria-controls="additional" aria-selected="false"><?= $this->lang->line('additional_info');?></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="enquiry-tab" data-toggle="tab" href="#enquiry" role="tab" aria-controls="enquiry" aria-selected="false"><?= $this->lang->line('enquiry'); ?></a>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                                <?php $j=11;
                                if (! empty($item['item_model'])) {
                                    $j=9;
                                    $model = $this->db->get_where('item_models', ['id' => $item['item_model']])->row_array();
                                    $make = $this->db->get_where('item_makes', ['id' => $item['item_make']])->row_array();
                                    $specs = $this->db->get_where('item', ['id' => $item['id']])->row_array();
                                    ?>
                                    <ul>
                                        <?php $make_title = json_decode($make['title']); ?>
                                        <li>Make</li>
                                        <li><?= $make_title->$language; ?></li>
                                    </ul>
                                    <ul>
                                        <?php $model_title = json_decode($model['title']); ?>
                                        <li>Model</li>
                                        <li><?= $model_title->$language; ?></li>
                                    </ul>
                                    <ul>
                                        <li>VIN</li>
                                        <li><?= $item['item_vin_number']; ?></li>
                                    </ul>
                                <?php } ?>
                                <?php $i=0;
                                foreach ($fields as $key => $value) {
                                    if (!empty($value['data-value'])) {
                                        $i++;
                                        if($i<= $j) { ?>
                                            <ul>
                                                <li><?= $this->template->make_dual($value['label']); ?></li>
                                                <li><?= $this->template->make_dual($value['data-value']); ?></li>
                                            </ul>
                                            <?php
                                        }
                                    }
                                } ?>
                                <div class="button-row report-btn">
                                    <ul>
                                        <?php if (($category['include_make_model'] == 'yes') && ($item['inspected']=='yes')) : ?>
                                        <li>
                                            <a href="<?= base_url('inspection_report/').$item['id']; ?>" class="btn-primary" target="_blank"><?= $this->lang->line('report');?></a>
                                        </li>
                                        <?php endif; ?>

                                        <?php
                                        $f = 0;
                                        if (!empty($item['item_test_report']) || $item['item_test_report'] !='' ) {
                                            $test_report_ids = explode(',', $item['item_test_report']);
                                            $item_test_report = $this->db->where_in('id', $test_report_ids)->get_where('files')->result_array();
                                            foreach ($item_test_report as $key1 => $document) { $f++;
                                            ?>
                                                <li>
                                                    <a href="<?= base_url().$document['path'].$document['name']; ?>" class="btn-primary" download><?php $path_parts = pathinfo($document['orignal_name']);
                                                echo $path_parts['filename']; ?></a>
                                                </li>
                                        <?php } } ?>
                                    </ul>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
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
                    <div class="right-image">
                      <div class="right-image-inner">
                        <p class="right-image-heading">
                            <?= $this->lang->line('bid_live_on_this_item'); ?> <br/> 

                             <?php
                      if($language == 'arabic'){
                        $ar_month = $months[$en_month];
                        echo $mday.' '.$ar_month.' '.$myear .' - '.date('h:i ' ,strtotime($auction_details['start_time']));
                    }else{
                       echo  date('jS M Y h:i ', strtotime($auction_details['start_time']));
                    }
                     ?>




                            <!--GST--></p>
                        <p class="right-image-desc">   <?= $this->lang->line('for_more_details');?> </p>

                        
                         <?php if($auction_details['start_status'] == 'start') { ?>
            <a href="<?= base_url('live-online/').$auction_details['id']; ?>" class="btn btn-default mt-20"><?= $this->lang->line('open_live'); ?></a>
            <?php } ?>


                      </div>
                    </div>
                     <div class="google-map">
                        <ul class="flex justify-content-between">
                            <li>
                                <?= $this->lang->line('lot_location');?>
                            </li>
                            <li>
                                <a href="https://www.google.com/maps/@<?= $item['item_lat']; ?>,<?= $item['item_lng']; ?>,15z" target="_blank"><?= $this->lang->line('open_google_maps');?></a>
                            </li>
                        </ul>
                        <div class="map">
                            <div dir="ltr" id="map" style="width: 100%; height: 100%;"></div> 
                            <script src="<?php echo base_url(); ?>assets_admin/js/jquery.geocomplete.js"></script>
                            <!-- <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d13594.266313237882!2d74.3000874!3d31.590931400000002!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2s!4v1612689739457!5m2!1sen!2s" width="100%" height="100%" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe> -->
                        </div>
                    </div>
                    <!-- <div class="add-space">
                        <?= $this->lang->line('ad_space');?>
                    </div> -->
                </div>
            </div>
        </div>

        <div class="similar-vehicles">

            <?php
            if (!empty($related_items)) : ?>
                <h2 class="page-title">
                    <?php if($category['id']==1) :
                        $mainCatHeading =  $this->lang->line('upcoming_new');
                    else:
                       $mainCatHeading =  $this->lang->line('similar_new');
                    endif
                    ?>
                     <?php 
                if($language=='arabic'):
                    echo json_decode($category['title'])->$language.' '.$mainCatHeading;
                else:
                    echo $mainCatHeading .' '.json_decode($category['title'])->$language;
                endif;
                
                ?>

                </h2>
                <div class="products">
                    <div class="customer-logos ">
                        
                            <?php
                            foreach ($related_items as $key => $related_item) :
                                $bid_end_time = strtotime($related_item['bid_end_time']);
                                $bid_info = $this->db->where('item_id',$related_item['item_id'])->where('auction_id',$related_item['auction_id'])->order_by('id', 'desc')->from('bid')->get()->row_array();
                                $visit_count = $this->db->where('item_id',$related_item['item_id'])->where('auction_id',$related_item['auction_id'])->from('online_auction_item_visits')->count_all_results();
                                $related_item_bids_count = $this->db->where('item_id',$related_item['item_id'])->where('auction_id',$related_item['auction_id'])->from('bid')->count_all_results();
                                $item_images_ids = explode(',', $related_item['item_images']);
                                $related_images = $this->db->where_in('id', $item_images_ids)->get('files')->row_array();
                                ?>
                                <div class="slide">
                                    <div class="col-xl-12">
                                        <div class="product">
                                            <div class="body">
                                                <?php $time= $related_item['bid_end_time'];
                                                $date = date('Y-m-d H:i:s');
                                                $url = base_url('live-online-detail/').$related_item['auction_id'].'/'.$related_item['item_id'];
                                                // if ($time > $date) {
                                                // } else {
                                                //     $url = "javascript:void(0)";
                                                // }
                                                ?>
                                                <a href="<?= $url; ?>">

                                                    <?php if ($related_images) { ?>
                                                        <div class="image background-cover" style="background-image: url(<?= base_url('uploads/items_documents/').$related_item['item_id'].'/'.$related_images['name']; ?>);" >
                                                            <!-- <span class="tag">Original price AED <?//= (!empty($bid_info)) ? number_format($bid_info['bid_amount']) : number_format($related_item['bid_start_price']); ?></span> -->
                                                        </div>
                                                    <?php } else { ?>
                                                        <div class="image background-cover" style="background-image: url(<?= base_url('assets_admin/images/product-default.jpg'); ?>);" >
                                                            <!-- <span class="tag"> Original price AED <?//= (!empty($bid_info)) ? number_format($bid_info['bid_amount']) : number_format($related_item['bid_start_price']); ?></span> -->
                                                        </div>
                                                    <?php } ?>
                                                    <h3>
                                                        <?= json_decode($related_item['item_name'])->$language; ?>
                                                    </h3>
                                                </a>
                                                <p><?= json_decode($related_item['item_detail'])->$language; ?></p>
                                            </div>
                                            <!-- <div class="footer">
                                                <ul class="h-list" >
                                                    <?php if ($category['include_make_model'] == 'yes') : ?>
                                                        <li class="d-flex align-items-center">
                                                            <svg width="32" height="14" viewBox="0 0 32 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path d="M13.2783 5.29102H15.4117V6.35793H13.2783V5.29102Z" fill="#9F9F9F"/>
                                                                <path d="M31.4883 3.69576L27.8653 3.17836C27.2046 2.77252 26.519 2.40921 25.8122 2.0904C23.4778 1.03303 20.9442 0.487424 18.3813 0.490245H17.7519C14.7274 0.492416 11.7513 1.25137 9.09487 2.69786L7.2664 3.69533C5.12606 3.71942 3.02696 4.28868 1.16746 5.34865C0.411762 5.78206 -0.0548502 6.58615 -0.0561523 7.45709V10.6242C-0.0561523 10.9189 0.182579 11.1576 0.477305 11.1576H2.26823C2.85073 12.8194 4.67008 13.6945 6.33187 13.112C7.24665 12.7914 7.96567 12.0722 8.28622 11.1576H22.5363C23.1188 12.8194 24.9382 13.6945 26.6 13.112C27.5145 12.7914 28.2338 12.0722 28.5543 11.1576H28.7457C28.8137 11.1576 28.881 11.1448 28.9441 11.1197L30.6717 10.4265C31.4434 10.1209 31.9489 9.37389 31.9461 8.54419V4.22379C31.9461 3.95858 31.7509 3.73352 31.4883 3.69576ZM7.36775 10.5176C7.13184 11.672 6.00481 12.4166 4.85044 12.1807C3.69584 11.9448 2.95122 10.8178 3.18713 9.66319C3.42325 8.50881 4.55028 7.76419 5.70466 8.0001C6.698 8.20302 7.41116 9.07699 7.41094 10.091C7.41094 10.2342 7.3964 10.3772 7.36775 10.5176ZM17.0116 10.091H8.47764C8.47764 8.32347 7.04503 6.89064 5.27755 6.89064C3.51007 6.89064 2.07724 8.32347 2.07724 10.091H1.01054V7.45709C1.0112 6.96856 1.27293 6.51735 1.69701 6.27472C3.43758 5.28094 5.4069 4.7579 7.41094 4.75725H17.0116V10.091ZM17.0116 3.69055H9.50288L9.60575 3.63434C11.8861 2.39511 14.419 1.69259 17.0116 1.58038V3.69055ZM18.0783 1.55694H18.3802C20.5297 1.55455 22.6596 1.96257 24.6561 2.75928L23.7248 3.69055H18.0783V1.55694ZM27.6359 10.5176C27.4 11.672 26.2729 12.4166 25.1183 12.1807C23.9639 11.9448 23.2193 10.8178 23.4552 9.66319C23.6911 8.50881 24.8184 7.76419 25.9728 8.0001C26.9661 8.20302 27.6793 9.07699 27.6791 10.091C27.6791 10.2342 27.6645 10.3772 27.6359 10.5176ZM30.8794 5.29049H29.2792V6.3574H30.8794V8.54419C30.8807 8.93723 30.6411 9.29098 30.2756 9.43531L28.7457 10.0497C28.7457 10.0007 28.7395 9.95205 28.7362 9.903C28.7329 9.85396 28.7319 9.79644 28.7266 9.74305C28.7212 9.68967 28.7106 9.63628 28.7021 9.5831C28.6934 9.52972 28.6871 9.47633 28.6765 9.42793C28.6659 9.37931 28.6503 9.32549 28.6364 9.27471C28.6225 9.22414 28.6118 9.17292 28.5958 9.12322C28.5799 9.07374 28.5606 9.02621 28.5426 8.97759C28.5244 8.9292 28.5079 8.87906 28.4892 8.83088C28.4706 8.78292 28.4454 8.7393 28.423 8.69394C28.4007 8.64858 28.3787 8.59888 28.3538 8.55309C28.3286 8.50729 28.3004 8.46823 28.2763 8.42569C28.2524 8.38293 28.2231 8.33432 28.1928 8.2907C28.1622 8.24686 28.1339 8.21235 28.1046 8.17285C28.0753 8.13335 28.0421 8.08691 28.0081 8.04589C27.974 8.00487 27.9421 7.97275 27.9089 7.93911C27.8759 7.90569 27.839 7.86033 27.8023 7.823C27.7654 7.78567 27.7292 7.75616 27.6925 7.72317C27.6556 7.69018 27.6161 7.65111 27.5755 7.61639C27.5352 7.58188 27.4939 7.55519 27.4536 7.5248C27.413 7.49442 27.3724 7.46186 27.3298 7.43343C27.2871 7.40522 27.2391 7.38026 27.1933 7.35031C27.1473 7.32036 27.11 7.29692 27.0668 7.27413C27.0236 7.25113 26.9698 7.22704 26.9208 7.2036C26.8717 7.18016 26.8333 7.15933 26.7879 7.14023C26.7426 7.12091 26.6812 7.10073 26.628 7.08163C26.5746 7.06231 26.5399 7.04734 26.4945 7.03301C26.4337 7.01435 26.3715 7.00089 26.3094 6.98549C26.2688 6.97594 26.2299 6.96313 26.1889 6.95467C26.1234 6.94121 26.0561 6.93383 25.9895 6.92407C25.95 6.91886 25.9122 6.91018 25.8727 6.90649C25.7659 6.89585 25.6594 6.88999 25.5474 6.88999C23.7806 6.89173 22.3488 8.32347 22.3471 10.0903H18.0786V4.75703H23.9455C24.087 4.75703 24.2226 4.70082 24.3225 4.60077L25.7056 3.21765C26.2862 3.49392 26.8507 3.80276 27.3965 4.14262C27.4594 4.18191 27.5297 4.20773 27.6033 4.21837L30.8794 4.68606V5.29049Z" fill="#9F9F9F"/>
                                                                <?php
                                                                if ($language == 'english') {
                                                                    if ($related_item['specification'] == 'GCC') {
                                                                        $specsType = 'GCC';
                                                                    }else{
                                                                        $specsType = 'IMPORTED';
                                                                    }
                                                                }else{
                                                                    if ($related_item['specification'] == 'GCC') {
                                                                        $specsType = 'خليجية';
                                                                    }else{
                                                                        $specsType = 'وارد';
                                                                    }
                                                                } ?>
                                                            </svg> <?= $specsType; ?>
                                                        </li>
                                                        <li class="d-flex align-items-center">
                                                            <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M17 1.88086H19.8125C20.7453 1.88086 21.5 2.63555 21.5 3.56836V19.6934C21.5 20.6238 20.7453 21.3809 19.8125 21.3809H2.1875C1.25469 21.3809 0.5 20.6262 0.5 19.6934V3.56836C0.5 2.63555 1.25469 1.88086 2.1875 1.88086H5V1.22461C5 0.758203 5.37734 0.380859 5.84375 0.380859C6.31016 0.380859 6.6875 0.758203 6.6875 1.22461V1.88086H15.3125V1.22461C15.3125 0.758203 15.6898 0.380859 16.1562 0.380859C16.6227 0.380859 17 0.758203 17 1.22461V1.88086ZM5 3.56836H2.1875V6.19336H19.8125V3.56836H17V4.03711C17 4.50352 16.6227 4.88086 16.1562 4.88086C15.6898 4.88086 15.3125 4.50352 15.3125 4.03711V3.56836H6.6875V4.03711C6.6875 4.50352 6.31016 4.88086 5.84375 4.88086C5.37734 4.88086 5 4.50352 5 4.03711V3.56836ZM19.8125 19.6934H2.1875V7.88086H19.8125V19.6934ZM7.25 10.8809H5.75C5.33516 10.8809 5 11.216 5 11.6309C5 12.0457 5.33516 12.3809 5.75 12.3809H7.25C7.66484 12.3809 8 12.0457 8 11.6309C8 11.216 7.66484 10.8809 7.25 10.8809ZM10.25 10.8809H11.75C12.1648 10.8809 12.5 11.216 12.5 11.6309C12.5 12.0457 12.1648 12.3809 11.75 12.3809H10.25C9.83516 12.3809 9.5 12.0457 9.5 11.6309C9.5 11.216 9.83516 10.8809 10.25 10.8809ZM16.25 10.8809H14.75C14.3352 10.8809 14 11.216 14 11.6309C14 12.0457 14.3352 12.3809 14.75 12.3809H16.25C16.6648 12.3809 17 12.0457 17 11.6309C17 11.216 16.6648 10.8809 16.25 10.8809ZM5.75 15.4277H7.25C7.66484 15.4277 8 15.7629 8 16.1777C8 16.5926 7.66484 16.9277 7.25 16.9277H5.75C5.33516 16.9277 5 16.5926 5 16.1777C5 15.7629 5.33516 15.4277 5.75 15.4277ZM11.75 15.4277H10.25C9.83516 15.4277 9.5 15.7629 9.5 16.1777C9.5 16.5926 9.83516 16.9277 10.25 16.9277H11.75C12.1648 16.9277 12.5 16.5926 12.5 16.1777C12.5 15.7629 12.1648 15.4277 11.75 15.4277Z" fill="#9F9F9F"/>
                                                            </svg>
                                                            <?= $related_item['year']; ?></li>
                                                        <li class="d-flex align-items-center">
                                                            <svg width="21" height="17" viewBox="0 0 21 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path d="M12.4768 15.3418H8.52295C8.18318 15.3418 7.90771 15.6173 7.90771 15.957C7.90771 16.2968 8.18318 16.5723 8.52295 16.5723H12.4768C12.8166 16.5723 13.0921 16.2968 13.0921 15.957C13.0921 15.6173 12.8166 15.3418 12.4768 15.3418Z" fill="#9F9F9F"/>
                                                                <path d="M12.2308 11.5461C12.36 11.2869 12.433 10.9948 12.433 10.6855C12.433 9.61813 11.5677 8.75287 10.5004 8.75287C10.191 8.75287 9.89885 8.82596 9.63959 8.9552L6.74167 6.05716C6.50144 5.81689 6.11188 5.81689 5.87161 6.05716C5.63134 6.29738 5.63134 6.68691 5.87161 6.92722L8.76973 9.82547C8.64074 10.0845 8.56781 10.3764 8.56781 10.6855C8.56781 11.7528 9.43308 12.6181 10.5004 12.6181C10.8096 12.6181 11.1015 12.5451 11.3606 12.4161L12.0423 13.0979C12.2826 13.3382 12.6721 13.3382 12.9124 13.098C13.1527 12.8577 13.1527 12.4682 12.9124 12.2279L12.2308 11.5461ZM10.5004 11.3876C10.1132 11.3876 9.79824 11.0727 9.79824 10.6855C9.79824 10.2984 10.1132 9.98338 10.5004 9.98338C10.8876 9.98338 11.2025 10.2984 11.2025 10.6855C11.2025 11.0727 10.8875 11.3876 10.5004 11.3876Z" fill="#9F9F9F"/>
                                                                <path d="M10.5 0.185547C4.71028 0.185547 0 4.89582 0 10.6855C0 12.5308 0.486527 14.3465 1.40704 15.9362C1.57709 16.2299 1.95288 16.3304 2.24679 16.1608C2.24692 16.1608 3.95924 15.1723 3.95924 15.1723C4.25348 15.0024 4.3543 14.6262 4.18441 14.3319C4.01453 14.0376 3.63833 13.9367 3.344 14.1067L2.18195 14.7776C1.64817 13.6939 1.33215 12.5109 1.25171 11.3008H2.59219C2.93196 11.3008 3.20742 11.0254 3.20742 10.6856C3.20742 10.3458 2.93196 10.0704 2.59219 10.0704H1.25139C1.33309 8.82979 1.65978 7.65523 2.18371 6.59457L3.34404 7.26447C3.63837 7.43448 4.01465 7.33354 4.18446 7.0393C4.35434 6.74501 4.25353 6.36873 3.95928 6.19889L2.80038 5.52976C3.47488 4.52578 4.34027 3.66039 5.34425 2.98589L6.0133 4.14466C6.18319 4.43895 6.55946 4.53985 6.85371 4.36984C7.14796 4.19991 7.24877 3.82367 7.07889 3.52943L6.40902 2.36922C7.46968 1.84533 8.64429 1.51884 9.88477 1.43702V2.77778C9.88477 3.11755 10.1602 3.39301 10.5 3.39301C10.8398 3.39301 11.1152 3.11755 11.1152 2.77778V1.43702C12.3557 1.51884 13.5303 1.84537 14.591 2.36922L13.9211 3.52943C13.7512 3.82367 13.852 4.19995 14.1463 4.36984C14.4405 4.53985 14.8168 4.43895 14.9867 4.14466L15.6557 2.98589C16.6597 3.66035 17.5252 4.52574 18.1996 5.52976L17.0408 6.19881C16.7465 6.36869 16.6457 6.74493 16.8156 7.03922C16.9854 7.33346 17.3617 7.4344 17.656 7.26439L18.8163 6.59448C19.3402 7.65515 19.667 8.82971 19.7487 10.0703H18.4078C18.068 10.0703 17.7926 10.3457 17.7926 10.6855C17.7926 11.0253 18.068 11.3007 18.4078 11.3007H19.7483C19.6679 12.5108 19.3518 13.6938 18.8181 14.7775L17.656 14.1066C17.3617 13.9366 16.9855 14.0375 16.8156 14.3318C16.6457 14.6261 16.7465 15.0024 17.0408 15.1722C17.0408 15.1722 18.7531 16.1607 18.7532 16.1608C19.0472 16.3303 19.4229 16.2299 19.593 15.9362C20.5135 14.3465 21 12.5309 21 10.6855C21 4.89582 16.2897 0.185547 10.5 0.185547Z" fill="#9F9F9F"/>
                                                            </svg>
                                                            <?php
                                                            if ($language == 'english') {
                                                                if ($related_item['mileage_type'] == 'km') {
                                                                    $mileageType = 'Km';
                                                                }else{
                                                                    $mileageType = 'miles';
                                                                }
                                                            }else{
                                                                if ($related_item['mileage_type'] == 'km') {
                                                                    $mileageType = ' كيلومتر ';
                                                                }else{
                                                                    $mileageType = ' ميل ';
                                                                }
                                                            } ?>
                                                            <?= number_format($related_item['mileage']).' '.$mileageType; ?>
                                                        </li>
                                                    <?php else : ?>
                                                        <li class="d-flex align-items-center">
                                                            <svg width="16" height="12" viewBox="0 0 16 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M14.7269 5.21836C14.9144 5.66836 15.0487 6.10117 15.0534 6.18867V6.19492V6.19805C15.0487 6.28555 14.9144 6.71836 14.7269 7.16836C14.5347 7.63242 14.2097 8.3043 13.7597 8.86523C13.1675 9.60273 12.4409 10.1918 11.6003 10.6121C10.5597 11.134 9.34873 11.398 8.00029 11.398C6.65342 11.398 5.44248 11.134 4.40029 10.6121C3.55654 10.1902 2.82998 9.60117 2.24092 8.86367C1.79248 8.3043 1.46904 7.63242 1.27529 7.16836C1.08779 6.7168 0.953418 6.28398 0.94873 6.19648V6.19336V6.19023C0.953418 6.10273 1.08623 5.66992 1.27373 5.21992C1.46592 4.75586 1.78936 4.08398 2.23936 3.52148C2.83467 2.78086 3.56123 2.19336 4.39873 1.77461C5.43467 1.2543 6.64717 0.990234 7.99873 0.990234C9.35185 0.990234 10.5644 1.25273 11.6003 1.77305C12.444 2.19492 13.1706 2.78398 13.7597 3.52148C14.2097 4.08242 14.5347 4.7543 14.7269 5.21836ZM12.8878 8.15664C13.4597 7.44414 13.805 6.46289 13.894 6.19102C13.805 5.91914 13.4581 4.93633 12.8831 4.21758C12.394 3.60508 11.794 3.11758 11.0972 2.76914C10.2206 2.32695 9.17842 2.10352 7.99873 2.10352C6.82373 2.10352 5.77998 2.32695 4.89561 2.76914C4.20029 3.11758 3.59873 3.60352 3.10811 4.21602C2.53311 4.93477 2.18623 5.91758 2.09717 6.18945C2.18623 6.46289 2.53311 7.44727 3.10811 8.16289C3.59717 8.77539 4.19717 9.26289 4.89404 9.61133C5.77686 10.0535 6.81904 10.2785 7.99404 10.2785C9.16748 10.2785 10.2112 10.0535 11.094 9.61133C11.7925 9.25977 12.394 8.77383 12.88 8.16445L12.8878 8.15664ZM7.99932 3.4707C6.50557 3.4707 5.2915 4.68633 5.2915 6.17852C5.2915 7.6707 6.50713 8.88633 7.99932 8.88633C9.4915 8.88633 10.7071 7.6707 10.7071 6.17852C10.7071 4.68633 9.49307 3.4707 7.99932 3.4707ZM9.12607 7.3082C9.42764 7.00664 9.59326 6.60664 9.59326 6.18164C9.59326 5.75664 9.42764 5.35664 9.12607 5.05508C8.82451 4.75352 8.42451 4.58789 7.99951 4.58789C7.57451 4.58789 7.17451 4.75352 6.87295 5.05508C6.57139 5.35664 6.40576 5.75664 6.40576 6.18164C6.40576 6.60664 6.57139 7.00664 6.87295 7.3082C7.17451 7.60977 7.57451 7.77539 7.99951 7.77539C8.42451 7.77539 8.82451 7.60977 9.12607 7.3082Z" fill="black"/>
                                                            </svg> <?= $visit_count; ?> <?= $this->lang->line('views');?>
                                                        </li>
                                                        <li class="d-flex align-items-center">
                                                            <a href="<?= $url; ?>"><?= $this->lang->line('view_details');?></a>
                                                        </li>
                                                        <li class="d-flex align-items-center">
                                                            <a href="<?= $url; ?>"><?= $this->lang->line('bid_now');?></a>
                                                        </li>
                                                    <?php endif; ?>
                                                </ul>
                                            </div> -->
                                            <div class="footer">

                                            <a href="<?= $url; ?>" class="">

                                                View Details

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
<!-- Slider image modal -->
<!-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#imgModal">
  Open modal
</button> -->

<div class="modal fade login-modal style-2 image-modal" id="liveModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                                    <img class="watermarks" src="<?= base_url('uploads/items_documents/').$item['id'].'/'.$img_value['name']; ?>">
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
<script src="<?php echo base_url(); ?>assets_admin/vendors/pnotify/dist/pnotify.js"></script>

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
</script>

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
    function livesliderRefresh(){
        $('.show-img .slider-for-img')[0].slick.refresh()
    }
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

    function goBack() {
      window.history.back();
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

</script>

<script type="text/javascript" src="<?php echo base_url();?>assets_admin/sweetalert/dist/sweetalert.min.js"></script>

<?php 
if($language == 'arabic'){
    $ln = '&region=EG&language=ar';
}else{
    $ln = '';
}
?>
<script src="https://maps.googleapis.com/maps/api/js?key=<?= $this->config->item('google_map_key'); ?>&callback=initialize<?= $ln; ?>"></script>



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