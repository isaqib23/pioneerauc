<?php 
    $title = json_decode($qualityPolicy['quality_policy_title']);
    $description = json_decode($qualityPolicy['quality_policy_description']);

?>
<div class="main-wrapper terms-conditions">
        <div class="banner">
            <h1><?php if(isset($qualityPolicy) && !empty($qualityPolicy)) { echo $title->$language; }?></h1>
        </div>
        <br>
        <!-- <div class="col-lg-12">
        <div class="image">
            <?php //if(isset($qualityPolicy) && !empty($qualityPolicy['quality_policy_image'])){
                //$file = $this->db->get_where('files', ['id' => $qualityPolicy['quality_policy_image']])->row_array(); ?>
                <img src="<?//= base_url($file['path'] . $file['name']); ?>" alt="">
            <?php //}else{ ?> 
                <img src="<?//= NEW_ASSETS_IMAGES; ?>home-banner.jpg" alt="">
            <?php //} ?>
        </div>
        </div> -->
        <div class="container small">
            <div class="content-box">
                <div class="text-page">
                  <!-- <h3><?php// if(isset($qualityPolicy) && !empty($qualityPolicy)) { echo $title->$language;}?></h3> -->
                  <p> <?php if(isset($qualityPolicy) && !empty($qualityPolicy)) { echo $description->$language; }?> </p>
                </div>
            </div>
        </div>    
    </div>


   