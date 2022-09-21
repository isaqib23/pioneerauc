
<?php 
    $title = json_decode($terms_info['title']);
    $description = json_decode($terms_info['description']);
?>
<div class="main-wrapper terms-conditions">
        <div class="banner">
            <h1><?= $title->$language; ?></h1>
        </div>
        <div class="container small">
            <div class="content-box">
                <div class="text-page">
                  <!-- <h3><?//= $title->$language; ?></h3> -->
                  <p> <?= $description->$language; ?> </p>
                </div>
            </div>
        </div>    
    </div>