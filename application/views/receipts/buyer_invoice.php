
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
						<h1 style="font-weight: 700; font-size: 24px; color: #000; margin: 0" >Tax - Invoice<?= (isset($data['new_recipt']) && $data['new_recipt'] == 'yes') ? "" : " - <small>(Duplicate)</small>"; ?></h1>
					</td>
					<td style="width: 50%; text-align: right;">
						<div class="logo">
							<a href="#">
								<img src="<?= ASSETS_USER; ?>new/images/Pioneer-logo.svg" alt="" height="100">
							</a>
						</div>
					</td>
				</tr>
			</tbody>
		</table>
		<?php $auctionType = $this->db->get_where('auctions',['id'=> $data['auction_id']])->row_array();
		?>

		<table cellpadding="0" cellspacing="0" style="width: 100%; table-layout: fixed; margin-top: 40px;">
			<tbody>
				<tr style="width: 100%">
					<td style="width: 50%; vertical-align: top;">
						<h2 style="font-weight: 700; font-size: 16px; color: #000; margin: 0" >
							BILL TO:
						</h2>
						<p><?= (isset($data['customer_name']) && !empty($data['customer_name'])) ? $data['customer_name'] : 'N/A'; ?><!-- Alba Motors LLC --><br>PO Box#<?= (!empty($data['user_po_box'])) ? $data['user_po_box'] : 'N/A'; ?>
							<?= (!empty($data['user_address'])) ? $data['user_address'] : 'N/A'; ?> <!-- Dubai, United Arab Emirates --></p>

						<p style="margin: 78px 0 0 0">
							<span style="min-width: 120px; display: inline-block;" >TRN No. <?= (!empty($data['trn_no'])) ? $data['trn_no'] : 'N/A'; ?></span>
						</p>

					</td>
					<td style="width: 50%; vertical-align: top;" >
						<h2 style="font-weight: 700; font-size: 16px; color: #000; margin: 0; text-align: right; " >
							Pioneer Auctions Organzing
						</h2>
						<table cellpadding="0" cellspacing="0" style="width: 100%; table-layout: fixed;     border: 1px solid #000; margin-top: 18px;">
							<tr>
								<td style="padding: 4px 8px" >
									<?php $receipt_no = json_decode($data['receipt_no'], true); ?>
									<span style="min-width: 120px; display: inline-block;" >Invoice  No.</span><!-- PS10573 --><?= (!empty($receipt_no['b_invoice'])) ? $receipt_no['b_invoice'] : 'N/A'; ?>
								</td>
							</tr>
							<tr>
								<td style="padding: 4px 8px" >
									<?php $dates_array = json_decode($data['receipt_date'], true); ?>
									<span style="min-width: 120px; display: inline-block;">Invoice  Date:</span><!-- January 8, 2020 --><?php if(isset($dates_array['b_invoice']) && !empty($dates_array['b_invoice'])) { echo date('F d, Y' ,strtotime($dates_array['b_invoice'])); } ?>
								</td>
							</tr>
							<tr>
								<td style="padding: 4px 8px" >
									<span style="min-width: 120px; display: inline-block;">Payment Terms:</span><?= (!empty($data['payment_terms'])) ? $data['payment_terms'] : 'N/A'; ?>
								</td>
							</tr>
							<tr>
								<td style="padding: 4px 8px" >
									<span style="min-width: 120px; display: inline-block;">Due Date:</span><?= date('F d, Y') ?>
								</td>
							</tr>
							<tr>
								<td style="padding: 4px 8px" >
									<span style="min-width: 120px; display: inline-block;">TRN No:</span><?= (!empty($data['trn_no'])) ? $data['trn_no'] : 'N/A'; ?>
								</td>
							</tr>
							<tr>
								<td style="padding: 4px 8px" >
									<span style="min-width: 120px; display: inline-block;">Reference No:</span><?= (!empty($data['ref_no'])) ? $data['ref_no'] : 'N/A'; ?>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</tbody>
		</table>

		<table cellpadding="0" cellspacing="0" style="width: 100%; table-layout: fixed; margin-top: 40px; text-align: left; border: 1px solid #000; border-bottom: 0;">
			<thead style="width: 100%;">
				<th style="padding: 10px 5px; vertical-align: bottom; font-size: 12px; border-bottom: 1px solid #000; border-right: 1px solid #000;" >Item No.</th>
				<th style="padding: 10px 5px; vertical-align: bottom; font-size: 12px; border-bottom: 1px solid #000; border-right: 1px solid #000; width: 130px" >Description</th>
				<th style="padding: 10px 5px; vertical-align: bottom; font-size: 12px; border-bottom: 1px solid #000; border-right: 1px solid #000;" >Auction</th>
				<th style="padding: 10px 5px; vertical-align: bottom; font-size: 12px; border-bottom: 1px solid #000; border-right: 1px solid #000;" >Lot</th>
				<th style="padding: 10px 5px; vertical-align: bottom; font-size: 12px; border-bottom: 1px solid #000; border-right: 1px solid #000; width: 70px;" >VIN/Chassis No.</th>
				<th style="padding: 10px 5px; vertical-align: bottom; font-size: 12px; border-bottom: 1px solid #000; border-right: 1px solid #000; width: 50px;" >Qty</th>
				<th style="padding: 10px 5px; vertical-align: bottom; font-size: 12px; border-bottom: 1px solid #000; border-right: 1px solid #000;" >Rate</th>
				<th style="padding: 10px 5px; vertical-align: bottom; font-size: 12px; border-bottom: 1px solid #000; border-right: 1px solid #000;" >Amount<br>(Excl.VAT)</th>
				<th style="padding: 10px 5px; vertical-align: bottom; font-size: 12px; border-bottom: 1px solid #000; border-right: 1px solid #000; width: 50px;" >VAT</th>
				<th style="padding: 10px 5px; vertical-align: bottom; font-size: 12px; border-bottom: 1px solid #000;" >Total Amount<br>(incl.VAT)</th>
			</thead>
			<tbody style="width: 100%;">
				<?php  
				$general_commission = json_decode($data['general_expenses'], true);
				$general_expenses_amount = json_decode($data['general_expenses_amount'], true);
				$general_description = json_decode($data['general_description'], true);
				$apply_vat = json_decode($data['apply_vat'], true);
				// print_r($general_commission);die();
				if (!empty($data['vat'])) {
				 	$vat_per = $data['vat'];
				} else {
				 	$vat_per = 0;
				} 
				$total_including_vat = 0;
				$total_excluding_vat = 0;
				$total_vat = 0;
				if ($general_commission) {
					foreach ($general_commission as $key => $value) {
						$charge_description = $general_description[$key];
						$sub_total = $general_expenses_amount[$key];
							$vat_status = ($apply_vat[$key]) ? $apply_vat[$key] : 'yes';
						if ($vat_status == 'yes') {
							$sub_total_excluding_vat = (100*$sub_total)/(100+$vat_per);
						} else{
							$sub_total_excluding_vat = $sub_total;
						}
						// $sub_total_excluding_vat = (100*$sub_total)/(100+$vat_per);
						
						$total_excluding_vat = $total_excluding_vat + $sub_total_excluding_vat;
						$vat = $sub_total - $sub_total_excluding_vat;
						$total_vat = $total_vat + $vat;
						$total_including_vat = $total_including_vat + $sub_total;
						?>
						<tr style="width: 100%;">
							<td style="padding: 10px 5px; vertical-align: top; font-size: 12px; border-bottom: 1px solid #000; border-right: 1px solid #000;"><?= (!empty($data['item_id'])) ? $data['item_id'] : 'N/A'; ?></td>
							<td style="padding: 10px 5px; vertical-align: top; font-size: 12px; border-bottom: 1px solid #000; border-right: 1px solid #000; width: 130px"><?= (!empty($charge_description)) ? $charge_description : 'N/A'; ?></td>
							<td style="padding: 10px 5px; vertical-align: top; font-size: 12px; border-bottom: 1px solid #000; border-right: 1px solid #000;"><?= (!empty($auctionType['access_type'])) ? $auctionType['access_type'] : 'N/A'; ?></td>
							<td style="padding: 10px 5px; vertical-align: top; font-size: 12px; border-bottom: 1px solid #000; border-right: 1px solid #000; text-align: right;"><?= (!empty($data['lot_no'])) ? $data['lot_no'] : 'N/A'; ?></td>
							<td style="padding: 10px 5px; vertical-align: top; font-size: 12px; border-bottom: 1px solid #000; border-right: 1px solid #000; width: 70px; word-break: break-all;"><?= (!empty($data['vin_number'])) ? $data['vin_number'] : 'N/A'; ?></td>

							<td style="padding: 10px 5px; vertical-align: top; font-size: 12px; border-bottom: 1px solid #000; border-right: 1px solid #000; width: 50px; text-align: right;">1</td>

							<?php $sub_total_excluding_vat = number_format((float)$sub_total_excluding_vat, 2, '.', ''); ?>
							<td style="padding: 10px 5px; vertical-align: top; font-size: 12px; border-bottom: 1px solid #000; border-right: 1px solid #000; text-align: right;"><?= (!empty($sub_total_excluding_vat)) ? $sub_total_excluding_vat : 'N/A'; ?></td>
							<td style="padding: 10px 5px; vertical-align: top; font-size: 12px; border-bottom: 1px solid #000; border-right: 1px solid #000; text-align: right;"><?= (!empty($sub_total_excluding_vat)) ? $sub_total_excluding_vat : 'N/A'; ?></td>

							<?php $vat = number_format((float)$vat, 2, '.', ''); ?>
							<td style="padding: 10px 5px; vertical-align: top; font-size: 12px; border-bottom: 1px solid #000; border-right: 1px solid #000; width: 50px; text-align: right;"><?= (!empty($vat)) ? $vat : 'N/A'; ?></td>

							<?php $sub_total = number_format((float)$sub_total, 2, '.', ''); ?>
							<td style="padding: 10px 5px; vertical-align: top; font-size: 12px; border-bottom: 1px solid #000; text-align: right;"><?= (!empty($sub_total)) ? $sub_total : 'N/A'; ?></td>
						</tr>
					<?php } ?>
				<?php } ?>
			</tbody>
		</table>

		<table cellpadding="0" cellspacing="0" style="width: 350px; margin: auto 0 auto auto; table-layout: fixed; margin-top: 20px; text-align: left; border: 1px solid #000; border-bottom: 0; font-size: 12px; text-align: right;">
			<tr>
				<td style="padding: 5px 10px; width: 50%; border-bottom: 1px solid #000; border-right: 1px solid #000;" ><b>Total AED Excl. VAT</b></td>
				<td style="padding: 5px 10px; width: 50%; border-bottom: 1px solid #000;" ><b><?= (!empty($total_excluding_vat)) ? number_format((float)$total_excluding_vat, 2, '.', '') : 'N/A'; ?></b></td>
			</tr>
			<tr>
				<td style="padding: 5px 10px; width: 50%; border-bottom: 1px solid #000; border-right: 1px solid #000;" >Discount</td>
				<td style="padding: 5px 10px; width: 50%; border-bottom: 1px solid #000;" ></td>
			</tr>
			<tr>
				<td style="padding: 5px 10px; width: 50%; border-bottom: 1px solid #000; border-right: 1px solid #000;" >Net Of Discount</td>
				<td style="padding: 5px 10px; width: 50%; border-bottom: 1px solid #000;" ><?= (!empty($total_excluding_vat)) ? number_format((float)$total_excluding_vat, 2, '.', '') : 'N/A'; ?></td>
			</tr>
			<tr>
				<td style="padding: 5px 10px; width: 50%; border-bottom: 1px solid #000; border-right: 1px solid #000;" ><?= $data['vat']; ?>% VAT</td>
				<td style="padding: 5px 10px; width: 50%; border-bottom: 1px solid #000;" ><?= (!empty($total_vat)) ? number_format((float)$total_vat, 2, '.', '') : 'N/A'; ?></td>
			</tr>
			<tr>
				<td style="padding: 5px 10px; width: 50%; border-bottom: 1px solid #000; border-right: 1px solid #000;" >&nbsp;</td>
				<td style="padding: 5px 10px; width: 50%; border-bottom: 1px solid #000;" >&nbsp;</td>
			</tr>
			<tr>
				<td style="padding: 5px 10px; width: 50%; border-bottom: 1px solid #000; border-right: 1px solid #000;" ><b>Total AED Incl. VAT</b></td>
				<?php $total_including_vat = number_format((float)$total_including_vat, 2, '.', ''); ?>
				<td style="padding: 5px 10px; width: 50%; border-bottom: 1px solid #000;" ><b><?= (isset($total_including_vat) && !empty($total_including_vat)) ? $total_including_vat : 'N/A'; ?></b></td>
			</tr>
		</table>
		<?php 
		if (isset($total_including_vat)) {
            $spellout = new NumberFormatter("en", NumberFormatter::SPELLOUT);
            $buyer_comission_in_words = $spellout->format($total_including_vat); 
        ?>
			<p style="margin: 20px 0 40px 0" >Total in Words: **** <?= ucfirst($buyer_comission_in_words) ?> DIRHAMS</p>
		<?php } ?>

		<?php $bank_id = $data['bank_id'];
		$bank_information = $this->db->get_where('bank_info',['id' =>$bank_id])->row_array();
		// print_r($bank_information);
		if (!empty($bank_information)) { ?>
			<table cellpadding="0" cellspacing="0" style="width: 400px; table-layout: fixed; margin-top: 20px; text-align: left; border: 1px solid #000; font-size: 12px;">
				<tr>
					<td style="padding: 10px" >
						<p style="font-size: 14px; font-weight: 500; margin: 0 0 20px 0" >Company Bank Details</p>
						<p style="margin: 0 0 5px 0" ><span>Bank Name : </span> <?php if(isset($bank_information) && !empty($bank_information)) { echo $bank_information['bank_name']; } ?></p>
						<p style="margin: 0 0 5px 0" ><span>Branch Name : </span> Dxb Airline Center P.O. Box :  666,Dubai,AE</p>
						<p style="margin: 0 0 5px 0" ><span>Account Name : </span> <?php if(isset($bank_information) && !empty($bank_information)) { echo $bank_information['ac_name']; } ?></p>
						<p style="margin: 0 0 5px 0" ><span>Account No : </span> <?php if(isset($bank_information) && !empty($bank_information)) { echo $bank_information['ac_number']; } ?></p>
						<p style="margin: 0 0 5px 0" ><span>IBAN No : </span> <?php if(isset($bank_information) && !empty($bank_information)) { echo $bank_information['iban']; } ?></p>
						<p style="margin: 0 0 0 0" ><span>Swift Code : </span> <?php if(isset($bank_information) && !empty($bank_information)) { echo $bank_information['swift_code']; } ?></p>
					</td>
				</tr>
			</table>
		<?php } ?>

		<table cellpadding="0" cellspacing="0" style="width: 100%; table-layout: fixed; margin-top: 100px; text-align: center;">
			<tr>
				<td>
					<b style="display: table; margin: 10px auto auto auto; padding: 5px 20px; border-top: 2px dashed #000;" >PREPARED BY</b>
				</td>
				<td>
					<b style="display: table; margin: 10px auto auto auto; padding: 5px 20px; border-top: 2px dashed #000;" >VERIFIED BY</b>
				</td>
				<td>
					<b style="display: table; margin: 10px auto auto auto; padding: 5px 20px; border-top: 2px dashed #000;" >APPROVED BY</b>
				</td>
				<td>
					<b style="display: table; margin: 10px auto auto auto; padding: 5px 20px; border-top: 2px dashed #000;" >RECEIVED BY</b>
				</td>
			</tr>
		</table>


		<table cellpadding="0" cellspacing="0" style="width: 100%; table-layout: fixed; margin: 60px 0 60px 0;">
			<tr>
				<td>
					<img src="images/logo.png" alt="" style="max-width: 140px;">
					<p style="margin: 10px 0 0 0">
						<?php $contact = $this->db->get_where('contact_us')->row_array(); ?>
						Pioneer Auctions Organizing <br>
						<?php $address = json_decode($contact['address']) ?>
						<?php if(isset($contact['address'])){echo $address->english;} ?> 
					</p>
				</td>
				
				<td style="border-left: 2px solid #000; padding-left: 40px;" >
					<p style="margin: 0 0 5px 0">TELEPHONE : <?= ($contact) ? $contact['mobile'] : 'N/A' ?></p>
					<p style="margin: 0">FAX : <?= ($contact) ? $contact['fax'] : 'N/A' ?></p>	
				</td>
				
			</tr>
		</table>

		<table cellpadding="0" cellspacing="0" style="width: 100%; table-layout: fixed; margin-top: 100px; text-align: center;">
			<?php
                $generated_by = json_decode($data['generated_by'], true);
                $generater_id = $generated_by['b_invoice'];
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