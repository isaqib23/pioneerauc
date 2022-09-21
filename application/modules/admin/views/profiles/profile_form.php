<style type="text/css">
    #page-wrapper {
        margin-top: 100px;
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
            <form method="post" novalidate="" id="demo-form2" action="" enctype="multipart/form-data" class="form-horizontal form-label-left">
            
                <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
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

                <?php
                // $profile = $this->db->get('users')->row_array();
                 if(isset($all_users[0]['picture']) && !empty($all_users[0]['picture'])){
                    $pfile = $this->db->get_where('files', ['id' => $all_users[0]['picture']])->row_array();
                    if (!empty($pfile)) {?>
                    <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="profile_picture">Profile Picture</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                             <img  height="100" src="<?= base_url($pfile['path'] . $pfile['name']); ?>">
                        </div>
                    </div>
                    <input type="hidden" name="old_profile_picture" value="<?php echo (isset($all_users[0]['picture']) && !empty($all_users[0]['picture'])) ? $all_users[0]['picture'] : ''; ?>">
                   <?php }?>
                <?php } ?>

                <div class="item form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="profile_picture"> <?php if(isset($all_users[0]['picture']) && !empty($all_users[0]['picture'])){ echo 'New '; }?>Profile Picture
                        </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="profile_picture" name="profile_picture" type="file" class="file">
                        <span>Max Image Size: 5Mb</span></div>
                </div>
                <div class="item form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="city">Address
                        </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" id="city" required="required" name="city" value="<?php
                            if (count($all_users) > 0) {echo $all_users[0]['city'];}?>" class="form-control col-md-7 col-xs-12" autocomplete="off">
                    </div>
                </div>

                <input type="hidden" name="lat" id="lat" > 
           <input type="hidden" name="lng" id="lng" >

                <div class="item form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="phone">Phone<span class="required">*</span>
                        </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="tel" id="mobile1" name="phone" oninput="this.value=this.value.replace(/[^0-9.]/g,'');" class="form-control col-md-7 col-xs-12">
                        <div class="mobile-error text-danger" ></div>
                    </div>
                    <input type="hidden" name="mobile_code" id="mobile">
                </div>


                <div class="item form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Email <span class="required"></span>
                        </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="email" id="email"  required="required" name="email" value="<?php
                        if (count($all_users) > 0) {echo $all_users[0]['email'];}?>" class="form-control col-md-7 col-xs-12" readonly>
                    </div>
                </div>

                <div class="ln_solid"></div>
                <div class="form-group">
                    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                        <button type="button" id="sendbtn" class="btn btn-success">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="<?php echo base_url(); ?>assets_admin/js/jquery.geocomplete.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=<?= $this->config->item('google_map_key'); ?>&libraries=places"></script>
<script>
  // Call Geo Complete
 $("#city").geocomplete({details:"form#demo-form2"});
     $(document).ready(function(){


     $(document).ready(function(){
        $("#mobile1").intlTelInput({
            allowDropdown: true,
            autoPlaceholder: "polite",
            placeholderNumberType: "MOBILE",
            formatOnDisplay: true,
            separateDialCode: true,
            nationalMode: false,
            autoHideDialCode: true,
            // hiddenInput: "mobile",
            preferredCountries: [ "sa", "ae" ]
        });
        $("#mobile1").intlTelInput("setNumber", "<?= isset($all_users) ? $all_users[0]['mobile'] : ''; ?>");
    });
 
});
  // Call Geo Complete
  // $("#city").geocomplete({details:"form#demo-form2"});


    var url = '<?php echo base_url(); ?>';
    var formaction_path = '<?php echo $formaction_path; ?>';
    $(document).ready(function() {
        // You can use the locally-scoped $ in here as an alias to jQuery.
        $(function() {
            $("#sendbtn").on('click', function(e) { //e.preventDefault();
            
            $('.mobile-error').hide();
            var user_phone = $("#mobile1").val();
            if(user_phone == '' || user_phone == null || user_phone == 'undefined')
            {
                $('.mobile-error').html('This value is required.').show();
                $('input[name="phone"]').focus();

                return false;
            }
            var intlNumberCode = $("#mobile1").intlTelInput("getSelectedCountryData");
            // var a = $(".mobile").intlTelInput("getNumber");
             // console.log($("#phone1").intlTelInput('getNumber'));
             console.log(intlNumberCode);
             $('#mobile').val(intlNumberCode.dialCode);
                var formData = new FormData($("#demo-form2")[0]);
                if (!validator.checkAll($("#demo-form2")[0])) {} else {
                    // alert(formaction_path);
                    $.ajax({
                        url: url + 'admin/Dashboard/' + formaction_path,
                        type: 'POST',
                        data: formData,
                        cache: false,
                        contentType: false,
                        processData: false
                    }).then(function(data) {
                        console.log(data);
                        if (data == 'success') {

                            window.location = url + 'admin/Dashboard/update_profile';

                        } else {
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

    // $(".alert").fadeTo(2000, 500).slideUp(500, function(){
    // $(".alert").slideUp(500);
    // });
  
</script>

<!-- /////////////////////// country code///////////////// -->
<link rel="stylesheet" type="text/css" href="https://intl-tel-input.com/node_modules/intl-tel-input/build/css/intlTelInput.css" />
<script type="text/javascript" src="https://intl-tel-input.com/node_modules/intl-tel-input/build/js/intlTelInput.js"></script>

<script type="text/javascript">

var input = document.querySelector("#phone1"),
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

// on keyup / change flag: reset
input.addEventListener('change', reset);
input.addEventListener('keyup', reset);
</script>