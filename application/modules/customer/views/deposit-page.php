<!-- PNotify -->
<link href="<?php echo base_url(); ?>assets_admin/vendors/pnotify/dist/pnotify.css" rel="stylesheet">
<!-- datepicker -->
<link rel="stylesheet" href="<?= ASSETS_USER;?>css/bootstrap-datepicker.css">
<script src="<?= ASSETS_USER;?>js/bootstrap-datepicker.js"></script>
<?= $this->load->view('template/auction_cat');?>
<div class="main gray-bg">
    <div class="row custom-wrapper add-deposit">
        <?= $this->load->view('template/template_user_leftbar') ?>
        <div class="right-col">
            <div class="container">
            <h1 class="section-title text-left"><?= $this->lang->line('deposit');?></h1>
                <div class="deposit-wrapper">
                    <h2><?= $this->lang->line('increase_deposit');?></h2>
                    <div class="detail-tabs inner">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link " id="detail-tab" data-toggle="tab" href="#detail" role="tab" aria-controls="detail" aria-selected="false"><?= $this->lang->line('cheque');?></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active" id="model-tab" data-toggle="tab" href="#model" role="tab" aria-controls="model" aria-selected="true"><?= $this->lang->line('credit_c');?></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="location-tab" data-toggle="tab" href="#location" role="tab" aria-controls="location" aria-selected="false"><?= $this->lang->line('bank_transfer');?></a>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade" id="detail" role="tabpanel" aria-labelledby="detail-tab">
                                <h3><?= $this->lang->line('pay_cheque');?></h3>
                                <h2><?= $this->lang->line('visit_location');?>.</h2>
                                <ul>
                                    <li><?= $this->lang->line('location');?>:</li>
                                    <li><?= $this->lang->line('dubai');?></li>
                                </ul>
                                <ul>
                                    <li><?= $this->lang->line('time');?>:</li>
                                    <li><?=$this->lang->line('sun_thur');?>: 8:00am - 4:00pm</li>
                                </ul>
                                <div class="map">
                                    <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d13610.482009735313!2d74.2913215697754!3d31.479624000000015!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2s!4v1595491267335!5m2!1sen!2s"  frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
                                </div>
                                <h3>*<?= $this->lang->line('cheque_location');?></h3>
                            </div>
                            <div class="tab-pane fade show active" id="model" role="tabpanel" aria-labelledby="model-tab">
                              <h3><?= $this->lang->line('card_pay');?></h3>
                              <p><?= $this->lang->line('p1');?> <?= (isset($setting) && !empty($setting['value'])) ? $setting['value']: '';  ?> AED. <?= $this->lang->line('p1_2');?>.</p>  
                              <p>
                                  <?= $this->lang->line('p2');?>
                              </p>
                              <p>
                                 <?= $this->lang->line('p3');?>
                              </p>
                              <p>
                                  <?= $this->lang->line('p4');?>
                              </p>
                              <p>
                                  <?= $this->lang->line('p5');?>
                              </p>
                                <ul class="align-items-center">
                                    <li><?= $this->lang->line('deposit_amount');?>:</li>
                                    <li>
                                        <form class="customform">
                                        <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />

                                        	<input type="hidden" name="rurl" id="rurl" value="<?= $_SERVER['REQUEST_URI']; ?>">
                                            <input type="text" oninput="this.value=this.value.replace(/[^0-9]/g,'');" class="form-control" type="number" min="<?= (isset($setting) && !empty($setting['value'])) ? $setting['value']: 0;  ?>" value="<?= (isset($setting) && !empty($setting['value'])) ? $setting['value']: 0;  ?>" id="amount" name="amount">
                                         <span class="valid-error text-danger amount-error" style="display: none;"></span>
                                        </form>
                                    </li>
                                </ul>
                                <div class="button-row">
                                    <button class="btn btn-default" id="priceedbtn"><?=$this->lang->line('proceed');?></button>
                                    <a class="btn btn-default" href="<?= base_url('customer'); ?>"><?= $this->lang->line('skip');?></a>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="location" role="tabpanel" aria-labelledby="Location-tab">
                                <h3><?=$this->lang->line('pay_by_bank_transfer');?></h3>
                                <?php 
									$query= $this->db->get('bank_info')->row_array();
								?>
                                <ul>
                                    <li><?=$this->lang->line('ac_name');?>:</li>
                                    <li><?php  if(!empty($query) && !empty($query)) echo $query['ac_name'];?></li>
                                </ul>  
                                <ul>
                                    <li><?=$this->lang->line('bank_name');?>:</li>
                                    <li><?php  if(!empty($query) && !empty($query)) echo $query['bank_name'];?></li>
                                </ul>
                                <ul>
                                    <li><?=$this->lang->line('iban');?>:</li>
                                    <li><?php  if(!empty($query) && !empty($query)) echo $query['iban'];?></li>
                                </ul>
                                <ul>
                                    <li><?=$this->lang->line('ac_number');?>:</li>
                                    <li><?php  if(!empty($query) && !empty($query)) echo $query['ac_number'];?></li>
                                </ul>
                                <ul>
                                    <li><?= $this->lang->line('swift_code');?>:</li>
                                    <li><?php  if(!empty($query) && !empty($query)) echo $query['swift_code'];?></li>
                                </ul>
                                <ul>
                                    <li><?= $this->lang->line('routing_number');?>:</li>
                                    <li><?php  if(!empty($query) && !empty($query)) echo $query['routing_number'];?></li>
                                </ul>
                                <?php 
									$data= $this->db->get('contact_us')->row_array();
								?>
                                <p><?= $this->lang->line('p6');?> <?php  if(!empty($data) && !empty($data)) echo $data['email'];?> <?= $this->lang->line('p6_2');?> <?php  if(!empty($data) && !empty($data)) echo $data['fax'];?></p>
                                <p>
                                    <?= $this->lang->line('p7');?>
                                </p>
                                <form class="customform" id="demo-form2">
                                    <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
                                    <div class="item">
                                        <label><?= $this->lang->line('transfer_slip');?></label><br>
                                        <input type="file" name="slip" id="slip" accept="image/x-png,image/jpeg">
                                        <span class="valid-error text-danger slip-error"></span>
                                    </div>
                                    <div class="item">
                                        <label><?= $this->lang->line('d_date');?></label>
                                        <input type="text" class="form-control" id="deposit_date" name="deposit_date">
                                         <span class="valid-error text-danger deposit_date-error"></span>
                                    </div>
                                    <div class="item">
                                        <label><?= $this->lang->line('d_amount');?></label>
                                        <input type="text" class="form-control" id="deposit_amount" oninput="this.value=this.value.replace(/[^0-9]/g,'');"  name="deposit_amount">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                         <span class="valid-error text-danger deposit_amount-error"></span>
                                    </div>
                                    <div class="button-row center">
                                        <button class="btn btn-default" id="bankdepositbtn"><?=$this->lang->line('submit_your_deposit');?></button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>     
                </div>
            </div>
        </div>
    </div>
</div>
<!-- 
<body class="gradient-banner">
	<main class="page-wrapper desposite">
		<div class="listing-wrapper">
	    	<?//= $this->load->view('template/template_user_leftbar') ?>
	    	<div class="right-col">
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
							<h2>Deposit</h2>
						</div>
					</div>	
				</div>
				<div class="gray-bg">
					<div class="container">
						<div id="response" ></div>
						<div class="tab-box">
							<h3>Increase your deposit</h3>
							<ul>
								<li><a href="javascript:void(0)" class="btn btn-primary" id="cheq">CHEQUE</a></li>
								<li><a href="javascript:void(0)" class="btn btn-primary" id="crad">CREDIT CARD</a></li>
								<li><a href="javascript:void(0)" class="btn btn-primary" id="banktrns">Bank Trasfer</a></li>
							</ul>
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
										<li>Sun-Thur:8:00am-4:00pm</li>
									</ul>
								</div>
								<div class="map">
									<iframe src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d13610.480337022605!2d74.3000874!3d31.479635499999997!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2s!4v1583893089665!5m2!1sen!2s"  frameborder="0" style="border:0;" allowfullscreen=""></iframe>
									<p>*For cheque payments visit location</p>
								</div>	
							</div>
							<div class="box" id="cradit_card">
								<h3>Pay by Credit Card</h3>
								<p>Please note that the minimum required amount is <?= (isset($setting) && !empty($setting['deposite'])) ?
									$setting['deposite']: '';  ?>  AED.<br>
									We accept Visa and Mastercard only.<br><br>

									(Note that we do not accept Electron or ATM cards, only cards mentioned above are accepted)<br><br>
									If you select this option, you will be asked to enter your Credit Card details in the bank secure online payment page. Once your transaction has been processed you will be able to enter an auction and begin bidding.<br><br>
									The Amount will be deducted from your card and will show in your bank statement, You can request your deposit refund anytime through our application, Refund will be credited to the same card, It usually takes 7-15 working days to get the credit back, depending on your card issuing bank.<br><br>
								Also please note that you cannot include the security deposit paid by credit card in the car final price, this mean that you have to pay the full amount and the deposit will be refunded to you.</p>
								<div class="loc-box">
									<ul>
										<li>Deposit Amount:</li>
										<li>
											<input type="number" min="<?= (isset($setting) && !empty($setting['deposite'])) ? $setting['deposite']: 0;  ?>" value="<?= (isset($setting) && !empty($setting['deposite'])) ? $setting['deposite']: 0;  ?>" id="amount" name="amount">
											<div class="amount-error text-danger"></div>
										</li>
									</ul>
								</div>
								<div>
									<button class="btn btn-success" id="priceedbtn">Proceed </button>
								</div>	
							</div>
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
	</main> -->

<!-- Notify JS Files -->
<script src="<?php echo base_url(); ?>assets_admin/vendors/pnotify/dist/pnotify.js"></script>

<script>
    var token_name = '<?= $this->security->get_csrf_token_name();?>';
    var token_value = '<?=$this->security->get_csrf_hash();?>';
	$(document).ready(function(){

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
	    	$('.amount-error').hide();
	    	var base_url = '<?php base_url() ?>';
	        var amount = $('#amount').val();
	        var rurl = $('#rurl').val();
            var redirect = '<?= (isset($_GET["r"])) ? $_GET["r"] : ""; ?>';
            if (redirect) {
                var url = 'customer/cradit_card?r='+redirect;
            } else {
                var url = 'customer/cradit_card';
            }
	        var min_amount = '<?= (isset($setting) && !empty($setting['value'])) ?
									$setting['value']: '';  ?>';
	        //console.log(min_amount);
	        if (amount < min_amount) {
	        	$('.amount-error').html('<small class="font-weight-bold"> <?= $this->lang->line("min_deposit_amount_is"); ?> '+min_amount+' </samll>').show();
	        	$("input[name=amount]").focus();
	        	$('.amount-error').show();
	        } else {
                // alert('not allowed. get permission from Zain sab');
		        $.ajax({
					url: base_url + url,
					type: 'POST',
					data: {amount:amount, rurl:rurl, [token_name]:token_value},
					beforeSend: function(){
						$('.loading_oz_premium').html('<img src="'+base_url+'assets_admin/images/load.gif" align="center" />');
						$('.loading_oz_premium').parent().attr("disabled",true);
						$('.loading_oz').parent().attr("disabled",true);
					},
			    }).then(function(data) {
			        var objData = jQuery.parseJSON(data);
			        PNotify.removeAll();
			        if (objData.msg == 'success') 
			        {
			        	window.location = objData.redirect;
			        } else {
			        	new PNotify({
				            text: objData.response,
				            type: 'error',
							addclass: 'custom-error',
				            title: "<?= $this->lang->line('error'); ?>",
				            styling: 'bootstrap3'
				        });
						// $('#response').html('<div class="alert" ><div class="alert alert-domain alert-warning alert-dismissible in  msg-alert" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: black"><span aria-hidden="true">×</span> </button><strong> Error! </strong> '+objData.response+'</div></div>');
						// window.scrollTo({top: 190, behavior: "smooth"});
			        }
			    });
	        }
	    });

	    $('#bankdepositbtn').on('click', function(event) {  
	    	event.preventDefault();
	    	console.log('click');

	    	var validation = false;
    		var language = '<?= $language; ?>';
		    selectedInputs = ['slip', 'deposit_date', 'deposit_amount'];
    		validation = validateFields(selectedInputs, language);

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
			        	new PNotify({
					        text: objData.response,
					        type: 'success',
							addclass: 'custom-success',
					        title: "<?= $this->lang->line('success_'); ?>",
					        styling: 'bootstrap3'
					    });
                    	$('#slip').val('');
                    	$('#deposit_date').val('');
                    	$('#deposit_amount').val('');
                    	$('#bankdepositbtn').attr('disabled', true);

			        	// $('#response').html('<div class="alert" ><div class="alert alert-domain alert-success alert-dismissible in  msg-alert" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: black"><span aria-hidden="true">×</span> </button><strong> success! </strong> '+objData.response+'</div></div>');
            //         	window.scrollTo({top: 190, behavior: "smooth"});
			        }
			        else{
			        	new PNotify({
				            text: objData.response,
				            type: 'error',
							addclass: 'custom-error',
				            title: "<?= $this->lang->line('error'); ?>",
				            styling: 'bootstrap3'
				        });
						// $('#response').html('<div class="alert" ><div class="alert alert-domain alert-warning alert-dismissible in  msg-alert" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: black"><span aria-hidden="true">×</span> </button><strong> Error! </strong> '+objData.response+'</div></div>');
      //               	window.scrollTo({top: 190, behavior: "smooth"});
			        }
			    });
			}
	    });

	});
</script>