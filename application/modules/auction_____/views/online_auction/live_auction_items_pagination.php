<!-- <script src="<?//= NEW_ASSETS_USER; ?>js/jquery.syotimer.js"></script> -->

<div class="products">
  <div class="row col-gap-20">
    <?php if ($live_items){ 
      $start_time = new DateTime($auction['start_time']);
      $bid_start_time = strtotime($auction['start_time']);
      $current_time= new DateTime('now');
      $time_left = $start_time->diff($current_time);
      $days = $time_left->d;
      // $sec = $time_left->s;
      $hours = $days*24 + $time_left->h;
      // print_r($time_left);
      $blink ="";
      if ($time_left->invert == 1) {
        $blink = "add_blink";
      }
      ?>
      <?php foreach ($live_items as $key => $auction_item){

        //$auction_id = $auction_item['auction_id'];

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
              <h6><a href="#"><?= $auction_item['item_name']; ?></a></h6>
              <ul>
                <?php
                $detail_points = explode(',', $auction_item['item_detail']);
                $firstThreeElements = array_slice($detail_points, 0, 3);
                foreach ($firstThreeElements as $key => $value) {
                  ?>
                  <li><?= $value; ?></li>
                  <?php
                }
                ?>
              </ul>
              <div class="button-row">
                <a href="#" class="btn btn-default sm">View Details</a>
              </div>
            </div>
            <div class="table-wrapper hide-list" class="blink_table">
              <table cellpadding="0" cellpadding="0">
                <tr>
                  <td class="<?= $blink.$auction_item['auction_item_id']; ?>">
                    <p>
                      Lot#
                      <span><?php if(isset($auction_item) && !empty($auction_item)) { echo $auction_item['order_lot_no']; }else{ echo "N/A";}?></span>
                    </p>
                  </td>
                  <td class="<?= $blink.$auction_item['auction_item_id']; ?>">
                    <p>
                     Time Remaining
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
                <tr>
                  <td>
                    <p>Odometer<span>
                      <?php if(isset($auction_item['mileage']) && !empty($auction_item['mileage'])) { echo $auction_item['mileage']; }else{
                        echo "N/A";
                        }?> 
                        <?php if(isset($auction_item['mileage_type']) && !empty($auction_item['mileage'])) { echo $auction_item['mileage_type']; }?>
                      </span></p>
                  </td>
                  <td>
                    <p>
                        Spec
                        <span>GCC</span>
                    </p>
                  </td>
                </tr>
              </table>
            </div>
            <div class="table-wrapper hide-grid"  class="blink_table">
              <table cellpadding="0" cellpadding="0">
                <tr>
                <tr>
                  <td class="<?= $blink.$auction_item['auction_item_id']; ?>">
                    <p>
                     Time Remaining
                     <span>
                       <em id="expiry-timer_list<?= $auction_item['auction_item_id']; ?>"> 
                        <script>
                          $('#expiry-timer_list<?= $auction_item['auction_item_id']; ?>').syotimer({
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
                  <td class="<?= $blink.$auction_item['auction_item_id']; ?>">
                    <p>
                        Lot#
                        <span><?php if(isset($auction_item) && !empty($auction_item)) { echo $auction_item['order_lot_no']; }else{ echo "N/A";}?></span>
                    </p>
                  </td>
                  
                </tr>
                <tr>
                  <td>
                    <p>Odometer<span>
                      <?php if(isset($auction_item['mileage']) && !empty($auction_item['mileage'])) { echo $auction_item['mileage']; }else{
                        echo "N/A";
                        }?> 
                        <?php if(isset($auction_item['mileage_type']) && !empty($auction_item['mileage'])) { echo $auction_item['mileage_type']; }?>
                    </span></p>
                  </td>
                  <td>
                    <p>
                      Spec
                      <span>GCC</span>
                    </p>
                  </td>
                </tr>
                
              </table>
              <script>
                function addblink(){
                  var thetime = '<?php echo $hours;?>';
                  // alert(thetime);
                  if (thetime < 3) {
                    console.log(thetime);
                    $('table.blink_table > tbody > tr > td.add_blink').addClass('blinking');
                    $('td.add_blink<?= $auction_item['auction_item_id']; ?>').addClass('blinking');
                  }
                }
                window.setInterval(function(){
                  addblink();
                }, 5000);      
              </script>
            </div>
            <ul class="h-list">
                <div class="actions">
                  <a href="#" class="btn btn-default sm">Bid Now</a>
                </div>
            </ul>
          </div>
        </div>
      <?php } ?>
    <?php }else{ ?>
      <h1>No item found.</h1>
    <?php } ?>
  </div>
</div>
<?php if ($pagination_links): ?>
  <div class="pager">
    <div class="row align-items-center">
      <div class="col-md-9">
        <?= $pagination_links;  ?>
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

<script>
  var token_name = '<?= $this->security->get_csrf_token_name();?>';
  var token_value = '<?=$this->security->get_csrf_hash();?>';
$('.live_pagination_link').on('click', 'a',function(r){
  r.preventDefault();
  var page_hr = $(this).attr('href');
  var btn = $(this);
  var auction_id = $(this).closest( "li" ).attr('data-auc-id');
  var offset = page_hr.replace('#/', '');
  var search = $('#search').val();
  // alert('click');
  $.ajax({
      url: '<?= base_url() ?>' + 'auction/OnlineAuction/live_auction_items_pagination/'+offset,
      type: 'POST',
      data: {offset:offset, auction_id:auction_id, search:search, [token_name]:token_value},
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

</script>