

<div class="main gray-bg gallery">
    <div class="container">
        <h1 class="section-title"><?= $this->lang->line('gallery');?></h1>
        <div class="row">

			<?php if (isset($list) && !empty($list)) : ?>

			<?php foreach ($list as $key => $value) :
        $title = json_decode($value['name']);
        $detail = json_decode($value['detail']);
        ?>
	            <div class="col-xl-3 col-md-4 col-sm-6">
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
		                        <h4><?= $title->$language; ?></h4>
		                        <p><?= $detail->$language; ?></p>
		                    </div>
		                </a>
	                </div>
	            </div>
        	<?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>        
</div>

<script>
  $('#load-more').on('click', function(event){
    event.preventDefault();

    var btn = $(this);
    var offset = btn.data('offset');
    var sort = $('#sort option:selected').val();
    console.log(sort);
    //var next = offset + 3;

    $.ajax({
      method: "POST",
      url: "<?= base_url('visitor/gallery_load_more/').$id; ?>",
      data: {'offset':offset,'sort':sort},
      beforeSend: function(){
        $('#loading').show();
      },
      success: function(data){
        $('#loading').hide();
        console.log(data);
        var response = JSON.parse(data);
        if(response.status == 'success'){
          $('#load-here').append(response.galleryArticles);
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
  $('#sort').on('change', function(event){
    event.preventDefault();

    var sort = $('#sort option:selected').val();
    var btn = $('#load-more');
    // var offset = btn.data('offset');
    //var next = offset + 3;

    $.ajax({
      method: "POST",
      url: "<?= base_url('visitor/gallery_order/').$id; ?>",
      data: {'sort':sort},
      beforeSend: function(){
      },
      success: function(data){
        var response = JSON.parse(data);
        //console.log(response);
        if(response.status == 'success'){
          $('#load-here').html(response.galleryArticles);
          btn.data('offset', response.offset);
          btn.text('Load More '+response.offset+'/'+response.total);
          if(response.btn == false){
            btn.hide();
          }
          if(response.btn == true){
            btn.show();
          }
        }else{
          btn.hide();
        }
      }
    });
  });
</script>