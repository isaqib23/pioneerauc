<!-- PNotify -->
<link href="<?php echo base_url(); ?>assets_admin/vendors/pnotify/dist/pnotify.css" rel="stylesheet">

<script type="text/javascript" language="JavaScript">
  // Callback to get the button working.
  function enableBtn1(){
    document.getElementById("login_verify").disabled = false;

              // var v = document.getElementById("login-verify");
              // v.removeClass('disabled');
  }
  
  // Call to rendor the captcha
  var recaptcha1;
  var myCallBack = function() {
    //Render the recaptcha1 on the element with ID "recaptcha1"
    recaptcha1 = grecaptcha.render('recaptcha1', {
      //'sitekey' : '6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI', //test key
      'sitekey' : '<?= $this->config->item('captcha_key');?>', // production
      // 'sitekey' : '6Lclis0UAAAAAChRbWV1iTxj_yjx_WHZWS0tPt4w', // production
      'theme' : 'light', // can also be dark
      'theme' : 'light', // can also be dark
      'callback' : 'enableBtn1' // function to call when successful verification for button 1
    });
  };  
  function recaptchaExpired(){
    document.getElementById("login_verify").disabled = true;
  }  
</script>

<div class="main gray-bg login-page">
        <div class="container">
            <!-- <h1 class="section-title"> LOGIN</h1> -->
            <div class="row">
                <div class="col-md-8">
                    <div class="detail-tabs">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" href="<?php echo base_url('user-login');?>"><?= $this->lang->line('login');?></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo base_url('user-register');?>"><?= $this->lang->line('register');?></a>
                            </li>
                        </ul>            
                    <!-- <div class="content-box"> -->
                        <form class="customform">
                          <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
                            <div class="row">
                                <div class="col-sm-6">
                                    <label><?= $this->lang->line('email');?> *</label>
                                    <input type="email" class="form-control" name="email">
                                    <span class="valid-error text-danger email-error"></span>
                                </div>
                                <div class="col-sm-6">
                                    <label><?= $this->lang->line('password');?> *</label>
                                    <input type="password" class="form-control" name="password1">
                                    <span class="valid-error text-danger password1-error"></span>
                                </div>

                                <div class="col-sm-12">
                                  <a href="javascript:void(0)"  id="reset">
                                      <h6 class="link"><?= $this->lang->line('f_password');?></h6>
                                  </a>  
                                  <!-- <div class="col-md-6"> -->
                                    <span class=" text-success" id="error-msgs" ></span>
                                  <!-- </div> -->
                                  <!-- <div class="col-md-6"> -->
                                    <span class=" text-success" id="valid-msgs" ></span>
                                  <!-- </div> -->
                                  <div class="button-row">
                                    <div style="width: 302px;" id="recaptcha1" class="g-recaptcha" data-sitekey="6LeYEc4UAAAAAJo9AVB50pm54Fu1Juw_FOUDDcup" data-expired-callback="recaptchaExpired"></div>
                                    <br>
                                      <!-- ozbutt data-sitekey="6LcmJnYUAAAAAC06jwhvoA1u4nAp145487-IWegT" -->
                                      <!-- < a href="javascript:void(0)" id="login_verify" class="btn btn-default">LOGIN</a> -->
                                    <button type="submit" id="login_verify" class="btn btn-default"><?= $this->lang->line('login');?></button>
                                  </div>
                                </div>
                            </div>
                        </form>
                      </div>  
                    <!-- </div> -->
                </div>
                <div class="col-md-4">
                    <div class="register-info">
                        <div class="desc">
                            <h4 class="heading">
                                <?= $this->lang->line('why');?>
                            </h4>
                            <ul class="steps">
                                <li><a href="#"><?= $this->lang->line('access');?></a></li>
                                <li><a href="#"><?= $this->lang->line('access_search');?></a></li>
                                <li><a href="#"><?= $this->lang->line('access_search_bid');?></a></li>
                            </ul>
                            <ul class="appstore-icon">
                                <li><a href="#"><img src="<?= NEW_ASSETS_IMAGES; ?>/play-store_img.png"></a></li>
                                <li><a href="#"><img src="<?= NEW_ASSETS_IMAGES; ?>/app-store_img.png"></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
  </div>

<!-- Notify JS Files -->
<script src="<?php echo base_url(); ?>assets_admin/vendors/pnotify/dist/pnotify.js"></script>

<script type="text/javascript">
  $(document).ready(function(){
    if($("body #telephone").length) {
      var input = document.querySelector("#telephone");
      window.intlTelInput(input);
    }
    if($("body #telephone2").length) {
      var input = document.querySelector("#telephone2");
      window.intlTelInput(input);
    }
  });
</script>


  <script type="text/javascript">

    <?php if($this->session->flashdata('success')){ ?>
      var message = "<?php echo $this->session->flashdata('success'); ?>";
        new PNotify({
            text: message,
            type: 'success',
            addclass: 'custom-success',
            title: "<?= $this->lang->line('success_'); ?>",
            styling: 'bootstrap3'
        });
     <?php } ?>

    <?php if($this->session->flashdata('error')){ ?>
      var message = "<?php echo $this->session->flashdata('error'); ?>";
      new PNotify({
              text: message,
              type: 'error',
              title: "<?= $this->lang->line('error'); ?>",
              styling: 'bootstrap3'
          });   
    <?php } ?>


    $('#login_verify').on('click', function(event){
      event.preventDefault();

      var validation = false;
      var language = '<?= $language; ?>';
      selectedInputs = ['email','password1'];
      validation = validateFields(selectedInputs, language); 

      if (validation == true) {
        var form = $(this).closest('form').serializeArray();

        //console.log(form);
        $.ajax({
          type: 'post',
          url: '<?= base_url('home/login_process'); ?>',
          data: form,
          success: function(msg){
            console.log(msg);
            var response = JSON.parse(msg);
            
            if(response.error == true){
              $('#error-msgs').html('<div class="alert alert-danger alert-dismissible" id="valid-msgs"><button type="button" class="close" data-dismiss="alert">&times;</button>'+response.msg+'</div>');
            }
            if(response.msg == 'success'){

              var rurl = '<?= isset($_GET['rurl']) ? $_GET['rurl'] : ''; ?>';
              if (rurl == '') {
                window.location.replace('<?= base_url('deposit'); ?>');
              }else{
                window.location.replace(rurl);
              }
            }
             // else {
             //        window.location.replace('<?= base_url('home/login/'); ?>');
             //    }
          }
        });
      }
    });
  </script>



        <!-- ////////////////////////////////////// RESET PASSWORD////////////////////// -->
        <script type="text/javascript">
          $('#reset').on('click', function(event){
                    $('#emailmodel').modal();
        });
        </script>

            

      <!-- //////////////////// CAPTCHA ////////////////////////////// -->

      <script type="text/javascript">
        jQuery(document).ready(function ($) {
          // disable the button until successfull google captcha
            document.getElementById("login_verify").disabled = true;
            // var v = document.getElementById("login_verify");
            //   v.addClass('disabled');
            //   var b = document.getElementById("register-verify");
            //   b.addClass('disabled');
        });
      </script>
      <script src="https://www.google.com/recaptcha/api.js?onload=myCallBack&render=explicit&hl=<?= $ln; ?>" async defer></script>
        


        <!-- /////////////////////  email duplication check  /////////////////////// -->
        <!-- <script src="jquery-1.8.0.min.js"></script> -->
        <script>
        $(document).ready(function(){
            // $('#email').keyup(check_username); //use keyup,blur, or change
        });
        // function check_username(){
        //     var username = $('#email').val();
        //     jQuery.ajax({
        //             type: 'POST',
        //             url: '<?= base_url('home/email_check'); ?>',
        //             data: 'email='+ username,
        //             cache: false,
        //             success: function(response){
        //                 if(response.msg == 'success'){
                          
        //                 }
        //                 else {
        //                     $('#error-msg').html('<div class="alert alert-danger alert-dismissible" id="valid-msgs"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Email already registered</strong> </div>');
        //                      //do perform other actions like displaying error messages etc.,
        //                 }
        //             }
        //         });
        // }

        function varify(e){
          var mobile = $(e).data('mobile');
          $('#exampleModal').modal();
          $('#nmbr').html(mobile);
          // alert(mobile);
            
        }
        </script>