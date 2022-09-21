<!-- <script src="<?php echo base_url();?>assets_admin/tinymce_4.7.3/tinymce.js"></script> -->
<script type="text/javascript" src="<?php echo base_url();?>assets_admin/tinymce_4.7.3/tinymce.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets_admin/tinymce_4.7.3/langs/ar.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets_admin/tinymce_4.7.3/langs/en_GB.js"></script>
<style type="text/css">
    #page-wrapper {
        margin-top: 100px;
    }
</style>
<div class="clearfix"></div>
<div class="col-md-12 col-sm-12 col-xs-12">
  <div class="x_panel">
      <div class="x_title">
          <h2>
              <?php echo $small_title; ?>
          </h2>
          <div class="clearfix"></div>
      </div>
      <div class="x_content"></div>
        <?php if($this->session->flashdata('msg')){ ?>
      <div class="alert">
          <div class="alert alert-success alert-dismissible fade in" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: black;"><span aria-hidden="true">×</span>
            </button>
            <?php  echo $this->session->flashdata('msg'); 
            
              ?>
        </div>
    </div>
  <?php }?>
  <?php $address = json_decode($contact_us_info['address']);?>
  <div id="result"></div>
  
  <form method="post" data-parsley-validate="" id="demo-form2" action="" enctype="multipart/form-data" class="form-horizontal form-label-left">
    <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />          
    <div class="item form-group">    
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="address">English Address 
        <span class="required">*</span>
      </label>
      <div class="col-md-6 col-sm-6 col-xs-12">
            <input type="text" required  name="address" id="address" value="<?php if(isset($contact_us_info) && !empty($contact_us_info)) { if(!empty($contact_us_info['address'])){echo $address->english;} } ?>" class="form-control col-md-7 col-xs-12">
            <input type="hidden" name="lat" id="lat" value="<?php if(isset($contact_us_info) && !empty($contact_us_info)) { echo $contact_us_info['lat']; } ?>">
            <input type="hidden" name="lng" id="lng" value="<?php if(isset($contact_us_info) && !empty($contact_us_info)) { echo $contact_us_info['lng']; } ?>">
      </div>
    </div>  
     <div class="item form-group">    
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="address">Arabic Address
        <span class="required">*</span>
      </label>
      <div class="col-md-6 col-sm-6 col-xs-12">
            <input type="text" required dir="rtl" name="address_arabic" id="address" value="<?php if(isset($contact_us_info) && !empty($contact_us_info)) { if(!empty($contact_us_info['address'])){echo $address->arabic;} } ?>" class="form-control col-md-7 col-xs-12">
            <input type="hidden" name="lat" id="lat" value="<?php if(isset($contact_us_info) && !empty($contact_us_info)) { echo $contact_us_info['lat']; } ?>">
            <input type="hidden" name="lng" id="lng" value="<?php if(isset($contact_us_info) && !empty($contact_us_info)) { echo $contact_us_info['lng']; } ?>">
      </div>
    </div> 
    <div class="item form-group">    
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title_arabic">Website
            <span class="required">*</span>
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <input type="url" required="required" name="website" value="<?php if(isset($contact_us_info) && !empty($contact_us_info)) { echo $contact_us_info['website']; } ?>" class="form-control col-md-7 col-xs-12">
        </div>
    </div>

     <div class="item form-group">    
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title_arabic">Mobile
            <span class="required">*</span>
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <input type="tel" required="required" id="mobile" name="mobile" value="<?php if(isset($contact_us_info) && !empty($contact_us_info)) { echo $contact_us_info['mobile']; } ?>" onkeyup="this.value=this.value.replace(/[^\d]/,'')" class="form-control col-md-7 col-xs-12">
            <input type="hidden" name="mobile_code" id="mobile1">
        </div>
    </div>

     <div class="item form-group">    
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title_arabic">Fax
            <span class="required">*</span>
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <input type="text"  name="fax" required="" value="<?php if(isset($contact_us_info) && !empty($contact_us_info)) { echo $contact_us_info['fax']; } ?>" class="form-control col-md-7 col-xs-12">
        </div>
    </div>

    <div class="item form-group">    
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title_arabic">Email
            <span class="required">*</span>
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <input type="text"  name="email" required="" value="<?php if(isset($contact_us_info) && !empty($contact_us_info)) { echo $contact_us_info['email']; } ?>" class="form-control col-md-7 col-xs-12">
        </div>
    </div>

    <div class="item form-group">    
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title_arabic">Toll Free
            <span class="required">*</span>
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <input type="text"  name="toll_free" required="" value="<?php if(isset($contact_us_info) && !empty($contact_us_info)) { echo $contact_us_info['toll_free']; } ?>" class="form-control col-md-7 col-xs-12">
        </div>
    </div>
    <div class="item form-group">    
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title_arabic">PO BOX
            <span class="required">*</span>
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <input type="text"  name="po_box" required="" value="<?php if(isset($contact_us_info) && !empty($contact_us_info)) { echo $contact_us_info['po_box']; } ?>" class="form-control col-md-7 col-xs-12">
        </div>
    </div>

     <div class="item form-group">    
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title_arabic">Phone
            <span class="required">*</span>
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <input type="text"  name="phone" required="" value="<?php if(isset($contact_us_info) && !empty($contact_us_info)) { echo $contact_us_info['phone']; } ?>" class="form-control col-md-7 col-xs-12">
        </div>
    </div>

    <div class="item form-group">    
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title_arabic">Business Hours
            <span class="required">*</span>
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <input type="text"  name="business_hr" required="" value="<?php if(isset($contact_us_info) && !empty($contact_us_info)) { echo $contact_us_info['business_hr']; } ?>" class="form-control col-md-7 col-xs-12">
        </div>
    </div>

    <div class="item form-group">    
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title_arabic">Working Days
            <span class="required">*</span>
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <input type="text" required="" name="working_hr" value="<?php if(isset($contact_us_info) && !empty($contact_us_info)) { echo $contact_us_info['working_hr']; } ?>" class="form-control col-md-7 col-xs-12">
        </div>
    </div>
    
    <div class="ln_solid"></div>
    <div class="form-group">
        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-2">
            <button type="submit" id="send" class="btn btn-success"><?php echo $status_btn; ?></button>
             <!-- <a href="<?php echo base_url().'settings/email_template_list'; ?>" class="custom-class" type="button">Cancel</a> -->
        </div>
    </div>

</form>

</div>
</div>

<script src="<?php echo base_url(); ?>assets_admin/js/jquery.geocomplete.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=<?= $this->config->item('google_map_key'); ?>&libraries=places"></script>
<script type="text/javascript">

    $("#address").geocomplete({details:"form#demo-form2"});

      // for flag input to mobile
        $("#mobile").intlTelInput({
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


    var ok;
    var url = '<?php echo base_url();?>';
    var formaction_path = '<?php echo $formaction_path;?>';
  
     // You can use the locally-scoped $ in here as an alias to jQuery.
  $('#demo-form2').parsley().on('field:validated', function() {
    var ok = $('.parsley-error').length === 0;
  }).on('form:submit', function() {

        var intlNumberCode = $("#mobile").intlTelInput("getSelectedCountryData");
            // var a = $(".mobile").intlTelInput("getNumber");
             // console.log($("#phone1").intlTelInput('getNumber'));
             console.log(intlNumberCode);
        $('#mobile1').val(intlNumberCode.dialCode);

        var formData = new FormData($("#demo-form2")[0]);
          $.ajax({
              url: url + 'cms/' + formaction_path,
              type: 'POST',
              data: formData,
              cache: false,
              contentType: false,
              processData: false
          }).then(function(data) 
          {
            var objData = jQuery.parseJSON(data);
            console.log(objData);
            if (objData.error == false)
            {
                // window.location = url + 'cms/terms';
                 $("#result").html('<div class="alert" ><div class="alert alert-domain alert-success alert-dismissible fade in  msg-alert" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: black"><span aria-hidden="true">×</span> </button>' + objData.msg + '</div></div>');
            } 
            else
            {
                $('.msg-alert').css('display', 'block');
                $("#result").html('<div class="alert" ><div class="alert alert-domain alert-danger alert-dismissible fade in  msg-alert" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: black"><span aria-hidden="true">×</span> </button>' + objData.msg + '</div></div>');
            }
            window.scrollTo({top: 0, behavior: "smooth"});
          });
              return false; // Don't submit form for this demo
  });

</script>



<script type="text/javascript">

</script>