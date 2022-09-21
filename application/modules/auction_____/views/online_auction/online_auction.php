<style type="text/css">
@media print {
	#load-more {
	display: none;
	}
	.stack-on-414 {
	display: none;
	}
	.left-col {
	display: none;
	}
	.table-head {
	display: none;
	}
}
</style>

<main class="page-wrapper filter-page">
	<div class="catergory-wrapper">
		<div class="left-col">
			<form id="frmfilters">
				<input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
				<input type="hidden" name="auction_id" value="<?= $auction_id; ?>">
				<h1 class="filter-title">Price</h1>
				<div class="filter-rang">
					<ul>
						<li>
							<select name="min" class="selectpicker">
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
							// print_r($field);die();
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

				</div>
				<input type="button" id="applyFilters" name="apply" value="Apply" class="btn btn-default" />
			</form>
		</div>
		<div class="right-col">
			<?php if($online_auctions): ?>
				<ul class="auction-items">
					<?php foreach ($online_auctions as $key => $value):
						$title = json_decode($value['title']);
						?>
							<li>
								<a href="<?= base_url('auction/online-auction/'.$value['id']); ?>" 
									class="<?= ($value['id'] == $selected_auction['id']) ? 'active':''; ?>">
										<?= $title->english; ?>
								</a>
							</li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
			<div class="table-head">
				<div class="row align-items-center">
					<div class="col-sm-3">
						<h1>Search Results (<span id="count">0</span>)</h1>
					</div>
					<div class="col-sm-9">
						<ul>
							<li>
								<div class="search-box">
									<input type="" class="form-control" placeholder="Search" id="search" name="search">
									<button type="button" id="search_btn">
										<img src="<?= ASSETS_IMAGES; ?>/search-icon.png">
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
										<img src="<?= ASSETS_IMAGES; ?>/list-icon.png">
									</a>
									<a href="#" class="grid-view">
										<img src="<?= ASSETS_IMAGES; ?>/grid.png">
									</a>
								</div>
							</li>
						</ul>
					</div>
				</div>
				<ul>
					<li>
						<a href="javascript:emailCurrentPage()">
							<img src="<?= ASSETS_IMAGES; ?>/red-email.png"> Email this search 
						</a>
					</li>
					<li>
						<a href="javascript:void(0)" id="print_btn">
							<img src="<?= ASSETS_IMAGES; ?>/red-printer.png"> Print
						</a>	 
					</li>
				</ul>
			</div>
			<div class="row" id="load-here"></div>
			<div class="overlay" style="display: none;">
				<div class="loader-wrapper" ><div class="sk-spinner sk-spinner-wave"><div class="sk-rect1"></div> <div class="sk-rect2"></div> <div class="sk-rect3"></div> <div class="sk-rect4"></div> <div class="sk-rect5"></div> </div> </div>
			</div>
			<div class="button-row">
				<a id="load-more" data-offset="0" href="#" class="btn btn-default load-btn"></a>
			</div>
		</div>
	</div>	
</main>	

<script>

$('#applyFilters').on('click', function(event){
	event.preventDefault();

	var form = $(this).closest('form').serializeArray();

	$.ajax({
		method: "POST",
		url: "<?= base_url('auction/OnlineAuction/apply_filters'); ?>",
		data: form,
		beforeSend: function(){
      		$(".overlay").show();
      	},	
		success: function(data){
      		$(".overlay").hide();
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

$('#search_btn').on('click', function(event){
    event.preventDefault();

    var search = $('#search').val();
    var sort = $('#sort_by').val();
    var auctionID = '<?= $auction_id; ?>';
    //var next = offset + 3;

    $.ajax({
      method: "POST",
      url: "<?= base_url('auction/OnlineAuction/search_online_items'); ?>",
      data: {'search':search, 'auction_id':auctionID, 'sort':sort},
      beforeSend: function(){
        // $('#loading').show();
      	$(".overlay").show();
      },
      success: function(data){
      	$(".overlay").hide();
        //$('#loading').hide();
        var response = JSON.parse(data);
        console.log(response);
        if(response.status == 'success'){
          $('#load-here').html(response.items);
          $('#load-more').data('offset', response.offset);
          $('#load-more').text('Load More '+response.offset+'/'+response.total);
          $('#count').text(response.total);
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

$(document).ready(function(){
	
	// $('[data-toggle="tooltip"]').tooltip({
	// 	 title:'Hooray!'
	// });
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
	    url: "<?= base_url('auction/OnlineAuction/ai_load_more'); ?>",
	    data: {'offset':offset, 'auction_id':auctionID, 'search':search, 'sort':sort},
      	beforeSend: function(){
      		$(".overlay").show();
      	},
      	success: function(data){
	      	//alert(data);
	        $('.overlay').hide();
	        var response = JSON.parse(data);
	        console.log(response);
	        if(response.status == 'success'){
	          $('#load-here').append(response.items);
	          btn.data('offset', response.offset);
	          btn.text('Load More '+response.offset+'/'+response.total);
	          $('#count').text(response.total);
	          if(response.btn == false){
	            btn.hide();
	          }
	        }else{
	          btn.hide();
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
      url: "<?= base_url('auction/OnlineAuction/search_online_items'); ?>",
      data: {'search':search, 'auction_id':auctionID, 'sort':sort},
      beforeSend: function(){
        $(".overlay").show();
      },
      success: function(data){
      	//alert(data);
        $(".overlay").hide();
        var response = JSON.parse(data);
        console.log(response);
        if(response.status == 'success'){
          $('#load-here').html(response.items);
          $('#load-more').data('offset', response.offset);
          $('#load-more').text('Load More '+response.offset+'/'+response.total);
          $('#count').text(response.total);
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
//         	}
//         	if(msg == 'remove_heart'){
//         		heart.find('.fa-heart').addClass('fa-heart-o').removeClass('fa-heart');
//         	}
//         	if(msg == '0'){
//         		window.location.replace('<?= base_url("home/login?rurl="); ?>'+encodeURIComponent(window.location.href));
//         	}
//     	}
// 	});   
// }

$('#print_btn').on('click', function(event){ 
	window.print();
	window.focus();
});

function emailCurrentPage(){
    window.location.href="mailto:?subject="+document.title+"&body="+escape(window.location.href);
}


</script>