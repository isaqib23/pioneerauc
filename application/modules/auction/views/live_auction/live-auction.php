
	<main class="page-wrapper live-auction">
		<div class="catergory-wrapper">
			<div class="left-col">
				<form id="frmfilters">
					<input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
					<input type="hidden" name="auction_id" value="<?= $auction_id; ?>">
					<h1 class="filter-title">Price</h1>
					<div class="filter-rang">
						<ul>
							<li>
								<select name="min" class="selectpicker" >
									<option value="0">Min</option>
									<option value="1000">1000</option>
									<option value="2000">2000</option>
									<option value="3000">3000</option>
									<option value="4000">4000</option>
									<option value="5000">5000</option>
								</select>
							</li>
							<li>
								<select name="max" class="selectpicker">
									<option value="0">Max</option>
									<option value="100000">100000</option>
									<option value="200000">200000</option>
									<option value="300000">300000</option>
									<option value="400000">400000</option>
									<option value="500000">500000</option>
								</select>
							</li>
						</ul>
					</div>
					<div class="filters-list">

						<?php if($item_category_fields):
						foreach ($item_category_fields as $key => $field):
							?>

							<div class="catergory">
								<a href="#"><?= $field['label']; ?></a>
								<ul class="">
									<?php 
									if(in_array($field['type'], ['select','radio-group','checkbox-group'])):
										$values = json_decode($field['values'],true);
										foreach ($values as $key => $value):
										?>
										<li class="checkbox red">
											<label>
												<input type="checkbox" name="<?= $field['id']; ?>[]" value="<?= $value['value']; ?>">
												<span><?= $value['label']; ?> <b></b></span>
											</label>
										</li>
										<?php endforeach;
									elseif(in_array($field['type'], ['text','textarea'])):
										?>
										<li class="checkbox red">
											<!-- <label for="<?= $field['name']; ?>">
												<span><?= $field['label']; ?></span>
											</label> -->
												<input type="<?= $field['type']; ?>" 
													id="<?= $field['id']; ?>" 
													name="<?= $field['id']; ?>" 
													class="form-control"
													placeholder="<?= $field['placeholder']; ?>">
										</li>
									<?php endif; ?>
									
								</ul>
							</div>

							<?php
						endforeach;
					endif;
					?>
						<!-- <div class="catergory">
							<a href="#">Make</a>
							<ul class="">
								<li class="checkbox red">
									<label>
										<input type="checkbox" name="">
										<span>Volkswagen <b>(10)</b></span>
									</label>
								</li>
								<li class="checkbox red">
									<label>
										<input type="checkbox" name="">
										<span>Toyota <b>(1)</b></span>
									</label>
								</li>
							</ul>
						</div>
						<div class="catergory">
							<a href="#">Condition</a>
							<ul >
								<li class="checkbox red">
									<label>
										<input type="checkbox" name="">
										<span>Volkswagen <b>(10)</b></span>
									</label>
								</li>
								<li class="checkbox red">
									<label>
										<input type="checkbox" name="">
										<span>Toyota <b>(1)</b></span>
									</label>
								</li>
							</ul>
						</div>
						<div class="catergory">
							<a href="#">Size</a>
							<ul >
								<li class="checkbox red">
									<label>
										<input type="checkbox" name="">
										<span>Volkswagen <b>(10)</b></span>
									</label>
								</li>
								<li class="checkbox red">
									<label>
										<input type="checkbox" name="">
										<span>Toyota <b>(1)</b></span>
									</label>
								</li>
							</ul>
						</div>
						<div class="catergory">
							<a href="#">Model</a>
							<ul >
								<li class="checkbox red">
									<label>
										<input type="checkbox" name="">
										<span>Volkswagen <b>(10)</b></span>
									</label>
								</li>
								<li class="checkbox red">
									<label>
										<input type="checkbox" name="">
										<span>Toyota <b>(1)</b></span>
									</label>
								</li>
							</ul>
						</div>
						<div class="catergory">
							<a href="#">Body</a>
							<ul >
								<li class="checkbox red">
									<label>
										<input type="checkbox" name="">
										<span>Volkswagen <b>(10)</b></span>
									</label>
								</li>
								<li class="checkbox red">
									<label>
										<input type="checkbox" name="">
										<span>Toyota <b>(1)</b></span>
									</label>
								</li>
							</ul>
						</div>
						<div class="catergory">
							<a href="#">Transmission</a>
							<ul >
								<li class="checkbox red">
									<label>
										<input type="checkbox" name="">
										<span>Volkswagen <b>(10)</b></span>
									</label>
								</li>
								<li class="checkbox red">
									<label>
										<input type="checkbox" name="">
										<span>Toyota <b>(1)</b></span>
									</label>
								</li>
							</ul>
						</div>
						<div class="catergory">
							<a href="#">Drive Type</a>
							<ul >
								<li class="checkbox red">
									<label>
										<input type="checkbox" name="">
										<span>Volkswagen <b>(10)</b></span>
									</label>
								</li>
								<li class="checkbox red">
									<label>
										<input type="checkbox" name="">
										<span>Toyota <b>(1)</b></span>
									</label>
								</li>
							</ul>
						</div>
						<div class="catergory">
							<a href="#">Doors</a>
							<ul >
								<li class="checkbox red">
									<label>
										<input type="checkbox" name="">
										<span>Volkswagen <b>(10)</b></span>
									</label>
								</li>
								<li class="checkbox red">
									<label>
										<input type="checkbox" name="">
										<span>Toyota <b>(1)</b></span>
									</label>
								</li>
							</ul>
						</div>
						<div class="catergory">
							<a href="#">Seats</a>
							<ul >
								<li class="checkbox red">
									<label>
										<input type="checkbox" name="">
										<span>Volkswagen <b>(10)</b></span>
									</label>
								</li>
								<li class="checkbox red">
									<label>
										<input type="checkbox" name="">
										<span>Toyota <b>(1)</b></span>
									</label>
								</li>
							</ul>
						</div>
						<div class="catergory">
							<a href="#">Colour</a>
							<ul >
								<li class="checkbox red">
									<label>
										<input type="checkbox" name="">
										<span>Volkswagen <b>(10)</b></span>
									</label>
								</li>
								<li class="checkbox red">
									<label>
										<input type="checkbox" name="">
										<span>Toyota <b>(1)</b></span>
									</label>
								</li>
							</ul>
						</div>
						<div class="catergory">
							<a href="#">Year</a>
							<ul >
								<li class="checkbox red">
									<label>
										<input type="checkbox" name="">
										<span>Volkswagen <b>(10)</b></span>
									</label>
								</li>
								<li class="checkbox red">
									<label>
										<input type="checkbox" name="">
										<span>Toyota <b>(1)</b></span>
									</label>
								</li>
							</ul>
						</div>
						<div class="catergory">
							<a href="#">Odometer</a>
							<ul >
								<li class="checkbox red">
									<label>
										<input type="checkbox" name="">
										<span>Volkswagen <b>(10)</b></span>
									</label>
								</li>
								<li class="checkbox red">
									<label>
										<input type="checkbox" name="">
										<span>Toyota <b>(1)</b></span>
									</label>
								</li>
							</ul>
						</div> -->
					</div>
					<input type="button" id="applyFilters" name="apply" value="Apply" class="btn btn-default" />
				</form>
			</div>
			<div class="right-col">
				<?php if($live_auctions): ?>
					<ul class="auction-items">
						<?php foreach ($live_auctions as $key => $value):
							$title = json_decode($value['title']);
							?>
								<li>
									<a href="<?= base_url('auction/live-auction-items/'.$value['id']); ?>" 
										class="<?= ($value['id'] == $selected_auction['id']) ? 'active':''; ?>">
											<?= $title->english; ?>
									</a>
								</li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
				<div class="table-head">
					<div class="row">
						<div class="col-md-4">
							<!-- <p>Tuesday, November 19</p> -->
							<p><?= date('l F y'); ?></p>
							<h1 class="table-heading" >Todays Live Auction</h1>
						</div>
						<div class="col-md-8">
							<ul>
								<li>
									<div class="search-box">
										<input type="" class="form-control" id="search" placeholder="Search" name="search">
										<button id="search_btn" type="button">
											<img src="<?= ASSETS_USER; ?>images/search-icon.png">
										</button>
									</div>
								</li>
								<li>
									<select class="selectpicker gray-text" id="sort_by">
										<option value="" selected>Sort by Type</option>
										<option value="">Latest</option>
										<option value="hp">High Price</option>
										<option value="lp">Low Price</option>
									</select>
								</li>
								<li class="view">
									<span>View</span>
									<div class="view-changer">
										<a href="#" class="list-view active">
											<img src="<?= ASSETS_USER; ?>images/list-icon.png">
										</a>
										<a href="#" class="grid-view">
											<img src="<?= ASSETS_USER; ?>images/grid.png">
										</a>
									</div>
								</li>
							</ul>
						</div>
					</div>
					<ul>
						<li>
							<a href="#">
								<img src="<?= ASSETS_USER; ?>images/red-email.png"> Email this search 
							</a>
						</li>
						<li>
							<a href="javascript:void(0)" id="print_btn">
								<img src="<?= ASSETS_USER; ?>images/red-printer.png"> Print
							</a>	 
						</li>
					</ul>
				</div>
				<div class="row" id="load-here">
					<!-- <div class="col-12">
						<div class="pro-box">
							<div class="image">
								<img src="<?= ASSETS_USER; ?>images/bolg-image.png">
								<div class="overlay">
									<ul>
										<li>
											<a href="#" class="wishlist-icon">
												<i class="fa fa-heart-o"></i>
											</a>
										</li>
										<li>
											<a href="#" class="btn btn-default">BID NOW</a>
										</li>
									</ul>
								</div>
							</div>
							<div class="desc">
								<div class="row">
									<div class="col-lg-8">
										<h1>Chevrolet Camaro 2013</h1>
										<i>Black, Automatic, 4 Door, MPV, Petrol</i>
									</div>
									<div class="col-lg-4">
										<p><img src="<?= ASSETS_USER; ?>images/wifi-signal-red.png"> LIVE AUCTIONS</p>
									</div>
								</div>
								<div class="row rating-row">
									<div class="col-lg-7">
										<div class="rating"></div>
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
												<img src="<?= ASSETS_USER; ?>images/timed-eye.png"> 150
											</li>
										</ul>
									</div>
								<div class="col-lg-5">
										<ul class="action-btns">
											<li>
												<a href="#" class="wishlist-icon">
													<i class="fa fa-heart-o"></i>
												</a>
											</li>
											<li>
												<a href="#" class="btn btn-primary">LIVE NOW</a>
											</li>
										</ul>
									</div>
								</div>
							</div>
							<div class="desc-for-grid">
								<h4>Mercedes C 300 2017</h4>
								<h3 class="red-text">AED 25,000</h3>
								<ul>
									<li>
										<img src="<?= ASSETS_USER; ?>images/timed-calndr.png"> 2020
									</li>
									<li>
										<img src="<?= ASSETS_USER; ?>images/timed-clock.png"> 266,154
									</li>
									<li>
										<img src="<?= ASSETS_USER; ?>images/timed-eye.png"> 150
									</li>
								</ul>
							</div>
						</div>
					</div> -->
				</div>	
				<div class="button-row">
					<a id="load-more" data-offset="0" href="#" class="btn btn-default">LOAD MORE 1/2</a>
				</div>
			</div>
		</div>	
	</main>


<script>
	var token_name = '<?= $this->security->get_csrf_token_name();?>';
	var token_value = '<?=$this->security->get_csrf_hash();?>';

	$('#applyFilters').on('click', function(event){
		event.preventDefault();

		var form = $(this).closest('form').serializeArray();

		$.ajax({
			method: "POST",
			url: "<?= base_url('auction/LiveAuction/apply_filters'); ?>",
			data: form,
			success: function(data){
				//console.log(data);
				var response = JSON.parse(data);
				if(response.status == 'success'){
	          		$('#load-here').html(response.items);
	          	}else{
	          		$('#load-here').html('<h1>No item found.</h1>');
	          	}
	          	if(response.btn == false){
		        	$('#load-more').hide();
		        }
			}
	  	});
	});


	$( document ).ready(function() {
	    $('#load-more').click();
	});
	$('#load-more').on('click', function(event){
	    event.preventDefault();

	    var btn = $(this);
	    var offset = btn.data('offset');
	    var auctionID = '<?= $auction_id; ?>';
	    var search = $('#search').val();
	    var sort = $('#sort_by').val();
	    //var next = offset + 3;

	    $.ajax({
	      method: "POST",
	      url: "<?= base_url('auction/LiveAuction/ai_load_more'); ?>",
	      data: {'offset':offset, 'auction_id':auctionID, 'search':search, 'sort':sort, [token_name]:token_value},
	      /*beforeSend: function(){
	        $('#loading').show();
	      },*/
	      success: function(data){
	      	//alert(data);
	        //$('#loading').hide();
	        var response = JSON.parse(data);
	        console.log(response);
	        if(response.status == 'success'){
	          $('#load-here').append(response.items);
	          btn.data('offset', response.offset);
	          btn.text('Load More '+response.offset+'/'+response.total);
	          if(response.btn == false){
	            btn.hide();
	          }
	        }else{
	          btn.hide();
	        }
	      }
	    });
	});

	function doFavt(e){
		//alert('dona done');
		var heart = $(e);
		var itemID = $(e).data('item');
		$.ajax({
	      	type: 'post',
	      	url: '<?= base_url('auction/OnlineAuction/do_favt'); ?>',
	      	data: {'item_id':itemID, [token_name]:token_value},
	      	success: function(msg){
	        	console.log(msg);
	        	//var response = JSON.parse(msg);
	        	if(msg == 'do_heart'){
	        		heart.find('.fa-heart-o').addClass('fa-heart').removeClass('fa-heart-o');
	        	}
	        	if(msg == 'remove_heart'){
	        		heart.find('.fa-heart').addClass('fa-heart-o').removeClass('fa-heart');
	        	}
	        	if(msg == '0'){
	        		window.location.replace('<?= base_url("home/login"); ?>');
	        	}
	    	}
		});   
	}

	$('#search_btn').on('click', function(event){
	    event.preventDefault();

	    var search = $('#search').val();
	    var sort = $('#sort_by').val();
	    var auctionID = '<?= $auction_id; ?>';
	    //var next = offset + 3;

	    $.ajax({
	      method: "POST",
	      url: "<?= base_url('auction/LiveAuction/search_online_items'); ?>",
	      data: {'search':search, 'auction_id':auctionID, 'sort':sort, [token_name]:token_value},
	      /*beforeSend: function(){
	        $('#loading').show();
	      },*/
	      success: function(data){
	      	//alert(data);
	        //$('#loading').hide();
	        var response = JSON.parse(data);
	        console.log(response);
	        if(response.status == 'success'){
	          $('#load-here').html(response.items);
	          $('#load-more').data('offset', response.offset);
	          $('#load-more').text('Load More '+response.offset+'/'+response.total);
	          if(response.btn == false){
	            $('#load-more').hide();
	          }else{
	            $('#load-more').show();
	          }
	        }else{
	          	$('#load-here').html('<h1>No item found.</h1>');
	          $('#load-more').hide();
	        }
	      }
	    });
	});

	$('#sort_by').on('change', function(event){
	    event.preventDefault();

	    var search = $('#search').val();
	    var sort = $('#sort_by').val();
	    var auctionID = '<?= $auction_id; ?>';
	    //var next = offset + 3;

	    $.ajax({
	      method: "POST",
	      url: "<?= base_url('auction/LiveAuction/search_online_items'); ?>",
	      data: {'search':search, 'auction_id':auctionID, 'sort':sort, [token_name]:token_value},
	      /*beforeSend: function(){
	        $('#loading').show();
	      },*/
	      success: function(data){
	      	//alert(data);
	        //$('#loading').hide();
	        var response = JSON.parse(data);
	        console.log(response);
	        if(response.status == 'success'){
	          $('#load-here').html(response.items);
	          $('#load-more').data('offset', response.offset);
	          $('#load-more').text('Load More '+response.offset+'/'+response.total);
	          if(response.btn == false){
	            $('#load-more').hide();
	          }else{
	            $('#load-more').show();
	          }
	        }else{
	          	$('#load-here').html('<h1>No item found.</h1>');
	          $('#load-more').hide();
	        }
	      }
	    });
	});

	$('#print_btn').on('click', function(event){ 
		window.print();
		window.focus();
		// var divToPrint=document.getElementById("container2");
		// newWin= window.open("");
		// newWin.document.write(divToPrint.outerHTML);
		// newWin.print();
		// newWin.close();
	});

</script>