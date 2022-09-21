
<!DOCTYPE html>
<html>
<head>
	<title>Pioneer</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="<?php echo base_url('screen_assets/css/bootstrap4.min.css');?>">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="<?php echo base_url('screen_assets/css/style.css');?>">
	<link rel="stylesheet" href="<?php echo base_url('screen_assets/css/slick.css');?>">
	<!-- <link rel="stylesheet" href="<?php //echo base_url('screen_assets/css/bootstrap-select.css');?>"> -->

	<script src="<?php echo base_url('screen_assets/js/jquery.min.js'); ?>"></script>
	<script src="<?php echo base_url('screen_assets/js/popper.min.js'); ?>"></script>
	<script src="<?php echo base_url('screen_assets/js/bootstrap4.min.js'); ?>"></script>
	<script src="<?php echo base_url('screen_assets/js/slick.js'); ?>"></script>
	<script src="<?php echo base_url('screen_assets/js/custom.js'); ?>"></script>


</head> 
<body>
	
	<main>
  
		<div class="slider">
			<div class="main" id="full_image">
				<div class="image">
					<img src="<?php echo base_url('screen_assets/images/pro-image1.png');?>" alt="">
				</div>
			</div>
			<div class="thumb" id="small_image">
				<div class="img">
					<img src="<?php echo base_url('screen_assets/images/pro-image1.png');?>" alt="">
				</div>
				<div class="image">
					<img src="<?php echo base_url('screen_assets/images/pro-image1.png');?>" alt="">
				</div>
			</div>
		</div>
	</main>
</body>
</html>

	<script src="https://js.pusher.com/6.0/pusher.min.js"></script>

	<script>
	Pusher.logToConsole = true;
	var pusher = new Pusher("<?= $this->config->item('pusher_app_key'); ?>", {
		cluster: 'ap1'
	});

	var channel = pusher.subscribe('ci_pusher');
	
	channel.bind('my-event', function(push) {
		var item_images = push.item_images;
		$('#full_image,#small_image').slick('slickRemove',null ,null, true);
		$.each(item_images, function(index, value) {
			var img = '<div class="image"><img src="'+value.path+value.name+'" alt=""></div>';
			$('#full_image,#small_image').slick('slickAdd',img);
		});

	});

	</script>