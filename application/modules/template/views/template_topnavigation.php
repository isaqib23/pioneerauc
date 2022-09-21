<div class="top_nav">
  <div class="nav_menu">
    <nav>
      <div class="nav toggle">
        <a id="menu_toggle"><i class="fa fa-bars"></i></a>
      </div>

      <ul class="nav navbar-nav navbar-right">
        <li class="">
          <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">

          <?php 
          $pic_id = $this->session->userdata('logged_in')->picture;
          $pfile = $this->db->get_where('files', ['id' => $pic_id])->row_array(); 
          if(isset($pfile['name']) && !empty($pfile['name'])){ ?>
              <div class="carousel-item active">
                  <img class="d-block" src="<?= base_url($pfile['path'] . $pfile['name']); ?>" height="140" width="254" alt="First slide">
              </div>
          <?php } 
          else { ?>
              <img  class="img-circle profile" src="<?php echo base_url().'uploads/profile_picture/default';?>">
          <?php } ?> 

           <?php echo (isset($logged_in['fname']) && !empty($logged_in['fname'])) ? $logged_in['fname'] : ''; ?>
            <span class=" fa fa-angle-down"></span>
          </a>
          <ul class="dropdown-menu dropdown-usermenu pull-right">
            <li><a href="<?php echo base_url();?>admin/Dashboard/update_profile"><i class="fa fa-child pull-right"></i> Profile </a></li>
            <li><a href="<?php echo base_url();?>admin/Dashboard/update_password"><i class="fa fa-key pull-right"></i> Change Password </a></li>
            <i ></i>
            <li><a href="<?php echo base_url();?>user/logout"><i class="fa fa-sign-out pull-right"></i> Log Out</a></li>
          </ul> 
        </li>
        <?php 
        if(isset($logged_in['role']) && $logged_in['role'] == 4 && $logged_in['type'] == 'buyer')
        { ?>
          <li>
          <a href="<?= base_url()?>transaction/show_deposite_options"><button class="btn-xs btn-success">Pay Deposit</button></a>
          </li>
        <?php
        }
        ?>

          <?php 
          // $session_data = $this->session->userdata('logged_in'); 
          // if($session_data->role == 6){
          //         $user_id =$session_data->id; 
          //         $notify = $this->db->query('select * from notify_me where status = "unseen" AND tasker_id= "'.$user_id.'" ')->result_array();
          //         $count = count($notify);
             ?>
             <li role="presentation" class="dropdown">
                  <a href="javascript:;" onclick="seen_notification(this)" class="dropdown-toggle info-number" data-toggle="dropdown" aria-expanded="false">
                    <i class="fa fa-envelope-o"></i>
                    <span class="badge bg-green" id="notify_count"></span>
                  </a>
                  <ul id="menu1" class="dropdown-menu list-unstyled msg_list notify_me" role="menu">
                    <!-- <li id=""> -->
                
                      <!-- <a id="notify_me"> -->
                        <!-- <span class="image"><img src="images/img.jpg" alt="Profile Image" /></span> -->
                        <!-- <span>
                          <span id="assign_by_name"> </span>
                          <span id="time" class="time"></span>
                        </span>
                        <span id="description" class="message">
                        </span> -->
                      <!-- </a> -->
                    <!-- </li> -->
                  <!--   <li>
                      <div class="text-center">
                        <a>
                          <strong>See All Alerts</strong>
                          <i class="fa fa-angle-right"></i>
                        </a>
                      </div>
                    </li> -->
                  </ul>
              </li>
           
                <!--  <li role="presentation" class="dropdown">
                  <a href="javascript:;" class="dropdown-toggle info-number" data-toggle="dropdown" aria-expanded="false">
                    <i class="fa fa-envelope-o"></i>
                    <span class="badge bg-green">2</span>
                  </a>
                  <ul id="menu1" class="dropdown-menu list-unstyled msg_list" role="menu">
                    <li>
                      <a>
                        <span class="image"><img src="images/img.jpg" alt="Profile Image" /></span> 
                        <span>
                          <span>John Smith</span>
                          <span class="time">3 mins ago</span>
                        </span>
                        <span class="message">
                          Film festivals used to be do-or-die moments for movie makers. They were where...
                        </span>
                      </a>
                    </li>
                    <li>
                      <div class="text-center">
                        <a>
                          <strong>See All Alerts</strong>
                          <i class="fa fa-angle-right"></i>
                        </a>
                      </div>
                    </li>
                  </ul>
              </li> -->
          
              
         

      </ul>
    </nav>
  </div>
</div>



<script type="text/javascript">





 function seen_notification(e)
  {
    var url= '<?php echo base_url(); ?>';
    $.ajax({
      url: url + 'jobcard/seen_notification',
      // data : url,
      success:function(){
        $('#notify_count').html(0);
        // $('#notify_me').html('N');
           }
      });
  }

 var base_url = '<?php echo base_url();?>';
        
        var doneTypingInterval = 500;  //time in ms, 5 second for example
         //setInterval(check_notify, 5000); 
        function check_notify(){
                $.ajax({
                url: base_url + 'jobcard/get_notify',
                success : function(data) {
                  // console.log(data);
                    var objData = jQuery.parseJSON(data);
                    var count = $('#notify_count').val();
                    // alert(count);
                    // if(count < objData.count ){
                    //    playSound();
                    // }
                    $('#notify_count').html(objData.count);
                    if (objData.count > 0) {
                      $.each((objData.data),function(i,v) { 
                        console.log(v); 
                        $('.notify_me').append(v);
                      });
                    } else {
                      $('.notify_me').html('<p class="no-record">No record found.</p>');
                    }
                    

               }
                
            });
         }

    // function playSound()
    //   {
    //   var url = '<?php echo base_url(); ?>'
    //   var audio = new Audio(url+'assets_admin/notification.mp3');
    //   audio.play();
    //   }


       

        // setTimeout(function(){ console.log('abc'); }, 1000);
</script>

