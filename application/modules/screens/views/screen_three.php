
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
					<img src="<?= base_url('screen_assets/images/logo.png'); ?>" alt="">
				</div>
			</div>
			<div class="thumb" id="small_image">
				<div class="img">
					<img src="<?= base_url('screen_assets/images/logo.png'); ?>" alt="">
				</div>
			</div>
		</div>
	</main>
</body>
</html>

	<script src="https://js.pusher.com/6.0/pusher.min.js"></script>

	<script>

    var token_name = '<?= $this->security->get_csrf_token_name();?>';
    var token_value = '<?=$this->security->get_csrf_hash();?>';
    
	//Pusher.logToConsole = true;
	var pusher = new Pusher("<?= $this->config->item('pusher_app_key'); ?>", {
		cluster: 'ap1'
	});

	var channel = pusher.subscribe('ci_pusher');
	channel.bind('stop-event', function(push) {
		$('#full_image,#small_image').slick('slickRemove',null ,null, true);
		var img = '<div class="image"><img src="<?= base_url('screen_assets/images/logo.png'); ?>" alt=""></div>';
		$('#full_image,#small_image').slick('slickAdd',img);
    });

	channel.bind('live-event', function(push) {
		console.log('live evenr');
		console.log(push);
		var select_event = '';
		if (push.select_item) {
			select_event = push.select_item; 
		}
		console.log(select_event);
		brodcast_pusher(push.item_id, push.auction_id, select_event);
	});

	function brodcast_pusher(itemID, auctionid, select_event){
		var url = '<?= base_url('cronjob/broadcast_pusher/'); ?>'+itemID+'/'+auctionid;
        console.log(url)
        if (select_event && select_event === 'select_item') {
			$.ajax({
	            type: 'post',
	            url: url,
	            data: {[token_name] : token_value},
	            // data: {},
	            success: function(data){
	                var push = $.parseJSON(data)
	                console.log(push);
	                var item_images = push.item_images;
					$('#full_image,#small_image').slick('slickRemove',null ,null, true);
					$.each(item_images, function(index, value) {
						var img = '<div class="image"><img src="'+value.path+value.name+'" alt=""></div>';
						$('#full_image,#small_image').slick('slickAdd',img);
					});
	            }
	        });
        }
	}

	</script>