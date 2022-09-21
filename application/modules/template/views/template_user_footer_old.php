    <footer>
        <div class="container">
            <div class="row stack-on-414">
                <div class="col-lg-3 col-6">
                    <h3>Auctions</h3>
                    <ul>

                        <?php $category = $this->db->get('item_category')->result_array(); ?>
                        <!-- <li><a href="<?php// echo base_url('visitor/gallery/1');?>">Vehicles</a></li> -->
                        <?php foreach ($category as $key => $value) { 
                                $title= json_decode($value['title']);
                                ?>
                            <li><a href="<?= base_url('visitor/gallery/').$value['id']; ?>" ><?= $title->english; ?></a></li>
                            <?php } ?>
                    </ul>
                </div>
                <div class="col-lg-3 col-6">
                    <h3> About Us</h3>
                    <ul>
                        <li><a href="<?php echo base_url('visitor/about_us')?>">Overview</a></li>
                        <li><a href="<?= base_url('visitor/qualityPolicy');?>">Quality Policy</a></li>
                        <li><a href="<?php echo base_url('visitor/about_us#hist')?>">Our History</a></li>
                        <li><a href="<?= base_url('visitor/our_team')?>">Our Team</a></li>
                        <li><a href="#">Our Partners</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-6">
                    <h3>Quick Links</h3>
                    <ul>
           

                        <li><a href="<?= base_url('visitor/gallery')?>">Media</a></li>
                        <li><a href="<?= base_url('home/policy')?>">Privacy Policy</a></li>
                        <li><a href="<?= base_url('home/terms')?>">Terms and Conditions</a></li>
                        <li><a href="<?= base_url('visitor/faqs')?>">FAQ's</a></li>
                        <a href="<?= base_url('visitor/contact_us')?>"><li>Contact us</li></a>
                    </ul>
                     <h3><a href="<?= base_url('customer/sell_item')?>" class="wifi-signal">
                            <img src="<?= ASSETS_IMAGES;?>footer-notif.png">
                             Sell my item
                        </a></h3>
                </div>

                
                <?php 
                $data=$this->db->get('contact_us')->row_array();
                ?>
                <div class="col-lg-3 col-6">
                   
                    <h3><?php echo $data['mobile'];?></h3>
                    <ul>
                        <li><?php echo $data['address'];?> <br>
                            Email:<a href="#" class="email"> <?php echo $data['email'];?></a></li>
                    </ul>
                    <ul>        
                        <!-- <li><h3>Subscribe to our newsletter</h3></li> -->
                        <a href="<?= base_url('visitor/contact_us')?>"><li>Connect with us</li></a>
                    </ul>  
                    <?php $social = $this->db->get('social_links')->row_array();
                    ?> 
                    <ul class="social-icons">
                        <li>
                            <a href="<?php echo $social['facebook']?>"target="_blank"><i class="fa fa-facebook"></i></a>
                        </li>
                        <li>
                            <a href="<?php echo $social['google_plus']?>"target="_blank"><i class="fa fa-google-plus"></i></a>
                        </li>
                        <li>
                            <a href="<?php echo $social['linked_in']?>"target="_blank"><i class="fa fa-linkedin"></i></a>
                        </li>
                        <li>
                            <a href="<?php echo $social['twitter']?>"target="_blank"><i class="fa fa-instagram"></i></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>      
    </footer>
    
        <!-- Modal -->
        <!-- <form method="post"name="myForm"> -->
        <div class="modal fade" id="exampleModal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
                      </button>
                    </div>
              
                    <div class="modal-body" id="resend">
                        <h3 style="color:green;" id="success_msg"></h3>     
                        <!-- <h3 style="color:green;" id="successs"></h3> -->
                        <h4 id="verification_code" class="heading">Enter the verification code</h4>
                        <p id="one_time_password">A One Time Password has been sent to <span id="nmbr"></span></p>
                            <input id="input" type="code" class="form-control" placeholder="Code" name="code"> 
                            <span id="valid-error" class="valid-error text-danger code-error"></span>
                            <span id="agree-error" class="text-danger"></span>
                            <div id="button_di" class="button-row">
                                <!-- <h3 style="color:green;" id="success_msgss"></h3> -->
                               <a href="javascript:void(0)" id="confirm-verification"   class="btn btn-default">CONFIRM</a>

                            </div>
                            <div class="note" id="resend_c">
                                <p>Note: If you did not receive a code, <a href="javascript:void(0)" onclick="resendCode()">Request new code</a id=""></p>
                            </div>
                    </div>   
                </div>      
            </div>
        </div>

          <!-- ///////////// model for resend again code ////////////// -->
          <div class="modal fade" id="resendModal" tabindex="-1" data-backdrop="static" data-keyboard="false" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body" id="rresend">
                    <h3 style="color:green;" id="rsuccess_msg"></h3>     
                    <h3 style="color:green;" id="rsuccess"></h3>
                    <h4 id="rverification_code" class="heading">Enter the verification code</h4>
                    <p id="rone_time_password">A One Time Password has been sent to your number</p>
                        <input id="rinput" type="code" class="form-control" placeholder="Code" name="codee"> 
                        <span class="valid-error text-danger code-error"></span>
                        <span id="ragree-error" class="text-danger"></span>
                        <div id="rbutton_di" class="button-row">
                            <!-- <h3 style="color:green;" id="success_msgss"></h3> -->
                           <a href="javascript:void(0)" id="confirm-verification2"   class="btn btn-default">CONFIRM</a>

                        </div>
                        <div class="note" id="rresend_c">
                            <p>Note: If you did not receive a code, <a href="javascript:void(0)" onclick="resendCode()">Request new code</a id="rsnd"></p>
                        </div>
                </div>   
            </div>      
           
            </div>
          </div>

          <!-- ///////////// resend again model end here ///////////////// -->
          <!-- </form> -->

            <div class="modal fade" id="emailmodel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                  
                        <div class="modal-body">
                            <h4 class="heading">Enter your email for reset password</h4>
                            <p>A reset link will sent to your Email </p>
                            <input type="code" class="form-control" placeholder="Email" name="email2">
                            <div class="button-row">
                                <div class="button-row" id="error-msg_email" ></div>
                                <!-- <div class="button-row text-success" id="error-msg_email_success" ></div> -->
                               <a href="javascript:void(0)" id="confirm-email"   class="btn btn-default">CONFIRM</a>
                            </div>
                            <!-- <div class="note">
                                <p>Note: If you did not receive a link, <a href="javascript:void(0)">Request new link</a></p>
                            </div> -->
                        </div>
                    </div>      
                </div>
            </div>
        </div>
</body>
</html>

         
         <script>
        $('#confirm-verification').on('click', function(event){
        event.preventDefault();

        var validation = false;
        var e;
        var errorMsg = "This value is required.";
        var errorClass = ".valid-error";
        selectedInputs = ['code'];

        $.each(selectedInputs, function(index, value){
            e = $('input[name='+value+']');

            if(($.trim(e.val()) == "") || (e.val() == null) || (typeof e.val() === 'undefined')){
                e.focus();
                validation = false;
                return false;
            }else{
                validation = true;
            }
        });

        if(validation == true){
            var code = $('input[name=code]').val();
            $.ajax({
                type: 'post',
                url: '<?= base_url('home/verify_code'); ?>',
                data: {'code': code},
                success: function(msg){
                    console.log(msg);
                    var response = JSON.parse(msg);

                    if(response.error == true){
                        $('#valid-error').html('');
                        $('#valid-error').html(response.msg);
                    }

                    if(response.success == true){            
                        // window.location.replace('<?= base_url('customer/dashboard/  '); ?>' + response.user_id); 

                         $('#success_msg').html(response.msg);

                         $('#one_time_password').hide();
                         $('#verification_code').hide();
                         $('#confirm-verification').hide();
                         $('#button_di').hide();
                         $('#input').hide();
                         $('#resend_c').hide();
                        $('#valid-error').hide();
                        window.setTimeout(function() {
                            window.location.replace('<?= base_url('home/login'); ?>');
                        }, 3000);
                    }
                     // else{
                     //        window.location.replace('<?//= base_url('home/login/'); ?>' + response.user_id);
                     //    }

                }
            });
        }
    });

    </script>

    <!-- ///// resend confirm button ///////////-->
    <script>
        $('#confirm-verification2').on('click', function(event){
        event.preventDefault();

        var validation = false;
        var e;
        var errorMsg = "This value is required.";
        var errorClass = ".valid-error";
        selectedInputs = ['codee'];

        $.each(selectedInputs, function(index, value){
            e = $('input[name='+value+']');

            if(($.trim(e.val()) == "") || (e.val() == null) || (typeof e.val() === 'undefined')){
                e.focus();
                validation = false;
                return false;
            }else{
                validation = true;
            }
        });

        if(validation == true){
            var code = $('input[name=codee]').val();
            $.ajax({
                type: 'post',
                url: '<?= base_url('home/verify_code'); ?>',
                data: {'code': code},
                success: function(msg){
                    console.log(msg);
                    var response = JSON.parse(msg);

                    if(response.error == true){
                        $('#ragree-error').html(response.msg);
                    }

                    if(response.success == true){            
                        // window.location.replace('<?= base_url('customer/dashboard/  '); ?>' + response.user_id); 

                         $('#rsuccess_msg').html(response.msg);

                         $('#rsuccess').hide();
                         $('#rverification_code').hide();
                         $('#rconfirm-verification2').hide();
                         $('#rbutton_di').hide();
                         $('#rone_time_password').hide();
                         $('#rinput').hide();
                         $('#rresend_c').hide();
                         $('#rsnd').hide();
                        $('#ragree-error').hide();
                        window.setTimeout(function() {
                            window.location.replace('<?= base_url('home/login'); ?>');
                        }, 3000);
                    }
                     // else{
                     //        window.location.replace('<?= base_url('home/login_user/'); ?>' + response.user_id);
                     //    }

                }
            });
        }
    });

    </script>
      

    <!-- ///////////////////////// Resend Code  /////////////////////////////// -->
     <script>
        function resendCode(){
                var phoneNumber = $('#nmbr').html();
             $.ajax({
                type: 'post',
                url: '<?= base_url('home/resend_mobile_code'); ?>',
                data: {'phone': phoneNumber},
                success: function(msg){
                    console.log(msg);
                    var response = JSON.parse(msg);

                    if(response.error == true){
                        $('#valid-error').html(response.msg);
                    }

                    if(response.success == true){            
                        // window.location.replace('<?= base_url('customer/dashboard/  '); ?>' + response.user_id); 


                         $('#resendModal').modal();
                         $('#exampleModal').hide();
                       
                    }
                }
            });
        }
    </script>
      
<?php 
    // $id = '';
    // if ($this->session->userdata('logged_in')) {
    //     $id = $this->session->userdata('logged_in')->id;
    // }
    // if ($this->session->userdata('user_id')) {
    //     $user_id = $this->session->userdata('user_id');
    //     print_r($user_id);
    // }else{
    //     $user_id = '';
    // } 
?>
    <!-- ///////////////////////// Manage flag of user  /////////////////////////////// -->
     <!-- <script>
        window.setInterval(function(){
          changeflag();
        }, 20000);
        var u_id = '<?= $id ?>';
        // alert(u_id);
        if (u_id == '') { 
            var id = '';
        }else{
            var id = '<?= $id; ?>';
        }
            var user_id = '<?= $user_id ?>';
        function changeflag(){
            console.log(id);
        if(id == '' && user_id != ''){
            console.log(user_id);
            $.ajax({    
                type: 'post',
                url: '<?//= base_url('home/changeflag'); ?>',
                data: {'user_id': user_id},
                success: function(msg){
                    console.log(msg);
                    var response = JSON.parse(msg);
                    if(response.success == true){
                        window.location.replace("<?//= base_url('home/login') ?>");
                        // alert(response.msg);
                    }else{
                            
                    }
                }
            });
        }else{
            console.log('user_id');
        }
        //         var phoneNumber = $('#nmbr').html();
        //      
        }
    </script> -->



    <!-- ////////////////////////////// RESET LINK ////////////////////// -->
    <script>
        $('#confirm-email').on('click', function(event){
        event.preventDefault();

        var validation = false;
        var e;
        selectedInputs = ['email2'];

        $.each(selectedInputs, function(index, value){
            e = $('input[name='+value+']');

            if(($.trim(e.val()) == "") || (e.val() == null) || (typeof e.val() === 'undefined')){
                e.focus();
                validation = false;
                return false;
            }else{
                validation = true;
            }
        });

        if(validation == true){
            var code = $('input[name=email2]').val();
            $.ajax({
                type: 'post',
                url: '<?= base_url('home/forgot_password'); ?>',
                data: {'email2': code},
                success: function(msg){
                    console.log(msg);
                    var response = JSON.parse(msg);

                    if(response.error == true){
                        $('#error-msg_email').html(response.msg);
                        $('#error-msg_email').removeClass('alert-success text-success');
                        $('#error-msg_email').addClass('alert-danger text-danger');
                         // $('#error-msg_email_success')(hide);
                    }
                    else{
                        $('#error-msg_email').html(response.msg);
                        $('#error-msg_email').removeClass('alert-danger text-danger');
                        $('#error-msg_email').addClass('alert-success text-success');
                        // $('#error-msg_email').html(response.msg)(hide);
                    }

                    // if(response.msg == 'success'){        
                    // alert('scsjcnsjndc');    
                    //         // window.location.replace('<?= base_url('customer/dashboard/  '); ?>' + response.user_id);             
                    // }
                    //  else{
                    //         // window.location.replace('<?= base_url('home/login_user/'); ?>' + response.user_id);
                    //     }

                }
            });
        }
    });

    </script>