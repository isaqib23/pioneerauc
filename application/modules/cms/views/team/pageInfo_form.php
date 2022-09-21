
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
   <?php 
            if(isset($team_information) && !empty($team_information)) {
                $description = json_decode($team_information['description']);
                // print_r($description);die();
            }
            ?>
  
  <form method="post" data-parsley-validate="" id="demo-form2" action="" enctype="multipart/form-data" class="form-horizontal form-label-left" >
    <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
    <div class="item form-group">    
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="description_english">Description English <span class="required">*</span>
        </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <textarea class="form-control col-md-7 col-xs-12 desc_english" id="description_english" ><?php if(isset($team_information) && !empty($team_information)){ echo $description->english; }?></textarea>  
            </div>
        <input type="hidden" name="description_english" id="texteditor">
    </div>

    <div class="item form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="description_arabic">Description Arabic<span class="required">*</span>
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <textarea class="form-control col-md-7 col-xs-12 desc_arabic" id="description_arabic"><?php if(isset($team_information) && !empty($team_information)) { echo $description->arabic;
          } ?></textarea>
        </div>
        <input type="hidden" name="description_arabic" id="texteditor2">
    </div>


    
    <div class="ln_solid"></div>
    <div class="form-group">
        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-2">
            <button type="submit" id="send" class="btn btn-success"><?php echo $status_btn; ?></button>
            <!-- <a href="<?php echo base_url('cms');?>" type="button" id="send" class="btn btn-success"> Back</a> -->

        </div>
    </div>

</form>

</div>
</div>

<script type="text/javascript">

    var ok;
    var url = '<?php echo base_url();?>';
    var formaction_path = '<?php echo $formaction_path;?>';
  
     // You can use the locally-scoped $ in here as an alias to jQuery.
  $('#demo-form2').parsley().on('field:validated', function() {
    var ok = $('.parsley-error').length === 0;
  }).on('form:submit', function() {
    var content1 = tinymce.get("description_english").getContent();
            $("#texteditor").val(content1);
            var content2 = tinymce.get("description_arabic").getContent();
            $("#texteditor2").val(content2);

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

   tinymce.init({
      selector: '.desc_english',
      height: 350,
      theme: 'modern',
      menubar: false,
      branding: false,
      plugins: [
        'autolink lists link',
      ],
    });

    tinymce.init({
      selector: '.desc_arabic',
      height: 350,
      theme: 'modern',
      menubar: false,
      branding: false,
      //language: 'ar',
      directionality: 'rtl',
      plugins: [
        'autolink lists link',
      ],
    });
</script>