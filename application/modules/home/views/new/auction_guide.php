<?php 
if(isset($auctionGuide) && !empty($auctionGuide)) {
    $title = json_decode($auctionGuide['auction_guide_title']);
    $description = json_decode($auctionGuide['auction_guide_description']);
}
?>
<div class="main-wrapper terms-conditions">
    <div class="banner">
        <h1><?php if(isset($auctionGuide) && !empty($auctionGuide)) { echo $title->$language;}  ?></h1>
    </div>
    <br>
    <!-- <div class="col-lg-12">
        <div class="image">
            <?php //if(isset($v) && !empty($v['quality_policy_image'])){
                //$file = $this->db->get_where('files', ['id' => $v['quality_policy_image']])->row_array(); ?>
                <img src="<?//= base_url($file['path']. $file['name']); ?>" alt="">
            <?php //}else{ ?> 
                <img src="<?//= NEW_ASSETS_IMAGES; ?>home-banner.jpg" alt="">
            <?php //} ?>
        </div>
    </div> -->
    <div class="container small">
        <div class="content-box">
            <div class="text-page">
              <p> <?php if(isset($auctionGuide) && !empty($auctionGuide)) { echo $description->$language;}  ?> </p>
            </div>
        </div>
    </div>    
</div>