<script type="text/javascript" language="JavaScript">
  // Callback to get the button working.
  function enableBtn1(){
    document.getElementById("login_verify").disabled = false;

              // var v = document.getElementById("login-verify");
              // v.removeClass('disabled');
  }
  function enableBtn2(){
    document.getElementById("register-verify").disabled = false;

              // var v = document.getElementById("register-verify");
              // v.removeClass('disabled');
  }
  
  // Call to rendor the captcha
  var recaptcha1;
  var recaptcha2;
  var myCallBack = function() {
    //Render the recaptcha1 on the element with ID "recaptcha1"
    recaptcha1 = grecaptcha.render('recaptcha1', {
      //'sitekey' : '6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI', //test key
      'sitekey' : '<?= $this->config->item('captcha_key');?>', // production
      // 'sitekey' : '6Lclis0UAAAAAChRbWV1iTxj_yjx_WHZWS0tPt4w', // production
      'theme' : 'light', // can also be dark
      'callback' : 'enableBtn1' // function to call when successful verification for button 1
    });
    
    //Render the recaptcha2 on the element with ID "recaptcha2"
    recaptcha2 = grecaptcha.render('recaptcha2', {
      //'sitekey' : '6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI', // test key
      'sitekey' : '<?= $this->config->item('captcha_key');?>', // production
      'theme' : 'light', // can also be dark
      'callback' : 'enableBtn2' // function to call when successful verification for button 2
    });
  };  
  function recaptchaExpired(){
    document.getElementById("login_verify").disabled = true;
    document.getElementById("register-verify").disabled = true;
  }  
  </script>
<main class="page-wrapper register-page"> 
        <div class="container">

            <?php if($this->session->flashdata('success')){ ?>
            <div class="alert">
                <div class="alert alert-domain alert-success alert-dismissible in" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                      </button>
                    <?php echo $this->session->flashdata('success'); ?>
                </div>
            </div>
            <?php }?>
            <?php if($this->session->flashdata('error')){ ?>
            <div class="alert">
                <div class="alert alert-domain alert-danger alert-dismissible in" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                      </button>
                    <?php echo $this->session->flashdata('error'); ?>
                </div>
            </div>
            <?php }?>
          <div class="row col-gap-56">
            <div class="col-lg-7">
              <div class="content-wrapper">
                <div class="tabs">
                  <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link <?= (isset($active_login)) ? $active_login : ''?>" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">LOGIN</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= (isset($active_register)) ? $active_register : ''?>" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">REGISTER</a>
                    </li>
                  </ul>
                  <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade <?= (isset($login_show)) ? $login_show : ''?> <?= (isset($active_login)) ? $active_login : ''?>" id="home" role="tabpanel" aria-labelledby="home-tab">
                      <div class="row">
                        <div class="col-lg-12">
                          <div class="contact-form login-page">
                            <form>
                              <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
                              <div class="row">
                                <div class="col-lg-6">
                                  <div class="form-group">
                                    <label>Email   *</label>  
                                    <input type="text" class="form-control" name="email1">
                                    <span class="valid-error text-danger email1-error"></span>
                                  </div>
                                </div>
                                <div class="col-lg-6">
                                  <div class="form-group">
                                    <label>Password  *</label>  
                                    <input type="password" class="form-control" name="password1">
                                    <span class="valid-error text-danger password1-error"></span>
                                  </div>
                                </div>
                                
                                <div class="col-lg-12">
                                  <a href="javascript:void(0)"  id="reset" class="red-text">Forgot Password ?</a>
                                </div>
                                 <div class="col-md-12">
                                      <span class=" text-success" id="error-msgs" ></span>
                                    </div>
                                    <div class="col-md-12">
                                    <span class=" text-success" id="valid-msgs" ></span>
                                  </div>
                                
                                <div class="col-lg-4">
                                   <div class="button-ro">
                                    <div style="width: 302px;" id="recaptcha1" class="g-recaptcha" data-sitekey="6LeYEc4UAAAAAJo9AVB50pm54Fu1Juw_FOUDDcup" data-expired-callback="recaptchaExpired"></div><br>
                                    <!-- ozbutt data-sitekey="6LcmJnYUAAAAAC06jwhvoA1u4nAp145487-IWegT" -->
                                   <!-- <a href="javascript:void(0)" id="login_verify" class="btn btn-default">LOGIN</a> -->
                                    <button type="submit" id="login_verify" class="btn btn-default">LOGIN</button>
                              </div>


                                </div>
                              </div>
                            </form>
                          </div>  
                        </div>
                      </div>  
                    </div>
                    <div class="tab-pane fade <?= (isset($register_show)) ? $register_show : ''?> <?= (isset($active_register)) ? $active_register : ''?>" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                      <div class="row">
                        <div class="col-lg-12">
                          <div class="contact-form">


                          <form method="post" name="myForm">
                          <div class="row">

                            <div class="col-lg-6">
                              <div class="form-group">
                                <label>First Name  *</label>
                                <input type="text" class="form-control" name="fname">
                                <span class="valid-error text-danger fname-error"></span>
                              </div>
                            </div>

                            <div class="col-lg-6">
                              <div class="form-group">
                                <label>Last name</label>
                                <input type="text" class="form-control" name="lname">
                              </div>
                            </div>
                            <div class="col-lg-6">
                              <div class="form-group">
                                <label>Registration Type </label>
                                <select name="typer" class="selectpicker">
                                  <!-- <option>Registration Type</option> -->
                                  <option value="individuals" selected="individuals">individuals</option>
                                  <option value="organization">organization</option>
                                </select>
                                <span class="valid-error text-danger typer-error"></span>
                                     
                              </div>
                            </div>
                            <div class="col-lg-6">
                              <div class="form-group">
                                <label >Mobile  *</label> 
                                <input type="tel"
                                id="user-phone" 
                                name="phone"
                                maxlength="11"
                                oninput="this.value=this.value.replace(/[^0-9]/g,'');"  class=" form-control">
                                <span class="valid-error text-danger phone-error"></span>
                              </div>
                            </div>
                            <div class="col-lg-6">
                              <div class="form-group">
                                <label>Email   *</label>  
                                <input type="text" name="email" id="email" class="form-control">
                                <span class="valid-error text-danger email-error"></span>
                              </div>
                            </div>
                            <div class="col-lg-6">
                              <div class="form-group">
                                <label>Password  *</label>  
                                <input type="password" class="form-control" name="password">
                                <span class="valid-error text-danger password-error"></span>
                              </div>
                            </div>
                            <div class="col-lg-6">
                              <div class="form-group">
                                <label>How did u hear about us?</label> 
                                <select class="selectpicker" name="social">
                                  <option value="Individual">Individual</option>
                                  <option value="Website">Website</option>
                                  <option value="Social Media">Social Media</option>
                                </select>
                              </div>
                            </div>
                            <div class="col-lg-6">
                              <div class="form-group">
                                <label>Preferred Language</label> 
                                <select class="selectpicker" name="prefered_language">
                                  <option value="English">English</option>
                                  <option value="Arabic">Arabic</option>
                                 <!-- <option>3</option> -->
                                </select>
                              </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                  <div class="checkbox">
                                    <label>
                                      <input type="checkbox" name="" id="agree">
                                    <span>I have read and agree to the <b>Privacy Policy. </b></span>
                                    <span id="agree-error" class="text-danger"></span>
                                    </label>
                                  </div>
                                </div>
                              </div>
                                  
                            <div class="col-lg-12">
                               <div class="col-md-12">
                             <span class=" text-success" id="error-msg" ></span>
                            </div>
                            
                             <div style="width: 302px;" id="recaptcha2" class="g-recaptcha" data-sitekey="<?= $this->config->item('captcha_key');?>" data-expired-callback="recaptchaExpired"></div><br>
                            
                              <div class="button-row">
                                    <!-- <a href="#" id="btnsave"  class="btn btn-default">SUBMIT</a> -->
                                    <!-- data-toggle="modal"  -->
                                    <!-- data-target="#exampleModal" -->
                                    <!-- <button type="SUBMIT" class="btn btn-default">SUBMIT</button> -->
                                    <!-- <a href="javascript:void(0)" id="register-verify" class="btn btn-default">SUBMIT</a> -->
                                    <button type="button" id="register-verify" class="btn btn-default">REGISTER</button>
                                  </div>

                            </div>
                          </div>
                          </form>
                          </div>  
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>  
            </div>
            <div class="col-lg-5">
              <div class="register-info">
                <div class="desc">
                  <h4 class="heading">
                    Why Pioneer auctions
                  </h4>
                  <ul class="steps">
                    <li><a href="#">Access to search and bid of vehicles each day</a></li>
                    <li><a href="#">Access to search and bid</a></li>
                    <li><a href="#">Access to search and bid of vehicles each day</a></li>
                  </ul>
                  <ul class="appstore-icon">
                    <li><a href="#"><img src="<?= ASSETS_IMAGES;?>play-store_img.png"></a></li>
                    <li><a href="#"><img src="<?= ASSETS_IMAGES;?>app-store_img.png"></a></li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>

      </main>
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
      $('#agree-error').html('Please indicate that you accept our privacy policy.');
      return false;
    }

    var validation = false;
    var errorMsg = "This value is required.";
    var errorClass = ".valid-error";
    var e;
    selectedInputs = ['fname', 'email', 'phone', 'password'];

    $.each(selectedInputs, function(index, value){
      e = $('input[name='+value+']');

      if(value == 'email'){
        if( ! isEmail(e.val())){
          e.focus().siblings(errorClass).html('Email is not valid.').show();
          validation = false;
          return false;
        }
      }

      if(($.trim(e.val()) == "") || (e.val() == null) || (typeof e.val() === 'undefined')){
          e.focus();
          $('.'+value+'-error').html(errorMsg).show();
          validation = false;
          return false;
      }else{
          validation = true;
          $('.'+value+'-error').html('').hide();
      }
    });

    if (validation == true) {
      $('input[name=mobile]').val($("#user-phone").intlTelInput('getNumber'));
      var form = $(this).closest('form').serializeArray();
      //console.log(form);
      $.ajax({
        type: 'post',
        url: '<?= base_url('home/register_user'); ?>',
        data: form,
        success: function(msg){
          var response = JSON.parse(msg);
          console.log(response);
          
          if(response.error == true){
            $('#error-msg').html('<div class="alert alert-danger alert-dismissible" id="valid-msgss"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Error! </strong>'+ response.msg+' </div>');
          }

          if(response.msg == 'success'){
          
            $('#exampleModal').modal();
            $('#nmbr').html(response.number);

            // console.info($('input[name=mobile]').val());
            console.log(response.codeGenerated);
          }
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




  <script type="text/javascript">

    $('#login_verify').on('click', function(event){
      event.preventDefault();

      var validation = false;
      var errorMsg = "This value is required.";
      var errorClass = ".valid-error";
      var e;
      selectedInputs = ['email1','password1'];

      $.each(selectedInputs, function(index, value){
        e = $('input[name='+value+']');

        if(value == 'email1'){
          if( ! isEmail(e.val())){
            e.focus().siblings(errorClass).html('Email is not valid.').show();
            validation = false;
            return false;
          }
        }

        if(($.trim(e.val()) == "") || (e.val() == null) || (typeof e.val() === 'undefined')){
            e.focus();
            $('.'+value+'-error').html(errorMsg).show();
            validation = false;
            return false;
        }else{
            validation = true;
            $('.'+value+'-error').html('').hide();
        }
      });

        

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
            document.getElementById("register-verify").disabled = true;
            // var v = document.getElementById("login_verify");
            //   v.addClass('disabled');
            //   var b = document.getElementById("register-verify");
            //   b.addClass('disabled');
        });
      </script>
      <script src="https://www.google.com/recaptcha/api.js?onload=myCallBack&render=explicit" async defer></script>
        


        <!-- /////////////////////  email duplication check  /////////////////////// -->
        <script src="jquery-1.8.0.min.js"></script>
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
                            $('#error-msg').html('<div class="alert alert-danger alert-dismissible" id="valid-msgs"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Email already registered</strong> </div>');
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