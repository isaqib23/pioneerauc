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
        <div class="x_content">
        <div id="result"></div>  
         <?php 
            if(isset($edit) && !empty($edit)) {
                $heading = json_decode($edit['heading']);
                $title = json_decode($edit['title']);
                $description = json_decode($edit['description']);
            }
            ?>


            <form method="post" novalidate="" id="demo-form2" name="ozform" action="" enctype="multipart/form-data" class="form-horizontal form-label-left">
                <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
               <?php if(isset($edit) && !empty($edit)){ ?>
                <input type="hidden" name="id" value="<?php echo $this->uri->segment(3); ?>">
            <?php } ?>  
                 
                <div class="item form-group">    
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="english_heading">English Heading  
                        <span class="required">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text"   id="english_heading" required="required" name="english_heading" value="<?php if(isset($edit) && !empty($edit)) { echo $heading->english; } ?>" class="form-control col-md-7 col-xs-12">
                    </div>
                </div>

                <div class="item form-group">    
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="arabic_heading"> Arabic Heading
                        <span class="required">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" dir="rtl" id="arabic_heading" required="required" name="arabic_heading" value="<?php if(isset($edit) && !empty($edit)) { echo $heading->arabic; } ?>" class="form-control col-md-7 col-xs-12">
                    </div>
                </div>
                 
                <div class="item form-group">    
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="english_title">English Title
                        <span class="required">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text"   id="english_title" required="required" name="english_title" value="<?php if(isset($edit) && !empty($edit)) { echo $title->english; } ?>" class="form-control col-md-7 col-xs-12">
                    </div>
                </div>

                <div class="item form-group">    
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="arabic_title">Arabic Title
                        <span class="required">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" dir="rtl" id="arabic_title" required="required" name="arabic_title" value="<?php if(isset($edit) && !empty($edit)) { echo $title->arabic; } ?>" class="form-control col-md-7 col-xs-12">
                    </div>
                </div>

                <div class="item form-group">    
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title_arabic">Since
                        <span class="required">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" readonly="readonly" name="since" required="required" id="date" value="<?php if(isset($edit) && !empty($edit)) { echo date('Y',strtotime($edit['since'])); } ?>" class="form-control col-md-7 col-xs-12">
                    </div>
                </div>

                <div class="item form-group">    
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="english_description">English Description <span class="required">*</span>
                    </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <textarea class="form-control col-md-7 col-xs-12 desc_english" id="english_description" ><?php if(isset($edit) && !empty($edit)){ echo $description->english; }?></textarea>  
                        </div>
                    <input type="hidden" name="english_description" id="texteditor">
                </div>

                <div class="item form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="arabic_description">Arabic Description<span class="required">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <textarea class="form-control col-md-7 col-xs-12 desc_arabic" id="arabic_description"><?php if(isset($edit) && !empty($edit)) { echo $description->arabic;
                      } ?></textarea>
                    </div>
                    <input type="hidden" name="arabic_description" id="texteditor2">
                </div>

                <div class="form-group">
                    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                    <button type="submit" id="send" class="btn btn-success"><?php echo (isset($edit) && !empty($edit)) ? 'Update' : 'Submit'; ?></button> 
                    <a href="<?php echo base_url().'cms/aboutus_history_listing/'; ?>" class="custom-class" type="button"> 
                        Cancel
                    </a>
                    </div>
                </div>

            </form>
        </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var base_url = '<?php echo base_url();?>';
    // var formaction_path = '<?php echo (isset($ques_ans_info) && !empty($ques_ans_info)) ? 'edit' : 'add';?>';
    var formaction_path = '<?php echo $formaction_path; ?>';
    $(document).ready(function() {
         // You can use the locally-scoped $ in here as an alias to jQuery.
        $('#demo-form2').parsley().on('field:validated', function() {
            var ok = $('.parsley-error').length === 0;
          }) .on('form:submit', function() {
            // $('#body').val(tinymce.activeEditor.getContent());
            var content1 = tinymce.get("english_description").getContent();
            $("#texteditor").val(content1);
            var content2 = tinymce.get("arabic_description").getContent();
            $("#texteditor2").val(content2);
            var formData = new FormData($("#demo-form2")[0]);
            $.ajax({
                url: base_url + 'cms/' + formaction_path,
                type: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success : function(data) {
                var objData = jQuery.parseJSON(data);
                   if (objData.success == true) { 
                        window.location = base_url + 'cms/aboutus_history_listing';
                    } 
                    else 
                    {
                    $('.msg-alert').css('display', 'block');
                    $("#result").html('<div class="alert" ><div class="alert alert-domain alert-danger alert-dismissible fade in  msg-alert" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: black"><span aria-hidden="true">×</span> </button>' + objData.msg + '</div></div>');
                        window.scrollTo({top: 0, behavior: "smooth"});
                    }  
                }

            });
            return false; // Don't submit form for this demo
          });
  
    });
</script>

<script type="text/javascript">
    $(document).ready(function() {
        $('#date').datetimepicker({ 
            format: 'YYYY',
            ignoreReadonly: true,
        });
    });

    tinymce.init({
      selector: '.desc_english',
      height: 350,
      theme: 'modern',
      menubar: false,
      branding: false
    });

    tinymce.init({
      selector: '.desc_arabic',
      height: 350,
      theme: 'modern',
      menubar: false,
      branding: false,
      //language: 'ar',
      directionality: 'rtl'
    });
</script>