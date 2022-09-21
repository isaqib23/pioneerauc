<!DOCTYPE html>
<html dir="<?= $direction; ?>" class="<?= $direction; ?>">
<head>
  <link href="<?php echo base_url();?>uploads/favicon.ico" rel="shortcut icon" type="image/x-icon" />
  <title><?= isset($page_title) ? $page_title : ''; ?></title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="<?= ASSETS_USER;?>css/bootstrap4.min.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="<?= ASSETS_USER;?>css/bootstrap-select.css">
  <link href="<?php echo base_url();?>assets_admin/sweetalert/dist/sweetalert.css" rel="stylesheet">
  <link rel="stylesheet" href="<?= ASSETS_USER;?>css/iti.css">
  <link rel="stylesheet" href="<?= ASSETS_USER;?>css/TimeCircles.css">
  <link rel="stylesheet" href="<?= ASSETS_USER;?>css/readme.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="<?= ASSETS_USER;?>css/slick.css">
  <link rel="stylesheet" href="<?= ASSETS_USER;?>css/animate.css">
  <link rel="stylesheet" href="<?= ASSETS_USER;?>css/skitter.css">
  <link rel="stylesheet" href="<?= ASSETS_USER;?>css/glapy-fonts.css">
  <link rel="stylesheet" href="<?= ASSETS_USER;?>css/bootstrap-datepicker.css">
  <link rel="stylesheet" href="<?= ASSETS_USER;?>css/bootstrap-timepicker.css">
  <!-- <link rel="stylesheet" href="<?= ASSETS_USER;?>css/style.css"> -->
  <link rel="stylesheet" href="<?= ASSETS_USER;?>screens/style-screen.css">
  <link rel="stylesheet" href="<?= ASSETS_USER;?>css/zubair.css">

  <script src="<?= ASSETS_USER;?>js/jquery.min.js"></script>
    <script src="<?= base_url(); ?>assets_admin/js/formBuilder/jquery-ui.min.js"></script>
  <script src="<?= ASSETS_USER;?>js/jquery.easing.1.3.js"></script>
  <script src="<?= ASSETS_USER;?>js/jquery.skitter.min.js"></script>
  <script src="<?= ASSETS_USER;?>js/popper.min.js"></script>
  <script src="<?= ASSETS_USER;?>js/bootstrap4.min.js"></script>
  <script src="<?= ASSETS_USER;?>js/wow.js"></script>
  <script src="<?= ASSETS_USER;?>js/bootstrap-select.js"></script>
  <!-- <script src="<?= ASSETS_USER;?>js/intlTelInput-jquery.min.js"></script>
  <script src="<?= ASSETS_USER;?>js/utils.js"></script> -->
  <script src="<?= ASSETS_USER;?>js/slick.js"></script>
  <script src="<?= ASSETS_USER;?>js/jquery.raty-fa.js"></script>
  <!-- <script src="assets/js/TimeCircles.js"></script> -->
  <script src="<?= ASSETS_USER;?>js/jquery.syotimer.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
  <script type="text/javascript" src="<?php echo base_url();?>assets_admin/sweetalert/dist/sweetalert.min.js"></script>
  <script type="text/javascript" src="<?php echo base_url();?>assets_admin/js/deletesweetalert.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
  <script src="https://cdn.jsdelivr.net/npm/promise-polyfill"></script>
  <script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
  <script src="https://cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap4.min.js"></script>
  <script src="<?= ASSETS_USER;?>js/bootstrap-datepicker.js"></script>
  <script src="<?= ASSETS_USER;?>js/bootstrap-timepicker.js"></script>
  <script src="<?= ASSETS_USER;?>js/custom.js"></script>
  

    
    <script  src="<?= ASSETS_USER;?>js/ozCustom.js"></script>
    <!-- Phone Mask -->
    <script src="<?= base_url(); ?>assets_user/tools/PhoneMask/js/intlTelInput-jquery.min.js"></script>
    <script src="<?= base_url(); ?>assets_user/tools/PhoneMask/js/utils.js"></script>
    <script src="<?= base_url(); ?>assets_user/js/zee.js"></script>
    <!-- Form Render -->
    <script src="<?= base_url(); ?>assets_admin/js/formBuilder/form-render.min.js"></script>
    
    <script type="text/javascript">

        // email validation function
      function isEmail(email) {
          var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
          return regex.test(email);
      }

      function IsJsonString(str) {
          try {
              JSON.parse(str);
          } catch (e) {
              return false;
          }
          return true;
      }
    </script>
</head> 
<body class="gradient-banner <?= isset($homee_class) ? 'front-page' : ''; ?>">
  <?php 
      $this->load->view('template/header_screens');
   ?>

 <?php echo (($main_content)) ? $main_content : ''; ?>

 <?php 
      $this->load->view('template/footer_screens');  
  ?>  

  <script>
    //load default image for every img tag in the project
    loadDefaultImage('<?= ASSETS_ADMIN."images/product-default.jpg" ?>')
  </script>