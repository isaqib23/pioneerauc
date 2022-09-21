<script src="<?php echo base_url();?>assets_admin/tinymce_4.7.3/tinymce.js"></script>
<style type="text/css">
    #page-wrapper {
        margin-top: 100px;
    }
</style>
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
    <div id="result"></div>

    <form method="post" data-parsley-validate="" id="demo-form2" action="" enctype="multipart/form-data" class="form-horizontal form-label-left">
      <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />

      <?php if(isset($email_template) && !empty($email_template)){ ?>
        <input type="hidden" name="id" value="<?php echo $this->uri->segment(3);?>">
      <?php } 
      ?> 
      <div class="item form-group">    
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="template_name">Template Name <span class="required">*</span>
        </label>

        <div class="col-md-6 col-sm-6 col-xs-12">
          <input type="text" required id="template_name" name="template_name" value="<?php if(isset($email_template) && !empty($email_template)) { echo $email_template[0]['template_name']; } ?>" class="form-control col-md-7 col-xs-12">
        </div>
      </div>

      <div class="item form-group">    
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="template_name">Slug <span class="required">*</span>
        </label>
        <?php if(empty($email_template)){ ?>
          <div class="col-md-6 col-sm-6 col-xs-12">
            <input type="text" required id="slug" name="slug" value="<?php if(isset($email_template) && !empty($email_template)) { echo $email_template[0]['slug']; } ?>" class="form-control col-md-7 col-xs-12">
          </div> 
        <?php } else{ ?>

          <div class="col-md-6 col-sm-6 col-xs-12">
            <input disabled="" type="text" required id="slug" name="slug" value="<?php if(isset($email_template) && !empty($email_template)) { echo $email_template[0]['slug']; } ?>" class="form-control col-md-7 col-xs-12">
          </div>
        <?php } ?>
      </div>
            
      <div class="item form-group">    
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="from_email">Subject<span class="required">*</span>
        </label>

        <div class="col-md-6 col-sm-6 col-xs-12">
          <input type="text" required id="from_email" name="subject" value="<?php if(isset($email_template) && !empty($email_template)) { echo $email_template[0]['subject']; } ?>" class="form-control col-md-7 col-xs-12">
        </div>
      </div>
      <div class="item form-group">    
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="from_email">Key Words<span class="required"></span>
        </label>

        <div class="col-md-6 col-sm-6 col-xs-12">
          <p> {Username},{Name},{Password},{Assigned_by},{Item_name},{ItemNo},{Category_name},{Amount},{Activation_link},{Login_link},{Email},{Valuation_make},{Valuation_model},{Valuation_year},{Valuation_enginesize},{Option},{Paint},{Valuation_price},{Year},{Bid_price},{Lot_number},{Registration_number},{Date},{Time},{User_email},{First_name},{Last_name},{Mobile} </p>
        </div>
      </div>

      <div class="item form-group">    
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="body">Email Body<span class=""> *</span>
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
          <textarea style="display: none;" rows="5"  id="body" title="please add something" name="body" class="form-control col-md-7 col-xs-12"><?php if(isset($email_template) && !empty($email_template)) { echo $email_template[0]['body']; } ?></textarea>
          <div id="content_message"><?php if(isset($email_template) && !empty($email_template)) { echo $email_template[0]['body']; } ?></div>


        </div>
      </div>

      <div class="ln_solid"></div>
      <div class="form-group">
        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-2">
            <button type="submit" id="send" class="btn btn-success"><?php echo $status_btn; ?></button>
             <a href="<?php echo base_url().'settings/email_template_list'; ?>" class="custom-class" type="button">Cancel</a>
        </div>
      </div>

    </form>
  </div>
</div>

<script type="text/javascript">
  $("#template_name").keyup(function(){
    var Text = $(this).val();
    Text = Text.toLowerCase();
    Text = Text.replace(/[^a-zA-Z0-9]+/g,'-');
    $("#slug").val(Text);        
  });


  var ok;
  var url = '<?php echo base_url();?>';
  var formaction_path = '<?php echo $formaction_path;?>';
  
  // You can use the locally-scoped $ in here as an alias to jQuery.
  $('#demo-form2').parsley().on('field:validated', function() {
    var ok = $('.parsley-error').length === 0;
  }).on('form:submit', function() {

   $('#body').val(tinymce.activeEditor.getContent());
   var formData = new FormData($("#demo-form2")[0]);
    $.ajax({
      url: url + 'settings/' + formaction_path,
      type: 'POST',
      data: formData,
      cache: false,
      contentType: false,
      processData: false
    }).then(function(data) 
    {
      var objData = jQuery.parseJSON(data);
      console.log(objData);
      if (objData.msg == 'success')
      {
          window.location = url + 'settings/email_template_list';
      } 
      else
      {
        $('.msg-alert').css('display', 'block');
        $("#result").html('<div class="alert" ><div class="alert alert-domain alert-danger alert-dismissible fade in  msg-alert" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: black"><span aria-hidden="true">×</span> </button>' + objData.msg + '</div></div>');
      }
    });
    return false; // Don't submit form for this demo
  });
  
  tinymce.init({
    selector: '#content_message',
    skin: 'lightgray',
    theme: 'modern',
    mobile: {
      theme: 'beta-mobile',
      plugins: [ 'autosave' ]
    },//fullpage
    // width: 600,
    menubar: false,
    relative_urls: false,
    automatic_uploads: false,
    fix_list_elements : true,
    visual_table_class: 'tiny-table',
    height: 300,
    branding: false,
    plugins: [
        'lists link',
    ],
    relative_urls : false,
    remove_script_host : false,
    document_base_url : '<?= base_url(); ?>'
  });

</script>