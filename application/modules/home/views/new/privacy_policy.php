<?php 
  $title = json_decode($terms_info['title']);
  $description = json_decode($terms_info['description']);
 ?>
<div class="main-wrapper terms-conditions">
        <div class="banner">
            <h1><?php if(isset($terms_info) && !empty($terms_info)) { echo $title->$language; }  ?></h1>
        </div>
        <div class="container small">
            <div class="content-box">
                <div class="text-page">
                  <h3><?php if(isset($terms_info) && !empty($terms_info)) { echo $title->$language;}  ?></h3>
                  <p> <?php if(isset($terms_info) && !empty($terms_info)) { echo $description->$language; }  ?></p>
                  <!-- <h3>Sub Title</h3>
                  <p><?= $description->$language; ?></p> -->
                 <!--  <ul>
                    <li>Lorem ipsum dolor sit amet, consectetur adipisicing elit</li>
                    <li>Lorem ipsum dolor sit amet, consectetur adipisicing elit</li>
                    <li>Lorem ipsum dolor sit amet, consectetur adipisicing elit</li>
                    <li>Lorem ipsum dolor sit amet, consectetur adipisicing elit</li>
                  </ul> -->
                </div>
            </div>
        </div>    
    </div>