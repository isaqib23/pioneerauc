 <!-- <script src="https://maps.googleapis.com/maps/api/js?key=<?//= $this->config->item('google_map_key'); ?>&libraries=places"></script>  -->

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
                <div class="alert alert-domain alert-success alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true" style="color: black;">×</span>
                      </button>
                    <?php echo $this->session->flashdata('msg'); ?>
                </div>
            </div>
            <?php }?>


            <br />

            
            <form method="post"  novalidate="" name="myForm" id="demo-form3" action="" enctype="multipart/form-data" class="form-horizontal form-label-left" >
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
                        <input type="email" id="email"  required="required" name="email"  <?php if (isset($all_users) &&  !empty($all_users)) {echo '';}else{ echo ' data-parsley-remote data-parsley-remote-validator="mycustom_email" data-parsley-remote-message="Email already exists." data-parsley-trigger="focusin focusout" '; }?>  value="<?php
                            if (count($all_users) > 0) {echo $all_users[0]['email'];}?>" class="form-control col-md-7 col-xs-12">
                    </div>
                </div>

                  <div class="item form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="mobile">Mobile <span class="required">*</span>
                        </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="tel" <?php if (isset($all_users) &&  count($all_users) > 0) {echo '';}else{  }?>  required="" id="phone1" name="mobile" value="<?php
                        if (count($all_users) > 0) {echo $all_users[0]['mobile'];}?>" class="form-control col-md-7 col-xs-12">

                     <span class="duplicate_msg" id="message__" style="display: none;"  >
                        <!-- <div style="color: red"> <h5 class="duplicate_msg" id="duplicate_msg">
                            Number Alreadt Exist </h5></div> -->
                    </span>
                    <span id="valid-msg" class="hide"></span>
                    <span id="error-msg" class="hide"></span>
                  
                    </div>
                  
                    <input type="hidden" name="mobile_code" id="mobile_hidden">
                </div>

                <?php if(empty($all_users)){ ?>
                <div class="item form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="password">Password <span class="required">*</span>
                        </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" id="password" required="required" name="password" value="<?php
                        if (count($all_users) > 0) {echo $all_users[0]['password'];}?>" class="form-control col-md-7 col-xs-12">
                    </div>
                    <?php $get_users_list_by_id ?>
                 <input type="button" class=" custom-class-info" value="Generate" onClick="generate();" tabindex="2">
                </div>
                <?php } ?>

                <div style="display: none;"  id="social_div" class="item form-group">    
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">How did u hear about us? <span class="required">*</span>
                </label>
                
                <div class="col-md-6 col-sm-6 col-xs-12"> 
                    <select required="" class="form-control select2" id="social" name="social">
                        <option >Select Answer</option>
                        
                        <option <?php if(isset($all_users) && !empty($all_users) && $all_users[0]['social'] == 'website '){ echo 'selected'; }?> value="website">Website </option>
                        <option <?php if(isset($all_users) && !empty($all_users) && $all_users[0]['social'] == 'social_media'){ echo 'selected'; }?> value="social_media">Social Media</option>
                        <option <?php if(isset($all_users) && !empty($all_users) && $all_users[0]['social'] == 'google'){ echo 'selected'; }?> value="google">Google</option> 
                        <option <?php if(isset($all_users) && !empty($all_users) && $all_users[0]['social'] == 'sms'){ echo 'selected'; }?> value="sms">SMS</option> 
                        <option <?php if(isset($all_users) && !empty($all_users) && $all_users[0]['social'] == 'email'){ echo 'selected'; }?> value="email">Email</option> 
                        <option <?php if(isset($all_users) && !empty($all_users) && $all_users[0]['social'] == 'referral'){ echo 'selected'; }?> value="referral">Referral</option> 
                        <option <?php if(isset($all_users) && !empty($all_users) && $all_users[0]['social'] == 'organisation'){ echo 'selected'; }?> value="organisation">Organization</option>
                      
                        
                    </select>
                </div>
                </div>



                <div  id="reg_type_div" class="item form-group">    
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Registration Type <span class="required">*</span>
                </label>
                


                <div class="col-md-6 col-sm-6 col-xs-12"> 
                    <select required="" class="form-control select2" id="reg_type" name="reg_type">
                        <option disabled >Select User Registration Type</option>
                        <option <?php if(isset($all_users) && !empty($all_users) && $all_users[0]['reg_type'] == 'individual'){ echo 'selected'; }?> value="individual">Individual</option>
                        <option <?php if(isset($all_users) && !empty($all_users) && $all_users[0]['reg_type'] == 'organisation'){ echo 'selected'; }?> value="organisation">Organisation</option>
                        
                    </select>
                </div>
                </div>


                <div  id="prefered_language_div" class="item form-group">    
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Preferred Language <span class="required"></span>
                    </label>
                    
                    <div class="col-md-6 col-sm-6 col-xs-12"> 
                        <select required="" class="form-control select2" id="prefered_language" name="prefered_language">
                            <option disabled >Select Prefered language</option>
                            <option <?php if(isset($all_users) && !empty($all_users) && $all_users[0]['prefered_language'] == 'english'){ echo 'selected'; }?> value="english">English</option>
                            <option <?php if(isset($all_users) && !empty($all_users) && $all_users[0]['prefered_language'] == 'arabic'){ echo 'selected'; }?> value="arabic">Arabic</option>
                        </select>
                    </div>
                </div>
                <?php $id = $this->uri->segment(3);?>
                <input type="hidden" name="status" value="1">
                <div class="ln_solid"></div>
                <div class="form-group">

                    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">              
                        <button type="submit" id="sendbtn" class="btn btn-success">Submit</button>
                        <?php if(isset($_GET['user_type']) && $_GET['user_type'] == 'customers'){
                            $redirect_to = 'customers';
                        }else{
                            
                            $redirect_to = 'users';
                        } ?>

                        <?php if(isset($_GET['sh']) && $_GET['sh'] == 'au'){?>
                            <input type="hidden" value="sold" name="sold">
                        <a id="cancel" class="btn btn-primary" href="<?php echo base_url().'livecontroller/sold/items'; ?>"  >Cancel</a>
                        <?php }else{?>
                        <a id="cancel" class="btn btn-primary" href="<?php echo base_url().'auction/auctionDeposit/'.$id;?>"  >Cancel</a>
                    <?php }?>
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
            // hiddenInput: ".mobile",
            preferredCountries: [ "ae" ]
        });
    });


    var base_url = '<?php echo base_url();?>';
        //setup before functions
    var typingTimer;                //timer identifier
    var doneTypingInterval = 500;  //time in ms, 5 second for example
    var $input = $('#phone1');

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
            data: {mobile: mobile,mobile_code:mobile_code, [token_name]:token_value},
            success : function(data) {
                var objData = jQuery.parseJSON(data);
               if (objData.msg == 'duplicate') {   
                $('.duplicate_msg').show();
                $('.duplicate_msg').html('<h5 style="color:red;">Number Already Exist. </h5>');
                $('.duplicate_msg').focus();

                }  
                else{
                    $(".duplicate_msg").hide();
                }
            }
            
        });
    })

    if($('#reg_type').on('load'))
    {
        if($('#reg_type').val() == 'organisation')
        {
            $('#company_name_div').show()
            $('#company_name').attr('disabled', false);
        }
    }

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

        // You can use the locally-scoped $ in here as an alias to jQuery.
        $('#demo-form3').parsley().on('field:validated', function() {
            var ok = $('.parsley-error').length === 0;
        }) .on('form:submit', function() {
            var intlNumberCode = $("#phone1").intlTelInput("getSelectedCountryData");
            $('#mobile_hidden').val(intlNumberCode.dialCode);
            var formData = new FormData($("#demo-form3")[0]);
            $("#sendbtn").attr('disabled', 'disabled');
            // alert(formaction_path);
            $.ajax({
                url: url + 'auction/' + formaction_path,
                type: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false
            }).then(function(data) {
                 var objData = jQuery.parseJSON(data);
                 console.log(data);
                 console.log(objData.msg);
                if(objData.msg == 'user_listing')
                {
                    window.location = url + 'auction/save_user';
                } else {
                    // alert('aaaaa');
                    $('.msg-alert').css('display', 'block');
                    $("#result").html('<div class="alert" ><div class="alert alert-domain alert-danger alert-dismissible fade in  msg-alert" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true" style="color: black;">×</span> </button>' + objData.mid + '</div></div>');
                    $("#result").fadeTo(2000, 500).slideUp(500, function(){
                        $("#result").slideUp(500);
                    });
                    $("#sendbtn").removeAttr('disabled');
                }
            });
            return false;
        });

        // $("#sendbtn").on('click', function(e) { //e.preventDefault();    
            
        // });
    });
    

    // $(".alert").slideUp(500)fadeTo(2000, 500).function(){
    //     $(".alert").slideUp(500);
    // };

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

                        $.post(link, {id: id, obj: obj, file: file, type:type, [token_name]:token_value}, function(result){
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


<!-- /////////////////////// country code///////////////// -->
<link rel="stylesheet" type="text/css" href="https://intl-tel-input.com/node_modules/intl-tel-input/build/css/intlTelInput.css" />
<script type="text/javascript" src="https://intl-tel-input.com/node_modules/intl-tel-input/build/js/intlTelInput.js"></script>

<script type="text/javascript">

var input = document.querySelector("#phone2"),
errorMsg = document.querySelector("#error-msg"),
validMsg = document.querySelector("#valid-msg");
var errorMap = ["Invalid number", "Invalid country code", "Too short", "Too long", "Invalid number"];


// Lookup
var iti = window.intlTelInput(input, {
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

});


var reset = function() {
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
});

</script>