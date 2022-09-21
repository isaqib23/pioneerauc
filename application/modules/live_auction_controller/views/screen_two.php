<!DOCTYPE html>
<html>
<head>
	<title>Pioneer</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="<?php echo base_url('screen_assets/css/bootstrap4.min.css');?>">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="<?php echo base_url('screen_assets/css/style.css');?>">
	<!-- <link rel="stylesheet" href="<?php echo base_url('screen_assets/css/bootstrap-select.css');?>"> -->
	<link rel="stylesheet" href="<?php echo base_url('screen_assets/css/slick.css');?>">

	<script src="<?php echo base_url('screen_assets/js/jquery.min.js'); ?>"></script>
	<script src="<?php echo base_url('screen_assets/js/slick.js'); ?>"></script>
	<script src="<?php echo base_url('screen_assets/js/popper.min.js'); ?>"></script>
	<script src="<?php echo base_url('screen_assets/js/bootstrap4.min.js'); ?>"></script>
	<script src="<?php echo base_url('screen_assets/js/custom.js'); ?>"></script>
	<script src="<?= NEW_ASSETS_USER; ?>js/zee.js"></script> 
</head> 
<body class="detail-page">
	<main>
		<header class="front-screen">
			<div class="container">
				<div class="row">
					<div class="col-sm-4">
						<h1><?php echo date('d/m/Y');?></h1>
					</div>
					<div class="col-sm-4">
						<h1 class="live-text text-center">LIVE BID</h1>
					</div>
					<div class="col-sm-4">
						<!-- <h1 class="text-right">12:30:21 PM</h1> -->
						<h1 class="text-right"><div id="MyClockDisplay" class="clock" onload="showTime()"></div></h1>

					</div>
				</div>
			</div>
		</header>
		<div class="wrapper">
			<div class="row col-gap-24">
				<div class="col-sm-6">
					<div class="item">
						<h2>Lot<span id="lot_num">N/A</span></h2>
						<div class="image" id="full_pic">
							<img src="<?php echo base_url('screen_assets/images/logo.png');?>" alt="">
						</div>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="content-box" >
						<h3 id="name"></h3>
						<ul id="cat">
							<li><span>Spec</span><span id="specification" style="color: black">N/A</span></li>
							<li><span>Model</span><span id="model" style="color: black">N/A</span></li>
							<li><span>Trans</span><span id="trans" style="color: black">N/A</span></li>
							<li><span>Mileage</span><span id="mils" style="color: black">N/A</span><span id="mils_type" style="color: black"></span></li>
						</ul>
					</div>
					<div class="content-box waiting">
						<div class="button-row">
							<span>Waiting Bid</span><a href="#" class="btn btn-default"><span id="bid" style="color: white">0</span><i>AED</i></a>
						</div>
					</div>
				</div>
			</div>

			<div class="content-box desc" id ="detail">
				<p></p>
			</div>
		</div>
		<footer>
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
						<h3 class="approval-text" id="blink">AR - ON APPROmmmmVAL</h3>
					</div>
				</div>
			</div>
		</footer>
	</main>
</body>
</html>


<script src="https://js.pusher.com/6.0/pusher.min.js"></script>

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

	////////Pusher code///////
		Pusher.logToConsole = true;
		var pusher = new Pusher("<?= $this->config->item('pusher_app_key'); ?>", {
			cluster: 'ap1'
		});

		var channel = pusher.subscribe('ci_pusher');
		channel.bind('my-event', function(push) {
			var item_images = push.item_images;
			var data = push.data;
			var cb_amount = push.cb_amount;
			var blink_text = push.blink_text;
			var lot = push.lot_number;
			var item_fields_data = push.item_fields_data;
			// var bid_data = push.bid_data;
			var cat = data['category_id'];
			
			$('.slider .main').slick('refresh');
			$('.slider .thumb').slick('refresh');
			
			if (cat == 1) {
				$('#cat').show();
				var transaction = item_fields_data['value'];
			    var trans_cap = transaction.charAt(0).toUpperCase() + transaction.slice(1);
			}else{
				$('#cat').hide();
			}

			var img = '<div class="image"><img src="'+item_images[0]['path']+item_images[0]['name']+'" alt=""></div>';
			$('#full_pic').html(img);
			$('#detail').html(data['detail']);
			$('#lot_num').html(lot['order_lot_no']);
			$('#name').html(data['name']);
			$('#specification').html(data['specification']);
			$('#model').html(data['year']);
			$('#mils').html(data['mileage']);
			$('#mils_type').html(data['mileage_type']);
			// $('#bid').html(bid_data);
			$('#bid').html(numberWithCommas(cb_amount));
			$('#trans').html(trans_cap);
			$('#blink').html(blink_text);

		});
	</script>