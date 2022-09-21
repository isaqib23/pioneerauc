<?php
foreach ($auction_items as $key => $auction_item):
	$visit_count = $this->db->where('item_id',$auction_item['item_id'])->where('auction_id',$auction_item['auction_id'])->from('online_auction_item_visits')->count_all_results();
	$bid_info = $this->db->where('item_id',$auction_item['item_id'])->where('auction_id',$auction_item['auction_id'])->order_by('id', 'desc')->from('bid')->get()->result_array();
	$count = count($bid_info);

	$bid_end_time = strtotime($auction_item['bid_end_time']);
	$item_images_ids = explode(',', $auction_item['item_images']);
	$images = $this->db->where_in('id', $item_images_ids)->get('files')->result_array();
	if($images){
		$image_src = base_url('uploads/items_documents/').$auction_item['item_id'].'/'.$images[0]['name'];
	}else{
		$image_src = '';
	}

	$ratings = 0;
	$user = $this->session->userdata('logged_in');
    if($user){
		$ratings = $this->db->get_where('auction_item_ratings', ['auction_item_id' => $auction_item['auction_item_id'],'user_id' => $user->id])->row_array();
		if($ratings){
			$ratings = $ratings['ratings'];
		}else{
			$ratings = 0;
		}
	}

	$favorite = 'fa-heart-o';
	$user = $this->session->userdata('logged_in');
    if($user){
		$favt = $this->db->get_where('favorites_items', ['item_id' => $auction_item['item_id'],'user_id' => $user->id])->row_array();
		if($favt){
			$favorite = 'fa-heart';
		}else{
			$favorite = 'fa-heart-o';
		}
	}
?>
	<div class="col-12">
		<div class="pro-box">
			<div class="image">
				<img id="iimg" src="<?= $image_src; ?>" >
				<div class="overlay">
					<ul>
						<li>
							<a onclick="doFavt(this)" data-item="<?= $auction_item['item_id']; ?>" href="javascript:void(0);" class="wishlist-icon">
								<i class="fa <?= $favorite; ?>"></i>
							</a>
						</li>
						<li>
							<a href="<?= base_url('auction/live-auction/details/').$auction_item['auction_id'].'/'.$auction_item['item_id']; ?>" class="btn btn-default">BID NOW</a>
						</li>
					</ul>
				</div>
			</div>
			<div class="desc">
				<div class="row">
					<div class="col-lg-8">
						<h1><?= $auction_item['item_name']; ?></h1>
						<i><?= $auction_item['item_detail']; ?></i>
					</div>
					<div class="col-lg-4">
						<p><img src="<?= ASSETS_USER; ?>images/wifi-signal-red.png"> LIVE AUCTIONS</p>
					</div>
				</div>
				<div class="row rating-row">
					<div class="col-lg-7">
						<div class="rating" data-score="<?= $ratings; ?>" data-aiid="<?= $auction_item['auction_item_id']; ?>"></div>
					</div>
					<div class="col-lg-5">
					</div>
				</div>
				<div class="row bid-row align-items-center">
					<div class="col-lg-7">
						<ul>
							<li>
								<img src="<?= ASSETS_USER; ?>images/timed-calndr.png"> 2020
							</li>
							<li>
								<img src="<?= ASSETS_USER; ?>images/timed-clock.png"> 266,154
							</li>
							<li>
								<img src="<?= ASSETS_USER; ?>images/timed-eye.png"> <?= (!empty($visit_count)) ? $visit_count : '0'; ?>
							</li>
						</ul>
					</div>
				<div class="col-lg-5">
						<ul class="action-btns">
							<li>
								<a onclick="doFavt(this)" data-item="<?= $auction_item['item_id']; ?>" href="javascript:void(0);" class="wishlist-icon">
									<i class="fa <?= $favorite; ?>"></i>
								</a>
							</li>
							<li>
								<a href="<?= base_url('auction/live-auction/details/').$auction_item['auction_id'].'/'.$auction_item['item_id']; ?>" class="btn btn-primary">LIVE NOW</a>
							</li>
						</ul>
					</div>
				</div>
			</div>
			<div class="desc-for-grid">
				<h4><?= $auction_item['item_name']; ?></h4>
				<!-- <h3 class="red-text">AED 25,000</h3> -->
				<ul>
					<li>
						<img src="<?= ASSETS_USER; ?>images/timed-calndr.png"> 2020
					</li>
					<li>
						<img src="<?= ASSETS_USER; ?>images/timed-clock.png"> 266,154
					</li>
					<li>
						<img src="<?= ASSETS_USER; ?>images/timed-eye.png"><?= (!empty($visit_count)) ? $visit_count : '0'; ?>
					</li>
				</ul>
			</div>
		</div>
	</div>
	<!-- <div class="col-12">
		<div class="pro-box">
			<div class="image">
				<img id="iimg" src="<?= $image_src; ?>" >
				<div class="overlay">
					<ul>
						<li>
							<a onclick="doFavt(this)" data-item="<?= $auction_item['item_id']; ?>" href="javascript:void(0);" class="wishlist-icon">
								<i class="fa <?= $favorite; ?>"></i>
							</a>
						</li>
						<li>
							<a href="<?= base_url('auction/online-auction/details/').$auction_item['auction_id'].'/'.$auction_item['item_id']; ?>" class="btn btn-default">BID NOW</a>
						</li>
						<li>	
							<div class="timer" data-date="2019-12-29 00:00:00"></div>
						</li>
					</ul>
				</div>
			</div>
			<div class="desc">
				<div class="row">
					<div class="col-sm-8">
						<a href="<?= base_url('auction/OnlineAuction/item_detail/'.$auction_item['item_id']); ?>"><h1><?= $auction_item['item_name']; ?></h1></a>
						<i><?= $auction_item['item_detail']; ?></i>
					</div>
					<div class="col-sm-4">
						<h3><span>AED</span><?= (!empty($bid_info)) ? $bid_info['0']['bid_amount'] : $auction_item['bid_start_price']; ?></h3>
					</div>
				</div>
				<div class="row rating-row">
					<div class="col-sm-6">
						<div class="rating" data-score="<?= $ratings; ?>" data-aiid="<?= $auction_item['auction_item_id']; ?>"></div>
					</div>
					<div class="col-sm-6">
						<ul>
							<li>
								<i>Time remaining</i>
							</li>
							<li>	
								<div class="timer" id="expiry-timer<?= $key; ?>"></div>
								<div>
									<script>
				                      $('#expiry-timer<?= $key; ?>').syotimer({
				                        year: <?= date('Y', $bid_end_time); ?>,
				                        month: <?= date('m', $bid_end_time); ?>,
				                        day: <?= date('d', $bid_end_time); ?>,
				                        hour: <?= date('H', $bid_end_time); ?>,
				                        minute: <?= date('i', $bid_end_time); ?>
				                      });
				                    </script>
								</div>
							</li>	
						</ul>
					</div>
				</div>
				<div class="row price-row">
					<div class="col-sm-12">
						<ul>
							<li>
								<h3>Min Increment Price</h3>
								<span>AED <?= $auction_item['minimum_bid_price']; ?></span>
							</li>
							<li>
								<h3>End Time</h3> 
								<span><?= $auction_item['bid_end_time']; ?></span>
							</li>
							<li>
								<h3>No: of Bids Made</h3> 
								<span><?=  (!empty($count)) ? $count : '0'; ?></span>
							</li>
						</ul>
					</div>
				</div>
				<div class="row bid-row align-items-center">
					<div class="col-sm-7">
						<ul>
							<li>
								<img src="<?= ASSETS_IMAGES; ?>/timed-calndr.png"> 2020
							</li>
							<li>
								<img src="<?= ASSETS_IMAGES; ?>/timed-clock.png"> 266,154
							</li>
							<li>
								<img src="<?= ASSETS_IMAGES; ?>/timed-eye.png"><?= (!empty($visit_count)) ? $visit_count : '0'; ?>
							</li>
						</ul>
					</div>
					<div class="col-sm-5">
						<ul class="action-btns">
							<li>
								<a onclick="doFavt(this)" data-item="<?= $auction_item['item_id']; ?>" href="javascript:void(0);" class="wishlist-icon">
									<i class="fa <?= $favorite; ?>"></i>
								</a>
							</li>
							<li>
								<a href="<?= base_url('auction/online-auction/details/').$auction_item['auction_id'].'/'.$auction_item['item_id']; ?>" class="btn btn-default">BID NOW</a>
							</li>
						</ul>
					</div>
				</div>
			</div>
			<div class="desc-for-grid">
				<h4><?= $auction_item['item_name']; ?></h4>
				<h3>AED <?= (!empty($bid_info)) ? $bid_info['0']['bid_amount'] : $auction_item['bid_start_price']; ?></h3>
				<ul>
					<li>
						<img src="<?= ASSETS_IMAGES; ?>/timed-calndr.png"> 2020
					</li>
					<li>
						<img src="<?= ASSETS_IMAGES; ?>/timed-clock.png"> 266,154
					</li>
					<li>
						<img src="<?= ASSETS_IMAGES; ?>/timed-eye.png"><?= (!empty($visit_count)) ? $visit_count : '0'; ?>
					</li>
				</ul>
			</div>
		</div>
	</div> -->
<?php endforeach; ?>


<script>
	//ratings
$( document ).ready(function() {
	$('.rating').raty({
	  click: function(score, evt) {
	  	var aiid = $(this).attr('data-aiid');
	    $.ajax({
			method: "POST",
			url: "<?= base_url('auction/OnlineAuction/ratings'); ?>",
			data: {'ratings':score, 'auction_item_id':aiid},
			success: function(data){
				//console.log(data);
				if(data == '1'){
        			console.log('1');
	        	}
	        	if(data == '0'){
	        		window.location.replace('<?= base_url("home/login"); ?>');
	        	}
			}
		});
	  },
	  score: function() {
	    return $(this).attr('data-score');
	  }
	});
});

</script>