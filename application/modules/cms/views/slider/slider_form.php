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
      if(isset($slider_info) && !empty($slider_info)) {
        $smal_title = json_decode($slider_info[0]['small_title']);
        $title = json_decode($slider_info[0]['title']);
        // print_r($title);die();
        $description = json_decode($slider_info[0]['description']);
        $mobile_description = json_decode($slider_info[0]['mobile_description']);
      }
      ?>
      <form method="post" novalidate="" id="demo-form" enctype="multipart/form-data" class="form-horizontal form-label-left">
        <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
         <?php if(isset($slider_info) && !empty($slider_info)){ ?>
          <input type="hidden" name="id" value="<?php echo $this->uri->segment(3); ?>">
        <?php } ?>  
          <div class="item form-group">    
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="english_heading"> Small Title English 
                <span class="required"></span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text"   id="english_heading"  name="small_english_heading" value="<?php if(isset($smal_title) && !empty($smal_title)) { echo $smal_title->english; } ?>" class="form-control col-md-7 col-xs-12">
            </div>
          </div>
          <div class="item form-group">    
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="small_arabic_title">Small Title Arabic 
                  <span class="required"></span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="text" dir="rtl" id="small_arabic_title"  name="small_arabic_title" value="<?php if(isset($smal_title) && !empty($smal_title)) { echo $smal_title->arabic; } ?>" class="form-control col-md-7 col-xs-12">
              </div>
          </div>
           
          <div class="item form-group">    
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="english_title">Title English
                  <span class="required"></span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="text"   id="english_title"  name="english_title" value="<?php if(isset($title) && !empty($title)) { echo $title->english; } ?>" class="form-control col-md-7 col-xs-12">
              </div>
          </div>
          <div class="item form-group">    
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="arabic_title">Title Arabic 
                  <span class="required"></span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="text" dir="rtl" id="arabic_title"  name="arabic_title" value="<?php if(isset($title) && !empty($title)) { echo $title->arabic; } ?>" class="form-control col-md-7 col-xs-12">
              </div>
          </div>

          <div class="item form-group">    
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="link">Banner Link 
                  <span class="required"></span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="text" id="link"  name="link" value="<?php if(isset($slider_info) && !empty($slider_info)) { echo $slider_info[0]['link']; } ?>" class="form-control col-md-7 col-xs-12">
              </div>
          </div>

          <div class="item form-group">    
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="sort_order">Sort Order 
                  <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="text" id="sort_order" required="required" name="sort_order" value="<?php if(isset($slider_info) && !empty($slider_info)) { echo $slider_info[0]['sort_order']; } ?>" class="form-control col-md-7 col-xs-12">
              </div>
          </div>

           <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="category_id">Slider Category <span class="required">*</span></label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <select class="form-control col-md-7 col-xs-12" id="category_id" name="category_id" required="required" >
                         <option >Select Category</option>
                         <?php foreach ($sliderCategory as $sliderCategorys) { 
                            $categoryTitle = json_decode($sliderCategorys['title']);
                            ?>
                        <option 
                        <?php if(isset($slider_info) && !empty($slider_info)){
                            if($slider_info[0]['category_id'] == $sliderCategorys['id']){ echo 'selected';}
                        }?>
                            value="<?php echo $sliderCategorys['id']; ?>"><?php echo $categoryTitle->english; ?></option>
                            <?php  } ?> 
                          
                    </select>
                </div>
                </div>

          <div class="item form-group">    
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="status">status 
                  <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                  <select class="form-control col-md-7 col-xs-12" name="status" required="required">
                    <option value="active" <?php if(isset($slider_info) && ($slider_info[0]['status'] == 'active')) { echo "selected"; } ?>>Active</option>
                    <option value="inactive" <?php if(isset($slider_info) && ($slider_info[0]['status'] == 'inactive')) { echo "selected"; } ?>>Inactive</option>
                  </select>
              </div>
          </div>

          <div class="item form-group">    
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="start_date">Start Date Time <span class="required">*</span>
            </label>

            <div class="col-md-6 col-sm-6 col-xs-12  ">
              <div class='input-group date' id='start_date'>
                <input type="text" readonly class="form-control" name="start_date" placeholder="From" value="<?php if(isset($slider_info) && !empty($slider_info)) { echo date('Y-m-d H:i A',strtotime($slider_info[0]['start_date'])); } ?>" required />
                <span class="input-group-addon">
                  <span class="glyphicon glyphicon-calendar"></span>
                </span>
              </div>
            </div>
          </div>  

          <div class="item form-group">    
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="end_date">Expiry Date Time <span class="required">*</span>
            </label>

            <div class="col-md-6 col-sm-6 col-xs-12  ">
              <div class='input-group date' id='end_date'>
                <input type="text" readonly class="form-control" name="end_date" placeholder="To" value="<?php if(isset($slider_info) && !empty($slider_info)) { echo date('Y-m-d H:i A',strtotime($slider_info[0]['end_date'])); } ?>" required />
                <span class="input-group-addon">
                  <span class="glyphicon glyphicon-calendar"></span>
                </span>
              </div>
            </div>
          </div>  

          <div class="item form-group">    
              <label class="control-label col-md-2 col-sm-3 col-xs-12" for="english_description">Description English <span class="required"></span>
              </label>
                  <div class="col-md-10 col-sm-6 col-xs-12">
                      <textarea class="form-control col-md-7 col-xs-12 desc_english"  name="english_description" id="english_description" ><?php if(isset($description) && !empty($description)){ echo $description->english; }?></textarea> 
                      <span class="text-danger english_description-error"></span> 
                  </div>
              <input type="hidden" name="english_description" id="texteditor">
          </div>
          <div class="item form-group">
              <label class="control-label col-md-2 col-sm-3 col-xs-12" for="arabic_description">Description Arabic<span class="required"></span>
              </label>
              <div class="col-md-10 col-sm-6 col-xs-12">
                  <textarea class="form-control col-md-7 col-xs-12 desc_arabic"  name="arabic_description" id="arabic_description"><?php if(isset($description) && !empty($description)) { echo $description->arabic;
                } ?></textarea>
                <span class="text-danger arabic_description-error"></span>
              </div>
              <input type="hidden" name="arabic_description" id="texteditor2">
          </div>



          <div class="item form-group">    
              <label class="control-label col-md-2 col-sm-3 col-xs-12" for="mobile_english_description">Mobile Description English <span class="required"></span>
              </label>
                  <div class="col-md-10 col-sm-6 col-xs-12">
                      <textarea class="form-control col-md-7 col-xs-12 desc_english"  name="mobile_english_description" id="mobile_english_description" ><?php if(isset($mobile_description) && !empty($mobile_description)){ echo $mobile_description->english; }?></textarea> 
                      <span class="text-danger mobile_english_description-error"></span> 
                  </div>
              <input type="hidden" name="mobile_english_description" id="mobile_texteditor">
          </div>
          <div class="item form-group">
              <label class="control-label col-md-2 col-sm-3 col-xs-12" for="mobile_arabic_description">Mobile Description Arabic<span class="required"></span>
              </label>
              <div class="col-md-10 col-sm-6 col-xs-12">
                  <textarea class="form-control col-md-7 col-xs-12 desc_arabic"  name="mobile_arabic_description" id="mobile_arabic_description"><?php if(isset($mobile_description) && !empty($mobile_description)) { echo $mobile_description->arabic;
                } ?></textarea>
                <span class="text-danger mobile_arabic_description-error"></span>
              </div>
              <input type="hidden" name="mobile_arabic_description" id="mobile_texteditor2">
          </div>


          <div class="row">
            <legend>Desktop Banner</legend>
            <div class=" form-group col-md-6">    
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="image">
                Slider English
                  <span class="required"></span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                  <?php if(isset($slider_info)){

               $pfile = $this->db->get_where('files', ['id' => $slider_info[0]['image']])->row_array(); 
                  ?>

                      <input type="hidden" name="p_old_file" value="<?= $pfile['name']; ?>">
                      <img src="<?= base_url($pfile['path'] . $pfile['name']); ?>" height="72" width="72" style="margin-top: 15px">
                      <!-- <img src="<?= base_url().$pfile['path'].$pfile['name'];?>"> -->
                  <?php } ?>
                 <br/>
                   <input type="file"   id="image" name="image">
              </div>
          </div>


          <div class=" form-group col-md-6">    
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="image_arabic">Slider Arabic 
                  <span class="required"></span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
               
                  <?php if(isset($slider_info)){

               $pfile = $this->db->get_where('files', ['id' => $slider_info[0]['image_arabic']])->row_array(); 
                  ?>

                      <input type="hidden" name="p_old_file_arabic" value="<?= $pfile['name']; ?>">
                      <img src="<?= base_url($pfile['path'] . $pfile['name']); ?>" height="72" width="72" style="margin-top: 15px">
                      <!-- <img src="<?= base_url().$pfile['path'].$pfile['name'];?>"> -->
                  <?php } ?>
                 <br/>
                 <input type="file"   id="image_arabic" name="image_arabic">

              </div>
          </div>
        </div>


        <div class="row">
            <legend>Mobile Banner</legend>
            <div class=" form-group col-md-6">    
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="mobile_image_english">
                Slider English
                  <span class="required"></span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                  <?php if(isset($slider_info)){

               $pfile = $this->db->get_where('files', ['id' => $slider_info[0]['mobile_image_english']])->row_array(); 
                  ?>

                      <input type="hidden" name="p_old_file_mobile_image_english" value="<?= $pfile['name']; ?>">
                      <img src="<?= base_url($pfile['path'] . $pfile['name']); ?>" height="72" width="72" style="margin-top: 15px">
                      <!-- <img src="<?= base_url().$pfile['path'].$pfile['name'];?>"> -->
                  <?php } ?>
                 <br/>
                   <input type="file"   id="mobile_image_english" name="mobile_image_english">
              </div>
          </div>


          <div class=" form-group col-md-6">    
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="mobile_image_arabic">Slider Arabic 
                  <span class="required"></span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
               
                  <?php if(isset($slider_info)){

               $pfile = $this->db->get_where('files', ['id' => $slider_info[0]['mobile_image_arabic']])->row_array(); 
                  ?>

                      <input type="hidden" name="p_old_file_mobile_image_arabic" value="<?= $pfile['name']; ?>">
                      <img src="<?= base_url($pfile['path'] . $pfile['name']); ?>" height="72" width="72" style="margin-top: 15px">
                      <!-- <img src="<?= base_url().$pfile['path'].$pfile['name'];?>"> -->
                  <?php } ?>
                 <br/>
                 <input type="file"   id="mobile_image_arabic" name="mobile_image_arabic">

              </div>
          </div>
        </div>



          <div class="form-group">
              <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
              <input type="submit" id="submit" class="btn btn-success" value="<?php echo (isset($slider_info) && !empty($slider_info)) ? 'Update' : 'Submit'; ?>" />  
              <a href="<?php echo base_url().'cms/slider/'; ?>" class="custom-class" type="button"> 
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

  $(function () { 
    $('#start_date').datetimepicker({ 
        maxDate: "<?php if(isset($slider_info) && !empty($slider_info)) { echo date('Y-m-d H:i',strtotime($slider_info[0]['end_date'])); }else{ echo date('Y-m-d H:i', strtotime('+2 month'));} ?>",
        minDate: "<?php if(isset($slider_info) && !empty($slider_info)) { echo date('Y-m-d H:i',strtotime($slider_info[0]['start_date'])); }else{ echo date('Y-m-d H:i');}  ?>",
        format: 'YYYY-MM-DD HH:mm',
        <?php if(isset($slider_info) && !empty($slider_info)) { ?>
        useCurrent: false, //Important! See issue #1075 
        <?php }?>
        ignoreReadonly : true
    });
    $('#end_date').datetimepicker({
        useCurrent: false, //Important! See issue #1075 
        minDate: "<?php if(isset($slider_info) && !empty($slider_info)) { echo date('Y-m-d H:i',strtotime($slider_info[0]['start_date'])); }else{ echo date('Y-m-d H:i');} ?>",
       format: 'YYYY-MM-DD HH:mm',
        ignoreReadonly : true
       
    });
    $("#start_date").on("dp.change", function (e) {
        $('#end_date').data("DateTimePicker").minDate(e.date);
    });
    $("#end_date").on("dp.change", function (e) {
        $('#start_date').data("DateTimePicker").maxDate(e.date);
    });
  });


    var base_url = '<?php echo base_url();?>';
    // var formaction_path = '<?php echo (isset($slider_info) && !empty($slider_info)) ? 'edit' : 'add';?>';
    var formaction_path = '<?php echo $formaction_path; ?>';
    // alert(formaction_path);
    $(document).ready(function() {
         // You can use the locally-scoped $ in here as an alias to jQuery.
        // $('#demo-form').parsley().on('field:validated', function() {
        //     var ok = $('.parsley-error').length === 0;
        //   }) .on('form:submit', function() {
           $('#demo-form').on('submit', function(e) {
             e.preventDefault();

            var content1 = tinymce.get("english_description").getContent();
            $("#texteditor").val(content1);
            var content2 = tinymce.get("arabic_description").getContent();
            $("#texteditor2").val(content2);

            var mobile_content1 = tinymce.get("mobile_english_description").getContent();
            $("#mobile_texteditor").val(mobile_content1);
            var mobile_content2 = tinymce.get("mobile_arabic_description").getContent();
            $("#mobile_texteditor2").val(mobile_content2);


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
                    window.location = base_url + 'cms/slider';
                    } 
                    else 
                    {
                    $('.msg-alert').css('display', 'block');
                    $("#result").html('<div class="alert" ><div class="alert alert-domain alert-success alert-dismissible fade in  msg-alert" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: black"><span aria-hidden="true">×</span> </button>' + objData.msg + '</div></div>');
                    window.location = base_url + 'cms/slider';
                    }  
                }

            });
            return false; // Don't submit form for this demo
          });
  
    });
</script>
 <script src="https://cdn.tiny.cloud/1/5g31a9fz7mrk2qklgqpzbxmmyoxipk3tbh8tr2nqm9k5zx3t/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>


<script type="text/javascript">



var useDarkMode = window.matchMedia('(prefers-color-scheme: dark)').matches;

tinymce.init({
   selector: '.desc_arabic',
  //selector: 'textarea#english_description',
  plugins: 'print preview paste importcss searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount imagetools textpattern noneditable help charmap quickbars emoticons',
  imagetools_cors_hosts: ['picsum.photos'],
  menubar: 'file edit view insert format tools table help',
  toolbar: 'undo redo | bold italic underline strikethrough | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist | forecolor backcolor removeformat | pagebreak | charmap emoticons | fullscreen  preview save print | insertfile template link anchor codesample | ltr rtl',
  font_formats: 'Montserrat=montserrat,sans-serif;Tajawal=tajawal,sans-serif;Andale Mono=andale mono,times; Arial=arial,helvetica,sans-serif; Arial Black=arial black,avant garde; Book Antiqua=book antiqua,palatino; Comic Sans MS=comic sans ms,sans-serif; Courier New=courier new,courier; Georgia=georgia,palatino; Helvetica=helvetica; Impact=impact,chicago; Oswald=oswald; Symbol=symbol; Tahoma=tahoma,arial,helvetica,sans-serif; Terminal=terminal,monaco; Times New Roman=times new roman,times; Trebuchet MS=trebuchet ms,geneva; Verdana=verdana,geneva; Webdings=webdings; Wingdings=wingdings,zapf dingbats',
  content_css: ['https://fonts.googleapis.com/css?family=Tajawal'],
  content_style: "body { font-family: 'Tajawal', sans-serif; }",

  toolbar_sticky: true,
  autosave_ask_before_unload: true,
  autosave_interval: '30s',
  autosave_prefix: '{path}{query}-{id}-',
  autosave_restore_when_empty: false,
  autosave_retention: '2m',
  image_advtab: true,
  link_list: [
    { title: 'My page 1', value: 'https://www.tiny.cloud' },
    { title: 'My page 2', value: 'http://www.moxiecode.com' }
  ],
  image_list: [
    { title: 'My page 1', value: 'https://www.tiny.cloud' },
    { title: 'My page 2', value: 'http://www.moxiecode.com' }
  ],
  image_class_list: [
    { title: 'None', value: '' },
    { title: 'Some class', value: 'class-name' }
  ],
  importcss_append: true,
  file_picker_callback: function (callback, value, meta) {
    /* Provide file and text for the link dialog */
    if (meta.filetype === 'file') {
      callback('https://www.google.com/logos/google.jpg', { text: 'My text' });
    }

    /* Provide image and alt text for the image dialog */
    if (meta.filetype === 'image') {
      callback('https://www.google.com/logos/google.jpg', { alt: 'My alt text' });
    }

    /* Provide alternative source and posted for the media dialog */
    if (meta.filetype === 'media') {
      callback('movie.mp4', { source2: 'alt.ogg', poster: 'https://www.google.com/logos/google.jpg' });
    }
  },
  templates: [
        { title: 'New Table', description: 'creates a new table', content: '<div class="mceTmpl"><table width="98%%"  border="0" cellspacing="0" cellpadding="0"><tr><th scope="col"> </th><th scope="col"> </th></tr><tr><td> </td><td> </td></tr></table></div>' },
    { title: 'Starting my story', description: 'A cure for writers block', content: 'Once upon a time...' },
    { title: 'New list with dates', description: 'New List with dates', content: '<div class="mceTmpl"><span class="cdate">cdate</span><br /><span class="mdate">mdate</span><h2>My List</h2><ul><li></li><li></li></ul></div>' }
  ],
  template_cdate_format: '[Date Created (CDATE): %m/%d/%Y : %H:%M:%S]',
  template_mdate_format: '[Date Modified (MDATE): %m/%d/%Y : %H:%M:%S]',
  height: 350,
  image_caption: true,
  quickbars_selection_toolbar: 'bold italic | quicklink h2 h3 blockquote quickimage quicktable',
  noneditable_noneditable_class: 'mceNonEditable',
  toolbar_mode: 'sliding',
  contextmenu: 'link image imagetools table',
  skin: useDarkMode ? 'oxide-dark' : 'oxide',
  directionality: 'rtl',
 });


tinymce.init({
  selector: '.desc_english',
 // selector: 'textarea#english_description',
  plugins: 'print preview paste importcss searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount imagetools textpattern noneditable help charmap quickbars emoticons',
  imagetools_cors_hosts: ['picsum.photos'],
  menubar: 'file edit view insert format tools table help',
  toolbar: 'undo redo | bold italic underline strikethrough | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist | forecolor backcolor removeformat | pagebreak | charmap emoticons | fullscreen  preview save print | insertfile template link anchor codesample | ltr rtl',
  font_formats: 'Montserrat=montserrat,sans-serif;Tajawal=tajawal,sans-serif;Andale Mono=andale mono,times; Arial=arial,helvetica,sans-serif; Arial Black=arial black,avant garde; Book Antiqua=book antiqua,palatino; Comic Sans MS=comic sans ms,sans-serif; Courier New=courier new,courier; Georgia=georgia,palatino; Helvetica=helvetica; Impact=impact,chicago; Oswald=oswald; Symbol=symbol; Tahoma=tahoma,arial,helvetica,sans-serif; Terminal=terminal,monaco; Times New Roman=times new roman,times; Trebuchet MS=trebuchet ms,geneva; Verdana=verdana,geneva; Webdings=webdings; Wingdings=wingdings,zapf dingbats',
  content_css: ['https://fonts.googleapis.com/css?family=Montserrat'],
  content_style: "body { font-family: 'Montserrat', sans-serif; }",

  toolbar_sticky: true,
  autosave_ask_before_unload: true,
  autosave_interval: '30s',
  autosave_prefix: '{path}{query}-{id}-',
  autosave_restore_when_empty: false,
  autosave_retention: '2m',
  image_advtab: true,
  link_list: [
    { title: 'My page 1', value: 'https://www.tiny.cloud' },
    { title: 'My page 2', value: 'http://www.moxiecode.com' }
  ],
  image_list: [
    { title: 'My page 1', value: 'https://www.tiny.cloud' },
    { title: 'My page 2', value: 'http://www.moxiecode.com' }
  ],
  image_class_list: [
    { title: 'None', value: '' },
    { title: 'Some class', value: 'class-name' }
  ],
  importcss_append: true,
  file_picker_callback: function (callback, value, meta) {
    /* Provide file and text for the link dialog */
    if (meta.filetype === 'file') {
      callback('https://www.google.com/logos/google.jpg', { text: 'My text' });
    }

    /* Provide image and alt text for the image dialog */
    if (meta.filetype === 'image') {
      callback('https://www.google.com/logos/google.jpg', { alt: 'My alt text' });
    }

    /* Provide alternative source and posted for the media dialog */
    if (meta.filetype === 'media') {
      callback('movie.mp4', { source2: 'alt.ogg', poster: 'https://www.google.com/logos/google.jpg' });
    }
  },
  templates: [
        { title: 'New Table', description: 'creates a new table', content: '<div class="mceTmpl"><table width="98%%"  border="0" cellspacing="0" cellpadding="0"><tr><th scope="col"> </th><th scope="col"> </th></tr><tr><td> </td><td> </td></tr></table></div>' },
    { title: 'Starting my story', description: 'A cure for writers block', content: 'Once upon a time...' },
    { title: 'New list with dates', description: 'New List with dates', content: '<div class="mceTmpl"><span class="cdate">cdate</span><br /><span class="mdate">mdate</span><h2>My List</h2><ul><li></li><li></li></ul></div>' }
  ],
  template_cdate_format: '[Date Created (CDATE): %m/%d/%Y : %H:%M:%S]',
  template_mdate_format: '[Date Modified (MDATE): %m/%d/%Y : %H:%M:%S]',
  height: 350,
  image_caption: true,
  quickbars_selection_toolbar: 'bold italic | quicklink h2 h3 blockquote quickimage quicktable',
  noneditable_noneditable_class: 'mceNonEditable',
  toolbar_mode: 'sliding',
  contextmenu: 'link image imagetools table',
  skin: useDarkMode ? 'oxide-dark' : 'oxide',
  //content_css: useDarkMode ? 'dark' : 'default',
 });



/*

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
    });*/
</script>




