 <footer>
    <div class="container">
        <div class="row cols-5">
            <div class="col-md-3" style="flex: 0 0 19%; max-width: 19%;">
                <h5><?= $this->lang->line('auction_footer_link');?></h5>
                <ul>
                    <?php $category = $this->db->get('item_category')->result_array(); ?>
                        <?php foreach ($category as $key => $value) { 
                                $title= json_decode($value['title']);
                                ?>
                            <li><a href="<?= base_url('visitor/gallery/').$value['id']; ?>" ><?= $title->$language; ?></a></li>
                            <?php } ?>
                </ul>
            </div>
            <div class="col-md-3" style="flex: 0 0 17%; max-width: 17%;">
                <h5><?= $this->lang->line('about_us');?></h5>
                <ul>
                    <li>
                        <a href="<?= base_url('about-us')?>"><?= $this->lang->line('about_us');?></a>
                    </li>
                     <!-- <li><a href="<?php echo base_url('visitor/about_us')?>">Overview</a></li> -->
                        <li><a href="<?= base_url('visitor/qualityPolicy');?>"><?= $this->lang->line('quality_policy_footer');?></a></li>
                        <!-- <li><a href="<?php echo base_url('visitor/about_us#hist')?>">Our History</a></li> -->
                        <!-- <li><a href="<?= base_url('visitor/our_team')?>">Our Team</a></li> -->
                        <li><a href="<?= base_url('faqs')?>"><?= $this->lang->line('faq_footer');?></a></li>
                        <li><a href="#"><?= $this->lang->line('our_partners_footer');?></a></li>
                </ul>
            </div>
            <div class="col-md-3" style="flex: 0 0 19%; max-width: 19%;">
                <h5><?= $this->lang->line('quick_link');?></h5>
                <ul>
                    <li><a href="<?= base_url('visitor/gallery')?>"><?= $this->lang->line('media');?></a></li>
                    <li><a href="<?= base_url('home/policy')?>"><?= $this->lang->line('privacy');?></a></li>
                    <li><a href="<?= base_url('terms-conditions')?>"><?= $this->lang->line('terms');?></a></li>
                    <li><a href="<?= base_url('contact-us')?>"><?= $this->lang->line('contact_us');?></a></li>
                    <li><a href="<?= base_url('visitor/gallery')?>"><?= $this->lang->line('gallery');?></a></li>
                </ul>
            </div>

            <?php 
                $data=$this->db->get('contact_us')->row_array();

            ?>
            <div class="col-md-3"  style="flex: 0 0 22%; max-width: 22%;">
                <h5><?= $this->lang->line('address_footer');?></h5>
                <ul class="contact-links">
                    <li>
                        <a href="#">
                            <i class="phone-icon"></i>
                            <!-- <img src="<?= NEW_ASSETS_IMAGES;?>phone-icon.png" alt=""> -->
                            <?php echo $data['mobile'];?>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="message-icon"></i>
                            <!-- <img src="<?= NEW_ASSETS_IMAGES;?>message-icon.png" alt=""> -->
                            <?php echo $data['email'];?>
                        </a>
                    </li>
                    <li>
                        <!-- <a href="#"> -->
                            <i class="address-icon"></i>
                            <!-- <img src="<?= NEW_ASSETS_IMAGES;?>address-icon.png" alt=""> -->
                            <?= $this->lang->line('business_hr');?><?php echo $data['business_hr'];?>
                        <!-- </`a> -->
                    </li>
                    <li>
                        <!-- <a href="#"> -->
                            <i class="address-icon"></i>
                            <!-- <img src="<?= NEW_ASSETS_IMAGES;?>address-icon.png" alt=""> -->
                            <?php echo $data['working_hr'];?>
                        <!-- </a> -->
                    </li>
                    <?php $address = json_decode($data['address']); ?>
                    <li>
                        <!-- <a href="#"> -->
                            <?php if($data['address']){echo $address->$language;}?>
                        <!-- </a> -->
                    </li>
                </ul>
            </div>
            <div class="col-md-3" style="flex: 0 0 23%; max-width: 23%;">
                <div class="twitter-box">
                    <div class="tooltip-box">
                        <p>@Layerdrops <span><?= $this->lang->line('twitter');?></span><a href="#">http://yhdj58.tp8/JK</a></p>
                    </div>
                    <p><i class="fa fa-twitter"></i> Elix&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;<span>June 23, 2020</span></p>
                </div>
            </div>
        </div>
    </div>                     
</footer>
<?php $social = $this->db->get('social_links')->row_array();?>
    <div class="copyright">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p><?= $this->lang->line('copyright');?> &copy; 2010-2020 <?= $this->lang->line('site_title');?> <?= $this->lang->line('rights');?>.</p>
                </div>
                <div class="col-md-6">
                    <ul class="social-icons">
                        <li>
                            <a  href="<?php echo $social['twitter']?>"target="_blank">
                                <i class="fa fa-instagram"></i>
                            </a>
                        </li>
                        <li>
                            <a  href="<?php echo $social['facebook']?>"target="_blank">
                                <i class="fa fa-facebook"></i>
                            </a>
                        </li>
                        <li>
                            <a  href="<?php echo $social['linked_in']?>"target="_blank">
                                <i class="fa fa-linkedin"></i>
                            </a>
                        </li> 
                        <li>
                            <a href="<?php echo $social['google_plus']?>"target="_blank">
                                <i class="fa fa-youtube"></i>
                            </a>
                        </li> 
                    </ul>
                </div>
            </div>
        </div>
    </div> 


    
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
              
                    <div class="modal-body verification" id="resend" style="text-align: center;">
                        <h3 id="success_msg"></h3>     
                        <!-- <h3 style="color:green;" id="successs"></h3> -->
                        <h4 id="verification_code" class="heading"><?= $this->lang->line('enter_varification_code');?></h4>
                        <p id="one_time_password"><?= $this->lang->line('otp');?> <span id="nmbr"></span></p>
                            <input id="input" type="code" class="form-control" placeholder="Code" name="code" style="width: 80% !important; margin-left: 47px;" maxlength="4"> 
                            <span id="valid-error" class="valid-error text-danger code-error"></span>
                            <span id="agree-error" class="text-danger"></span>
                            <br>
                            <div id="button_di" class="button-row">
                                <!-- <h3 style="color:green;" id="success_msgss"></h3> -->
                               <a href="javascript:void(0)" id="confirm-verification"   class="btn btn-default"><?= $this->lang->line('submit');?></a>

                            </div>
                            <br>
                            <div class="note" id="resend_c">
                                <p><?= $this->lang->line('otp_note');?>, <a href="javascript:void(0)" onclick="resendCode()"><?= $this->lang->line('request_new_code');?></a id=""></p>
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
                <div class="modal-body" id="rresend" style="text-align: center;">
                    <h3 style="color:green;" id="rsuccess_msg"></h3>     
                    <h3 style="color:green;" id="rsuccess"></h3>
                    <h4 id="rverification_code" class="heading"><?= $this->lang->line('enter_varification_code');?></h4>
                    <p id="rone_time_password"><?= $this->lang->line('otp');?></p>
                        <input id="rinput" type="code" class="form-control" placeholder="Code" name="codee" style="width: 80% !important; margin-left: 47px;" maxlength="4"> 
                        <span class="valid-error text-danger code-error"></span>
                        <span id="ragree-error" class="text-danger"></span>
                        <br>
                        <div id="rbutton_di" class="button-row">
                            <!-- <h3 style="color:green;" id="success_msgss"></h3> -->
                           <a href="javascript:void(0)" id="confirm-verification2"   class="btn btn-default"><?= $this->lang->line('submit');?></a>

                        </div>
                        <br>
                        <div class="note" id="rresend_c">
                            <p><?= $this->lang->line('otp_note');?>, <a href="javascript:void(0)" onclick="resendCode()"><?= $this->lang->line('request_new_code');?></a id="rsnd"></p>
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
                        <div class="modal-body forgot-page" style="text-align: center;">
                            <h4 class="heading"><?= $this->lang->line('enter_email');?></h4>
                            <p><?= $this->lang->line('reset_link');?> </p>
                            <div class="customform">
                                <input type="code" dir="ltr" class="form-control" placeholder="<?= $this->lang->line('email');?>" name="email2" style="width: 80% !important; margin-left: 47px;">
                            </div>
                            <br>
                            <div class="button-row">
                                <div class="button-row" id="error-msg_email" ></div>
                                <!-- <div class="button-row text-success" id="error-msg_email_success" ></div> -->
                               <a href="javascript:void(0)" id="confirm-email"   class="btn btn-default sm"><?= $this->lang->line('submit');?></a>
                            </div>
                            <!-- <div class="note">
                                <p>Note: If you did not receive a link, <a href="javascript:void(0)">Request new link</a></p>
                            </div> -->
                        </div>
                    </div>      
                </div>
            </div>
        <!-- </div> -->
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
                data: {'code': code,'<?= $this->security->get_csrf_token_name();?>':"<?=$this->security->get_csrf_hash();?>"},
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
        var phoneNumber = $('#nmbr').html();
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
                data: {'code': code ,'<?= $this->security->get_csrf_token_name();?>':"<?=$this->security->get_csrf_hash();?>"},
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
                data: {'email2': code,'<?= $this->security->get_csrf_token_name();?>':"<?=$this->security->get_csrf_hash();?>"},
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