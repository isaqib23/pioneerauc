<?php 
  $title = json_decode($terms_info['title']);
  $description = json_decode($terms_info['description']);
 ?>
<main class="page-wrapper terms-condition">
    <div class="inner-banner">
      <div class="container">
        <div class="caption">
          <h1 class="page-title"><span><?= $this->lang->line('terms_guides'); ?></span><br>
              <?= $title->$language; ?>
          </h1>
        </div>
      </div>
    </div>
    <div class="gray-bg">
      <div class="container">
        <div class="desc">
          <?= $description->$language; ?>
          
        </div>
      </div>
    </div>
  </main>