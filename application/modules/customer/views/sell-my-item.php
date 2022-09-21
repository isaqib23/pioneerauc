<!-- PNotify -->
<link href="<?php echo base_url(); ?>assets_admin/vendors/pnotify/dist/pnotify.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>assets_admin/vendors/pnotify/dist/pnotify.buttons.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>assets_admin/vendors/pnotify/dist/pnotify.nonblock.css" rel="stylesheet">

<script src="<?= ASSETS_USER; ?>js/bootstrap-select.js"></script>
<script src="<?= ASSETS_ADMIN; ?>js/formBuilder/jquery-ui.min.js"></script>
<script src="<?= ASSETS_ADMIN; ?>js/formBuilder/form-render.min.js"></script>

<style type="text/css">
    label.error{
        color: red;
    }
</style>
<?php $this->load->view('template/auction_cat');?>
<div class="main gray-bg">
    <div class="row custom-wrapper sell-items">
        <?php $this->load->view('template/template_user_leftbar') ?>
        <div class="right-col">
            <h1 class="section-title text-left"><?= $this->lang->line('sell_item');?></h1>
            <form class="customform" method="post" novalidate="" id="demo-form2">
                <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
                <div class="row">
                    <div class="col-sm-6">
                        <label><?= $this->lang->line('category');?> *</label>
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

                    <div class="col-sm-6">
                        <label><?= $this->lang->line('vin_number');?></label>
                        <input type="text" class="form-control" id="vin_no" name="item[vin_no]">
                    </div>

                    <div class="col-sm-6">
                        <label><?= $this->lang->line('reg_code');?></label>
                        <input type="text" class="form-control" id="registration_no" name="item[registration_no]">
                    </div>

                    <div class="col-sm-6 categories_case">
                        <label><?= $this->lang->line('sub_cat');?></label>
                        <select class="selectpicker" id="subcategory_id" name="item[subcategory_id]">
                        </select>
                    </div>

                    <div class="col-sm-12">
                        <div class="vehicle_case row">
                            <div class="col-sm-6">
                                <div class=" make_case">
                                    <label for="make"><?= $this->lang->line('make');?> <span>*</span></label>
                                    <select class="selectpicker" class="form-control" id="make" name="item[make]">
                                        <option value=""><?= $this->lang->line('select_make');?></option>
                                    </select>
                                    <span class="valid-error text-danger itemmake-error"></span>
                                </div>
                            </div>    
                            <div class="col-sm-6">
                                <label for="model"><?= $this->lang->line('model');?> <span>*</span></label>   
                                <select class="selectpicker" id="model" name="item[model]">
                                    <option value=""><?= $this->lang->line('select_model');?></option>
                                </select>
                                <span class="valid-error text-danger itemmodel-error"></span>
                            </div>
                        </div>                                                       
                    </div>
                    <div class="col-sm-6">
                        <label><?= $this->lang->line('item_name_english');?> *</label>
                        <input type="text" class="form-control" id="name" required="required" name="item[name]">
                        <span class="valid-error text-danger itemname-error"></span>
                    </div>  
                    <div class="col-sm-6">
                        <label><?= $this->lang->line('item_name_arabic');?> *</label>
                        <input type="text" dir="rtl" class="form-control" id="name_arabic" required="required" name="item[name_arabic]">
                        <span class="valid-error text-danger itemname_arabic-error"></span>
                    </div>     
                    <div class="col-sm-6">
                        <label><?= $this->lang->line('reserve_price');?></label>
                        <input type="text" class="form-control" id="price" name="item[price]" oninput="this.value=this.value.replace(/[^0-9.]/g,'');">
                    </div>  
                    <div class="col-sm-6">
                        <label><?= $this->lang->line('year');?></label>
                        <select required="" class="selectpicker" id="year" name="item[year]">
                            <?php $year = date('Y'); ?>
                            <?php for ($i=0; $i <= 50 ; $i++) { ?>
                                <option <?php if(isset($item_info) && !empty($item_info) && $item_info[0]['year'] == $year-$i){ echo 'selected'; }?> value="<?= $year-$i ?>"><?= $year-$i ?></option>
                            <?php }; ?>
                        </select>
                    </div>
                    <div class="col-sm-6">
                        <label><?= $this->lang->line('keyword');?></label>
                        <input type="text" class="form-control" id="keyword" name="item[keyword]">
                    </div>
                    <div class="col-sm-12">
                        <label><?= $this->lang->line('detail_english');?></label>
                        <textarea class="form-control" rows="5" id="detail" title="<?= $this->lang->line('please_add_something'); ?>" name="item[detail]"></textarea>
                    </div> 
                    <div class="col-sm-12">
                        <label><?= $this->lang->line('detail_arabic');?></label>
                        <textarea class="form-control" rows="5" dir="rtl" id="detail_arabic" title="<?= $this->lang->line('please_add_something'); ?>" name="item[detail_arabic]"></textarea>
                    </div>  
                    <div class="col-md-12">
                        <span class="section"><?= $this->lang->line('other_info');?></span>   
                        <div style="margin: 0 auto; width: 60%;" class="other-info container-fluid">
                        <div id="build-wrap" class="center"></div>
                        </div>
                        <div class="ln_solid"></div>
                    </div>
                    <div class="col-sm-12">
                        <div class="button-row">
                            <button id="submitbtn" class="btn-default"><?= $this->lang->line('submit');?></button>
                        </div>    
                    </div>
                </div>    
            </form>  
        </div>
    </div>
</div>

<script src="<?= base_url(); ?>assets_user/js/jquery.validate.min.js"></script>

 <!-- Notify JS Files -->
<script src="<?php echo base_url(); ?>assets_admin/vendors/pnotify/dist/pnotify.js"></script>
<script src="<?php echo base_url(); ?>assets_admin/vendors/pnotify/dist/pnotify.buttons.js"></script>
<script src="<?php echo base_url(); ?>assets_admin/vendors/pnotify/dist/pnotify.nonblock.js"></script>

  
<script>
    var token_name = '<?= $this->security->get_csrf_token_name();?>';
    var token_value = '<?=$this->security->get_csrf_hash();?>';

      
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
                selectedInputs = ['"item[category_id]"','"item[make]"','"item[model]"','"item[name]"','"item[name_arabic]"'];
            }else{
                selectedInputs = ['"item[category_id]"','"item[name]"','"item[name_arabic]"'];
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
                                        title: "<?= $this->lang->line('success_'); ?>",
                                        styling: 'bootstrap3'
                                    });
                                    setTimeout(function(){  
                                        window.location = url + 'customer/sell_item';
                                    }, 5000);
                                
                                } else {
                                    $('#submitbtn').attr('disabled', true);
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

    
</script>