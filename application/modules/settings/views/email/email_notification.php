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
        <div class="col-md-12 col-sm-12 " id="result"></div>

        <form method="post" data-parsley-validate="" id="demo-form2" action="" enctype="multipart/form-data" class="form-horizontal form-label-left">
            <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />

            <div class="item form-group">    
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="subject">Subject<span class="required">*</span>
                </label>

                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input type="text" autocomplete="off" name="subject" id="subject" value="<?php if(isset($email_template) && !empty($email_template)) { echo $email_template[0]['subject']; } ?>" class="form-control col-md-7 col-xs-12">
                    <span class="text-danger subject-error"></span>
                </div>
            </div>

            <div class="item form-group">    
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="body">Email Body<span class=""> *</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <textarea style="display: none;" rows="5"  id="body" title="please add something" name="body" class="form-control col-md-7 col-xs-12"><?php if(isset($email_template) && !empty($email_template)) { echo $email_template[0]['body']; } ?></textarea>
                    <div id="content_message"><?php if(isset($email_template) && !empty($email_template)) { echo $email_template[0]['body']; } ?></div>
                    <span class="text-danger body-error"></span>
                </div>
            </div>

            <!-- <div class="item form-group">    
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="template_id">Email Template 
                    <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <select class="form-control col-md-7 col-xs-12 template_id" multiple="" id="template_id" name="template_id[]">
                        <?php //foreach ($email_template_body as $type_value) { ?>
                            <option value="<?php// echo $type_value['id']; ?>"><?php //echo $type_value['template_name']; ?></option>
                        <?php // } ?>
                    </select>
                    <span class="text-danger template_id-error"></span>
                </div>
            </div> -->

            <div class="item form-group">    
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="to_email">To Email 
                    <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <select class="form-control col-md-7 col-xs-12 to_email" multiple="" id="to_email" name="to_email[]">
                        <?php foreach ($customer_list as $type_value) { ?>
                            <option value="<?php echo $type_value['email']; ?>"><?php echo $type_value['name']; ?></option>
                        <?php  } ?>
                    </select>
                    <span class="text-danger to_email-error"></span>
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
  var ok;
  var url = '<?php echo base_url();?>';
  var formaction_path = '<?php echo $formaction_path;?>';

   // You can use the locally-scoped $ in here as an alias to jQuery.
  $('#demo-form2').parsley().on('field:validated', function() {
    var ok = $('.parsley-error').length === 0;

  }) .on('form:submit', function() {
    return false; // Don't submit form for this demo
  });
  
  $("#send").on('click', function(e) { 
    e.preventDefault();
    $('#body').val(tinymce.activeEditor.getContent());
    // if(ok == false){ 
    //   return;
    //  }
    var validation = false;
    var errorMsg = "This value is required.";
    var errorClass = ".valid-error";
    var e;
    selectedInputs = ['subject','to_email'];

    $.each(selectedInputs, function(index, value){
        e = $("#"+value);
        console.log(e);

        if(($.trim(e.val()) == "") || (e.val() == null) || (typeof e.val() === 'undefined')){
            e.focus();
            $('.'+value+'-error').html(errorMsg).show();
            validation = false;
            return false;
        }else{
            validation = true;
            $('.'+value+'-error').html('').hide();
        }
    });

    if (validation == true) {
        $('#send').attr( 'disabled','disabled');
      var formData = new FormData($("#demo-form2")[0]);
      $.ajax({
        url: url + 'settings/' + formaction_path,
        type: 'POST',
        data: formData,
        cache: false,
        contentType: false,
        processData: false
      }).then(function(data123) 
      {
        var objData = jQuery.parseJSON(data123);
        //console.log(objData.data);
        if (objData.msg == 'success')
        {
            $('.msg-alert').css('display', 'block');
            // $("#result").html('<div class="alert" ><div class="alert alert-domain alert-success fade in  msg-alert" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: black"><span aria-hidden="true">×</span> </button><h3>Email Sent </h3></div></div>');
            $("#result").html('<div class="alert" ><div class="alert alert-domain alert-success alert-dismissible fade in  msg-alert" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: black"><span aria-hidden="true">×</span> </button>Email sent</div></div>');
            window.scrollTo({top: 0, behavior: "smooth"});
            setTimeout(function(){ window.location.reload(); }, 5000);
        } 
        else
        {
            $('.msg-alert').css('display', 'block');
            $("#result").html('<div class="alert" ><div class="alert alert-domain alert-danger alert-dismissible fade in  msg-alert" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: black"><span aria-hidden="true">×</span> </button>' + objData.msg + '</div></div>');
            window.scrollTo({top: 0, behavior: "smooth"});
        }
      });
    }
  });

 // multiple select select2 JQuery 
  // $(".template_id").select2({
  //   placeholder: " Select Email Template", 
  //   width: '200px',
  // });

 // multiple select select2 JQuery 
  $(".to_email").select2({
    placeholder: " Select Customers", 
    width: '200px',
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
    // relative_urls : true,
    // document_base_url : 'http://www.example.com/path1/'
  });

</script>