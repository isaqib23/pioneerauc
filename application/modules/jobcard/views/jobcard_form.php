

<!-- /////////////////////// country code///////////////// -->
<link rel="stylesheet" type="text/css" href="https://intl-tel-input.com/node_modules/intl-tel-input/build/css/intlTelInput.css" />
<script type="text/javascript" src="https://intl-tel-input.com/node_modules/intl-tel-input/build/js/intlTelInput.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/parsley.js/2.3.4/parsley.min.js"></script>
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
                    <input type="email" id="email" data-parsley-type="email" data-parsley-trigger="focusin focusout keyup blur" <?php if(isset($all_users) && !empty($all_users)) { echo ''; }else{ echo 'data-parsley-remote="'.base_url().'jobcard/validate_check_user_email/{value}" data-parsley-remote-validator="mycustom_email" data-parsley-remote-message="Email already exists."'; } ?> name="email" value="<?php if (count($all_users) > 0) {echo $all_users[0]['email'];}?>" class="form-control col-md-7 col-xs-12">
                    </div>
                </div>

                <?php if(empty($all_users)){ ?>
                <div class="item form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="password">Password<span class="required">*</span>
                        </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" id="password" required="required" name="password" value="<?php
if (count($all_users) > 0) {echo $all_users[0]['password'];}?>" class="form-control col-md-7 col-xs-12">
                    </div>
                     <input type="button" class=" custom-class-info" value="Generate" onClick="generate();" tabindex="2">
                    <?php $get_users_list_by_id ?>
                </div>
                <?php } ?>


             <!--    <div class="item form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="role_id">User Role</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <select class="form-control col-md-7 col-xs-12" id="role_id" name="role_id" required >
                             <option disabled="" value="" >Select User</option>
                             <?php
                            if(isset($job_cart_users) && !empty($job_cart_users))
                            {
                                foreach ($job_cart_users as $role_value) 
                                {
                              ?>
                            <option value="<?php echo $role_value['id']  ?>"><?php echo $role_value['name']  ?></option>
                            <?php  
                                } 
                            }
                             ?>
                        </select>
                    </div> 
                </div> -->
                 <?php
                if(count($all_users)>0)
                { ?>
                    <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="profile_picture">
                        </label>
                        <input type="hidden" name="old_files" value="<?= $all_users[0]['picture']; ?>">
                        <?php 
                        if (strpos($all_users[0]['picture'], ',') !== false) {
                            $images = explode(',', $all_users[0]['picture']);

                            
                            foreach ($images as $key => $value) { ?>
                                <div class="col-md-2 col-sm-2 col-xs-12">
                                    <img  height="100" width="150" src="<?php echo base_url();?>uploads/profile_picture/<?php echo $value; ?>">
                                </div>
                            <?php
                             }
                        }else
                        {   if(isset($all_users[0]['picture'])){ 
                            $image_name = $this->files_model->get_file_by_id($all_users[0]['picture']);
                            $tasker_id = $this->uri->segment(3);
                            if (isset($image_name[0]['name'])) {
                            
                            ?>

                            <div class="col-md-3 col-sm-3 col-xs-12">
                                <img  height="100" src="<?php echo base_url();?>uploads/profile_picture/<?php echo $tasker_id.'/'. $image_name[0]['name']; ?>">
                            </div>
                        <?php
                      }  } }?>                    
                        
                    </div>
                <?php
                }
                ?> 
                

                <div class="item form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="profile_picture"> Profile Picture<span class="required">*</span>
                        </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                         <!-- <input type="file" multiple="" name="images[]"> -->
                        <input id="profile_picture"  name="images" type="file" class="file" accept="image/x-png,image/gif,image/jpeg">
                        <!-- <span>Max Image Size: 5Mb</span> -->
                        <div class="images-error text-danger"></div>
                    </div>
                </div>         
                 <div style="display:none;" id="buyer_commission_div" class="item form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="lname">Buyer Commission<span class="required"></span>
                        </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" id="buyer_commission" name="buyer_commission" value="<?php
                            if (count($all_users) > 0) {echo $all_users[0]['buyer_commission'];}?>" class="form-control col-md-7 col-xs-12">
                    </div>
                </div>

            
               

               <!--  <div class="item form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="phone">Mobile
                        </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" pattern="[0-9]*" data-parsley-trigger="focusin focusout keyup change" data-parsley-type="digits" <?php if(isset($all_users) && !empty($all_users)) { echo ''; }else{ echo 'data-parsley-remote="'.base_url().'jobcard/validate_check_user_mobile/{value}" data-parsley-remote-validator="mycustom_mobile" data-parsley-remote-message="Mobile already exists"'; } ?> required="" id="mobile" name="mobile" value="<?php
if (count($all_users) > 0) {echo $all_users[0]['mobile'];}?>" class="form-control col-md-7 col-xs-12">
                    </div>
                </div>      -->    

                 <div class="item form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="mobile">Mobile <span class="required">*</span>
                        </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <!-- <input type="tel"
                                id="user-phone" 
                                name="mobile"
                                maxlength="11"
                                oninput="this.value=this.value.replace(/[^0-9]/g,'');"  class="mobile form-control"> -->
                        <input type="tel" <?php if (isset($all_users) &&  count($all_users) > 0) {echo '';}else{  }?>  id="user-phone" maxlength="11" name="mobile" oninput="this.value=this.value.replace(/[^0-9]/g,'');"  value="<?php
                if (count($all_users) > 0) {echo $all_users[0]['mobile'];}?>" class="mobile form-control col-md-7 col-xs-12">
                <span id="message">
                        <div style="color: red"> <h5 class="duplicate_msg" id="duplicate_msg">
                            </h5></div>
                    </span>
                    <span class="valid-error text-danger mobile-error"></span>
                    <!-- <span id="valid-msg" class="hide"></span> -->
                    <!-- <span id="error-msg" class="hide"></span> -->
                    </div>
                </div>       
                <input type="hidden" name="mobile_code" id="mobile_hidden">


                <input type="hidden" name="status" value="0">
                 <div class="item form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="phone">Status
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">

                        <select required="" class="form-control select_withoutcross" id="status" name="status">
                            <option <?php if(count($all_users) >0 && $all_users[0]['status'] == 0){ echo 'selected'; }?> value="0">Inactive</option>
                            <option <?php if(count($all_users) >0 && $all_users[0]['status'] == 1){ echo 'selected'; }?> value="1">Active</option>
                            
                        </select>
                    </div>
                </div>
                
                <div class="ln_solid"></div>
                <div class="form-group">
                    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                        <button type="submit" id="sendbtn" class="btn btn-success">Submit</button>
                        <a id="cancel" class="custom-class" href="<?php echo base_url().'jobcard'; ?>"  >Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>
<script>
      
         $(document).ready(function(){
        $(".mobile").intlTelInput({
            allowDropdown: true,
            autoPlaceholder: "polite",
            placeholderNumberType: "MOBILE",
            formatOnDisplay: true,
            separateDialCode: true,
            nationalMode: false,
            autoHideDialCode: true,
            // hiddenInput: ".mobile",
            preferredCountries: [ "ae" ]
        });
    });


let value_option = '';
window.Parsley.addAsyncValidator('mycustom_email', function (xhr_email) 
{
    // console.log(this.$element); // jQuery Object[ input[name="q"] ]
    var obj_email = this.$element;
    // console.log(obj_email.val());
    value_option = obj_email.val();
    // trigger: true;
    // console.log(value_option);
    var response = xhr_email.responseText;
    if (response === '200') 
    {
        // success if not exists
        return 200 === xhr_email.status;
    } 
    else 
    {
        // error if already exists
        return 404 === xhr_email.status;
    }

}); 

let value_option_mobile = '';
window.Parsley.addAsyncValidator('mycustom_mobile', function (xhr) 
{
    // console.log(this.$element); // jQuery Object[ input[name="q"] ]
    var obj = this.$element;
    // console.log(obj.val());
    value_option_mobile = obj.val();
    // console.log(value_option);
    var response = xhr.responseText;
    if (response === '200') 
    {
        // success if not exists
        return 200 === xhr.status;
    } 
    else 
    {
        // error if already exists
        return 404 === xhr.status;
    }

}); 


        $('#id_number_div').hide()
        $('#po_box_div').hide()
        $('#fax_div').hide()
        $('#job_title_div').hide()
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
        $('#reg_type').attr('disabled', false);
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
        $('#vat_number_div').show()  
         $('#vat_number').attr('disabled', false);  
        }
        else{
             $('#vat_number_div').hide()  
         $('#vat_number').attr('disabled', true);
        }
    });

       $('#reg_type').on('change', function(){
        if($('#reg_type').val() == 'organisation')
        {
            $('#company_name_div').show() 
            $('#company_name').attr('disabled', false);  
        }
        else {
             $('#company_name_div').hide() 
            $('#company_name').attr('disabled', true);
        }

       });




     $(document).ready(function(){
  // Call Geo Complete
  // $("#city").geocomplete({details:"form#demo-form2"});
});

    var url = '<?php echo base_url(); ?>';
    var formaction_path = '<?php echo $formaction_path; ?>';
    var edit = '<?php if(isset($all_users[0]['picture'])){ echo $all_users[0]['picture'];  } ?>'
    $(document).ready(function() {

       // You can use the locally-scoped $ in here as an alias to jQuery.
        $('#demo-form2').parsley().on('field:validated', function() {
            var ok = $('.parsley-error').length === 0;
          }) .on('form:submit', function() {
            $('.images-error').hide();
            $('.mobile-error').hide();
            var intlNumberCode = $(".mobile").intlTelInput("getSelectedCountryData");
            var filename = $("#profile_picture").val();
            var user_phone = $("#user-phone").val();
            var extension = filename.substr( (filename.lastIndexOf('.') +1) );
            if(edit == ''){
                if(extension == '' )
                {
                    $('.images-error').html('Select User Image').show();
                    return false;
                }
                if(user_phone == '' )
                {
                    $('.mobile-error').html('This value is required.').show();
                    $('input[name="mobile"]').focus();

                    return false;
                }
            }
            if((extension == 'jpg') || (extension == 'png') || (extension == 'jpeg') || (extension == ''))
            {
             $('#mobile_hidden').val(intlNumberCode.dialCode);
             var formData = new FormData($("#demo-form2")[0]);
                    $.ajax({
                        url: url + 'jobcard/' + formaction_path,
                        type: 'POST',
                        data:formData,
                        cache: false,
                        contentType: false,
                        processData: false
                    }).then(function(data) {
                         var objData = jQuery.parseJSON(data);
                            if(objData.msg == 'success'){
                            window.location = url + 'jobcard';
                        }else{
                                $('.msg-alert').css('display', 'block');
                                $("#result").html('<div class="alert" ><div class="alert alert-domain alert-danger alert-dismissible fade in  msg-alert" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true" style="color: black;">×</span> </button>' + objData.mid + '</div></div>');
                                  $("#result").fadeTo(2000, 500).slideUp(500, function(){
                                    $("#result").slideUp(500);
                                    });
                                }
                    });
                }else{  $('.msg-alert').css('display', 'block');
                            $("#result").html('<div class="alert" ><div class="alert alert-domain alert-danger alert-dismissible fade in  msg-alert" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true" style="color: black;">×</span> </button>This File is not allowed to upload</div></div>');
                              $("#result").fadeTo(2000, 500).slideUp(500, function(){
                                $("#result").slideUp(500);
                                });
                        }
                return false;
                
          });
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






  
</script>
</script>


<!-- /////////////////////// country code///////////////// -->
<link rel="stylesheet" type="text/css" href="https://intl-tel-input.com/node_modules/intl-tel-input/build/css/intlTelInput.css" />
<script type="text/javascript" src="https://intl-tel-input.com/node_modules/intl-tel-input/build/js/intlTelInput.js"></script>

<script type="text/javascript">


         $(document).ready(function(){
        $("#phone1").intlTelInput({
            allowDropdown: true,
            autoPlaceholder: "polite",
            placeholderNumberType: "MOBILE",
            formatOnDisplay: true,
            separateDialCode: true,
            nationalMode: false,
            autoHideDialCode: true,
            // hiddenInput: ".mobile",
            preferredCountries: ["VN" ]
        });
        //$("#user-phone").intlTelInput("setNumber", "<?php //echo isset($user['mobile']) ? $user['mobile'] : ''; ?>");
    });

// var input = document.querySelector("#phone1"),
// errorMsg = document.querySelector("#error-msg"),
// validMsg = document.querySelector("#valid-msg");
// var errorMap = ["Invalid number", "Invalid country code", "Too short", "Too long"];


// // Lookup
// var iti = window.intlTelInput(input, {
// initialCountry: "auto",
// nationalMode: false,
// //formatOnDisplay: false,
// hiddenInput: "full_phone",
// geoIpLookup: function(callback) {
// $.get('https://ipinfo.io', function() {}, "jsonp").always(function(resp) {
// var countryCode = (resp && resp.country) ? resp.country : "";

// callback(countryCode);
// });
// },
// utilsScript: "https://intl-tel-input.com/node_modules/intl-tel-input/build/js/utils.js" // just for formatting/placeholders etc

// });


// var reset = function() {
// input.classList.remove("error");
// errorMsg.innerHTML = "";
// errorMsg.classList.add("hide");
// validMsg.classList.add("hide");
// };

// input.addEventListener('blur', function() {
// reset();
// if (input.value.trim()) {
// if (iti.isValidNumber()) {
// validMsg.classList.remove("hide");
// document.getElementById('send').style.display = 'block';

// } else {
// input.classList.add("error");
// var errorCode = iti.getValidationError();
// errorMsg.innerHTML = errorMap[errorCode];
// errorMsg.classList.remove("hide");
// document.getElementById('send').style.display = 'none';

// }
// }
// });

// // on keyup / change flag: reset
// input.addEventListener('change', reset);
// input.addEventListener('keyup', reset);
 var base_url = '<?php echo base_url();?>';
            //setup before functions
        // var typingTimer;                //timer identifier
        // var doneTypingInterval = 500;  //time in ms, 5 second for example
        // var $input = $('#user-phone');

        // //on keyup, start the countdown
        // $input.on('keyup', function () {
        //   clearTimeout(typingTimer);
        //   typingTimer = setTimeout(check_mobile, doneTypingInterval);
        // });

        // //on keydown, clear the countdown 
        // $input.on('keydown', function () {
        //   clearTimeout(typingTimer);
        // });

         // $('#user-phone').on('change',function(){ 
         //    alert('aaa');
         // })

       $('#user-phone').on('keyup',function(){ 
                var mobile = $("#user-phone").val();
                var intlNumberCode = $("#user-phone").intlTelInput("getSelectedCountryData");
                var mobile_code = intlNumberCode.dialCode;
                var user_id = '<?php echo $this->uri->segment('3'); ?>';
                if(user_id == null)
                {
                    user_id =  0;
                }
                $.ajax({
                url: base_url + 'jobcard/check_dublication_mobile?user_id=' + user_id,
                type: 'POST',
                data: {mobile: mobile,mobile_code:mobile_code},
                success : function(data) {
                    var objData = jQuery.parseJSON(data);
                   if (objData.msg == 'duplicate') {   
                    $('.duplicate_msg').show();
                    $('.duplicate_msg').html('<h5 style="color:red;">Number Alreadt Exist </h5>');
                    $('.duplicate_msg').focus();

                    }  
                    else{
                        $(".duplicate_msg").hide();
                    }
                }
                
            });
        })



        // function check_mobile(){
        //         var mobile = $("#user-phone").val();
        //         var intlNumberCode = $("#user-phone").intlTelInput("getSelectedCountryData");
        //         var mobile_code = intlNumberCode.dialCode;
        //         $.ajax({
        //         url: base_url + 'jobcard/check_dublication_mobile',
        //         type: 'POST',
        //         data: {mobile: mobile,mobile_code:mobile_code},
        //         success : function(data) {
        //             var objData = jQuery.parseJSON(data);
        //            if (objData.msg == 'duplicate') {   
        //            $(".duplicate_msg").html('Number already exist');  
        //             $("#message").focus();
        //             // $("#message").css("color","red");
        //             $("#message").css("font-size","2px;");
                
        //             }  
        //             else{
        //                 $(".duplicate_msg").hide();
        //             }
        //         }
                
        //     });
        //  }

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
</script>