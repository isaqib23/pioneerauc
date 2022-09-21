<!DOCTYPE html>
<html lang="en">
<head>
  <link href="<?php echo base_url();?>uploads/favicon.ico" rel="shortcut icon" type="image/x-icon" />
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <!-- Meta, title, CSS, favicons, etc. -->
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title><?php echo $page_title;?>|Login</title>

  <!-- Bootstrap -->
  <link href="<?php echo base_url();?>assets_admin/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link href="<?php echo base_url();?>assets_admin/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <!-- NProgress -->
  <link href="<?php echo base_url();?>assets_admin/vendors/nprogress/nprogress.css" rel="stylesheet">
  <!-- Animate.css -->
  <link href="<?php echo base_url();?>assets_admin/vendors/animate.css/animate.min.css" rel="stylesheet">

  <!-- Custom Theme Style -->
  <!-- <link href="<?php //echo base_url();?>assets_admin/css/custom.min.css" rel="stylesheet"> -->
  <link href="<?php echo base_url();?>assets_admin/css/custom.css" rel="stylesheet">
  <!-- jQuery -->
  <script src="<?php echo base_url();?>assets_admin/vendors/jquery/dist/jquery.min.js"></script>

  <!-- Bootstrap -->
  <script src="<?php echo base_url();?>assets_admin/vendors/bootstrap/dist/js/bootstrap.min.js"></script>


  <!-- FastClick -->
  <script src="<?php echo base_url();?>assets_admin/vendors/fastclick/lib/fastclick.js"></script>
  <!-- NProgress -->
  <script src="<?php echo base_url();?>assets_admin/vendors/nprogress/nprogress.js"></script>

  <!-- validator -->
  <script src="<?php echo base_url();?>assets_admin/vendors/validator/validator.js"></script>

  <!-- Custom Theme Scripts -->
  <script src="<?php echo base_url();?>assets_admin/js/custom.js"></script>
  
  <style type="text/css">
  .login{
    background: #efeeee;
  }
  </style>
  <!--about us-->
<script type="text/javascript" language="JavaScript">
  // Callback to get the button working.
  function enableBtn1(){
    document.getElementById("button1").disabled = false;
  }

  // Call to rendor the captcha
  var recaptcha1;
  var recaptcha2;
  var myCallBack = function() {
    //Render the recaptcha1 on the element with ID "recaptcha1"
    recaptcha1 = grecaptcha.render('recaptcha1', {
      'sitekey' : '<?= $this->config->item('admin_captcha_site_key');?>', // production
      'theme' : 'light', // can also be dark
      'callback' : 'enableBtn1' // function to call when successful verification for button 1
    });    
  };
  </script>
</head>

<body class="login">
  <?php if($this->session->flashdata('login_error')){ ?>
    <div class="alert">
      <div class="alert alert-error alert-dismissible fade in txt-center" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span> </button>
        <?php  echo $this->session->flashdata('login_error'); ?>
      </div>
    </div>
  <?php }?>
  <?php if($this->session->flashdata('login_success_message')){ ?>
    <div class="alert">
      <div class="alert alert-success alert-dismissible fade in txt-center" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
        </button>
        <?php  echo $this->session->flashdata('login_success_message');   ?>
      </div>
    </div>
  <?php }?>

  <div>

    <a class="hiddenanchor" id="signup"></a>
    <a class="hiddenanchor" id="signin"></a>
    <div class="login_wrapper">
      <div class="animate form login_form">
        <section class="login_content">
          <form name="login" id="login" method="post" action="<?php echo site_url('user/login');?>" novalidate>
            <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
            <h2>
               <img width="80%" src="<?php echo base_url();?>assets_user/new/images/logo/logo_header_english.svg">
            </h2>            
            <div class="item form-group item-one">
              <div><input name="username" id="username" type="email" class="form-control" placeholder="Email"  required="required"/></div>
            </div>
            <div  class="item form-group item-one">
              <input name="password" id="password" type="password" class="form-control" placeholder="Password"  required="required" />
            </div>

            <div class="g-recaptcha" id="recaptcha1" data-sitekey="6LeYEc4UAAAAAJo9AVB50pm54Fu1Juw_FOUDDcup"></div>

            <button type="submit" id="button1" class="btn btn-default submit">Log in</button>

            <div class="separator">
                <a href="<?php echo base_url('user/forgot'); ?>">Forgot Password</a>
              <div class="clearfix"></div>
              <br />
              <div>
               <p>© 2020 All Rights Reserved <?php echo $site_title;?></p>
             </div>
           </div>
         </form>
       </section>
     </div>
 
   <script type="text/javascript">
  jQuery(document).ready(function () {
    // disable the button until successfull google captcha
      document.getElementById("button1").disabled = true; 
  });
</script>
<script src="https://www.google.com/recaptcha/api.js?onload=myCallBack&render=explicit"  async defer></script>


 </div>
</div>
</body>
</html>
