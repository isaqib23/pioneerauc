
<input type="hidden" id="base_url" value="<?php echo base_url(); ?>">

<div class="clearfix"></div>

<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>
                <?php  echo $small_title; ?> CRM Detail</h2>

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

           
            <form method="post" data-parsley-validate="" id="demo-form2" action="" enctype="multipart/form-data" class="form-horizontal form-label-left">
                <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
               <?php if(isset($crm_info) && !empty($crm_info)){ ?>
                <input type="hidden" name="id" value="<?php echo $this->uri->segment(3); ?>">
            <?php } ?>  
                
                <div class="item form-group">    
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Customer Name <span class="required">*</span>
                        </label>

                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" id="name" name="name" value="<?php if(isset($crm_info) && !empty($crm_info)) { echo $crm_info[0]['name']; } ?>" class="form-control col-md-7 col-xs-12">
                        <span class="text-danger name-error"></span>
                    </div>
                </div>
                <div class="item form-group">    
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Customer Email 
                        </label>

                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="email" id="email" name="email" <?php if(isset($crm_info) && !empty($crm_info)) { echo ''; }else{ echo 'data-parsley-remote data-parsley-remote-validator="mycustom_email" data-parsley-remote-message="Email already exists." data-parsley-trigger="focusin focusout"'; } ?>  value="<?php if(isset($crm_info) && !empty($crm_info)) { echo $crm_info[0]['email']; } ?>" class="form-control col-md-7 col-xs-12">
                    </div>
                </div>
                
                 <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="lead_source_id">Lead Source <span class="required">*</span></label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <select class="form-control col-md-7 col-xs-12" id="lead_source_id" name="lead_source_id">
                         <option disabled="" selected="" >Select Lead Source</option>
                         <?php foreach ($lead_source_list as $lead_source_value) { ?>
                        <option 
                        <?php if(isset($crm_info) && !empty($crm_info)){
                            if($crm_info[0]['lead_source_id'] == $lead_source_value['id']){ echo 'selected';}
                        }?>
                            value="<?php echo $lead_source_value['id']; ?>"><?php echo $lead_source_value['title']; ?></option>
                            <?php  } ?> 
                          
                    </select>
                    <span class="text-danger lead_source_id-error"></span>
                </div>
            </div>
                
                <div class="item form-group">    
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="mobile1">Mobile <span class="required">*</span>
                        </label>

                    <div class="col-md-6 col-sm-6 col-xs-12">

                         <input type="tel" id="mobile1" id="mobile1" maxlength="11" oninput="this.value=this.value.replace(/[^0-9]/g,'');" class="form-control col-md-7 col-xs-12">
                        <div class="text-danger mobile1-error"></div>

                         <span class="duplicate_msg" id="message__" style="display: none;"  >
                        <!-- <div style="color: red"> <h5 class="duplicate_msg" id="duplicate_msg">
                            Number Alreadt Exist </h5></div> -->
                    </span>
                <span id="message">


                        <!-- <input type="text" <?php if(isset($crm_info) && !empty($crm_info)) { echo ''; }else{ echo 'data-parsley-remote data-parsley-remote-validator="mycustom" data-parsley-focus="none" data-parsley-remote-message="Mobile already exists" data-parsley-trigger="focusout"'; } ?>  name="mobile" value="<?php if(isset($crm_info) && !empty($crm_info)) { echo $crm_info[0]['mobile']; } ?>" class="form-control col-md-7 col-xs-12"> -->
                    </div>
                </div>
                  <!-- <input type="hidden" name="mobile_code" id="mobile_hidden"> -->

                <div class="item form-group">    
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="interested">Interested <span class="">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12"> 
                    <select class="form-control select2" id="interested" name="interested">
                        <option <?php if(isset($crm_info) && !empty($crm_info) && $crm_info[0]['interested'] == 'no'){ echo 'selected'; }?> value="no">No</option>
                        <option <?php if(isset($crm_info) && !empty($crm_info) && $crm_info[0]['interested'] == 'yes'){ echo 'selected'; }?> value="yes">Yes</option>
                        
                        <option <?php if(isset($crm_info) && !empty($crm_info) && $crm_info[0]['interested'] == 'maybe'){ echo 'selected'; }?> value="maybe">Maybe</option>
                    </select>
                    <span class="text-danger interested-error"></span>
                </div>
                </div>
                <!-- hide fields start  -->
                <div class="hide_field_intrested">

                 <div class="item form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="customer_type_id">Customer Type</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <select class="form-control col-md-7 col-xs-12" id="customer_type_id" name="customer_type_id" >
                             <option disabled="" selected="" >Select Customer Type</option>
                             <?php foreach ($customer_type_list as $type_value) { ?>
                            <option 
                            <?php if(isset($crm_info) && !empty($crm_info)){
                                if($crm_info[0]['customer_type_id'] == $type_value['id']){ echo 'selected';}
                            }?>
                                value="<?php echo $type_value['id']; ?>"><?php echo $type_value['title']; ?></option>
                                <?php  } ?> 
                              
                        </select>
                    </div>
                </div>


                 <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="lead_category_id">Lead Category</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <select class="form-control col-md-7 col-xs-12" id="lead_category_id" name="lead_category_id" >
                         <option disabled="" selected="" >Select Lead Category</option>
                         <?php foreach ($lead_category_list as $lead_category_value) { ?>
                        <option 
                        <?php if(isset($crm_info) && !empty($crm_info)){
                            if($crm_info[0]['lead_category_id'] == $lead_category_value['id']){ echo 'selected';}
                        }?>
                            value="<?php echo $lead_category_value['id']; ?>"><?php echo $lead_category_value['title']; ?></option>
                            <?php  } ?> 
                          
                    </select>
                </div>
            </div>


                <div class="item form-group">    
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="project_name">Project Name 
                        </label>

                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text"  id="project_name" name="project_name" value="<?php if(isset($crm_info) && !empty($crm_info)) { echo $crm_info[0]['project_name']; } ?>" class="form-control col-md-7 col-xs-12">
                    </div>
                </div>

                <div class="item form-group">    
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="company_name">Company Name
                        </label>

                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" id="company_name" name="company_name" value="<?php if(isset($crm_info) && !empty($crm_info)) { echo $crm_info[0]['company_name']; } ?>" class="form-control col-md-7 col-xs-12">
                    </div>
                </div>


               <!--  <div class="item form-group">    
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="phone">Phone 
                        </label>

                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text"  id="phone" name="phone" value="<?php if(isset($crm_info) && !empty($crm_info)) { echo $crm_info[0]['phone']; } ?>" class="form-control col-md-7 col-xs-12">
                    </div>
                </div> -->

                <div class="item form-group">    
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="estimated_reserve_price">Estimated Reserve Price 
                        </label>

                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="number"  id="estimated_reserve_price" name="estimated_reserve_price" value="<?php if(isset($crm_info) && !empty($crm_info)) { echo $crm_info[0]['estimated_reserve_price']; } ?>" class="form-control col-md-7 col-xs-12">
                    </div>
                </div>

                <div class="item form-group">    
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="estimated_commission">Estimated Commission 
                        </label>

                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="number" id="estimated_commission" name="estimated_commission" value="<?php if(isset($crm_info) && !empty($crm_info)) { echo $crm_info[0]['estimated_commission']; } ?>" class="form-control col-md-7 col-xs-12">
                    </div>
                </div>
                
                <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="lead_stage_id">Lead Stage</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <select class="form-control col-md-7 col-xs-12" id="lead_stage_id" name="lead_stage_id">
                         <option disabled="" selected="" >Select Lead Stage</option>
                         <?php foreach ($lead_stage_list as $lead_stage_value) { ?>
                        <option 
                        <?php if(isset($crm_info) && !empty($crm_info)){
                            if($crm_info[0]['lead_stage_id'] == $lead_stage_value['id']){ echo 'selected';}
                        }?>
                            value="<?php echo $lead_stage_value['id']; ?>"><?php echo $lead_stage_value['title']; ?></option>
                            <?php  } ?> 
                          
                    </select>
                </div>
            </div>
                
                <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="next_step_id">Next Step</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <select class="form-control col-md-7 col-xs-12" id="next_step_id" name="next_step_id">
                         <option disabled="" selected="" >Select Next Step</option>
                         <?php foreach ($next_step_list as $next_step_value) { ?>
                        <option 
                        <?php if(isset($crm_info) && !empty($crm_info)){
                            if($crm_info[0]['next_step_id'] == $next_step_value['id']){ echo 'selected';}
                        }?>
                            value="<?php echo $next_step_value['id']; ?>"><?php echo $next_step_value['title']; ?></option>
                            <?php  } ?> 
                          
                    </select>
                </div>
                </div>

                <div class="item form-group">    
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="final_status">Final Status
                </label>
                
                <div class="col-md-6 col-sm-6 col-xs-12"> 
                    <select class="form-control select2" id="final_status" name="final_status">
                        <option disabled="" selected="" >Select Final Status</option>
                        <option <?php if(isset($crm_info) && !empty($crm_info) && $crm_info[0]['final_status'] == 'won'){ echo 'selected'; }?> value="won">Won</option>
                        <option <?php if(isset($crm_info) && !empty($crm_info) && $crm_info[0]['final_status'] == 'lost'){ echo 'selected'; }?> value="lost">Lost</option>
                        <option <?php if(isset($crm_info) && !empty($crm_info) && $crm_info[0]['final_status'] == 'pending'){ echo 'selected'; }?> value="pending">Pending</option>
                    </select>
                </div>
                </div>
                

                <div class="item form-group loss_reason">    
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="loss_reason">Loss Reason </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <select class="form-control col-md-7 col-xs-12" id="loss_reason" name="loss_reason" >
                         <option disabled="" selected="" >Select Loss Reason</option>
                         <?php foreach ($loss_reason_list as $loss_reason_value) { ?>
                        <option 
                        <?php if(isset($crm_info) && !empty($crm_info)){
                            if($crm_info[0]['loss_reason'] == $loss_reason_value['id']){ echo 'selected';}
                        }?>
                            value="<?php echo $loss_reason_value['id']; ?>"><?php echo $loss_reason_value['title']; ?></option>
                            <?php  } ?> 
                          
                        </select>
                    </div>
                </div>
                
                </div>
                <!-- hide fields end  -->
                <div class="item form-group">    
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="remarks">Remarks
                        </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                         <textarea rows="5"  maxlength="150" id="remarks" title="please add something" name="remarks" class="form-control col-md-7 col-xs-12"><?php if(isset($crm_info) && !empty($crm_info)) { echo $crm_info[0]['remarks']; } ?></textarea>
                    </div>
                </div>
                
                <?php $role = $this->session->userdata('logged_in')->role; 
                    if($role == 1)
                    {
                ?>

                <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="assigned_by">Assigned By <span class="required">*</span>  </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <select class="form-control col-md-7 col-xs-12" id="assigned_by" name="assigned_by">
                         <!-- <option disabled="" selected="" >Select Assigned By</option> -->
                         <?php //foreach ($assigned_by_list as $assigned_by_value) { ?>
                       
                            <?php  //} ?> 
                          
                    </select>
                    <span class="text-danger assigned_by-error"></span>
                </div>
                </div>
                <?php }
                 else
                 { 

                 ?>
                <input type="hidden" name="assigned_by" value="<?php echo $this->session->userdata('logged_in')->id; ?>">
                <?php 
                 }
                 ?>
                
                <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="assigned_to">Assigned To <span class="required">*</span> </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <select class="form-control col-md-7 col-xs-12" id="assigned_to" name="assigned_to">
                         <option disabled="" selected="" >Select Assigned To</option>
                         <?php foreach ($assigned_to_list as $assigned_to_value) { ?>
                        <option 
                        <?php if(isset($crm_info) && !empty($crm_info)){
                            if($crm_info[0]['assigned_to'] == $assigned_to_value['id']){ echo 'selected';}
                        }?>
                            value="<?php echo $assigned_to_value['id']; ?>"><?php echo $assigned_to_value['fname'].' '.$assigned_to_value['lname'].' - ('.ucwords($assigned_to_value['role_name']).')'; ?></option>
                            <?php  } ?> 
                          
                    </select>
                    <span class="text-danger assigned_to-error"></span>
                </div>
                </div>

                <div class="ln_solid"></div>
                <div class="form-group">
                    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                        <!-- <input type="submit" class="btn btn-default" value="validate"> -->
                        <a href="javascript:void(0)" id="send" class="btn btn-success" type="button">Submit</a>
                        <!-- <a  value="" class="btn btn-success" /> -->

                        <a href="<?php echo base_url().'crm'; ?>" class="custom-class" type="button">Cancel</a>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>

 

<script>

    var token_name = '<?= $this->security->get_csrf_token_name();?>';
    var token_value = '<?=$this->security->get_csrf_hash();?>';

    $(document).ready(function(){
        // $("#register-phone").intlTelInput({
        //     allowDropdown: true,
        //     autoPlaceholder: "polite",
        //     placeholderNumberType: "MOBILE",
        //     formatOnDisplay: true,
        //     separateDialCode: true,
        //     nationalMode: false,
        //     autoHideDialCode: true,
        //     hiddenInput: "phone",
        //     preferredCountries: [ "ae", "sa", "cn", "ru" ]
        // });
        $("#mobile1").intlTelInput({
            allowDropdown: true,
            autoPlaceholder: "polite",
            placeholderNumberType: "MOBILE",
            formatOnDisplay: true,
            separateDialCode: true,
            nationalMode: false,
            autoHideDialCode: true,
            hiddenInput: "mobile",
            preferredCountries: [ "ae", "sa" ]
        });
        $("#mobile1").intlTelInput("setNumber", "<?= isset($crm_info) ? $crm_info[0]['mobile'] : ''; ?>");
        //$("#user-phone").intlTelInput("setNumber", "<?php //echo isset($user['mobile']) ? $user['mobile'] : ''; ?>");
    });


 $('#mobile1').on('keyup',function(){ 
    $('input[name=mobile]').val($("#mobile1").intlTelInput('getNumber'));
    var phone = $('input[name=mobile]').val();
    var user_id = '<?php echo $this->uri->segment('3'); ?>';
    if(user_id == null)
    {
        user_id =  0;
    }
    if (phone.length > 0) {
        $.ajax({
            url: base_url + 'crm/check_dublication_mobile?user_id=' + phone,
            type: 'POST',
            data: {mobile: phone, [token_name] : token_value,'<?= $this->security->get_csrf_token_name();?>':"<?=$this->security->get_csrf_hash();?>"},
            success : function(data) {
                var objData = jQuery.parseJSON(data);
               if (objData.msg == 'duplicate') {   
                $('.duplicate_msg').show();
                $('.duplicate_msg').html('<h5 style="color:red;">Number already exist.</h5>');
                $('.duplicate_msg').focus();

                }  
                else{
                    $(".duplicate_msg").hide();
                }
            }
            
        });
    }
});
    
    // show hide specific fields
    showhide_div();
    $('#interested').change(showhide_div);

    function showhide_div(){
      if ( $('#interested').val() == 'yes')
      {
        $(".hide_field_intrested").show();
        $(".hide_field_intrested :input").attr("disabled", false);
      }
      else
      {
        $(".hide_field_intrested").hide();
        $(".hide_field_intrested :input").attr("disabled", true);
      }
    } 


                          
    <?php 
    if(isset($crm_info[0]['assigned_to']) && !empty($crm_info[0]['assigned_to'])){ 
        // for existed selected options
        foreach ($assigned_to_list as $assigned_to_value) { 
            if($crm_info[0]['assigned_to'] == $assigned_to_value['id']){
    ?>
    var data = {
        id: <?php echo $crm_info[0]['assigned_to']; ?>,
        text: "<?= (isset($assigned_to_value['fname'])) ? $assigned_to_value['fname'].' '.$assigned_to_value['lname'].' - ('.ucwords($assigned_to_value['role_name']).') ' : '' ; ?>"
    };
    var newOption2 = new Option(data.text, data.id, false, false);
    $('#assigned_to').append(newOption2).trigger('change');        
    <?php  
            }
        }   
    }
    if($role == 1)
    {
    if(isset($crm_info[0]['assigned_by']) && !empty($crm_info[0]['assigned_by'])){ 
        // for existed selected options
        foreach ($assigned_by_list as $assigned_by_value) { 
            if($crm_info[0]['assigned_by'] == $assigned_by_value['id']){
    ?>
    var data = {
        id: <?php echo $crm_info[0]['assigned_by']; ?>,
        text: "<?= (isset($assigned_by_value['fname'])) ? $assigned_by_value['fname'].' '.$assigned_by_value['lname'].' - ('.ucwords($assigned_by_value['role_name']).') ' : '' ; ?>"
    };
    var newOption = new Option(data.text, data.id, false, false);
    $('#assigned_by').append(newOption).trigger('change');        
    <?php  
            }
        }   
    } 
    ?> 
    

    var selectOz = remoteSelect2({ 
      'selectorId' : '#assigned_to',
      'placeholder' : 'Select Assigned To',
      'table' : 'users',
      'values' : 'Name',
      'width' : '200px',
      'delay' : '250',
      'cache' : false,
      'minimumInputLength' : 1,
      'limit' : 5,
      'csrf':'<?=$this->security->get_csrf_hash();?>',
      'url' : '<?= base_url("crm/assigned_to_api");?>'
    });

    var selectOz = remoteSelect2({ 
      'selectorId' : '#assigned_by',
      'placeholder' : 'Select Assigned By',
      'table' : 'users',
      'values' : 'Name',
      'width' : '200px',
      'delay' : '250',
      'cache' : false,
      'minimumInputLength' : 1,
      'limit' : 5,
      'csrf':'<?=$this->security->get_csrf_hash();?>',
      'url' : '<?= base_url("crm/assigned_by_api");?>',
    });
    <?php } ?>

    // show hide specific fields
    showhide_reason();
    $('#final_status').change(showhide_reason);

    function showhide_reason(){
      if ( $('#final_status').val() == 'lost')
      {
        $(".loss_reason").show();
        $("#loss_reason").attr("disabled", false);
      }
      else
      {
        $(".loss_reason").hide();
        $("#loss_reason").attr("disabled", true);
      }
    }



    //  ajax call for process form
    var url = '<?php echo base_url();?>';
    var formaction_path = '<?php echo $formaction_path;?>';
    var if_user_ = '<?php  echo (isset($crm_info[0]['mobile']) && !empty($crm_info[0]['mobile'])) ? $crm_info[0]['mobile'] : ""; ?>';
    let ok;

    $(document).ready(function() { 
        
    if(if_user_ == '')
    {
        window.Parsley.addAsyncValidator('mycustom', function (xhr) {
        // console.log(this.$element); // jQuery Object[ input[name="q"] ]
        var response = xhr.responseText;
        if (response === '200') {
            // success if not exists
        return 200 === xhr.status;
        } else {
            // error if already exists
        return 404 === xhr.status;
        }

        }, url+'crm/validate_mobile');

         window.Parsley.addAsyncValidator('mycustom_email', function (xhr) {
        // console.log(this.$element); // jQuery Object[ input[name="q"] ]
        var response = xhr.responseText;
        if (response === '200') {
            // success if not exists
        return 200 === xhr.status;
        } else {
            // error if already exists
        return 404 === xhr.status;
        }

        }, url+'crm/validate_email'); 
    }
    // $("#send").on('click', function(e) {

    // });

        // $('#demo-form2').parsley().on('field:validated', function() {
        //     var ok = $('.parsley-error').length === 0;
        //   }) .on('form:submit', function() {
    $('#send').on('click', function(event) {
        event.preventDefault();
        // alert($("#lead_source_id").val());

        $('input[name=mobile]').val($("#mobile1").intlTelInput('getNumber'));
        var validation = false;
            var errorMsg = "This value is required.";
            var errorClass = ".valid-error";
            var e;
        selectedInputs = ['name','lead_source_id','mobile1','interested','assigned_by','assigned_to'];

        $.each(selectedInputs, function(index, value){
                e = $("#"+value);

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
                var formData = new FormData($("#demo-form2")[0]);
                    // alert(formaction_path);
                    $.ajax({
                        url: url + 'crm/' + formaction_path,
                        type: 'POST',
                        data: formData,
                        cache: false,
                        contentType: false,
                        processData: false
                    }).then(function(data) {
                        var objData = jQuery.parseJSON(data);
                        console.log(objData);
                        if (objData.msg == 'success') {

                            window.location = url + 'crm';

                        } else {
                            $('.msg-alert').css('display', 'block');
                            $("#result").html('<div class="alert" ><div class="alert alert-domain alert-danger alert-dismissible fade in  msg-alert" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: black"><span aria-hidden="true">×</span> </button>' + objData.msg + '</div></div>');

                        //        window.setTimeout(function() {
                        //     $(".alert").fadeTo(500, 0).slideUp(500, function(){
                        //         $(this).remove(); 
                        //     });
                        // }, 3000);

                        }
                    });
                    return false;
            }
    
        // });
      
    });
});
      
</script>