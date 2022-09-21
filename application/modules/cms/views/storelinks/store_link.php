
<div class="clearfix"></div>
<div class="col-md-12 col-sm-12 col-xs-12">
  <div class="x_panel">
      <div class="x_title">
          <h2>
              <?php echo $small_title; ?>
          </h2>
          <div class="clearfix"></div>
      </div>
      <div class="x_content"></div>
        <?php if($this->session->flashdata('msg')){ ?>
      <div class="alert">
          <div class="alert alert-success alert-dismissible fade in" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: black;"><span aria-hidden="true">×</span>
            </button>
            <?php  echo $this->session->flashdata('msg');   ?>
        </div>
    </div>
  <?php }?>
  <div id="resultt"></div>
  
  <form method="post" data-parsley-validate="" id="demo-form2" action="" enctype="multipart/form-data" class="form-horizontal form-label-left" >
    <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />   
    <div class="item form-group">    
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title_arabic">Google Store Link
            <span class="required">*</span>
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <input type="text"  name="google_play" required="required"  value="<?php if(isset($store_links) && !empty($store_links)) { echo $store_links['google_play']; } ?>" class="form-control col-md-7 col-xs-12">
        </div>
    </div>

     <div class="item form-group">    
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title_arabic">Apple Store Link
            <span class="required">*</span>
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <input type="text"  name="apple_store"  required="required" value="<?php if(isset($store_links) && !empty($store_links)) { echo $store_links['apple_store']; } ?>" class="form-control col-md-7 col-xs-12">
        </div>
    </div>

    
    <div class="ln_solid"></div>
    <div class="form-group">
        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-2">
            <button type="submit" id="send" class="btn btn-success"><?php echo $status_btn; ?></button>
        </div>
    </div>

</form>

</div>
</div>

<script type="text/javascript">

    $("#address").geocomplete({details:"form#demo-form2"});

    var ok;
    var url = '<?php echo base_url();?>';
    var formaction_path = '<?php echo $formaction_path;?>';
  
     // You can use the locally-scoped $ in here as an alias to jQuery.
  $('#demo-form2').parsley().on('field:validated', function() {
    var ok = $('.parsley-error').length === 0;
  }).on('form:submit', function() {

        var formData = new FormData($("#demo-form2")[0]);
          $.ajax({
              url: url + 'cms/' + formaction_path,
              type: 'POST',
              data: formData,
              cache: false,
              contentType: false,
              processData: false
          }).then(function(data) 
          {
            var objData = jQuery.parseJSON(data);
            console.log(objData);
            if (objData.error == false)
            {
                // window.location = url + 'cms/terms';
                 $("#resultt").html('<div class="alert" ><div class="alert alert-domain alert-success alert-dismissible fade in  msg-alert" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: black"><span aria-hidden="true">×</span> </button>' + objData.msg + '</div></div>');
            } 
            else
            {
                $('.msg-alert').css('display', 'block');
                $("#resultt").html('<div class="alert" ><div class="alert alert-domain alert-danger alert-dismissible fade in  msg-alert" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: black"><span aria-hidden="true">×</span> </button>' + objData.msg + '</div></div>');
            }
          });
              return false; // Don't submit form for this demo
  });

</script>

<script type="text/javascript">

</script>