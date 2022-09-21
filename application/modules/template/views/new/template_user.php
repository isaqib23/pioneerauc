<!DOCTYPE html>
<html lang="<?= $ln; ?>" dir="<?= $direction; ?>" class="<?= $direction; ?>">
<head>
  
  <?= meta($this->uri->segment(2));?>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <!-- <link rel="icon" href="<?= base_url('uploads/'); ?>favicon.png" type="image/x-icon"> -->
    <link href="<?php echo base_url();?>assets_user/new/images/favicon.png" rel="shortcut icon" type="image/x-icon" />

    <!-- Styles -->
    <link rel="stylesheet" href="<?= NEW_ASSETS_USER; ?>new/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= NEW_ASSETS_USER; ?>new/css/slick.css">
    <link rel="stylesheet" href="<?= NEW_ASSETS_USER; ?>new/css/bootstrap-select.min.css">
    <link rel="stylesheet" href="<?= NEW_ASSETS_USER; ?>new/css/datatables.min.css">
    <link rel="stylesheet" href="<?= NEW_ASSETS_USER; ?>new/css/dropzone.css">
    <link rel="stylesheet" href="<?= NEW_ASSETS_USER; ?>new/css/bootstrap-datepicker.css">
    <link rel="stylesheet" href="<?= base_url(); ?>assets_user/tools/PhoneMask/css/intlTelInput.min.css">
    <link rel="stylesheet" href="<?= base_url(); ?>assets_admin/vendors/pnotify/dist/pnotify.css">
    <link rel="stylesheet" href="<?= NEW_ASSETS_USER; ?>new/css/styles.css">
    <link rel="stylesheet" href="<?= NEW_ASSETS_USER; ?>new/css/styles-2.css">
    <link rel="stylesheet" href="<?= NEW_ASSETS_USER; ?>new/css/responsive.css">

    <!-- Scripts -->
    <script src="<?= NEW_ASSETS_USER; ?>new/js/jquery.min.js"></script>
    <script src="<?= NEW_ASSETS_USER; ?>new/js/popper.min.js"></script>
    <script src="<?= NEW_ASSETS_USER; ?>new/js/bootstrap.min.js"></script>
    <script src="<?= NEW_ASSETS_USER; ?>new/js/slick.min.js"></script>
    <script src="<?= NEW_ASSETS_USER; ?>new/js/bootstrap-select.min.js"></script>
    <script src="<?= NEW_ASSETS_USER; ?>new/js/datatables.min.js"></script>
    <script src="<?= NEW_ASSETS_USER; ?>new/js/dropzone.js"></script>
    <script src="<?= NEW_ASSETS_USER; ?>new/js/bootstrap-datepicker.js"></script>
    <script src="<?= base_url(); ?>assets_user/tools/PhoneMask/js/intlTelInput-jquery.min.js"></script>
    <script src="<?= base_url(); ?>assets_user/tools/PhoneMask/js/utils.js"></script>
    <script src="<?= base_url(); ?>assets_admin/vendors/pnotify/dist/pnotify.js"></script>
    <script src="<?= NEW_ASSETS_USER; ?>new/js/custom.js"></script>

    <script type='text/javascript' src='https://platform-api.sharethis.com/js/sharethis.js#property=6051c66951f70600114ff4dd&product=sop' async='async'></script>
  
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

    $(document).ready(function() {
        if($(".datatable").length){
          var language = '<?= ($language == 'arabic') ? NEW_ASSETS_USER."new/js/DTArabic.json" : ""; ?>';
          var table = $('.datatable').DataTable( {
            responsive: true,
            "autoWidth": false,
            autoWidth: false,
            "language":  {
                "url": language,
                "paginate": {
                    "previous": '<span class="material-icons">keyboard_arrow_left</span>',
                    "next": '<span class="material-icons">keyboard_arrow_right</span>'
                }
            }
          });
          table.columns.adjust().draw();

          // $(window).on('resize', function () {
          //   table.columns.adjust();
          // });
          // $(window).load(function () {
          //   table.columns.adjust();
          // });
        
        }
    });
  </script>

<meta name="facebook-domain-verification" content="ib5ip1p0arl2fsyogr901pgnkv7wy3" />
<!-- Global site tag (gtag.js) - Google Ads: 384770484 -->
<script async src="https://www.googletagmanager.com/gtag/js?id=AW-384770484"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-211169252-1');
</script>
<!-- Event snippet for Sign-up conversion page
In your html page, add the snippet and call gtag_report_conversion when someone clicks on the chosen link or button. -->
<script>
function gtag_report_conversion() {
  var callback = function () {
    if (typeof(url) != 'undefined') {
      window.location = url;
    }
  };
  gtag('event', 'conversion', {
      'send_to': 'AW-384770484/URZdCIets4kCELTDvLcB',
      'event_callback': callback
  });
  return false;
}
</script>

<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-TM48G9D');</script>
<!-- End Google Tag Manager

<!-- Facebook Pixel Code -->
<script>
!function(f,b,e,v,n,t,s)
{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};
if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];
s.parentNode.insertBefore(t,s)}(window, document,'script',
'https://connect.facebook.net/en_US/fbevents.js');
fbq('init', '971217612923915');
fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id=971217612923915&ev=PageView&noscript=1"
/></noscript>
<!-- End Facebook Pixel Code -->

<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-TM48G9D');</script>
<!-- End Google Tag Manager -->

</head> 
<body class="<?= isset($home_class) ? 'front-page' : ''; ?>">
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-TM48G9D"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->

<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-TM48G9D"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->

<?php 
    if (isset($categoryId) && !empty($categoryId)) {
      $headerCategoryId = $categoryId;
    }elseif (!empty($this->session->userdata('categoryId'))) {
      $headerCategoryId = $this->session->userdata('categoryId');
    } else {
      $headerCategoryId = '';
    }
    $this->load->view('template/new/header_customer',['headerCategoryId' => $headerCategoryId]);
    
    echo (($main_content)) ? $main_content : ''; 
  ?>

  <script>
      $("img").on("error", function(){
          $(this).attr("src", '<?= ASSETS_ADMIN."images/product-default.jpg" ?>');
      });
  </script>

  <?php $this->load->view('template/new/template_user_footer'); ?>  
