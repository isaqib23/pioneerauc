<style>
#hideText{
	color: green;
font-size: x-large;
text-align: center;
border: 4px solid green;
width: 272px;
align-content: center;
text-align: center;
margin-left: 104px;
text-shadow: 0 0 1px #20b347;
}
</style>
<style type="text/css">
	.auto-biding .btn.btn-default {width: auto; max-width: 200px;}
	.auto-biding ul .btn {width: 70%; max-width: 100px; }
	.auto-biding ul {justify-content: center;}
	.auto-biding li {width: 60px !important; padding: 0 !important;}
	.auto-biding li:not(:last-child){margin-right: 12px !important;}
</style>

<script type="text/javascript">
	$(document).ready(function(){
		$("#btn-toggle").on("click", function(){
			$(".auto-biding > ul").slideToggle();
		});
	});
</script>

<!-- PNotify -->
    <link href="<?php echo base_url(); ?>assets_admin/vendors/pnotify/dist/pnotify.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets_admin/vendors/pnotify/dist/pnotify.buttons.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets_admin/vendors/pnotify/dist/pnotify.nonblock.css" rel="stylesheet">

<?php
	// $bid_end_time = strtotime($item['bid_end_time']);
	$item_images_ids = explode(',', $item['item_images']);
	$images = $this->db->where_in('id', $item_images_ids)->get('files')->result_array();
	if($images){
		$image_src = base_url('uploads/items_documents/').$item['id'].'/'.$images[0]['name'];
	}else{
		$image_src = '';
	}

	$favorite = 'fa-heart-o';
	$user = $this->session->userdata('logged_in');
	$favt = $this->db->get_where('favorites_items', ['item_id' => $item['id'],'user_id' => $user->id])->row_array();
	if($favt){
		$favorite = 'fa-heart';
	}else{
		$favorite = 'fa-heart-o';
	}

?>

<main class="page-wrapper proxy">
	<div class="auctions">
		<div class="container">
			<div class="row stack-on-1200">
				<div class="col-sm-7">
					<div class="slider-wapper">
						<div class="row">

							<?php if($this->session->flashdata('success')){ ?>
					            <div class="alert alert-success alert-dismissible">
					              <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
					              <strong>Success!</strong> <?= $this->session->flashdata('success'); ?>
					            </div>
					        <?php } ?>
					        
							<div class="col-sm-12">
								<div class="slider-for">
									<?php foreach ($images as $key => $value) { ?>
										<div class="image">
											<img src="<?= base_url('uploads/items_documents/').$item['id'].'/'.$value['name']; ?>" style="width: 752px; height: 392px;" >
											<!-- <img src="<?= ASSETS_IMAGES; ?>/gallery-detail.png"> -->
											<div class="img-auctions">
												<a href="javascript:void(0)" id="doFavt" onclick="doFavt(this)" data-item="<?= $item['id']; ?>">
													<!-- <img src="<?= ASSETS_IMAGES; ?>/heart-white.png" alt=""> -->
													<i class="fa <?= $favorite; ?>"></i>
												</a>
												<a href="#">
													<img src="<?= ASSETS_IMAGES; ?>/share-white.svg" alt="">
												</a>
											</div>
										</div>
									<?php } ?>
								</div>
							</div>
							<div class="col-sm-12">
								<div class="slider-nav">
									<?php foreach ($images as $key => $value) { ?>
										<div class="image">
											<img src="<?= base_url('uploads/items_documents/').$item['id'].'/'.$value['name']; ?>">
										</div>
									<?php } ?>
									
								</div>
							</div>	
						</div>
					</div>
					<div class="tabs-warpper">
						<ul class="nav nav-tabs" id="myTab" role="tablist">
						 	<li class="nav-item">
								<a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">DETAILS</a>
						  	</li>
						  	<?php if (! empty($item['item_model'])) { 
						  		$model = $this->db->get_where('item_models', ['id' => $item['item_model']])->row_array(); 
						  		$make = $this->db->get_where('item_makes', ['id' => $item['item_make']])->row_array(); 
						  		?>
						  	<li class="nav-item">
						    	<a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">MODEL</a>
						  	</li>
						  	<?php } ?>
						  	<li class="nav-item">
						    	<a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">LOCATION</a>
						  	</li>
						</ul>
						<div class="tab-content" id="myTabContent">
							<div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
								<div class="row">
									<div class="col-lg-6">
										<?php $i=0; 
										foreach ($fields as $key => $value) {
										if (!empty($value['data-value'])) {
										$i++;
										if($i<= 5) { ?>
											<ul>
												<li><?= $value['label']; ?>:</li>
												<li><?= $value['data-value']; ?></li>
											</ul>
										<?php } } } ?>
									</div>
									<div class="col-lg-6">
										<?php $i=0; 
										foreach ($fields as $key => $value) {
										if (!empty($value['data-value'])) {
										$i++;
										if($i> 5 && $i<= 10) { ?>
											<ul>
												<li><?= $value['label']; ?>:</li>
												<li><?= $value['data-value']; ?></li>
											</ul>
										<?php } } } ?>
									</div>
								</div>
								<div class="text">
									<p>
										<?= $item['item_detail']; ?>
										<!-- Lorem ipsum dolor sit amet, consectetur adipiscing elit. In laoreet ornare mi ac eleifend. Sed vestibulum purus leo, ut blandit nunc tincidunt vitae. Mauris mattis et nunc at euismod. Morbi laoreet tellus odio, vitae elementum nisl tristique interdum.  -->
									</p>
								</div>
							</div>
						  	<div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
						  		<div class="row">
									<div class="col-lg-6">
										<ul>
											<li>Make:</li>
											<li><?= $make['title']; ?></li>
										</ul>
										<ul>
											<li>Model:</li>
											<li><?= $model['title']; ?></li>
										</ul>
									</div>
									<div class="col-lg-6">
	
									</div>
								</div>
						  	</div>
						  	<div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
						  		<div class="row">
									<div id="map" style="width: 100%; height: 300px;"></div> 
									<script src="<?php echo base_url(); ?>assets_admin/js/jquery.geocomplete.js"></script>
									<div class="col-lg-12">
										<ul>
											<li>Location:</li>
											<li><?= $item['item_location']; ?></li>
										</ul>
									</div>
								</div>
						  	</div>
						</div>
					</div>
				</div>
				<div class="col-sm-5">
					<div class="card">
						<h2><?= $item['item_name']; ?></h2>
						<h3><?= $item['item_detail']; ?></h3>
						<ul class="items">
						 	<li id="eye"><img src="<?= ASSETS_IMAGES; ?>/timed-eye.png"><?= $visit_count; ?></li>
						 	<?php if (isset($fields[0]['data-value'])) {  ?>
						 		<li><img src="<?= ASSETS_IMAGES; ?>/timed-calndr.png"><?= $fields[0]['data-value']; ?>	</li>
							<?php } ?>
						 	<?php if (isset($fields[1]['data-value'])) {  ?>
						 		<li><img src="<?= ASSETS_IMAGES; ?>/timed-clock.png"><?= number_format($fields[1]['data-value']); ?></li>
							<?php } ?>
						</ul>
						<ul class="current-bid">
						 	<li><h4>Current Bid</h4></li>
						 	<li class="current-btn"><p id="CBP" class="<?= $item['item_id']; ?>"><?= (isset($heighest_bid_data) && !empty($heighest_bid_data['current_bid'])) ? $heighest_bid_data['current_bid'] : $item['bid_start_price']; ?>	</p><span>AED</span>
						 	</li>
						</ul>
						<ul class="current-bid">
						 	<li>
						 		<p style=" font-weight: bold; margin-top: 20px;">
						 		<span style="color: red;" id="bid_user_name<?= $item['item_id']; ?>">
						 			<?php 
						 			if (isset($heighest_bid_data) && !empty($heighest_bid_data)) {
							 			if($heighest_bid_data['user_id'] == $user_id) {
							 				echo 'You are the highest bidder.'; 
							 			}else{
							 				echo 'You are not the highest bidder.'; 	
							 			} 
							 		}
						 			?>
						 		</span>
						 		</p>
						 	</li>
						</ul>
					</div>


					<div class="proxy-form">
						<h2 class="form-title">BID INFORMATION</h2>
						<div class="proxy-bid">
							<ul class="first">
								<li>Bids</li>
								<li id="no_of_bids<?= $item['item_id']; ?>"><?= (!empty($bid_count)) ? $bid_count : '0'; ?></li>
							</ul>
							<!-- <ul class="nth">
								<li>Watch</li>
								<li class="red-text">Add to watch list </li>
								<li class="timer-icon gray" ><img src="<?= ASSETS_IMAGES; ?>/timer-icon.png">&nbsp;(4 users watching)</li>
							</ul> -->
						</div>

						<?php 
						$deposit_by_user = false;
						if($item['security'] == 'yes'){
							$user = $this->session->userdata('logged_in');
							$user_deposit = $this->db->get_where('auction_item_deposits', [
								'user_id' => $user->id,
								'item_id' => $item['item_id'],
								'auction_id' => $item['auction_id'],
								'auction_item_id' => $item['auction_item_id']
							]);
							//echo $this->db->last_query();
							if($user_deposit->num_rows() > 0){
								$user_deposit = $user_deposit->row_array();
								if($user_deposit['deposit'] >= $item['deposit']){
									$deposit_by_user = true;
								}else{
									$deposit_by_user = false;
								}
							}else{
								$deposit_by_user = false;
							}
						}else{
							$deposit_by_user = true;
						}
						?>

						<?php if($deposit_by_user == true): ?>
							<div id="hideText" class="hideText<?= $item['item_id']; ?>"></div>
							<div class="your-bid bid-div<?= $item['item_id']; ?>" id="hidebid_section">
								<h2 class="form-title">YOUR BID <span class="toltip"> * On Approval</span></h2>
									<ul>
										<span style="font-size: 18px; font-family: 'Gotham-medium', 'Frutiger-regular'; " id="your-bid-amount<?= $item['item_id']; ?>"><?= (isset($heighest_bid_data) && !empty($heighest_bid_data['current_bid'])) ? $heighest_bid_data['current_bid'] : $item['bid_start_price']; ?> + &nbsp</span>
										<input type="hidden" name="current_price" id="current_price<?= $item['item_id']; ?>" value="<?= (isset($heighest_bid_data) && !empty($heighest_bid_data['current_bid'])) ? $heighest_bid_data['current_bid'] : $item['bid_start_price']; ?>">

										<li>
											<input type="number" min="0" placeholder="Enter here" class="form-control" id="bid_amount" name="bid_amount">
											<p>You are the lower bidder, please higher</p>
										</li>
										<li>
											<button disabled="" 
												onclick="placebid(this)" 
												data-auction-id="<?= $item['auction_id']; ?>" 
												data-item-id="<?= $item['item_id']; ?>" 
												data-seller-id="<?= $item['item_seller_id']; ?>" 
												data-min-bid="<?= $item['minimum_bid_price']; ?>" 
												data-lot-no="<?= $item['lot_no']; ?>" 
												data-start-price="<?= $item['bid_start_price']; ?>" 
												data-max-bid="<?= (float)$balance*10; ?>" 
												class="btn btn-primary" id="bid_btn">PLACE BID</button>
										</li>
									</ul>
								<h2 class="form-title"><span class="toltip">Minimum bid price <?= $item['minimum_bid_price'] ?></span></h2>
								
								<div class="bid-update">
									<ul class="five-items">
										<li>
											<button class="btn btn-primary btn-counter bidder-button" id="myBtn" onclick="bidButtons(this)" value="100">AED 100</button>
										</li>
										<li>
											<button class="btn btn-primary btn-counter bidder-button" id="myBtn" onclick="bidButtons(this)" value="500">AED 500</button>
										</li>
										<li>
											<button class="btn btn-primary btn-counter bidder-button" id="myBtn" onclick="bidButtons(this)" value="1000">AED 1000</button>
										</li>
										<li>
											<button class="btn btn-primary btn-counter bidder-button" id="myBtn" onclick="bidButtons(this)" value="2000">AED 2000</button>
										</li>
										<li>
											<button class="btn btn-primary btn-counter bidder-button" id="myBtn" onclick="bidButtons(this)" value="5000">AED 5000</button>
										</li>
									</ul>
								</div>
								

								<div class="auto-biding hideauto" id="autohide">
									<div class="button-row">
										<button class="btn btn-default" id="btn-toggle" style="color: #e11f26">Auto Biding</button>
									</div>
									<ul>
										<li>
											<input type="number" class="form-control" placeholder="Your Limit" id="auto_limit"  name="auto_limit" style="width: 130px;">
										</li>
										<li>
											<input type="hidden" class="form-control" placeholder="Increment" id="auto_increment" name="auto_increment">
											
										</li>
										<li>
											<button class="btn btn-default"
											 id="start_bid"
											 onclick="autoBiding(this)"
											 >Start</button>
										</li>
									</ul>

								</div>
							</div>
							<?php $biding_limit = $balance*10;
							?>
						<?php endif; ?>
						<?php if($item['security'] == 'yes' && !empty($item['deposit']) && $deposit_by_user == false): 
							?>
							<div class="report">
								<h2 class="form-title">DEPOSIT</h2>
								<ul>
									<li>
										<p class="toltip">This item required extra deposit of amount <?= $item['deposit']; ?></p>
									</li>
									<li>
										<a href="<?= base_url('customer/item_deposit?item_id=').$item['auction_item_id'].'&auction_id='.$item['auction_id'].'&rurl='.$rurl; ?>" class="file-link" >Deposit Now</a>
									</li>
								</ul>
							</div>
						<?php endif; ?>

						<div class="report">
							<h2 class="form-title">REPORTS</h2>
							<?php $item_test_report = $this->db->get_where('files', ['id' => $item['item_test_report']])->row_array(); ?>
							<ul>
								<li>
									<a href="<?= base_url().$item_test_report['path'].$item_test_report['name']; ?>" target="_blank" class="file-link"  >Condition Report</a>
								</li>
								<li>
									<a href="<?= base_url().$item_test_report['path'].$item_test_report['name']; ?>" download class="file-link" >TEST Report</a>
								</li>
							</ul>
						</div>	
						<div class="form-footer">
							<p>If you require further assistance, please contact us</p>
							<ul>
								<li>
									<a href="#" class="file-link" >ENQUIRE</a>
								</li>
								<li class="bold-text">
									<img src="<?= ASSETS_IMAGES; ?>/telephone-icon.png">&nbsp;<!-- 800 12045 --><?= $contact['phone']; ?>
								</li>
							</ul>
						</div>
					</div>


					<div class="post-link">
						<a href="<?= base_url('customer/terms'); ?>">Sales Information, Fees & Terms <i class="fa fa-angle-right"></i></a>
					</div>
				</div>
			</div>
			<div class="upcoming-auction">
				<h1>RELATED PRODUCTS</h1>
				<div class="row">
					<?php if (!empty($related_items)) {
					foreach ($related_items as $key => $related_item) {
						$bid_info = $this->db->where('item_id',$related_item['item_id'])->where('auction_id',$related_item['auction_id'])->order_by('id', 'desc')->from('bid')->get()->row_array();
						$visit_count = $this->db->where('item_id',$related_item['item_id'])->where('auction_id',$related_item['auction_id'])->from('online_auction_item_visits')->count_all_results();
						$item_images_ids = explode(',', $related_item['item_images']);
						$images = $this->db->where_in('id', $item_images_ids)->get('files')->row_array();
					  ?>
						<div class="col-3">
							<a href="<?= base_url('auction/online-auction/details/').$related_item['auction_id'].'/'.$related_item['item_id']; ?>">
								<div class="box">
									<div class="image">
										<img src="<?= base_url('uploads/items_documents/').$related_item['item_id'].'/'.$images['name']; ?>" width="304px" height="180px">
									</div>
									<div class="desc">
										<h4><?= $related_item['item_name']; ?></h4>
										<h3 class="red-text">AED <?= (!empty($bid_info)) ? $bid_info['bid_amount'] : $related_item['bid_start_price']; ?></h3>
										<ul>
												<li>
													<img src="<?= ASSETS_IMAGES; ?>/timed-calndr.png"> 
												</li>
											<li>
												<img src="<?= ASSETS_IMAGES; ?>/timed-clock.png"> 266,154
											</li>
											<li>
												<img src="<?= ASSETS_IMAGES; ?>/timed-eye.png"> <?= (!empty($visit_count)) ? $visit_count : '0'; ?>
											</li>
										</ul>
									</div>
								</div>
							</a>
						</div>
					<?php } } ?>
					
				</div>
			</div>
		</div>	
	</div>			
</main>
 <!-- Notify JS Files -->
<script src="<?php echo base_url(); ?>assets_admin/vendors/pnotify/dist/pnotify.js"></script>
<script src="<?php echo base_url(); ?>assets_admin/vendors/pnotify/dist/pnotify.buttons.js"></script>
<script src="<?php echo base_url(); ?>assets_admin/vendors/pnotify/dist/pnotify.nonblock.js"></script>


<script src="https://js.pusher.com/6.0/pusher.min.js"></script>

<script>  
	$(document).ready(function(){
		$('#eye').tooltip({
			 title:'users visit this item!',
			 placement:'bottom'
		});
		// $('[data-toggle="tooltip"]').tooltip({
		// 	 title:'Hooray!'
		// });

		
		$('.fa-heart-o').tooltip({
			 title:'Add to favorite!',
			 placement:'bottom'
		});

		$('.fa-heart').tooltip({
			 title:'Remove from favorite!',
			 placement:'bottom'
		});
	});
	
	

	// function doFavt(e){
	// 	//alert('dona done');
	// 	var heart = $(e);
	// 	var itemID = $(e).data('item');
	// 	$.ajax({
	//       	type: 'post',
	//       	url: '<?= base_url('auction/OnlineAuction/do_favt'); ?>',
	//       	data: {'item_id':itemID},
	//       	success: function(msg){
	//         	console.log(msg);
	//         	//var response = JSON.parse(msg);
	//         	if(msg == 'do_heart'){
	//         		heart.find('.fa-heart-o').addClass('fa-heart').removeClass('fa-heart-o');	
	// 				$('.fa-heart').tooltip({
	// 					title:'Remove from favorite!',
	// 					placement:'bottom'
	// 				});		
	//         	}
	//         	if(msg == 'remove_heart'){
	//         		heart.find('.fa-heart').addClass('fa-heart-o').removeClass('fa-heart');
	//         		$('.fa-heart-o').tooltip({
	// 					title:'Add to favorite!',
	// 					placement:'bottom'
	// 				});
	//         	}
	//         	if(msg == '0'){
	//         		window.location.replace('<?= base_url("home/login?rurl="); ?>'+encodeURIComponent(window.location.href));
	//         	}
	//     	}
	// 	});   
	// }


function placebid(e){
	//alert('dona done');
	var lotno = $(e).data('lot-no');
	var heart = $(e);
	var auctionid = $(e).data('auction-id');
	var itemID = $(e).data('item-id');
	var minimum_bid_price = $(e).data('min-bid');
	var sellerid = $(e).data('seller-id');
	var start_price = $(e).data('start-price');
	var maximum_bid_amount = $(e).data('max-bid');
	var bid_amount = $('#bid_amount').val();
	var current_price = $('#current_price<?= $item['item_id']; ?>').val();
	console.log('your bid'+bid_amount);
	console.log('current price'+current_price);
	var your_bid_amount = Number(bid_amount) + Number(current_price);

	
	swal({
		title: "Are you sure to place a bid?",
		text: "Your bid amount amount will be " +(Number(bid_amount)+ Number(current_price)),
		type: "info",
		showCancelButton: true,
		cancelButtonClass: 'btn-default btn-md waves-effect',
		confirmButtonClass: 'btn-danger btn-md waves-effect waves-light',
		confirmButtonText: 'Confirm!'
	},function(isConfirm) {
		if (isConfirm) {
			//console.log('itemID :'+itemID+' auctionid :'+auctionid+' lotno :'+lotno+' sellerid :'+sellerid+' minimum_bid_price :'+minimum_bid_price+' bid_amount :'+bid_amount+' maximum_bid_amount :'+maximum_bid_amount);	
			if (bid_amount < minimum_bid_price) {
				$('.proxy-form .your-bid li p').fadeTo(0, 500).css('display','block !important');
				window.setTimeout(function() {
		            $(".proxy-form .your-bid li p").fadeTo(500, 0).css('display','none');
		        }, 3000);
			}else{

				if (your_bid_amount > maximum_bid_amount) {
					new PNotify({
		                type: 'error',
						addclass: 'custom-error',
		                text: "Insufficient Balance! Please Recharge.",
		                styling: 'bootstrap3',
		                title: 'Error'
		            });
				}else{
					$.ajax({
				      	type: 'post',
				      	url: '<?= base_url('auction/OnlineAuction/placebid'); ?>',
				      	data: {'item_id':itemID,'auction_id':auctionid,'seller_id':sellerid,'bid_amount':bid_amount,'start_price':start_price},
				      	success: function(data){
				        	console.log(data);
				        	var response = JSON.parse(data);
				        	if(response.status == 'success'){
				        		
				                $('.current-btn p').html(response.current_bid);
				                $('#bid_amount').val('');
				        	}


				        	if(response.status == 'fail'){
				        		new PNotify({
				                    text: ""+response.msg+"",
				                    type: 'error',
									addclass: 'custom-error',
				                    title: 'Error',
				                    styling: 'bootstrap3'
				                });
				        	}

				        	if(data == '0'){
				        		window.location.replace('<?= base_url("home/login"); ?>');
				        	}
				        	if(response.status =='soldout'){
				        		new PNotify({
				                    text: ""+response.msg+"",
				                    type: 'error',
									addclass: 'custom-error',
				                    title: 'Error',
				                    styling: 'bootstrap3'
				                });
				                $('.your-bid').hide();
				        	}
				  //    //


				    	}
					});
				}
			}
			window.setTimeout(function() 
		    {
		        $(".alert").fadeTo(500, 0).slideUp(500, function()
		        {
		            $(this).remove(); 
		        });
		    }, 5000);
		}
	});
}
	

	//Enable pusher logging - don't include this in production
	Pusher.logToConsole = true;

	var pusher = new Pusher("<?= $this->config->item('pusher_app_key'); ?>", {
	  cluster: 'ap1'
	});
	var user_id = "<?= isset($user) ? $user->id : ''; ?>";

	var channel = pusher.subscribe('ci_pusher');
	channel.bind('my-event', function(push) {
	  // alert(JSON.stringify(push));
		if (push.status) {
			if (user_id == push.user_id) {
				$('.bid-div'+push.item_id).show();
				$('.hideText'+push.item_id).hide();
			}
		} else {
			var no_of_bids = parseInt($('#no_of_bids'+push.item_id).text());
			var no_of_bids = no_of_bids + 1;
			$('#no_of_bids'+push.item_id).html(no_of_bids);
			$('.'+push.item_id).html(push.bid_amount);
			$('#your-bid-amount'+push.item_id).html(push.bid_amount+' + ');
			$('#current_price'+push.item_id).val(push.bid_amount);
			if (user_id == push.user_id) {
				$('#bid_user_name'+push.item_id).html('You are the heighest bidder.');
				new PNotify({
	                text: "Bid successfully.",
	                type: 'success',
					addclass: 'custom-success',
	                title: 'Success',
	                styling: 'bootstrap3'
	            });
			} else {
				$('#bid_user_name'+push.item_id).html('You are not the heighest bidder.');
			}
		}
	});


	function initialize() {
		var lat = "<?php echo $item['item_lat']; ?>";
		var lng = "<?php echo $item['item_lng']; ?>";
	    var latlng = new google.maps.LatLng(lat,lng);
		var address = "<?php echo $item['item_location']; ?>";
	    var map = new google.maps.Map(document.getElementById('map'), {
	  		center:new google.maps.LatLng(lat,lng),
	      	zoom: 13
	    });
	    
	    var marker = new google.maps.Marker({
	      map: map,
	      position: latlng,
	      draggable: false,
	      anchorPoint: new google.maps.Point(0, -29)
	   });
	    var infowindow = new google.maps.InfoWindow();   
	    google.maps.event.addListener(marker, 'click', function() {
	      var iwContent = '<div id="iw_container">' +
	      '<div class="iw_title"><b>Location</b> : '+ address +' </div></div>';
	      // including content to the infowindow
	      infowindow.setContent(iwContent);
	      // opening the infowindow in the current map and at the current marker location
	      infowindow.open(map, marker);
	    });
	}
</script>

<script>

function bidButtons(id) {
	var x = $(id).val();
	var y = '<?= $item['minimum_bid_price'] ?>';
	var z = $('#bid_amount').val();
	if(!z){
		z=0;
	}
	$("#bid_amount").val(Number(z) + Number(x));
	let bid = $('#bid_amount').val();

	if(bid != '' && bid >= Number(y)){
		$('#bid_btn').removeAttr("disabled");
	}else{
		$('#bid_btn').attr("disabled" , "disabled");
	}
}


$(document).on("input" , "#bid_amount" , function(){
	var y = '<?= $item['minimum_bid_price'] ?>';
	let bid = $('#bid_amount').val();

	if(bid != '' && bid >= Number(y)){
		$('#bid_btn').removeAttr("disabled");
	}else{
		$('#bid_btn').attr("disabled" , "disabled");
	}
});
</script>

<script>
$(document).ready( function() {
	var hidebtn ="<?= $hide_auto_bid['auto_status']; ?>";
	if(hidebtn == "start"){
       $('#hidebid_section').hide();
       // $('#bid_amount').prop("readonly",true);
       $('#hideText').html('<div>You Are Auto Bider</div>');
   }
   else{
   	$('#autohide').show();
   	$('#hideText').hide();	
   }
});
</script>

<script>
function autoBiding(e) {

	var cbp = $('#current_price<?= $item['item_id']; ?>').val();
  	var item = "<?= $item['item_id']; ?>";
  	var loginUser = "<?= $user->username; ?>";
  	var limit = "<?= $biding_limit; ?>";
  	
  	var auctionItem_id ="<?= $item['auction_item_id']; ?>";
  	var auction = "<?= $item['auction_id']; ?>";
	var bids = $('#auto_limit').val();
	var increment = "<?= $item['minimum_bid_price']; ?>";
	var start_price = "$item['bid_start_price']; ?>";
	var sellerid = "<?= $item['item_seller_id']; ?>";

	if(bids != ''  && bids > Number(cbp)){
		$('#start_bid').removeAttr("disabled");
		if ( Number(limit) <= Number(bids)) {
        	swal("Error!", "Your Deposit Limit Exceeds.", "error");
		}else{
			// $('#bid_amount').val($('#auto_increment').val());
			// $('#bid_btn').click();
			$.ajax({
		      	type: 'post',
		      	url: "<?= base_url('auction/OnlineAuction/placebid');?>",
		      	data: {'bid_limit':bids,'bid_amount':increment, 'auction_id':auction,'item_id':item,'auction_item_id':auctionItem_id,'seller_id':sellerid,'start_price':start_price},

		      	success: function(data)
		      	{

				    var response = JSON.parse(data);
					if(response.auto_bid_msg ='true') {
						swal("Success!", "Auto bid is activated.", "success");
						window.location.reload(true);
						// return false;
				    }
				    else{
				    	swal("error!", "Auto bid is not activated.", "error");
				    }
		  		}
			});
		}

		// if (userId && userId == loginUser) {
			//auto bid////=
		// }else{
		// 	//pusher code/////
			// $('#bid_amount').val($('#auto_increment').val());
			// $('#bid_btn').click();
		// }
			//place bid
			
	}else{
			swal("Error", "Bid limit should be greater than "+cbp, "error");
			$('#start_bid').attr("disabled" , "disabled");
	}
}


	$(document).on("input" , "#auto_limit" , function(){
		var cbp = $('#current_price<?= $item['item_id']; ?>').val();
		let bids = $('#auto_limit').val();

		if(bids != '' && bids >= Number(cbp)){
			$('#start_bid').removeAttr("disabled");
		}else{
			$('#start_bid').attr("disabled" , "disabled");
		}
	});

</script>
<script src="https://maps.googleapis.com/maps/api/js?key=<?= $this->config->item('google_map_key'); ?>&callback=initialize"></script>
<style type="text/css">
	.five-items {display: flex; align-items: center; flex-wrap: wrap;}
	.five-items li {width: 130px !important; padding-right: 0 !important; margin: 0 10px 7px 0;}
	.five-items .btn.btn-primary  {font-size: 14px !important}
</style>

<script type="text/javascript" src="<?php echo base_url();?>assets_admin/sweetalert/dist/sweetalert.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets_admin/js/deletesweetalert.js"></script>