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
            if(isset($our_team_info) && !empty($our_team_info)) {
                $question = json_decode($our_team_info[0]['member_name']);
                // print_r($our_team_info);die();
                $answer = json_decode($our_team_info[0]['description']);
                $designation = json_decode($our_team_info[0]['designation']);
            }
            ?>


            <form method="post" novalidate="" id="demo-form2" name="ozform" action="" enctype="multipart/form-data" class="form-horizontal form-label-left">
                <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
                <?php if(isset($our_team_info) && !empty($our_team_info)){ ?>
                    <input type="hidden" name="id" value="<?php echo $this->uri->segment(3); ?>">
                <?php } ?>  
                 
                <div class="item form-group">    
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name_english">Member Name 
                        <span class="required">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text"   id="name_english" required="required" name="name_english" value="<?php if(isset($our_team_info) && !empty($our_team_info)) { echo $question->english; } ?>" class="form-control col-md-7 col-xs-12">
                    </div>
                </div>

                <div class="item form-group">    
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name_arabic">Member Name Arabic 
                        <span class="required">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" dir="rtl" id="name_arabic" name="name_arabic" value="<?php if(isset($our_team_info) && !empty($our_team_info)) { echo $question->arabic; } ?>" class="form-control col-md-7 col-xs-12">
                    </div>
                </div>

    <div class="item form-group">    
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="description_english">Description English <span class="required">*</span>
        </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <textarea class="form-control col-md-7 col-xs-12 desc_english" id="description_english" ><?php if(isset($our_team_info) && !empty($our_team_info)){ echo $answer->english; }?></textarea>  
            </div>
        <input type="hidden" name="description_english" id="texteditor">
    </div>

    <div class="item form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="description_arabic">Description Arabic<span class="required">*</span>
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <textarea class="form-control col-md-7 col-xs-12 desc_arabic" id="description_arabic"><?php if(isset($our_team_info) && !empty($our_team_info)) { echo $answer->arabic;
          } ?></textarea>
        </div>
        <input type="hidden" name="description_arabic" id="texteditor2">
    </div>

      <div class="item form-group">    
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="english_designation">Member Designation 
                        <span class="required">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text"   id="english_designation" required="required" name="english_designation" value="<?php if(isset($our_team_info) && !empty($our_team_info)) { echo $designation->english; } ?>" class="form-control col-md-7 col-xs-12">
                    </div>
                </div>

                <div class="item form-group">    
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="arabic_designation">Member Designation Arabic 
                        <span class="required">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" dir="rtl" id="arabic_designation" name="arabic_designation" value="<?php if(isset($our_team_info) && !empty($our_team_info)) { echo $designation->arabic; } ?>" class="form-control col-md-7 col-xs-12">
                    </div>
                </div>

              

                <div class="item form-group">    
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="policy_english_title">Member Image
                        <span class="required">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="file"   id="image" name="image">
                     
                        <?php if(isset($our_team_info)){

                     $pfile = $this->db->get_where('files', ['id' => $our_team_info[0]['image']])->row_array(); 
                        ?>

                            <input type="hidden" name="p_old_file" value="<?= $pfile['name']; ?>">
                            <img src="<?= base_url($pfile['path'] . $pfile['name']); ?>" height="72" width="72" style="margin-top: 15px">
                            <!-- <img src="<?= base_url().$pfile['path'].$pfile['name'];?>"> -->
                        <?php } ?>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                    <button type="submit" id="send" class="btn btn-success"><?php echo (isset($our_team_info) && !empty($our_team_info)) ? 'Update' : 'Submit'; ?></button> 
                    <a href="<?php echo base_url().'cms/our_team/'; ?>" class="custom-class" type="button"> 
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
    // var formaction_path = '<?php echo (isset($our_team_info) && !empty($our_team_info)) ? 'edit' : 'add';?>';
    var formaction_path = '<?php echo $formaction_path; ?>';
    $(document).ready(function() {
         // You can use the locally-scoped $ in here as an alias to jQuery.
        $('#demo-form2').parsley().on('field:validated', function() {
            var ok = $('.parsley-error').length === 0;
          }) .on('form:submit', function() {
            // $('#body').val(tinymce.activeEditor.getContent());
            var content1 = tinymce.get("description_english").getContent();
            $("#texteditor").val(content1);
            var content2 = tinymce.get("description_arabic").getContent();
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
                    if (objData.error == false) { 
                        window.location = base_url + 'cms/our_team';
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




