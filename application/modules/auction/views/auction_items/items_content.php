 
       <?php if(!empty($items_list)){

        //print_r($items_list);die();

                $j = 0;
                $CI =& get_instance();
                foreach($items_list as $value){
                    $j++;

                    $title =json_decode($value['category_name']);
                    $itemid = $value['id'];
                    $result_if_already = $CI->auction_model->auction_item_bidding_rule_list($auction_id,$itemid);
                    if(isset($result_if_already) && !empty($result_if_already))
                    {
                        $checked_ = 'checked';
                    }else{
                        $checked_ = '';
                    }
                    if(empty($checked_)){
                    ?>
                        <tr id="row-<?php echo  $value['id']; ?>" >
                            <td class="a-center ">
                            <input type="checkbox" <?php echo $checked_; ?> onchange="check_uncheck(this)" class="flat multiple_rows ozproceed_check child_items_<?php echo  $value['id']; ?>" id="<?php echo  $value['id']; ?>" name="table_record_items[]">
                            </td>
                           <?php $itm_name = json_decode($value['name']); ?> 
                            <td><?php echo $itm_name->english; ?></td>
                            <td><?php echo (isset($title->english) && !empty($title->english)) ? $title->english : ''; ?></td>
                            <td><?php echo $value['registration_no']; ?></td>
                            <!-- <td><?php //echo $value['lot_id']; ?></td> -->
                            <td><?php echo (isset($value['keyword']) && !empty($value['keyword'])) ? $value['keyword'] : ''; ?></td>
                            <td>
                            <?php if(isset($result_if_already) && !empty($result_if_already)){ ?>
                              <button type="button" id="<?php echo $value['id']; ?>" onclick="get_rules(<?php echo $auction_id.",".$itemid ?>)"  data-toggle="modal" data-target=".bs-example-modal-sm" data-backdrop="static" data-keyboard="false" class="btn btn-info btn-xs oz_bidding_model"><i class="fa fa-pencil-square-o"></i>Rules</button>
                            <?php }else{ echo 'Not Added Yet';} ?>
                            </td>
                        </tr>
                    <?php }?>
                <?php }?>
            
        <?php 
    }else{
        echo '<tr  ><td colspan="8"><h3 class="text-center">No Record Found</h3></td></tr>';
    }
    ?>
 
<script type="text/javascript">
    
    //var ids_list_auto = $('#item_ids_list').val(); 
    // if(ids_list_auto != '')
    // {
    //     var array = ids_list_auto.split(',');
    //     $.each( array, function( key, value ) {
    //     $("#datatable-responsive_3 input[name='table_record_items[]'] #" + value).prop('checked', true);
            
    //     });
    // }
 
</script>