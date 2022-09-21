<style type="text/css">
    #page-wrapper {
        margin-top: 100px;
    }
    .dz-image img{
    max-width: 120px;
    max-height: 120px;
    }
</style>
<input type="hidden" id="base_url" value="<?php echo base_url(); ?>">
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
            <?php if ($this->session->flashdata('msg')) {?>
            <div class="alert">
                <div class="alert alert-domain alert-success alert-dismissible fade in" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true" style="color: black;">×</span>
                      </button>
                    <?php echo $this->session->flashdata('msg'); ?>
                </div>
            </div>
            <?php }?>
            <br />


            
            <form method="post"  novalidate="" name="myForm" id="demo-form2" action="" enctype="multipart/form-data" class="form-horizontal form-label-left">
                <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
            
                <?php
                if(count($all_users) > 0){?>
                    <input type="hidden" name="post_id" value="<?= $all_users[0]['id']; ?>">
                <?php
                }
                ?>
                <input type="hidden" id="user_id" name="user_id" value="<?php echo  $this->uri->segment(3);?>">
<!--                 <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="fname">Account Title<span class="required">*</span>
                        </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" id="account_title" required="required" name="account_title" value="<?php
if (count($all_users) > 0) {echo $all_users[0]['account_title'];}?>" class="form-control col-md-7 col-xs-12">
                    </div>
                </div>
 -->              
 <!--   <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="lname">Account No<span class="required">*</span>
                        </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" id="account_no" required="required" name="account_no" value="<?php
if (count($all_users) > 0) {echo $all_users[0]['account_no'];}?>" class="form-control col-md-7 col-xs-12">
                    </div>
                </div>
  -->
                   <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Bank Name <span class="required">*</span>
                        </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" id="bank_name"  required="required" name="bank_name" value="<?php
if (count($all_users) > 0) {echo $all_users[0]['bank_name'];}?>" class="form-control col-md-7 col-xs-12">
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="password">Branch Name<span class="required">*</span>
                        </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" id="branch_name" required="required" name="branch_name" value="<?php
if (count($all_users) > 0) {echo $all_users[0]['branch_name'];}?>" class="form-control col-md-7 col-xs-12">
                    </div>
                </div><div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="password">PO Box<span class="required">*</span>
                        </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="number" id="po_box" required="required" name="po_box" value="<?php
if (count($all_users) > 0) {echo $all_users[0]['po_box'];}?>" class="form-control col-md-7 col-xs-12">
                    </div>
                </div><div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="password">Account Name<span class="required">*</span>
                        </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" id="account_name" required="required" name="account_name" value="<?php
if (count($all_users) > 0) {echo $all_users[0]['account_name'];}?>" class="form-control col-md-7 col-xs-12">
                    </div>
                </div><div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="password">IBAN No<span class="required">*</span>
                        </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" id="iban_no" required="required" name="iban_no" value="<?php
if (count($all_users) > 0) {echo $all_users[0]['iban_no'];}?>" class="form-control col-md-7 col-xs-12">
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="password">Swift Code<span class="required">*</span>
                        </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" id="swift_code" required="required" name="swift_code" value="<?php
if (count($all_users) > 0) {echo $all_users[0]['swift_code'];}?>" class="form-control col-md-7 col-xs-12">
                    </div>
                </div>

                <!-- <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="password">Address<span class="required">*</span>
                        </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" id="address" required="required" name="address" value="<?php
if (count($all_users) > 0) {echo $all_users[0]['address'];}?>" class="form-control col-md-7 col-xs-12">
                    </div>
                </div> -->

                <!-- <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="password">Post Code<span class="required">*</span>
                        </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" id="post_code" required="required" name="post_code" value="<?php
if (count($all_users) > 0) {echo $all_users[0]['post_code'];}?>" class="form-control col-md-7 col-xs-12">
                    </div>
                </div>
                 -->
                <!-- <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Sort Code <span class="required">*</span>
                        </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" id="sort_code"  required="required" name="sort_code" value="<?php
if (count($all_users) > 0) {echo $all_users[0]['sort_code'];}?>" class="form-control col-md-7 col-xs-12">
                    </div>
                </div>
                 --> 
                <!-- <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">SWIFT-BIC <span class="required">*</span>
                        </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" id="swift_bic"  required="required" name="swift_bic" value="<?php
if (count($all_users) > 0) {echo $all_users[0]['swift_bic'];}?>" class="form-control col-md-7 col-xs-12">
                    </div>
                </div>
                 -->
<!--                 <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">IBAN <span class="required">*</span>
                        </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" id="iban"  required="required" name="iban" value="<?php
if (count($all_users) > 0) {echo $all_users[0]['iban'];}?>" class="form-control col-md-7 col-xs-12">
                    </div>
                </div>
 -->
<!--                 <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Clearance Date <span class="required">*</span>
                        </label>
                    <div class='col-md-6 col-sm-6 col-xs-12 input-group date' id='dateto'>
                        <input type='text' class="form-control" name="clearance_date" value="<?php
if (count($all_users) > 0) {echo date('m/d/Y',strtotime($all_users[0]['clearance_date']));}?>" required=""/>
                      <span class="input-group-addon">
                         <span class="glyphicon glyphicon-calendar"></span>
                      </span>
                    </div>
                </div>
-->               
<!--   <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Credit Limit <span class="required">*</span>
                        </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" id="credit_limit"  required="required" name="credit_limit" value="<?php
if (count($all_users) > 0) {echo $all_users[0]['credit_limit'];}?>" class="form-control col-md-7 col-xs-12">
                    </div>
                </div>
 -->
                <!-- <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="skip_credit_check">Skip Credit Check</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
           
                        <select required="" class="form-control select_withoutcross" id="skip_credit_check" name="skip_credit_check">

                            <option <?php if(count($all_users) > 0 && $all_users[0]['skip_credit_check'] == 'No'){ echo 'selected'; }?> value="No">No</option>
                            <option <?php if(count($all_users) >0 && $all_users[0]['skip_credit_check'] == 'Yes'){ echo 'selected'; }?> value="Yes">Yes</option>
                        </select>
                    </div>
                </div>
 -->

                <!-- <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Credit Days<span class="required">*</span>
                        </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" id="credit_days"  required="required" name="credit_days" value="<?php
if (count($all_users) > 0) {echo $all_users[0]['credit_days'];}?>" class="form-control col-md-7 col-xs-12">
                    </div>
                </div>

 -->

<!--                 <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="skip_credit_check">Contrast Allowed</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
           
                        <select required="" class="form-control select_withoutcross" id="contrast_allowed" name="contrast_allowed">

                            <option <?php if(count($all_users) > 0 && $all_users[0]['contrast_allowed'] == 'No'){ echo 'selected'; }?> value="No">No</option>
                            <option <?php if(count($all_users) >0 && $all_users[0]['contrast_allowed'] == 'Yes'){ echo 'selected'; }?> value="Yes">Yes</option>
                        </select>
                    </div>
                </div>
 -->                
                <!-- <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="deposit_required">Deposit Required</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
           
                        <select required="" class="form-control select_withoutcross" id="deposit_required" name="deposit_required">

                            <option <?php if(count($all_users) > 0 && $all_users[0]['deposit_required'] == 'No'){ echo 'selected'; }?> value="No">No</option>
                            <option <?php if(count($all_users) >0 && $all_users[0]['deposit_required'] == 'Yes'){ echo 'selected'; }?> value="Yes">Yes</option>
                        </select>
                    </div>
                 -->
             <!-- </div> -->


                <!-- <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="deposit_required">Cleared Funds</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
           
                        <select required="" class="form-control select_withoutcross" id="clearance_funds" name="clearance_funds">

                            <option <?php if(count($all_users) > 0 && $all_users[0]['clearance_funds'] == 'No'){ echo 'selected'; }?> value="No">No</option>
                            <option <?php if(count($all_users) >0 && $all_users[0]['clearance_funds'] == 'Yes'){ echo 'selected'; }?> value="Yes">Yes</option>
                        </select>
                    </div>
                </div>
                 -->
                <div class="ln_solid"></div>
                <div class="form-group">
                    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                        <button type="button" id="sendbtn" class="btn btn-success">Submit</button>
                        <a href="<?php echo base_url().'users/bank_detail/'.$this->uri->segment(3); ?>" class="btn btn-primary">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>
<script>
    $(document).ready(function(){
      // Call Geo Complete
      // $("#city").geocomplete({details:"form#demo-form2"});
    });
    $('#dateto').datetimepicker({
                format: 'MM/DD/YYYY',
            });
    var url = '<?php echo base_url(); ?>';
    var formaction_path = '<?php echo $formaction_path; ?>';
    $(document).ready(function() {
        // You can use the locally-scoped $ in here as an alias to jQuery.
        $(function() {
            $("#sendbtn").on('click', function(e) { //e.preventDefault();
                var formData = new FormData($("#demo-form2")[0]);
                if (!validator.checkAll($("#demo-form2")[0])) {} else {
                    var user_id = $("#user_id").val();
                    // alert(formaction_path);
                    $.ajax({
                        url: url + 'users/users/' + formaction_path,
                        type: 'POST',
                        data: formData,
                        cache: false,
                        contentType: false,
                        processData: false
                    }).then(function(data) {
                         var objData = jQuery.parseJSON(data);
                        ;
                        if (objData.msg == 'user_listing') {
                             // dz2.processQueue();
                            window.location = url + 'users/bank_detail/'+user_id;

                        }
                         else {
                            $('.msg-alert').css('display', 'block');
                            $("#result").html('<div class="alert" ><div class="alert alert-domain alert-danger alert-dismissible fade in  msg-alert" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true" style="color: black;">×</span> </button>' + data + '</div></div>');
                              $("#result").fadeTo(2000, 500).slideUp(500, function(){
                                $("#result").slideUp(500);
                                });
                        }
                    });
                }
            });
        });
    });

    $(".alert").fadeTo(2000, 500).slideUp(500, function(){
        $(".alert").slideUp(500);
    });
</script>