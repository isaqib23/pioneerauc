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
        
        <div id="result"></div>    <!-- for response -->

            <form method="post" novalidate="" id="demo-form2" name="ozform" action="" enctype="multipart/form-data" class="form-horizontal form-label-left">
                <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
               <?php if(isset($ques_ans_info) && !empty($ques_ans_info)){ ?>
                <input type="hidden" name="id" value="<?php echo $this->uri->segment(4); ?>">
            <?php } ?>  
                 
                <div class="item form-group">    
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title_english">Question English 
                        <span class="required">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text"   id="question_english" required="required" name="question_english" value="<?php if(isset($ques_ans_info) && !empty($ques_ans_info)) { echo $ques_ans_info[0]['question_english']; } ?>" class="form-control col-md-7 col-xs-12">
                    </div>
                </div>



                <!-- <?php print_r($image_file[0]['name']); ?> -->

                <div class="item form-group">    
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title_arabic">Question Arabic 
                        <span class="required">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" dir="rtl" id="question_arabic"  name="question_arabic" value="<?php if(isset($ques_ans_info) && !empty($ques_ans_info)) { echo $ques_ans_info[0]['question_arabic']; } ?>" class="form-control col-md-7 col-xs-12">
                    </div>
                </div> 
                

              


                <div class="item form-group">    
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
                  
                </div>
                <div class="form-group">
                    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                    <button type="submit" id="send" class="btn btn-success"><?php echo (isset($ques_ans_info) && !empty($ques_ans_info)) ? 'Update' : 'Submit'; ?></button> 
                    <a href="<?php echo base_url().'footer_content/question_answer/'; ?>" class="custom-class" type="button"> 
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
    var formaction_path = '<?php echo (isset($ques_ans_info) && !empty($ques_ans_info)) ? 'edit' : 'add';?>';
    $(document).ready(function() {
         // You can use the locally-scoped $ in here as an alias to jQuery.
        $('#demo-form2').parsley().on('field:validated', function() {
            var ok = $('.parsley-error').length === 0;
          }) .on('form:submit', function() {
             var formData = new FormData($("#demo-form2")[0]);
            $.ajax({
                url: base_url + 'footer_content/question_answer/' + formaction_path,
                type: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success : function(data) {
                var objData = jQuery.parseJSON(data);
                   if (objData.msg == 'success') { 
                    window.location = base_url + 'footer_content/question_answer';
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





