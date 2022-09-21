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
        <h2>Payables <small>Payable history for <?php if(isset($user) && !empty($user)){ echo $user['username']; ?> 
          </small></h2>
        <?php } ?>
        <div class="clearfix"></div>
      </div>
      <div class="x_content">
        <table id="datatable" class="table table-striped table-bordered">
          <thead>
            <tr>
              <th>Item Name</th>
              <th>Registration No</th>
              <th>Auction Name</th>
              <th>Win Bid Date</th>
              <th>Win Bid Price</th>
              <th>Buyer Payment Status</th>
              <!-- <th>Adjustments</th> -->
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php 
            if( (isset($sold_items)) && !(empty($sold_items)) ){

              foreach ($sold_items as $key => $value) {
                $auction_name = json_decode($value['auction_title']);
                $item_name = json_decode($value['item_name']);
                // print_r($seller_percentage_charges);die();
                ?>
                <tr>
                  <td><?= @$item_name->english; ?></td>
                  <td><?= $value['registration_no']; ?></td>
                  <td><?= @$auction_name->english; ?></td>
                  <td><?= date('Y-m-d', strtotime($value['created_on'])); ?></td>
                  <td><?= $value['win_bid_price']; ?></td>
                  <td><?= ($value['payment_status']) ? 'Paid' : 'Unpaid'; ?></td>
                  <td>
                    <?php $invoice = $this->db->get_where('invoices', ['sold_item_id' => $value['sold_item_id'], 'type' => 'seller'])->row_array();
                    if($value['payment_status']){ 
                      if (empty($invoice) || $invoice['invoice_status'] == '0') { ?>
                        <a href="<?= base_url('seller-invoice/').$value['sold_item_id']; ?>" 
                          class="btn btn-warning btn-xs"
                        ><i class="fa fa-money"></i> Recipt</a>
                      <?php } else{ ?>
                        <a href="<?= base_url('view-seller-invoice/').$value['sold_item_id']; ?>" 
                          class="btn btn-info btn-xs" target="_blank"><i class="fa fa-print"></i> View Receipt</a>
                      <?php }
                      if (empty($invoice) || $invoice['statement_status'] == '0') { ?>
                        <a href="<?= base_url('seller-statement/').$value['sold_item_id']; ?>" 
                          class="btn btn-warning btn-xs"
                        ><i class="fa fa-money"></i> Statement</a>
                      <?php } else{ ?>
                        <a href="<?= base_url('view-seller-statement/').$value['sold_item_id']; ?>" 
                          class="btn btn-info btn-xs" target="_blank"><i class="fa fa-print"></i> View Statement</a>
                        <?php if(!$value['seller_payment_status']){ ?>
                          <a href="<?= base_url('buyer-pay/').$value['sold_item_id'].'/'.$user['id']; ?>" 
                          class="btn btn-info btn-xs" ><i class="fa fa-print"></i> Pay</a>
                        <?php } ?>
                      <?php } ?>
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