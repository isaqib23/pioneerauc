<?php if(!empty($bid_list)){ ?>
    <table id="datatable-responsive" class="table table-striped jambo_table bulk_action" cellspacing="0" width="100%">
        <thead>
            <tr> 
                <th>#</th> 
                <th>Item</th>
                <th>Bidder</th>
                <th>Bid Amount</th>
                <th>Bid Status</th>
                <th>Date</th> 
            </tr>
        </thead>
        <tbody id="user_listing">
            <?php 
            $j = 0;
            foreach($bid_list as $value){
              $j++;
              $item_name = json_decode($value['item_name']);
              ?>
                <tr id="row-<?php echo  $value['id']; ?>">                      
                    <td><?php echo $j ?></td>
                    <td><?php echo $item_name->english; ?></td>
                    <td><?php echo $value['fname'].' '.$value['lname']; ?></td>
                    <td><?php echo $value['bid_amount'].' AED'; ?></td>
                    <td><?php echo $value['bid_status']; ?></td>
                    <td><?php echo date('Y-m-d', strtotime($value['date'])); ?></td> 
                </tr>
            <?php }?>
        </tbody>
    </table>
    <tfoot>
        <th colspan="50%" style="padding: 10px;">
        <div class="pull-left actions">                           
        </div>                
    </tfoot>
<?php 
  }else{
    echo "<h1>No Record Found</h1>";
  }
?>

<script>
    $('#datatable-responsive').DataTable();
</script>


<!-- <?php 
    $j = 0;
    if(isset($bid_list) && !empty($bid_list)){
    foreach($bid_list as $value){
        $j++;
        ?>
        <tr id="row-<?php echo  $value['id']; ?>">
          
             <td><?php echo $j ?></td>
            <td><?php echo $value['item_name']; ?></td>
            <td><?php echo $value['fname'].' '.$value['lname']; ?></td>
            <td><?php echo $value['bid_amount'].' AED'; ?></td>
            <td><?php echo $value['bid_status']; ?></td>
            <td><?php echo date('Y-m-d', strtotime($value['date'])); ?></td> 
             
        </tr>
<?php } }else{ echo '<tr><td colspan="7"><h3>No Record Found</h3></td></tr>'; }?> -->