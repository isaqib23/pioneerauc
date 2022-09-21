<!-- PNotify -->
<link href="<?php echo base_url(); ?>assets_admin/vendors/pnotify/dist/pnotify.css" rel="stylesheet">

<script src="<?= ASSETS_USER; ?>new/js/bootstrap-select.min.js"></script>
<script src="<?= ASSETS_ADMIN; ?>js/formBuilder/jquery-ui.min.js"></script>
<script src="<?= ASSETS_ADMIN; ?>js/formBuilder/form-render.min.js"></script>

<style type="text/css">
    label.error{
        color: red;
    }
</style>

<div class="main-wrapper account-page">
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#"><?= $this->lang->line('home')?></a></li>
            <li class="breadcrumb-item" aria-current="page"><a href="<?= base_url('customer/deposit'); ?>"><?= $this->lang->line('my_account_new')?></a></li>
            <li class="breadcrumb-item active" aria-current="page"><?= $this->lang->line('sell_my_item_new')?></li>
        </ol>
        <h2><?= $this->lang->line('my_account_new')?></h2>
        <div class="account-wrapper">
            <?= $this->load->view('template/new/template_user_leftbar') ?>
            <div class="right-col">
                <h3><?= $this->lang->line('item_details')?></h3>
                <h4 class="border-title"><?= $this->lang->line('personal_information')?></h4>
                <form class="account-form customform"  method="post" novalidate="" id="demo-form2">
                    <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
                    <div class="row col-gap-24">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label><?= $this->lang->line('category');?> <span>*</span></label>
                                <select class="selectpicker" id="category_id" name="item[category_id]" required>
                                    <option value="" data-make_model="no"><?= $this->lang->line('select_cat');?></option>
                                    <?php foreach ($category_list as $category_value) { ?>
                                    <?php $title = json_decode($category_value['title']); ?>
                                    <option data-make_model="<?= $category_value['include_make_model']; ?>"
                                    <?php if(isset($item_info) && !empty($item_info)){
                                        if($item_info[0]['category_id'] == $category_value['id']){ echo 'selected';}
                                    }?>
                                        value="<?php echo $category_value['id']; ?>"><?php echo $title->$language; ?></option>
                                    <?php  } ?> 
                                </select>
                                <span class="valid-error text-danger itemcategory_id-error"></span>
                            </div>
                        </div>
                        <div class="col-sm-4 categories_case">
                            <div class="form-group">
                                <label><?= $this->lang->line('sub_cat');?></label>
                                <select class="selectpicker" id="subcategory_id" name="item[subcategory_id]">
                        </select>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label><?= $this->lang->line('vin_number');?></label>
                                <input type="text" class="form-control" id="vin_no" name="item[vin_no]">
                            </div>
                        </div>
                       <!--  <div class="col-sm-4">
                            <div class="form-group">
                                <label><?= $this->lang->line('reg_code');?> </label>
                                <input type="text" class="form-control" id="registration_no" name="item[registration_no]">
                                <span class="valid-error text-danger registration_no-error"></span>
                            </div>
                        </div> -->

                        <div class="col-sm-4 vehicle_case">
                            <div class="form-group make_case">
                                <label for="make"><?= $this->lang->line('make');?> <span>*</span></label>
                                <select class="selectpicker" class="form-control" id="make" name="item[make]">
                                    <option value=""><?= $this->lang->line('select_make');?></option>
                                </select>
                                <span class="valid-error text-danger itemmake-error"></span>
                            </div>
                        </div>
                        <div class="col-sm-4 vehicle_case">
                            <div class="form-group">
                                <label for="model"><?= $this->lang->line('model');?> <span>*</span></label>
                                <select class="selectpicker" id="model" name="item[model]">
                                <option value=""><?= $this->lang->line('select_model');?></option>
                            </select>
                            <span class="valid-error text-danger itemmodel-error"></span>
                            </div>
                        </div>
                        <div class="col-sm-4 vehicle_case">
                            <div class="form-group">
                                <label for="mileage"><?= $this->lang->line('mileage');?> <span>*</span></label>
                                <input type="text" class="form-control" id="mileage" name="item[mileage]">
                                <span class="valid-error text-danger itemmileage-error"></span>
                            </div>
                        </div>
                        <div class="col-sm-4 vehicle_case">
                            <div class="form-group">
                                <label for="mileage_type"><?= $this->lang->line('mileage_type');?> <span>*</span></label>
                                <select class="selectpicker" id="mileage_type" name="item[mileage_type]">
                                <option value="km" ><?= $this->lang->line('km_new');?></option>
                                <option value="miles"><?= $this->lang->line('miles_new');?></option>
                            </select>
                            <span class="valid-error text-danger itemmileage_type-error"></span>
                            </div>
                        </div>
                        <div class="col-sm-4 vehicle_case">
                            <div class="form-group">
                                <label for="specification"><?= $this->lang->line('specifications');?> <span>*</span></label>
                                <select class="selectpicker" id="specification" name="item[specification]">
                                    <option value="GCC" ><?= $this->lang->line('gcc_new');?></option>
                                    <option value="IMPORTED"><?= $this->lang->line('imported_new');?></option>
                                </select>
                                <span class="valid-error text-danger itemspecification-error"></span>
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                <label><?= $this->lang->line('item_name_english');?> <span>*</span></label>
                                <input type="text" class="form-control" id="name" required="required" name="item[name]" >
                                <span class="valid-error text-danger itemname-error"></span>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label><?= $this->lang->line('item_name_arabic');?> <span></span></label>
                                <input type="text" dir="rtl" class="form-control" id="name_arabic" name="item[name_arabic]">
                                <span class="valid-error text-danger itemname_arabic-error"></span>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label><?= $this->lang->line('item_location');?> <span>*</span></label>
                                <input id="lon" type="hidden" name="item[lng]">
                                <input id="lat" type="hidden" name="item[lat]">
                                <input type="text" class="form-control" id="location" required="required" name="item[location]">
                                <span class="valid-error text-danger itemlocation-error"></span>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label><?= $this->lang->line('reserve_price');?></label>
                                <input type="text" class="form-control" id="price" name="item[price]" oninput="this.value=this.value.replace(/[^0-9.]/g,'');">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label><?= $this->lang->line('year');?></label>
                                <select required="" class="selectpicker" id="year" name="item[year]">
                                    <?php $year = date('Y'); ?>
                                    <?php for ($i=0; $i <= 50 ; $i++) { ?>
                                        <option <?php if(isset($item_info) && !empty($item_info) && $item_info[0]['year'] == $year-$i){ echo 'selected'; }?> value="<?= $year-$i ?>"><?= $year-$i ?></option>
                                    <?php }; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label><?= $this->lang->line('detail_english');?></label>
                                <textarea class="form-control" rows="5" id="detail" title="<?= $this->lang->line('please_add_something'); ?>" name="item[detail]"></textarea>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label><?= $this->lang->line('detail_arabic');?></label>
                                <textarea class="form-control" rows="5" dir="rtl" id="detail_arabic" title="<?= $this->lang->line('please_add_something'); ?>" name="item[detail_arabic]"></textarea>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label>Images</label>
                        <div id="dz0" class="dropzone"></div>
                    </div>
                    <h4 class="border-title"><?= $this->lang->line('more_info')?></h4>
                    <div style="margin: 0 auto; width: 60%;" class="other-info container-fluid">
                        <div id="build-wrap" class="center"></div>
                    </div>
                    <div class="ln_solid"></div>
                    <div class="row col-gap-24">
                        <div class="col-sm-4">
                            <!-- <div class="form-group mb-0">
                                <label>Password <span>*</span></label>
                                <input type="password" class="form-control">
                            </div> -->
                            <div class="button-row mt-4">
                                <button type="submit" id="submitbtn" class="btn btn-primary"><?= $this->lang->line('submit');?></button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <?= $this->load->view('template/new/faq_footer') ?>
    </div>
</div>

<script src="<?= base_url(); ?>assets_user/js/jquery.validate.min.js"></script>
<!-- Notify JS Files -->
<script src="<?php echo base_url(); ?>assets_admin/vendors/pnotify/dist/pnotify.js"></script>


<script>
    var token_name = '<?= $this->security->get_csrf_token_name();?>';
    var token_value = '<?=$this->security->get_csrf_hash();?>';
    var itemId = '';

      
    // $('.select2').select2();

    // $('#7-test').select2();
    

    // var selectOz = remoteSelect2({ 
    //   'selectorId' : '#seller_id',
    //   'placeholder' : 'Select Seller',
    //   'table' : 'users',
    //   'values' : 'Name',
    //   'width' : '200px',
    //   'delay' : '250',
    //   'cache' : false,
    //   'minimumInputLength' : 1,
    //   'limit' : 5,
    //   'url' : '<?= base_url();?>items/seller_id_api'
    // });

    var url = '<?php echo base_url();?>';
    var formaction_path = '<?php echo $formaction_path;?>';
    var subcategory_id_selected = '<?php echo (isset($item_info[0]['subcategory_id']) && !empty($item_info[0]['subcategory_id'])) ? $item_info[0]['subcategory_id'] : '' ;?>';
    var make_id_selected = '<?php echo (isset($item_info[0]['make']) && !empty($item_info[0]['make'])) ? $item_info[0]['make'] : '' ;?>';
    var model_id_selected = '<?php echo (isset($item_info[0]['model']) && !empty($item_info[0]['model'])) ? $item_info[0]['model'] : '' ;?>';
    var data_ids_json = '<?php echo (isset($field_ids) && !empty($field_ids)) ? json_encode($field_ids) : ''; ?>';
    var data_values_json = '<?php echo (isset($field_values) && !empty($field_values)) ? json_encode($field_values) : ''; ?>';
    var if_code_ = '<?php  echo (isset($item_info[0]['registration_no']) && !empty($item_info[0]['registration_no'])) ? $item_info[0]['registration_no'] : ""; ?>';
    // console.log(data_values_json);
    // data_values_arr = $.parseJSON(data_values_json);

    $(document).ready(function() {

        if(if_code_ == '')
        {
            // let value_option = '';
            // window.Parsley.addAsyncValidator('mycustom_code', function (xhr) {
            // // console.log(this.$element); // jQuery Object[ input[name="q"] ]
            // var obj = this.$element;
            // // console.log(obj.val());
            // value_option = obj.val();

            // console.log(value_option);
            // var response = xhr.responseText;
            // if (response === '200') {
            //     // success if not exists
            // return 200 === xhr.status;
            // } else {
            //     // error if already exists
            // return 404 === xhr.status;
            // }

            // }); 
        }

         // You can use the locally-scoped $ in here as an alias to jQuery.
        // $('#demo-form2').parsley().on('field:validated', function() {
        //     var ok = $('.parsley-error').length === 0; 

        //   }).on('form:submit', function(event) {
        $('#submitbtn').on('click', function(event) {
            event.preventDefault();
            var validation = false;
            var lan = "<?= $language;?>";
            var selectedText = $("#category_id").find("option:selected").attr('data-make_model');
            if(selectedText.toLowerCase() == 'yes'){
                selectedInputs = ['"item[category_id]"','"item[make]"','"item[model]"','"item[mileage]"','"item[mileage_type]"','"item[name]"','"item[location]"'];
            }else{
                selectedInputs = ['"item[category_id]"','"item[name]"','"item[location]"'];
            }
            validation = validateFields(selectedInputs,lan);


            if(validation == false){
                return false;
            }
                if (validation == true) {

                    // initialize the validator
                    // $('form#demo-form2').validate();
                    if ($('form#demo-form2').validate({
                        onfocusout: false,
                        invalidHandler: function(form, validator) {
                            var errors = validator.numberOfInvalids();
                            if (errors) {                    
                                validator.errorList[0].element.focus();
                            }
                        } 
                    }).form()) {
                        var formData = new FormData($("#demo-form2")[0]);
                        formData.append([token_name], token_value);
                        $('#submitbtn').attr('disabled', true);
                        
                        $.ajax({
                            url: url + 'customer/' + formaction_path,
                            type: 'POST',
                            data: formData,
                            cache: false,
                            contentType: false,
                            processData: false,
                            success : function(data) {
                                var objData = jQuery.parseJSON(data);
                                if (objData.msg == 'success') { 
                                    new PNotify({
                                        text: objData.message,
                                        type: 'success',
                                        addclass: 'custom-success',
                                        title: "<?= $this->lang->line('success_'); ?>",
                                        styling: 'bootstrap3'
                                    });
                                    itemId = objData.in_id;
                                    abc.processQueue();
                                    if (abc.getUploadingFiles().length === 0 ){
                                        setTimeout(function(){  
                                            window.location = url + 'customer/inventory';
                                        }, 2000);
                                    }
                                
                                } else {
                                    $('#submitbtn').attr('disabled', false);
                                    new PNotify({
                                        text: ""+objData.msg+"",
                                        type: 'error',
                                        addclass: 'custom-error',
                                        title: "<?= $this->lang->line('error'); ?>",
                                        styling: 'bootstrap3'
                                    });
                                }  
                            },
                            complete: function (data,ddd,fff) {
                                console.log('completed'); 
                                // window.location = url + 'Items';
                            }
                        });
                    }
                }
            return false; // Don't submit form for this demo
        });
    });
 
    
$(document).ready(function() {
    getcategory_fields();
    function getcategory_fields() {
        var category_id = $('#category_id').val();
        var selectedText = $("#category_id").find("option:selected").attr('data-make_model');
        // alert(selectedText);
        if(selectedText.toLowerCase() == 'yes')
        {
            // console.log(category_id);
            $('#make').attr('disabled',false);
            $('#model').attr('disabled',false);
            $('#make').attr('required',true);
            $('#model').attr('required',true);
            $('.vehicle_case').show();

            $.ajax({    //create an ajax request to display.php
            type: "post",
            url: "<?php echo base_url(); ?>" + "customer/get_makes_options",    
            data:{ category_id:category_id, [token_name]:token_value},
            success: function(data) 
            {
                objdata = $.parseJSON(data);
                // console.log(objdata);
                if(objdata.msg == 'success')
                {
                    $('.make_case').show();
                    $('#make').attr('disabled',false);
                    $('#make').html(objdata.data);
                    if(make_id_selected != '')
                    {
                    $('#make option[value="'+make_id_selected+'"]').attr("selected", "selected");

                    $.ajax({    //create an ajax request to display.php
                        type: "post",
                        url: "<?php echo base_url(); ?>" + "customer/get_model_options",    
                        data:{ make_id:make_id_selected},
                        success: function(data) {
                            objdata = $.parseJSON(data);
                            if(objdata.msg == 'success')
                            {
                                // $('.make_case').show();
                                $('#model').attr('disabled',false);
                                $('#model').html(objdata.data);

                            }
                            else
                            {
                                // $('.make_case').hide();
                                $('#model').attr('disabled',false);
                                $('#model').html('<option value=""><?= $this->lang->line('select_model'); ?></option>');
                                $('.selectpicker').selectpicker('refresh');
                            }
                        }
                    });
                    }


                }
                else
                {
                    $('.make_case').hide();
                    $('#make').attr('disabled',true);
                    $('#make').html('');
                }
            }
            });

        }
        else{
            $('.vehicle_case').hide();
            $('#make').attr('disabled',true);
            $('#model').attr('disabled',true);
            $('#make').attr('required',false);
            $('#model').attr('required',false);
        }
       
         $.ajax({    //create an ajax request to display.php
                    type: "post",
                    url: "<?php echo base_url(); ?>" + "customer/get_item_fields",    
                    data:{ category_id:category_id, [token_name]:token_value},
                    success: function(data) {
                     data = $.parseJSON(data);
                     jQuery.each(data, function(indexs, item_value) {
                        data[indexs].name = data[indexs].id+'-'+data[indexs].name;
                     });

                     if(data_values_json != ''){ 
                     data_values_arr = $.parseJSON(data_values_json); 
                    
                     jQuery.each(data, function(indexs, item_value1) {
                   
                        jQuery.each(data_values_arr, function(index, item_value2) {
                                // console.log(index);
                            if(index == item_value1.id)
                            {
                                if(data[indexs].multiple == 'true'){
                                jQuery.each(data[indexs].values, function(index_inner, item_value_inner){
                                    // console.log(item_value_inner.value+' - '+ item_value2.value);
                                    var hasApple = item_value2.value.indexOf(item_value_inner.value) != -1;
                                    if(hasApple)
                                    {
                                        // make selected if match
                                        item_value_inner.selected = 'true';
                                    }
                                });

                                }
                                else
                                {
                                    
                                data[indexs].value = item_value2.value;
                                }
                            }
                        });
                                // console.log(item_value1.id);
                    });

                     }
                     
                   
                    formData = data;
                    var formRenderOpts = {
                        formData,
                        dataType: 'json'
                    };
                    var frInstance = $('#build-wrap').formRender(formRenderOpts);
                   
                }
              });


            $.ajax({    //create an ajax request to display.php
                    type: "post",
                    url: "<?php echo base_url(); ?>" + "customer/get_subcategories",    
                    data:{ category_id:category_id, [token_name]:token_value},
                    success: function(data) {
                     objdata = $.parseJSON(data);
                    if(objdata.msg == 'success')
                    {
                        $('.categories_case').show();
                        $('#subcategory_id').attr('disabled',false);
                        $('#subcategory_id').html(objdata.data);
                        $('.selectpicker').selectpicker('refresh');
                        if(subcategory_id_selected != '')
                        {
                        $('#subcategory_id option[value="'+subcategory_id_selected+'"]').attr("selected", "selected");
                        }

                    }
                    else
                    {
                        $('.categories_case').hide();
                        $('#subcategory_id').attr('disabled',true);
                        $('.selectpicker').selectpicker('refresh');
                        $('#subcategory_id').html('');
                    }
                }
            });
    }

});

    $('#make').on('change', function() {
        var make_id = $(this).val();
          $.ajax({    //create an ajax request to display.php
            type: "post",
            url: "<?php echo base_url(); ?>" + "customer/get_model_options",    
            data:{ make_id:make_id, [token_name]:token_value},
            success: function(data) {
                objdata = $.parseJSON(data);
                if(objdata.msg == 'success')
                {
                    // $('.make_case').show();
                    $('#model').attr('disabled',false);
                    $('#model').empty();
                    $('#model').html(objdata.data);
                    // $('.selectpicker').val('default');
                    $('.selectpicker').selectpicker('refresh');

                }
                else
                {
                    // $('.make_case').hide();
                    $('#model').attr('disabled',false);
                    $('#model').html('<option value=""><?= $this->lang->line('select_model'); ?></option>');
                    $('.selectpicker').selectpicker('refresh');
                }
            }
        });

    });   

    // category change
    $('#category_id').on('change', function() {
        var category_id = $(this).val();
        var selectedText = $(this).find("option:selected").attr('data-make_model');
        // console.log(selectedText);
        if(selectedText.toLowerCase() == 'yes')
        {
            console.log(category_id);
            $('#make').attr('disabled',false);
            $('#model').attr('disabled',false);

            $('#make').attr('required',true);
            $('#model').attr('required',true);
            $('.vehicle_case').show();

            $.ajax({    //create an ajax request to display.php
                type: "post",
                url: "<?php echo base_url(); ?>" + "customer/get_makes_options",    
                data:{ category_id:category_id, [token_name]:token_value},
                success: function(data) {
                    objdata = $.parseJSON(data);
                    if(objdata.msg == 'success')
                    {
                        console.log(objdata.data)
                        $('.make_case').show();
                        $('#make').html(objdata.data);
                        $('.selectpicker').selectpicker('refresh');
                        $('#make').attr('disabled',false);

                    }
                    else
                    {
                        $('.make_case').hide();
                        $('#make').attr('disabled',true);
                        $('#make').html('');
                    }
                }
            });

        }
        else
        {
            $('.vehicle_case').hide();
            $('#make').attr('disabled',true);
            $('#model').attr('disabled',true);
            $('#make').attr('required',false);
            $('#model').attr('required',false);
        }

        $.ajax({    //create an ajax request to display.php
            type: "post",
            url: "<?php echo base_url('customer/get_item_fields'); ?>",    
            data:{ category_id:category_id, [token_name]:token_value},
            success: function(data) {
                console.log(data);
                data = $.parseJSON(data);
                var lim = data.length;
                // console.log(lim);
                for (var i = 0; i < lim; i++)
                {
                   if (data[i].id != '')
                   { 
                        data[i].name = data[i].id+'-'+data[i].name;
                      
                   }
                }
            
                formData = data;
                var formRenderOpts = {
                    formData,
                    dataType: 'json'

                };
                var frInstance = $('#build-wrap').formRender(formRenderOpts);
            }
        });

        $.ajax({    //create an ajax request to display.php
            type: "post",
            url: "<?php echo base_url(); ?>" + "customer/get_subcategories",    
            data:{ category_id:category_id, [token_name]:token_value},
            success: function(data) {
                objdata = $.parseJSON(data);
                if(objdata.msg == 'success')
                {
                    $('.categories_case').show();
                    $('#subcategory_id').attr('disabled',false);
                    $('#subcategory_id').html(objdata.data);
                    $('.selectpicker').selectpicker('refresh');

                }else{
                    $('.categories_case').hide();
                    $('#subcategory_id').attr('disabled',true);
                    $('#subcategory_id').html('');
                    $('.selectpicker').selectpicker('refresh');
                }
            }
        });
    }); 

    


    var abc = new makeDropzone({
        'url': "<?php echo base_url('customer/save_item_file_images'); ?>",
        'deleteURL':"<?php echo base_url('customer/delete_customerDocs/'); ?>",
        'div':"#dz0",
        'paramName':"images",
        'maxFilesize':8,
        "dictDefaultMessage": "<?= $this->lang->line('drag_drop_image_here'); ?>",
        'maxFiles':100,
        'acceptedFiles':'.jpeg,.png,.jpg',
        'uploadMultiple':true,
        'existedFiles':'',
        'existedFilesPath':"",
        'iconPath':"<?= base_url('assets_admin/images/file-icons/'); ?>"
     });
        
    var currentFile = null;
    var status_done_doc = 'false';
    var thumb = "<?php echo base_url('assets_user/images/logo.png');?>";
    function makeDropzone(options){
        Dropzone.autoDiscover = false;
         var dz = new Dropzone(options.div, {
            url: options.url,
            paramName: options.paramName,
            dictDefaultMessage: options.dictDefaultMessage,
            maxFilesize: options.maxFilesize,
            maxFiles: options.maxFiles,
            acceptedFiles : options.acceptedFiles,
            addRemoveLinks : true,
            // reset : true,
            dictRemoveFile : 'X',
            parallelUploads: 100,
            uploadMultiple: options.uploadMultiple,
            autoProcessQueue: false,
            thumbnailWidth: 150,
            thumbnailHeight: 150,
            previewsContainer: options.div,  
            init: function() { 
                this.on('addedfile', function(file){
                    // this.emit("thumbnail", file,thumb);
                    this.options.addRemoveLinks = true;
                    this.options.dictRemoveFile = 'X';
                     var extension = file.name.substr( (file.name.lastIndexOf('.')) );
                    if(file.type){
                        if (extension.match(/.pdf/)) {
                            this.emit("thumbnail", file, options.iconPath+'pdf-icon.png');
                        }
                        if (extension.match(/.ai/)) {
                            this.emit("thumbnail", file, options.iconPath+'ai-icon.png');
                        }
                        if (extension.match(/.docx/)) {
                            this.emit("thumbnail", file, options.iconPath+'docx-icon.png');
                        }
                        if (extension.match(/.doc/)) {
                            this.emit("thumbnail", file, options.iconPath+'docx-icon.png');
                        }
                        if (extension.match(/.eps/)) {
                            this.emit("thumbnail", file, options.iconPath+'eps-icon.png');
                        }
                        if (extension.match(/.id/)) {
                            this.emit("thumbnail", file, options.iconPath+'id-icon.png');
                        }
                        if (extension.match(/.ppt/)) {
                            this.emit("thumbnail", file, options.iconPath+'ppt-icon.png');
                        }
                        if (extension.match(/.pptx/)) {
                            this.emit("thumbnail", file, options.iconPath+'ppt-icon.png');
                        }
                        if (extension.match(/.psd/)) {
                            this.emit("thumbnail", file, options.iconPath+'psd-icon.png');
                        }
                        if (extension.match(/.xlsx/)) {
                            this.emit("thumbnail", file, options.iconPath+'xlsx-icon.png');
                        }
                        if (extension.match(/.txt/)) {
                            this.emit("thumbnail", file, options.iconPath+'txt-icon.png');
                        }
                        if (extension.match(/.xls/)) {
                            this.emit("thumbnail", file, options.iconPath+'xlsx-icon.png');
                        }    
                    }
                });
                if(options.existedFiles){
                    var existedFiles = JSON.parse(options.existedFiles);  
                    for(let i = 0; i < existedFiles.length; i++) {
                        let existedFile = existedFiles[i];
                        // Create the mock file:
                        var mockFile = {name: existedFile.name, size: existedFile.size, url: existedFile.url};
                        var extension = existedFile.name.substr( (existedFile.name.lastIndexOf('.') +1) );
                        this.options.addedfile.call(this, mockFile);
                        console.log(extension);
                        if(extension == 'pdf'){
                            this.options.thumbnail.call(this, mockFile, options.iconPath+'pdf-icon.png');
                        }else if(extension == 'docx'){ 
                            this.options.thumbnail.call(this, mockFile, options.iconPath+'docx-icon.png');
                        }else if(extension == 'doc'){ 
                            this.options.thumbnail.call(this, mockFile, options.iconPath+'docx-icon.png');
                        }else if(extension == 'xlsx'){
                            this.options.thumbnail.call(this, mockFile, options.iconPath+'xlsx-icon.png');
                        }else if(extension == 'xls'){
                            this.options.thumbnail.call(this, mockFile, options.iconPath+'xlsx-icon.png');
                        }else if(extension == 'ppt'){
                            this.options.thumbnail.call(this, mockFile, options.iconPath+'ppt-icon.png');
                        }else if(extension == 'pptx'){
                            this.options.thumbnail.call(this, mockFile, options.iconPath+'ppt-icon.png');
                        }else if(extension == 'txt'){
                            this.options.thumbnail.call(this, mockFile, options.iconPath+'txt-icon.png');
                        }else if(extension == 'psd'){
                            this.options.thumbnail.call(this, mockFile, options.iconPath+'psd-icon.png');
                        }else{
                            // this.emit("addedfile", mockFile);
                            this.emit("thumbnail", mockFile, options.existedFilesPath+existedFile.url);
                        }
                        this.emit("complete", mockFile);
                        var existingFileCount = i; // The number of files already uploaded
                        this.options.maxFiles = this.options.maxFiles - existingFileCount;
                        mockFile.previewElement.classList.add('dz-success');
                        mockFile.previewElement.classList.add('dz-complete');
                    }
                }
                this.on('sending', function(file, xhr, formData){
                    var catid = $('#cats').find(":selected").val();
                    formData.append('catid', catid);
                    formData.append("<?= $this->security->get_csrf_token_name();?>","<?=$this->security->get_csrf_hash();?>");
                    if(itemId != ''){
                        formData.append('item_id', itemId);
                    }
                });
                this.on("success", function(file, response) { 
                    this.options.addRemoveLinks = false;
                    this.options.dictRemoveFile = '';
                    var objDataa = jQuery.parseJSON(response);
                    if(objDataa.error == false){
                        PNotify.removeAll();
                        new PNotify({
                            text: "<?= $this->lang->line('user_documents_has_updated_successfully'); ?>",
                            type: 'success',
                            addclass: 'custom-success',
                            title: '<?= $this->lang->line('success_'); ?>',
                            styling: 'bootstrap3'
                        });
                        setTimeout(function(){  
                            window.location = url + 'customer/sell_item';
                        }, 5000);
                    }
                });              
                this.on("maxfilesexceeded", function (file) {
                    this.removeAllFiles();
                    this.addFile(file);
                });
                 this.on("queuecomplete", function (file) {
                    $('#send').text("<?= $this->lang->line('submit'); ?>");
                    $('#send').attr('disabled',false);
                });
                this.on("completemultiple", function (file) {
                    if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
                    }
                }); 
                if(options.existedFiles){
                    this.on("removedfile", function(file) {
                        if (this.files.length == 0) {
                            // var catid = ;
                            var catid = $('#cats').find(":selected").val();
                            var name = file.name; 
                            $.post(options.deleteURL, { file_to_be_deleted: name,catid: catid,"<?= $this->security->get_csrf_token_name();?>":"<?=$this->security->get_csrf_hash();?>"} );
                        } 
                    });   
                }
            }// end init function 
        });     
        return dz;
    } // End funciton 
</script>
