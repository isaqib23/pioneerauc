<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="<?php echo base_url();?>uploads/favicon.ico" rel="shortcut icon" type="image/x-icon" />


    <title><?php echo $page_title;?></title>

    <!-- Bootstrap -->
    <link href="<?php echo base_url();?>assets_admin/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="<?php echo base_url();?>assets_admin/vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
   
    <link href="<?php echo base_url();?>assets_admin/vendors/bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="<?php echo base_url();?>assets_admin/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="<?php echo base_url();?>assets_admin/vendors/nprogress/nprogress.css" rel="stylesheet">
    <!-- iCheck -->
    <link href="<?php echo base_url();?>assets_admin/vendors/iCheck/skins/flat/green.css" rel="stylesheet">
    <!-- Datatables -->
    <link href="<?php echo base_url();?>assets_admin/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
     <link rel="stylesheet" href="<?php echo base_url();?>assets_user/tools/PhoneMask/css/intlTelInput.min.css">
    <link href="<?php echo base_url();?>assets_admin/vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url();?>assets_admin/vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url();?>assets_admin/vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url();?>assets_admin/vendors/datatables.net-select/css/select.dataTables.min.css" rel="stylesheet">
    
    
    <!-- Dropzone.js -->
    <link href="<?php echo base_url();?>assets_admin/vendors/dropzone/dist/min/dropzone.min.css" rel="stylesheet">
    
    
    <link href="<?php echo base_url();?>assets_admin/vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css" rel="stylesheet">

<!--	<link rel="stylesheet" type="text/css" href="<?php // echo base_url(); ?>assets_admin/vendors/bootstrap-daterangepicker/daterangepicker.css"/>-->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets_admin/vendors/bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.css"/>
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets_admin/vendors/select2/dist/css/select2.css"/>
    <!-- Custom Theme Style -->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets_admin/css/custom.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets_admin/css/oz_style_custome.css" />
    <link href="<?php echo base_url();?>assets_admin/sweetalert/dist/sweetalert.css" rel="stylesheet">
    <link href="<?php echo base_url();?>assets_admin/vendors/mjolnic-bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css" rel="stylesheet">
    <!-- <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/css/bootstrap.min.css"> -->


 <!-- jQuery -->
    <script src="<?php echo base_url();?>assets_admin/vendors/jquery/dist/jquery.min.js"></script>
    
    <!-- <script src="<?php echo base_url();?>assets_user/tools/PhoneMask/js/jquery-latest.min.js"></script> -->
    <script src="<?php echo base_url();?>assets_user/tools/PhoneMask/js/intlTelInput-jquery.min.js"></script>
    <script src="<?php echo base_url();?>assets_user/tools/PhoneMask/js/utils.js"></script>
    <!-- Bootstrap -->
    <script src="<?php echo base_url();?>assets_admin/vendors/bootstrap/dist/js/bootstrap.min.js"></script>
     <!-- FastClick -->
    <script src="<?php echo base_url();?>assets_admin/vendors/fastclick/lib/fastclick.js"></script>
	<!-- NProgress -->
    <script src="<?php echo base_url();?>assets_admin/vendors/nprogress/nprogress.js"></script>
    
    <!-- iCheck -->
	<!-- NProgress -->

    

	
    <script src="<?php echo base_url();?>assets_admin/vendors/iCheck/icheck.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets_admin/vendors/select2/dist/js/select2.js"></script>
    <!-- Datatables -->
    <script src="<?php echo base_url();?>assets_admin/vendors/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="<?php echo base_url();?>assets_admin/vendors/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
    <script src="<?php echo base_url();?>assets_admin/vendors/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
    <script src="<?php echo base_url();?>assets_admin/vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js"></script>
    <script src="<?php echo base_url();?>assets_admin/vendors/datatables.net-buttons/js/buttons.flash.min.js"></script>
    <script src="<?php echo base_url();?>assets_admin/vendors/jszip/dist/jszip.min.js"></script>
    <script src="<?php echo base_url();?>assets_admin/vendors/datatables.net-buttons/js/buttons.print.min.js"></script>
    <script src="<?php echo base_url();?>assets_admin/vendors/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js"></script>
    <script src="<?php echo base_url();?>assets_admin/vendors/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
    <script src="<?php echo base_url();?>assets_admin/vendors/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
    <script src="<?php echo base_url();?>assets_admin/vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js"></script>
    <script src="<?php echo base_url();?>assets_admin/vendors/datatables.net-scroller/js/dataTables.scroller.min.js"></script>
    <script src="<?php echo base_url();?>assets_admin/vendors/pdfmake/build/pdfmake.min.js"></script>
    <script src="<?php echo base_url();?>assets_admin/vendors/pdfmake/build/vfs_fonts.js"></script>
    <script src="<?php echo base_url();?>assets_admin/vendors/datatables.net-buttons/js/buttons.html5.min.js"></script>
    <script src="<?php echo base_url();?>assets_admin/vendors/datatables.net-select/js/dataTables.select.min.js"></script>
       <script type="text/javascript" src="<?php echo base_url();?>assets_admin/sweetalert/dist/sweetalert.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url();?>assets_admin/js/deletesweetalert.js"></script>
    
    <!-- Dropzone.js -->
    <script src="<?php echo base_url();?>assets_admin/vendors/dropzone/dist/dropzone.js"></script>
	 <script src="<?php echo base_url();?>assets_admin/vendors/moment/min/moment.min.js"></script>

    
    <script src="<?php echo base_url();?>assets_admin/vendors/bootstrap-daterangepicker/daterangepicker.js"></script>
	
	<!-- DateTimePicker.js -->
    <script src="<?php echo base_url();?>assets_admin/vendors/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>

    <!-- validator -->
    <script src="<?php echo base_url();?>assets_admin/vendors/validator/validator.js"></script>
    <script src="<?php echo base_url();?>assets_admin/vendors/mjolnic-bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js"></script>
    

    <script src="<?php echo base_url();?>assets_admin/js/parsley.js"></script>
    
    <script  src="<?php echo base_url();?>assets_admin/js/ozCustom.js"></script>

    <script type="text/javascript" src="<?php echo base_url();?>assets_admin/tinymce_4.7.3/tinymce.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets_admin/tinymce_4.7.3/langs/ar.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets_admin/tinymce_4.7.3/langs/en_GB.js"></script>
    <!-- sweet alerts css loadded in header -->
 
  </head>
  <style type="text/css">
    .profile_info{
        padding: 10px;
    }

      .center {
  display: block;
  margin-left: auto;
  margin-right: auto;
}

 .custom-class {
    background-color:#337ab7;
    color: white;padding: 0.7em 1em;
    text-decoration: none;
    /*text-transform: uppercase;*/
    margin-bottom: 0px;
    border-color: #2e6da4;
    margin-right: 5px;
    display: inline-block;
    padding: 7px 12px;
    border-radius: 3px;
    font-size: 14px;
    font-weight: 400;
    line-height: 1.42857143;
    text-align: center;
    white-space: nowrap;
    vertical-align: middle;
    -ms-touch-action: manipulation;
    touch-action: manipulation;
    cursor: pointer;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    background-image: none;
    border: 1px solid transparent;
        /*border-radius: 5px;*/
    }
    .custom-class-info {
    background-color:#5bc0de;;
    color: white;padding: 0.7em 1em;
    text-decoration: none;
    /*text-transform: uppercase;*/
    margin-bottom: -6px;
    border-color: #2e6da4;
    margin-right: 5px;
    display: inline-block;
    padding: 6px 12px;
    border-radius: 3px;
    font-size: 14px;
    font-weight: 400;
    line-height: 1.42857143;
    text-align: center;
    white-space: nowrap;
    vertical-align: middle;
    -ms-touch-action: manipulation;
    touch-action: manipulation;
    cursor: pointer;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    background-image: none;
    border: 1px solid transparent;
        /*border-radius: 5px;*/
    }
  </style>

  <body class="nav-sm">
    <div class="container body">
      <div class="main_container">
        <div class="col-md-3 left_col">
          <div class="left_col scroll-view">
            <div class="navbar nav_title" style="margin-top: 10px; margin-bottom: 20px !important;"> 
              <a href="<?php echo base_url('admin/Dashboard/');?>" class="site_title center"><?php echo $site_title;?></a> 
            </div> 
            <div style="width: 100% !important; float: none !important; ">
            <?php 
            $picture_id = $this->session->userdata('logged_in')->picture;
            $pfile = $this->db->get_where('files', ['id' => $picture_id])->row_array();

                ?> 
                     <div class="profile clearfix">
                      <div class="profile_pic" style="width: 40%;">
                      <?php
                        // $profile = $this->db->get('users')->row_array();
                        if(isset($pfile['name']) && !empty($pfile['name'])){
                            ?>
                            <div class="carousel-item active">
                                 <img style="max-width: 70px; height: 65px;" class="img-circle profile" src="<?= base_url($pfile['path'] . $pfile['name']); ?>">
                            </div>
                         
                        <?php }else{ ?>
                            <div class="profile_pic" style="width: 40%;">
                               <img style="max-width: 70px; height: 65px;" src="<?php echo base_url().'uploads/profile_picture/default';?>" alt="..." class="img-circle profile_img">
                             </div>

                        <?php } ?>

                      </div>
                  </div>
                  <div class="profile clearfix">
                      <div class="profile_info">
                        <span style="float: left; line-height: 65px">Welcome,</span>
                        <h2 style="margin: 25px 7px 7px 6px; float: left;"><?php echo (isset($logged_in['fname']) && !empty($logged_in['fname'])) ? $logged_in['fname'] : '';?></h2>
                      </div>
                </div>
              </div>
            <div class="clearfix"></div>
            <br /> 

            <?php $this->load->view('template/template_sidebar_controller');?>
            
          </div>
        </div>

		<?php $this->load->view('template/template_topnavigation');?>
        <div class="right_col" role="main">
          <div class="">
            <?php echo $main_content;?>
          </div>

        </div>


		<?php $this->load->view('template/template_footer');?>
      </div>
    </div>


<script  src="<?php echo base_url();?>assets_admin/js/custom.js"></script>
<!-- <script  src="<?php //echo base_url();?>assets_admin/js/form-builder.js"></script> -->
<!-- <script src="http://parsleyjs.org/dist/parsley.js"></script> -->
<script>
$(document).ready(function() {
var oTable = $('#datatable-responsive').dataTable();
   
  // Re-draw the table - you wouldn't want to do it here, but it's an example :-)
  oTable.fnDraw();
});
  window.setTimeout(function() 
  {
    $(".alert").fadeTo(500, 0).slideUp(500, function()
    {
        $(this).remove(); 
    });
  }, 3000);
</script>

  </body>
</html>


