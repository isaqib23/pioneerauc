<main>
		<div class="slider">
			<div class="main"> 
				<?php 
			if (!empty($auction_items)) 	
			?>
			<?php $pfile = $this->db->get_where('files', ['id' => $auction_items['item_images']])->row_array(); 
			                 ?>
				<div class="image">
					<img src="<?= base_url($pfile['path'] . $pfile['name']); ?>" alt="">
				</div>
				<?php ?> 
				
			</div>
			<div class="thumb">
				if (!empty($auction_items)) 	
			?>
			<?php $pfile = $this->db->get_where('files', ['id' => $auction_items['item_images']])->row_array();?>
				<div class="image">
					<img src="<?= base_url($pfile['path'] . $pfile['name']); ?>" alt="">
				</div>
				<?php ?>
			</div>
		</div>
	</main>