
		<section class="aution">
			<div class="our-services-1">
				<div class="container">	
					<h2 class="wow fadeInDown" data-wow-duration="0.4" data-wow-delay="0.40s">AUCTIONS</h2>
					<div class="">
						<div class="slide">

							<?php if($live_auctions): 
				              foreach ($live_auctions as $key => $auction):
				                $expiry_time = strtotime($auction['expiry_time']);
				                $cat_title = json_decode($auction['cat_title']);
				                $icon = $this->db->get_where('files', ['id' => $auction['cat_icon']])->row_array();
				                ?>
				                <div class="box">
					                <a href="<?= base_url('auction/live-auction-items/'.$auction['id']); ?>">
					                    <div class="image">
					                      <img src="<?= base_url('uploads/category_icon/').$icon['name']; ?>">
					                    </div>
										<span><?= (!empty($auction['item_count'])) ? $auction['item_count'] : ''; ?></span>
					                    <p><?= $cat_title->english; ?></p>
					                    <div class="timer"></div>
					                </a>
				                </div>
				              <?php endforeach; ?>
				            <?php endif; ?>

							<!-- <div class="box">
								<div class="image">
									<img src="<?= ASSETS_USER; ?>images/services-1.png">
									<img src="<?= ASSETS_USER; ?>images/services-1-hover.png" class="hover-img">
								</div>
									<span>10</span>
								<p>VEHICLE</p>
								<div class="timer"></div>
							</div>
							<div class="box">
								<div class="image">
									<img src="<?= ASSETS_USER; ?>images/services-1.png">
									<img src="<?= ASSETS_USER; ?>images/services-1-hover.png" class="hover-img">
								</div>
									<span>1</span>
								<p>VEHICLE</p>
								<div class="timer"></div>
							</div>
							<div class="box">
								<div class="image">
									<img src="<?= ASSETS_USER; ?>images/services-1.png">
									<img src="<?= ASSETS_USER; ?>images/services-1-hover.png" class="hover-img">
								</div>
								<p>VEHICLE</p>
								<div class="timer"></div>
							</div>
							<div class="box">
								<div class="image">
									<img src="<?= ASSETS_USER; ?>images/services-1.png">
									<img src="<?= ASSETS_USER; ?>images/services-1-hover.png" class="hover-img">
								</div>
								<p>VEHICLE</p>
								<div class="timer"></div>
							</div>
							<div class="box">
								<div class="image">
									<img src="<?= ASSETS_USER; ?>images/services-1.png">
									<img src="<?= ASSETS_USER; ?>images/services-1-hover.png" class="hover-img">
								</div>
									<span>10</span>
								<p>VEHICLE</p>
								<div class="timer"></div>
							</div>
							<div class="box">
								<div class="image">
									<img src="<?= ASSETS_USER; ?>images/services-1.png">
									<img src="<?= ASSETS_USER; ?>images/services-1-hover.png" class="hover-img">
								</div>
									<span>10</span>
								<p>VEHICLE</p>
								<div class="timer"></div>
							</div>
							<div class="box">
								<div class="image">
									<img src="<?= ASSETS_USER; ?>images/services-1.png">
									<img src="<?= ASSETS_USER; ?>images/services-1-hover.png" class="hover-img">
								</div>
									<span>10</span>
								<p>VEHICLE</p>
								<div class="timer"></div>
							</div> -->
						</div>		
					</div>	
				</div>
			</div>	
		</section>	