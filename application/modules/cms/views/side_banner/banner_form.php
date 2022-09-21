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
      if(isset($banner_info) && !empty($banner_info)) {
        $title = json_decode($banner_info['title']);
        // print_r($title);die();
        $description = json_decode($banner_info['description']);
      }
      ?>
      <form method="post" novalidate="" id="demo-form" enctype="multipart/form-data" class="form-horizontal form-label-left">
        <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
         <?php if(isset($banner_info) && !empty($banner_info)){ ?>
          <input type="hidden" name="id" value="<?php echo $this->uri->segment(3); ?>">
        <?php } ?>
           
          <div class="item form-group">    
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="english_title">Title English
                  <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="text"   id="english_title" required="required" name="english_title" value="<?php if(isset($title) && !empty($title)) { echo $title->english; } ?>" class="form-control col-md-7 col-xs-12">
              </div>
          </div>
          <div class="item form-group">    
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="arabic_title">Title Arabic 
                  <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="text" dir="rtl" id="arabic_title" required="required" name="arabic_title" value="<?php if(isset($title) && !empty($title)) { echo $title->arabic; } ?>" class="form-control col-md-7 col-xs-12">
              </div>
          </div>

          <div class="item form-group">    
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="status">status 
                  <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                  <select class="form-control col-md-7 col-xs-12" name="status">
                    <option value="1" <?php if(isset($banner_info) && ($banner_info['status'] == '1')) { echo "selected"; } ?>>Active</option>
                    <option value="0" <?php if(isset($banner_info) && ($banner_info['status'] == '0')) { echo "selected"; } ?>>Inactive</option>
                  </select>
              </div>
          </div>

          <div class="item form-group">    
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="size">Size 
                  <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                  <select class="form-control col-md-7 col-xs-12" name="size">
                    <option value="big" <?php if(isset($banner_info) && ($banner_info['size'] == 'big')) { echo "selected"; } ?>>Big</option>
                    <option value="small" <?php if(isset($banner_info) && ($banner_info['size'] == 'small')) { echo "selected"; } ?>>Small</option>
                  </select>
              </div>
          </div>

          <div class="item form-group">    
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="english_description">Description English <span class="required">*</span>
              </label>
                  <div class="col-md-6 col-sm-6 col-xs-12">
                      <textarea class="form-control col-md-7 col-xs-12 desc_english"  name="english_description" id="english_description" ><?php if(isset($description) && !empty($description)){ echo $description->english; }?></textarea> 
                      <span class="text-danger english_description-error"></span> 
                  </div>
              <input type="hidden" name="english_description" id="texteditor">
          </div>
          <div class="item form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="arabic_description">Description Arabic<span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                  <textarea class="form-control col-md-7 col-xs-12 desc_arabic"  name="arabic_description" id="arabic_description"><?php if(isset($description) && !empty($description)) { echo $description->arabic;
                } ?></textarea>
                <span class="text-danger arabic_description-error"></span>
              </div>
              <input type="hidden" name="arabic_description" id="texteditor2">
          </div>

          <div class="item form-group">    
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="image">Banner Image
                  <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="file"   id="image" name="image">
               
                  <?php if(isset($banner_info)){

               $pfile = $this->db->get_where('files', ['id' => $banner_info['image']])->row_array(); 
                  ?>

                      <input type="hidden" name="p_old_file" value="<?= $pfile['name']; ?>">
                      <img src="<?= base_url($pfile['path'] . $pfile['name']); ?>" height="72" width="72" style="margin-top: 15px">
                      <!-- <img src="<?= base_url().$pfile['path'].$pfile['name'];?>"> -->
                  <?php } ?>
              </div>
          </div>

          <div class="form-group">
              <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
              <input type="submit" class="btn btn-success" value="<?php echo (isset($banner_info) && !empty($banner_info)) ? 'Update' : 'Submit'; ?>" />  
              <a href="<?php echo base_url().'cms/side_banner/'; ?>" class="custom-class" type="button"> 
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
    var formaction_path = '<?php echo $formaction_path; ?>';
    // alert(formaction_path);
    $(document).ready(function() {
         // You can use the locally-scoped $ in here as an alias to jQuery.
        $('#demo-form').parsley().on('field:validated', function() {
            var ok = $('.parsley-error').length === 0;
          }) .on('form:submit', function() {
            var content1 = tinymce.get("english_description").getContent();
            $("#texteditor").val(content1);
            var content2 = tinymce.get("arabic_description").getContent();
            $("#texteditor2").val(content2);


             var formData = new FormData($("#demo-form")[0]);
            $.ajax({
                url: base_url + 'cms/' + formaction_path,
                type: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success : function(data) {
                var objData = jQuery.parseJSON(data);
                console.log(objData);
                   if (objData.error == false) { 
                    window.location = base_url + 'cms/side_banner';
                    } 
                    else 
                    {
                    $('.msg-alert').css('display', 'block');
                    $("#result").html('<div class="alert" ><div class="alert alert-domain alert-success alert-dismissible fade in  msg-alert" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: black"><span aria-hidden="true">×</span> </button>' + objData.msg + '</div></div>');
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




