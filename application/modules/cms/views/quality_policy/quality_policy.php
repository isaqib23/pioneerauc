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
                $title = json_decode($row['quality_policy_title']);
                $description = json_decode($row['quality_policy_description']);
                // $chairman_title = json_decode($row['chairman_title']);
            }
            ?>


            <form method="post" novalidate="" id="quality_policy" name="ozform" action="" enctype="multipart/form-data" class="form-horizontal form-label-left">
                <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
                <?php if(isset($row) && !empty($row)){ ?>
                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                <?php } ?>  
                 
                <div class="item form-group">    
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="english_title">Title English 
                        <span class="required">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text"   id="english_title" name="english_title" value="<?php if(isset($row) && !empty($row)) { echo $title->english; } ?>" class="form-control col-md-7 col-xs-12">
                        <span class="text-danger english_title-error"></span> 
                    </div>
                </div>

                <div class="item form-group">    
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="arabic_title">Title Arabic 
                        <span class="required">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" dir="rtl" id="arabic_title" name="arabic_title" value="<?php if(isset($row) && !empty($row)) { echo $title->arabic; } ?>" class="form-control col-md-7 col-xs-12">
                        <span class="text-danger arabic_title-error"></span> 
                    </div>
                </div>

                <div class="item form-group">    
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="english_description">Description English <span class="required">*</span>
                    </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <textarea class="form-control col-md-7 col-xs-12 desc_english" name="english_description" id="english_description" ><?php if(isset($row) && !empty($row)){ echo $description->english; }?></textarea> 
                            <span class="text-danger english_description-error"></span> 
                        </div>
                    <input type="hidden" name="english_description" id="texteditor">
                </div>

                <div class="item form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="arabic_description">Description Arabic<span class="required">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <textarea class="form-control col-md-7 col-xs-12 desc_arabic" name="arabic_description" id="arabic_description"><?php if(isset($row) && !empty($row)) { echo $description->arabic;
                      } ?></textarea>
                      <span class="text-danger arabic_description-error"></span>
                    </div>
                    <input type="hidden" name="arabic_description" id="texteditor2">
                </div>

                <!-- <div class="item form-group">    
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="policy_english_title">Quality Policy Image
                        <span class="required">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="file"   id="image" name="image">
                        <?php //if(isset($row)){
                           // $file = $this->db->get_where('files', ['id' => $row['quality_policy_image']])->row_array(); ?>
                            <input type="hidden" name="old_file" value="<?//= $file['name']; ?>">
                            <img src="<?//= base_url($file['path'] . $file['name']); ?>" height="72" width="72" style="margin-top: 15px">
                        <?php //} ?>
                        <input type="hidden" name="himage" id="himage" required="required" value="<?//= isset($file) ? $file['name'] : ''; ?>">
                    </div>
                </div> -->
                <div class="form-group">
                    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                    <button type="button" id="send" class="btn btn-success"><?php echo (isset($row) && !empty($row)) ? 'Update' : 'Submit'; ?></button> 
                  
                    </div>
                </div>

            </form>
        </div>
        </div>
    </div>
</div>

<script>
    $('#image').on('change', function(event){
      event.preventDefault();
      var nameMe = event.target.files[0].name; 
      $('#himage').val(nameMe); 
    });
</script>

<script type="text/javascript">
    var base_url = '<?php echo base_url();?>';
    var formaction_path = '<?php echo $formaction_path; ?>';
    $(document).ready(function() {
        $('#send').on('click', function(event){

            event.preventDefault();
            var validation = false;
            var errorMsg = "This value is required.";
            var e;
            selectedinputs = ['english_title','arabic_title'];
            $.each(selectedinputs, function(index, value){
              var e = ($('input[name='+value+']'));
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
                selectedtextarea = ['english_description','arabic_description'];
                $.each(selectedtextarea, function(index, value){
                  var e = (tinymce.get(""+value+""));
                  if(($.trim(e.getContent()) == "") || (e.getContent() == null) || (typeof e.getContent() === 'undefined')){
                    e.focus();
                    $('.'+value+'-error').html(errorMsg).show();
                    validation = false;
                    return false;
                  }else{
                    validation = true;
                    $('.'+value+'-error').html('').hide();
                //     // $('.'+value+'-error').html('').hide();
                  }
                });
            }

            $('#body').val(tinymce.activeEditor.getContent());
            var content1 = tinymce.get("english_description").getContent();
            $("#texteditor").val(content1);
            var content2 = tinymce.get("arabic_description").getContent();
            $("#texteditor2").val(content2);
            if (validation == true) {
                var formData = new FormData($("#quality_policy")[0]);
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
                        window.location = base_url + 'cms/quality_policy';
                        }else{
                            $('.msg-alert').css('display', 'block');
                            $("#result").html('<div class="alert" ><div class="alert alert-domain alert-danger alert-dismissible fade in  msg-alert" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: black"><span aria-hidden="true">×</span> </button>' + objData.msg + '</div></div>');
                            window.scrollTo({top: 0, behavior: "smooth"});
                        }  
                    }

                });
            }
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




