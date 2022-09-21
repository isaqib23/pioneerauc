<div class="col-md-10">
	<div class="message"></div>
</div>

<form method="post"  novalidate="" name="myForm" id="demo-form2" action="" enctype="multipart/form-data" class="form-horizontal form-label-left">
    <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
    <input type="hidden" name="crm_id" value="<?= $crm_info; ?>">
            
                <?php
                if(isset($all_users) && !empty($all_users)){?>
                    <input type="hidden" name="post_id" value="<?= $all_users[0]['id']; ?>">
                    <input type="hidden" name="user_id" value="<?= $all_users[0]['id']; ?>">
                <?php
                }
                ?>
                <div class="item form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="fname">First Name<span class="required">*</span>
                        </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" id="fname"   required="required" name="fname" value="<?php
                        if (isset($all_users) && !empty($all_users)) {echo $all_users[0]['fname'];}?>" class="form-control col-md-7 col-xs-12">
                    </div>
                </div>
                <div class="item form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="lname">Last Name<span class="required">*</span>
                        </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" id="lname" required="required" name="lname" value="<?php
                        if (isset($all_users) && !empty($all_users)) {echo $all_users[0]['lname'];}?>" class="form-control col-md-7 col-xs-12">
                    </div>
                </div>
                <div class="item form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Email <span class="required">*</span>
                        </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="email" id="email"  required="required" name="email" value="<?php
                        if (isset($all_users) && !empty($all_users)) {echo $all_users[0]['email'];}?>" class="form-control col-md-7 col-xs-12">
                    </div>
                </div>

                <?php if(empty($all_users)){ ?>
                <div class="item form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="password">Password<span class="required">*</span>
                        </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="password" id="password" name="password" value="<?php
                        if (isset($all_users) && !empty($all_users)) {echo $all_users[0]['password'];}?>" class="form-control col-md-7 col-xs-12">
                    </div>

                    <!-- <?php print_r($all_roles); ?> -->
                    <?php $get_users_list_by_id ?>
                </div>
                <?php } ?> 
                <div class="item form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="role_id">User Role</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                
                        <!-- <select class="form-control col-md-7 col-xs-12" onChange="checkOption(this)" id="role_id" name="role_id" required> -->
                        <select class="form-control col-md-7 col-xs-12" id="role_id" name="role_id" required>
                         
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
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Account Type <span class="required">*</span>
                </label>
                
                <div class="col-md-6 col-sm-6 col-xs-12"> 
                    <select class="form-control select2" id="type" name="type">
                        <option disabled="" >Select User Type</option>
                        <option <?php if(isset($all_users) && !empty($all_users) && $all_users[0]['type'] == 'buyer'){ echo 'selected'; }?> value="buyer">Buyer</option>
                        <option <?php if(isset($all_users) && !empty($all_users) && $all_users[0]['type'] == 'vender'){ echo 'selected'; }?> value="vender">Vender</option>
                        <option <?php if(isset($all_users) && !empty($all_users) && $all_users[0]['type'] == 'both'){ echo 'selected'; }?> value="both">Both</option>
                    </select>
                </div>
                </div>



                 <div style="display: none;"  id="sales_person_div" class="item form-group">    
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Sales Person <span class="required">*</span>
                </label>
                
                <div class="col-md-6 col-sm-6 col-xs-12"> 
                    <select class="form-control select2" id="sales_id" name="sales_person">
                             <option disabled="" selected="" >Select User Role</option>
                        <?php if(isset($sales_person) && !empty($sales_person)){ ?>
                             <?php foreach ($sales_person as $sales_person) { ?>
                            <option 
                            <?php if(isset($all_users) && !empty($all_users)){
                                if($all_users[0]['role'] == $all_roles['id']){ echo 'selected';}
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
                    <select  class="form-control select2" id="social" name="social">
                        <option >Select Answer</option>
                        
                        <option <?php if(isset($all_users) && !empty($all_users) && $all_users[0]['social'] == 'website '){ echo 'selected'; }?> value="individual">Website </option>
                        <option <?php if(isset($all_users) && !empty($all_users) && $all_users[0]['social'] == 'social_media'){ echo 'selected'; }?> value="organisation">Social Media</option>
                        <option <?php if(isset($all_users) && !empty($all_users) && $all_users[0]['social'] == 'google'){ echo 'selected'; }?> value="organisation">Google</option> 
                        <option <?php if(isset($all_users) && !empty($all_users) && $all_users[0]['social'] == 'sms'){ echo 'selected'; }?> value="organisation">SMS</option> 
                        <option <?php if(isset($all_users) && !empty($all_users) && $all_users[0]['social'] == 'email'){ echo 'selected'; }?> value="organisation">Email</option> 
                        <option <?php if(isset($all_users) && !empty($all_users) && $all_users[0]['social'] == 'referral'){ echo 'selected'; }?> value="organisation">Referral</option> 
                        <option <?php if(isset($all_users) && !empty($all_users) && $all_users[0]['social'] == 'organisation'){ echo 'selected'; }?> value="organisation">Newspaper Ads</option>
                        
                    </select>
                </div>
                </div> 

                <div style="display: none;"  id="reg_type_div" class="item form-group">    
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Registration Type <span class="required">*</span>
                </label>
                
                <div class="col-md-6 col-sm-6 col-xs-12"> 
                    <select  class="form-control select2" id="reg_type" name="reg_type">
                        <option >Select User Registration Type</option>
                        <option <?php if(isset($all_users) && !empty($all_users) && $all_users[0]['reg_type'] == 'individual '){ echo 'selected'; }?> value="individual">Individual </option>
                        <option <?php if(isset($all_users) && !empty($all_users) && $all_users[0]['reg_type'] == 'organisation'){ echo 'selected'; }?> value="organisation">Organisation</option>
                        
                    </select>
                </div>
                </div>

                 <div style="display: none;" id="company_name_div" class="item form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="phone">Company Name
                        </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id="company_name" name="company_name" value="<?php
                if (isset($all_users) && !empty($all_users)) {echo $all_users[0]['company_name'];}?>" class="form-control col-md-7 col-xs-12">
                    </div>
                </div> 

                <?php
                if(isset($all_users) && !empty($all_users))
                { ?>
                    <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="profile_picture">Profile Picture
                        </label>
                        <input type="hidden" name="old_files" value="<?= $all_users[0]['picture']; ?>">
                        <?php 
                        if(isset($all_users[0]['picture']) && !empty($all_users[0]['picture'])){
                        $file = $this->db->get_where('files',['id' => $all_users[0]['picture']])->row_array();
                      ?>
                        <img id="img"  height="100" width="150" src="<?= base_url($file['path'] . $file['name']); ?>" alt="">
                      <?php }else
                        { ?>
                             <div class="col-md-3 col-sm-3 col-xs-12">
                                <!-- <img  height="100" src="<?php echo base_url();?>uploads/profile_picture/<?php echo (isset($all_users[0]['picture']) && !empty($all_users[0]['picture'])) ? $all_users[0]['picture'] : 'default.png'; ?>"> -->
                                <img id="img" src="<?php echo base_url('assets_user/images/no_image');?>" height="100" width="130" alt="">
                            </div> 
                        <?php
                        }?>                    
                        
                    </div>
                <?php
                }
                ?> 
                 
                <div class="item form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="profile_picture"> Profile Picture
                        </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">

                        <!-- <input id="profile_picture" name="profile_picture[]" type="file" class="file" multiple> -->
                        <input id="profile_picture" name="profile_picture" type="file" class="file">
                        <span>Max Image Size: 5Mb</span></div>
                </div>         
                 <div class="item form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="lname">Buyer Commission 
                        </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" id="buyer_commission" name="buyer_commission" value="<?php
                        if (isset($all_users) && !empty($all_users)) {echo $all_users[0]['buyer_commission'];}?>" class="form-control col-md-7 col-xs-12">
                    </div>
                </div>

                <div class="item form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="address">Address
                        </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" id="address" name="address" value="<?php
                        if (isset($all_users) && !empty($all_users)) {echo $all_users[0]['address'];}?>" class="form-control col-md-7 col-xs-12">
                    </div>
                </div>
                <div class="item form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="city">City
                        </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" id="city" name="city" value="<?php
                        if (isset($all_users) && !empty($all_users)) {echo $all_users[0]['city'];}?>" class="form-control col-md-7 col-xs-12">
                    </div>
                </div>

                <div class="item form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="mobile1">Mobile <span class="required"></span>
                        </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" readonly required id="mobile1" value="<?php
                        if (isset($all_users) && !empty($all_users)) {echo $all_users[0]['phone'];}?>" class="form-control col-md-7 col-xs-12">
                    </div>
                </div>





            <div style="display: none;" id="id_number_div"  class="item form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="phone">ID Number
                        </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" id="id_number" name="id_number" value="<?php
                        if (isset($all_users) && !empty($all_users)) {echo $all_users[0]['id_number'];}?>" class="form-control col-md-7 col-xs-12">
                    </div>
                </div>

                <div  id="po_box_div" style="display: none;" class="item form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="phone">PO Box
                        </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" id="po_box" name="po_box" value="<?php
                        if (isset($all_users) && !empty($all_users)) {echo $all_users[0]['po_box'];}?>" class="form-control col-md-7 col-xs-12">
                    </div>
                </div>

                <div style="display: none" id="fax_div" class="item form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="phone">Remarks
                        </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" id="fax" name="remarks" value="<?php
                        if (isset($all_users) && !empty($all_users)) {echo $all_users[0]['remarks'];}?>" class="form-control col-md-7 col-xs-12">
                    </div>
                </div>


                <div style="display: none" id="description_div" class="item form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="phone">Description
                        </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" id="description" name="description" value="<?php
                        if (isset($all_users) && !empty($all_users)) {echo $all_users[0]['description'];}?>" class="form-control col-md-7 col-xs-12">
                    </div>
                </div>


                <div style="display: none;" id="job_title_div" class="item form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="phone">Job Title
                        </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" id="job_title" name="job_title" value="<?php
                        if (isset($all_users) && !empty($all_users)) {echo $all_users[0]['job_title'];}?>" class="form-control col-md-7 col-xs-12">
                    </div>
                </div>

                   <div style="display: none;"  id="prefered_language_div" class="item form-group">    
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Preferred Language 
                </label>
                
                <div class="col-md-6 col-sm-6 col-xs-12"> 
                    <select  class="form-control select2" id="prefered_language" name="prefered_language">
                        <option >Select Prefered language</option>
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
                        if (isset($all_users) && !empty($all_users)) {echo $all_users[0]['vat_number'];}?>" class="form-control col-md-7 col-xs-12">
                    </div>
                </div>


               
                <input type="hidden" name="status" value="1"> 
                
                <div class="ln_solid"></div>
                <div class="form-group">
                    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                        <button type="submit" id="sendbtn" class="btn btn-success">Submit</button>
                        <a href="<?php echo base_url().'crm'; ?>" class="custom-class" type="submit">Cancel</a>
                    </div>
                </div>
            </form>

<script>
    $('#role_id').on('change',function(){
        if($('#role_id').val() == 4){
            $('#type_id').show();
            $('#id_number_div').show()
            $('#po_box_div').show()
            $('#fax_div').show()
            $('#job_title_div').show()
            $('#VAT_div').show()
            if($('#VAT_div').val() == 'applicable')
            {
            $('#vat_number_div').show()  
             $('#vat_number').attr('disabled', false);  
            }
            // $('#company_name_div').show()
            $('#name_on_check_div').show();
            $('#sales_person_div').show();
            $('#description_div').show();
            $('#prefered_language_div').show();
            $('#reg_type_div').show();
            $('#social_div').show();


            $('#type').attr('required', true);
            $('#sales_id').attr('required', true);
            $('#social').attr('required', true);
            $('#reg_type').attr('required', true);

            $('#social').attr('disabled', false);
            $('#reg_type').attr('disabled', false);
            $('#prefered_language').attr('disabled', false);
            $('#vat').attr('disabled', false);
            $('#description').attr('disabled', false);
            $('#type').attr('disabled', false);
            $('#id_number').attr('disabled', false);
            $('#po_box').attr('disabled', false);
            $('#fax').attr('disabled', false);
            $('#job_title').attr('disabled', false);
            $('#vet_number').attr('disabled', true);
            // $('#company_name').attr('disabled', false);
            $('#name_on_check').attr('disabled', true);

        }else{
            if($('#type_id').hide())
            {
                $('#id_number_div').hide()
                $('#po_box_div').hide()
                $('#fax_div').hide()
                $('#job_title_div').hide()
                $('#VAT_div').hide()
                $('#vat_number_div').hide()  
                 if($('#VAT_div').val() == 'applicable')
                {
                $('#vat_number_div').hide()  
                $('#vat_number').attr('disabled', false);  
                }

                $('#company_no_div').hide()
                $('#name_on_check_div').hide()
                $('#sales_person_div').hide()
                $('#description_div').hide()
                $('#reg_type_div').hide()
                $('#social_div').hide()


                $('#type').attr('required', false);
                $('#sales_id').attr('required', false);
                $('#social').attr('required', false);
                $('#reg_type').attr('required', false);

                $('#social').attr('disabled', true);
                $('#reg_type').attr('disabled', true);
                $('#description').attr('disabled', true);
                $('#sales_person').attr('disabled', true);
                $('#vat').attr('disabled', true);
                $('#type').attr('disabled', true);
                $('#company_no').attr('disabled', true);
                $('#id_number').attr('disabled', true);
                $('#po_box').attr('disabled', true);
                $('#fax').attr('disabled', true);
                $('#job_title').attr('disabled', true);
            }
        }
    });

    if($('#reg_type').on('load'))
    {
        if($('#reg_type').val() == 'organisation')
        {
            $('#company_name_div').show()
            $('#company_name').attr('disabled', false);

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
            $('#job_title_div').show()
            $('#vet_number_div').show()
            $('#company_no_div').show()
            $('#name_on_check_div').show()
            $('#name_on_check_div').show()
            $('#sales_person_div').show()
            $('#reg_type_div').show();
            $('#social_div').show();
            $('#description_div').show();
            $('#prefered_language_div').show();
            $('#VAT_div').show()
            if($('#VAT_div').val() == 'applicable')
            {
            $('#vat_number_div').show()  
             $('#vat_number').attr('disabled', false);  
            }


            $('#sales_person_div').attr('required', true);
            $('#reg_type_div').attr('required', true);
            $('#social_div').attr('required', true);

            $('#type').attr('disabled', false);
            $('#id_number').attr('disabled', false);
            $('#po_box').attr('disabled', false);
            $('#fax').attr('disabled', false);
            $('#job_title').attr('disabled', false);
            $('#vet_number').attr('disabled', true);
            $('#company_no').attr('disabled', false);
            $('#name_on_check').attr('disabled', true);
        }
    }
    
    if($('#vat').val() == 'applicable')
    {
        $('#vat_number_div').show()  
        $('#vat_number').attr('disabled', false);  
    }


    $('#reg_type').on('change', function(){
        if($('#reg_type').val() == 'organisation')
        {
            $('#company_name_div').show() 
            $('#company_name').attr('disabled', false);  
        }else {
            $('#company_name_div').hide() 
            $('#company_name').attr('disabled', true);
        }
    });



    $(document).ready(function(){
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
        $("#mobile1").intlTelInput("setNumber", "<?= isset($crm_info) ? $crm_list[0]['mobile'] : ''; ?>");
      // Call Geo Complete
      // $("#city").geocomplete({details:"form#demo-form2"});
    });

</script>
<script>
    // $('#sendbtn').on('click', function(){
    //     alert('fdfcx');
    // });
    var url = '<?php echo base_url(); ?>';
    var formaction_path = '<?php echo $formaction_path; ?>';

       // You can use the locally-scoped $ in here as an alias to jQuery.
        $('#demo-form2').parsley().on('field:validated', function() {
            var ok = $('.parsley-error').length === 0;
          }) .on('form:submit', function() {
            var formData = new FormData($("#demo-form2")[0]);
            // alert(formaction_path);
            $('#sendbtn').attr('disabled', 'disabled');
            $.ajax({
                url: url + 'crm/' + formaction_path,
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
                    window.location = url + 'crm';
                }
                if(objData.msg == 'apllication_user')
                {
                    window.location = url + 'crm';
                }else{
                    $('.msg-alert').css('display', 'block');
                    $("#result").html('<div class="alert" ><div class="alert alert-domain alert-danger alert-dismissible fade in  msg-alert" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true" style="color: black;">×</span> </button>' + data + '</div></div>');
                    $('#sendbtn').removeAttr('disabled');
                    $("#result").fadeTo(2000, 500).slideUp(500, function(){
                        $("#result").slideUp(500);
                    });
                }
            });
            return false;
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
                    });

                } else {
                    swal("Cancelled", "Your action has been cancelled!", "error");
                }
            }

            );

        }else{
            swal("Error!", "Information Missing. Please reload the page and try again.", "error");
        }
    }

</script>
<script type="text/javascript">

	// $('#send').on('click',function(){
    //           var url = '<?php echo base_url();?>';
    //           var formData2 = new FormData($("#demo-form2")[0]);
    //           // console.log(id);
    //            $.ajax({
    //             url: url + 'crm/update_assign_to',
    //             type: 'POST',
    //             data: formData2,
    //             cache: false,
    //             contentType: false,
    //             processData: false
    //             }).then(function(data) {
    //               var objData = jQuery.parseJSON(data);
    //               console.log(objData);
    //               if (objData.msg == 'success') 
    //               { 
    //                  window.location = url + 'crm';
    //               }
    //               else
    //               {
    //               	$('.msg-alert').css('display', 'block');
    //                 $(".message").html('<div class="alert" ><div class="alert alert-domain alert-danger alert-dismissible fade in  msg-alert" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: black"><span aria-hidden="true">×</span> </button>' + objData.data + '</div></div>');
    //               }

    //       });
    //     });

</script>