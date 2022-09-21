<!-- PNotify -->
<link href="<?php echo base_url(); ?>assets_admin/vendors/pnotify/dist/pnotify.css" rel="stylesheet">


<link rel="stylesheet" href="<?= base_url(); ?>assets_user/tools/PhoneMask/css/intlTelInput.min.css">
<!-- Phone Mask -->
<script src="<?= base_url(); ?>assets_user/tools/PhoneMask/js/intlTelInput-jquery.min.js"></script>
<script src="<?= base_url(); ?>assets_user/tools/PhoneMask/js/utils.js"></script>

<style>  
	.iti--allow-dropdown #mobile {  
	    width: 385px;  
	}  
</style> 
<div class="main gray-bg contact-us">
	<h1 class="section-title"><?=$this->lang->line('contact_us');?></h1>
	<?php $address = json_decode($contact_us_info['address']); ?>
	<div class="container">
		<div class="row">
			<div class="col-sm-4">
				<h2><?= $this->lang->line('head_office');?></h2>
				<ul class="address-links">
					<li>
						<i>
							<img src="<?= NEW_ASSETS_IMAGES; ?>loaction-icon.png" alt="">
						</i>
						<?php if(!empty($contact_us_info['address'])){ echo $address->$language;}?>
					</li>
					<li>
						<i>
							<img src="<?= NEW_ASSETS_IMAGES; ?>message-icon2.png" alt="">
						</i>
						<a href="<?php echo 'mailto:'.$contact_us_info['email'];?>"><?php echo $contact_us_info['email']; ?></a>
					</li>
					<li>
						<i>
							<img src="<?= NEW_ASSETS_IMAGES; ?>contact-us-icon.png" alt="">
						</i>
						<a href="#" class="ltr"><?php echo $contact_us_info['mobile']; ?></a>
					</li>
					<li>
						<i>
							<img src="<?= NEW_ASSETS_IMAGES; ?>Fax-icon.png" alt="">
						</i>
						<a href="#" class="ltr"><?php echo $contact_us_info['fax']; ?></a>
					</li>
					<li>
						<p><?=$this->lang->line('toll_free');?>:
							<b dir="ltr">800 746 6337</b>
						</p>
					</li>
				</ul>
			</div>
			<div class="col-sm-8">
				<h2 style="text-align: left;"><?=$this->lang->line('write_us');?></h2>
				<form class="customform" action="<?php echo base_url('visitor/contactUs_visitor');?>">
					<input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
					<div class="row ">
						<div class="col-sm-6">
							<label><?=$this->lang->line('f_name');?> *</label>
							<input type="text" class="form-control" name="fname">
							<span class="fname-error text-danger"></span>
						</div>
						<div class="col-sm-6">
							<label><?=$this->lang->line('subject');?> *</label>
							<input type="text" class="form-control"  name="subject">
							<span class="subject-error text-danger"></span>
						</div>
						<div class="col-sm-6">
							<label><?=$this->lang->line('email');?> *</label>
							<input type="text" class="form-control" name="email">
							<span class="email-error text-danger"></span>
						</div>
						<div class="col-sm-6">
							<label><?=$this->lang->line('mobile');?> *</label>
							<input type="text" 
								class="form-control" 
								id="mobile" 
								name="mobile" 
								maxlength="12" 
								oninput="this.value=this.value.replace(/[^0-9]/g,'');">
							<span class="mobile-error text-danger"></span>
						</div>
						<div class="col-sm-12">
							<label><?=$this->lang->line('how_can');?> *</label>
							<textarea class="form-control" name="text"></textarea>
							<span class="text1-error text-danger"></span>
						</div>
						<div class="col-sm-12">
							<div style="width: 302px;" id="recaptcha1" class="g-recaptcha" data-sitekey="<?= $this->config->item('captcha_key');?>" data-expired-callback="recaptchaExpired">
	                        </div>
						</div>
						<div class="button-row">
							<button type="SUBMIT" class="btn btn-default sub_btn" id="sub_btn"><?=$this->lang->line('submit');?></button>
						</div>
					</div>
				</form>
			</div>
		</div>
		<div id="map" style="width: 100%; height: 300px;"></div>  
		<input type="hidden" name="currentLat" id="currentLat">
		<input type="hidden" name="currentLng" id="currentLng">
		

		<script src="<?php echo base_url(); ?>assets_admin/js/jquery.geocomplete.js"></script>
	</div>
</div>

<!-- Notify JS Files -->
<script src="<?php echo base_url(); ?>assets_admin/vendors/pnotify/dist/pnotify.js"></script>

<script>
	    // Recaptcha Code
	    function enableBtn1(){
	        document.getElementById("sub_btn").disabled = false;
	    }
	    var recaptcha1;
	    var myCallBack = function() {
	        recaptcha1 = grecaptcha.render('recaptcha1', {
	          'sitekey' : '<?= $this->config->item('captcha_key');?>',
	          'theme' : 'light', // can also be dark
	          'callback' : 'enableBtn1' // function to call when successful verification for button 1
	        });
	    };  
	    function recaptchaExpired(){
	        document.getElementById("sub_btn").disabled = true;
	    }

	    // Login button code
	    jQuery(document).ready(function ($) {
	        document.getElementById("sub_btn").disabled = true;
	    });

</script>
<script type="text/javascript">

function initialize() {
	var lat = "<?php echo $contact_us_info['lat']; ?>";
	var lng = "<?php echo $contact_us_info['lng']; ?>";
	// var lat = .239243;
	// var lng = 55.3695568;
	var address = "<?php echo json_decode($contact_us_info['address'])->english; ?>"
   var latlng = new google.maps.LatLng(lat,lng);
    var map = new google.maps.Map(document.getElementById('map'), {
  		center:new google.maps.LatLng(lat,lng),
      zoom: 13
    });
    var marker = new google.maps.Marker({
      map: map,
      position: latlng,
      draggable: false,
      anchorPoint: new google.maps.Point(0, -29)
   });
    var infowindow = new google.maps.InfoWindow();   
    google.maps.event.addListener(marker, 'click', function() {
      var iwContent = '<div id="iw_container">' +
      '<div class="iw_title"><b>Location</b> : '+ address +' </div></div>';
      // including content to the infowindow
      infowindow.setContent(iwContent);
      // opening the infowindow in the current map and at the current marker location
      infowindow.open(map, marker);
    });
}

$('#sub_btn').on('click',function(e){
 e.preventDefault();
	var validation = false;
	var language = '<?= $language; ?>';

	selectedInputs = ['fname','subject', 'email', 'mobile','text'];
	validation = validateFields(selectedInputs,language);


	if(validation == false){
		return false;
	}

	if(validation == true){
		//var data = $(this).closest("form").serializeArray();
		//console.log(data);
		$(this).closest("form").submit();
	}

}); 
</script>
<script src="https://www.google.com/recaptcha/api.js?onload=myCallBack&render=explicit&hl=<?= $ln; ?>" async defer></script>
<?php 
if($language == 'arabic'){
    $ln_map = '&region=EG&language=ar';
}else{
    $ln_map = '';
}
?>
<script src="https://maps.googleapis.com/maps/api/js?key=<?= $this->config->item('google_map_key'); ?>&callback=initialize<?= $ln_map; ?>"></script>

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
        
        //$("#user-phone").intlTelInput("setNumber", "<?php //echo isset($user['mobile']) ? $user['mobile'] : ''; ?>");
    });
</script>


