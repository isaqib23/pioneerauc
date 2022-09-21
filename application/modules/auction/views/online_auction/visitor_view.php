
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
	// $favt = $this->db->get_where('favorites_items', ['item_id' => $item['id'],'user_id' => $user->id])->row_array();
	// if($favt){
	// 	$favorite = 'fa-heart';
	// }else{
	// 	$favorite = 'fa-heart-o';
	// }

?>
<body class="gradient-banner">
			<main class="page-wrapper online-auction">
				<div class="auctions">
					<ul class="auction-itemss" style="padding-top: 20px">
						<!-- <li><a href="#" class="active">CURRENT LOT</a></li>
						<li><a href="#">ALL LOTS</a></li>
						<li><a href="#">Winning lots</a></li>
						<li><a href="#">proxy bid</a></li>	 -->
					</ul>
					<div class="container" style="max-width: 1100px;">
						<div class="row stack-on-1200">
							<div class="col-sm-12">
								<div class="slider-wapper">
									<div class="row">
								<div class="col-sm-12">
									<div class="slider-for">
										<?php foreach ($images as $key => $value) { ?>
											<div class="image">
												<img src="<?= base_url('uploads/items_documents/').$item['id'].'/'.$value['name']; ?>" style="width: 752px; height: 392px;" >
											
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
							</div>
							<div style="display: flex; background-color: white; margin: -20px 0px 10px 100px; height: 100px !important;" class="col-sm-10">
								<div class="col-sm-7" >
									<p style="font-weight: 1000; text-align: right; padding-top: 25px;"> Please <a style="color: #5797ff;" href="<?= base_url('home/register'); ?>"> register here</a> or <a style="color: #5797ff;" href="<?= base_url('home/login'); ?>"> login</a> to make a BID. <br>
									A minimum 10,000 Deposit is required.</p>
								</div>
								<div class="col-sm-3" style="margin-top: -20px; padding-top: 30px;" >
									<a href="<?= base_url('home/login?rurl=').$rurl; ?>" type="button" style="width: 200px !important;" class="btn btn-default"> LOGIN </a>
								</div>
								
									
							</div>
							<div class="col-sm-12">
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

									  	<div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
									  		<div class="row">
												<div class="col-lg-6">
												 <ul>
											        <li>Location:</li>
											        <li><?= $item['item_location']; ?></li>
										     </ul>
												</div>
											<!-- 	<div class="col-lg-6">
													<ul>
														<li>Build Year:</li>
														<li>2009</li>
													</ul>
													<ul>
														<li>Compliance:</li>
														<li>08/2009</li>
													</ul>
													<ul>
														<li>Make:</li>
														<li>VOLKSWAGEN</li>
													</ul>
													<ul>
														<li>Body Type:</li>
														<li>S/Wagon</li>
													</ul>
												</div> -->
											</div>
									  	</div>
									</div>
								</div>
							</div>
						</div>

					</div>	
				</div>			
		</main>
