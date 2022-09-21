<style type="text/css">
    .dz-image img{
    max-width: 120px;
    max-height: 120px;
    }
</style>

<!-- <?php?> -->

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
                <?php } ?>  
                
                <div class="item form-group">    
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="access_type">Auction Type <span class="required">*</span>
                    </label>
                    
                    <div class="col-md-6 col-sm-6 col-xs-12"> 
                        <select required class="form-control select2" id="access_type" name="access_type">
                            <?php $access_type_arr = explode(",",$auction_info[0]['access_type']); ?>
                            
                            <option <?php if(isset($auction_info) && !empty($auction_info) && in_array('online', $access_type_arr)){ echo 'selected'; }?> value="online">Online</option>
                            
                            <option <?php if(isset($auction_info) && !empty($auction_info) && in_array('closed', $access_type_arr)){ echo 'selected'; }?> value="closed">Closed</option>
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
                                value="<?php echo $customer_value['id']; ?>"><?php echo $customer_value['username']; ?></option>
               
                                <?php  } ?> 
                              
                        </select>
                    </div>
                </div>

                <div class="item form-group">    
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Auction Title <span class="required">*</span>
                        </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <?php if (!empty($auction_info)) {
                      
                            $title = json_decode($auction_info[0]['title']);
                         }?>
                        <input type="text"  id="title" required="required" name="title_english" value="<?php if(isset($auction_info) && !empty($auction_info)) { echo $title->english; } ?>" class="form-control col-md-7 col-xs-12">
                    </div>
                </div>

                 <div class="item form-group">    
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Title Arabic <span class="required">*</span>
                        </label>
                    <?php if (!empty($auction_info)) { 
                            $title = json_decode($auction_info[0]['title']);
                         }?>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                         <input type="text" dir="rtl" id="title" required="required" name="title_arabic" value="<?php if(isset($auction_info) && !empty($auction_info)) { echo $title->arabic; } ?>" class="form-control col-md-7 col-xs-12">
                    </div>
                </div>

                <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="category_id">Item Category <span class="required">*</span></label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <select class="form-control col-md-7 col-xs-12" id="category_id" name="category_id" >
                         <!-- <option >Select Category</option> -->
                         <?php foreach ($category_list as $category_value) { 
                            $catsList = json_decode($category_value['title']);
                            ?>
                        <option 
                        <?php if(isset($auction_info) && !empty($auction_info)){
                            if($auction_info[0]['category_id'] == $category_value['id']){ echo 'selected';}
                        }?>
                            value="<?php echo $category_value['id']; ?>"><?php echo $catsList->english; ?></option>
                            <?php  } ?> 
                          
                    </select>
                </div>
                </div>
                 <div class="item form-group">    
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="registration_no">Auction Code <span class="required">*</span>
                        </label>

                       <!--  data-parsley-trigger="focusin focusout" data-parsley-type="alphanum" <?php //if(isset($auction_info) && !empty($auction_info)) { echo ''; }else{ echo 'data-parsley-remote="'.base_url().'auction/validate_check_registration_no/{value}" data-parsley-remote-validator="mycustom_code" data-parsley-remote-message="Code already exists"'; } ?>  -->

                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" id="registration_no" data-parsley-trigger="focusin focusout" data-parsley-type="alphanum" <?php if(isset($auction_info) && !empty($auction_info)) { echo ''; }else{ echo 'data-parsley-remote="'.base_url().'auction/validate_check_registration_no/{value}" data-parsley-remote-validator="mycustom_code" data-parsley-remote-message="Code already exists"'; } ?> required="required" name="registration_no" value="<?php if(isset($auction_info) && !empty($auction_info)) { echo $auction_info[0]['registration_no']; } ?>" class="form-control col-md-7 col-xs-12">
                    </div>
                </div>

                <div class="item form-group">    
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="start_time">Start Date Time <span class="required">*</span>
                    </label>

                    <div class="col-md-6 col-sm-6 col-xs-12  ">
                        <div class='input-group date' id='start_time'>
                          <input type="text" readonly class="form-control" name="start_time" placeholder="From" value="<?php if(isset($auction_info) && !empty($auction_info)) { echo date('Y-m-d H:i A',strtotime($auction_info[0]['start_time'])); } ?>" required />
                          <span class="input-group-addon">
                             <span class="glyphicon glyphicon-calendar"></span>
                          </span>
                        </div>
                    </div>
                </div>  

                <div class="item form-group">    
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="expiry_time">Expiry Date Time <span class="required">*</span>
                    </label>

                    <div class="col-md-6 col-sm-6 col-xs-12  ">
                        <div class='input-group date' id='expiry_time'>
                          <input type="text" readonly class="form-control" name="expiry_time" placeholder="To" value="<?php if(isset($auction_info) && !empty($auction_info)) { echo date('Y-m-d H:i A',strtotime($auction_info[0]['expiry_time'])); } ?>" required />
                          <span class="input-group-addon">
                             <span class="glyphicon glyphicon-calendar"></span>
                          </span>
                        </div>
                    </div>
                </div>  
                
                <div class="item form-group">    
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="detail">Detail English 
                        </label>
                        <?php if (!empty($auction_info)) {
                            $detail = json_decode($auction_info[0]['detail']);
                         }?>

                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <textarea type="text" rows="5"  maxlength="150" id="detail" title="please add something" name="detail_english" class="form-control col-md-7 col-xs-12"><?php 
                        if(isset($auction_info) && !empty($auction_info)){ echo $detail->english; }?></textarea>
                    </div>
                </div>

                <div class="item form-group">    
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="detail">Detail Arabic 
                        </label>
                        <?php if (!empty($auction_info)) {
                            $detail = json_decode($auction_info[0]['detail']);
                         }?>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <textarea type="text" dir="rtl" rows="5"  maxlength="150" id="detail" title="please add something" name="detail_arabic" class="form-control col-md-7 col-xs-12"><?php 
                        if(isset($auction_info) && !empty($auction_info)){ echo $detail->arabic; }?></textarea>
                    </div>
                </div>

                <div class="item form-group">    
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="status">Status <span class="required">*</span>
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
                    <a href="<?php echo base_url().'auction'; ?>" class="custom-class" type="button"> 
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
              defaultText:'add a bid',
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
        // on change show hide user list if closed select

    $('#access_type').on('change', function() 
    {

        if ($("#access_type option[value=closed]:selected").length > 0){
         $('#close_auction_users').attr('disabled',false);
        $('#close_auction_users').attr('required',true);
         $('.close_auction_user_list').show();
        }
        else{
            //DO something if closed not selected
            $('#close_auction_users').attr('disabled',true);
            $('#close_auction_users').attr('required',false);
            $('.close_auction_user_list').hide();
        }

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
    var url = '<?php echo base_url();?>';
    var formaction_path = '<?php echo $formaction_path;?>';

    $(document).ready(function() {
        init_TagsInput2();
         // You can use the locally-scoped $ in here as an alias to jQuery.
        $('#demo-form2').parsley().on('field:validated', function() {
            var ok = $('.parsley-error').length === 0;
          }) .on('form:submit', function() {
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
                        window.location = url + 'auction';
                    }else{
                        $('.msg-alert').css('display', 'block');
                        $("#result").html('<div class="alert" ><div class="alert alert-domain alert-danger alert-dismissible fade in  msg-alert" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: black"><span aria-hidden="true">×</span> </button>' + objData.msg + '</div></div>');


                    }  
                }

            });
            return false; // Don't submit form for this demo
        });
  
    // You can use the locally-scoped $ in here as an alias to jQuery.
  
        // $("#send").on('click', function(e) 
        // {       
           
                
        // }); 
    });
     
</script>