<!-- <?php 
  $title = json_decode($terms_info['title']);
  $description = json_decode($terms_info['description']);
 ?>
<main class="page-wrapper terms-condition">
    <div class="inner-banner">
      <div class="container">
        <div class="caption">
          <h1 class="page-title"><span><?= $this->lang->line('terms_guides'); ?></span><br>
              <?php if(isset($terms_info['title']) && !empty($terms_info['title'])) {
                echo $title->$language; 
              } ?>
          </h1>
        </div>
      </div>
    </div>
    <div class="gray-bg">
      <div class="container">
        <div class="desc">
          <?php if (isset($terms_info['description']) && !empty($terms_info['description'])) {
            echo $description->$language; 
          }?> 
        </div>
      </div>
    </div>
  </main> -->


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
