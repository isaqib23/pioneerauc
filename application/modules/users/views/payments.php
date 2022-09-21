<!-- Design Management start -->
<div class="page-title">
  <div class="title_left">
    <h3>Customers <small></small></h3>
  </div>
  <div class="title_right"></div>
</div>


<?php if($this->session->flashdata('error')){ ?>
  <div class="alert alert-danger alert-dismissible">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    <strong>Error!</strong> <?= $this->session->flashdata('error'); ?>
  </div>
<?php } ?>

<?php if($this->session->flashdata('success')){ ?>
  <div class="alert alert-success alert-dismissible">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    <strong>Success!</strong> <?= $this->session->flashdata('success'); ?>
  </div>
<?php } ?>
<?php //print_r($sold_items); ?>
<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_title">
      <h2>Payments Detail <small>Payment history for <?php if(isset($user) && !empty($user)) echo $user['username'];{ ?> 
          </small></h2>
        <?php }?>
        <!-- <div class="nav navbar-right">
          <h2>Total Package Power: <?= $total_package_power; ?> TH/s</h2>
        </div> -->
        <div class="clearfix"></div>
      </div>
      <div class="x_content">
        <table id="datatable" class="table table-striped table-bordered">
          <thead>
            <tr>
              <th>Item Name</th>
              <th>Reg No</th>
              <th>Auction Name</th>
              <th>Win Bid Date</th>
              <th>Win Bid Price</th>
              <th>Item Security</th>
              <th>Deposit Amount</th>
              <th>Payable Amount</th>
              <th>Payment Status</th>
              <th>Adjustments</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php 
            if( (isset($sold_items)) && !(empty($sold_items)) ){

              foreach ($sold_items as $key => $value) {
                $auction_name = json_decode($value['auction_title']);
                //Security adjustment
                $this->db->select('auction_item_deposits.id as security_id,
                                  auction_item_deposits.deposit as security_amount,
                                  auction_item_deposits.status as security_status
                                  ');
                $this->db->from('auction_item_deposits');
                $this->db->where([
                  'auction_item_deposits.user_id' => $value['buyer_id'],
                  'auction_item_deposits.item_id' => $value['item_id'],
                  'auction_item_deposits.auction_id' => $value['auction_id'],
                  'auction_item_deposits.auction_item_id' => $value['auction_item_id'],
                  'auction_item_deposits.status' => 'approved'
                ]);
                $security = $this->db->get();
                if($security->num_rows() > 0){
                  $security = $security->row_array();
                  $security_flag = true;
                  //$payable_amount = (float)$payable_amount - (float)$security['security_amount'];
                }else{
                  $security = [];
                  $security_flag = false;
                }

                //Deposit adjustment
                $this->db->select('SUM(auction_deposit.amount) as dr_amount');
                $this->db->from('auction_deposit');
                $this->db->where([
                  'auction_deposit.deleted' => 'no',
                  'auction_deposit.deposit_type' => 'permanent',
                  'auction_deposit.status' => 'approved',
                  'auction_deposit.account' => 'DR',
                  'auction_deposit.user_id' => $value['buyer_id']
                ]);
                $this->db->group_by(['auction_deposit.user_id']);
                $DR = $this->db->get();
                
                if($DR->num_rows() > 0){
                  $DR = $DR->row_array();

                  // get CR
                  $this->db->select('SUM(auction_deposit.amount) as cr_amount');
                  $this->db->from('auction_deposit');
                  $this->db->where([
                    'auction_deposit.deleted' => 'no',
                    'auction_deposit.deposit_type' => 'permanent',
                    'auction_deposit.status' => 'approved',
                    'auction_deposit.account' => 'CR',
                    'auction_deposit.user_id' => $value['buyer_id']
                  ]);
                  $this->db->group_by(['auction_deposit.user_id']);
                  $CR = $this->db->get();
                  $CR = $CR->row_array();

                  $deposit = (float)$DR['dr_amount'] - (float)@$CR['cr_amount'];

                  if(!empty($value['adjusted_deposit']) && (float)$value['adjusted_deposit'] > 0){
                    $deposit_flag = false;
                  }else{
                    $deposit_flag = true;
                  }
                  //$payable_amount = (float)$payable_amount - (float)$deposit['deposit_amount'];
                }else{
                  $deposit = 0;
                  $deposit_flag = false;
                }

                ?>
                <?php $item_name = json_decode($value['item_name']); ?>
                <tr>
                  <td><?= $item_name->$language; ?></td>
                  <td><?= $value['registration_no']; ?></td>
                  <td><?= $auction_name->$language; ?></td>
                  <td><?= date('Y-m-d', strtotime($value['created_on'])); ?></td>
                  <td><?= $value['win_bid_price']; ?></td>
                  <td><?= ($security_flag) ? $security['security_amount'] : '0'; ?></td>
                  <td><?= ($deposit_flag) ? $deposit : '0'; ?></td>
                  <td><?= $value['payable_amount']; ?></td>
                  <td><?= ($value['payment_status']) ? 'Paid' : 'Unpaid'; ?></td>
                  <td>
                    
                    <?php 
                    if($value['item_security'] == 'yes'){
                      if($security_flag && $value['payment_status'] == 0){ ?>

                        <a href="<?= base_url('security-adjust/').$value['buyer_id'].'/'.$security['security_id'].'/'.$value['sold_item_id'].'/'.$security['security_amount']; ?>" 
                          class="btn btn-primary btn-xs"
                        ><i class="fa fa-check"></i> Adjust Item Security</a>
                      
                      <?php }else{ ?>
                        Adjusted Item Security: <?= $value['adjusted_security']; ?><br />
                      <?php } ?>
                    <?php } ?>

                    <?php if($deposit_flag && $value['payment_status'] == 0){ ?>
                      <a href="<?= base_url('deposit-adjust/').$value['buyer_id'].'/'.$value['sold_item_id'].'/'.$deposit; ?>" 
                        class="btn btn-primary btn-xs"
                      ><i class="fa fa-check"></i> Adjust Deposit</a>
                    <?php }else{ ?>
                      Adjusted Deposit: <?= $value['adjusted_deposit']; ?>
                    <?php } ?>

                  </td>
                  <td>

                    <?php if(!$value['payment_status']){ ?>
                      <a href="<?= base_url('make-payment/').$value['buyer_id'].'/'.$value['sold_item_id']; ?>" 
                        class="btn btn-primary btn-xs"
                      ><i class="fa fa-money"></i> Make Payment</a>
                    <?php } ?>


                    <?php if($value['payment_status']){ ?>
                      <?php $invoice = $this->db->get_where('invoices', ['sold_item_id' => $value['sold_item_id'], 'type' => 'buyer'])->row_array();
                        if (empty($invoice) || $invoice['receipt_status'] == '0') { ?>
                          <a href="<?= base_url('receipt/').$value['sold_item_id']; ?>" 
                            class="btn btn-warning btn-xs"
                            ><i class="fa fa-print"></i> Receipt</a>
                      <?php } else{ ?>
                            <a href="<?= base_url('users/view_payment_receipt/').$value['sold_item_id']; ?>" 
                            class="btn btn-info btn-xs" target="_blank"
                            ><i class="fa fa-print"></i>View Receipt</a>
                        <?php } ?>
                        <?php if (empty($invoice) || $invoice['invoice_status'] == '0') { ?>
                          <a href="<?= base_url('buyer-invoice/').$value['sold_item_id']; ?>" 
                            class="btn btn-warning btn-xs"
                          ><i class="fa fa-print"></i> Buyer Invoice</a>
                        <?php } else{ ?>
                          <a href="<?= base_url('users/view_buyer_tex_invoice/').$value['sold_item_id']; ?>" 
                            class="btn btn-info btn-xs" target="_blank"
                          ><i class="fa fa-print"></i> View Buyer Invoice</a>
                        <?php } ?>
                        <?php if (empty($invoice) || $invoice['statement_status'] == '0') { ?>
                          <a href="<?= base_url('buyer-statement/').$value['sold_item_id']; ?>" 
                            class="btn btn-warning btn-xs"
                          ><i class="fa fa-print"></i> Buyer Statement</a>
                        <?php } else{ ?>
                          <a href="<?= base_url('users/view_buyer_tex_statement/').$value['sold_item_id']; ?>"
                            class="btn btn-info btn-xs" target="_blank"
                          ><i class="fa fa-print"></i> View Buyer Statement</a>
                        <?php } ?>
                        <a href="<?= base_url('buyer-payment-detail/').$value['sold_item_id']; ?>" 
                          class="btn btn-info btn-xs"
                        ><i class="fa fa-print"></i> Payment Detail</a>
                    <?php } ?>

                  </td>
                </tr>
                <?php
              }
            }
            ?>
            
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<script>
$(document).ready(function(){
  $('#datatable').DataTable({
    bSort: false
  });
});

</script>