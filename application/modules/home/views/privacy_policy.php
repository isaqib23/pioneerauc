


<?= $this->load->view('template/auction_cat') ?>
<?php 
  $title = json_decode($terms_info['title']);
  $description = json_decode($terms_info['description']);
 ?>
<div class="main gray-bg about-us">
    <div class="container">
        <h1 class="section-title"><?= $title->$language; ?></h1>
        <div class="content-box">
            <div class="inner-banner">
                <div class="desc" style="padding-top: 0">
                    <p>
                      <?= $description->$language; ?>
                    </p>
                </div>
            </div>    
        </div>
        </div> 
    </div> 
</div>
