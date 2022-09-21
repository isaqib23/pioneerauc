
  <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="row">
                        <!-- accepted payments column -->
                        <div class="col-xs-6">
                        <p class="lead"><?php echo (isset($item_row[0]['name']) && !empty($item_row[0]['name'])) ? json_decode($item_row[0]['name'])->english : 'Product' ?></p>
                          <?php if(isset($files_array[0]['name']) && !empty($files_array[0]['name']))
                          {
                           ?>
                          <img style="max-width: 350px" class="img-responsive avatar-view" src="<?php echo base_url().'uploads/items_documents/'.$item_row[0]['id'].'/'.$files_array[0]['name']; ?>" alt="Visa">
                        <?php }
                            else
                            { ?>
                            <img style="max-width: 350px" class="img-responsive avatar-view" src="<?php echo base_url().'assets_admin/images/product-default.jpg'; ?>" alt="Visa">
                        <?php } ?>
                        <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;max-width: 350px">
                            <?php echo (isset($item_row[0]['detail']) && !empty($item_row[0]['detail'])) ? json_decode($item_row[0]['detail'])->english: '' ?>
                        </p>

                        </div>
                        <!-- /.col -->
                        <div class="col-xs-6">
                          <p class="lead">Created Date: <?php echo (isset($item_row[0]['created_on']) && !empty($item_row[0]['created_on'])) ? date('d F Y',strtotime($item_row[0]['created_on'])) : '' ?></p>
                          <div class="table-responsive">
                            <table class="table">
                              <tbody>
                                <tr>
                                  <th style="width:50%">Reserve Price:</th>
                                  <td><?php echo (isset($item_row[0]['price']) && !empty($item_row[0]['price'])) ? $item_row[0]['price'] : '' ?> AED</td>
                                </tr>
                                 <tr>
                                  <th style="width:50%">Registration No:</th>
                                  <td><?php echo (isset($item_row[0]['registration_no']) && !empty($item_row[0]['registration_no'])) ? $item_row[0]['registration_no'] : '' ?></td>
                                </tr>
                                 <tr>
                                  <th style="width:50%">Make:</th>
                                  <td><?php echo (isset($item_row[0]['make_name']) && !empty($item_row[0]['make_name'])) ? json_decode($item_row[0]['make_name'])->english : '' ?> </td>
                                </tr>
                                 <tr>
                                  <th style="width:50%">Model:</th>
                                  <td><?php echo (isset($item_row[0]['model_name']) && !empty($item_row[0]['model_name'])) ? json_decode($item_row[0]['model_name'])->english : '' ?> </td>
                                </tr>
                                <?php 
                                    $role = $this->session->userdata('logged_in')->role; 
                                    $CI =& get_instance(); 

                                 ?>

                                 <tr>
                                  <th style="width:50%">Lot:</th>
                                  <td><?php 

                                    $result_if_lot = $CI->auction_model->auction_item_bidding_rule_list($auction_id,$item_id); 
                                  echo (isset($result_if_lot[0]['order_lot_no']) && !empty($result_if_lot[0]['order_lot_no'])) ? $result_if_lot[0]['order_lot_no'] : '';
                                     ?> 
                                   </td>
                                </tr>
                             <?php 
                                   $dynamic_values_array =  $CI->items_model->get_itemfields_byItemid($item_row[0]['id']);
                                   if(isset($dynamic_values_array) && !empty($dynamic_values_array)){
                                    foreach ($dynamic_values_array as $value) {
                                      $value['value'] = str_replace(array( '[', ']' ), '', $value['value']);
                                      $dynamic_fields_array =  $CI->items_model->fields_data_by_id($value['fields_id']);
                                   if (isset($dynamic_fields_array[0])) {
                                ?>

                                <tr>
                                  <th style="width:50%"><?php echo ucwords($dynamic_fields_array[0]['label']); ?></th>
                                  <?php 
                                   $HiddenProducts = $dynamic_fields_array[0]['multiple']; 
                                   $check_array = array();
                                   $from_array = array();
                                   $existed_values = array();
                                  if($HiddenProducts == 'true'){
                                   $check_array = explode(',', $value['value']);
                                   // print_r($dynamic_fields_array);die();
                                   $from_array =  json_decode($dynamic_fields_array[0]['values'],true);
                                    foreach ($check_array as $check_array_value) 
                                    {
                                        foreach ($from_array as $from_key => $from_array_value) 
                                        {
                                            if($from_array[$from_key]['value'] == $check_array_value ) 
                                            { 
                                                $existed_values[] = $from_array[$from_key]['label'];
                                            }
                                        }
                                    }

                                  ?>
                                  <td><?php echo (isset($existed_values) && !empty($existed_values)) ? implode(",",$existed_values) : ''; ?> </td>
                              <?php }else{ ?>
                                   <td><?php 
                                  if ($dynamic_fields_array[0]['values'] != '' && $dynamic_fields_array[0]['type'] != 'checkbox-group') {
                                    
                                    $val = json_decode($dynamic_fields_array[0]['values'], true);
                                    foreach ($val as $key => $DDval) {
                                      if ($DDval['value'] == $value['value']) :
                                        echo $DDval['label'];
                                      endif;
                                    }
                                  } else {
                                    echo (isset($value['value']) && !empty($value['value'])) ? $value['value'] : ''; } ?> </td>
                          <?php } ?>
                                </tr>
                                <?php } } } ?>

                              </tbody>
                            </table>
                          </div>
                        </div>
                        <!-- /.col -->
                      </div>
                      </div>
 
<script type="text/javascript">
// $('.oz_print_qr').on('click',function(){
//     var url = '<?php //echo base_url();?>';
//     var id = $(this).attr("id");
//     console.log(id);
//      $.ajax({
//       url: url + 'items/get_qrcode',
//       type: 'POST',
//       data: {id:id},
//       }).then(function(data) {
//         var objData = jQuery.parseJSON(data);
//         console.log(objData);
//         $('#add_items').hide(); 
//         if (objData.msg == 'success') 
//         {
//           $('.modal-qr-body').html(objData.data);
//         }
//       });
// });
</script>