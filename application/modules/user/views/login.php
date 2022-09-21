<!DOCTYPE html>
<html lang="en">
<head>
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
            <h1>Login</h1>            
            <div class="item form-group item-one">
              <div><input name="username" id="username" type="email" class="form-control" placeholder="Email"  required="required"/></div>
            </div>
            <div  class="item form-group item-one">
              <input name="password" id="password" type="password" class="form-control" placeholder="Password"  required="required" />
            </div>

            <button type="submit" class="btn btn-default submit">Log in</button>

            <div class="separator">
             <!--  <p class="change_link">New to happied?
                <a href="#signup" class="to_register"> Create Account </a>
              </p>
 -->  <a href="<?php echo base_url('admin/forgot_password'); ?>">Forgot Password</a>
              <div class="clearfix"></div>
              <br />
              <div>
               <img width="80%" src="<?php echo base_url();?>upload/logo.png">
               <p>©2019 All Rights Reserved <?php echo $site_title;?></p>
             </div>
           </div>
         </form>
       </section>
     </div>

     <!-- Signup Form -->


     <div id="register" class="animate form registration_form">
      <section class="login_content">
        <form name="login" id="login2" method="post" action="<?php echo site_url('user/signup');?>" novalidate>
          <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
          <h1>Create Account</h1>       
          <div class="item form-group item-one">
            <input name="fname" id="fname" type="text" class="form-control" placeholder="First name"  required=""/>
          </div>          
          <div class="item form-group item-one">
            <input name="lname" id="lname" type="text" class="form-control" placeholder="Last name"  required=""/>
          </div>        
          <div class="item form-group item-one">
            <input name="phone" id="phone" type="text" class="form-control" placeholder="Phone"  required=""/>
          </div>
          <div class="item form-group item-one">
            <input type="email" class="form-control" name="new_email" placeholder="Email" required="" />
          </div>
          <div class="item form-group item-one">
            <input type="email" class="form-control" name="new_conf_email" placeholder="Confirm Email" required="" />
          </div>
          <div class="item form-group item-one">
            <input type="password" minlength="5"  class="form-control" name="new_password" placeholder="Password" required="" />
          </div>
          <div class="item form-group item-one">
            <input type="password"  minlength="5" class="form-control" name="conf_password" placeholder="Confirm Password" required="" />
          </div>
          <?php if(isset($_GET['refer']) && !empty($_GET['refer'])){ ?>
               <div class="item form-group item-one">
            <input type="text" class="form-control" name="refer" value="<?php echo $_GET['refer']; ?>"  readonly />
          </div>
        <?php } ?>
          <div>
            <!-- <a class="btn btn-default submit" href="index.html">Submit</a> -->
            <button type="submit" class="btn btn-default submit">Sign up</button>
          </div>

          <div class="clearfix"></div>

          <div class="separator">
            <p class="change_link">Already a member ?
              <a href="#signin" class="to_register"> Log in </a>
            </p>

            <div class="clearfix"></div>
            <br />

            <div> 
             <img width="30%" src="<?php echo base_url();?>upload/logo.png">
             <p>©2019 All Rights Reserved <?php echo $site_title;?></p>
           </div>
         </div>
       </form>
     </section>
   </div>


 </div>
</div>
</body>
</html>
