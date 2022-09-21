
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

           
            <form method="post" data-parsley-validate="" id="demo-form2" action="" enctype="multipart/form-data" class="form-horizontal form-label-left">
                <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
               <?php if(isset($crm_info) && !empty($crm_info)){ ?>
                <input type="hidden" name="id" value="<?php echo $this->uri->segment(3); ?>">
            <?php } ?>  
            <?php if(isset($crm_info) && !empty($crm_info)) { $readonly = 'readonly'; }else{ $readonly = ''; } ?>
                   <div class="item form-group">    
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Customer Name <span class="required">*</span>
                        </label>

                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" required id="name" name="name" value="<?php if(isset($crm_info) && !empty($crm_info)) { echo $crm_info[0]['name']; } ?>" class="form-control col-md-7 col-xs-12">
                    </div>
                </div>
                <div class="item form-group">    
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Customer Email <span class="required">*</span>
                        </label>

                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="email" required id="email" data-parsley-remote data-parsley-remote-validator="mycustom_email" data-parsley-remote-message="Email already exists." data-parsley-trigger="focusin focusout" name="email" value="<?php if(isset($crm_info) && !empty($crm_info)) { echo $crm_info[0]['email']; } ?>" class="form-control col-md-7 col-xs-12">
                    </div>
                </div>
                
                 <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="lead_source_id">Lead Source</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <select class="form-control col-md-7 col-xs-12" id="lead_source_id" name="lead_source_id" required>
                         <option disabled="" selected="" >Select Lead Source</option>
                         <?php foreach ($lead_source_list as $lead_source_value) { ?>
                        <option 
                        <?php if(isset($crm_info) && !empty($crm_info)){
                            if($crm_info[0]['lead_source_id'] == $lead_source_value['id']){ echo 'selected';}
                        }?>
                            value="<?php echo $lead_source_value['id']; ?>"><?php echo $lead_source_value['title']; ?></option>
                            <?php  } ?> 
                          
                    </select>
                </div>
            </div>


                <div class="item form-group">    
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="mobile">Mobile <span class="required">*</span>
                        </label>

                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" required id="mobile" data-parsley-remote data-parsley-remote-validator="mycustom" data-parsley-remote-message="Mobile already exists" data-parsley-trigger="focusin focusout" name="mobile" value="<?php if(isset($crm_info) && !empty($crm_info)) { echo $crm_info[0]['mobile']; } ?>" class="form-control col-md-7 col-xs-12">
                    </div>
                </div>

                
                <div class="item form-group">    
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="interested">Interested <span class="">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12"> 
                    <select required class="form-control select2" id="interested" name="interested">
                        <option <?php if(isset($crm_info) && !empty($crm_info) && $crm_info[0]['interested'] == 'no'){ echo 'selected'; }?> value="no">No</option>
                        <option <?php if(isset($crm_info) && !empty($crm_info) && $crm_info[0]['interested'] == 'yes'){ echo 'selected'; }?> value="yes">Yes</option>
                        <option <?php if(isset($crm_info) && !empty($crm_info) && $crm_info[0]['interested'] == 'maybe'){ echo 'selected'; }?> value="maybe">Maybe</option>
                    </select>
                </div>
                </div>


                <!-- hide fields start  -->
                <div class="hide_field_intrested">

                 <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="customer_type_id_r">Customer Type</label>
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
                        <input type="text"  id="company_name" name="company_name" value="<?php if(isset($crm_info) && !empty($crm_info)) { echo $crm_info[0]['company_name']; } ?>" class="form-control col-md-7 col-xs-12">
                    </div>
                </div>


                <div class="item form-group">    
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="phone">Phone 
                        </label>

                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" id="phone" name="phone" value="<?php if(isset($crm_info) && !empty($crm_info)) { echo $crm_info[0]['phone']; } ?>" class="form-control col-md-7 col-xs-12">
                    </div>
                </div>

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
                        <input type="number"  id="estimated_commission" name="estimated_commission" value="<?php if(isset($crm_info) && !empty($crm_info)) { echo $crm_info[0]['estimated_commission']; } ?>" class="form-control col-md-7 col-xs-12">
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
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="final_status">Final Status <span class="">*</span>
                </label>
                
                <div class="col-md-6 col-sm-6 col-xs-12"> 
                    <select class="form-control select2" id="final_status" name="final_status">
                        <option disabled="" selected="" >Select Final Status</option>
                        <option <?php if(isset($crm_info) && !empty($crm_info) && $crm_info[0]['final_status'] == 'won'){ echo 'selected'; }?> value="won">Won</option>
                        <option <?php if(isset($crm_info) && !empty($crm_info) && $crm_info[0]['final_status'] == 'lost'){ echo 'selected'; }?> value="lost">Lost</option>
                    </select>
                </div>
                </div>
                
                <div class="item form-group loss_reason">    
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="loss_reason">Loss Reason<span class=""></span>
                        </label>
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

                <div class="ln_solid"></div>
                <div class="form-group">
                    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                        <!-- <input type="submit" class="btn btn-default" value="validate"> -->
                        <button type="submit" id="send" class="btn btn-success">Submit</button>
                        <a href="<?php echo base_url().'crm/crm_sales_list'; ?>" class="custom-class" type="button">Cancel</a>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>

 

<script>

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
    var ok;
    if(if_user_ == '')
    {
    }
        // check mobile duplication 
        window.Parsley.addAsyncValidator('mycustom', function (xhr) {
            var response = xhr.responseText;
            if (response === '200') {
                // success if not exists
            return 200 === xhr.status;
            } else {
                // error if already exists
            return 404 === xhr.status;
            }
        }, url+"crm/validate_mobile/"+"<?php if(isset($crm_info) && !empty($crm_info)) { echo $crm_info[0]['mobile']; } ?>");


         // check email duplication 
        window.Parsley.addAsyncValidator('mycustom_email', function (xhr) {
            var response = xhr.responseText;
            if (response === '200') {
                // success if not exists
            return 200 === xhr.status;
            } else {
                // error if already exists
            return 404 === xhr.status;
            }
        }, url+"crm/validate_email/"+"<?php if(isset($crm_info) && !empty($crm_info)) { echo urlencode($crm_info[0]['email']); } ?>");
    
    
    $(document).ready(function() {
        // You can use the locally-scoped $ in here as an alias to jQuery.
        $('#demo-form2').parsley().on('field:validated', function() {
    var ok = $('.parsley-error').length === 0;
  }) .on('form:submit', function() {

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
                        console.log(data);
                        console.log(objData);
                        if (objData.msg == "success") {

                            window.location = url + 'crm/crm_sales_list';

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
    return false; // Don't submit form for this demo
  });
        $(function() {
        
        $("#send").on('click', function(e) { //e.preventDefault();
                
            });
    
        });
      
    });
      
</script>