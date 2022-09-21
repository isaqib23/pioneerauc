<div class="main gray-bg gallery-detail">
    <div class="container">

		<?php if (isset($list) && !empty($list)) : 
		$name = json_decode($list['name']);
		?>
	        <h1 class="section-title"><?php if(!empty($list['name'])){echo $name->$language;}  ?></h1>
		<?php endif; ?>
        <div class="pro-slider">
            <div class="main" id="bander">
            	<?php if (isset($files_array) && !empty($files_array)) : ?>
				<?php foreach ($files_array as $key => $value) : ?>
	                <div class="image">
	                    <img src="<?= base_url().$value['path'].$value['name']; ?>" width="800" height="566" alt="">
	                </div>
                <?php endforeach; ?>
				<?php endif; ?>
            </div>
            <div class="thumb" id="bander-nav">
            	<?php if (isset($files_array) && !empty($files_array)) : ?>
				<?php foreach ($files_array as $key => $value) : ?>
	                <div class="image">
	                    <img src="<?= base_url().$value['path'].$value['name']; ?>" width="118" height="88" alt="">
	                </div>
                <?php endforeach; ?>
				<?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
	// for gallery side
	$('#bander').slick({
		slidesToShow: 1,
		slidesToScroll: 1,
		arrows: true,
		fade: false,
		infinite: false,
		asNavFor: '#bander-nav',
		// rtl: true,	
		responsive: [{
			breakpoint: 1000,
			settings: {
				autoplay: true,
				arrows: false
			}
		}, ]
	});
	$('#bander-nav').slick({
		slidesToShow: 9,
		slidesToScroll: 1,
		asNavFor: '#bander',
		dots: false,
		arrows: false,
		centerMode: false,
		focusOnSelect: true,
		variableWidth: true,
		infinite: false
	});
	$('html:not(.rtl) #bander').slick({
		rtl: true,	

	});
	// $('.rtl #bander-nav').slick({
	// 	slidesToShow: 9,
	// 	slidesToScroll: 1,
	// 	asNavFor: '#bander',
	// 	dots: false,
	// 	arrows: false,
	// 	centerMode: false,
	// 	focusOnSelect: true,
	// 	variableWidth: true,
	// 	infinite: false
	// 	rtl: true,	

	// });

</script>