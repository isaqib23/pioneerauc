<table id="datatable-responsive_2" class="table table-striped jambo_table bulk_action" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>
                        <input type="checkbox" id="check-all" class="flat ozproceed_check" name="table_records">
                    </th>
                    <th>Name</th>
                    <th>Auction Type</th>
                    <th>Auction Category</th>
                    <th>Start Date</th>
                    <th>Expiry Date</th>
                    <th>Status</th>
                    <th data-orderable="false">Actions</th>
                </tr>
            </thead>
            <tbody class="">
<?php 
            if(isset($auction_list) && !empty($auction_list)){
                $j = 0;
                $role = $this->session->userdata('logged_in')->role; 
                $CI =& get_instance();
                
                // print_r($auction_list);

                foreach($auction_list as $value){
                    $j++;
                    $b64_auction_id = urlencode(base64_encode($value['id']));
                    $result_if_already = $CI->auction_model->get_auction_item_ids($value['id']);
                    foreach ($result_if_already as $key => $item_id) {
                        $result_if_already_item_ids[] = $item_id['item_id'];
                    }
                    $result_if_already_implode = implode(',', $result_if_already_item_ids);
                    if(isset($result_if_already) && !empty($result_if_already))
                    {
                        $if_items = 'btn-success';
                    }else{
                        $if_items = 'btn-warning';
                    }
                    ?>
                    <tr id="row-<?php echo  $value['id']; ?>">
                        <td class="a-center ">
                        <input type="checkbox" class="flat multiple_rows ozproceed_check" id="<?php echo  $value['id']; ?>" name="table_records"  value="<?php echo  $value['id']; ?>">
                        </td>
                        <td><?php $title= json_decode($value['title']); echo $title->english; ?></td>
                        <td><?php echo (isset($value['access_type']) && !empty($value['access_type'])) ? $value['access_type'] : ''; ?></td>
                        <td><?php $auctionInfoo = json_decode($value['category_name']); echo (isset($value['category_name']) && !empty($value['category_name'])) ? $auctionInfoo->english : ''; ?></td>
                        <td><?php echo (isset($value['start_time']) && !empty($value['start_time'])) ? $value['start_time'] : ''; ?></td>
                        <td><?php echo (isset($value['expiry_time']) && !empty($value['expiry_time'])) ? $value['expiry_time'] : ''; ?></td>
                        <td><?php echo $value['status']; ?></td>
                        <td>
                            <?php if($user['role'] == 1){ ?>
                                <!-- only admin can access these links -->
                                <a href="<?php echo base_url().'auction/update_live_auction/'.$value['id']; ?>" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> Edit</a>
                                <?php if ($value['expiry_time'] > date('Y-m-d H:i:s')) : ?>
                                    <button type="button" onclick="oz_stock_list(this)" id="<?php echo $value['id']; ?>" data-toggle="modal" data-target=".bs-example-modal-sm2"  data-backdrop="static" data-keyboard="true" class="btn btn-info btn-xs oz_stock_list"><i class="fa fa-plus"></i> Add Item</button>
                                <?php endif; ?>

                                <a href="<?php echo base_url().'auction/liveitems/'.$b64_auction_id; ?>" class="btn <?php echo  $if_items; ?> btn-xs"><i class="fa fa-search"></i> View Items</a>
                            <?php } ?>

                            <a href="<?php echo base_url('auction/printCatalog/').$value['id']; ?>" target="_blank" class="btn btn-info btn-xs"><i class="fa fa-file"></i> Catalog</a>
                            
                            <?php if($if_items =='btn-success' && ($value['expiry_time'] > date('Y-m-d H:i:s'))){?>
                                <a href="<?php echo base_url().'auction/auctionDeposit/'.$value['id'].'/'.$b64_auction_id; ?>" class="btn btn-success btn-xs"><i class="fa fa-plus"></i> Auction Deposit</a>
                                <?php if($user['role'] == 1){ ?>
                                    <a href="<?php echo base_url().'auction/direct_sale/'.$b64_auction_id; ?>" class="btn btn-success btn-xs"><i class="fa fa-plus"></i> Direct Sale</a>
                                <?php } ?>
                            <?php } ?>
                            <?php if($if_items =='btn-success'){?>
                                <button type="button" id="<?= $value['id']; ?>" item-id="<?php echo $result_if_already_implode ?>" data-toggle="modal" onclick="getBanner(this);" data-target=".bs-example-modal-banner"  data-backdrop="static" data-keyboard="true" class="btn btn-info btn-xs oz_banner_pr"><i class="fa fa-file"></i> Banner</button>
                            <?php } ?>
                            <?php if($if_items =='btn-success'){?>
                                <button type="button" id="<?= $value['id']; ?>" item-id="<?php echo $result_if_already_implode ?>" data-toggle="modal" onclick="getlots(this);" data-target=".bs-example-modal-lot"  data-backdrop="static" data-keyboard="true" class="btn btn-info btn-xs"><i class="fa fa-file"></i> Lot Info</button>
                            <?php } ?>
                            <?php if($role == 1) { if($if_items =='btn-warning'){?>

                                <button onclick="deleteRecord(this)" type="button" data-obj="auctions" data-token-name="<?= $this->security->get_csrf_token_name();?>" data-token-value="<?=$this->security->get_csrf_hash();?>" data-id="<?php echo $value['id']; ?>" data-url="<?php echo base_url(); ?>auction/delete" class="btn btn-danger btn-xs" title="Delete"><i class="fa fa-trash"></i> Delete</button>
                            <?php }} ?>
                        </td>
                    </tr>
                <?php }?>
           
        <?php 
    }else{
        echo '<tr><td colspan="7"><h1>No Record Found</h1></td></tr>';
    }
    ?>
  </tbody>
</table>

<script type="text/javascript">
  var token_name = '<?= $this->security->get_csrf_token_name();?>';
  var token_value = '<?=$this->security->get_csrf_hash();?>';

  $('#datatable-responsive_2').DataTable({
    responsive: true,
          columnDefs: [ 
            { targets:"_all", orderable: false },
            { targets:[7], className: "tablet, mobile" }
          ]
  });

  $('.oz_stock_list').on('click',function(){
    var url = '<?php echo base_url();?>';
    var id = $(this).attr("id");
    console.log(id);
    $.ajax({
      url: url + 'auction/get_stock_list',
      type: 'POST',
      data: {id:id, [token_name]:token_value},
    }).then(function(data) {
      var objData = jQuery.parseJSON(data);
      console.log(objData);
      $('#add_items').hide(); 
      if (objData.msg == 'success') 
      {
        $('.modal-stock-body').html(objData.data);
      }
    });
  });
</script>