<?php 
if(isset($howToRegister) && !empty($howToRegister)) {
    $title = json_decode($howToRegister['register_title']);
    $description = json_decode($howToRegister['register_description']);
}
?>
<div class="main-wrapper terms-conditions">
    <div class="banner">
        <h1><?php if(isset($howToRegister) && !empty($howToRegister)) { echo $title->$language;}  ?></h1>
    </div>
    <br>
    <!-- <div class="col-lg-12">
        <div class="image">
            <?php //if(isset($howToRegister) && !empty($howToRegister['register_image'])){
                //$file = $this->db->get_where('files', ['id' => $howToRegister['register_image']])->row_array(); ?>
                <img src="<?//= base_url($file['path'] . $file['name']); ?>" alt="">
            <?php //}else{ ?> 
                <img src="<?//= NEW_ASSETS_IMAGES; ?>home-banner.jpg" alt="">
            <?php //} ?>
        </div>
    </div> -->
        <div class="container small">
            <div class="content-box">
                <div class="text-page">
                  <p> <?php if(isset($howToRegister) && !empty($howToRegister)) { echo $description->$language; }  ?> </p>
                </div>
            </div>
        </div>    
    </div>