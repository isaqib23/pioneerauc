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
                <?php if($this->session->flashdata('error')){ ?>
                  <div class="alert alert-danger alert-dismissible">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <strong>Error!</strong> <?= $this->session->flashdata('error'); ?>
                  </div>
                <?php } ?>

                <?php if($this->session->flashdata('success')){ ?>
                  <div class="alert alert-success alert-dismissible">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <strong>Success!</strong> <?= $this->session->flashdata('success'); ?>
                  </div>
                <?php } ?>
                <?php 
                    if(isset($row) && !empty($row)) {
                        $title = json_decode($row['title']);
                        $description = json_decode($row['description']);
                        $chairman_title = json_decode($row['chairman_title']);
                        $chairman_description = json_decode($row['chairman_description']);
                        $policy_title = json_decode($row['policy_title']);
                        $policy_description = json_decode($row['policy_description']);
                        // print_r($policy_description);die();
                    }
                ?>

                <form method="post" novalidate="" id="demo-form2" name="ozform" action="" enctype="multipart/form-data" class="form-horizontal form-label-left">
                    <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
                    <?php if(isset($row) && !empty($row)){ ?>
                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                    <?php } ?>  
                     
                    <div class="item form-group">    
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="english_title">Title English 
                            <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text"   id="english_title" required="required" name="english_title" value="<?php if(isset($row) && !empty($row)) { echo $title->english; } ?>" class="form-control col-md-7 col-xs-12">
                        </div>
                    </div>

                    <div class="item form-group">    
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="arabic_title">Title Arabic 
                            <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" dir="rtl" id="arabic_title" required="required" name="arabic_title" value="<?php if(isset($row) && !empty($row)) { echo $title->arabic; } ?>" class="form-control col-md-7 col-xs-12">
                        </div>
                    </div>

                    <div class="item form-group">    
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="english_description">Description English <span class="required">*</span>
                        </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <textarea class="form-control col-md-7 col-xs-12 desc_english" name="english_description" id="english_description" ><?php if(isset($row) && !empty($row)){ echo $description->english; }?></textarea>  
                            </div>
                        <input type="hidden" name="english_description" id="texteditor">
                    </div>

                    <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="arabic_description">Description Arabic<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <textarea class="form-control col-md-7 col-xs-12 desc_arabic" name="arabic_description" id="arabic_description"><?php if(isset($row) && !empty($row)) { echo $description->arabic;
                          } ?></textarea>
                        </div>
                        <input type="hidden" name="arabic_description" id="texteditor2">
                    </div>

                    <div class="item form-group">    
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="policy_english_title">Image
                            <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="file"   id="chairman_image" name="chairman_image">
                            <?php if(isset($row)){
                                $file = $this->db->get_where('files', ['id' => $row['chairman_image']])->row_array(); ?>
                                <input type="hidden" name="c_old_file" value="<?= $file['name']; ?>">
                                <img src="<?= base_url($file['path'] . $file['name']); ?>" height="72" width="72" style="margin-top: 15px">
                            <?php } ?>
                            <input type="hidden" name="chimage" id="chimage" value="<?= isset($file) ? $file['name'] : ''; ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                        <button type="submit" id="send" class="btn btn-success"><?php echo (isset($row) && !empty($row)) ? 'Update' : 'Submit'; ?></button> 
                        <!-- <a href="<?php echo base_url().'cms/faqs_listing/'; ?>" class="custom-class" type="button"> 
                            Cancel
                        </a> -->
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $('#policy_image').on('change', function(event){
      event.preventDefault();
      var nameMe = event.target.files[0].name; 
      $('#phimage').val(nameMe); 
    });
    $('#chairman_image').on('change', function(event){
      event.preventDefault();
      var nameMe = event.target.files[0].name; 
      $('#chimage').val(nameMe); 
    });
</script>

<script type="text/javascript">
    var base_url = '<?php echo base_url();?>';
    // var formaction_path = '<?php echo (isset($ques_ans_info) && !empty($ques_ans_info)) ? 'edit' : 'add';?>';
    var formaction_path = '<?php echo $formaction_path; ?>';
    $(document).ready(function() {
         // You can use the locally-scoped $ in here as an alias to jQuery.
        $('#demo-form2').parsley().on('field:validated', function() {
            var ok = $('.parsley-error').length === 0;
          }) .on('form:submit', function() {
            $('#body').val(tinymce.activeEditor.getContent());
            var content1 = tinymce.get("english_description").getContent();
            $("#texteditor").val(content1);
            var content2 = tinymce.get("arabic_description").getContent();
            $("#texteditor2").val(content2);
            var content3 = tinymce.get("chairman_english_description").getContent();
            $("#texteditor3").val(content3);
            var content4 = tinymce.get("chairman_arabic_description").getContent();
            $("#texteditor4").val(content4);
            var content5 = tinymce.get("policy_english_description").getContent();
            $("#texteditor5").val(content5);
            var content6 = tinymce.get("chairman_arabic_description").getContent();
            $("#texteditor6").val(content6);
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
                   if (objData.error == false) { 
                    window.location = base_url + 'cms/about_us';
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




