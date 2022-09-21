<?php 
if (!empty($auction_items)) {
?>
<body class="detail-page">
	<main>
		<?php isset($auction_items) && !empty($auction_items);		
		?>
		<div class="wrapper">
			<div class="row col-gap-24">
				<div class="col-sm-6">
					<div class="item">
						<h2>Lot <span><?php if (isset($auction_items['lot_no']) && !empty($auction_items['lot_no'])) {
                           echo $auction_items['lot_no']; 
          } ?></span></h2>
                          <?php if (isset($auction_items) && !empty($auction_items)) ?>   
			                 <?php $pfile = $this->db->get_where('files', ['id' => $auction_items['item_images']])->row_array(); 
			                 if (!empty($pfile)) {
			                 ?>
						<div class="image">
							<!-- <img src="assets_user/images/gallery-detail.png" alt=""> -->
							<a href="<?php echo base_url('Screens/screen3');?>"><img  src="<?= base_url($pfile['path'] . $pfile['name']); ?>" class="hideBars"></a>
						</div>
						<?php } else{?> 
							<div class="image">
							<!-- <img src="assets_user/images/gallery-detail.png" alt=""> -->
							<img src="assets_user/images/gallery-detail.png" alt="">
						</div>
					<?php }?>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="content-box">
						<h3><?php if (isset($auction_items['name']) && !empty($auction_items['name'])) {
                            echo $auction_items['name']; }?></h3>
						<ul>
							<li><span>Spec</span>GCC</li>
							<li><span>Model</span><?php if (isset($auction_items['title']) && !empty($auction_items['title'])) {
                            echo $auction_items['title']; }?></li>
							<li><span>Trans</span>Automatic</li>
							<li><span>Mileage</span><?php if (isset($auction_items['milleage']) && !empty($auction_items['milleage'])) {
                            echo $auction_items['milleage']; }?> km</li>
						</ul>
					</div>
					<?php if (!empty($auction_items['bid_amount'])){

					?>
					<div class="content-box waiting">
						<div class="button-row">
							<span>Bid Placed</span><a href="#" class="btn btn-default"><?php if (isset($auction_items['bid_amount']) && !empty($auction_items['bid_amount'])) {
                            echo $auction_items['bid_amount']; }?><i>AED</i></a>
						</div>
					</div>
				<?php } else { ?>
					<div class="content-box waiting">
						<div class="button-row">
							<span>Waiting Bid</span><a href="#" class="btn btn-default">0 <i>AED</i></a>
						</div>
					</div>
				<?php }?>
				</div>
			</div>
			<div class="content-box desc">
				<p><?php if (isset($auction_items['detail']) && !empty($auction_items['detail'])) {
                            echo $auction_items['detail']; }?></p>
			</div>
		</div>
	</main>
</body>

<?php } else {	
 ?>
 <body class="detail-page">
 <main class="wrapper">
		<div class="main-image">
			<img src="assets_user/images/screen-bg.png" alt="">
		</div>
	</main>
</body>
<?php } ?>
