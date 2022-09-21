<!-- <main class="wrapper">
		<div class="main-image">
			<img src="<?php echo base_url('screen_assets/images/screen-bg.png');?>" alt="">
		</div>
	</main> -->
<!DOCTYPE html>
<html>
<head>
	<title>Pioneer</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="<?php echo base_url('screen_assets/css/bootstrap4.min.css');?>">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="<?php echo base_url('screen_assets/css/style.css');?>">
	<!-- <link rel="stylesheet" href="<?php echo base_url('screen_assets/css/bootstrap-select.css');?>"> -->

	<script src="<?php echo base_url('screen_assets/js/jquery.min.js'); ?>"></script>
	<script src="<?php echo base_url('screen_assets/js/popper.min.js'); ?>"></script>
	<script src="<?php echo base_url('screen_assets/js/slick.js'); ?>"></script>
	<script src="<?php echo base_url('screen_assets/js/bootstrap4.min.js'); ?>"></script>
	<script src="<?php echo base_url('screen_assets/js/custom.js'); ?>"></script>

</head> 
<body>
	<header class="front-screen" style="background-color:white">
		<div class="container">
			<div class="row">
				<div class="col-sm-4">
					<h1 style="color: black"><?php echo date('d/m/Y');?></h1>
				</div>
				<div class="col-sm-4">
					<h1 class="live-text text-center" style="color: #d4232b">LIVE BID</h1>
				</div>
				<div class="col-sm-4">
					<!-- <h1 class="text-right" style="color: black">12:30:21 PM</h1> -->
					<h1 class="text-right"><div id="MyClockDisplay" class="clock" onload="showTime()" style="color: black"></div></h1>
				</div>
			</div>
		</div>
	</header>
	<main class="wrapper">
		<div class="main-image">
			<img src="<?php echo base_url('screen_assets/images/screen-bg.png');?>" alt="">
		</div>
	</main>
	<footer style="background-color:white">
		<div class="container">
			<div class="row">
				<div class="col-sm-6">
					<div class="logo">
						<a href="#">
							<img src="<?php echo base_url('screen_assets/images/logo.png');?>" alt="">
						</a>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="logo text-right">
						<a href="#">
							<img src="<?php echo base_url('screen_assets/images/logo.png');?>" alt="">
						</a>
					</div>
				</div>
			</div>
		</div>
	</footer>
</body>
</html>
<script>
	//////Timer script /////
		function showTime(){
			var date = new Date();
			var h = date.getHours(); // 0 - 23
			var m = date.getMinutes(); // 0 - 59
			var s = date.getSeconds(); // 0 - 59
			var session = "AM";

			if(h == 0){
				h = 12;
			}

			if(h > 12){
				h = h - 12;
				session = "PM";
			}
			h = (h < 10) ? "0" + h : h;
			m = (m < 10) ? "0" + m : m;
			s = (s < 10) ? "0" + s : s;
			var time = h + ":" + m + ":" + s + " " + session;
			document.getElementById("MyClockDisplay").innerText = time;
			document.getElementById("MyClockDisplay").textContent = time;
			setTimeout(showTime, 1000);
		}
		showTime();
	//////End Timer script /////
</script>