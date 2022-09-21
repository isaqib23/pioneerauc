<!-- sidebar menu -->
<div id="sidebar-menu" class="main_menu_side hidden-print main_menu">

  <div class="menu_section">
    <ul class="nav side-menu">
          <?php 
          $main_permission_check2 = $this->acl_model->main_permission('livecontroller',$logged_in['role']); 
          if($main_permission_check2){ 
          ?>   
           <?php
               if ($this->acl_model->has_permission('livecontroller','index',$logged_in['role']))
               {
                ?>
                <li class=""><a href="<?php echo base_url();?>livecontroller"><i class="fa fa-gavel"></i>   Auction </span></a></li>
              <?php
               } 
               ?>
           <?php
               if ($this->acl_model->has_permission('livecontroller','sold_items',$logged_in['role']))
               {
                ?>
                <li class=""><a href="<?php echo base_url();?>livecontroller/sold/items"><i class="fa fa-gavel"></i> Sold Auction Items </span></a></li>
              <?php
               } 
               ?>

          <?php } ?>  
       </ul> 
  </div>
</div>



