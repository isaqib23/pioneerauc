
<style type="text/css">
    #page-wrapper {
        margin-top: 100px;
    }
    .dz-image img{
    max-width: 120px;
    max-height: 120px;
    }

    .iti {height: 32px;}
   
</style>
<input type="hidden" id="base_url" value="<?php echo base_url(); ?>">
<div class="clearfix"></div>
<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>
              <?php if(isset($all_users) && !empty($all_users)) echo 'Update for '.$all_users[0]['username'];{ ?>
                </h2>
                <?php }?>

                 <h2>
              <?php if(isset($small_title) && !empty($small_title)) echo $small_title;{ ?>
                </h2>
                <?php }?>
              
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <div id="result"></div>  

            <?php if ($this->session->flashdata('msg')) { ?>
            <div class="alert">
                <div class="alert alert-domain alert-success alert-dismissible fade in" id="video" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true" style="color: black;">×</span>
                      </button>
                    <?php echo $this->session->flashdata('msg'); ?>
                </div>
            </div>
            <?php }?>

            <br />    
            <form method="post"  novalidate="" name="myForm" id="demo-form2" action="" enctype="multipart/form-data" class="form-horizontal form-label-left" >
                <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
             <input type="hidden" name="length" value="8">
                <?php
                if(count($all_users) > 0){?>
                    <input type="hidden" name="post_id" value="<?= $all_users[0]['id']; ?>">
                    <input type="hidden" name="user_id" value="<?php echo  $this->uri->segment(3);?>">
                <?php
                }
                ?>

                <div class="item form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="fname">First Name<span class="required">*</span>
                        </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" id="fname" required="required" name="fname" value="<?php
if (count($all_users) > 0) {echo $all_users[0]['fname'];}?>" class="form-control col-md-7 col-xs-12">
                    </div>
                </div>
                <div class="item form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="lname">Last Name<span class="required">*</span>
                        </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" id="lname" required="required" name="lname" value="<?php
if (count($all_users) > 0) {echo $all_users[0]['lname'];}?>" class="form-control col-md-7 col-xs-12">
                    </div>
                </div>
                <div class="item form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Email <span class="required">*</span>
                        </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" 
                            id="email"  
                            required="required" 
                            name="email"  
                            <?php if (isset($all_users) &&  !empty($all_users)) {echo '';}else{ echo ' data-parsley-remote data-parsley-remote-validator="mycustom_email" data-parsley-remote-message="Email already exists." data-parsley-trigger="focusin focusout" '; }?>
                            value="<?php if(count($all_users) > 0) {echo $all_users[0]['email']; }?>" 
                            class="form-control col-md-7 col-xs-12">
                    <div id="emailErrors" class="text-danger"></div>
                    </div>
                </div>
                <script>
                    $(document).ready(function(){
                        $('.alert').hide();
                    });
                </script>

                <div class="item form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="mobile">Mobile <span class="required">*</span></label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="tel" oninput="this.value=this.value.replace(/[^0-9.]/g,'');" <?php if (isset($all_users) &&  count($all_users) > 0) {echo '';}else{  }?>  required="" id="phone1" name="mobile" value="<?php
                        if (count($all_users) > 0) {echo $all_users[0]['mobile'];}?>" class="form-control col-md-7 col-xs-12">          
                        <div class="duplicate_msg" id="message__" style="display: none;"  ></div>   
                    </div>  
                        <input type="hidden" name="mobile_code" id="mobile_hidden">
                </div>
                <?php if (!empty($all_users)) {
                    $passwordText = 'Change password';
                    $required = '';
                }else{
                    $passwordText = 'Password';
                    $required = 'required';
                }?>
                <div class="item form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="password"><?= $passwordText;?><span class="required">*</span>
                        </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" id="password" <?= $required;?> name="password" oninput="this.value=this.value.replace(/[^0-9a-zA-Z!@#$%^&*()_-]/g,'');" class="form-control col-md-7 col-xs-12">
                    </div>
                    <?php $get_users_list_by_id ?>
                 <input type="button" class=" custom-class-info" value="Generate" onClick="generate();" tabindex="2">
                </div>

                <?php
                $hide = '';
                if(isset($_GET['user_type']) && $_GET['user_type'] == 'customers'){
                    $hide = 'style="display:none;"';
                } ?>

                <div class="item form-group" <?= $hide; ?> >
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="role_id">User Role <span class="required">*</span></label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                
                        <select class="form-control col-md-7 col-xs-12" id="role_id" name="role_id" required >
                            
                                <option disabled="" value="" >Select User Role</option>
                                 <?php foreach ($all_roles as $all_roles) { ?>
                                <option 
                                <?php if(isset($all_users) && !empty($all_users)){
                                    if($all_users[0]['role'] == $all_roles['id']){ echo 'selected';}
                                }?>
                                    value="<?php echo $all_roles['id']; ?>"><?php echo $all_roles['name']; ?></option>
                            <?php  } ?> 
                        </select>

                    </div>
                </div>

                 <div style="display: none;"  id="type_id" class="item form-group">    
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Account Type <span class="required"></span>
                </label>
                
                <div class="col-md-6 col-sm-6 col-xs-12"> 
                    <select  class="form-control select2" id="type" name="type">
                        <option disabled="" >Select Customer Type</option>
                        <option <?php if(isset($all_users) && !empty($all_users) && $all_users[0]['type'] == 'buyer'){ echo 'selected'; }?> value="buyer">Buyer</option>
                        <option <?php if(isset($all_users) && !empty($all_users) && $all_users[0]['type'] == 'vendor'){ echo 'selected'; }?> value="vendor">Vendor</option>
                        <option <?php if(isset($all_users) && !empty($all_users) && $all_users[0]['type'] == 'both'){ echo 'selected'; }?> value="both">Both</option>
                    </select>
                </div>
                </div>


                 <div style="display: none;"  id="sales_person_div" class="item form-group">    
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Sales Person <span ></span>
                </label>
                
                <div class="col-md-6 col-sm-6 col-xs-12"> 
                    <select class="form-control select2" id="sales_id" name="sales_person">
                             <option disabled="" selected="" >Select Customer Role</option>
                        <?php if(isset($sales_person) && !empty($sales_person)){ ?>
                             <?php foreach ($sales_person as $sales_person) { ?>
                            <option 
                            <?php if(isset($all_users) && !empty($all_users)){
                                if($all_users[0]['sales_id'] == $sales_person['id']){ echo 'selected';}
                            }?>
                                value="<?php echo $sales_person['id']; ?>"><?php echo $sales_person['username']; ?></option>
                                <?php  }} ?> 
                    </select>
                </div>
                </div>
                <div style="display: none;"  id="social_div" class="item form-group">    
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">How did u hear about us? <span class="required">*</span>
                </label>
                
                <div class="col-md-6 col-sm-6 col-xs-12"> 
                    <select class="form-control select2" id="social" name="social">
                        <option value="">Select Answer</option>
                        
                        <option <?php if(isset($all_users) && !empty($all_users) && $all_users[0]['social'] == 'website'){ echo 'selected'; }?> value="website">Website </option>
                        <option <?php if(isset($all_users) && !empty($all_users) && $all_users[0]['social'] == 'social_media'){ echo 'selected'; }?> value="social_media">Social Media</option>
                        <option <?php if(isset($all_users) && !empty($all_users) && $all_users[0]['social'] == 'google'){ echo 'selected'; }?> value="google">Google</option> 
                        <option <?php if(isset($all_users) && !empty($all_users) && $all_users[0]['social'] == 'sms'){ echo 'selected'; }?> value="sms">SMS</option> 
                        <option <?php if(isset($all_users) && !empty($all_users) && $all_users[0]['social'] == 'email'){ echo 'selected'; }?> value="email">Email</option> 
                        <option <?php if(isset($all_users) && !empty($all_users) && $all_users[0]['social'] == 'referral'){ echo 'selected'; }?> value="referral">Referral</option> 
                        <option <?php if(isset($all_users) && !empty($all_users) && $all_users[0]['social'] == 'organisation'){ echo 'selected'; }?> value="organisation">Organization</option>
                      
                        
                    </select>
                </div>
                </div>
                <div style="display: none;"  id="reg_type_div" class="item form-group">    
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Registration Type <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12"> 
                    <select class="form-control select2" id="reg_type" name="reg_type">
                        <option  value="">Select User Registration Type</option>
                        <option <?php if(isset($all_users) && !empty($all_users) && $all_users[0]['reg_type'] == 'individual'){ echo 'selected'; }?> value="individual">Individual</option>
                        <option <?php if(isset($all_users) && !empty($all_users) && $all_users[0]['reg_type'] == 'organisation'){ echo 'selected'; }?> value="organisation">Organisation</option>         
                    </select>
                </div>
                </div>

                <div style="display: none;" id="company_name_div" class="item form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="phone">Company Name
                        </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" id="company_name" name="company_name" value="<?php
                        if (count($all_users) > 0) {echo $all_users[0]['company_name'];}?>" class="form-control col-md-7 col-xs-12">
                    </div>
                </div>

                <div style="display: none;" id="job_title_div" class="item form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="phone">Job Title
                        </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" id="job_title" name="job_title" value="<?php if (count($all_users) > 0) {echo $all_users[0]['job_title'];}?>" class="form-control col-md-7 col-xs-12">
                    </div>
                </div>

                 <?php
                if(count($all_users)>0)
                { 
                    if(isset($all_users[0]['picture']) && !empty($all_users[0]['picture'])){
                    $pfile = $this->db->get_where('files', ['id' => $all_users[0]['picture']])->row_array();?>
                    <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="profile_picture">Profile Picture</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">                               
                            <img  height="100" src="<?= base_url($pfile['path'] . $pfile['name']); ?>">
                        </div>
                            <input type="hidden" name="old_files" value="<?= $all_users[0]['picture']; ?>">
                                                  
                    </div>
                     <?php }?> 
                <?php }?> 
                <div class="item form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="profile_picture"> Profile Picture
                        </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                         <!-- <input type="file" multiple="" name="images[]"> -->
                        <input id="profile_picture" name="profile_picture" type="file" class="file" accept="image/x-png,image/gif,image/jpeg" multiple>
                        <span>Max Image Size: 5Mb</span>
                        <div id="video_error" class="text-danger"></div>
                    </div>
                    
                </div>         
             <!--    <div style="display:none;" id="buyer_commission_div" class="item form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="lname">Buyer Commission<span class="required"></span>
                        </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" id="buyer_commission" name="buyer_commission" value="<?php
                            if (count($all_users) > 0) {echo $all_users[0]['buyer_commission'];}?>" class="form-control col-md-7 col-xs-12">
                    </div>
                </div> -->

                <div style="display:none;" id="address_div" class="item form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="address">Address
                        </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" id="address" name="address" value="<?php
                            if (count($all_users) > 0) {echo $all_users[0]['address'];}?>" class="form-control col-md-7 col-xs-12">
                    </div>
                </div>
                <div style="display:none;" id="city_div" class="item form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="city">City
                        </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" id="city" name="city" value="<?php
if (count($all_users) > 0) {echo $all_users[0]['city'];}?>" class="form-control col-md-7 col-xs-12">
                    </div>
                </div>

                <div style="display: none;" id="telephone_div"  class="item form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="phone">Telephone
                        </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" id="phone" oninput="this.value=this.value.replace(/[^0-9.]/g,'');" name="phone" value="<?php
if (count($all_users) > 0) {echo $all_users[0]['phone'];}?>" class="form-control col-md-7 col-xs-12">
                    </div>
                </div>

            <div style="display: none;" id="id_number_div"  class="item form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="phone">ID Number
                        </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" id="id_number" name="id_number" value="<?php
if (count($all_users) > 0) {echo $all_users[0]['id_number'];}?>" class="form-control col-md-7 col-xs-12">
                    </div>
            </div>

                <div  id="po_box_div" style="display: none;" class="item form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="phone">PO Box
                        </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" id="po_box" name="po_box" value="<?php
if (count($all_users) > 0) {echo $all_users[0]['po_box'];}?>" class="form-control col-md-7 col-xs-12">
                    </div>
                </div>

                <div style="display: none" id="fax_div" class="item form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="phone">Remarks
                        </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" id="fax" name="remarks" value="<?php
if (count($all_users) > 0) {echo $all_users[0]['remarks'];}?>" class="form-control col-md-7 col-xs-12">
                    </div>
                </div>


                <div style="display: none" id="description_div" class="item form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="phone">Description
                        </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" id="description" name="description" value="<?php
if (count($all_users) > 0) {echo $all_users[0]['description'];}?>" class="form-control col-md-7 col-xs-12">
                    </div>
                </div>


                <!-- <div style="display: none;" id="job_title_div" class="item form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="phone">Job Title
                        </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" id="job_title" name="job_title" value="<?php if (count($all_users) > 0) {echo $all_users[0]['job_title'];}?>" class="form-control col-md-7 col-xs-12">
                    </div>
                </div> -->

                <div style="display: none;"  id="prefered_language_div" class="item form-group">    
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Preferred Language <span class="required"></span>
                </label>
                
                <div class="col-md-6 col-sm-6 col-xs-12"> 
                    <select class="form-control select2" id="prefered_language" required name="prefered_language">
                        <option  >Select Prefered language</option>
                        <option <?php if(isset($all_users) && !empty($all_users) && $all_users[0]['prefered_language'] == 'english'){ echo 'selected'; }?> value="english">English</option>
                        <option <?php if(isset($all_users) && !empty($all_users) && $all_users[0]['prefered_language'] == 'arabic'){ echo 'selected'; }?> value="arabic">Arabic</option>
                    </select>
                </div>
            </div>




                  <div style="display: none;"  id="VAT_div" class="item form-group">    
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">VAT <span class="required"></span>
                </label>
                
                <div class="col-md-6 col-sm-6 col-xs-12"> 
                    <select class="form-control select2" id="vat" name="vat">
                        <option >Select VAT</option>
                        <option <?php if(isset($all_users) && !empty($all_users) && $all_users[0]['vat'] == 'applicable'){ echo 'selected'; }?> value="applicable">Applicable</option>
                        <option <?php if(isset($all_users) && !empty($all_users) && $all_users[0]['vat'] == 'exempt'){ echo 'selected'; }?> value="exempt">Exempt</option>
                    </select>
                </div>
                </div>

                <div style="display: none;" id="vat_number_div" class="item form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="phone">Vat Number
                        </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" id="vat_number" name="vat_number" value="<?php
if (count($all_users) > 0) {echo $all_users[0]['vat_number'];}?>" class="form-control col-md-7 col-xs-12">
                    </div>
                </div>

                <input type="hidden" name="status" value="1">
                <div class="ln_solid"></div>
                <div class="form-group">
                    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                        <!-- <div id="video_error" class="text-danger"></div> -->
                        <button type="submit" id="sendbtn" onclick="Validate(this)" class="btn btn-success">Submit</button>
                        <?php if(isset($_GET['user_type']) && $_GET['user_type'] == 'customers'){
                            $redirect_to = 'customers';
                        }else{
                            
                            $redirect_to = 'users';
                        } ?>
                        <a id="cancel" class="custom-class" href="<?php echo base_url().$redirect_to; ?>"  >Cancel</a>
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
        $("#phone1").intlTelInput({
            allowDropdown: true,
            autoPlaceholder: "polite",
            placeholderNumberType: "MOBILE",
            formatOnDisplay: true,
            separateDialCode: true,
            nationalMode: false,
            autoHideDialCode: true,
            // css: true;
            // hiddenInput: ".mobile",
            preferredCountries: [ "ae" ]
        });
    });


        var base_url = '<?php echo base_url();?>';
            //setup before functions
        var typingTimer;                //timer identifier
        var doneTypingInterval = 500;  //time in ms, 5 second for example
        //var $input = $('#phone1');

        //on keyup, start the countdown
        // $input.on('keyup', function () {
        //     check_mobile();
        //   // clearTimeout(typingTimer);
        //   // typingTimer = setTimeout(check_mobile, doneTypingInterval);
        // });

        //on keydown, clear the countdown 
        // $input.on('keydown', function () {
        //   clearTimeout(typingTimer);
        // });

        // function check_mobile(){
            $('input[name="password1"]').val('');
            $('#phone1').on('keyup',function(){ 
                var mobile = $("#phone1").val();
                var intlNumberCode = $("#phone1").intlTelInput("getSelectedCountryData");
                var mobile_code = intlNumberCode.dialCode;
                var user_id = '<?php echo $this->uri->segment('3'); ?>';
                if(user_id == null)
                {
                    user_id =  0;
                }
                $.ajax({
                url: base_url + 'users/check_dublication_mobile?user_id=' + user_id,
                type: 'POST',
                data: {mobile: mobile,mobile_code:mobile_code, [token_name] : token_value},
                success : function(data) {
                    var objData = jQuery.parseJSON(data);
                   if (objData.msg == 'duplicate') {   
                    $('.duplicate_msg').show();
                    $('.duplicate_msg').html('<h5 style="color:red;">Number already exists. </h5>');
                    $('.duplicate_msg').focus();

                    }  
                    else{
                        $(".duplicate_msg").hide();
                    }
                }
                
            });
        })
      //$('#role_id').on('change',function(){
     //if($('#role_id').val() == 4){
       // $('#type_id').show();
        // $('#id_number_div').show()
        // $('#po_box_div').show()
        // $('#fax_div').show()
        // $('#job_title_div').show()
        // $('#VAT_div').show()
        // if($('#VAT_div').val() == 'applicable')
        // {
        // $('#vat_number_div').show()  
        //  $('#vat_number').attr('disabled', false);  
        // }
        // // $('#company_name_div').show()
        // $('#name_on_check_div').show()
        // $('#sales_person_div').show()
        // $('#description_div').show()
        // $('#prefered_language_div').show()
        // $('#reg_type_div').show()
        // $('#social_div').show()
        // $('#buyer_commission_div').show()
        // $('#address_div').show()
        // $('#city_div').show()

        // $('#buyer_commission').attr('disabled', false);
        // $('#address').attr('disabled', false);
        // $('#city').attr('disabled', false);
        // $('#social').attr('disabled', false);
        // $('#reg_type').attr('disabled', false);
        // $('#prefered_language').attr('disabled', false);
        // $('#vat').attr('disabled', false);
        // $('#description').attr('disabled', false);
        // $('#type').attr('disabled', false);
        // $('#sales_id').attr('disabled', false);
        // $('#id_number').attr('disabled', false);
        // $('#po_box').attr('disabled', false);
        // $('#fax').attr('disabled', false);
        // $('#job_title').attr('disabled', false);
        // $('#vet_number').attr('disabled', true);
        // // $('#company_name').attr('disabled', false);
        // $('#name_on_check').attr('disabled', true);

     // }

     // else{
        $('#id_number_div').hide()
        $('#po_box_div').hide()
        $('#fax_div').hide()
        //$('#job_title_div').hide()
        $('#VAT_div').hide()
        //  if($('#VAT_div').val() == 'applicable')
        // {
        // $('#vat_number_div').show()  
        // $('#vat_number').attr('disabled', false);  
        // }
        $('#company_no_div').hide()
        $('#name_on_check_div').hide()
        $('#sales_person_div').hide()
        $('#description_div').hide()
        $('#reg_type_div').hide()
        $('#social_div').hide()
         $('#buyer_commission_div').hide()
        $('#address_div').hide()
        $('#city_div').hide()
        $('#prefered_language_div').hide()
        $('#telephone_div').hide()
        $('#vat_number_div').hide()  
        $('#company_name_div').hide() 
        $('#type_id').hide();

        $('#company_name').attr('disabled', true);
        $('#type').attr('disabled', true);

        
        $('#vat_number').attr('disabled', true);
        $('#phone').attr('disabled', true);
        $('#buyer_commission').attr('disabled', true);
        $('#address').attr('disabled', true);
        $('#city').attr('disabled', true);
        $('#social').attr('disabled', true);
        $('#reg_type').attr('disabled', true);
        $('#description').attr('disabled', true);
        $('#sales_person').attr('disabled', true);
        $('#vat').attr('disabled', true);
        $('#sales_id').attr('disabled', true);
        $('#company_no').attr('disabled', true);
        $('#id_number').attr('disabled', true);
        $('#po_box').attr('disabled', true);
        $('#fax').attr('disabled', true);
        $('#job_title').attr('disabled', true);
        $('#prefered_language').attr('disabled', true);

    //  }
    // });
     if($('#reg_type').on('load'))
      {
        if($('#reg_type').val() == 'organisation')
        {
            $('#company_name_div').show();
            $('#company_name').attr('disabled', false);

            $('#job_title_div').show();
            $('#job_title_div').attr('disabled', false);

        }

      }

      if($('#role_id').on('load'))
      {
        if($('#role_id').val() == 4)
        {
       $('#type_id').show();
        $('#id_number_div').show()
        $('#po_box_div').show()
        $('#fax_div').show()
        //$('#job_title_div').show()
        $('#VAT_div').show()
        if($('#VAT_div').val() == 'applicable')
        {
        $('#vat_number_div').show()  
         $('#vat_number').attr('disabled', false);  
        }
        // $('#company_name_div').show()
        $('#name_on_check_div').show()
        $('#sales_person_div').show()
        $('#description_div').show()
        $('#prefered_language_div').show()
        $('#reg_type_div').show()
        $('#social_div').show()
        $('#buyer_commission_div').show()
        $('#address_div').show()
        $('#city_div').show()

       $('#buyer_commission').attr('disabled', false);
        $('#address').attr('disabled', false);
        $('#city').attr('disabled', false);
        $('#social').attr('disabled', false);
        $('#social').attr('required', true);
        $('#reg_type').attr('disabled', false);
        $('#reg_type').attr('required', true);
        $('#prefered_language').attr('disabled', false);
        $('#vat').attr('disabled', false);
        $('#description').attr('disabled', false);
        $('#type').attr('disabled', false);
        $('#sales_id').attr('disabled', false);
        $('#id_number').attr('disabled', false);
        $('#po_box').attr('disabled', false);
        $('#fax').attr('disabled', false);
        $('#job_title').attr('disabled', false);
        $('#vet_number').attr('disabled', true);
        // $('#company_name').attr('disabled', false);
        $('#name_on_check').attr('disabled', true);
        }

      }



    $('#vat').on('change', function(){
     if($('#vat').val() == 'applicable')
        {
        $('#vat_number_div').show();
         $('#vat_number').attr('disabled', false);  
        }
        else{
             $('#vat_number_div').hide();
         $('#vat_number').attr('disabled', true);
        }
    });

       $('#reg_type').on('change', function(){
        if($('#reg_type').val() == 'organisation')
        {
            $('#company_name_div').show();
            $('#company_name').attr('disabled', false);  

            $('#job_title_div').show();
            $('#job_title_div').attr('disabled', false);  
        }
        else {
             $('#company_name_div').hide();
            $('#company_name').attr('disabled', true);

            $('#job_title_div').hide();
            $('#job_title_div').attr('disabled', true);
        }

       });



function randomPassword(length) {
            var chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOP1234567890";
            var pass = "";
            for (var x = 0; x < length; x++) {
                var i = Math.floor(Math.random() * chars.length);
                pass += chars.charAt(i);
            }
            return pass;
        }

        function generate() {

            myForm.password.value = randomPassword(myForm.length.value);

        }



     $(document).ready(function(){
  // Call Geo Complete
  // $("#city").geocomplete({details:"form#demo-form2"});
});

    var url = '<?php echo base_url(); ?>';
    var formaction_path = '<?php echo $formaction_path; ?>';
    var if_user_ = '<?php  echo (isset($all_users[0]['mobile']) && !empty($all_users[0]['mobile'])) ? $all_users[0]['mobile'] : ""; ?>';
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

        }, url+'users/validate_mobile');

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

        }, url+'users/validate_email');

    }
    $(document).ready(function() {
         var url = '<?php echo base_url(); ?>';
         
       // You can use the locally-scoped $ in here as an alias to jQuery.
        $('#demo-form2').parsley().on('field:validated', function() {
            var ok = $('.parsley-error').length === 0;
          }) .on('form:submit', function() {
                var intlNumberCode = $("#phone1").intlTelInput("getSelectedCountryData");
                 $('#mobile_hidden').val(intlNumberCode.dialCode);
                  var formData = new FormData($("#demo-form2")[0]);

                  var ok = Validate($('#demo-form2'));

                if(! ok){
                    setTimeout(function(){ $('#video_error').hide(); }, 5000);
                    return false;
                }

                  if(!isEmail($('input[name=email]').val())){
                    $('#emailErrors').html('Email is not valid.').show();
                    $('input[name=email]').focus();
                    setTimeout(function(){ $('#emailErrors').hide(); }, 3000);
                    return false;
                  }

                //   setTimeout(function() {
                    // $("#successMessage").hide('blind', {}, 500)
                // }, 5000);
                   
                    $.ajax({
                        url: url + 'users/' + formaction_path,
                        type: 'POST',
                        data: formData,
                        cache: false,
                        contentType: false,
                        processData: false,
                        beforeSend: function(){
                            $('#sendbtn').attr('disabled', true);
                            $('#sendbtn').html('Loading...');
                        }
                    }).then(function(data) {
                        console.log(data);
                        var objData = $.parseJSON(data);
                        console.log(objData);
                        var page;

                        if(objData.status == 'true'){
                            $("#result").html('<div class="alert" ><div class="alert alert-domain alert-success alert-dismissible fade in  msg-alert" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true" style="color: black;">×</span> </button>' + objData.msg + '</div></div>');
                            $('.msg-alert').css('display', 'block');
                            
                            if(objData.redirect == 'apllicationuser'){
                                page = 'users';
                            }else if(objData.redirect == 'user_listing'){
                                page = 'customers';
                            }

                            setTimeout(function(){
                                window.location = url + page;
                            }, 2000);
                        }

                        if(objData.status == 'false'){
                            $("#result").html('<div class="alert" ><div class="alert alert-domain alert-danger alert-dismissible fade in  msg-alert" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true" style="color: black;">×</span> </button>' + objData.msg + '</div></div>');
                            // $('.msg-alert').css('display', 'block');
                        }
                        $('#video_error').hide();
                        $('#sendbtn').attr('disabled', false);
                        $('#sendbtn').html('Submit');
                        window.scrollTo({top: 0, behavior: "smooth"});
                        $("#result").fadeTo(2000, 500).slideUp(1000, function(){
                            $("#result").slideUp(1000);
                        });

                    });
                    return false;
          });


            // $("#sendbtn").on('click', function(e) { //e.preventDefault();
          
                       
                
            // });
        });
    

    $(".alert").fadeTo(2000, 500).slideUp(500, function(){
    $(".alert").slideUp(500);
    });

    function deleteImage(xp){
        var id       = $(xp).data('id');
        var file      = $(xp).data('file');
        var type      = $(xp).data('type');
        var obj      = 'task_testing';
        var link     = base_url+"tasks/deleteFileForTaskTesting";

        if(id!='' && obj!=''){

            swal({
                    title: "Are you sure?",
                    text: "You cannot recover it later.",
                    type: "error",
                    showCancelButton: true,
                    cancelButtonClass: 'btn-default btn-md waves-effect',
                    confirmButtonClass: 'btn-danger btn-md waves-effect waves-light',
                    confirmButtonText: 'Confirm!'
                },
                function(isConfirm) {
                    if (isConfirm) {
                        var parent = $(xp).parents('.file_parent');
                        $("#loading").show();

                        $.post(link, {id: id, obj: obj, file: file, type:type}, function(result){
                                //console.log(result);
                                if(result!='0'){
                                    var data = JSON.parse(result);

                                    if(data.type == 'success'){
                                        //hide gallery image
                                        swal("Success!", data.msg, "success");
                                        $(parent).fadeOut("slow");
                                        $(parent).remove();
                                    }

                                    if(data.type == 'error'){
                                        swal("Error!", data.msg, "error");
                                    }

                                }else{
                                    swal("Error!", "Something went wrong.", "error");
                                }
                                $('#loading').hide();
                            }

                        );

                    } else {
                        swal("Cancelled", "Your action has been cancelled!", "error");
                    }
                }

            );

        }else{
            swal("Error!", "Information Missing. Please reload the page and try again.", "error");
        }
    }
    // $("#message").hide();

</script>


<!-- /////////////////////// country code///////////////// -->
<!-- <link rel="stylesheet" type="text/css" href="https://intl-tel-input.com/node_modules/intl-tel-input/build/css/intlTelInput.css" /> -->
<!-- <script type="text/javascript" src="https://intl-tel-input.com/node_modules/intl-tel-input/build/js/intlTelInput.js"></script> -->

<script type="text/javascript">

var input = document.querySelector("#phone2"),
errorMsg = document.querySelector("#error-msg"),
validMsg = document.querySelector("#valid-msg");
var errorMap = ["Invalid number", "Invalid country code", "Too short", "Too long", "Invalid number"];


// Lookup
/*var iti = window.intlTelInput(input, {
initialCountry: "auto",
nationalMode: false,
//formatOnDisplay: false,
hiddenInput: "full_phone",
geoIpLookup: function(callback) {
$.get('https://ipinfo.io', function() {}, "jsonp").always(function(resp) {
var countryCode = (resp && resp.country) ? resp.country : "";

callback(countryCode);
});
},
utilsScript: "https://intl-tel-input.com/node_modules/intl-tel-input/build/js/utils.js" // just for formatting/placeholders etc

});*/


/*var reset = function() {
input.classList.remove("error");
errorMsg.innerHTML = "";
errorMsg.classList.add("hide");
validMsg.classList.add("hide");
};

input.addEventListener('blur', function() {
reset();
if (input.value.trim()) {
if (iti.isValidNumber()) {
validMsg.classList.remove("hide");
document.getElementById('send').style.display = 'block';

} else {
input.classList.add("error");
var errorCode = iti.getValidationError();
errorMsg.innerHTML = errorMap[errorCode];
errorMsg.classList.remove("hide");
document.getElementById('send').style.display = 'none';

}
}
});*/

// on keyup / change flag: reset
// input.addEventListener('change', reset);
// input.addEventListener('keyup', reset);
</script>

<!-- ///// video file validation //////////// -->
<script>

    var _validFileExtensions = [".jpg", ".jpeg", ".bmp", ".gif", ".png"];
    function Validate(oForm) {
    var arrInputs = oForm.find('input[name=profile_picture]');
    // alert('fdfdf');
    for (var i = 0; i < arrInputs.length; i++) {
        var oInput = arrInputs[i];
        if (oInput.type == "file") {
            var sFileName = oInput.value;
            if (sFileName.length > 0) {
                var blnValid = false;
                for (var j = 0; j < _validFileExtensions.length; j++) {
                    var sCurExtension = _validFileExtensions[j];
                    if (sFileName.substr(sFileName.length - sCurExtension.length, sCurExtension.length).toLowerCase() == sCurExtension.toLowerCase()) {
                            blnValid = true;
                            break;
                     }
                }

                if (!blnValid) {
                    $("#video_error").html('The file type you are trying to upload is not allowed.').show();
                    oForm.find('input[name=profile_picture]').focus();
                    // $('.alert-domain').hide();
                     // alert("Sorry, The file type you are trying to upload is not allowed. ");
                     // window.location.reload(true);
                return false;
                }
            }
        }
    }

    return true;
    }
</script>