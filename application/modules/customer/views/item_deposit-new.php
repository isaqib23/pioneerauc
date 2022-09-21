<!-- PNotify -->
<link href="<?php echo base_url(); ?>assets_admin/vendors/pnotify/dist/pnotify.css" rel="stylesheet">

<style type="text/css">
	.align-items-center{ margin-bottom: 20px; }
</style>
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
                                <a class="nav-link active" id="model-tab" data-toggle="tab" href="#cradit_card" role="tab" aria-controls="model" aria-selected="true"><?= $this->lang->line('credit_c');?></a>
                            </li>
                            <!-- <li class="nav-item">
                                <a class="nav-link" id="location-tab" data-toggle="tab" href="#location" role="tab" aria-controls="location" aria-selected="false">Bank Trasfer</a>
                            </li> -->
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
                            <div class="tab-pane fade show active" id="cradit_card" role="tabpanel" aria-labelledby="model-tab">
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
								<div  class="customform auctions-items">
	                              	<ul class="align-items-center">
										<label><?= $this->lang->line('select_auction');?>:</label>
										<li><select class="selectpicker form-control" id="auction" name="auction_id">
											<option value=""><?= $this->lang->line('select_auction');?></option>
											<?php $auction_id = $_GET['auction_id']; foreach ($auctions as $key => $value) {
												$title = json_decode($value['title']);
												?>
												<option <?= (isset($auction_id) && $auction_id == $value['id']) ? 'selected=selected' : ''; ?> value="<?= $value['id']; ?>"><?= $title->$language; ?></option>
												<?php
											} ?>
										</select></li>
									</ul>
									<ul class="align-items-center">
										<label><?= $this->lang->line('select_item');?>:</label>
										<li><select class="selectpicker" id="auction_item" name="auction_item_id">
											<option value=""> <?= $this->lang->line('select_auction_first');?> </option>
										</select></li>
									</ul>
									<ul class="align-items-center">
										<!-- <li>Deposit Amount:</li>							 -->
										<label><?= $this->lang->line('deposit_amount');?>:</label>							
										<li><input class="form-control" type="number" readonly="readonly" id="amount" name="amount"></li>
									</ul>
								</div>	
                                <div class="button-row">
                                    <button class="btn btn-default" disabled="disabled" id="priceedbtn" ><?= $this->lang->line('proceed');?></button>
                                    <!-- <a class="btn btn-default" href="<?= base_url('customer'); ?>">Skip</a> -->
                                </div>
                            </div>
                            <div class="tab-pane fade" id="bank" role="tabpanel" aria-labelledby="Location-tab">
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
                                        <input type="file" name="slip" id="slip">
                  						<div class="text-danger slip-error"></div>
                                    </div>
                                    <div class="item">
                                        <label><?= $this->lang->line('d_date');?></label>
                                        <!-- <input type="text" class="form-control" id="deposit_date" name="deposit_date"> -->
                  						<span class="text-danger deposit_date-error"></span>
                                    </div>
                                    <div class="item">
                                        <label><?= $this->lang->line('d_amount');?></label>
                                        <input type="text" class="form-control" id="deposit_amount" name="deposit_amount">
                  						<span class="text-danger deposit_amount-error"></span>
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

<!-- Notify JS Files -->
<script src="<?php echo base_url(); ?>assets_admin/vendors/pnotify/dist/pnotify.js"></script>

<script>
	$(document).ready(function(){
		<?php if (isset($_GET['item_id'])) { ?>
			$('#auction').change();
			$('#auction_item').change();
		<?php } ?>

		// $('#deposit_date').datepicker({
		// 	format: 'yyyy-mm-d',
  //   		// startDate: '-30d',
	 //     	ignoreReadonly: true,
	 //     	autoclose: true
	 //     	// allowInputToggle: true,
	 //    	// minDate: moment(),
	 //    });
		// $('#deposit_date').datepicker();
	    $('#cheque').hide();
		$('#cradit_card').show();
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
		     data: {amount:amount, auction_id:auction_id, auction_item_id:auction_item_id, item_id:item_id, rurl:rurl,'<?= $this->security->get_csrf_token_name();?>':"<?=$this->security->get_csrf_hash();?>"},
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
					$('#error-response').html('<div class="alert" ><div class="alert alert-domain alert-warning alert-dismissible in  msg-alert" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: black"><span aria-hidden="true">×</span> </button><strong> <?= $this->lang->line("invalid"); ?> </strong> '+objData.response+'</div></div>');
					window.scrollTo({top: 10, behavior: "smooth"});
		        }
		    });
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
			        	$('#response').html('<div class="alert" ><div class="alert alert-domain alert-success alert-dismissible in  msg-alert" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: black"><span aria-hidden="true">×</span> </button><strong> <?= $this->lang->line("success_"); ?> </strong> '+objData.response+'</div></div>');
                    	window.scrollTo({top: 190, behavior: "smooth"});
                    	$('#slip').val('');
                    	$('#deposit_date').val('');
                    	$('#deposit_amount').val('');
			        }
			        else{
						$('#response').html('<div class="alert" ><div class="alert alert-domain alert-warning alert-dismissible in  msg-alert" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: black"><span aria-hidden="true">×</span> </button><strong> <?= $this->lang->line("error_"); ?> </strong> '+objData.response+'</div></div>');
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
         data: {id: id, user_id: user_id, item_id: item_id,'<?= $this->security->get_csrf_token_name();?>':"<?=$this->security->get_csrf_hash();?>"},
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
        	$('#auction_item').html('<option value=""> <?= $this->lang->line("select_auction_first"); ?> </option>');
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
     data: {id: id,'<?= $this->security->get_csrf_token_name();?>':"<?=$this->security->get_csrf_hash();?>"},
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