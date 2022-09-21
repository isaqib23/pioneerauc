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
            if(isset($ques_ans_info) && !empty($ques_ans_info)) {
                $question = json_decode($ques_ans_info[0]['question']);
                $answer = json_decode($ques_ans_info[0]['answer']);
            }
            ?>


            <form method="post" novalidate="" id="demo-form2" name="ozform" action="" enctype="multipart/form-data" class="form-horizontal form-label-left">
                <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
                <?php if(isset($ques_ans_info) && !empty($ques_ans_info)){ ?>
                    <input type="hidden" name="id" value="<?php echo $this->uri->segment(3); ?>">
                <?php } ?>  
                 
                <div class="item form-group">    
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title_english">Question English 
                        <span class="required">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text"   id="question_english" required="required" name="question_english" value="<?php if(isset($ques_ans_info) && !empty($ques_ans_info)) { echo $question->english; } ?>" class="form-control col-md-7 col-xs-12">
                    </div>
                </div>



                <!-- <?php print_r($image_file[0]['name']); ?> -->

                <div class="item form-group">    
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title_arabic">Question Arabic 
                        <span class="required">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" dir="rtl" id="question_arabic" required="" name="question_arabic" value="<?php if(isset($ques_ans_info) && !empty($ques_ans_info)) { echo $question->arabic; } ?>" class="form-control col-md-7 col-xs-12">
                    </div>
                </div>

    <div class="item form-group">    
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="description_english">Answer English <span class="required">*</span>
        </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <textarea class="form-control col-md-7 col-xs-12 desc_english" id="answer_english" ><?php if(isset($ques_ans_info) && !empty($ques_ans_info)){ echo $answer->english; }?></textarea>  
            </div>
        <input type="hidden" name="answer_english" id="texteditor">
        <!-- <div id="result"></div> -->
        <!-- <span class="required" id="result">*</span> -->
    </div>

    <div class="item form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="description_arabic">Answer Arabic<span class="required">*</span>
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <textarea class="form-control col-md-7 col-xs-12 desc_arabic" id="answer_arabic"><?php if(isset($ques_ans_info) && !empty($ques_ans_info)) { echo $answer->arabic;
          } ?></textarea>
        </div>
        <input type="hidden" name="answer_arabic" id="texteditor2">
    </div>


               <!--  <div class="item form-group">    
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="description_english">Answer English
                          <span class="required">*</span>
                        </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        
                      <textarea type="text" rows="7" id="answer_english" title="please add something" name="answer_english" class="form-control col-md-7 col-xs-12"><?php if(isset($ques_ans_info) && !empty($ques_ans_info)){ echo $ques_ans_info[0]['answer_english']; }?></textarea>
                                            
                    </div>     
                </div>

                <div class="item form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="description_arabic">Answer Arabic
                          <span class="required">*</span>
                        </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <textarea type="text" rows="7" dir="rtl" id="description_arabic" title="please add something" name="answer_arabic" class="form-control col-md-7 col-xs-12"><?php if(isset($ques_ans_info) && !empty($ques_ans_info)){ echo $ques_ans_info[0]['answer_arabic']; }?></textarea>       
                    </div>   
                </div> -->

                <div class="form-group">
                    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                    <button type="submit" id="send" class="btn btn-success"><?php echo (isset($ques_ans_info) && !empty($ques_ans_info)) ? 'Update' : 'Submit'; ?></button> 
                    <a href="<?php echo base_url().'cms/faqs_listing/'; ?>" class="custom-class" type="button"> 
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
            var content1 = tinymce.get("answer_english").getContent();
            $("#texteditor").val(content1);
            var content2 = tinymce.get("answer_arabic").getContent();
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
                   if (objData.msg == 'success') { 
                    window.location = base_url + 'cms/faqs_listing';
                    } 
                    else 
                    {
                    $('.msg-alert').css('display', 'block');
                    $("#result").html('<div class="alert" ><div class="alert alert-domain alert-danger alert-dismissible fade in  msg-alert" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: black"><span aria-hidden="true">×</span> </button>' + objData.msg + '</div></div>');
                    }  
                }

            });
            return false; // Don't submit form for this demo
          });
  
    });
</script>
<script type="text/javascript">
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




