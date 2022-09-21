<?php  
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
?>
<link href="<?php echo base_url(); ?>assets_admin/vendors/pnotify/dist/pnotify.css" rel="stylesheet">
<link rel="stylesheet" href="<?= base_url(); ?>assets_user/tools/PhoneMask/css/intlTelInput.min.css">
<script src="<?= base_url(); ?>assets_user/tools/PhoneMask/js/intlTelInput-jquery.min.js"></script>
<script src="<?= base_url(); ?>assets_user/tools/PhoneMask/js/utils.js"></script>

<style>
    .bootstrap-select {margin-bottom: 0px}
    .col-12 {margin-bottom: 20px;}
    .custom-form .bootstrap-select>.dropdown-toggle {margin-bottom: 0 !important;}
</style>

<div class="main gray-bg valuation valuation-page">
    <div class="container">
        <h1 class="section-title"><?= $this->lang->line('free_valuation'); ?></h1>
        <div class="valuation-step">
            <ul>
                <li class="active">
                    <span></span>
                    <?= $this->lang->line('select_car');?>
                </li>
                <li class="step2_top">
                    <span></span>
                    <?= $this->lang->line('model_cndtion');?>
                </li>
                <li class="step3_top" id="step3_top">
                    <span></span>
                    <?= $this->lang->line('book_appointment');?>
                </li>
            </ul>
        </div>
        <div class="content-box sm">
            <form class="custom-form" method="post" id="form-2" name="myForm">
                <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
                <div class="row" id="step1">
                    <div class="col-12">
                        <select class="selectpicker make_select"  id="make_id" name="valuation_make_id">
							 <option  value=""><?= $this->lang->line('select_make');?></option>
	                        <?php foreach ($makes_list as $key => $value) { 
                                $title_new = json_decode($value['title']);
                                ?>
	                        <option value="<?php echo $value['id']; ?>"><?= $title_new->$language; ?></option>
	                            <?php  } ?>
						</select>
						<span class="make_error"></span>
                    </div>
                    <div class="col-12">
                        <select class="selectpicker" title="<?= $this->lang->line('please_select_model');?>" ochange="model_change()" name="valuation_model_id" id="valuation_model_id">
							<option value="" ><?= $this->lang->line('select_model');?></option>
						</select>
						<span class="model_error"></span>
                    </div>
                    <div class="col-12">
                        <select class="selectpicker" title="<?= $this->lang->line('year_range_to');?>" name="year_to" id="year_to">
							<option value="" ><?= $this->lang->line('year_range');?></option>
						</select><span class="year_to_error"></span>
                    </div>
                    <div class="col-12">
                        <div class="button-row">
                            <button id="car_valuation_step_one" class="btn-default"><?= $this->lang->line('get_started');?></button>
                            <h2><?=$this->lang->line('vehicle_not_listed');?>?<a href="javascript:void(0)" id="skip"><?= $this->lang->line('book_appointment');?></a></h2>
                        </div>
                    </div>
                </div>
                <div class="row" id="step2" style="display: none;">
                    <div class="col-12">
                        <select class="selectpicker" title="<?= $this->lang->line('please_select_engine_size');?>" id="valuation_engine_size"  name="engine_size_id">
						</select>
						<div id="engine_size_error"></div>
                         <div class="valid-error text-danger engine_size_id-error"></div>
                    </div>
                    <div class="col-12">
                        <select class="selectpicker" title="<?= $this->lang->line('please_select_model');?>" id="milleage_id" name="valuation_milleage_id">
							 <option  value=""><?= $this->lang->line('select_millage');?></option>
    
	                        <?php foreach ($milleage_list as $key => $value) { ?>
	                        <option 
	                            value="<?php echo $value['id']; ?>"><?php echo $value['mileage_label']; ?></option>
	                            <?php  } ?>
						</select>
                        <!-- <div id="milleage_error"></div> -->
                        <div class="valid-error text-danger valuation_milleage_id-error"></div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label><?= $this->lang->line('option');?></label>
                            <ul class="valuation-option">
                                <li class="radio">
                                    <label>
                                    	<input type="radio" name="valuate_option" value="Basic">
										<span><?= $this->lang->line('basic');?></span>
                                    </label>    
                                </li>
                                <li class="radio">
                                    <label>
                                        <input type="radio" name="valuate_option" value="Mid">
										<span><?= $this->lang->line('mid');?></span>
                                    </label>    
                                </li>
                                <li class="radio">
                                    <label>
                                        <input type="radio" name="valuate_option" value="Full">
										<span><?= $this->lang->line('full');?></span>
                                    </label>    
                                </li>
                            </ul>
                        </div>  
                        <div class="valid-error text-danger valuate_option-error"></div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label><?= $this->lang->line('paint');?></label>
                            <ul class="valuation-option">
                                <li class="radio">
                                    <label>
                                        <input type="radio" name="valuate_paint" value="Original">
										<span><?= $this->lang->line('original');?></span>
                                    </label>    
                                </li>
                                <li class="radio">
                                    <label>
                                        <input type="radio" name="valuate_paint" value="Partial">
										<span><?= $this->lang->line('partial');?></span>
                                    </label>    
                                </li>
                                <li class="radio">
                                    <label>
                                        <input type="radio" name="valuate_paint" value="Total_Repaint" >
										<span><?= $this->lang->line('total');?></span>
                                    </label>    
                                </li>
                            </ul>
                        </div> 
                        <div class="valid-error text-danger valuate_paint-error"></div> 
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label><?= $this->lang->line('has_specs');?></label>
                            <ul class="valuation-option">
                                <li class="radio">
                                    <label>
                                        <input type="radio" name="valuate_gc" value="GCC">
										<span><?= $this->lang->line('gcc');?></span>
                                    </label>    
                                </li>
                                <li class="radio">
                                    <label>
                                        <input type="radio" name="valuate_gc" value="Non_GCC">
											<span><?= $this->lang->line('non_gcc');?></span>
                                    </label>    
                                </li>
                                <li class="radio">
                                    <label>
                                        <input type="radio" name="valuate_gc" value="0">
										<span><?= $this->lang->line('dont_know');?></span>
                                    </label>    
                                </li>
                            </ul>
                        </div>  
                         <div class="valid-error text-danger valuate_gc-error"></div> 
                    </div>
                    <div class="col-12">
                        <div class="customform">
                            <input type="text" id="email" dir="ltr" class="form-control" placeholder="<?= $this->lang->line('email');?>" name="email">
							<div id="email_error"></div>
                            <div class="valid-error text-danger email-error"></div>
							<!-- <div id="emailErrors" class="text-danger"></div> -->
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="button-row">
                            <button id="car_valuation_step2" class="btn-default"><?= $this->lang->line('valuate_your_car');?></button>
                            <!-- <h2>Vehicle not listed?<a href="#">Book an appointment</a></h2> -->
                        </div>
                    </div>
                </div>
				<input type="hidden" id="valuation_price_result" name="valuation_price_result">
				<div class="row customform" id="form3" style="display: none" class="Valuation-form form3">

					<h1 id="valuation_price_div" >
						<span><?= $this->lang->line('vehicle_market_price');?></span>
						<span id="valuation_price"></span>
					</h1>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label><?= $this->lang->line('date_time');?> <span class="required">*</span></label>
                            <input type="text" id="date-app" class="form-control ltr" name="date">
                            <span class="input-group-addon">
				            <span class="glyphicon glyphicon-calendar"></span>
				        	</span>
                            <!-- <span class="input-group-addon"> -->
							<div id="date_error"></div>
	                    </div>
	                </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label><?= $this->lang->line('f_name');?> <span class="required">*</span></label>
                            <input type="text" id="f_name" class="form-control" name="f_name">
							<div id="f_name_error"></div>
                        </div>  
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label><?= $this->lang->line('l_name');?></label>
                            <input type="text" class="form-control" name="l_name">

                        </div>  
                    </div>
                    <div style="display: none;" class="col-sm-12" id="email_div_appointment">
                        <div class="form-group">
                            <label><?= $this->lang->line('email');?> <span class="required">*</span></label>
                            <input type="text" id="email_for_appointment" name="email2" class="form-control">
                            <div id="email2_error"></div>
                        </div>  
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label><?= $this->lang->line('mobile');?> <span class="required">*</span></label>
                            <input type="text"  id="telephone2"  oninput="this.value=this.value.replace(/[^0-9]/g,'');" class=" form-control" name="mobile">
                            <div id="telephone2_error"></div>
                        </div>  
                    </div>
                    <div class="col-12">
                        <div class="button-row center">
                            <a id="book_appointment" class="btn btn-default book_appointment"><?= $this->lang->line('book_appointment');?></a>
                        </div>
                        <h2 class="text-center">
                            <a href="<?php echo base_url('valuation');?>"><?= $this->lang->line('not_now_thanks');?></a>
                        </h2>
                    </div>
                </div>
            </form>
        </div>    
    </div> 
</div>

<!-- Notify JS Files -->
<script src="<?php echo base_url(); ?>assets_admin/vendors/pnotify/dist/pnotify.js"></script>
<script>
	$("#telephone2").intlTelInput({
		// initialCountry: "auto",
        allowDropdown: true,
        autoPlaceholder: "polite",
        placeholderNumberType: "MOBILE",
        formatOnDisplay: true,
        separateDialCode: true,
        nationalMode: false,
        autoHideDialCode: true,
        preferredCountries: ["ae" ]
    });

	$("#car_valuation_step_one").on('click' , function(e) {
		e.preventDefault();

		if($('#make_id').val() =="" )
		{	
			$('.make_error').html("<?= $this->lang->line('please_select_make'); ?>").css("color","red");
			$('.make_select').css('border-color', 'red');
			// $('#make_id').focus();
			return false
		}
		if($('#valuation_model_id').val() =="" )
		{
			
			$('.model_error').show();
			$('.model_error').html("<?= $this->lang->line('please_select_model'); ?>").css('color','red');
			$('#valuation_model_id').focus();
			return false
		}
		var year = $('#year_to').val();
		if(year == null || year == "")
		{
			$('.year_to_error').html("<?= $this->lang->line('please_select_year'); ?>").css("color","red");	
			$('.year_to_error').show();
			return false;
		}

		$('#step1').hide();
			$('#loading').show();
			$('#loading').delay(1000).fadeOut('slow');
			$('#step2').show();
			$('.step2_top').addClass("active");
			$('.valuation-page').addClass("step2");


	});

	$('#make_id').on('change', function() {
		$('.year_to_error').hide();
        var make_id=document.myForm.valuation_make_id.value;
       	$.ajax({    //create an ajax request to display.php
            type: "post",
            url: "<?php echo base_url(); ?>" + "valuation/get_make_data",    
            data:{ make_id:make_id,'<?= $this->security->get_csrf_token_name();?>':"<?=$this->security->get_csrf_hash();?>"},
            beforeSend: function(){
            	 $('#loading').show();
            },
            success: function(data) {
                 console.log(data);
                 if(data== "null"){
                 	$("#valuation_model_id option").not(':first').remove();
                 }
                $(".selectpicker").selectpicker("refresh");
                if(data != "null"){
                	 $(".selectpicker").selectpicker("refresh");
                	 $("#valuation_model_id option").not(':first').remove();
                    $.each(JSON.parse(data), function(i,v) {
                        var title = $.parseJSON(v['title']);
                        var lan = "<?php echo $language;?>";
                      $("<option / >").val(v.id).text(title[lan]).appendTo('select#valuation_model_id');
                    });
                  
                	$('.model_error').hide();
                	$('.year_to_error').hide();
            	}
                 // $("#loading").fadeOut(8000);

                 $('#loading').delay(1000).fadeOut('slow');
                 // $('#loading').hide();
                $(".selectpicker").selectpicker("refresh");
            }
        });
    });
		
	$('#year_to').on('change',function(){
		$('#year_to_error').hide();
		var year = $('#year_to').val();
		var make_id = $('#make_id').val();
		var model_id = $('#valuation_model_id').val();
		$.ajax({
			type:'post',
			url: "<?php echo base_url(); ?>" + "valuation/get_enginesize_by_year",  
			data:{year:year,make_id:make_id, model_id:model_id,'<?= $this->security->get_csrf_token_name();?>':"<?=$this->security->get_csrf_hash();?>"},
		    success: function(data) {
                $('select#valuation_engine_size').html('');
                objData = jQuery.parseJSON(data);
                console.log(objData.result);
                if(objData.error == true){
             	$("#valuation_engine_size option").remove();
             	$('#valuation_engine_size').append('<option disabled> <?= $this->lang->line('no_enginesize_available'); ?> </option>');
            	 }
            	 // alert(data);
            	$("#valuation_engine_size option").not(':first').remove();
             	$(".selectpicker").selectpicker("refresh");
             	if(objData.error == false){
                $.each(objData.result, function(i,v) {
                  $("<option / >").val(v.id).text(v.title).appendTo('select#valuation_engine_size');
                });
            }
                $('#loading').delay(1000).fadeOut('slow');
                $(".selectpicker").selectpicker("refresh");
                	 
            }
		})
	});

 
	$('#valuation_model_id').on('change' , function(){
       	var model_id=$('#valuation_model_id option:selected').val();
       	var make_id=$('#make_id').val();
        $.ajax({    //create an ajax request to display.php
            type: "post",
            url: "<?php echo base_url(); ?>" + "valuation/get_years/" + model_id ,
            data: {make_id:make_id, model_id:model_id,'<?= $this->security->get_csrf_token_name();?>':"<?=$this->security->get_csrf_hash();?>"},	  
            beforeSend: function(){
            	 $('#loading').show();
            },
            success: function(data) {
                // $('#loading').hide() ;
                objData = jQuery.parseJSON(data);
               	if(objData.msg == "No Years Found"){
             		$("#year_to option").remove();
             		$('#year_to').append('<option value=""> <?= $this->lang->line('no_record'); ?> </option>');
            	}
            	$(".selectpicker").selectpicker("refresh");
                $('select#year_to').html('');

                if(objData.result){
                	$('#year_to').append('<option value=""> <?= $this->lang->line('year_range_to'); ?> </option>')
                	$('#year_to_error').hide();
                	$('#loading').delay(1500).fadeOut('slow');
                	$.each((objData.result),function(c,k) {	
                  		$("<option/>").val(k).text(k).appendTo('select#year_to');
                		$(".selectpicker").selectpicker("refresh");
 
                	});
            	}
            }
        });
           			 
	});


    $('#car_valuation_step2').on('click' , function(event){
    	event.preventDefault();
      	// var engine_size_id = $('#valuation_engine_size').val();
       //  var milleage_id = $('#milleage_id').val();
      	// var email = $('#email').val();
      	// // console.log(engine_size_id);
      	// if((typeof engine_size_id === "undefined")){
	      // 	$('#engine_size_error').html("<?= $this->lang->line('please_select_engine_size'); ?>").css("color","red");
	      // 	$('#valuation_engine_size').focus();
	      // 	return false;
      	// } 
       //  if(engine_size_id == ''){
       //      $('#engine_size_error').html("<?= $this->lang->line('please_select_engine_size'); ?>").css("color","red");
       //      $('#valuation_engine_size').focus();
       //      return false;
       //  } 
       //  if(milleage_id == ''){
       //      $('#milleage_error').html("<?= $this->lang->line('select_millage'); ?>").css("color","red");
       //      $('#milleage_id').focus();
       //      return false;
       //  } 
      	// if(email == ""){
	      // 	$('#email_error').html("<?= $this->lang->line('please_select_email'); ?>").css("color","red");
	      // 	$('#email').focus();
	      // 	return false;
      	// }

      	// if(!isEmail($('input[name=email]').val())){
       //      $('#email_error').html('<?= $this->lang->line('email_is_not_valid'); ?>').show();
       //      $('input[name=email]').focus();
       //      setTimeout(function(){ $('#email_error').hide(); }, 3000);
       //      return false;
       //   }

      	var formData = $('#form-2').serializeArray();
      	var url = "<?php echo base_url(); ?>";
        var validation = false;
        var language = '<?= $language; ?>';
        selectedInputs = ['engine_size_id','valuation_milleage_id','valuate_option', 'valuate_paint', 'valuate_gc','email'];
        validation = validateFields(selectedInputs, language);
        if (validation == true) {
      	$.ajax({
      		url: url + 'valuation/car_valuation_results',
      		type: 'POST',
      		data: formData,
      		beforeSend: function(){
      			// $('#loading').show();
 		 	},
     		success: function(data) {
      			// alert('success');
      			var objData = jQuery.parseJSON(data);
      			console.log(objData);
  			  	if (objData.msg == 'success') {

  			  		$('#step2').hide();
  			  	 
	  			 	$('#loading').delay(1500).fadeOut('slow');
	  			 	$('#valuation_price').html('AED ' +objData.price );

	  			 	$('#valuation_price').css('color:green');
	  			 	$('#form3').show();
	  			 	$('#step3_top').addClass("active");
	  			 	$('#email_for_appointment').prop('disabled')
	  			 	$('#valuation_price_result').val(objData.price);
							 
               	}
                 else {
               		var message = objData.msg;
					new PNotify({
			            text: message,
			            type: 'error',
						addclass: 'custom-error',
			            title: "<?= $this->lang->line('error'); ?>",
			            styling: 'bootstrap3'
			        });
                }
      		}
      	});
      }
    });

	$('#skip').on('click', function(){
		$('#step1').hide();
		$('#step2').hide();
		$('#loading').show();
		$('#loading').delay(1000).fadeOut('slow');
		$('.step2_top').addClass("active");
		$('#step3_top').addClass("active");
		$('#valuation_price_div').hide();
		$('#email_div_appointment').show();
		$('#form3').show();

	});

    $('.book_appointment').on('click',function(){
      	let tdate = $('#date').val();
      	var now = new Date('Y-m-d');
      	// alert(now);
      	if($('#date').val() == ""  && $('#date').val() < val(now))
      	{
      		$('#date_error').html("<?= $this->lang->line('please_select_date'); ?>").css("color","red");
      		$('#date').focus();
      		return false;
      	}
      	///// for date greater than today or equal ///////////////////
		function TDate(e) {
		    var UserDate = document.getElementById("date").value;
		    var ToDate = new Date();
		    alert(ToDate);

		    if (new Date(UserDate).getTime() <= ToDate.getTime()) {
		          // alert("The Date must be Bigger or Equal to today date");
		          $('#date_error').html("<?= $this->lang->line('select_date_greater_than_today'); ?>");
		          return false;
		     }
		    return true;
		}


      	if($('#date-app').val() == '')
      	{
      		$('#date_error').html("<?= $this->lang->line('please_select_date'); ?>").css("color","red");
      		$('#date').focus();
      		return false;
      	}

      	if($('#f_name').val() == '')
      	{
      		$('#f_name_error').html("<?= $this->lang->line('please_select_first_name'); ?>").css("color","red");
      		$('#f_name').focus();
      		return false;
      	}

      	// if(! (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test($('#email_for_appointment').val())))
      	// {
      	// 	$('#email2_error').html('Please select valid email.').css("color","red");
      	// 	$('#email_for_appointment').focus();
      	// 	return false;
      	// }
      	var valuation_price= $('#valuation_price_result').val();

      	if(valuation_price == ""){
          	if($('#email_for_appointment').val() == '')
          	{
          		$('#email_for_appointment_error').html("<?= $this->lang->line('please_select_email'); ?>").css("color","red");
          		$('#email_for_appointment').focus();
          		return false;
          	}
      	}
		if($('#telephone2').val() == '')
      	{
      		$('#telephone2_error').html("<?= $this->lang->line('please_select_number'); ?>").css("color","red");
      		$('#telephone2').focus();
      		return false;
      	}
      	var base_url = '<?php echo base_url(); ?>';
      	var formData = $('form').serializeArray();
      	$.ajax({
      		type:"Post",
      		url: base_url + 'valuation/book_appointment',
      		data:formData,
      		beforeSend: function(){
      			$('#loading').show();
 		 	},
      		success:function(data){
      			var objData = jQuery.parseJSON(data);
      			if(objData.msg=="success")
      			{
      				$('#form3').hide();
                	 $('#loading').delay(1000).fadeOut('slow');

      				var message = "<strong><?= $this->lang->line('appointment_request_sent'); ?></strong> <?= $this->lang->line('you_notified_by_email_shortly'); ?>";
				    new PNotify({
				        text: message,
				        type: 'success',
						addclass: 'custom-success',
				        title: '<?= $this->lang->line('success_'); ?>',
				        styling: 'bootstrap3'
				    });
                    location.reload();
      			}
      			if(objData.msg == "failed")
      			{
      				$('#form3').hide();
                	 $('#loading').delay(1000).fadeOut('slow');

      				var message = "<strong><?= $this->lang->line('appointment_request_failed'); ?></strong><?= $this->lang->line('please_try_again_later'); ?></div>";
					new PNotify({
			            text: message,
			            type: 'error',
						addclass: 'custom-error',
			            title: '<?= $this->lang->line('error'); ?>',
			            styling: 'bootstrap3'
			        });	
      			}
      		}
      	});
    });
 </script>



