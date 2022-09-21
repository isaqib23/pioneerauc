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
                <input type="hidden" name="user_id" id="user_id" value="<?php echo $this->uri->segment(3); ?>">
                <div class="item form-group">    
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Account Number <span class="required">*</span>
                        </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <?php if (!empty($auction_info)) {
                      
                            $title = $auction_info['manuall_account_number'];
                            // print_r($auction_info);die('dcjkdjh');
                         }?>
                        <input type="text"  id="cheque_title" required="required" name="manuall_account_number" value="<?php if(isset($auction_info) && !empty($auction_info)) { echo $manuall_account_number; } ?>" class="form-control col-md-7 col-xs-12">
                    </div>
                </div>
                 <div class="item form-group">    
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Account Title <span class="required">*</span>
                        </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <?php if (!empty($auction_info)) {
                      
                            $title = json_decode($auction_info[0]['manuall_title']);
                         }?>
                        <input type="text"  id="cheque_title" required="required" name="manuall_title" value="<?php if(isset($auction_info) && !empty($auction_info)) { echo $manuall_title; } ?>" class="form-control col-md-7 col-xs-12">
                    </div>
                </div>

                 <div class="item form-group">    
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Bank Name <span class="required">*</span>
                        </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <?php if (!empty($auction_info)) {
                      
                            $title = json_decode($auction_info[0]['manuall_bank_name']);
                         }?>
                        <input type="text"  id="manuall_bank_name" required="required" name="manuall_bank_name_english" value="<?php if(isset($auction_info) && !empty($auction_info)) { echo $manuall_bank_name->english; } ?>" class="form-control col-md-7 col-xs-12">
                    </div>
                </div>

                 <div class="item form-group">    
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Bank Name Arabic  <span class=""></span>
                        </label>
                    <?php if (!empty($auction_info)) { 
                            $title = json_decode($auction_info[0]['manuall_bank_name']);
                         }?>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                         <input type="text" dir="rtl" id="title"  name="manuall_bank_name_arabic" value="<?php if(isset($auction_info) && !empty($auction_info)) { echo $manuall_bank_name->arabic; } ?>" class="form-control col-md-7 col-xs-12">
                    </div>
                </div>

                <div class="item form-group">    
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Bank Branch <span class="required">*</span>
                        </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <?php if (!empty($auction_info)) {
                      
                            $title = json_decode($auction_info[0]['manuall_bank_branch']);
                         }?>
                        <input type="text"  id="manuall_bank_branch" required="required" name="manuall_bank_branch_english" value="<?php if(isset($auction_info) && !empty($auction_info)) { echo $manuall_bank_branch->english; } ?>" class="form-control col-md-7 col-xs-12">
                    </div>
                </div>

                 <div class="item form-group">    
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Bank Branch Arabic  <span class=""></span>
                        </label>
                    <?php if (!empty($auction_info)) { 
                            $title = json_decode($auction_info[0]['manuall_bank_branch']);
                         }?>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                         <input type="text" dir="rtl" id="title"  name="manuall_bank_branch_arabic" value="<?php if(isset($auction_info) && !empty($auction_info)) { echo $manuall_bank_branch->arabic; } ?>" class="form-control col-md-7 col-xs-12">
                    </div>
                </div>

                <div class="item form-group categories_case">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="subcategory_id"></label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                <select class="form-control col-md-7 col-xs-12" id="subcategory_id" name="subcategory_id">

                </select>
                </div>
                </div>
                
                 <div class="item form-group">    
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="registration_no">Manuall Amount<span class="required">*</span>
                        </label>

                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" id="registration_nouo"  data-parsley-trigger="focusin focusout" data-parsley-type="digits" <?php if(isset($auction_info) && !empty($auction_info)) { echo ''; }else{ echo 'data-parsley-remote="'.base_url().'auction/validate_check_registration_no/{value}" data-parsley-remote-validator="mycustom_code" data-parsley-remote-message="Code already exists"'; } ?>  required="required" name="manuall_amount" value="<?php if(isset($auction_info) && !empty($auction_info)) { echo $auction_info[0]['manuall_amount']; } ?>" class="form-control col-md-7 col-xs-12">
                    </div>
                </div>

                  <div class="item form-group">    
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Deposit By Name <span class="required">*</span>
                        </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <?php if (!empty($auction_info)) {
                      
                            $title = json_decode($auction_info[0]['manuall_deposit_name']);
                         }?>
                        <input type="text"  id="cheque_title" required="required" name="manuall_deposit_name_english" value="<?php if(isset($auction_info) && !empty($auction_info)) { echo $manuall_deposit_name->english; } ?>" class="form-control col-md-7 col-xs-12">
                    </div>
                </div>

                 <div class="item form-group">    
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Deposit By Name Arabic  <span class=""></span>
                        </label>
                    <?php if (!empty($auction_info)) { 
                            $title = json_decode($auction_info[0]['manuall_deposit_name']);
                         }?>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                         <input type="text" dir="rtl" id="title"  name="manuall_deposit_name_arabic" value="<?php if(isset($auction_info) && !empty($auction_info)) { echo $manuall_deposit_name->arabic; } ?>" class="form-control col-md-7 col-xs-12">
                    </div>
                </div>

                 <div class="item form-group">    
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Deposit By Identity number <span class="required">*</span>
                        </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <?php if (!empty($auction_info)) {
                      
                            $title = json_decode($auction_info[0]['manuall_identity']);
                         }?>
                        <input type="text"  id="cheque_title" required="required" name="manuall_identity" value="<?php if(isset($auction_info) && !empty($auction_info)) { echo $manuall_identity;} ?>" class="form-control col-md-7 col-xs-12">
                    </div>
                </div>

                 <div class="item form-group">    
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Deposit Phone Number <span class="required">*</span>
                        </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <?php if (!empty($auction_info)) {
                      
                            $title = json_decode($auction_info[0]['manuall_deposit_phone']);
                         }?>
                        <input type="text"  id="cheque_title" required="required" name="manuall_deposit_phone" value="<?php if(isset($auction_info) && !empty($auction_info)) { echo $manuall_deposit_phone; } ?>" class="form-control col-md-7 col-xs-12">
                    </div>
                </div>

                <div class="item form-group">    
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Deposit Transaction ID <span class="required">*</span>
                        </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <?php if (!empty($auction_info)) {
                      
                            $title = json_decode($auction_info[0]['manuall_transaction_id']);
                         }?>
                        <input type="text"  id="cheque_title" required="required" name="manuall_transaction_id" value="<?php if(isset($auction_info) && !empty($auction_info)) { echo $manuall_transaction_id; } ?>" class="form-control col-md-7 col-xs-12">
                    </div>
                </div>

                <div class="item form-group">    
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="start_time">Date <span class="required">*</span>
                    </label>

                    <div class="col-md-6 col-sm-6 col-xs-12  ">
                        <div class='input-group date' id='start_time'>
                          <input type="text" readonly class="form-control" name="manuall_deposit_date" placeholder="Date" value="<?php if(isset($auction_info) && !empty($auction_info)) { echo date('Y-m-d H:i A',strtotime($auction_info[0]['manuall_deposit_date'])); } ?>" required />
                          <span class="input-group-addon">
                             <span class="glyphicon glyphicon-calendar"></span>
                          </span>
                        </div>
                    </div>
                </div>  

               <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="category_id">Deposit Type<span class="required">*</span></label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <select class="form-control col-md-7 col-xs-12" id="" name="manuall_deposit_type" >
                          <option value="">Select Deposit Type</option>
                          <option value="Cash">Cash</option>
                          <option value="Online">Online</option>
                          <option value="cheque">cheque</option>
                          <option value="online cheque">online cheque</option>
                          
                    </select>
                </div>
                </div>

                 <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="category_id">Deposit Currency<span class="required">*</span></label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <select class="form-control col-md-7 col-xs-12" id="" name="manuall_currency" >
                          <option value="">Select Deposit Currency</option>
                          <option value="AED">AED</option>
                          <option value="USD">USD</option>
                          
                    </select>
                </div>
                </div>


                <div class="form-group">
                    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                    <button type="submit" id="send" class="btn btn-success">Submit</button> 
                    <a href="<?php echo base_url().'users'; ?>" class="custom-class" type="button"> 
                        Cancel
                    </a>

                    </div>
                </div>

            </form>
        </div>
    </div>
</div>


<script>
    
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
            data:{ category_id:category_id},
            success: function(data) {
                objdata = $.parseJSON(data);
                if(objdata.msg == 'success')
                {
                    $('.categories_case').show();
                    $('#subcategory_id').attr('disabled',false);
                    $('#subcategory_id').html(objdata.data);
                    if(subcategory_id_selected != '')
                    {
                    $('#subcategory_id option[value="'+subcategory_id_selected+'"]').attr("selected", "selected");
                    }

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
            format: 'YYYY-MM-DD H:m',
            <?php if(isset($auction_info) && !empty($auction_info)) { ?>
            useCurrent: false, //Important! See issue #1075 
            <?php }?>
            ignoreReadonly : true
        });
        $('#expiry_time').datetimepicker({
            useCurrent: false, //Important! See issue #1075 
            minDate: "<?php if(isset($auction_info) && !empty($auction_info)) { echo date('Y-m-d H:i',strtotime($auction_info[0]['start_time'])); }else{ echo date('Y-m-d H:i');} ?>",
           format: 'YYYY-MM-DD H:m',
            ignoreReadonly : true
           
        });
        $("#start_time").on("dp.change", function (e) {
            $('#expiry_time').data("DateTimePicker").minDate(e.date);
        });
        $("#expiry_time").on("dp.change", function (e) {
            $('#start_time').data("DateTimePicker").maxDate(e.date);
        });
    });

    $('#category_id').on('change', function() {
        var category_id = $(this).val();
        var selectedText = $(this).find("option:selected").text();
         var base_url = '<?php echo base_url(); ?>';
         console.log('here is');

         $.ajax({    //create an ajax request to display.php
                    type: "post",
                    url: "<?php echo base_url(); ?>" + "items/get_subcategories",    
                    data:{ category_id:category_id},
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
                url: url + 'users/' + formaction_path,
                type: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success : function(data) {
                    var objData = jQuery.parseJSON(data);
                    var ids =$('#user_id').val();
                    if (objData.msg == 'success') { 
                        window.location = url + 'users/show_deposite_options/' + ids;
                    }else{
                        $('.msg-alert').css('display', 'block');
                        $("#result").html('<div class="alert" ><div class="alert alert-domain alert-danger alert-dismissible fade in  msg-alert" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: black"><span aria-hidden="true">×</span> </button>' + objData.msg + '</div></div>');


                    }  
                }

            });
            return false; // Don't submit form for this demo
        });
    });
     
</script>