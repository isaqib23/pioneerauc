<!DOCTYPE html>
<html lang="<?= $ln; ?>" dir="<?= $direction; ?>" class="<?= $direction; ?>">
<head>
    <meta charset="utf-8">
    <!-- <meta name="viewport" content="width=device-width, initial-scale=0"> -->
    <meta name="viewport" content="initial-scale=1.0, user-scalable=0">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <link rel="icon" href="<?php echo base_url();?>uploads/favicon.ico" type="image/x-icon">
    <title><?= isset($page_title) ? $page_title : ''; ?></title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css">

    <?php if(isset($evaluation)){ ?>
      <link href="<?= NEW_ASSETS_USER; ?>css/bootstrap.min.css" rel="stylesheet">
      <link href="<?= NEW_ASSETS_USER; ?>css/bootstrap-datetimepicker.css" rel="stylesheet">
    <?php }else { ?>
      <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4-4.1.1/dt-1.10.21/r-2.2.5/datatables.min.css"/>
    <?php } ?>
    <link rel="stylesheet" href="<?= NEW_ASSETS_USER; ?>css/slick.css">
    <link rel="stylesheet" href="<?= NEW_ASSETS_USER; ?>css/aos.css">
    <link rel="stylesheet" href="<?= NEW_ASSETS_USER; ?>css/bootstrap-select.min.css">
  
    <link rel="stylesheet" href="<?= NEW_ASSETS_USER; ?>css/style.css">

    
    

    <?php if(isset($evaluation)){ ?>
      <script src="<?= NEW_ASSETS_USER; ?>js/jquery.min.js"></script>
      <script src="<?= NEW_ASSETS_USER; ?>js/bootstrap.min.js"></script>
      <script src="<?= NEW_ASSETS_USER; ?>js/moment.min.js"></script>
      <script src="<?= NEW_ASSETS_USER; ?>js/bootstrap-datetimepicker.min.js"></script>

      <script>
        $(document).ready(function() {
          $("#date-app").datetimepicker({
            minDate: moment(),
            widgetPositioning:{
              horizontal: 'auto',
              vertical: 'bottom'
            }
          });
        });
      </script>

    <?php } else { ?>
      <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="<?= NEW_ASSETS_USER; ?>js/popper.min.js"></script>
      <script type="text/javascript" src="https://cdn.datatables.net/v/bs4-4.1.1/dt-1.10.21/r-2.2.5/datatables.min.js"></script>

      <script>
        // $(document).ready(function() {
        if($(".table_id").length){
          var language = '<?= ($language == 'arabic') ? "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Arabic.json" : ""; ?>';
          var table = $('.table_id').DataTable( {
            responsive: true,
            "autoWidth": false,
            autoWidth: false,
            "dom": '<"top"i>rt<"bottom"flp>',
            "language":  {"url": language}
          });
          table.columns.adjust().draw();

          $(window).on('resize', function () {
            table.columns.adjust();
          });
          $(window).load(function () {
            table.columns.adjust();
          });
        
        }
        // });
      </script>
    <?php } ?>
    
    <script src="<?= NEW_ASSETS_USER; ?>js/jquery.syotimer.js"></script>
    <script src="<?= NEW_ASSETS_USER; ?>js/slick.js"></script>
    <script src="<?= NEW_ASSETS_USER; ?>js/aos.js"></script>
    <script src="<?= NEW_ASSETS_USER; ?>js/bootstrap-select.min.js"></script>
    <script src="<?= NEW_ASSETS_USER; ?>js/zee.js"></script> 

    <script src="https://cdn.jsdelivr.net/momentjs/2.14.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js"></script>

    <script src="<?= NEW_ASSETS_USER; ?>js/custom.js"></script> 
    <!-- <script src="<?= NEW_ASSETS_USER; ?>js/proper.min.js.map"></script>  -->

  
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
<body class="<?= isset($home_class) ? 'front-page' : ''; ?>">
  <?php 
      $this->load->view('template/header_customer');
   ?>

 <?php echo (($main_content)) ? $main_content : ''; ?>

 <?php 
      $this->load->view('template/template_user_footer');  
  ?>  

  <script>
    //load default image for every img tag in the project
      $("img").on("error", function(){
          $(this).attr("src", '<?= ASSETS_ADMIN."images/product-default.jpg" ?>');
      });
  </script>