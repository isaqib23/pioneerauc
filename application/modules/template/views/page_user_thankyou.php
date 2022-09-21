<!-- <main class="page-wrapper our-team">
  <div class="inner-banner"> 
    <div class="container"> 
      <div class="caption">
          <h1 class="page-title">Thanks for Joining</h1><br> 
          	<h1>Your registration is complete.</h1>
          <p>Now you can login to your account. <a href="<?php echo base_url();?>home/login">Login</a>
        </div>
    </div>
 </div>
</div> -->
<div class="main gray-bg about-us login-page wellcome-page">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="detail-tabs" align="center">
                      <h1 class="section-title">Thanks for join</h1>
                      <h2>Your email verification has been completed Please click here to <a href="<?php echo base_url('user-login');?>">login.</a></h2>
                    </div>
                </div>
                <div class="col-md-4">
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
                                <li><a href="#"><img src="<?= NEW_ASSETS_IMAGES; ?>/play-store_img.png"></a></li>
                                <li><a href="#"><img src="<?= NEW_ASSETS_IMAGES; ?>/app-store_img.png"></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>         
        </div> 
     </div>



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
