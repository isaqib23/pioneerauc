<!-- <script src="<?php echo base_url();?>assets_admin/tinymce_4.7.3/tinymce.js"></script> -->
<script type="text/javascript" src="<?php echo base_url();?>assets_admin/tinymce_4.7.3/tinymce.min.js"></script>
<!-- <script type="text/javascript" src="<?php //echo base_url();?>assets_admin/tinymce_4.7.3/langs/ar.js"></script> -->
<script type="text/javascript" src="<?php echo base_url();?>assets_admin/tinymce_4.7.3/langs/en_GB.js"></script>
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
  <?php 
      $description = json_decode($terms_info['description']);
      $title = json_decode($terms_info['title']);
   ?>
  <form method="post" data-parsley-validate="" id="demo-form2" action="" enctype="multipart/form-data" class="form-horizontal form-label-left">
    <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />           
    <div class="item form-group">    
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title_english">Title English
        <span class="required">*</span>
      </label>
      <div class="col-md-6 col-sm-6 col-xs-12">
        <input type="text" required id="title_english" name="title_english" value="<?php if(isset($terms_info) && !empty($terms_info)) { echo $title->english; } ?>" class="form-control col-md-7 col-xs-12">
      </div>
    </div>   
    <div class="item form-group">    
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title_arabic">Title Arabic
            <span class="required">*</span>
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <input type="text" dir="rtl"  id="title_arabic" required="required" name="title_arabic" value="<?php if(isset($terms_info) && !empty($terms_info)) { echo $title->arabic; } ?>" class="form-control col-md-7 col-xs-12">
        </div>
    </div>
    <div class="item form-group">    
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="description_english">Detail English <span class="required">*</span>
            
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <textarea class="form-control col-md-7 col-xs-12" id="description_english" ><?php if(isset($terms_info) && !empty($terms_info)){ echo $description->english; }?></textarea>
            <span class="text-danger description_english-error"></span>
        </div>
        <input type="hidden" name="description_english" id="texteditor">
    </div>
    <div class="item form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="description_arabic">Detail Arabic<span class="required">*</span>
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <textarea class="form-control col-md-7 col-xs-12" id="description_arabic"><?php if(isset($terms_info) && !empty($terms_info)) { echo $description->arabic;
          } ?></textarea>
          <span class="text-danger description_arabic-error"></span>
        </div>
        <input type="hidden" name="description_arabic" id="texteditor2">
    </div>
    <div class="ln_solid"></div>
    <div class="form-group">
        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-2">
            <button type="submit" id="send" class="btn btn-success"><?php echo $status_btn; ?></button>
             <!-- <a href="<?php echo base_url().'cms/terms/'; ?>" class="custom-class" type="button">Cancel</a> -->
        </div>
    </div>

</form>

</div>
</div>

<script>
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
    var validation = false;
    var errorMsg = "This value is required.";
    // var errorClass = ".valid-error";
    var e;
    selectedInputs = ['description_english','description_arabic'];
    $.each(selectedInputs, function(index, value){
      var e = (tinymce.get(""+value+""))
      if(($.trim(e.getContent()) == "") || (e.getContent() == null) || (typeof e.getContent() === 'undefined')){
        e.focus();
        $('.'+value+'-error').html(errorMsg).show();
        validation = false;
        return false;
      }else{
        validation = true;
        $('.'+value+'-error').html('').hide();
        // $('.'+value+'-error').html('').hide();
      }
    });

    $('#body').val(tinymce.activeEditor.getContent());
    var content1 = tinymce.get("description_english").getContent();
    $("#texteditor").val(content1);
    var content2 = tinymce.get("description_arabic").getContent();
    $("#texteditor2").val(content2);

    if (validation == true) {
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
             $("#result").html('<div class="alert" ><div class="alert alert-domain alert-success alert-dismissible fade in  msg-alert" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: black"><span aria-hidden="true">×</span> </button>' + objData.msg + '</div></div>');
        }else{
            $('.msg-alert').css('display', 'block');
            $("#result").html('<div class="alert" ><div class="alert alert-domain alert-danger alert-dismissible fade in  msg-alert" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: black"><span aria-hidden="true">×</span> </button>' + objData.msg + '</div></div>');
        }
        window.scrollTo({top: 0, behavior: "smooth"});
      });
    }
        return false; // Don't submit form for this demo
  });

</script>


<script >
     tinymce.init({
  selector: 'textarea#description_arabic',
  document_base_url: '<?php echo base_url(); ?>',
   // language: 'ar',
   language: 'en_GB',
   branding: false,
  height: 300,
  direction: 'ltr',
  directionality : 'rtl',
  mode : "specific_textareas", 
  menubar: false,
  plugins: [
    //'image',

    //'advlist autolink lists link image charmap print preview anchor textcolor',
    'autolink lists link',
    //'searchreplace visualblocks code fullscreen',
    //'insertdatetime media table paste code help wordcount'
  ],
   // toolbar1: "link bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | styleselect fontselect fontsizeselect | cut copy paste | searchreplace | bullist numlist | outdent indent blockquote | undo redo | code | inserttime preview | forecolor backcolor",
   // toolbar2: "table | hr removeformat | subscript superscript | charmap | print fullscreen | spellchecker | visualchars visualblocks nonbreaking template pagebreak restoredraft",
   
    // content_css: [
    //   '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
    //   '//www.tiny.cloud/css/codepen.min.css'
    // ],

});

tinymce.init({
  selector: 'textarea#description_english',
  document_base_url: '<?php echo base_url(); ?>',
  language: 'en_GB',
  branding: false,
  height: 300,
  // directionality : 'ltr',
  mode : "specific_textareas", 
  menubar: false,
  plugins: [
    // 'image',
    'lists link',
    // 'searchreplace visualblocks code fullscreen',
    // 'insertdatetime media table paste code help wordcount'
  ],
   // toolbar1: "link bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | styleselect fontselect fontsizeselect | cut copy paste | searchreplace | bullist numlist | outdent indent blockquote | undo redo | code | inserttime preview | forecolor backcolor",
   // toolbar2: "table | hr removeformat | subscript superscript | charmap | print fullscreen | spellchecker | visualchars visualblocks nonbreaking template pagebreak restoredraft",
   
   //  content_css: [
   //    '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
   //    '//www.tiny.cloud/css/codepen.min.css'
   //  ],

});
</script>