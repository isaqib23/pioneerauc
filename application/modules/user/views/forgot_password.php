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
   <?php if($this->session->flashdata('msg')){ ?>
    <div class="alert">
      <div class="alert alert-success alert-dismissible fade in txt-center" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
        </button>
        <?php  echo $this->session->flashdata('msg');   ?>
      </div>
    </div>
  <?php }?>

  <div>

    <div class="login_wrapper">
      <div class="animate form login_form">
        <section class="login_content">
             <form method="post" action="<?php echo base_url(); ?>user/forgot_password" enctype="multipart/form-data" class="form-horizontal form-label-left">
              <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
            <h1>Forgot Password</h1>            
             <div class="item form-group">
              <div>
              	<input name="email" id="email" type="email" class="form-control" placeholder="Email"  required=""/>
              </div>
            </div>
            	<button type="submit" class="btn btn-default submit">Reset Passwordd</button>
                <div class="separator">
              <p class="change_link">Already a member?
  		<a href="<?php echo base_url(); ?>" class="to_register">Log In </a>
              </p>
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
 </div>
</div>
</body>
</html>
