<main class="page-wrapper desposite">
	<div class="listing-wrapper">
    	<?= $this->load->view('template/template_user_leftbar') ?>
    	<div class="right-col">

    		<div id="error-response"></div>

    		<?php if($this->session->flashdata('error')){ ?>
	            <div class="alert alert-danger alert-dismissible">
	              <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
	              <strong>Error!</strong> <?= $this->session->flashdata('error'); ?>
	            </div>
	        <?php } ?>

	        <?php if($this->session->flashdata('success')){ ?>
	            <div class="alert alert-success alert-dismissible">
	              <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
	              <strong>Success!</strong> <?= $this->session->flashdata('success'); ?>
	            </div>
	        <?php } ?>

			<div class="info-head">
				<div class="row">
					<div class="col-md-12">
						<h2>Item Deposit</h2>
					</div>
				</div>	
			</div>
			<div class="gray-bg">
				<div class="container">
					<div id="response" ></div>
					<div class="tab-box">
						<h3>Increase your deposit</h3>
						<ul>
							<li><a href="javascript:void(0)" class="btn btn-primary" id="cheq">CHEQUE/DEPOSIT</a></li>
							<li><a href="javascript:void(0)" class="btn btn-primary" id="crad">CREDIT CARD</a></li>
							<!-- <li><a href="javascript:void(0)" class="btn btn-primary" id="banktrns">Bank Trasfer</a></li> -->
						</ul>
						<!-- cheque deposite -->
						<div class="box" id="cheque">
							<h3>Pay Cheque Deposit</h3>
							<p>Please visit any of our office location below to finalize your cheque deposit.</p>
							<div class="loc-box">
								<ul>
									<li>Location:</li>
									<li>Dubai</li>
								</ul>
								<ul>
									<li>Time:</li>
									<li>Sun-Thur: 8:00am - 4:00pm</li>
								</ul>
							</div>
							<div class="map">
								<iframe src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d13610.480337022605!2d74.3000874!3d31.479635499999997!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2s!4v1583893089665!5m2!1sen!2s"  frameborder="0" style="border:0;" allowfullscreen=""></iframe>
								<p>*For cheque payments visit location</p>
							</div>	
						</div>
						<!-- Cradit Card -->
						<div class="box" id="cradit_card">
							<h3>Pay by Credit Card</h3>
							<p>Please note that the minimum required amount is <?= (isset($setting) && !empty($setting['deposite'])) ?
								$setting['deposite']: '';  ?> <!-- 1,000 --> AED.<br>
								We accept Visa and Mastercard only.<br><br>

								(Note that we do not accept Electron or ATM cards, only cards mentioned above are accepted)<br><br>
								If you select this option, you will be asked to enter your Credit Card details in the bank secure online payment page. Once your transaction has been processed you will be able to enter an auction and begin bidding.<br><br>
								The Amount will be deducted from your card and will show in your bank statement, You can request your deposit refund anytime through our application, Refund will be credited to the same card, It usually takes 7-15 working days to get the credit back, depending on your card issuing bank.<br><br>
							Also please note that you cannot include the security deposit paid by credit card in the car final price, this mean that you have to pay the full amount and the deposit will be refunded to you.</p>
							<div class="loc-box">
								<ul>
									<li>Select Auction:</li>
									<li><select id="auction" name="auction_id">
										<option value="">Select Auction</option>
										<?php $auction_id = $_GET['auction_id']; foreach ($auctions as $key => $value) {
											$title = json_decode($value['title']);
											?>
											<option <?= (isset($auction_id) && $auction_id == $value['id']) ? 'selected=selected' : ''; ?> value="<?= $value['id']; ?>"><?= $title->english; ?></option>
											<?php
										} ?>
									</select></li>
								</ul>
								<ul>
									<li>Select Item:</li>
									<li><select id="auction_item" name="auction_item_id">
										<option value=""> Select auction first </option>
									</select></li>
								</ul>
								<ul>
									<li>Deposit Amount:</li>
									<li><input type="number" readonly="readonly" id="amount" name="amount"></li>
								</ul>
								<!-- <ul>
									<li>New Bidding Deposit:</li>
									<li>AED</li>
								</ul> -->
							</div>
							<div>
								<button class="btn btn-success" disabled="disabled" id="priceedbtn">Proceed </button>
							</div>	
						</div>
						<!-- Bank Transfer -->
						<div class="box" id="bank">
							<h3>Pay by Bank transfer</h3>
							<?php 
							$query= $this->db->get('bank_info')->row_array();
							?>

							<div class="loc-box">
								<ul>
						          <li>Account Name:</li>
						          <li><?php  if(!empty($query) && !empty($query)) echo $query['ac_name'];?></li>
						        </ul>
						        <ul>
						          <li>Bank Name:</li>
						          <li><?php  if(!empty($query) && !empty($query)) echo $query['bank_name'];?></li>
						        </ul>
						        <ul>
						          <li>IBAN:</li>
						          <li><?php  if(!empty($query) && !empty($query)) echo $query['iban'];?></li>
						        </ul>
						        <ul>
						          <li>Account Number:</li>
						          <li><?php  if(!empty($query) && !empty($query)) echo $query['ac_number'];?></li>
						        </ul>
						        <ul>
						          <li>Swift Code:</li>
						          <li><?php  if(!empty($query) && !empty($query)) echo $query['swift_code'];?></li>
						        </ul>
						        <ul>
						          <li>Routing Number:</li>
						          <li><?php  if(!empty($query) && !empty($query)) echo $query['routing_number'];?></li>
						        </ul>
							</div>
							<?php 
							$data= $this->db->get('contact_us')->row_array();
							?>
							<p>Once your transfer is completed, please submit the transfer slip using the form below, or attach by email on <span><?php  if(!empty($data) && !empty($data)) echo $data['email'];?></span><br> or fax to <?php  if(!empty($data) && !empty($data)) echo $data['fax'];?><br>please allow for 24hrs before your deposit is reflected on your system. </p>
							<form id="demo-form2">
								<input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
								<div class="form-group">
									<label>Deposit Transfer Slip</label>
									<div class="uploader">
										Choose File
										<input type="file" name="slip" id="slip">
                  						<div class="text-danger slip-error"></div>
									</div>
								</div>
								<div class="form-group">
									<label>Deposit Date</label>
									<input type="text" class="form-control" id="deposit_date" name="deposit_date">
                  					<span class="text-danger deposit_date-error"></span>
								</div>
								<div class="form-group">
									<label>Deposit Amount</label>
									<input type="text" class="form-control" id="deposit_amount" name="deposit_amount">
                  					<span class="text-danger deposit_amount-error"></span>
								</div>
								<div class="button-row">
									<a href="javascript:void(0)" class="btn btn-default" id="bankdepositbtn">Submit your Deposit</a>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>	
	</div>
</main>

<script>
	$(document).ready(function(){
		<?php if (isset($_GET['item_id'])) { ?>
			$('#auction').change();
			$('#auction_item').change();
		<?php } ?>

		$('#deposit_date').datepicker({
			format: 'yyyy-mm-d',
    		// startDate: '-30d',
	     	ignoreReadonly: true,
	     	autoclose: true
	     	// allowInputToggle: true,
	    	// minDate: moment(),
	    });
		// $('#deposit_date').datepicker();
	    $('#cheque').show();
		$('#cradit_card').hide();
		$('#bank').hide();

	    $('#crad').on('click', function(event) {  
	        $('#cradit_card').show();
	        $('#cheque').hide();
			$('#bank').hide();
	    });

	    $('#cheq').on('click', function(event) {    
	        $('#cheque').show();
	        $('#cradit_card').hide();
			$('#bank').hide();
	    });

	    $('#banktrns').on('click', function(event) {    
			$('#bank').show();
	        $('#cheque').hide();
	        $('#cradit_card').hide();
	    });


	    $('#priceedbtn').on('click', function(event) {  
	    	event.preventDefault();
	    	var base_url = '<?php base_url() ?>';
	        var amount = $('#amount').val();
	        var auction_id = $('#auction').val();
	        var auction_item_id = $('#auction_item').val();
	        var item_id = $('#item_id').val();
	        <?php if (isset($_GET['rurl'])) { ?>
		        var rurl = "<?= $_GET['rurl']; ?>";
		    <?php } else { ?>
		        var rurl = "";
		    <?php } ?>
	        var min_amount = "<?= (isset($setting) && !empty($setting['deposite'])) ? $setting['deposite']: '';  ?>";
	        $.ajax({
		     url: base_url + 'customer/item_cradit_card',
		     type: 'POST',
		     data: {amount:amount, auction_id:auction_id, auction_item_id:auction_item_id, item_id:item_id, rurl:rurl},
		   	 beforeSend: function(){
		     $('.loading_oz_premium').html('<img src="'+base_url+'assets_admin/images/load.gif" align="center" />');
		     $('.loading_oz_premium').parent().attr("disabled",true);
		     $('.loading_oz').parent().attr("disabled",true);
		   	 },
		     }).then(function(data) 
		     {
		        var objData = jQuery.parseJSON(data);
		        if (objData.msg == 'success') 
		        {
		        	window.location = objData.redirect;
		        }
		        else{
					$('#error-response').html('<div class="alert" ><div class="alert alert-domain alert-warning alert-dismissible in  msg-alert" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: black"><span aria-hidden="true">×</span> </button><strong> Invalid! </strong> '+objData.response+'</div></div>');
					window.scrollTo({top: 10, behavior: "smooth"});
		        }
		    });
	    });

	    $('#bankdepositbtn').on('click', function(event) {  
	    	event.preventDefault();
	    	console.log('click');

	    	var validation = false;
		    var errorMsg = "This value is required.";
		    var errorClass = ".valid-error";
		    var e;
		    selectedInputs = ['slip', 'deposit_date', 'deposit_amount'];

		    $.each(selectedInputs, function(index, value){
		      e = $('input[name='+value+']');

		      if(($.trim(e.val()) == "") || (e.val() == null) || (typeof e.val() === 'undefined')){
		        e.focus();
		        $('.'+value+'-error').html(errorMsg).show();
		        validation = false;
		        return false;
		      }else{
		        validation = true;
		        $('.'+value+'-error').html('').hide();
		      }
		    });

	    	var base_url = '<?php base_url() ?>';
            var formData = new FormData($("#demo-form2")[0]);
            if (validation == true) {
	    	console.log(formData);
		        $.ajax({
			     url: base_url + 'customer/add_bank_slip',
			     type: 'POST',
			     data: formData,
			     cache: false,
	             contentType: false,
	             processData: false,
			   	 beforeSend: function(){
			     $('.loading_oz_premium').html('<img src="'+base_url+'assets_admin/images/load.gif" align="center" />');
			     $('.loading_oz_premium').parent().attr("disabled",true);
			     $('.loading_oz').parent().attr("disabled",true);
			   	 },
			    }).then(function(data) 
			    {
			        var objData = jQuery.parseJSON(data);
			        if (objData.msg == 'success') 
			        {
			        	$('#response').html('<div class="alert" ><div class="alert alert-domain alert-success alert-dismissible in  msg-alert" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: black"><span aria-hidden="true">×</span> </button><strong> success! </strong> '+objData.response+'</div></div>');
                    	window.scrollTo({top: 190, behavior: "smooth"});
                    	$('#slip').val('');
                    	$('#deposit_date').val('');
                    	$('#deposit_amount').val('');
			        }
			        else{
						$('#response').html('<div class="alert" ><div class="alert alert-domain alert-warning alert-dismissible in  msg-alert" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: black"><span aria-hidden="true">×</span> </button><strong> Error! </strong> '+objData.response+'</div></div>');
                    	window.scrollTo({top: 190, behavior: "smooth"});
			        }
			    });
			}
	    });

	});

$('#auction').on('change', function() {
	var base_url = '<?= base_url() ?>';
	var id = $('#auction').val();
	var item_id = '';
	<?php if(isset($_GET['item_id'])) { ?>
		item_id = '<?= $_GET['item_id']; ?>';
	<?php } ?>

	var user_id = '<?= $this->session->userdata('logged_in')->id; ?>';
    $.ajax({
     url: base_url + 'customer/get_ai_list',
     type: 'POST',
     data: {id: id, user_id: user_id, item_id: item_id},
   	 beforeSend: function(){
     	$('.loading_oz_premium').html('<img src="'+base_url+'assets_admin/images/load.gif" align="center" />');
   	 },
     }).then(function(qadeer) 
     {
        var objData = jQuery.parseJSON(qadeer);
        // var objData = $('#auction_item').val();
        console.log(objData);
        	// $('#auction_item').append('<option value="">Choose</option>');
        if (objData.status == true) {
        	// alert(objData.status);
        	$('#auction_item').html(objData.data);
        	$('#auction_item').selectpicker('refresh');
        }else{
        	$('#auction_item').html('<option value=""> Select auction first </option>');
        	$('#auction_item').selectpicker('refresh');

        }
    });
});

$('#auction_item').on('change', function() {
	var base_url = '<?= base_url() ?>';
	var id = $('#auction_item').val();
	var item_id = '';
	<?php if(isset($_GET['item_id'])) { ?>
		id = '<?= $_GET['item_id']; ?>';
	<?php } ?>
	// alert(id);
    $.ajax({
     url: base_url + 'customer/get_ai_deposit',
     type: 'POST',
     data: {id: id},
   	 beforeSend: function(){
     	$('.loading_oz_premium').html('<img src="'+base_url+'assets_admin/images/load.gif" align="center" />');
   	 },
     }).then(function(data) 
     {
        var objData = jQuery.parseJSON(data);
        // var objData = $('#auction_item').val();
        console.log(objData);
        	// $('#auction_item').append('<option value="">Choose</option>');
        if (objData.status == true) {
        	// alert(objData.status);
        	$('#amount').val(objData.deposit);
        	$('#priceedbtn').attr('disabled', false);
        }else{
        	$('#amount').val('');
        	$('#priceedbtn').attr('disabled', true);
        }
    });
});
</script>