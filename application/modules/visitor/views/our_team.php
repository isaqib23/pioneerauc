
		<main class="page-wrapper our-team">
		<div class="inner-banner">
			<div class="container">
				<div class="caption">
					<h1 class="page-title"><span>About us</span><br>TEAM</h1>

					<?php (isset($our_team) && !empty($our_team['description']));
					$des = json_decode($our_team['description']);
					// print_r($our_team_info[0]['description']);die();
					?>
					<p><?php echo (isset($des)) ? $des->english : ''; ?></p>	
				</div>
			</div>
		</div>
		<div class="gray-bg">
			<div class="bg-arrow">
				<div class="container">
					<div class="our-team-info">
					  <div class="row">
					    <?php
					    if(isset($our_team) && !empty($our_team)){
					     foreach ($our_team_info as $key => $value) {
					    	$name = json_decode($value['member_name']);
					    	$designation = json_decode($value['designation']);
					    	$pic= $this->db->get_where('files',['id'=>$value['image']])->row_array();
					    	 ?>
						    <div class="col-lg-3 col-md-4 col-6">
								<div class="info-box">
									<div class="image">
										<img src="<?= base_url().$pic['path'].$pic['name'];?>">
									</div>
									<div class="desc">	
										 <?php if(isset($value['member_name']) && !empty($value['member_name'])){ ?>
										     <h4><?php echo $name->english;?></h4>
							            <?php } ?>
										<?php if(isset($value['designation']) && !empty($value['designation'])){ ?>
										     <p><?php echo $designation->english;?></p>
							            <?php } ?>
									</div>
								</div>
							</div>
						 <?php }
						} ?>
						</div>
					</div>
				</div>
			</div>					
		</div>
		</main>
