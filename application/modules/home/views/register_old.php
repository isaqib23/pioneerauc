<link rel="stylesheet" href="<?= base_url(); ?>assets_user/tools/PhoneMask/css/intlTelInput.min.css">
<!-- Phone Mask -->
<script src="<?= base_url(); ?>assets_user/tools/PhoneMask/js/intlTelInput-jquery.min.js"></script>
<script src="<?= base_url(); ?>assets_user/tools/PhoneMask/js/utils.js"></script>

<script>
  // Callback to get the button working.
  function enableBtn1(){
    document.getElementById("register-verify").disabled = false;

              // var v = document.getElementById("register-verify");
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
      'callback' : 'enableBtn1' // function to call when successful verification for button 1
    });
    
  };  
  function recaptchaExpired(){
    document.getElementById("register-verify").disabled = true;
  }  
  </script>


<div class="main gray-bg register-page login-page">
  <div class="container">
    <!-- <h1 class="section-title">REGISTER</h1> -->
    <div class="row">    
      <div class="col-md-8">
        <div class="detail-tabs">
          <ul class="nav nav-tabs" id="myTab" role="tablist">
              <li class="nav-item">
                  <a class="nav-link" href="<?php echo base_url('user-login');?>"><?=$this->lang->line('login_cap');?></a>
              </li>
              <li class="nav-item">
                  <a class="nav-link active" href="<?php echo base_url('user-register');?>"><?=$this->lang->line('register_cap');?></a>
              </li>
          </ul>
          <form class="customform" method="post" name="myForm">
            <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
            <div class="row">
              <div class="col-sm-6">
                <label><?=$this->lang->line('f_name');?> *</label>
                <input type="email" class="form-control" name="fname">
                <span class="valid-error text-danger fname-error"></span>
              </div>
              <div class="col-sm-6">
                <label><?=$this->lang->line('l_name');?> </label>
                <input type="passowrd" class="form-control" name="lname">
              </div>
              <div class="col-sm-6">
                <label><?=$this->lang->line('reg_type');?></label>
                <select name="type" class="selectpicker" title="<?=$this->lang->line('indivisual');?>">
                  <option value="individuals" selected="individuals"><?=$this->lang->line('indivisual');?></option>
                  <option value="organization"><?=$this->lang->line('organization');?></option>
                </select>
                <span class="valid-error text-danger typer-error"></span>
              </div>
              <div class="col-sm-6">
                <label><?=$this->lang->line('mobile');?> *</label>
                <input type="tel"
                  id="user-phone" 
                  name="phone"
                  maxlength="11"
                  oninput="this.value=this.value.replace(/[^0-9]/g,'');" class="form-control">
                  <span class="valid-error text-danger phone-error"></span>
              </div>
              <div class="col-sm-6">
                <label><?=$this->lang->line('email');?> *</label>
                <input type="email"  name="email" id="email" class="form-control">
                <span class="valid-error text-danger email-error"></span>
              </div>
              <div class="col-sm-6">
                <label><?=$this->lang->line('password');?> *</label>
                <input type="password" class="form-control" name="password">
                <span class="valid-error text-danger password-error"></span>
              </div>
              <div class="col-sm-6">
                <label><?=$this->lang->line('how_did');?></label>
                <select class="selectpicker" title="<?=$this->lang->line('indivisual');?>" name="social">
                  <option value="Individual"><?=$this->lang->line('indivisual');?></option>
                  <option value="Website"><?=$this->lang->line('web');?></option>
                  <option value="Social Media"><?=$this->lang->line('social');?></option>
                </select>
              </div>
              <div class="col-sm-6">
                <label><?=$this->lang->line('pref');?></label>
                <select class="selectpicker" title="<?=$this->lang->line('eng');?>" name="prefered_language">
                  <option value="English"><?=$this->lang->line('eng');?></option>
                  <option value="Arabic"><?=$this->lang->line('arab');?></option>
                </select>
              </div>
              <div class="col-sm-12">
                <div class="check-item">
                  <label>
                    <input type="checkbox" name="" id="agree">
                    <span><?=$this->lang->line('read');?> <a href="<?= base_url('home/policy');?>"><?=$this->lang->line('privacy');?>.</a></span>
                    <br><br>
                    <div id="agree-error" class="text-danger"></div>
                  </label>
                </div>
                <div class="col-md-12">
                  <span class=" text-success" id="error-msg" ></span>
                </div>
                <div style="width: 302px;" id="recaptcha1" class="g-recaptcha" data-sitekey="<?= $this->config->item('captcha_key');?>" data-expired-callback="recaptchaExpired"></div><br>

                <div class="button-row">
                  <button type="button" id="register-verify" class="btn btn-default"><?=$this->lang->line('register');?></button>
                  <button type="button" id="loading" class="btn btn-default"><div id="loading"> <img src="<?= ASSETS_IMAGES; ?>load.gif"></div></button>
                  <!-- <div id="loading"> <img src="<?= ASSETS_IMAGES; ?>load.gif"></div> -->
                </div>
              </div>
            </div>
          </form>  
        </div>
      </div>
      <div class="col-md-4">
          <div class="register-info">
              <div class="desc">
                  <h4 class="heading">
                      <?=$this->lang->line('why');?>
                  </h4>
                  <ul class="steps">
                      <li><a href="#"><?=$this->lang->line('access');?></a></li>
                      <li><a href="#"><?=$this->lang->line('access_search');?></a></li>
                      <li><a href="#"><?=$this->lang->line('access_search_bid');?></a></li>
                  </ul>
                  <ul class="appstore-icon">
                      <li><a href="#"><img src="<?= NEW_ASSETS_IMAGES; ?>play-store_img.png"></a></li>
                      <li><a href="#"><img src="<?= NEW_ASSETS_IMAGES; ?>app-store_img.png"></a></li>
                  </ul>
              </div>
          </div>
      </div>
    </div>    
  </div>
</div>  
<script>
  $(document).ready(function(){
    $("#user-phone").intlTelInput({
      allowDropdown: true,
      autoPlaceholder: "polite",
      placeholderNumberType: "MOBILE",
      formatOnDisplay: true,
      separateDialCode: true,
      // nationalMode: false,
      // autoHideDialCode: true,
      hiddenInput: "mobile",
      preferredCountries: [ "ae", "sa" ]
    });
        // $("#user-phone").intlTelInput("setNumber", "<?php //echo isset($user['mobile']) ? $user['mobile'] : ''; ?>");
  });

  // $('#show-register-password').on('click', function(event){
  //   event.preventDefault();
  //   ePass = $(this).siblings('input[name=password]');
  //   if(ePass.attr('type') == 'password'){
  //     ePass.attr({type:"text"});
  //   }else{
  //     ePass.attr({type:"password"});
  //   }
  // });

  $('#register-verify').on('click', function(event){
    event.preventDefault();

    $('#agree-error').html('');
    if( ! $('#agree').is(':checked')){
      $('#agree-error').html("<?= $this->lang->line('please_indicate_you_accept_policy'); ?>");
      return false;
    }

    var validation = false;
    var language = '<?= $language; ?>';
    selectedInputs = ['fname', 'phone', 'email', 'password'];
    validation = validateFields(selectedInputs, language);


    if (validation == true) {
      $('input[name=mobile]').val($("#user-phone").intlTelInput('getNumber'));
      var form = $(this).closest('form').serializeArray();
      //console.log(form);
      $.ajax({
        type: 'post',
        url: '<?= base_url('home/register_user'); ?>',
        data: form,
        beforeSend: function(){
          $("#loading").show();
          $("#register-verify").hide();
          // $("#register-verify").attr('disabled',true);
        },
        complete: function(){
          $("#loading").hide();
        },
        success: function(msg){
          var response = JSON.parse(msg);
          console.log(response);
          
          if(response.error == true){
            $('#error-msg').html('<div class="alert alert-danger alert-dismissible" id="valid-msgss"><button type="button" class="close" data-dismiss="alert" style="top: -14px;">&times;</button><strong><?= $this->lang->line("error_"); ?> </strong>'+ response.msg+' </div>');
          }

          if(response.msg == 'success'){
            $('#exampleModal').modal();
            $('#nmbr').html(response.number);

            // console.info($('input[name=mobile]').val());
            console.log(response.codeGenerated);
          }
          $("#register-verify").show();
          // $("#register-verify").attr('disabled',false);
        } 
      });
    }
  });
</script>

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
            document.getElementById("register-verify").disabled = true;
            $("#loading").hide();
        });
      </script>
      <script src="https://www.google.com/recaptcha/api.js?onload=myCallBack&render=explicit&hl=<?= $ln; ?>" async defer></script>
        


        <!-- /////////////////////  email duplication check  /////////////////////// -->
        <!-- <script src="jquery-1.8.0.min.js"></script> -->
        <script>
        $(document).ready(function(){
            // $('#email').keyup(check_username); //use keyup,blur, or change
        });
        function check_username(){
            var username = $('#email').val();
            jQuery.ajax({
                    type: 'POST',
                    url: '<?= base_url('home/email_check'); ?>',
                    data: 'email='+ username,
                    cache: false,
                    success: function(response){
                        if(response.msg == 'success'){
                          
                        }
                        else {
                            $('#error-msg').html('<div class="alert alert-danger alert-dismissible" id="valid-msgs"><button type="button" class="close" data-dismiss="alert">&times;</button><strong><?= $this->lang->line("email_already_registered"); ?></strong> </div>');
                             //do perform other actions like displaying error messages etc.,
                        }
                    }
                });
        }

        function varify(e){
          var mobile = $(e).data('mobile');
          $('#exampleModal').modal();
          $('#nmbr').html(mobile);
          // alert(mobile);
            
        }
        </script>