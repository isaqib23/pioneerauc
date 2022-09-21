
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <title>Pioneer Auctions</title>
</head>
<body style="font-family: 'Open Sans', sans-serif; font-size: 12px; color: #000; margin: 0; padding-top: 0px;">
<div class="container" style="max-width: 800px; margin: auto; padding: 0 15px;">
		<table cellpadding="0" cellspacing="0" style="width: 100%; table-layout: fixed;">
			<tbody>
				<tr style="width: 100%">
					<td style="width: 50%;">
						<div class="logo">
							<a href="#">
								<img src="<?php echo base_url('assets_user/images/logo.png');?>" alt="">
							
							</a>
						</div>
					</td>
					<td style="width: 50%; text-align: right;">
						<h3 style="font-size: 13px; font-weight: 700; color: #baa055; margin: 0; line-height: 1.4; " >
							<?php $contact = $this->db->get_where('contact_us')->row_array(); ?>
							Dubai - United Arab Emirates<br>
							P.O. BOX:<?= $contact['po_box']; ?><br>
							T : <?= $contact['phone']; ?><br>
							F : <?= $contact['fax']; ?><br>
							<a href="#" style="color: #baa055; text-decoration: none;" >www.pioneerauctions.ae</a>
						</h3>
					</td>
				</tr>
			</tbody>
		</table>
		<div class="box" style="padding: 12px; border: 1px solid #ccc; margin-top: 15px;" >
			<h3 style="font-weight: 700; font-size: 15px; text-align: center; margin: 0 0 0px 0;" >Receipt <?= (isset($data['new_recipt']) && $data['new_recipt'] == 'yes') ? "" : "  <small></small>"; ?></h3>

			<table cellpadding="0" cellspacing="0" style="width: 100%; table-layout: fixed;">
				<tbody>

					<tr style="width: 100%">
						<td style="width: 30%;">
							<p style="margin: 5px 0px;">Receipt No.&nbsp;&nbsp;&nbsp; <span>:&nbsp;<?php if(isset($randomKey) && !empty($randomKey)) { echo $randomKey; } ?></span></p>
						</td>
						<td style="width: 40%;">
							<? $date = date('d-F-Y') ?>
							<p style="margin: 5px 0px;">Date.&nbsp;&nbsp;&nbsp; <span>:&nbsp;<?= date('F d, Y', strtotime($receipt['created_on'])); ?> </span></p>
						</td>

						<td style="width: 30%;">
							<p style="margin: 0px;">Ref No.&nbsp;&nbsp;&nbsp; <span>:&nbsp;<?= (isset($data) && !empty($data['ref_no'])) ? $data['ref_no'] : 'N/A'; ?></span></p>
						</td>
					</tr>
				</tbody>
			</table>

			<table cellpadding="0" cellspacing="0" style="width: 100%; table-layout: fixed; margin-bottom: 10px;">
				<tbody>
					<tr style="width: 100%">
						<?php if ($receipt['deposit_type'] != 'permanent') : ?>
							<td style="width: 70%; vertical-align: bottom;">
								<!-- <p>Lot No.&nbsp;&nbsp;&nbsp; <b>:&nbsp;  <?php if(isset($lot_no) && !empty($lot_no)) { echo $lot_no; } ?></b></p> -->
							</td>
							<td style="width: 30%; vertical-align: bottom;">
								<p style="margin: 0px;">Auction No.&nbsp;&nbsp;&nbsp; <b>:&nbsp; <?php if(isset($auction_id) && !empty($auction_id)) { echo $auction_id; } ?></b></p>
								
							</td>
							<td style="width: 30%; vertical-align: bottom;">
								
								<p style="margin: 0px;">Paddle No.&nbsp;&nbsp;&nbsp; <b>:&nbsp; <?php if(isset($card_number) && !empty($card_number)) { echo $card_number; } ?></b></p>
							</td>
						<?php endif; ?>
					</tr>
				</tbody>
			</table>

			<table cellpadding="0" cellspacing="0" style="width: 100%; table-layout: fixed;">
				<tbody>
					<tr style="width: 100%">
						<td style="width: 100%;">
							<p style="margin-top: 0; margin-bottom: 0px;">
								<span  style="min-width: 150px; display: inline-block;">Client Name:</span> 
								<b><?php if(isset( $customer_name) && !empty( $customer_name)) { echo $customer_name; } ?></b>
							</p>
						</td>
					</tr>
					<tr style="width: 100%;" >
						<td style="width: 100%;">
							<p style="margin-top: 0; margin-bottom: 0px;">
								<span  style="min-width: 150px; display: inline-block;">Purpose:</span> 
								<b><?php if(isset($purpose) && !empty($purpose)) { echo $purpose; } ?></b>
							</p>
						</td>
					</tr>
					 <tr style="width: 100%;" >
						<td style="width: 100%;">
							<p style="margin-top: 0; margin-bottom: 0px;">
								<span  style="min-width: 150px; display: inline-block;">Amount in figure:</span>
								<!-- <?php echo number_format($receipt['amount'], 0, ".", ","); ?> -->
								<b>AED <?php if(isset($amount)) { echo number_format($receipt['amount'], 0, ".", ","); } ?></b>
							</p>
						</td>
					</tr>
					<tr style="width: 100%;" >
						<td style="width: 100%;">
							<p style="margin-top: 0; margin-bottom: 0px;">
								<?php $payable_amount_in_words = '';
									if (!empty($amount)) {
							            $spellout = new NumberFormatter("en", NumberFormatter::SPELLOUT);
						    	        $payable_amount_in_words = $spellout->format($receipt['amount']); 
									}
								?>
								<span  style="min-width: 150px; display: inline-block;">Amount in Words:</span> 
								<b><?php if(isset($payable_amount_in_words) && !empty($payable_amount_in_words)) { echo ucfirst($payable_amount_in_words); } ?></b>
							</p>
						</td>
					</tr>
					<tr style="width: 100%;" >
						<td style="width: 100%;">
							<p style="margin-top: 0; margin-bottom: 0px;">
								<span  style="min-width: 150px; display: inline-block;">Cash/Cheque:</span> 
								<b><?php if(isset($payment_mode) && !empty($payment_mode)) { echo ucfirst($payment_mode);} ?></b>
							</p>
						</td>
					</tr>
					<tr style="width: 100%;" >
						<td style="width: 100%;">
							<p style="margin-top: 0; margin-bottom: 0px;">
								<span  style="min-width: 150px; display: inline-block;">Description:</span> 
								<b><?= (isset($description) && !empty($description)) ? $description : 'N/A'; ?></b>
							</p>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		<table cellpadding="0" cellspacing="0" style="width: 100%; table-layout: fixed; margin-top: 10px">
			<tbody>
				<tr style="width: 100%">
					<td style="width: 50%;">
						<p style="margin-top: 0;">
							<b>Cashier's Sign</b>
						</p>
					</td>
					<td style="width: 50%; text-align: right;">
						<p style="margin-top: 0;">
							<b>Customer's Sign</b>
						</p>
					</td>
				</tr>
			</tbody>
		</table>
		<table cellpadding="0" cellspacing="0" style="width: 100%; table-layout: fixed;">
			<tbody>
				<tr style="width: 100%">
					<td style="width: 50%;">
						<p style="font-size: 14px; margin-top: 0; color: #baa055;">
							Please read the terms and condition backside
							<span style="display: block; font-weight: 600; font-size: 17px;" >"REFUND WILL BE AGAINST THIS RECEIPT"</span>
						</p>
					</td>
					<td style="width: 50%; text-align: right;">
						<p style="font-size: 17px; margin-top: 0; color: #000;">
							يرجى قراءة الشروط والأحكام في الخلف
							<span style="display: block; font-weight: 600; font-size: 15px; color: #b46167; " >"سيتم استرداد المبلغ مقابل هذه الإيصال"</span>
						</p>
					</td>
				</tr>
			</tbody>
		</table>
		<table cellpadding="0" cellspacing="0" style="width: 100%; table-layout: fixed; margin-top: 0px; text-align: center;">
			 <?php
			    $generated_by['b_receipt'] = $this->session->userdata('logged_in')->id;
                // $generated_by = json_decode($data['generated_by'], true);
                $generater_id = $generated_by['b_receipt'];
                $cashier = $this->db->get_where('users', ['id' => $generater_id])->row_array();
                $userName = $cashier['username'];
			?> 
			<tr>
				<td>
					<p><?php echo $userName; ?></p> 
					<b style="display: table; margin: 10px auto auto auto; padding: 5px 20px; border-top: 2px dashed #000;" >Generated By</b>
				</td>
				
			</tr>
		</table>
	</div>	
<div class="qenchi" style="height: 10px; display: flex;"><img style="    position: relative;
    width: 20px;
	height: 20px;
    top: -2px;" src="<?php echo base_url('assets_user/new/images/quenchi.png');?>" alt=""><hr style="width:96%; margin: auto; border-width: 1px; border-style: dotted; text-align:left;margin-left:0; color: black; opacity: 0.5;"></div>
	<div class="container" style="max-width: 800px; margin: auto; padding: 0 15px;">
		<table cellpadding="0" cellspacing="0" style="width: 100%; table-layout: fixed;">
			<tbody>
				<tr style="width: 100%">
					<td style="width: 50%;">
						<div class="logo">
							<a href="#">
								<img src="<?php echo base_url('assets_user/images/logo.png');?>" alt="">
							
							</a>
						</div>
					</td>
					<td style="width: 50%; text-align: right;">
						<h3 style="font-size: 13px; font-weight: 700; color: #baa055; margin: 0; line-height: 1.4; " >
							<?php $contact = $this->db->get_where('contact_us')->row_array(); ?>
							Dubai - United Arab Emirates<br>
							P.O. BOX:<?= $contact['po_box']; ?><br>
							T : <?= $contact['phone']; ?><br>
							F : <?= $contact['fax']; ?><br>
							<a href="#" style="color: #baa055; text-decoration: none;" >www.pioneerauctions.ae</a>
						</h3>
					</td>
				</tr>
			</tbody>
		</table>
		<div class="box" style="padding: 12px; border: 1px solid #ccc; margin-top: 15px;" >
			<h3 style="font-weight: 700; font-size: 15px; text-align: center; margin: 0 0 0px 0;" >Receipt <?= (isset($data['new_recipt']) && $data['new_recipt'] == 'yes') ? "" : "  <small></small>"; ?></h3>

			<table cellpadding="0" cellspacing="0" style="width: 100%; table-layout: fixed;">
				<tbody>

					<tr style="width: 100%">
						<td style="width: 30%;">
							<p style="margin: 5px 0px;">Receipt No.&nbsp;&nbsp;&nbsp; <span>:&nbsp;<?php if(isset($randomKey) && !empty($randomKey)) { echo $randomKey; } ?></span></p>
						</td>
						<td style="width: 40%;">
							<? $date = date('d-F-Y') ?>
							<p style="margin: 5px 0px;">Date.&nbsp;&nbsp;&nbsp; <span>:&nbsp;<?= date('F d, Y', strtotime($receipt['created_on'])); ?> </span></p>
						</td>

						<td style="width: 30%;">
							<p style="margin: 0px;">Ref No.&nbsp;&nbsp;&nbsp; <span>:&nbsp;<?= (isset($data) && !empty($data['ref_no'])) ? $data['ref_no'] : 'N/A'; ?></span></p>
						</td>
					</tr>
				</tbody>
			</table>

			<table cellpadding="0" cellspacing="0" style="width: 100%; table-layout: fixed; margin-bottom: 10px;">
				<tbody>
					<tr style="width: 100%">
						<?php if ($receipt['deposit_type'] != 'permanent') : ?>
							<td style="width: 70%; vertical-align: bottom;">
								<!-- <p>Lot No.&nbsp;&nbsp;&nbsp; <b>:&nbsp;  <?php if(isset($lot_no) && !empty($lot_no)) { echo $lot_no; } ?></b></p> -->
							</td>
							<td style="width: 30%; vertical-align: bottom;">
								<p style="margin: 0px;">Auction No.&nbsp;&nbsp;&nbsp; <b>:&nbsp; <?php if(isset($auction_id) && !empty($auction_id)) { echo $auction_id; } ?></b></p>
							</td>
							<td style="width: 30%; vertical-align: bottom;">
								<p style="margin: 0px;">Paddle No.&nbsp;&nbsp;&nbsp; <b>:&nbsp; <?php if(isset($card_number) && !empty($card_number)) { echo $card_number; } ?></b></p>
							</td>
						<?php endif; ?>
					</tr>
				</tbody>
			</table>

			<table cellpadding="0" cellspacing="0" style="width: 100%; table-layout: fixed;">
				<tbody>
					<tr style="width: 100%">
						<td style="width: 100%;">
							<p style="margin-top: 0; margin-bottom: 0px;">
								<span  style="min-width: 150px; display: inline-block;">Client Name:</span> 
								<b><?php if(isset( $customer_name) && !empty( $customer_name)) { echo $customer_name; } ?></b>
							</p>
						</td>
					</tr>
					<tr style="width: 100%;" >
						<td style="width: 100%;">
							<p style="margin-top: 0; margin-bottom: 0px;">
								<span  style="min-width: 150px; display: inline-block;">Purpose:</span> 
								<b><?php if(isset($purpose) && !empty($purpose)) { echo $purpose; } ?></b>
							</p>
						</td>
					</tr>
					 <tr style="width: 100%;" >
						<td style="width: 100%;">
							<p style="margin-top: 0; margin-bottom: 0px;">
								<span  style="min-width: 150px; display: inline-block;">Amount in figure:</span><strong> </strong> 
								<!-- <?php echo number_format($receipt['amount'], 0, ".", ","); ?> -->
								<b>AED <?php if(isset($amount)) { echo number_format($receipt['amount'], 0, ".", ","); } ?></b>
							</p>
						</td>
					</tr>
					<tr style="width: 100%;" >
						<td style="width: 100%;">
							<p style="margin-top: 0; margin-bottom: 0px;">
								<?php $payable_amount_in_words = '';
									if (!empty($amount)) {
							            $spellout = new NumberFormatter("en", NumberFormatter::SPELLOUT);
						    	        $payable_amount_in_words = $spellout->format($receipt['amount']); 
									}
								?>
								<span  style="min-width: 150px; display: inline-block;">Amount in Words:</span> 
								<b><?php if(isset($payable_amount_in_words) && !empty($payable_amount_in_words)) { echo ucfirst($payable_amount_in_words); } ?></b>
							</p>
						</td>
					</tr>
					<tr style="width: 100%;" >
						<td style="width: 100%;">
							<p style="margin-top: 0; margin-bottom: 0px;">
								<span  style="min-width: 150px; display: inline-block;">Cash/Cheque:</span> 
								<b><?php if(isset($payment_mode) && !empty($payment_mode)) { echo ucfirst($payment_mode);} ?></b>
							</p>
						</td>
					</tr>
					<tr style="width: 100%; position: relative;" >
						<td style="width: 100%;">
							<p style="margin-top: 0; margin-bottom: 0px;">
								<span  style="min-width: 150px; display: inline-block;">Description:</span> 
								<b><?= (isset($description) && !empty($description)) ? $description : 'N/A'; ?></b>
							</p>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		<table cellpadding="0" cellspacing="0" style="width: 100%; table-layout: fixed; margin-top: 10px">
			<tbody>
				<tr style="width: 100%">
					<td style="width: 50%;">
						<p style="margin-top: 0;">
							<b>Cashier's Sign</b>
						</p>
					</td>
					<td style="width: 50%; text-align: right;">
						<p style="margin-top: 0;">
							<b>Customer's Sign</b>
						</p>
					</td>
				</tr>
			</tbody>
		</table>
		<table cellpadding="0" cellspacing="0" style="width: 100%; table-layout: fixed;">
			<tbody>
				<tr style="width: 100%">
					<td style="width: 50%;">
						<p style="font-size: 14px; margin-top: 0; color: #baa055;">
							Please read the terms and condition backside
							<span style="display: block; font-weight: 600; font-size: 17px;" >"REFUND WILL BE AGAINST THIS RECEIPT"</span>
						</p>
					</td>
					<td style="width: 50%; text-align: right;">
						<p style="font-size: 17px; margin-top: 0; color: #000;">
							يرجى قراءة الشروط والأحكام في الخلف
							<span style="display: block; font-weight: 600; font-size: 15px; color: #b46167; " >"سيتم استرداد المبلغ مقابل هذه الإيصال"</span>
						</p>
					</td>
				</tr>
			</tbody>
		</table>
		<table cellpadding="0" cellspacing="0" style="width: 100%; table-layout: fixed; margin-top: 0px; text-align: center;">
			 <?php
			    $generated_by['b_receipt'] = $this->session->userdata('logged_in')->id;
                // $generated_by = json_decode($data['generated_by'], true);
                $generater_id = $generated_by['b_receipt'];
                $cashier = $this->db->get_where('users', ['id' => $generater_id])->row_array();
                $userName = $cashier['username'];
			?> 
			<tr>
				<td>
					<p><?php echo $userName; ?></p> 
					<b style="display: table; margin: 10px auto auto auto; padding: 5px 20px; border-top: 2px dashed #000;" >Generated By</b>
				</td>
				
			</tr>
		</table>
	</div>
</body>
</html>