<!DOCTYPE html>
<html>
<head>
	<title>Email</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
</head>
<body style="padding: 60px 0 70px 0;">
	<div class="container" style="max-width: 1000px; margin: auto;">
		<div class="logo" style="margin: auto; text-align: center;">
			<img src="<?= ASSETS_USER; ?>new/images/Pioneer-logo.png" alt="" style="width: 200px; margin: auto;">
		</div>
		<table style="margin: 70px 0 0 0;">
			<tbody>
				<tr>
					<td style="font-family: 'Open Sans', sans-serif; font-size: 18px; font-weight: 200; margin-bottom: 0px; color: #000; display: block;" >Dear {username}</td>
				</tr>
				<tr>
					<td>
						<p style="font-family: 'Open Sans', sans-serif; font-size: 16px; font-weight: 600; color: gray; margin: 0 0 30px 0;">
							<!-- Congratulations! --> <?php if(isset($notification) && !empty($notification)){ echo $notification['description_english'];} ?>
						</p>
						<!-- <h3 style="font-family: 'Open Sans', sans-serif; font-size: 16px; font-weight: 400; margin-bottom: 8px; color: #000;">Name: 
							<span style="font-family: 'Open Sans', sans-serif; font-size: 16px; font-weight: 700; color: #000;">Mercedes banze</span>
						</h3>
						<h3 style="font-family: 'Open Sans', sans-serif; font-size: 16px; font-weight: 400; margin-bottom: 8px; color: #000;">Year: 
							<span style="font-family: 'Open Sans', sans-serif; font-size: 16px; font-weight: 700; color: #000;">2020</span>
						</h3>
						<h3 style="font-family: 'Open Sans', sans-serif; font-size: 16px; font-weight: 400; margin-bottom: 8px; color: #000;">Your heighest price: 
							<span style="font-family: 'Open Sans', sans-serif; font-size: 16px; font-weight: 700; color: #000;">21000 AED</span>
						</h3>
						<h3 style="font-family: 'Open Sans', sans-serif; font-size: 16px; font-weight: 400; margin-bottom: 8px; color: #000;">Lot Number: 
							<span style="font-family: 'Open Sans', sans-serif; font-size: 16px; font-weight: 700; color: #000;"># 98</span>
						</h3>
						<h3 style="font-family: 'Open Sans', sans-serif; font-size: 16px; font-weight: 400; margin-bottom: 8px; color: #000;">Registration Number: 
							<span style="font-family: 'Open Sans', sans-serif; font-size: 16px; font-weight: 700; color: #000;">2254254</span>
						</h3>
						<h3 style="font-family: 'Open Sans', sans-serif; font-size: 16px; font-weight: 400; margin: 0 0 40px 0; color: #000;">Status: 
							<span style="font-family: 'Open Sans', sans-serif; font-size: 16px; color: #000; font-weight: 400;">On Approval</span>
						</h3> -->
						<!-- <p style="font-family: 'Open Sans', sans-serif; font-size: 14px; font-weight: 600; color: gray; margin: 0 0 50px 0;"> -->
							<!-- Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley text of the printing and typesetting industry. Lorem Ipsum has been the industry's of type and scrambled it to make a type specimen book. It has survived not only five centuries, -->
						<!-- </p> -->
						<!-- <h2 style="font-family: 'Open Sans', sans-serif; font-size: 13px; color: #000; margin: 0 0 30px 0;">Please login: 
							<a href="#" style="font-family: 'Open Sans', sans-serif; font-size: 13px; font-weight: 700; color: #30d1ff; text-decoration: none;">Go to My Account</a>
						</h2>
						<h4 style="font-family: 'Open Sans', sans-serif; font-size: 14px; color: #000; margin:0 0 10px 0;">
							PIONEER AUCTION MANAGMENT
						</h4>
						<p style="font-family: 'Open Sans', sans-serif; font-size: 14px; color: #000; margin: 0 0 0 0;">
							800-PIONER
						</p> -->
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</body>
</html>