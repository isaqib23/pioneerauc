<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <title>Pioneer Auctions</title>

    <style>
    	@media print {
		  .break  {page-break-after: always;}
		}
    </style>
</head>
<body style="font-family: 'Open Sans', sans-serif; font-size: 13px; color: #000; margin: 0; padding-top: 30px; list-style: none !important;">
	<div class="container" style="max-width: 800px; margin: auto; padding: 0 15px;">
		<table cellpadding="0" cellspacing="0" style="width: 100%; table-layout: fixed;">
			<tbody style="width: 100%; display: flex;">
				<?php $contact_us_data = $this->db->get('contact_us')->row_array();
				$adrs = json_decode($contact_us_data['address']);
				// $date = date('D/m/y');
				?>
				<tr style="width: 30%">
					<td style="font-family: 'Open Sans', sans-serif; font-weight: bold; display: block; color: #003366; font-size: 16px; line-height: 17px; margin: 0 0 6px 0;">Pioneer Auctions</td>
					<td style="font-family: 'Open Sans', sans-serif; font-weight: bold; display: block; color: #003366; font-size: 16px; line-height: 17px; margin: 0 0 6px 0;"><?php echo $adrs->english;?></td>
					<td style="font-family: 'Open Sans', sans-serif; font-weight: bold; display: block; color: #003366; font-size: 16px; line-height: 17px; margin: 0 0 6px 0;">Dubai</td>
					<td style="font-family: 'Open Sans', sans-serif; font-weight: bold; display: block; color: #003366; font-size: 16px; line-height: 17px; margin: 0 0 6px 0;">T: <?php echo $contact_us_data['phone'];?></td>
					<td style="font-family: 'Open Sans', sans-serif; font-weight: bold; display: block; color: #003366; font-size: 16px; line-height: 17px; margin: 0 0 6px 0;">F: <?php echo $contact_us_data['fax'];?></td>
				</tr>
				<tr style="width: 70%; display: flex;">
					<td style="width: 100%; text-align: right;">
						<div class="logo" style="margin-bottom: 20px;">
							<a href="#">
								<img style="width: 300px;" src="<?= NEW_ASSETS_USER; ?>new/images/logo/logo_header_english.svg" alt="">
							</a>
						</div>
						<h1 style="font-family: 'Open Sans', sans-serif; font-weight: 800; display: block; color: #000; font-size: 18px; margin: 0 0 0px 0; display: inline-block; padding-right: 20px;"> Vehicle Inspection Report
							<span style="font-family: 'Open Sans', sans-serif; font-weight: 600; display: block; color: #000; font-size: 13px; margin: 0; text-align: center;">Date Inspected: <?php echo date('d/m/y');?></span>
						</h1>
					</td>
				</tr>
			</tbody>
		</table>
		
		<table cellpadding="0" cellspacing="0" style="width: 100%; table-layout: fixed; margin-top: 4px;">
			<tbody>
				<tr style="width: 100%; display: flex;">
					<td style="width: 52%; padding: 3px 10px; margin-right: 10px; border: 2px solid #000; vertical-align: top;" >
						<h2 style="font-family: 'Open Sans', sans-serif; font-weight: 800; font-size: 15px; color: #000; margin: 0 0 1px 0;" >
							Vendor: Pioneer Auctions
						</h2>
						<p style="font-family: 'Open Sans', sans-serif; font-weight: 800; font-size: 15px; color: #000; margin: 0;">Agreement No:</p>
					</td>

					<td style="width: 26%; padding: 3px 10px; margin-right: 10px; border: 2px solid #000; vertical-align: top;" >
						<h2 style="font-family: 'Open Sans', sans-serif; font-weight: 800; font-size: 15px; color: #000; margin: 0 0 1px 0;" >
							Reg: <?php 
							if(isset($contact_us_data) && !empty($contact_us_data)){
								echo $data['registration_no'];
							}else{
								echo "N/A";
							}?>
						</h2>
						<p style="font-family: 'Open Sans', sans-serif; font-weight: 800; font-size: 15px; color: #000; margin: 0;">CN: MNTBB7A99E6014680</p>
					</td>

					<td style="width: 16%; padding: 3px 10px; border: 2px solid #000; vertical-align: top;" >
						<h2 style="font-family: 'Open Sans', sans-serif; font-weight: 800; font-size: 15px; color: #000; text-align: center; margin: 0 0 1px 0;" >
							Specification
						</h2>
						<p style="font-family: 'Open Sans', sans-serif; font-weight: 600; font-size: 15px; color: #000; margin: 0; text-align: center;"><?php 
							if(isset($contact_us_data) && !empty($contact_us_data)){
								echo $data['specification'];
							}else{
								echo "N/A";
							}?></p>
					</td>
				</tr>
			</tbody>
		</table>

		<table cellpadding="0" cellspacing="0" style="width: 100%; table-layout: fixed; margin-top: 6px; border: 2px solid; padding: 10px 8px 20px 8px;">
			<tbody>
				<tr style="width: 100%; display: flex;">
					<td style="width: 40%;">
						<?php 
							$make = $this->db->get_where('item_makes',['id' => $data['make']])->row_array();
							$model = $this->db->get_where('item_models',['id' => $data['model']])->row_array();
						?>
						<ul style="display: flex; list-style: none !important; padding: 0; margin: 0 0 5px 0;">
							<li style="font-family: 'Open Sans', sans-serif; font-weight: 800; font-size: 13px; min-width: 90px; color: #000; margin: 0 0 1px 0;">
								Make:
							</li>
							<li style="font-family: 'Open Sans', sans-serif; font-weight: 600; font-size: 12px; color: #000; margin: 0 0 1px 0;">
								<?php 
									if(isset($make) && !empty($make)){
										$m_title = json_decode($make['title']);
										echo $m_title->english;
									}else{
										echo "N/A";
									}?>
							</li>
						</ul>
						<ul style="display: flex; list-style: none !important; padding: 0; margin: 0 0 5px 0;">
							<li style="font-family: 'Open Sans', sans-serif; font-weight: 800; font-size: 13px; min-width: 90px; color: #000; margin: 0 0 1px 0;">
								Model: 
							</li>
							<li style="font-family: 'Open Sans', sans-serif; font-weight: 600; font-size: 12px; color: #000; margin: 0 0 1px 0;">
								<?php 
									if(isset($model) && !empty($model)){
										$mdl_title = json_decode($model['title']);
										echo $mdl_title->english;
									}else{
										echo "N/A";
									}?>
							</li>
						</ul>

						<!-- <ul style="display: flex; list-style: none !important; padding: 0; margin: 0 0 5px 0;">
							<li style="font-family: 'Open Sans', sans-serif; font-weight: 800; font-size: 13px; min-width: 90px; color: #000; margin: 0 0 1px 0;">
								Engine Size: 
							</li>
							<li style="font-family: 'Open Sans', sans-serif; font-weight: 600; font-size: 12px;  color: #000; margin: 0 0 1px 0;">		4 cylinder
							</li>
						</ul> -->

						<ul style="display: flex; list-style: none !important; padding: 0; margin: 0 0 5px 0;">
							<li style="font-family: 'Open Sans', sans-serif; font-weight: 800; font-size: 13px; min-width: 90px; color: #000; margin: 0 0 1px 0;">
								Engine No:
							</li>
							<li style="font-family: 'Open Sans', sans-serif; font-weight: 600; font-size: 12px; color: #000; margin: 0 0 1px 0;">
								<?= $data['registration_no']; ?>
							</li>
						</ul>

						<ul style="display: flex; list-style: none !important; padding: 0; margin: 0 0 5px 0;">
							<li style="font-family: 'Open Sans', sans-serif; font-weight: 800; font-size: 13px; min-width: 90px; color: #000; margin: 0 0 1px 0;">
								Year:
							</li>
							<li style="font-family: 'Open Sans', sans-serif; font-weight: 600; font-size: 12px; color: #000; margin: 0 0 1px 0;">
								<?= $data['year']; ?>
							</li>
						</ul>

					</td>
					<td style="width: 60%;">
					<?php 
					$i=0; 
					$j=10;
					foreach ($fields as $key => $value) {
						if (!empty($value['data-value'])) {
							$i++;
							if($i<= $j) { ?>
								<ul style="display: flex; list-style: none !important; padding: 0; margin: 0 0 5px 0;">
								<li style="font-family: 'Open Sans', sans-serif; font-weight: 800; font-size: 13px; min-width: 120px; color: #000; margin: 0 0 1px 0;">
								<?= explode('|', $value['label'])[0]; ?>: 
								</li>
								<li style="font-family: 'Open Sans', sans-serif; font-weight: 600; font-size: 12px; color: #000; margin: 0 0 1px 0;"><?= explode('|', $value['data-value'])[0]; ?>
								</li>
								</ul>
							<?php } 
						} 
					}?>
					</td>
				</tr>
			</tbody>
		</table>

		<!-- <table cellpadding="0" cellspacing="0"  style="width: 100%; table-layout: fixed; margin-top: 6px; border: 2px solid; padding: 10px 8px 2px 10px">
			<tbody>
				<tr style="width: 100%; display: flex;">
					
					<td style="width: 22%;">
						
						<ul style="display: flex; list-style: none !important; padding: 0; margin: 0 0 5px 0;">
							<li style="font-family: 'Open Sans', sans-serif; font-weight: 800; font-size: 13px; min-width: 65px; text-align: center; color: #000; margin: 0 0 1px 0;">
								Extras
							</li>
							<li style="font-family: 'Open Sans', sans-serif; font-weight: 600; font-size: 12px; color: #000; margin: 0 0 1px 0;">
							</li>
						</ul>

						<ul style="display: flex; list-style: none !important; padding: 0; margin: 0 0 5px 0;">
							<li style="font-family: 'Open Sans', sans-serif; font-weight: 800; font-size: 13px; min-width: 65px; color: #000; margin: 0 0 1px 0;">
								Interior: 
							</li>
							<li style="font-family: 'Open Sans', sans-serif; font-weight: 600; font-size: 12px; color: #000; margin: 0 0 1px 0;">
								Cloth
							</li>
						</ul>

						<ul style="display: flex; list-style: none !important; padding: 0; margin: 0 0 5px 0;">
							<li style="font-family: 'Open Sans', sans-serif; font-weight: 800; font-size: 13px; min-width: 65px; color: #000; margin: 0 0 1px 0;">
								Seats:
							</li>
							<li style="font-family: 'Open Sans', sans-serif; font-weight: 600; font-size: 12px;  color: #000; margin: 0 0 1px 0;">		
								Manual
							</li>
						</ul>
						<ul style="display: flex; list-style: none !important; padding: 0; margin: 0 0 5px 0;">
							<li style="font-family: 'Open Sans', sans-serif; font-weight: 800; font-size: 13px; min-width: 65px; color: #000; margin: 0 0 1px 0;">
								Stereo:
							</li>
							<li style="font-family: 'Open Sans', sans-serif; font-weight: 600; font-size: 12px; color: #000; margin: 0 0 1px 0;">		
								CD player
							</li>
						</ul>
						<ul style="display: flex; list-style: none !important; padding: 0; margin: 0 0 5px 0;">
							<li style="font-family: 'Open Sans', sans-serif; font-weight: 800; font-size: 13px; min-width: 65px; color: #000; margin: 0 0 1px 0;">
								Heating:

							</li>
							<li style="font-family: 'Open Sans', sans-serif; font-weight: 600; font-size: 12px;  color: #000; margin: 0 0 1px 0;">		
								 Air Con
							</li>
						</ul>
					</td>

					<td style="width: 24%;">
						
						<ul style="display: flex; list-style: none !important; padding: 0; margin: 0 0 5px 0;">
							<li style="font-family: 'Open Sans', sans-serif; font-weight: 800; font-size: 13px; min-width: 95px; color: #000; margin: 0 0 1px 0;">
								Cruise Control:
							</li>
							<li style="font-family: 'Open Sans', sans-serif; font-weight: 600; font-size: 12px; color: #000; padding-left: 8px; margin: 0 0 1px 0;">		
								No
							</li>
						</ul>

						<ul style="display: flex; list-style: none !important; padding: 0; margin: 0 0 5px 0;">
							<li style="font-family: 'Open Sans', sans-serif; font-weight: 800; font-size: 13px; min-width: 95px; color: #000; margin: 0 0 1px 0;">
								Pwr Steering:
							</li>
							<li style="font-family: 'Open Sans', sans-serif; font-weight: 600; font-size: 12px; color: #000; padding-left: 8px; margin: 0 0 1px 0;">
								Yes
							</li>
						</ul>

						<ul style="display: flex; list-style: none !important; padding: 0; margin: 0 0 5px 0;">
							<li style="font-family: 'Open Sans', sans-serif; font-weight: 800; font-size: 13px; min-width: 95px; color: #000; margin: 0 0 1px 0;">
								M Func S Wh:
							</li>
							<li style="font-family: 'Open Sans', sans-serif; font-weight: 600; font-size: 12px;  color: #000; padding-left: 8px; margin: 0 0 1px 0;">		
								No
							</li>
						</ul>

						<ul style="display: flex; list-style: none !important; padding: 0; margin: 0 0 5px 0;">
							<li style="font-family: 'Open Sans', sans-serif; font-weight: 800; font-size: 13px; min-width: 95px; color: #000; margin: 0 0 1px 0;">
								Elec Windows
							</li>
							<li style="font-family: 'Open Sans', sans-serif; font-weight: 600; font-size: 12px; color: #000; padding-left: 8px; margin: 0 0 1px 0;">		
								4
							</li>
						</ul>

						<ul style="display: flex; list-style: none !important; padding: 0; margin: 0 0 5px 0;">
							<li style="font-family: 'Open Sans', sans-serif; font-weight: 800; font-size: 13px; min-width: 95px; color: #000; margin: 0 0 1px 0;">
								Elec Mirrors:

							</li>
							<li style="font-family: 'Open Sans', sans-serif; font-weight: 600; font-size: 12px;  color: #000; padding-left: 8px; margin: 0 0 1px 0;">		
								Yes
							</li>
						</ul>
					</td>

					<td style="width: 18%;">
						
						<ul style="display: flex; list-style: none !important; padding: 0; margin: 0 0 5px 0;">
							<li style="font-family: 'Open Sans', sans-serif; font-weight: 800; font-size: 13px; min-width: 50px; color: #000; margin: 0 0 1px 0;">
								Sunroof:
							</li>
							<li style="font-family: 'Open Sans', sans-serif; font-weight: 600; font-size: 12px; color: #000; padding-left: 8px; margin: 0 0 1px 0;">		
								No
							</li>
						</ul>


						<ul style="display: flex; list-style: none !important; padding: 0; margin: 0 0 5px 0;">
							<li style="font-family: 'Open Sans', sans-serif; font-weight: 800; font-size: 13px; min-width: 50px; color: #000; margin: 0 0 1px 0;">
								F P Sens:
							</li>
							<li style="font-family: 'Open Sans', sans-serif; font-weight: 600; font-size: 12px;  color: #000; padding-left: 8px; margin: 0 0 1px 0;">		
								No
							</li>
						</ul>

						<ul style="display: flex; list-style: none !important; padding: 0; margin: 0 0 5px 0;">
							<li style="font-family: 'Open Sans', sans-serif; font-weight: 800; font-size: 13px; min-width: 50px; color: #000; margin: 0 0 1px 0;">
								R P Sens:
							</li>
							<li style="font-family: 'Open Sans', sans-serif; font-weight: 600; font-size: 12px; color: #000; padding-left: 8px; margin: 0 0 1px 0;">		
								No
							</li>
						</ul>

						<ul style="display: flex; list-style: none !important; padding: 0; margin: 0 0 5px 0;">
							<li style="font-family: 'Open Sans', sans-serif; font-weight: 800; font-size: 13px; min-width: 50px; color: #000; margin: 0 0 1px 0;">
								Alloy W:

							</li>
							<li style="font-family: 'Open Sans', sans-serif; font-weight: 600; font-size: 12px;  color: #000; padding-left: 8px; margin: 0 0 1px 0;">		
								No
							</li>
						</ul>

						<ul style="display: flex; list-style: none !important; padding: 0; margin: 0 0 5px 0;">
							<li style="font-family: 'Open Sans', sans-serif; font-weight: 800; font-size: 13px; min-width: 50px; color: #000; margin: 0 0 1px 0;">
								Other:

							</li>
							<li style="font-family: 'Open Sans', sans-serif; font-weight: 600; font-size: 12px;  color: #000; padding-left: 8px; margin: 0 0 1px 0;">
							</li>
						</ul>
					</td>

					<td style="width: 18%;">
						
						<ul style="display: flex; list-style: none !important; padding: 0; margin: 0 0 5px 0;">
							<li style="font-family: 'Open Sans', sans-serif; font-weight: 800; font-size: 13px; min-width: 50px; color: #000; margin: 0 0 1px 0;">
								ABS:
							</li>
							<li style="font-family: 'Open Sans', sans-serif; font-weight: 600; font-size: 12px; color: #000; padding-left: 8px; margin: 0 0 1px 0;">		
								Yes
							</li>
						</ul>


						<ul style="display: flex; list-style: none !important; padding: 0; margin: 0 0 5px 0;">
							<li style="font-family: 'Open Sans', sans-serif; font-weight: 800; font-size: 13px; min-width: 50px; color: #000; margin: 0 0 1px 0;">
								M Paint:
							</li>
							<li style="font-family: 'Open Sans', sans-serif; font-weight: 600; font-size: 12px;  color: #000; padding-left: 8px; margin: 0 0 1px 0;">		
								Yes
							</li>
						</ul>

						<ul style="display: flex; list-style: none !important; padding: 0; margin: 0 0 5px 0;">
							<li style="font-family: 'Open Sans', sans-serif; font-weight: 800; font-size: 13px; min-width: 50px; color: #000; margin: 0 0 1px 0;">
								Sat Nav:
							</li>
							<li style="font-family: 'Open Sans', sans-serif; font-weight: 600; font-size: 12px; color: #000; padding-left: 8px; margin: 0 0 1px 0;">		
								No
							</li>
						</ul>

						<ul style="display: flex; list-style: none !important; padding: 0; margin: 0 0 5px 0;">
							<li style="font-family: 'Open Sans', sans-serif; font-weight: 800; font-size: 13px; min-width: 50px; color: #000; margin: 0 0 1px 0;">
								C Lock:

							</li>
							<li style="font-family: 'Open Sans', sans-serif; font-weight: 600; font-size: 12px;  color: #000; padding-left: 8px; margin: 0 0 1px 0;">		
								No
							</li>
						</ul>
					</td>

					<td style="width: 14%;">
						
						<ul style="display: flex; list-style: none !important; padding: 0; margin: 0 0 5px 0;">
							<li style="font-family: 'Open Sans', sans-serif; font-weight: 800; font-size: 13px; min-width: 50px; color: #000; margin: 0 0 1px 0;">
								P Airbag:
							</li>
							<li style="font-family: 'Open Sans', sans-serif; font-weight: 600; font-size: 12px; color: #000; padding-left: 8px; margin: 0 0 1px 0;">		
								Yes
							</li>
						</ul>

						<ul style="display: flex; list-style: none !important; padding: 0; margin: 0 0 5px 0;">
							<li style="font-family: 'Open Sans', sans-serif; font-weight: 800; font-size: 13px; min-width: 50px; color: #000; margin: 0 0 1px 0;">
								D Airbag:
							</li>
							<li style="font-family: 'Open Sans', sans-serif; font-weight: 600; font-size: 12px;  color: #000; padding-left: 8px; margin: 0 0 1px 0;">		
								Yes
							</li>
						</ul>
					</td>

				</tr>
			</tbody>
		</table> -->

		<table cellpadding="0" cellspacing="0" class="break" style="width: 100%; table-layout: fixed; margin-top: 8px; margin-bottom: 150px;">
			<tbody style="width: 100%; display: flex; flex-wrap: wrap;">
				<?php 
					$img_explode = explode(',', $data['item_images']);
					$j=0;
					?>
				<tr style="width: 100%; display: flex; flex-wrap: wrap;">
				<?php 
				foreach ($img_explode as $key => $img) {
				    $j++;
					$files = $this->db->get_where('files',['id' => $img])->row_array();?>
					<?php if(!empty($files)) : ?>
						<td style="width: 50%; text-align: right;">
							<div class="image" style="margin-bottom: 8px; width: 300px; height: 200px; border: 2px solid #000; overflow: hidden; margin: auto;">
									<a href="#">
										<img src="<?= base_url($files['path'] . $files['name']); ?>"height="243" width="317" style="margin-top: 15px">
									</a>
							</div>
							<p style="font-weight: 700; text-align: center; color: #000; font-size: 17px; margin: 0 0 5px 0;"><?php echo $j?></p>
						</td>
					<?php endif; ?>
				<?php }?>
				</tr>
				<!-- <tr style="width: 100%; display: flex; flex-wrap: wrap;">
					<td style="width: 50%; text-align: right;">
						<div class="image" style="margin-bottom: 8px; width: 300px; height: 200px; border: 2px solid #000; overflow: hidden; margin: auto;">
							<a href="#">
								<img src="<?//= base_url() ?>assets_user/images/live-img2.png" alt="">
							</a>
						</div>
						<p style="font-weight: 700; text-align: center; color: #000; font-size: 17px; margin: 0 0 5px 0;">3</p>
					</td>

					<td style="width: 50%; text-align: right;">
						<div class="image" style="margin-bottom: 8px; width: 300px; height: 200px; border: 2px solid #000; overflow: hidden; margin: auto;">
							<a href="#">
								<img src="<?//= base_url() ?>assets_user/images/live-img2.png" alt="">
							</a>
						</div>
						<p style="font-weight: 700; text-align: center; color: #000; font-size: 17px; margin: 0 0 5px 0;">4</p>
					</td>
				</tr> -->
			</tbody>
		</table>

		<table cellpadding="0" cellspacing="0"  style="width: auto; table-layout: fixed; border: 1px solid #000; margin-top: 18px;">
			<tr>
				<td style="padding: 4px 8px" >						
					<p style="margin: 0 0 1px 0; text-align: center;">EXTERIOR CONDITION</p>
				</td>
			</tr>
			<tr>
				<td style="padding: 4px 8px" >
					<span style="min-width: 120px; display: inline-block;" >X: Damage/Repl</span>
					R: Rust							
				</td>
			</tr>
			<tr>
				<td style="padding: 4px 8px" >
					<span style="min-width: 120px; display: inline-block;">D: Dent</span>
					C: Chipping							
				</td>
			</tr>
			<tr>
				<td style="padding: 4px 8px" >
					<span style="min-width: 120px; display: inline-block;">S: Scratch</span>
					K: Crack
				</td>
			</tr>
			<tr>
				<td style="padding: 4px 8px" >
					<span style="min-width: 120px; display: inline-block;">P: Poor Previous </span>L: Light Mark/Scratch
				</td>
			</tr>
		</table>
		<div class="image" style="margin: 30px 0 0 0; height: 300px; display: flex;">
			<?php 
			if (isset($condition_img)) {?>
	 		<img src="<?php echo $condition_img ;?>" alt="">
			<?php }else{?>
				<h3><?php echo $condition_img_text;?></h3>
			<?php }?>
	 		<!-- <img src="<?//= base_url() ?>assets_user/images/live-img2.png" alt=""> -->
	 	</div>
		<table cellpadding="0" cellspacing="0" style="width: 100%; table-layout: fixed; margin-top: 18px;">
			<tbody>	
				<tr>
					<td>
						<p style=" font-size: 16px; font-weight: 700; padding: 15px; color: #000;min-height: 200px; border: 2px solid #000;" >
							<?php 
								if(isset($data) && !empty($data['comment'])){
									$comments = json_decode($data['comment']);
									echo $comments->english;
								}else{
									echo "N/A";
							}?>
						</p>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</body>
</html>