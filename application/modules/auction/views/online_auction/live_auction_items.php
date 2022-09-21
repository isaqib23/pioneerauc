<!-- <script src="https://code.jquery.com/jquery-3.5.1.js"></script> -->
<!-- <script src="<?//= base_url() ?>assets_user/js/bootstrap-select.min.js"></script> -->
<script src="<?= NEW_ASSETS_USER; ?>js/jquery.syotimer.js"></script> 

<div class="products">

  <?php foreach ($live_auctions as $key => $live_auction):

    $auction_id =$live_auction['id'];
    $title = json_decode($live_auction['title']);

    $auction_start_time = date('H:i l d F Y' , strtotime($live_auction['start_time']));
    $date = date('Y-m-d H:i:s');
  ?>

  <div class="live-banner">
    <div class="logo">
      <img src="<?= NEW_ASSETS_IMAGES?>/pioneer-logo.png" alt="">
    </div>
    <div class="desc">
      <h3>
        <a href="#stock<?= $live_auction['id'] ?>"><?= $title->$language; ?><!-- VEHICLES — TUESDAY LIVE VEHICLE AUCTION --></a>
      </h3>
      <ul>
        <li><?= $this->lang->line('auction_location');?>: <span><?= $this->lang->line('dubai');?></span></li>
        <li><?= $this->lang->line('date');?>: <span dir="ltr"><?= $auction_start_time; ?><!-- 19:00 Tuesday 25 August 2020 --></span></li>
      </ul>
    </div>
    <div class="buttons">
      <a href="#" class="btn-default">
        <i class="fa fa-print"></i> <?= $this->lang->line('print_c');?>
      </a>

      <?php if($live_auction['start_status'] == 'start'){ ?>
        <a href="<?= base_url('live-online/').$live_auction['id']; ?>" class="btn-default">
          <i class="fa fa-check-circle"></i> <?= $this->lang->line('open_live');?>
        </a>
      <?php } ?>

    </div>
  </div>
<?php endforeach; ?>

</div>

<div class="stocks-wrapper">
  <?php $q = 0;
  foreach ($live_auctions as $key => $live_auction):
    $q++;

    $auction_id =$live_auction['id'];
    $heading_title = json_decode($live_auction['title']);

    $sold = $this->db->get_where('auction_items',['sold_status' =>'sold'])->result_array();
    $bid_start_time = strtotime($live_auction['start_time']);

    ?>
    <div class="stocks" id="stock<?= $live_auction['id'] ?>" style="<?= ($q == 1) ? 'display: block' : ''; ?>" >
      <!-- <div class="filter-head">
        <div class="row">
          <div class="col-md-12">
            <h3>Stock of <?= strtoupper($heading_title->english); ?></h3>
          </div>
        </div>
      </div> -->
      <div class="pg-div<?= $auction_id; ?>">
        <div class="products">
          <div class="row col-gap-20">
            <?php if (isset($live_auction['auction_items']) && !empty($live_auction['auction_items'])): ?>
              <?php foreach ($live_auction['auction_items'] as $key => $auction_item):

                $auction_id =$auction_item['auction_id'];

                $sold = $this->db->get_where('auction_items',['sold_status' =>'sold'])->result_array();
                $item_images_ids = explode(',', $auction_item['item_images']);
                $images = $this->db->where_in('id', $item_images_ids)->get('files')->result_array();
                if($images){
                  $image_src = base_url('uploads/items_documents/').$auction_item['item_id'].'/'.$images[0]['name'];
                }else{
                  $image_src = '';
                }
                ?>
                <div class="col-md-4">
                  <div class="pro-box">
                    <div class="image">
                      <img src="<?= $image_src; ?>" alt="">
                    </div>
                    <div class="desc" >
                      <h6><a href="#"><?php $item_name = json_decode($auction_item['item_name']); echo $item_name->$language; ?><!-- Lexus LX 570 2016  --></a></h6>
                      <ul>
                        <?php
                        $item_detail = json_decode($auction_item['item_detail']);
                        $detail_points = explode(',', $item_detail->$language);
                        $firstThreeElements = array_slice($detail_points, 0, 2);
                        foreach ($firstThreeElements as $key => $value) {
                          if (!empty($value)) {
                            ?>
                            <li><?= $value; ?></li>
                            <?php
                          }
                        }
                        ?>
                      </ul>
                      <div class="button-row">
                        <a href="<?= base_url('live-online-detail/').$auction_id.'/'.$auction_item['item_id']; ?>" class="btn btn-default sm"><?= $this->lang->line('see_detail');?></a>
                      </div>
                    </div>
                    <?php 
                      $expiry_date = new DateTime($live_auction['start_time']);
                      $current_time= new DateTime('now');
                      $time_left = $expiry_date->diff($current_time);
                      $days = $time_left->d;
                      // $sec = $time_left->s;
                      $hours = $days*24 + $time_left->h;
                      $blink ="";
                      if ($time_left->invert == 1) {
                        $blink = "add_blink";
                      }
                    ?>
                    <div class="table-wrapper hide-list" class="blink_table">
                      <table cellpadding="0" cellpadding="0">
                        <?php 
                        $sort_fields = $this->db->select('sort_catagories.*, item_category_fields.label as category_title, item_fields_data.value as category_data')
                        // , item_fields_data.value as category_data
                        ->join('item_category_fields', 'sort_catagories.field_id = item_category_fields.id', 'LEFT')
                        ->join('item_fields_data', 'sort_catagories.field_id = item_fields_data.fields_id', 'LEFT')
                        ->get_where('sort_catagories', ['sort_catagories.category_id' => $live_auctions[0]['category_id'], 'item_fields_data.item_id' =>  $auction_item['item_id']]);

                        if ($sort_fields->num_rows() > 0) {
                          $sort_fields = $sort_fields->result_array(); 
                          // print_r($sort_fields);die();
                          ?>

                          <tr>
                            <td>
                              <p>
                                <?php if (isset($sort_fields[0]['category_title'])) { echo $this->template->make_dual($sort_fields[0]['category_title']); } ?>
                                <span><?php if (isset($sort_fields[0]['category_data'])) { echo $this->template->make_dual($sort_fields[0]['category_data']); } ?></span>
                              </p>
                            </td>
                            <td>
                              <p>
                                <?php if (isset($sort_fields[1]['category_title'])) { echo $this->template->make_dual($sort_fields[1]['category_title']); } ?>
                                <span><?php if (isset($sort_fields[1]['category_data'])) { echo $this->template->make_dual($sort_fields[1]['category_data']); } ?></span>
                              </p>
                            </td>
                          </tr>

                          <tr>
                            <td>
                              <p>
                                <?php if (isset($sort_fields[2]['category_title'])) { echo $this->template->make_dual($sort_fields[2]['category_title']); } ?>
                                <span><?php if (isset($sort_fields[2]['category_data'])) { echo $this->template->make_dual($sort_fields[2]['category_data']); } ?></span>
                              </p>
                            </td>
                            <td>
                              <p>
                                <?php if (isset($sort_fields[3]['category_title'])) { echo $this->template->make_dual($sort_fields[3]['category_title']); } ?>
                                <span><?php if (isset($sort_fields[3]['category_data'])) { echo $this->template->make_dual($sort_fields[3]['category_data']); } ?></span>
                              </p>
                            </td>
                          </tr>
                        <?php } ?>

                        <tr>
                          <td class="<?= $blink.$auction_item['auction_item_id']; ?>">
                            <p>
                              <?= $this->lang->line('lot');?>#
                              <span><?php if(isset($auction_item) && !empty($auction_item)) { echo $auction_item['order_lot_no']; }else{ echo "N/A";}?></span>
                            </p>
                          </td>
                          <td class="<?= $blink.$auction_item['auction_item_id']; ?>">
                            <p>
                             <?= $this->lang->line('remain_time');?>
                             <span>
                              <em id="expiry-timer<?= $auction_item['auction_item_id']; ?>"> 
                                <script>
                                  $('#expiry-timer<?= $auction_item['auction_item_id']; ?>').syotimer({
                                  year: <?= date('Y', $bid_start_time); ?>,
                                  month: <?= date('m', $bid_start_time); ?>,
                                  day: <?= date('d', $bid_start_time); ?>,
                                  hour: <?= date('H', $bid_start_time); ?>,
                                  minute: <?= date('i', $bid_start_time); ?>,
                                });
                                </script>
                              </em>
                             </span>
                            </p>
                          </td>
                        </tr>
                        <?php $auction = $this->db->get_where('item_category', ['id' => $auction_item['category_id']])->row_array(); 
                        if ($auction['include_make_model'] == 'yes') {
                        ?>
                          <tr>
                            <td>
                              <p><?= $this->lang->line('odometer');?><span>
                                <?php if(isset($auction_item['mileage']) && !empty($auction_item['mileage'])) { echo $auction_item['mileage']; }else{
                                  echo "N/A";
                                  }?> 
                                  <?php if(isset($auction_item['mileage_type']) && !empty($auction_item['mileage'])) { echo $auction_item['mileage_type']; }?>
                                </span></p>
                            </td>
                            <td>
                              <p>
                                  <?= $this->lang->line('specs');?>
                                  <span><?= $auction_item['specification']; ?></span>
                              </p>
                            </td>
                          </tr>
                        <?php } ?>
                        <!-- <tr>
                          <td>
                            <p># of Bids<span>78</span></p>
                          </td>
                          <td>
                            <p>
                                Lot#
                                <span>965387</span>
                            </p>
                          </td>
                        </tr> -->
                      </table>
                    </div>
                    <div class="table-wrapper hide-grid"  class="blink_table">
                      <table cellpadding="0" cellpadding="0">
                        <?php if (!empty($sort_fields)) {
                          ?>

                          <tr>
                            <td>
                              <p>
                                <?php if (isset($sort_fields[0]['category_title'])) { echo $this->template->make_dual($sort_fields[0]['category_title']); } ?>
                                <span><?php if (isset($sort_fields[0]['category_data']) ){ echo $this->template->make_dual($sort_fields[0]['category_data']); } ?></span>
                              </p>
                            </td>
                            <td>
                              <p>
                                <?php if (isset($sort_fields[1]['category_title'])) { echo $this->template->make_dual($sort_fields[1]['category_title']); } ?>
                                <span><?php if (isset($sort_fields[1]['category_data'])) { echo $this->template->make_dual($sort_fields[1]['category_data']); } ?></span>
                              </p>
                            </td>
                          </tr>

                          <tr>
                            <td>
                              <p>
                                <?php if (isset($sort_fields[2]['category_title'])) { echo $this->template->make_dual($sort_fields[2]['category_title']); } ?>
                                <span><?php if (isset($sort_fields[2]['category_data'])) { echo $this->template->make_dual($sort_fields[2]['category_data']); } ?></span>
                              </p>
                            </td>
                            <td>
                              <p>
                                <?php if (isset($sort_fields[3]['category_title'])) { echo $this->template->make_dual($sort_fields[3]['category_title']); } ?>
                                <span><?php if (isset($sort_fields[3]['category_data'])) { echo $this->template->make_dual($sort_fields[3]['category_data']); } ?></span>
                              </p>
                            </td>
                          </tr>
                        <?php } ?>
                        <tr>
                          <td class="<?= $blink.$auction_item['auction_item_id']; ?>">
                            <p>
                             <?= $this->lang->line('remain_time');
                                $time= $auction_item['bid_end_time'];
                                $new_time = date('h:i A j M Y', strtotime($time));
                             ?>
                             <span>
                               <em id="expiry-timer_list<?= $auction_item['auction_item_id']; ?>"> 
                                <b class="ltr"> <?php if(isset($new_time) && !empty($new_time)) { echo $new_time; }?></b>
                                <!-- <script>
                                  $('#expiry-timer_list<?= $auction_item['auction_item_id']; ?>').syotimer({
                                  year: <?= date('Y', $bid_start_time); ?>,
                                  month: <?= date('m', $bid_start_time); ?>,
                                  day: <?= date('d', $bid_start_time); ?>,
                                  hour: <?= date('H', $bid_start_time); ?>,
                                  minute: <?= date('i', $bid_start_time); ?>,
                                });
                                </script> -->
                              </em>
                             </span>
                            </p>
                          </td>
                          <td class="<?= $blink.$auction_item['auction_item_id']; ?>">
                            <p>
                                <?= $this->lang->line('lot');?>#
                                <span><?php if(isset($auction_item) && !empty($auction_item)) { echo $auction_item['order_lot_no']; }else{ echo "N/A";}?></span>
                            </p>
                          </td>
                          
                          <!-- <td>
                            <p>
                              End Time
                              <span>
                               <b>6:00 <i>pm</i></b>
                               <b>12 Jul 2020</b>
                              </span>
                            </p>
                          </td> -->
                        </tr>
                        <?php //$auction = $this->db->get_where('item_category', ['id' => $auction_item['category_id']])->row_array(); 
                        if ($auction['include_make_model'] == 'yes') {
                        ?>
                          <tr>
                            <td>
                              <p><?= $this->lang->line('odometer');?><span>
                                <?php if(isset($auction_item['mileage']) && !empty($auction_item['mileage'])) { echo $auction_item['mileage']; }else{
                                  echo "N/A";
                                  }?> 
                                  <?php if(isset($auction_item['mileage_type']) && !empty($auction_item['mileage'])) { echo $auction_item['mileage_type']; }?>
                              </span></p>
                            </td>
                            <td>
                              <p>
                                <?= $this->lang->line('specs');?>
                                <span><?= $auction_item['specification']; ?></span>
                              </p>
                            </td>
                          </tr>
                        <?php } ?>
                        <!-- <tr>
                          <td>
                            <p># of Bids<span>78</span></p>  
                          </td>
                          <td>
                            <p>
                                Lot#
                                <span>965387</span>
                            </p>
                          </td>
                        </tr> -->
                      </table>
                      <script>
                        var include_make_model = "<?= $auction['include_make_model']; ?>"
                        if (include_make_model == 'no') {
                          $('.pro-box').addClass('small');
                        } 

                        var thetime = '<?php echo $hours;?>';
                        // alert(thetime);
                        if (thetime < 3) {
                          console.log(thetime); 
                          // $('table.blink_table > tbody > tr > td.add_blink').addClass('blinking');
                          // $('td.add_blink<?//= $auction_item['auction_item_id']; ?>').addClass('blinking');
                        }
                      </script>
                    </div>
                    <?php
                    $deadline = date('Y-m-d H:i:s', strtotime("-30 minutes", strtotime($live_auction['start_time'])));
                     if (date('Y-m-d H:i:s') < $deadline) { ?>
                      <ul class="h-list">
                        <!-- <li> -->
                          <div class="actions">
                            <!-- <a href="#" class="btn btn-primary sm">Add List</a> -->
                            <a href="<?= base_url('live-online-detail/').$auction_id.'/'.$auction_item['item_id']; ?>" class="btn btn-default sm"><?= $this->lang->line('bid_now');?></a>
                          </div>
                        <!-- </li> -->
                      </ul>
                    <?php } ?>
                  </div>
                </div>
              <?php endforeach; ?>
            <?php else: ?>
              <h1><?= $this->lang->line('no_record');?></h1>
            <?php endif; ?>
          </div>
        </div>
        <?php if (isset($live_auction['pagination_links']) && !empty($live_auction['pagination_links'])) : ?>
          <div class="pager">
            <div class="row align-items-center">
              <div class="col-md-9">
                <?= $live_auction['pagination_links'];  ?>
              </div>
              <!-- <div class="col-md-3">
                <div class="text-right">
                  <select class="selectpicker" >
                    <option>26 results per page</option>
                    <option>10 results per page</option>
                    <option>20 results per page</option>
                  </select>
                </div>
              </div> -->
            </div>
          </div>
        <?php endif; ?>
      </div>  
    </div>

    
  <?php endforeach; ?>
</div>



<script>
  var token_name = '<?= $this->security->get_csrf_token_name();?>';
  var token_value = '<?=$this->security->get_csrf_hash();?>';
  
  $('.live_pagination_link').on('click', 'a',function(r){
    r.preventDefault();
    var page_hr = $(this).attr('href');
    var btn = $(this);
    var auction_id = $(this).closest( "li" ).attr('data-auc-id');
    var offset = page_hr.replace('#/', '');
    var form = $(this).closest('form').serializeArray();
    // alert('click');
    form[form.length] = { name: "auction_id", value: auction_id, [token_name]:token_value};
    $.ajax({
        url: '<?= base_url() ?>' + 'auction/OnlineAuction/live_auction_items_pagination/'+offset,
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
          $( ".pg-div"+auction_id).html(''); 
          $( ".pg-div"+auction_id).html(objData.items);
        }
    });
});


	//ratings
  $( document ).ready(function() {

  	/*$('.rating'). ({
  	  click: function(score, evt) {
  	  	var aiid = $(this).attr('data-aiid');
  	    $.ajax({
    			method: "POST",
    			url: "<?= base_url('auction/OnlineAuction/ratings'); ?>",
    			data: {'ratings':score, 'auction_item_id':aiid},
    			success: function(data){
    				//console.log(data);
    				if(data == '1'){
            			console.log('1');
    	        	}
    	        	if(data == '0'){
    	        		window.location.replace('<?= base_url("home/login"); ?>');
    	        	}
    			}
  		  });
  	  },
  	  score: function() {
  	    return $(this).attr('data-score');
  	  }
  	});*/
  });

</script>