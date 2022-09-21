<!-- PNotify -->
<link href="<?php echo base_url(); ?>assets_admin/vendors/pnotify/dist/pnotify.css" rel="stylesheet">


<link rel="stylesheet" href="<?= base_url(); ?>assets_user/tools/PhoneMask/css/intlTelInput.min.css">
<!-- Phone Mask -->
<script src="<?= base_url(); ?>assets_user/tools/PhoneMask/js/intlTelInput-jquery.min.js"></script>
<script src="<?= base_url(); ?>assets_user/tools/PhoneMask/js/utils.js"></script>

<!-- <script src="https://code.jquery.com/jquery-1.12.4.js"></script> -->
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/3.1.62/jquery.inputmask.bundle.js"></script>
<script type="text/javascript">
    
$(document).ready(function()
{
    // $('#mobile').inputmask('## ### ####');
   //  var phones = [{ "mask": "## ### ####"}, { "mask": "## ### ####"}];
   //  $('#mobile').inputmask({ 
   //      mask: phones, 
   //      greedy: false, 
   //      definitions: { '#': { validator: "[0-9]", cardinality: 1}} 
   //  });
});
</script>
<div class="main-wrapper contact-us">
        <div class="contact-banner bg-primary">
            <h1><?= $this->lang->line('contact_us_new')?></h1>
            <h2><?= $this->lang->line('love_to_hear')?></h2>
        </div>
        <div class="container">
            <div class="row justify-content-between">
                <div class="col-xl-5 col-lg-5 col-md-12">
                    <h3><?= $this->lang->line('reach_to_us')?></h3>
                    <p><?= $this->lang->line('any_questions_or_suggestions')?></p>
                    <form method="post" action="<?php echo base_url('visitor/contactUs_visitor');?>">
                        <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
                        <div class="form-group">
                            <label><?=$this->lang->line('f_name');?> <span>*</span></label>
                            <input type="text" class="form-control" placeholder="<?= $this->lang->line('enter_first_name');?>" name="fname">
                            <span class="fname-error text-danger"></span>
                        </div>
                        <div class="form-group">
                            <label><?= $this->lang->line('last_name')?> <span>*</span></label>
                            <input type="text" class="form-control" placeholder="<?= $this->lang->line('enter_last_name');?>" name="lname">
                            <span class="lname-error text-danger"></span>
                        </div>
                        <div class="form-group">
                            <label><?= $this->lang->line('email_address')?>  <span>*</span></label>
                            <input type="text" class="form-control" placeholder="<?= $this->lang->line('enter_email_address');?>" name="emailContact">
                            <span class="emailContact-error text-danger"></span>
                        </div>
                        <div class="form-group">
                            <label><?= $this->lang->line('phone_num');?> <span>*</span></label>
                            <!-- <input type="hidden" name="number1"> -->
                            <input type="text" 
                                class="form-control" 
                                id="mobile" 
                                name="mobileContact" 
                                maxlength="9"
                                placeholder="50 123 4567" 
                                oninput="this.value=this.value.replace(/[^0-9]/g,'');">
                                <br>
                            <span class="mobileContact-error text-danger"></span>
                        </div>
                        <div class="form-group">
                            <label><?= $this->lang->line('message')?><span> *</span></label>
                            <textarea class="form-control" placeholder="<?= $this->lang->line('write_your_msg_here');?>..." name="text"></textarea>
                            <span class="text-error text-danger"></span>
                        </div>
                        <div class="button-row">
                            <button type="submit" class="btn btn-primary sub_btn" id="sub_btn"><?=$this->lang->line('submit');?></button>
                        </div>
                    </form>
                </div>
                <div class="col-xl-6 col-lg-5 col-md-12">
                    <h3><?= $this->lang->line('customer_care')?></h3>
                    <p><?= $this->lang->line('get_in_touch')?></p>
                    <ul class="contact-info">
                        <li>
                             <span class="material-icons">
                                local_phone
                            </span>
                            <a href="#" class="ltr"><?php echo $contact_us_info['toll_free']; ?></a>
                        </li>
                        <li>
                            <span class="material-icons">
                                email
                            </span>
                            <a href="<?php echo 'mailto:'.$contact_us_info['email'];?>"><?php echo $contact_us_info['email']; ?></a>
                        </li>
                        <li>
                            <span class="material-icons">
                            location_on
                            </span>

                            <?= $this->lang->line('contact_us_address')?>
                            

                        </li>
                    </ul>
                        <?php $social = $this->db->get('social_links')->row_array();?>
                    <div class="social-media">
                        <h3><?= $this->lang->line('social_media')?></h3>
                        <ul>
                            <li>
                                <span>
                                   <img src="<?= NEW_ASSETS_USER; ?>/new/images/logo-fb.svg" alt="">
                                </span>    
                                <p>
									<?= $this->lang->line('be_the_first_to_find_auctions');?> <br>
                                    <a href="<?php echo $social['facebook']?>"target="_blank"> <?= $this->lang->line('like_us_on_facebook')?> </a><?= $this->lang->line('today')?>!
                                </p>
                            </li>
                            <li>
                                <span>
                                     <img src="<?= NEW_ASSETS_USER; ?>/new/images/logo-insta.svg" alt="">
                                </span>
                                <p>
                                    <?= $this->lang->line('stay_updated_text')?><br>
                                    <a href="<?php echo $social['linked_in']?>"target="_blank"><?= $this->lang->line('follow_on_instagram')?>  </a><?= $this->lang->line('today')?>!
                                </p>
                            </li>
                        </ul>
                    </div>
<iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d28871.619000913044!2d55.371281!3d25.238529!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x2112d5139b5a9a9e!2sPioneer%20Auctions!5e0!3m2!1sen!2sae!4v1617003016564!5m2!1sen!2sae" width="400" height="300" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                    
                </div>
            </div>
        </div>
    </div>

    <!-- Notify JS Files -->
<script src="<?php echo base_url(); ?>assets_admin/vendors/pnotify/dist/pnotify.js"></script>
<script>

// function initialize() {
//     var lat = "<?php echo $contact_us_info['lat']; ?>";
//     var lng = "<?php echo $contact_us_info['lng']; ?>";
//     // var lat = .239243;
//     var latlng = new google.maps.LatLng(lat,lng);
//     var map = new google.maps.Map(document.getElementById('map'), {
//         center:new google.maps.LatLng(lat,lng),
//       zoom: 13
//     });
//     var marker = new google.maps.Marker({
//       map: map,
//       position: latlng,
//       draggable: false,
//       anchorPoint: new google.maps.Point(0, -29)
//    });
//     var infowindow = new google.maps.InfoWindow();   
//     google.maps.event.addListener(marker, 'click', function() {
//       var iwContent = '<div id="iw_container">' +
//       '<div class="iw_title"><b>Location</b> : '+ address +' </div></div>';
//       // including content to the infowindow
//       infowindow.setContent(iwContent);
//       // opening the infowindow in the current map and at the current marker location
//       infowindow.open(map, marker);
//     });
// }

$('#sub_btn').on('click',function(e){
 e.preventDefault();
    var validation = false;
    var language = '<?= $language; ?>';

    selectedInputs = ['fname','lname','emailContact','mobileContact','text'];
    validation = validateFields(selectedInputs,language);

    if(validation == false){
        return false;
    }
    if(validation == true){
        $(this).closest("form").submit();
    }

}); 
</script>
<!-- <?php 
if($language == 'arabic'){
    $ln_map = '&region=EG&language=ar';
}else{
    $ln_map = '';
}
?> -->

<script type="text/javascript">
    $(document).ready(function(){
        <?php if($this->session->flashdata('msg')){ ?>
            var message = "<?php echo $this->session->flashdata('msg'); ?>";
            new PNotify({
                text: message,
                type: 'success',
                addclass: 'custom-success',
                title: '<?= $this->lang->line('success_');?>',
                styling: 'bootstrap3'
            });
        <?php } ?>

        $("#mobile").intlTelInput({
            allowDropdown: true,
            autoPlaceholder: "polite",
            placeholderNumberType: "MOBILE",
            formatOnDisplay: true,
            separateDialCode: true,
            nationalMode: false,
            autoHideDialCode: true,
            language:"english",
            //hiddenInput: "mobile",
            preferredCountries: [ "ae", "sa" ]
        });
    });
</script>
