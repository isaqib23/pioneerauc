<style type="text/css">
    .dz-image img{
    max-width: 120px;
    max-height: 120px;
    }
</style>

<script src="<?php echo base_url()?>assets_admin/vendors/jquery.tagsinput/src/jquery.tagsinput.js"></script>

<input type="hidden" id="base_url" value="<?php echo base_url(); ?>">

<div class="clearfix"></div>

<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>
                <?php  echo $small_title; ?></h2>

            <div class="clearfix"></div>
        </div>
        <div class="x_content">
        <div id="result"></div>

            <?php if($this->session->flashdata('error')){ ?>
            <div class="alert">
                <div class="alert alert-domain alert-danger alert-dismissible fade in" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: black"><span aria-hidden="true">×</span>
                      </button>
                    <?php echo $this->session->flashdata('error'); ?>
                </div>
            </div>
            <?php }?>
           
            <form method="post" novalidate="" id="demo-form2" name="ozform" action="" enctype="multipart/form-data" class="form-horizontal form-label-left">
                <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
                <?php if(isset($auction_info) && !empty($auction_info)){ ?>
                    <input type="hidden" name="id" value="<?php echo $this->uri->segment(3); ?>">
                    <input type="hidden" name="access_type" value="live">
                <?php } ?>  
                

                <div class="item form-group">    
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="access_type">Auction Type <span class="required">*</span>
                    </label>
                    
                    <div class="col-md-6 col-sm-6 col-xs-12"> 
                        <select required class="form-control" id="access_type" name="access_type">
                            <?php $access_type_arr = explode(",",$auction_info[0]['access_type']); ?>
                            <option value="">Choose auction type</option>
                            <option <?php if(isset($auction_info) && !empty($auction_info) && in_array('live', $access_type_arr)){ echo 'selected'; }?> value="live">Live</option>

                        </select>
                    </div>
                </div>



    
                <div class="item form-group close_auction_user_list" style="display: none;">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="close_auction_users">Buyer List <span class="required">*</span></label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <select class="form-control col-md-7 col-xs-12 buyer_user_list" multiple id="close_auction_users" name="close_auction_users[]" required >
                             <option disabled value="">Select Buyers</option>
                             <?php foreach ($customer_list as $customer_value) { 
                                if(isset($auction_info[0]['close_auction_users']) && !empty($auction_info[0]['close_auction_users']))
                                {
                                    $user_list_array = explode(",",$auction_info[0]['close_auction_users']);
                                }
                                ?>
                            <option 
                            <?php if(isset($auction_info) && !empty($auction_info)){
                                if(isset($user_list_array) && !empty($user_list_array) && in_array($customer_value['id'],$user_list_array)){ echo 'selected';}
                            }?>
                                value="<?php echo $customer_value['id']; ?>"><?php echo $customer_value['fname']; ?></option>
                                <?php  } ?> 
                              
                        </select>
                    </div>
                </div>
                     
                <div class="item form-group">   
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Auction Title <span class="required">*</span>
                        </label>
                        
                   
                    <div class="col-md-6 col-sm-6 col-xs-12">
                  <!--  <?php foreach ($auction_info as $key => $value) {
                        $auctionInfo = json_decode($value['title']);
                    } ?>  -->
                        <input type="text"   id="title" required="required" name="title_english" value="<?php if(isset($auction_info) && !empty($auction_info)) { echo $auctionInfo->english; } ?>" class="form-control col-md-7 col-xs-12">
                    </div>
                </div>

                <div class="item form-group">  
                <!-- <?php foreach ($auction_info as $key => $value) {
                        $auctionInfo = json_decode($value['title']);
                    } ?>   -->
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Arabic Title <span class="required">*</span>
                        </label>

                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" dir="rtl"  id="title_arabic" required="required" name="title_arabic" value="<?php if(isset($auction_info) && !empty($auction_info)) { echo $auctionInfo->arabic; } ?>" class="form-control col-md-7 col-xs-12">
                    </div>
                </div>


                <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="category_id">Item Category <span class="required">*</span></label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <select class="form-control col-md-7 col-xs-12" id="category_id" name="category_id" >
                         <!-- <option >Select Category</option> -->
                         <!-- <option >Select Category</option> -->
                         <?php foreach ($category_list as $category_value) { 
                            $categoryTitle = json_decode($category_value['title']);
                            ?>
                        <option 
                        <?php if(isset($auction_info) && !empty($auction_info)){
                            if($auction_info[0]['category_id'] == $category_value['id']){ echo 'selected';}
                        }?>
                            value="<?php echo $category_value['id']; ?>"><?php echo $categoryTitle->english; ?></option>
                            <?php  } ?> 
                          
                    </select>
                </div>
                </div>

                <!-- <div class="item form-group categories_case">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="subcategory_id">Item Sub Category</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <select class="form-control col-md-7 col-xs-12" id="subcategory_id" name="subcategory_id">

                        </select>
                    </div>
                </div> -->
                
                 <div class="item form-group">    
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="registration_no">Auction Code <span class="required">*</span>
                        </label>

                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" id="registration_no"  data-parsley-trigger="focusin focusout" data-parsley-type="alphanum" <?php if(isset($auction_info) && !empty($auction_info)) { echo ''; }else{ echo 'data-parsley-remote="'.base_url().'auction/validate_check_registration_no/{value}" data-parsley-remote-validator="mycustom_code" data-parsley-remote-message="Code already exists"'; } ?>  required="required" name="registration_no" value="<?php if(isset($auction_info) && !empty($auction_info)) { echo $auction_info[0]['registration_no']; } ?>" class="form-control col-md-7 col-xs-12">
                    </div>
                </div>

                 <!-- <div class="item form-group">    
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="security">Security % 
                        </label>

                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" id="security"  data-parsley-trigger="focusin focusout" data-parsley-type="digits" name="security" value="<?php //if(isset($auction_info) && !empty($auction_info)) { echo $auction_info[0]['security']; } ?>" class="form-control col-md-7 col-xs-12">
                    </div>
                </div> -->
 

                <div class="item form-group">    
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="start_time">Start Date Time <span class="required">*</span>
                    </label>

                    <div class="col-md-6 col-sm-6 col-xs-12  ">
                        <div class='input-group date' >
                          <input type="text" readonly class="form-control" id='start_time' name="start_time" placeholder="From" value="<?php if(isset($auction_info) && !empty($auction_info)) { echo date('Y-m-d H:i A',strtotime($auction_info[0]['start_time'])); } ?>" required />
                          <!-- <span class="input-group-addon">
                             <span class="glyphicon glyphicon-calendar"></span>
                          </span> -->
                        </div>
                    </div>
                </div>  

                <div class="item form-group">    
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="expiry_time">Expiry Date Time <span class="required">*</span>
                    </label>

                    <div class="col-md-6 col-sm-6 col-xs-12  ">
                        <div class='input-group date' >
                          <input type="text" readonly class="form-control" id='expiry_time' name="expiry_time" placeholder="To" value="<?php if(isset($auction_info) && !empty($auction_info)) { echo date('Y-m-d H:i A',strtotime($auction_info[0]['expiry_time'])); } ?>" required />
                          <!-- <span class="input-group-addon">
                             <span class="glyphicon glyphicon-calendar"></span>
                          </span> -->
                        </div>
                    </div>
                </div>  
                
                <div class="item form-group">    
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="detail_english">Detail English 
                        </label>
                        <?php if (!empty($auction_info)) {
                            $detail = json_decode($auction_info[0]['detail']);
                         }?>

                    <div class="col-md-6 col-sm-6 col-xs-12">

                      <textarea data-parsley-no-focus class="form-control col-md-7 col-xs-12 desc_english"  name="detail_english" id="detail_english" ><?php if(isset($auction_info) && !empty($auction_info)){ echo $detail->english; }?></textarea> 
              <input type="hidden" name="detail_english" id="texteditor">


                      
                    </div>
                </div>

                 <div class="item form-group">    
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="detail_arabic">Detail Arabic 
                        </label>
                        <?php if (!empty($auction_info)) {
                            $detail = json_decode($auction_info[0]['detail']);
                         }?>
                    <div class="col-md-6 col-sm-6 col-xs-12">

                      <textarea data-parsley-no-focus class="form-control col-md-7 col-xs-12 desc_arabic"  name="detail_arabic" id="detail_arabic" ><?php if(isset($auction_info) && !empty($auction_info)){ echo $detail->arabic; }?></textarea> 
              <input type="hidden" name="detail_arabic" id="texteditor2">


                      
                    </div>
                </div>



                 <div class="item form-group">    
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="popup_message_english">Popup Message English 
                        </label>
                        <?php 
                        if (!empty($auction_info)) {
                            @$popup_message = json_decode($auction_info[0]['popup_message']);
                         }?>

                    <div class="col-md-6 col-sm-6 col-xs-12">

                      <textarea data-parsley-no-focus class="form-control col-md-7 col-xs-12 "  name="popup_message_english" id="popup_message_english" ><?php if(isset($auction_info) && !empty($auction_info)){ echo @$popup_message->english; }?></textarea> 
             <!--  <input data-parsley-no-focus type="hidden" name="popup_message_english" id="texteditor3">
 -->

                      
                    </div>
                </div>

                 <div class="item form-group">    
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="popup_message_arabic">Popup Message Arabic 
                        </label>
                        <?php if (!empty($auction_info)) {
                            @$popup_message = json_decode($auction_info[0]['popup_message']);
                         }?>
                    <div class="col-md-6 col-sm-6 col-xs-12">

                      <textarea style="direction:rtl" data-parsley-no-focus class="form-control col-md-7 col-xs-12 "  name="popup_message_arabic" id="popup_message_arabic" ><?php if(isset($auction_info) && !empty($auction_info)){ echo @$popup_message->arabic; }?></textarea> 
            <!--   <input data-parsley-no-focus type="hidden" name="popup_message_arabic" id="texteditor4"> -->


                      
                    </div>
                </div>



               

                <div class="item form-group">    
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="status">Status 
                    <span class="required">*</span>
                </label>                
                <div class="col-md-6 col-sm-6 col-xs-12"> 
                    <select required="" class="form-control select2" id="status" name="status">
                        <option <?php if(isset($auction_info) && !empty($auction_info) && $auction_info[0]['status'] == 'active'){ echo 'selected'; }?> value="active">Active</option>
                        <option <?php if(isset($auction_info) && !empty($auction_info) && $auction_info[0]['status'] == 'inactive'){ echo 'selected'; }?> value="inactive">In Active</option>
                    </select>
                </div>
                </div>

                <div class="form-group">
                    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                    <button type="submit" id="send" class="btn btn-success">Submit</button> 
                    <a href="<?php echo base_url().'auction/live'; ?>" class="custom-class" type="button"> 
                        Cancel
                    </a>

                    </div>
                </div>

            </form>
        </div>
    </div>
</div>


<script>
    var token_name = '<?= $this->security->get_csrf_token_name();?>';
    var token_value = '<?=$this->security->get_csrf_hash();?>';

    function init_TagsInput2() 
    {
        if(typeof $.fn.tagsInput !== 'undefined'){  
            $('#bid_options').tagsInput({
              width: 'auto',
              defaultText:'add button',
            });
        }     
    }

     let value_option = '';
        window.Parsley.addAsyncValidator('mycustom_code', function (xhr) {
        var obj = this.$element;
        value_option = obj.val();

        var response = xhr.responseText;
        if (response === '200') {
            // success if not exists
        return 200 === xhr.status;
        } else {
            // error if already exists
        return 404 === xhr.status;
        }

        }); 


    $('.select2').select2();
    $('.buyer_user_list').select2();

        // show hide user list if closed select
    get_access_type(); // call user list show hide funciton 
    function get_access_type()
    {
        if ($("#access_type option[value=closed]:selected").length > 0){
             $('.close_auction_user_list').show();
             $('#close_auction_users').attr('disabled',false);
            $('#close_auction_users').attr('required',true);
        }
        else{
            //DO something if closed not selected
            $('.close_auction_user_list').hide();
            $('#close_auction_users').attr('disabled',true);
            $('#close_auction_users').attr('required',false);
        } 

    }

    get_category_fields();
    function get_category_fields(){

            var category_id = $('#category_id').val();
            var selectedText = $("#category_id").find("option:selected").text();
            var base_url = '<?php echo base_url(); ?>';
            
            

            $.ajax({    //create an ajax request to display.php
                    type: "post",
                    url: "<?php echo base_url(); ?>" + "items/get_subcategories",    
                    data:{ category_id:category_id, [token_name]:token_value},
                    success: function(data) {
                     objdata = $.parseJSON(data);
                    if(objdata.msg == 'success')
                    {
                        $('.categories_case').show();
                        $('#subcategory_id').attr('disabled',false);
                        $('#subcategory_id').html(objdata.data);
                        // if(subcategory_id_selected != '')
                        // {
                        // $('#subcategory_id option[value="'+subcategory_id_selected+'"]').attr("selected", "selected");
                        // }

                    }
                    else
                    {
                        $('.categories_case').hide();
                        $('#subcategory_id').attr('disabled',true);
                        $('#subcategory_id').html('');
                    }
                }
              });
    }

     $('#category_id').on('change', function() {
        var category_id = $(this).val();
        var selectedText = $(this).find("option:selected").text();
         var base_url = '<?php echo base_url(); ?>';
         console.log('here is');

         $.ajax({    //create an ajax request to display.php
                    type: "post",
                    url: "<?php echo base_url(); ?>" + "items/get_subcategories",    
                    data:{ category_id:category_id, [token_name]:token_value},
                    beforeSend: function(){
                    $('.categories_case').show();
                    $('.categories_case').prepend('<div class="load_anim"><img class="center" src="'+base_url+'assets_admin/images/load.gif" align="center" /></div>');
                    },
                    success: function(data) {
                     objdata = $.parseJSON(data);
                    if(objdata.msg == 'success')
                    {
                        $('.categories_case').show();
                        $('.load_anim').remove();
                        $('#subcategory_id').attr('disabled',false);
                        $('#subcategory_id').html(objdata.data);

                    }
                    else
                    {
                        $('.load_anim').remove();
                        $('.categories_case').hide();
                        $('#subcategory_id').attr('disabled',true);
                        $('#subcategory_id').html('');
                    }
                }
              });
    });
        // on change show hide user list if closed select

      $('#access_type').on('change', function() 
      {

            if ($("#access_type option[value=closed]:selected").length > 0){
             $('.close_auction_user_list').show();
             $('#close_auction_users').attr('disabled',false);
            $('#close_auction_users').attr('required',true);
            }
            else{
                //DO something if closed not selected
                $('.close_auction_user_list').hide();
                $('#close_auction_users').attr('disabled',true);
                $('#close_auction_users').attr('required',false);
            }

            if ($("#access_type option[value=live]:selected").length > 0)
            {
                
                $('.live_auction_bid_list').show();
                $('#bid_options').attr('disabled',false);
                $('#bid_options').attr('required',true);
            }else{
                $('.live_auction_bid_list').hide();
                $('#bid_options').attr('disabled',true);
                $('#bid_options').attr('required',false);
            }

        });
 





    var url = '<?php echo base_url();?>';
    var formaction_path = '<?php echo $formaction_path;?>';

    $(document).ready(function() {



        init_TagsInput2();
         // You can use the locally-scoped $ in here as an alias to jQuery.
        $('#demo-form2').parsley()
        .on('field:validated', function() {
            var ok = $('.parsley-error').length === 0;
          }) 
        .on('form:submit', function() {
            
            var content1 = tinymce.get("detail_english").getContent();
            $("#texteditor").val(content1);
            var content2 = tinymce.get("detail_arabic").getContent();
            $("#texteditor2").val(content2);


            // var content3 = tinymce.get("popup_message_english").getContent();
            // $("#texteditor3").val(content3);
            // var content4 = tinymce.get("popup_message_arabic").getContent();
            // $("#texteditor4").val(content4);



             var formData = new FormData($("#demo-form2")[0]);
            
            $.ajax({
                url: url + 'auction/' + formaction_path,
                type: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success : function(data) {
                    var objData = jQuery.parseJSON(data);
                   if (objData.msg == 'success') { 
                    window.location = url + 'auction/live';
                    } 
                    else 
                    {
                    $('.msg-alert').css('display', 'block');
                    $('#send').html('Submit');
                    $('#send').attr('disabled',false);
                    $("#result").html('<div class="alert" ><div class="alert alert-domain alert-danger alert-dismissible fade in  msg-alert" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: black"><span aria-hidden="true">×</span> </button>' + objData.msg + '</div></div>');


                    }  
                }

            });
            return false; // Don't submit form for this demo
          });
  
    
    });
     
    $(function () { 
        $('#start_time').datetimepicker({ 
            maxDate: "<?php if(isset($auction_info) && !empty($auction_info)) { echo date('Y-m-d H:i',strtotime($auction_info[0]['expiry_time'])); }else{ echo date('Y-m-d H:i', strtotime('+2 month'));} ?>",
            minDate: "<?php if(isset($auction_info) && !empty($auction_info)) { echo date('Y-m-d H:i',strtotime($auction_info[0]['start_time'])); }else{ echo date('Y-m-d H:i');}  ?>",
            format: 'YYYY-MM-DD HH:mm',
            <?php if(isset($auction_info) && !empty($auction_info)) { ?>
            useCurrent: false, //Important! See issue #1075 
            <?php }?>
            ignoreReadonly : true
        });
        $('#expiry_time').datetimepicker({
            useCurrent: false, //Important! See issue #1075 
            minDate: "<?php if(isset($auction_info) && !empty($auction_info)) { echo date('Y-m-d H:i',strtotime($auction_info[0]['start_time'])); }else{ echo date('Y-m-d H:i');} ?>",
           format: 'YYYY-MM-DD HH:mm',
            ignoreReadonly : true
           
        });
        $("#start_time").on("dp.change", function (e) {
            $('#expiry_time').data("DateTimePicker").minDate(e.date);
        });
        $("#expiry_time").on("dp.change", function (e) {
            $('#start_time').data("DateTimePicker").maxDate(e.date);
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
  autosave_ask_before_unload: false,
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
  autosave_ask_before_unload: false,
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

</script>

