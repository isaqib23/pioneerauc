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
	<style type="text/css">
		.prpl{
			color: #800080 !important;
		}
		.ornge {
			color: #ffa500 !important;
		}
	</style>
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
				<div class="col-sm-7">
					<div class="item">
						<h2>Lot<span id="lot_num">N/A</span></h2>
						<div id="full_pic">
							<div class="image background-cover" style="background-image: url(<?php echo base_url('screen_assets/images/logo.png');?>);" >
								<img id="soldImg" src="<?php echo base_url('screen_assets/images/sold.png');?>" alt="Pioneer Auction Logo">
							</div>
						</div>
					</div>
				</div>
				<div class="col-sm-5">
					<div class="content-box" >
						<h3 id="name"> N/A </h3>
						<ul id="cat">
							<li><span>Spec: </span>&nbsp;<span id="specification" style="color: black">N/A</span></li>
							<li><span>Model: </span>&nbsp;<span id="model" style="color: black">N/A</span></li>
							<li><span>Mileage: </span>&nbsp;<span id="mils mils_type" style="color: black">N/A</span></li>
							<li id="trans"><span>Trans: </span>&nbsp;<span style="color: black">N/A</span></li>
						</ul>
					</div>
					<div class="content-box waiting">
						<div class="button-row">
				
							<span id="bidderType">Waiting Bid</span>
							<a href="#" id="bidderButton" class="btn btn-primary">
								<em id="bid" style="color: white; font-style: normal;">0</em><i>AED</i>
							</a>
						</div>
					</div>
				</div>
			</div>

			<div class="content-box desc detail-text" >
				<p id ="detail"></p>
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
						<h3 class="approval-text blinking" id="blink">AR - ON APPROVAL</h3>
					</div>
				</div>
			</div>
		</footer>
	</main>
</body>
</html>


<script src="https://js.pusher.com/6.0/pusher.min.js"></script>

<script>


    var token_name = '<?= $this->security->get_csrf_token_name();?>';
    var token_value = '<?=$this->security->get_csrf_hash();?>';
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


	function brodcast_pusher(itemID, auctionid){
		var url = '<?= base_url('cronjob/broadcast_pusher/'); ?>'+itemID+'/'+auctionid;
            console.log(url)
		$.ajax({
            type: 'post',
            url: url,
            data: {[token_name] : token_value},
            // data: {},
            success: function(data){
                var push = $.parseJSON(data)
                console.log(push);

                var item_images = push.item_images;
				var data = push.data;
				var cb_amount = push.cb_amount;
				var blink_text = push.blink_text;
				// alert(cb_amount);
				var lot = push.lot_number;
				var item_fields_data = push.item_fields_data;
				// console.log(item_fields_data[0].fieldName);
				// var bid_data = push.bid_data;
				var cat = data['category_id'];
				var itm_name = $.parseJSON(data['item_name']);
				var itm_detail = $.parseJSON(data['item_detail']);
				var fields_data = '';
				
				$('.slider .main').slick('refresh');
				$('.slider .thumb').slick('refresh');
				
				if (data['vehicle'] == true) {
					$('#cat').show();
					$('#cat').html('<li><span>Spec: </span>&nbsp;<span id="specification" style="color: black">N/A</span></li>\
								<li><span>Model: </span>&nbsp;<span id="model" style="color: black">N/A</span></li>\
								<li><span>Mileage: </span>&nbsp;<span id="mils" style="color: black">N/A</span><span id="mils_type" style="color: black"></span></li>\
								<li id="trans"><span>Trans: </span>&nbsp;<span style="color: black">N/A</span></li>');
					// var transaction = item_fields_data['value'];
				 //    var trans_cap = transaction.charAt(0).toUpperCase() + transaction.slice(1);
				    if (data['specification']) {
					$('#specification').html(data['specification']);
					} else {
						$('#specification').html('N/A');
					}

					/*if (data['model']) {
						var model = data['model']['title'];
						$('#model').html(model.english);
					} else {
						$('#model').html('N/A');
					}*/

					if (push.item_model) {
						$('#model').html(push.item_model);
					} else {
						$('#model').html('N/A');
					}

					$('#mils').html(data['mileage']);
					$('#mils_type').html(data['mileage_type']);
					if (item_fields_data[0]) {
						$('#trans').html('<span>'+item_fields_data[0].fieldName+': </span>&nbsp;<span id="specification" style="color: black">'+item_fields_data[0].fieldValue+'</span>');

					}
				}else{
					// $('#cat').hide();
					$.each(item_fields_data, function(index, value) {
						fields_data = fields_data+'<li><span>'+value.fieldName+': </span>&nbsp;<span id="specification" style="color: black">'+value.fieldValue+'</span></li>';
					});
					$('#cat').html(fields_data);
				}
				
				if (item_images[0] && item_images[0]['name']) {
					var img = '<div class="image background-cover" style="background-image: url('+item_images[0]['path']+item_images[0]['name']+')" ><img id="soldImg" src="<?php echo base_url('screen_assets/images/sold.png');?>" alt=""></div>';
				} else {
					var img = '<div class="image background-cover" style="background-image: url(<?php echo base_url('screen_assets/images/logo.jpg');?>);" > <img id="soldImg" src="<?php echo base_url('screen_assets/images/sold.png');?>" alt="Pioneer Auction Logo"> </div>';
				}
				if (item_images[0] && item_images[0]['name']) {
					
				$('#full_pic').html(img);
				$('#detail').html(itm_detail.english);
				//$('#lot_num').html(lot['order_lot_no']);
				$('#name').html(itm_name.english);
				// $('#name').html(data['name']);
				// $('#bid').html(bid_data);
				//$('#bid').html(numberWithCommas(cb_amount));
				// $('#trans').html(trans_cap);
				$('#blink').html(blink_text);
				}

				//update bid amount if available
		        if(push.cb_amount){
		            $("#bid").html(numberWithCommas(push.cb_amount));
		        }else{
			        $("#bidderType").html('Waiting Bid');
		            $("#bid").html('0');
		        }

		        //update lot number if available
		        if(push.lot_number){
		            $("#lot_num").html(push.lot_number);
		        }else{
		            $("#lot_num").html('0');
		        }

		        //update bidder type Hall or On line if available
		        if(push.current_bid){
		        	$("#bidderType").removeClass('prpl');
		        	$("#bidderType").removeClass('ornge');
		        	if (push.current_bid.bid_type == 'hall' || push.current_bid.bid_type == 'initial') {
		            	$("#bidderType").addClass('prpl');
		            }

		            if (push.current_bid.bid_type == 'online') {
		            	$("#bidderType").addClass('ornge');
		            }
		        	
			            var bidderTypePhrase = 'With '+push.current_bid.bid_type.toProperCase()+' Bidder';
			            $("#bidderType").html(bidderTypePhrase);
		            
		            /// change button color
		            if (push.current_bid.bid_type == 'hall' || push.current_bid.bid_type == 'initial') {
		            	$("#bidderButton").removeClass('btn-default').addClass('btn-primary');
		            	$("#bidderType").addClass('prpl');
		            }

		            if (push.current_bid.bid_type == 'online') {
		            	$("#bidderButton").removeClass('btn-primary').addClass('btn-default');
		            	$("#bidderType").addClass('ornge');
		            }
		            
		        }
            }
        });
	}


	////////Pusher code///////
	Pusher.logToConsole = true;
	var pusher = new Pusher("<?= $this->config->item('pusher_app_key'); ?>", {
		cluster: 'ap1'
	});

	var channel = pusher.subscribe('ci_pusher');

	channel.bind('stop-event', function(push) {
		console.log("stop-event run");
		var img = '<div class="image background-cover" style="background-image: url(<?= base_url('screen_assets/images/logo.png'); ?>)" ><img id="soldImg" src="<?php echo base_url('screen_assets/images/sold.png');?>" alt=""></div>';
		$('#full_pic').html(img);
		$('#cat').html('<li><span>Spec: </span>&nbsp;<span id="specification" style="color: black">N/A</span></li>\
			<li><span>Model: </span>&nbsp;<span id="model" style="color: black">N/A</span></li>\
			<li><span>Mileage: </span>&nbsp;<span id="mils" style="color: black">N/A</span><span id="mils_type" style="color: black"></span></li>\
			<li id="trans"><span>Trans: </span>&nbsp;<span style="color: black">N/A</span></li>');
		$('#detail').html('');
		$("#bidderButton").removeClass('btn-default').addClass('btn-primary');
		$("#bid").html('0');
		$("#lot_num").html('N/A');
		$('#name').html('N/A');
		$('#blink').html('ON APPROVAL');
    });
	channel.bind('sold-event', function(push) {
		//console.log("display:  block");
		console.log("sold-event run");
        $('#soldImg').show();
    });
	channel.bind('live-event', function(push) {
		console.log("push", push);
		console.log("live-event run");
        $('#soldImg').hide();
		brodcast_pusher(push.item_id, push.auction_id);

	});
</script>