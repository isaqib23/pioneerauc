  <?php if(!empty($deposit_list)){ ?>
          <table id="datatable-responsive" class="table table-striped jambo_table bulk_action" cellspacing="0" width="100%">
              <thead>
                <tr>
                  <?php
                  if($this->session->userdata('logged_in')->role == 1)
                  { ?>
                    <th class="td">
                        <!-- <input type="checkbox" id="check-all" class="flat"> -->
                    </th>
                  <?php
                  }
                  ?>
                    <th>#</th>
                    <th>Payment Type</th>
                    <th>Amount</th>
                    <th>Account</th>
                    <th>Created On</th>
                    <th>Status</th>
                    <?php
                    if($this->session->userdata('logged_in')->role == 1)
                    { ?>
                      <th data-orderable="false">Actions</th>
                    <?php
                    }
                    ?>
                </tr>
              </thead>
            <tbody id="user_listing">
              <?php 
              $j = 0;
              foreach($deposit_list as $value){
                  $j++;
                  ?>
                  <tr id="row-<?php echo  $value['id']; ?>">
                    <?php
                    if($this->session->userdata('logged_in')->role == 1)
                    { ?>
                      <td class="a-center td">
                      <!-- <input type="checkbox"  id="delete_bulk_button" value="<?php echo  $value['id']; ?>" class="flat" name="table_records"> -->
                      </td>
                    <?php
                    }?>
                      <td><?php echo $j ?></td>
                      <td><?php
                        switch ($value['payment_type']) {
                          case 'cash':
                            echo "Cash";
                            break;
                          case 'card':
                            echo "Card";
                            break;
                          case 'bank_transfer':
                            echo "Bank Transfer";
                            break;
                          case 'cheque':
                            echo "Cheque";
                            break;
                          case 'manual_deposit':
                            echo "Manual Deposit";
                            break;
                          
                          default:
                            echo "";
                            break;
                        } ?>    
                      </td>
                      <td><?php echo $value['amount'].' AED'; ?></td>
                      <td><?php echo $value['account']; ?></td>
                      <td><?php echo $value['created_on']; ?></td>
                      <td><?php echo ucfirst($value['status']); ?></td>
                      <?php
                      if($this->session->userdata('logged_in')->role == 1)
                      { ?>
                        <td>
                            <!-- <a href="<?php echo base_url().'users/update_deposit_detail/'.$value['user_id'].'/'.$value['id']; ?>" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> Edit</a> -->
                            <button onclick="deleteRecord(this)" type="button" data-obj="<?= isset($value['status']) ? 'auction_deposit' : 'transaction'; ?>" data-id="<?php echo $value['id']; ?>" data-url="<?php echo base_url(); ?>transaction/delete_deposit_detail" class="btn btn-danger btn-xs" title="Delete"><i class="fa fa-trash"></i> Delete</button>

                            <button type="button" id="<?php echo $value['id']; ?>" data-toggle="modal" data-target=".bs-example-modal-banner"  data-backdrop="static" data-keyboard="true" class="btn btn-info btn-xs oz_banner_pr"><i class="fa fa-file"></i> Print</button>
                        </td>
                      <?php
                      }
                      ?>
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

<!-- <?php 
  $j = 0;
  if(isset($deposit_list) && !empty($deposit_list)){
  foreach($deposit_list as $value){
  $j++;
  ?>
  <tr id="row-<?php echo  $value['id']; ?>">
    <?php
    if($this->session->userdata('logged_in')->role == 1)
    { ?>
      <td class="a-center ">
      <input type="checkbox"  id="delete_bulk_button" value="<?php echo  $value['id']; ?>" class="flat" name="table_records">
      </td>
    <?php
    }?>
      <td><?php echo $j ?></td>
      <td><?php echo $value['payment_type']; ?></td>
      <td><?php echo $value['amount'].' AED'; ?></td>
      <td><?php echo $value['account']; ?></td>
      <td><?php echo $value['created_on']; ?></td>
      <td><?php echo ucfirst($value['status']); ?></td> 
      <?php
      if($this->session->userdata('logged_in')->role == 1)
      { ?>
        <td>
            <button onclick="deleteRecord(this)" type="button" data-obj="transaction" data-id="<?php echo $value['id']; ?>" data-url="<?php echo base_url(); ?>users/delete_deposit_detail" class="btn btn-danger btn-xs" title="Delete"><i class="fa fa-trash"></i> Delete</button>
        </td>
      <?php
      }
      ?>
  </tr>
<?php }}else{ echo '<tr><td colspan="7"><h3>No Record Found</h3></td></tr>'; }?> -->

<script type="text/javascript">
  $(document).ready(function() {
    $('#datatable-responsive').DataTable( {
        columnDefs: [ {
            orderable: false,
            className: 'select-checkbox flat',
            targets:   0
        } ],
        select: {
            style:    'multi',
            selector: 'td:first-child'
        },
        order: [[ 1, 'asc' ]]
    } );
  });
</script>