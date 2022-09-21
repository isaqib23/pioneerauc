
<?php if (isset($list) && !empty($list)) : ?>
<?php foreach ($list as $key => $value) : ?>
	<div class="col-sm-3">
        <div class="detail-box">
			<a href="<?=  base_url('visitor/gallery_detail/').$value['id']; ?>">
                <div class="image">
                	<?php if (isset($value) && !empty($value['files_array'])) : ?>
						<img src="<?= base_url().$value['files_array'][0]['path'].$value['files_array'][0]['name']; ?>">
					<?php else: ?>
                    	<img src="<?= ASSETS_ADMIN; ?>images/product-default.jpg" alt="">
					<?php endif; ?>
                </div>
                <div class="detail">
                    <h4><?= $value['name']; ?></h4>
                    <p><?= $value['detail']; ?></p>
                </div>
            </a>
        </div>
    </div>
<?php endforeach; ?>
<?php endif; ?>
	