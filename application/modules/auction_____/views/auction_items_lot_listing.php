<table id="datatable-responsive_2" class="table table-striped jambo_table bulk_action" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>Name</th>
            <th>Image</th>
            <th>Lot Number</th>
        </tr>
    </thead>

    <tbody class="">
    <?php 
        if(isset($auction_item_rows) && !empty($auction_item_rows)){
            $j = 0;
            $role = $this->session->userdata('logged_in')->role; 
            $CI =& get_instance();
            
            // print_r($auction_item_rows);

            foreach($auction_item_rows as $value){
                foreach($value as $val){
                    $j++; ?>
                    <?php 
                        $item_name = json_decode($val['name']);
                        $file_name = '';
                        if (isset($val['item_files'][0])) {
                            $file_name = $val['item_files'][0]['name'];
                            $item_id = $val['item_id'];
                            $urlImg = base_url('uploads/items_documents/').$item_id.'/37x36_'.$file_name;
                            if(file_exists($urlImg)){
                                $base_url_img = $urlImg;
                            }else{
                                $base_url_img = base_url('uploads/items_documents/').$item_id.'/'.$file_name;
                            }
                        }
                    ?>
                    <tr>
                        <td><?php if(!empty($val['name'])){echo $item_name->english;} ?></td>
                        <td>
                            <?php if (!empty($val['item_files'])) { ?>
                                <img  style="height: 30px; width: 40px" class="img-responsive avatar-view" src="<?=  $base_url_img; ?>" alt="propery flat">

                            <?php } else { ?>
                                <img  style="height: 30px; width: 40px" class="img-responsive avatar-view" src="<?= ASSETS_ADMIN; ?>images/product-default.jpg" alt="propery flat">
                            <?php } ?>
                        </td>
                        <!-- <td></td> -->
                        <td><?php echo $val['order_lot_no']; ?></td>
                    </tr>
                <?php }?>
            <?php }?>
        <?php 
        }else{
            echo '<tr><td colspan="7"><h1 style="text-align: center;">No Record Found</h1></td></tr>';
        } ?>
    </tbody>
</table>

<script type="text/javascript">
    // var datatable = $('#datatable-responsive_2').DataTable({
    //     responsive: true,
    //     columnDefs: [ 
    //       { targets:"_all" },
    //       { targets:[7], className: "tablet, mobile" }
    //     ]
    // });
</script>