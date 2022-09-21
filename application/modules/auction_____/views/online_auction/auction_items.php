<!-- <link rel="stylesheet" href="<?//= base_url() ?>assets_user/css/bootstrap-select.min.css"> -->
<!-- <script src="https://code.jquery.com/jquery-3.5.1.js"></script> -->
<!-- <script src="<?//= base_url() ?>assets_user/js/bootstrap-select.min.js"></script> -->
<script src="<?= NEW_ASSETS_USER; ?>js/jquery.syotimer.js"></script> 

<div class="products">

  <div class="row col-gap-20" >
    <?php 
    foreach ($auction_items as $key => $auction_item):

      // print_r($auction_item['order_lot_no']);
    	// print_r($auction_items);
      $auction_id =$auction_item['auction_id'];
      $category_id =$auction_item['category_id'];
    	$visit_count = $this->db->where('item_id',$auction_item['item_id'])->where('auction_id',$auction_item['auction_id'])->from('online_auction_item_visits')->count_all_results();
    	$bid_info = $this->db->where('item_id',$auction_item['item_id'])->where('auction_id',$auction_item['auction_id'])->order_by('id', 'desc')->from('bid')->get()->result_array();
    	$count = count($bid_info);

    	$sold = $this->db->get_where('auction_items',['sold_status' =>'sold'])->result_array();
    	$bid_end_time = strtotime($auction_item['bid_end_time']);
    	// $time = explode(" ", $auction_item['bid_end_time']);
    	// $date_new = $auction_item['bid_end_time'];
    	// $time = date('h:i A', strtotime($date_new));
    	// $time_new = explode(" ", $date_new);
    	// print_r($time_new);
    	$item_images_ids = explode(',', $auction_item['item_images']);
    	$images = $this->db->where_in('id', $item_images_ids)->get('files')->result_array();
    	if($images){
    		$image_src = base_url('uploads/items_documents/').$auction_item['item_id'].'/'.$images[0]['name'];
    	}else{
    		$image_src = base_url('assets_admin/images/product-default.jpg');
    	}
    	
    	$ratings = 0;
    	$user = $this->session->userdata('logged_in');
        if($user){
    		$ratings = $this->db->get_where('auction_item_ratings', ['auction_item_id' => $auction_item['auction_item_id'],'user_id' => $user->id])->row_array();
    		if($ratings){
    			$ratings = $ratings['ratings'];
    		}else{
    			$ratings = 0;
    		}
    	}

    	$favorite = $this->lang->line('add_list');
    	$user = $this->session->userdata('logged_in');
        if($user){
    		$favt = $this->db->get_where('favorites_items', ['item_id' => $auction_item['item_id'],'user_id' => $user->id])->row_array();
    		if($favt){
    			$favorite = $this->lang->line('remove_list');
    		}else{
    			$favorite = $this->lang->line('add_list');
    		}
    	}

      $time = $auction_item['bid_end_time'];
      $date = date('Y-m-d H:i:s');
    ?>

    <style>
      .pro-box em {display: block; font-style: normal;}
      .pro-box .syotimer__body {display: flex; flex-wrap: wrap; justify-content: center; margin-top: 4px; padding: 0;}
    </style>

    <div class="col-md-4">
      <div class="pro-box">
        <div class="image">
          <img id="iimg" src="<?= $image_src; ?>" >
        </div>
        <div class="desc" >
          <?php
          if ($time > $date){?>
            <h6><a href="<?= base_url('auction/OnlineAuction/item_detail/'.$auction_item['auction_id'].'/'.$auction_item['item_id']); ?>"></h6>
          <?php }?>
          <h6><?php $item_name = json_decode($auction_item['item_name']); echo $item_name->$language; ?></h6>
          <!-- <p><?php //$auction_item['item_detail']; ?></p> -->
          <ul>
            <?php
            $item_detail = json_decode($auction_item['item_detail']);
            $detail_points = explode(',', $item_detail->$language);
            $firstThreeElements = array_slice($detail_points, 0, 2);
            foreach ($firstThreeElements as $key => $value) {
              ?>
              <li><?php 
              if ($language == 'english') {
                if (strlen($value) > 50) {
                  $stringCut = substr($value, 0, 50);
                  echo $stringCut.' ...';
                  echo $value;
                } else{ echo $value; } 
              } else { echo $value; } ?></li>
              <?php
            }
            ?>
          </ul>

          <?php if ($time > $date) {?>
            <div class="button-row">
              <a href="<?= base_url('auction/online-auction/details/').$auction_item['auction_id'].'/'.$auction_item['item_id']; ?>" class="btn btn-default sm"><?= $this->lang->line('see_detail');?></a>
            </div>
          <?php }else{?>
            <div class="button-row">
              <a href="javascript:void(0)" class="btn btn-default sm"><?= $this->lang->line('see_detail');?></a>
            </div>
          <?php }?>
        </div>
        <?php 
        $expiry_date = new DateTime($auction_item['bid_end_time']);
        $current_time= new DateTime('now');
        $time_left = $expiry_date->diff($current_time);
        $days = $time_left->d;
        // $sec = $time_left->s;
        $hours = $days*24 + $time_left->h;
        // print_r($time_left);
        $blink ="";
        if ($time_left->invert == 1) {
          $blink = "add_blink";
        }
        ?>
        <div class="table-wrapper hide-list">
          <table cellpadding="0" cellpadding="0" class="blink_table">
            <tr>
              <td class="<?= $blink; ?>">
                <p>
                  <?= $this->lang->line('c_price');?>
                  <span>AED <?= (!empty($bid_info)) ? $bid_info['0']['bid_amount'] : $auction_item['bid_start_price']; ?></span>
                </p>
             
              </td>
              <td class="<?= $blink; ?>">
                <p class="ltr"><?= $this->lang->line('remain_time');?>
                  <div class="expiryy-timer<?= $auction_item['auction_item_id']; ?>"> 
                  </div>
                </p>
              </td>
            </tr>
            <tr>
              <td>
                <p>
                 <?= $this->lang->line('min_increment');?>
                  <span>AED <?= $auction_item['minimum_bid_price'];?></span>
                </p>
              </td>
              <td class="ltr">
              	<?php
                  $time= $auction_item['bid_end_time'];
                  $new_time = date('h:i A j M Y', strtotime($time));
                ?> 
                <p>
                   <?= $this->lang->line('end_time');?>
                   <span>
                       <b class="ltr"> <?php if(isset($new_time) && !empty($new_time)) { echo $new_time; }?></b>
                       <!-- <b>6:00 <i>pm</i></b>
                       <b>12 Jul 2020</b> -->
                   </span>
                </p>
              </td>
            </tr>
            <?php $auction = $this->db->get_where('item_category', ['id' => $auction_item['category_id']])->row_array(); 
            if ($auction['include_make_model'] == 'yes') {
            ?>
              <tr>
                <td>
                  <p>      
                    <?= $this->lang->line('odometer');?>
                    <span><?php if(isset($auction_item['mileage']) && !empty($auction_item['mileage'])) { echo $auction_item['mileage']; }else{
                          echo "N/A";
                        }?> 
                        <?php if(isset($auction_item['mileage_type']) && !empty($auction_item['mileage'])) { echo $auction_item['mileage_type']; }?>
                    </span>
                  </p>
                </td>
                <td>
                  <p>
                    <?php 
                    $specs = $this->db->get_where('item', ['id' => $auction_item['item_id']])->row_array();
                    ?>
                    <?= $this->lang->line('specs');?>
                    <span><?= $specs['specification']; ?></span>
                  </p>
                </td>
              </tr>
            <?php } else {?>
              <script >
                $('.pro-box').addClass('small');
              </script>
            <?php } ?>
            <tr>
              <td>
                <p>      
                  <?= $this->lang->line('bids');?> #
                  <span><?php if(isset($count) && !empty($count)) { echo $count; }else{
                    echo "0";
                  }?></span>
                </p>
              </td>
              <td>
                <p>
                  <?= $this->lang->line('lot');?>#
                  <span><?php if(isset($auction_item) && !empty($auction_item)) { echo $auction_item['order_lot_no']; } else{ echo "N/A";}?></span>
                </p>
              </td>
            </tr>
          </table>
        </div>
        <div class="table-wrapper hide-grid">
          <table cellpadding="0" cellpadding="0" class="blink_table">
            <tr>
              <td class="<?= $blink.$auction_item['auction_item_id'];?>">
                <p>
                  <?= $this->lang->line('c_price');?>
                  <span>AED <?= (!empty($bid_info)) ? $bid_info['0']['bid_amount'] : $auction_item['bid_start_price']; ?></span>
                </p>
              </td>
              <td>
                <p>
                  <?= $this->lang->line('min_increment');?>
                  <span>AED <?= $auction_item['minimum_bid_price'];?></span>
                </p>
              </td>
            </tr>
            <tr>
              <td class="<?= $blink.$auction_item['auction_item_id']; ?>">
                <p><?= $this->lang->line('remain_time');?>
                  <div class="expiryy-timer<?= $auction_item['auction_item_id']; ?>"> 
                  </div>
                </p>
              </td>
              <script>
                var thetime = '<?php echo $hours;?>';
                // alert(thetime);
                if (thetime < 3) {
                  console.log(thetime);
                  // $('table.blink_table > tbody > tr > td.add_blink').addClass('blinking');
                  $('td.add_blink<?= $auction_item['auction_item_id']; ?>').addClass('blinking');
                }      
              </script>
              <td>
                <?php
                  $time= $auction_item['bid_end_time'];
                  $new_time = date('h:i A j M Y', strtotime($time));
                ?>
                <p>
                  <?= $this->lang->line('end_time');?>
                  <span>
                    <b class="ltr"> <?php if(isset($new_time) && !empty($new_time)) { echo $new_time; }?></b>
                      <!-- <b>6:00 <i>pm</i></b>
                     <b>12 Jul 2020</b>  -->
                  </span>
                </p>
              </td>
            </tr>
            <?php $auction = $this->db->get_where('item_category', ['id' => $auction_item['category_id']])->row_array(); 
            if ($auction['include_make_model'] == 'yes') {
            ?>
              <tr>
                <td>
                  <p>
                    <?= $this->lang->line('odometer');?>
                    <span>
                      <?php if(isset($auction_item['mileage']) && !empty($auction_item['mileage'])) { echo $auction_item['mileage']; }else{
                        echo "N/A";
                      }?> 
                      <?php if(isset($auction_item['mileage_type']) && !empty($auction_item['mileage'])) { echo $auction_item['mileage_type']; }?>
                    </span>
                  </p>
                </td>
                <td>
                  <p>
                    <?php 
                    $specs = $this->db->get_where('item', ['id' => $auction_item['item_id']])->row_array();
                    ?>
                    <?= $this->lang->line('specs');?>
                    <span><?= $specs['specification'];?></span>
                  </p>
                </td>
              </tr>
            <?php  }?>
            <tr>
              <td>
                <!-- <p>
                  Odometer
                  <span>
                    <?php if(isset($auction_item['mileage']) && !empty($auction_item['mileage'])) { echo $auction_item['mileage']; }else{
                      echo "N/A";
                    }?> 
                    <?php if(isset($auction_item['mileage_type']) && !empty($auction_item['mileage'])) { echo $auction_item['mileage_type']; }?>
                  </span>
                </p> -->
                <p>
                  <?= $this->lang->line('bids');?> #
                  <span>
                    <?php if(isset($count) && !empty($count)) { echo $count; }else{
                      echo "0";
                    }?> 
                  </span>
                </p>
              </td>
              <td>
                <p>
                  <?= $this->lang->line('lot');?>#
                  <span><?php if(isset($auction_item) && !empty($auction_item)) { echo $auction_item['order_lot_no']; }else{ echo "N/A";}?></span>
                </p>
              </td>
            </tr>
          </table>
        </div>
        <ul class="h-list">
          <!-- <li> -->
            <!-- <p>Bids#<span><?//=  (!empty($count)) ? $count : '0'; ?></span></p> -->
          <!-- </li> -->
          <!-- <li> -->
            <?php 
            if ($time > $date) {?>
              <div class="actions">
                <a onclick="doFavt(this)" data-item="<?= $auction_item['item_id']; ?>" data-auction-id="<?= $auction_item['auction_id']; ?>" data-auction-item-id="<?= $auction_item['auction_item_id']; ?>" href="javascript:void(0);" class="btn btn-primary sm">
                  <?= $favorite; ?></a>
                </a>
                <a href="<?= base_url('auction/online-auction/details/').$auction_item['auction_id'].'/'.$auction_item['item_id']; ?>" class="btn btn-default sm"><?= $this->lang->line('bid_now');?></a>
              </div>
            <?php }else{?>
              <a href="javascript:void(0)"  class="btn btn-default sm"><?= $this->lang->line('expired');?></a>
            <?php }?>
          <!-- </li> -->
        </ul>
      </div>
    </div>

    <script>
      $('.expiryy-timer<?= $auction_item['auction_item_id']; ?>').syotimer({
        year: <?= date('Y', $bid_end_time); ?>,
        month: <?= date('m', $bid_end_time); ?>,
        day: <?= date('d', $bid_end_time); ?>,
        hour: <?= date('H', $bid_end_time); ?>,
        minute: <?= date('i', $bid_end_time); ?>
      });
    </script>
    <?php endforeach; ?>
  </div>
</div>
<?php if ($pagination_links) : ?>
  <div class="pager">
    <div class="row align-items-center">
      <div class="col-md-9">
        <?= $pagination_links;  ?>
      </div>
      <!-- <div class="col-md-3">
        <div class="text-right">
          <select id="rows_per_page" class="selectpicker" >
            <option value="1"  >9 results per page</option>
            <option value="12"  >12 results per page</option>
            <option value="24" >24 results per page</option>
            <option value="48"  >48 results per page</option>
          </select>
        </div>
      </div> -->
    </div>
  </div>
<?php endif; ?>

<script>
  var token_name = '<?= $this->security->get_csrf_token_name();?>';
  var token_value = '<?=$this->security->get_csrf_hash();?>';

  function addblink1(){
    console.info('<?= $hours; ?>');
    var hours = '<?= $hours; ?>';
    if (hours < 3) {
      console.info('fuck');
      $('table.blink_table > tbody > tr > td.add_blink').addClass('blinking');
      $('td.add_blink').addClass('blinking');
    }
  }

  //online auction pagination
  $('.pagination_link').on('click', 'a',function(e){
    e.preventDefault();
    var page_hr = $(this).attr('href');  
    var offset = page_hr.replace('#/', ''); 
    var rows_per_page = $('#rows_per_page').children("option:selected").val();
    var form = $('form').serializeArray();
      $.ajax({
      url: '<?= base_url() ?>' + 'auction/OnlineAuction/ai_load_more/'+offset,
      type: 'POST',
      data: form,
      beforeSend: function(){ 
        // $('.oz-main-content-div').html('<img style="" src="<?//= ASSETS_USER; ?>images/loading.gif" />'); 
      },
      }).then(function(data) {
        var objData = jQuery.parseJSON(data); 
        console.log(objData);
        // alert(objData);
        console.log('objData');
        if (objData.status == 'success') 
        {
          $('#load-here').html(''); 
          $('#load-here').html(objData.items);
        }
      });
  });

  //close auction pagination
  $('.close_pagination_link').on('click', 'a',function(e){
    e.preventDefault();
    var page_hr = $(this).attr('href'); 
    var offset = page_hr.replace('#/', ''); 
    var rows_per_page = $('#rows_per_page').children("option:selected").val();
    var form = $('form').serializeArray();
    var category_id = '<?= $category_id; ?>';
    form[form.length] = { name: "category_id", value: category_id, [token_name]:token_value};
      $.ajax({
      url: '<?= base_url() ?>' + 'auction/OnlineAuction/close_auction_items_list/'+offset,
      type: 'POST',
      data: form,
      beforeSend: function(){ 
        // $('.oz-main-content-div').html('<img style="" src="<?//= ASSETS_USER; ?>images/loading.gif" />'); 
      },
      }).then(function(data) {
        var objData = jQuery.parseJSON(data); 
        console.log(objData);
        // alert(objData);
        console.log('objData');
        if (objData.status == 'success') 
        {
          $('#load-close-data-here').html(''); 
          $('#load-close-data-here').html(objData.items);
        }
      });
  });

	//ratings
  $( document ).ready(function() {
    // var fav = "<?php echo $favorite?>";
    // alert(fav);
  	// $('.eye').tooltip({
   //    title:'users visit this item!',
   //    placement:'bottom'
  	// });

  	// $('.fa-heart-o').tooltip({
   //    title:'Add to favorite!',
   //    placement:'bottom'
  	// });

  	// $('.fa-heart').tooltip({
   //    title:'Remove from favorite!',
   //    placement:'bottom'
   //  });



  	/*$('.rating'). ({
  	  click: function(score, evt) {
  	  	var aiid = $(this).attr('data-aiid');
  	    $.ajax({
    			method: "POST",
    			url: "<?//= base_url('auction/OnlineAuction/ratings'); ?>",
    			data: {'ratings':score, 'auction_item_id':aiid},
    			success: function(data){
    				//console.log(data);
    				if(data == '1'){
            			console.log('1');
    	        	}
    	        	if(data == '0'){
    	        		window.location.replace('<?//= base_url("home/login"); ?>');
    	        	}
    			}
  		  });
  	  },
  	  score: function() {
  	    return $(this).attr('data-score');
  	  }
  	});*/
  });
  // $('.syotimer').syotimer();

</script>