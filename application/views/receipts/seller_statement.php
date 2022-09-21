
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
		<table cellspacing="0" cellpadding="0" style="width: 100%; text-align: center;">
			<tr style="width: 100%;">
				<td style="width: 100%; font-size: 14px; "  >
					<img src="<?= ASSETS_USER; ?>new/images/Pioneer-logo.svg" alt="" style="max-width: 150px; margin: auto; display: block;">
					<p>
						<?php $contact = $this->db->get_where('contact_us')->row_array(); ?>
						PO Box <?= $contact['po_box']; ?>, <?= json_decode($contact['address'])->english; ?> <br>
						Tel: <?= $contact['phone']; ?>&nbsp;&nbsp;&nbsp;&nbsp; FAX: <?= $contact['fax']; ?><br>

						<b style="display: block; margin-top: 20px" >www.pioneeraucitons.ae</b>
					</p>
				</td>
			</tr>
		</table>
		<?php  $item_info = $this->db->get_where('item', ['id' => $data['item_id']])->row_array();
		// $key = $item_info['detail'];
		// $getKey = json_decode($key);
		// print_r($getKey);die('jdhscbjsdh');
		// print_r($key['keys']);
		?>

		<table cellspacing="0" cellpadding="0" style="width: 100%; margin-top: 40px;">
			<tr style="width: 100%;">
				<td style="width: 60%; font-size: 14px; "  >
					<h4 style="margin: 0;">
						TO
						<span style="display: block; padding-left: 20px" ><?php if(isset($data) && !empty($data)) { echo $data['customer_name']; } ?></span>
					</h4>
					<p style="padding-left: 20px;" >
						<!-- Tel: 971 646596459&nbsp;&nbsp; -  -->Mob: <?= (isset($data) && !empty($data['user_mobile_number'])) ? $data['user_mobile_number'] : 'N/A' ?><br>
						<?= ($data) ? $data['user_address'] : 'N/A' ?>

					</p>
				</td>
				<td style="width: 40%; font-size: 14px; "  >
					<h5 style="font-size: 18px; margin: 0; padding: 5px 10px; background: #000; color: #fff; text-align: center; margin: 0 0 15px 0" >COPY SALES INVOICE</h5>

					<table cellspacing="0" cellpadding="0" style="width: 100%;">
						<tr style="width: 100%">
							<td>Reg.No.</td>
							<td style="border: 1px solid #000; border-bottom: 0; padding: 5px 10px" ><?php if(isset($item_info) && !empty($item_info)) { echo $item_info['registration_no']; } ?></td>
						</tr>
						<tr style="width: 100%">
							<td>Lot ID.</td>
							<td style="border: 1px solid #000; border-bottom: 0; padding: 5px 10px"><?php if(isset($data) && !empty($data)) { echo $data['lot_no']; } ?></td>
						</tr>
						<tr style="width: 100%">
							<td>Sale</td>
							<td style="border: 1px solid #000; border-bottom: 0; padding: 5px 10px"><?php if(isset($sold_item) && !empty($sold_item)) { echo date('F d, Y', strtotime($sold_item['created_on'])); } ?></td>
						</tr>
						<tr style="width: 100%">
							<td>Ref:</td>
							<td style="border: 1px solid #000; padding: 5px 10px"><?= (!empty($data['ref_no'])) ? $data['ref_no'] : 'N/A'; ?></td>
						</tr>
					</table>
				</td>
			</tr>
		</table>

		<table cellspacing="0" cellpadding="0" style="width: 100%; margin-top: 40px;">
			<tr style="width: 100%;">
				<td style="width: 50%; font-size: 14px; border: 1px solid #000; padding: 50px 0 0 0; vertical-align: top;"  >
					<h4 style="margin: 0; font-size: 16px; text-align: center;">
						Brand / Model : <?php if(isset($item_info) && !empty($item_info)) { echo json_decode($item_info['name'])->english; } ?>
					</h4>
					<table cellspacing="0" cellpadding="0" style="width: 100%; margin-top: 20px;">
						<tr>
							<td style="padding: 5px 10px; width: 30%;" >
								<b>VIN:</b>
							</td>
							<td style="padding: 5px 10px; width: 70%;" >
								<?php if(isset($data) && !empty($data)) { echo $data['vin_number']; } ?>
							</td>
						</tr>

						<tr>
							<td style="padding: 5px 10px;" >
								<b>Year:</b>
							</td>
							<td style="padding: 5px 10px;" >
								<?= (!empty($item_info['year'])) ? $item_info['year'] : 'N/A'; ?>
							</td>
						</tr>
						<?php $category = $this->db->get_where('item_category', ['id' => $item_info['category_id']])->row_array();
							$make = $this->db->get_where('item_makes', ['id' => $item_info['make']])->row_array();
							$model = $this->db->get_where('item_models', ['id' => $item_info['model']])->row_array();
						if ($category['include_make_model'] == 'yes') { ?>
							<tr>
								<td style="padding: 5px 10px;" >
									<b>Make:</b>
								</td>
								<td style="padding: 5px 10px;" >
									<?= ($make['title']) ? json_decode($make['title'])->english : 'N/A'; ?>
								</td>
							</tr>
							<tr>
								<td style="padding: 5px 10px;" >
									<b>Model:</b>
								</td>
								<td style="padding: 5px 10px;" >
									<?= ($model['title']) ? json_decode($model['title'])->english : 'N/A'; ?>
								</td>
							</tr>
							<tr>
								<td style="padding: 5px 10px;" >
									<b>Mileage:</b>
								</td>
								<td style="padding: 5px 10px;" >
									<?= (!empty($item_info['mileage'])) ? $item_info['mileage'].$item_info['mileage_type'] : 'N/A'; ?>
								</td>
							</tr>
						<?php } ?>
					</table>
					<p style="font-size: 10px; padding: 10px; margin: 160px 0 0 0 " >
						The Terms and Conditions which the Purchaser shall be deemed the have read.<br>
						The Terms and Conditions are shown on the reverse of this invoice.<br>
						It is a condition of the sale that the purchaser, on receipt of the registration documents, checks that the Chassis Number correspons to that shown on the Registration Card. and any descrepancy is notified to Pioneer Auctions, before the vehicle leaves the premises. 
					</p>
				</td>
				<td style="width: 50%; font-size: 14px; padding: 14px 14px 0 14px; vertical-align: bottom; "  >
					<table  cellspacing="0" cellpadding="0" style="width: 100%; margin-top: 40px; text-align: left;">
						<thead>
							<th style="border: 1px solid #000; border-left: 0; border-right: 0; padding: 10px 5px; text-align: center;">Item</th>
							<th style="border: 1px solid #000; border-right: 0; text-align: right; padding: 10px 5px" ></th>
							<th style="border: 1px solid #000; border-right: 0; text-align: right; padding: 10px 5px" >Total</th>
						</thead>
						<tbody>
							<tr>
								<td style="border-bottom: 1px solid #000; padding: 10px 5px">Sale - <?php if(isset($item_info) && !empty($item_info)) { echo $item_info['registration_no']; } ?> </td>
								<td style=" border-left: 1px solid #000; border-bottom: 1px solid #000; text-align: right;" ><?php if(isset($data) && !empty($data)) { echo number_format((float)$data['win_price'], 2, '.', ''); } ?></td>
								<td style=" border-left: 1px solid #000; border-bottom: 1px solid #000; text-align: right;" ><?php if(isset($data) && !empty($data)) { echo number_format((float)$data['win_price'], 2, '.', ''); } ?></td>
							</tr>
							<tr>
								<td style="border-bottom: 1px solid #000; padding: 10px 5px">Total </td>

								<td style=" border-left: 1px solid #000; border-bottom: 1px solid #000; text-align: right;" ><?php if(isset($data) && !empty($data)) { echo number_format((float)$data['win_price'], 2, '.', ''); } ?></td>
								<td style=" border-left: 1px solid #000; border-bottom: 1px solid #000; text-align: right;" ><?php if(isset($data) && !empty($data)) { echo number_format((float)$data['win_price'], 2, '.', ''); } ?></td>
							</tr>
							<tr>
								<td style="border-bottom: 1px solid #000; padding: 10px 5px">Payment</td>
								<td style=" border-left: 1px solid #000; border-bottom: 1px solid #000; text-align: right;" ></td>
								<td style=" border-left: 1px solid #000; border-bottom: 1px solid #000; text-align: right;" ></td>
							</tr>
							<tr>
								<td style="border-bottom: 1px solid #000; padding: 10px 5px">Deposit</td>
								<td style=" border-left: 1px solid #000; border-bottom: 1px solid #000; text-align: right;" ></td>
								<td style=" border-left: 1px solid #000; border-bottom: 1px solid #000; text-align: right;" ><?php if(isset($sold_item) && !empty($sold_item['adjusted_deposit'])) { echo number_format((float)$sold_item['adjusted_deposit'], 2, '.', ''); } ?></td>
							</tr>
							<tr>
								<td style="border-bottom: 1px solid #000; padding: 10px 5px">Security</td>
								<td style=" border-left: 1px solid #000; border-bottom: 1px solid #000; text-align: right;" ></td>
								<td style=" border-left: 1px solid #000; border-bottom: 1px solid #000; text-align: right;" ><?php if(isset($sold_item) && !empty($sold_item['adjusted_security'])) { echo number_format((float)$sold_item['adjusted_security'], 2, '.', ''); } ?></td>
							</tr>
							<tr>
								<td style="border-bottom: 1px solid #000; padding: 10px 5px">Payment - <?php if(isset($sold_item) && !empty($sold_item)) { echo ucwords(str_replace("_"," ", $sold_item['payment_mode'])); } ?> </td>
								<td style=" border-left: 1px solid #000; border-bottom: 1px solid #000; text-align: right;" ></td>
								<td style=" border-left: 1px solid #000; border-bottom: 1px solid #000; text-align: right;" ><?php if(isset($sold_item) && !empty($sold_item)) { echo number_format((float)$sold_item['payable_amount'], 2, '.', ''); } ?></td>
							</tr>
							<tr>
								<td style="border-bottom: 1px solid #000; font-size: 18px; font-weight: 700; padding: 10px 5px; padding-top: 80px;">Paid in Full  </td>
								<td style=" border-left: 1px solid #000; border-bottom: 1px solid #000; text-align: right;" ></td>
								<td style=" border-left: 1px solid #000; font-size: 18px; font-weight: 700; border-bottom: 1px solid #000; text-align: right;  padding-top: 80px;" ><?php if(isset($data) && !empty($data)) { echo number_format((float)$data['win_price'], 2, '.', ''); } ?></td>
							</tr>
						</tbody>
					</table>

		            <?php 
			            $amount_in_words = '';
			            $spellout = new NumberFormatter("en", NumberFormatter::SPELLOUT);
			            $amount_in_words = $spellout->format($data['win_price']); 
		            ?>
					<p style=" text-align: right; margin: 0" ><?php if(isset($amount_in_words) && !empty($amount_in_words)) { echo $amount_in_words; } ?></p>
				</td>
			</tr>
		</table>	

		<table cellpadding="0" cellspacing="0" style="width: 100%; table-layout: fixed; margin-top: 60px">
			<tbody>
				<tr style="width: 100%">
					<td style="width: 50%;">
						<p style="margin-top: 0;">
							<b>Cashier:</b> JULIUS
						</p>
					</td>
					<td style="width: 50%; text-align: right;">
						<p style="margin-top: 0;">
							<b>Customer Signature</b>
						</p>
					</td>
				</tr>
			</tbody>
		</table>	
	</div>

	<!-- <div class="container" style="max-width: 800px; margin: auto; padding: 0 15px 40px 15px; margin-top: 30px;">
		<h3 style="font-weight: 700; font-size: 20px; text-align: center; margin: 0 0 30px 0;">Gate Pass</h3> 
		<p style=" font-size: 14px; text-align: center; margin: 0" >
			THE ABOVE VEHICLE REMAINS THE PROPERTY OF THE SELLER UNTILL FULL PAYMENT HAS CLEARED.<br>
			(TITLE WILL THEN BE TRANSFERRED)
		</p>
		<h4 style="font-size: 16px; text-align: center; margin-top: 0;" >THE ABOVE VEHICLE IS SOLD WITHOUT GUARANTEE AND SOLD AS SEEN</h4>
		<div class="box" style="padding: 10px; border: 2px solid #000">
			<table cellspacing="0" cellpadding="0" style="width: 100%;">
				<tr>
					<td style="width: 50%;" >
						<img src="images/logo.png" style="max-width: 140px;" alt="">
					</td>
					<td style="width: 50%; text-align: right;">
						<div style="display: table; margin: auto 0 auto auto; text-align: center;" >
							<h2 style="margin: 0" >PASS OUT</h2>
							<p style="margin: 10px 0 0 0; padding-top: 10px; border-top: 2px solid #000;" >Sale Date&nbsp;&nbsp; <?php //if(isset($sold_item) && !empty($sold_item)) { echo date('F d, Y', strtotime($sold_item['created_on'])); } ?></p>
						</div>
					</td>
				</tr>
			</table>

			<table cellspacing="0" cellpadding="0" style="width: 100%; margin-top: 40px; text-align: center;">
				<tr>
					<td>
						<h4 style="margin: 0; font-size: 17px;" >KEYS</h4>
					</td>
					<td>
						<h4 style="margin: 0; font-size: 17px;" >
							<span style="display: block; font-size: 14px; font-weight: 500;" >Make:-</span>
							<?php // if(isset($item_info) && !empty($item_info)) { echo json_decode($item_info['name'])->english; } ?>
						</h4>
					</td>
					<td>
						<h4 style="margin: 0; font-size: 17px;" >
							<span style="display: block; font-size: 14px; font-weight: 500;" >Lot No:-</span>
							<?php //if(isset($data) && !empty($data)) { echo $data['lot_no']; } ?>
						</h4>
					</td>
					<td>
						<h4 style="margin: 0; font-size: 17px;" >
							<span style="display: block; font-size: 14px; font-weight: 500;" >Reg No:-</span>
							<?php //if(isset($item_info) && !empty($item_info)) { echo $item_info['registration_no']; } ?>
						</h4>
					</td>
				</tr>
			</table>
			<table cellpadding="0" cellspacing="0" style="width: 100%; table-layout: fixed; margin-top: 100px; text-align: center;">
			<?php
                //$generated_by = json_decode($data['generated_by'], true);
                //$userName = '';
               // if (isset($generated_by['s_statement'])) {
	               // $generater_id = $generated_by['s_statement'];
	               // $cashier = $this->db->get_where('users', ['id' => $generater_id])->row_array();
	               //   $userName = $cashier['username'];
               // }
			?>
			<tr>
				<td>
					<p><?php //echo $userName; ?></p>
					<b style="display: table; margin: 10px auto auto auto; padding: 5px 20px; border-top: 2px dashed #000;" >Generated By</b>
				</td>
				
			</tr>
		</table>
		</div>
	</div>-->
</body>
</html>