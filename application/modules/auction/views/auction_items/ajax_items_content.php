<table id="datatable-responsive" class="table table-striped jambo_table bulk_action" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <!-- <th>
                        <input type="checkbox" id="check-all" class="flat ozproceed_check" name="table_records">
                    </th> -->
                    <th data-orderable="false"></th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Registration #</th>
                    <th>Lot #</th>
                    <th>Make</th>
                    <th>Model</th>
                    <th>Reserve</th>
                    <!-- <th>Seller Code</th> -->
                    <th>Created On</th>
                    <th>Sold Status</th>
                    <th data-orderable="false">Buyer</th>
                    <th>Status</th>
                    <th data-orderable="false">Actions</th>
                </tr>
            </thead>
            <tbody class=""> 
       <?php if(!empty($items_list)){?>
        
                <?php 
                $j = 0;
                $role = $this->session->userdata('logged_in')->role; 
                $CI =& get_instance();
                $have_documents = false;
                foreach($items_list as $value){
                    if (!empty($value['buyer_id'])) {
                      $item_buyer = $this->db->get_where('users', ['id' => $value['buyer_id']])->row_array();
                    }
                    $j++;
                    $itemid = $value['id'];
                    $bid_query = $this->db->get_where('live_auction_bid_log', ['auction_id' => $auction_id, 'item_id' => $itemid]);
                    // $auction_id = $this->uri->segment(3);
                    $b64_auction_id = urlencode(base64_encode($auction_id));
                    $result_if_already = $CI->auction_model->auction_item_bidding_rule_list($auction_id,$itemid);
                    if(isset($result_if_already) && !empty($result_if_already))
                    {
                        $checked_ = 'checked';
                        if(isset($result_if_already[0]['bid_start_time']) && !empty($result_if_already[0]['bid_end_time'])){
                        $if_rules = 'btn-success';
                        }
                        else{
                        $if_rules = 'btn-warning';
                        }

                        if(isset($result_if_already[0]['lot_no']) && !empty($result_if_already[0]['lot_no'])){
                        $if_lot_no = 'btn-success';
                        }
                        else{
                        $if_lot_no = 'btn-warning';
                        }
                        
                    }else{
                        $checked_ = '';
                        $if_rules = 'btn-warning';
                        $if_lot_no = 'btn-warning';
                    }

                    if(!empty($value['item_images']) || !empty($value['item_attachments']))
                    {
                        $documents_class = 'btn-success';
                        $have_documents = true;
                        if(empty($value['item_attachments']))
                        {
                            $documents_class = 'btn-info';
                        }
                    }
                    else
                    {
                        $have_documents = false;
                        $documents_class = 'btn-warning';
                    }

                    $category_name = json_decode($value['category_name']);
                    ?>
                    <tr id="row-<?php echo  $value['id']; ?>">
                        <!-- <td class="a-center ">
                        <input type="checkbox" class="flat multiple_rows ozproceed_check" id="<?php //echo  $value['id']; ?>" name="table_records">
                        </td> -->
                        <!-- <td><?php //echo $j ?></td> -->
                          <td><a class="text-success" href="<?php echo base_url().'auction/details/'.$value['id'].'/'.$b64_auction_id;  ?>">
                          <?php 
                          if(isset($value['item_images']) && !empty($value['item_images']))
                          {
                            $images_ids = explode(",",$value['item_images']);
                            $files_array = $this->files_model->get_multiple_files_by_ids_orders($images_ids);
                          }
                          else
                          {
                            $files_array = array();
                          }
                          
                        ?>
                         <?php if(isset($files_array[0]['name']) && !empty($files_array[0]['name']))
                          {
                           ?>
                          <img style="max-width: 50px" class="img-responsive avatar-view" src="<?php echo base_url().'uploads/items_documents/'.$value['id'].'/'.$files_array[0]['name']; ?>" alt="Visa">
                        <?php }
                            else
                            { ?>
                          <img style="max-width: 50px" class="img-responsive avatar-view" src="<?php echo base_url().'assets_admin/images/product-default.jpg'; ?>" alt="Visa">
                        <?php } ?>
                          
                        </a>
                        </td>
                        <td><label>
                          <a class="text-success" href="<?php echo base_url().'auction/details/'.$value['id'].'/'.$b64_auction_id;  ?>"><?php echo ucwords(json_decode($value['name'])->english); ?>
                          </a>
                          </label>
                        </td>
                        <!-- <td><?php //echo $value['name']; ?></td> -->
                        <td><?php echo (isset($category_name) && !empty($category_name)) ? $category_name->english : ''; ?></td>
                        <td><?php echo $value['registration_no']; ?></td>
                        <td><?php echo $result_if_already[0]['order_lot_no']; ?></td>
                        <td><?php echo (isset($value['make_name']) && !empty($value['make_name'])) ? json_decode($value['make_name'])->english : 'N/A'; ?></td>
                        <td><?php echo (isset($value['model_name']) && !empty($value['model_name'])) ? json_decode($value['model_name'])->english : 'N/A'; ?></td>
                        <td><?php echo (isset($value['price']) && !empty($value['price'])) ? $value['price'] : 'N/A'; ?></td>
                        <!-- <td><?php //echo $value['seller_code']; ?></td> -->
                        <td><?php echo $value['created_on']; ?></td>
                        <td><?php
                            $sold_ststus = $result_if_already[0]['sold_status'];
                            switch ($sold_ststus) {
                                case 'not':
                                $status = "Available";
                                break;

                                case 'not_sold':
                                $status = "Unsold";
                                break;

                                case 'sold':
                                $status = "Sold Out";
                                break;

                                case 'approval':
                                $status = "Need Approval From Customer";
                                if (!empty($record->buyer_id)) {
                                    $buyer_data = $this->db->get_where('users',['id'=>$record->buyer_id])->row();
                                    $buyer = $buyer_data->username;
                                }
                                break;

                                case 'return':
                                $status = "Item Returned";
                                break;

                                default:
                                $status = "";
                                break;
                            } echo $status; ?></td>
                        <td><?php echo (isset($item_buyer) && !empty($item_buyer)) ? $item_buyer['username'] : 'N/A'; ?></td>
                        <td><?php echo ucfirst($value['item_status']); ?></td>
                        <td>
                          <?php if ($role == 1 && $value['sold'] == 'no') : ?>
                              <a href="<?php echo base_url().'auction/update_auction_item/'.$value['id'].'/'.$b64_auction_id; ?>" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> Edit</a>
                          <?php endif; ?>

                            <a href="<?php echo base_url().'auction/documents/'.$value['id'].'/'.$b64_auction_id; ?>" class="btn <?php echo  $documents_class; ?> btn-xs"><i class="fa fa-pencil"></i> Documents</a>
                            <?php if($have_documents){ ?>
                                <a href="<?php echo base_url().'auction/view_documents/'.$value['id'].'/'.$b64_auction_id; ?>" class="btn <?php echo  $documents_class; ?> btn-xs"><i class="fa fa-pencil"></i> View Documents</a>
                            <?php } ?>
                            <button type="button" id="<?php echo $value['id']; ?>" data-toggle="modal" data-target=".bs-example-modal-print"  data-backdrop="static" data-keyboard="false" class="btn btn-info btn-xs oz_print_qr"><i class="fa fa-qrcode"></i> QR Code</button>
                            <!-- <button type="button" id="<?php //echo $value['id']; ?>" onclick="get_rules(<?php //echo $auction_id.",".$itemid ?>)"  data-toggle="modal" data-target=".bs-example-modal-sm" data-backdrop="static" data-keyboard="false" class="btn <?php echo $if_rules; ?> btn-xs oz_bidding_model"><i class="fa fa-pencil-square-o"></i>Rules</button> -->

                            <button type="button" id="<?php echo $value['id']; ?>" onclick="get_lotting(<?php echo $auction_id.",".$itemid ?>)"  data-toggle="modal" data-target=".bs-example-modal-sm_lot" data-backdrop="static" data-keyboard="false" class="btn <?php echo $if_lot_no; ?> btn-xs oz_lotting_model"><i class="fa fa-pencil-square-o"></i>Lotting</button>

                            <button type="button" id="<?= $itemid; ?>" onclick="getBlinking(<?php echo $auction_id.",".$itemid; ?>)"  data-toggle="modal" data-target=".bs-example-modal-sm_blink" data-backdrop="static" data-keyboard="false" class="btn btn-info btn-xs oz_blinking_model"><i class="fa fa-pencil-square-o"></i>Blinking text</button>

                            <button type="button" id="<?php echo $value['id']; ?>" data-toggle="modal" data-target=".bs-example-modal-banner"  data-backdrop="static" data-keyboard="false" class="btn btn-info btn-xs oz_banner_pr"><i class="fa fa-file"></i> Banner</button>
                            <?php if($this->loginUser->role == 1  && $bid_query->num_rows() < 1 && $value['sold'] == 'no') : ?>
                                <button onclick="deleteRecord(this)" type="button" data-obj="item" data-id="<?php echo $value['id']; ?>" data-url="<?php echo base_url(); ?>items/delete" class="btn btn-danger btn-xs" title="Delete"><i class="fa fa-trash"></i> Delete</button>
                              <?php endif; ?>

                        </td>
                    </tr>
                <?php }?>
            
        <?php 
    }else{
        echo '<tr><td colspan="13"><h3 class="text-center">No Record Found</h3></td></tr>';
    }
    ?>
</tbody>
</table>
 
<script type="text/javascript">
    
var datatable = $('#datatable-responsive').DataTable({
            responsive: true,
            "order": [[ 4, "desc" ]],
            columnDefs: [
              { targets:"_all" },
              { targets:[12], className: "tablet, mobile" }
            ]
    });
 $('.oz_banner_pr').on('click',function(){
         // alert('clicker');
    var url = '<?php echo base_url();?>';
    var auction_id = '<?php echo $auction_id;?>';
    var id = $(this).attr("id");
    console.log(id);
     $.ajax({
      url: url + 'auction/get_banner_details',
      type: 'POST',
      data: {id:id,auction_id:auction_id},
      beforeSend: function(){

         $('.modal-banner-body').html('<img src="'+url+'assets_admin/images/loading.gif" align="center" />');
      },
      }).then(function(data) {
        var objData = jQuery.parseJSON(data);
        // console.log(objData);
        $('#add_items').hide(); 
        if (objData.msg == 'success') 
        {
          $('.modal-banner-body').html(objData.data);
        }
      });
  });
 
</script>