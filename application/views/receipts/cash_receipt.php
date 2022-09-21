
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <title>Pioneer Auctions</title>
</head>
<body style="font-family: 'Open Sans', sans-serif; font-size: 13px; color: #000; margin: 0; padding-top: 30px;">
	<div class="container" style="max-width: 800px; margin: auto; padding: 0 15px;">
		<table cellpadding="0" cellspacing="0" style="width: 100%; table-layout: fixed;">
			<tbody>
				<tr style="width: 100%">
					<td style="width: 50%;">
						<div class="logo">
							<a href="#">
								<!-- <img src="<?php echo base_url('assets_user/images/logo.png');?>" alt=""> -->
								<img src="<?= base_url('assets_user/new/images/Pioneer-logo.svg');?>" alt="" height="100">
							</a>
						</div>
					</td>
					<td style="width: 50%; text-align: right;">
						<h3 style="font-size: 15px; font-weight: 700; color: #baa055; margin: 0; line-height: 1.4; " >
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
		<div class="box" style="padding: 12px; border: 1px solid #ccc;" >
			<h3 style="font-weight: 700; font-size: 20px; text-align: center; margin: 0 0 30px 0;" >Receipt <?= (isset($data['new_recipt']) && $data['new_recipt'] == 'yes') ? "" : " - <small>(Duplicate)</small>"; ?></h3>

			<table cellpadding="0" cellspacing="0" style="width: 100%; table-layout: fixed;">
				<tbody>
					<tr style="width: 100%">
						<td style="width: 30%;">
							<?php $receipt_no = json_decode($data['receipt_no'], true); ?>
							<p>Receipt No.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span>:&nbsp;<?php if(isset($receipt_no) && !empty($receipt_no)) { echo $receipt_no['b_receipt']; } ?></span></p>
						</td>
						<td style="width: 40%;">
							<?php $dates_array = json_decode($data['receipt_date'], true); ?>
							<p>Date.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span>:&nbsp;<?php if(isset($dates_array['b_receipt']) && !empty($dates_array['b_receipt'])) { echo date('F d, Y', strtotime($dates_array['b_receipt'])); } ?> </span></p>
						</td>

						<td style="width: 30%;">
							<p>Ref No.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span>:&nbsp;<?= (isset($data) && !empty($data['ref_no'])) ? $data['ref_no'] : 'N/A'; ?></span></p>
						</td>
					</tr>
				</tbody>
			</table>

			<table cellpadding="0" cellspacing="0" style="width: 100%; table-layout: fixed; margin-bottom: 20px;">
				<tbody>
					<tr style="width: 100%">
						<td style="width: 70%; vertical-align: bottom;">
							<p>Lot No.&nbsp;&nbsp;&nbsp; <b>:&nbsp;  <?php if(isset($data) && !empty($data)) { echo $data['lot_no']; } ?></b></p>
						</td>
						<td style="width: 30%; vertical-align: bottom;">
							<p>Auction No.&nbsp;&nbsp;&nbsp; <b>:&nbsp; <?php if(isset($data) && !empty($data)) { echo $data['auction_id']; } ?></b></p>
							<p>Paddle No .&nbsp;&nbsp;&nbsp; <b>:&nbsp;  N/A</b></p>
						</td>
					</tr>
				</tbody>
			</table>

			<table cellpadding="0" cellspacing="0" style="width: 100%; table-layout: fixed;">
				<tbody>
					<tr style="width: 100%">
						<td style="width: 100%;">
							<p style="margin-top: 0;">
								<span  style="min-width: 150px; display: inline-block;">Client Name:</span> 
								<b><?php if(isset($data) && !empty($data)) { echo $data['customer_name']; } ?></b>
							</p>
						</td>
					</tr>
					<tr style="width: 100%;" >
						<td style="width: 100%;">
							<p style="margin-top: 0;">
								<span  style="min-width: 150px; display: inline-block;">Purpose:</span> 
								<b><?php if(isset($data) && !empty($data)) { echo $data['purpose']; } ?></b>
							</p>
						</td>
					</tr>
					<tr style="width: 100%;" >
						<td style="width: 100%;">
							<p style="margin-top: 0;">
								<span  style="min-width: 150px; display: inline-block;">Amount in figure:</span> 
								<?php $payable_amount = number_format((float)$data['payable_amount'], 2, '.', ''); ?>
								<b><?php if(isset($payable_amount)) { echo $payable_amount; } ?></b>
							</p>
						</td>
					</tr>
					<tr style="width: 100%;" >
						<td style="width: 100%;">
							<p style="margin-top: 0;">
								<?php $payable_amount_in_words = '';
									if (!empty($payable_amount)) {
							            $spellout = new NumberFormatter("en", NumberFormatter::SPELLOUT);
						    	        $payable_amount_in_words = $spellout->format($payable_amount); 
									}
								?>
								<span  style="min-width: 150px; display: inline-block;">Amount in Words:</span> 
								<b><?php if(isset($payable_amount_in_words) && !empty($payable_amount_in_words)) { echo ucfirst($payable_amount_in_words); } ?></b>
							</p>
						</td>
					</tr>
					<tr style="width: 100%;" >
						<td style="width: 100%;">
							<p style="margin-top: 0;">
								<span  style="min-width: 150px; display: inline-block;">Cash/Draft No:</span> 
								<b><?php if(isset($sold_item) && !empty($sold_item)) { echo  ucwords(str_replace("_"," ", $sold_item['payment_mode'])); } ?></b>
							</p>
						</td>
					</tr>
					<tr style="width: 100%; position: relative;" >
						<td style="width: 100%;">
							<p style="margin-top: 0; position: relative; padding-left: 150px;">
								<span  style="min-width: 150px;  position: absolute; left: 0; top: 0; display: inline-block;">Description:</span> 
								<b><?php if(isset($data) && !empty($data)) { echo $data['description']; } ?></b>
							</p>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		<table cellpadding="0" cellspacing="0" style="width: 100%; table-layout: fixed; margin-top: 40px">
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
							<span style="display: block; font-weight: 600; font-size: 20px; color: #b46167; " >"سيتم استرداد المبلغ مقابل هذه الإيصال"</span>
						</p>
					</td>
				</tr>
			</tbody>
		</table>
		<table cellpadding="0" cellspacing="0" style="width: 100%; table-layout: fixed; margin-top: 100px; text-align: center;">
			<?php
                $generated_by = json_decode($data['generated_by'], true);
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