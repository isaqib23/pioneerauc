<!-- Design Management start -->
<div class="page-title">
  <div class="title_left">
    <h3>Customers <small></small></h3>
  </div>
  <div class="title_right"></div>
</div>

<?php //print_r($history); ?>

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

<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_title">
        <h2><?= $data['small_title']; ?> <small></small></h2>
        <div class="clearfix"></div>
      </div>
      <div class="x_content">
        <br>
        <form name="myform" method="post"
          action="<?= base_url('users/').$data['generate_link']; ?>" 
          id="frm"
          class="form-horizontal form-label-left">
          <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
          
          <input type="hidden" name="user_id" value="<?= $buyer['id']; ?>" />
          <input type="hidden" name="user_po_box" value="<?= $buyer['po_box']; ?>" />
          <input type="hidden" name="user_address" value="<?= $buyer['address']; ?>" />
          <input type="hidden" name="user_mobile_number" value="<?= $buyer['mobile']; ?>" />
          <input type="hidden" name="item_id" id="item_id" value="<?= $item['id']; ?>" />
          <input type="hidden" name="vin_number" id="vin_number" value="<?= $item['vin_number']; ?>" />
          <input type="hidden" name="auction_id" id="auction_id" value="<?= $auction['id']; ?>" />
          <input type="hidden" name="sold_item_id" id="sold_item_id" value="<?= $sold_item['id']; ?>" />
          <input type="hidden" name="updated_date" id="updated_date" value="<?= date('F m, Y',strtotime($sold_item['updated_on'])); ?>" />
          <input type="hidden" name="vat" id="vat" value="<?= $vat_row['value']; ?>" />
          <?php 
          $adjustments = 0;
          if (isset($sold_item)) {
            $adjustments = $sold_item['adjusted_security'] + $sold_item['adjusted_deposit'];
          } ?>
          <input type="hidden" name="adjustments" value="<?= $adjustments; ?>" />
          

          <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="payment_date">Payment Date <span class="required"></span></label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input type="text" 
                name="payment_date" 
                id="payment_date"
                value="<?php if(isset($sold_item)){ echo date('Y-m-d', strtotime($sold_item['created_on'])); } ?>"
                readonly="" 
                class="form-control col-md-7 col-xs-12" />
              <div class="payment_date-error text-danger"></div>
            </div>
          </div>

          <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="lot_no">Lot No. <span class="required"></span></label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input type="text" 
                name="lot_no" 
                id="lot_no"
                value="<?php if(isset($auction_item)){ echo $auction_item['order_lot_no'];} ?>"
                readonly="" 
                class="form-control col-md-7 col-xs-12" />
              <div class="lot_no-error text-danger"></div>
            </div>
          </div>

          <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="auction_code">Auction ID <span class="required"></span></label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input type="text" 
                name="auction_code" 
                id="auction_code"
                value="<?php if(isset($auction)){ echo $auction['id'];} ?>"
                readonly="" 
                class="form-control col-md-7 col-xs-12" />
              <div class="auction_code-error text-danger"></div>
            </div>
          </div>

          <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="customer_name">Customer Name <span class="required"></span></label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input type="text" 
                name="customer_name" 
                id="customer_name"
                value="<?php if(isset($buyer)){ echo $buyer['username'];} ?>"
                readonly="" 
                class="form-control col-md-7 col-xs-12" />
              <div class="customer_name-error text-danger"></div>
            </div>
          </div>

          <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="purpose">Purpose <span class="required"></span></label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input type="text" 
                name="purpose" 
                id="purpose"
                readonly=""
                value="Sale"
                class="form-control col-md-7 col-xs-12" />
              <div class="purpose-error"></div>
            </div>
          </div>


          <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="win_price">Win bid price <span class="required"></span></label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input type="text" 
                name="win_price" 
                id="win_price"
                readonly=""
                value="<?php if(isset($sold_item)){ echo $sold_item['price'];} ?>"
                class="form-control col-md-7 col-xs-12" />
              <div class="win_price-error"></div>
            </div>
          </div>


          <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="trn_no">TRN No <span class="required"></span></label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input type="number" 
                name="trn_no" 
                id="trn_no"
                readonly=""
                value="<?php
                if($sold_item['payment_mode'] == 'manual_deposit') { 
                  $payment_datail = json_decode($sold_item['payment_details'], true); 
                  echo $payment_datail['deposit_txn_id'];
                }
                if ($sold_item['payment_mode'] == 'card') {
                  $payment_datail = json_decode($sold_item['payment_details'], true); 
                  echo $payment_datail['transaction_id'];
                } ?>" 
                class="form-control col-md-7 col-xs-12" />
              <div class="trn_no-error"></div>
            </div>
          </div>

          <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="trn_no">Reference No <span class="required"></span></label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input type="text" 
                name="ref_no" 
                id="ref_no"
                <?php if ($invoices) { ?>
                  <?= ($invoices['invoice_status'] == '0' && $invoices['receipt_status'] == '0' && $invoices['statement_status'] == '0' ) ? '' : 'readonly'; ?>
                <?php } ?>
                value="<?= (!empty($invoices['ref_no'])) ? $invoices['ref_no'] : ''; ?>" 
                class="form-control col-md-7 col-xs-12" />
              <div class="ref_no-error"></div>
            </div>
          </div>

          <!-- <div class="col-md-12 col-sm-12 text-center"> -->
          <div class="form-group text-center padding-0">
            <div class="x_panel tile">
              <div class="x_title">
                <h2>General Expense</h2>

                <div class="clearfix"></div>
              </div>
              <?php         
              $comission_filelds = $this->db->select('*')->get_where('seller_charges', ['user_type' => 'buyer','status' => 'active'])->result_array();
              ?>
              <?php foreach ($comission_filelds as $key => $value) { 
                $title = json_decode($value['title']);
                $field_title = strtolower(str_replace(' ', '_', $title->english));
                if(!empty($invoices)){
                  $general_expenses = json_decode($invoices['general_expenses'], true); 
                  $general_description = json_decode($invoices['general_description'], true); 
                } 
                ?>
                <div class="item form-group" >
                  <div class="row">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="general_expenses"><?= ucfirst($title->english); ?> <span class="required">
                      <!-- <?php //$amount = (isset($general_expenses[$field_title])) ? $general_expenses[$field_title] : $value['commission']; ?> -->
                    <?= ($value['type'] == 'percent') ? "<small>(".$value['commission']."%)</small>" : ("<small>(".$value['commission']." AED)</small>");?></span></label>
                    <?php if(($value['type'] == 'percent')) { 
                        $input_val = (int)$sold_item['price']*(int)$value['commission']/100;
                      ?>
                        <input type="hidden" name="general_expenses_amount[<?= $field_title; ?>]" value="<?= $input_val; ?>">
                      <?php } else {
                        $input_val = (int)$value['commission']; ?>
                        <input type="hidden" name="general_expenses_amount[<?= $field_title; ?>]" value="<?= $input_val; ?>">
                      <?php } ?>
                    <div class="col-md-6 col-sm-6 col-xs-12 ">
                      <input class="form-control select_withoutcross comission-class" 
                      type="text" 
                      name="general_expenses[<?= $field_title; ?>]" 
                      readonly=""
                      value="<?= (isset($input_val)) ? $input_val : $input_val; ?>">
                    </div>
                  </div>
                </div>

                <div class="item form-group">    
                  <div class="row">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="description"><?= ucwords($title->english) ?> Description <span class="required"></span>
                        </label>

                    <div class="col-md-6 col-sm-6 col-xs-12 ">
                      <textarea id="description" rows="2" name="general_description[<?= $field_title; ?>]" class="form-control col-md-7 col-xs-12"><?= (isset($general_description[$field_title])) ? $general_description[$field_title] : $value['description']; ?></textarea>
                    </div>
                  </div>
                </div> 

              <?php } ?>
              <?php 
                    $comission = 0;
                    $seller_percentage_charges = $this->db->select('commission,title')->get_where('seller_charges', ['user_type' => 'buyer', 'type' => 'percent','status' => 'active'])->result_array();
                    if ($seller_percentage_charges) {
                      foreach ($seller_percentage_charges as $key => $value) {
                        $title = json_decode($value['title']);
                        $field_title = strtolower(str_replace(' ', '_', $title->english));
                        if(isset($general_expenses[$field_title]) && ($general_expenses[$field_title] == 'no')) {
                        } else {
                          $per_comission = (int)$sold_item['price']*(int)$value['commission']/100;
                          $comission = $comission + $per_comission;
                        }
                      }
                    }
                    $seller_amount_charges = $this->db->select('commission,title')->get_where('seller_charges', ['user_type' => 'buyer', 'type' => 'amount','status' => 'active'])->result_array();

                    if ($seller_amount_charges) {
                      foreach ($seller_amount_charges as $key => $value) {
                        $title = json_decode($value['title']);
                        $field_title = strtolower(str_replace(' ', '_', $title->english));
                        if(isset($general_expenses[$field_title]) && ($general_expenses[$field_title] == 'no')) {
                        } else {
                          $comission = $comission + $value['commission'];
                        }
                      }
                    }
                    $payable_price = $sold_item['payable_amount'] + $comission; 
                 ?>

              <div class="form-group">
                <div class="row">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="amount">Amount in figure <span class="required"></span></label>
                <div class="col-md-6 col-sm-6 col-xs-12 ">
                  <input type="text" 
                    name="amount" 
                    id="amount"
                    readonly=""
                    value="<?= (isset($invoices) && !empty($invoices['amount'])) ? $invoices['amount'] : $payable_price; ?>"
                    class="form-control col-md-7 col-xs-12" />
                  <div class="amount-error"></div>
                </div>
                </div>
              </div>

            </div>
          </div>

          <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="payment_mode">Cash/Draft No. <span class="required"></span></label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input type="text" 
                name="payment_mode" 
                id="payment_mode"
                readonly=""
                value="<?php if(isset($sold_item)){ echo ucwords(str_replace('_', ' ', $sold_item['payment_mode'])); } ?>"
                class="form-control col-md-7 col-xs-12" />
              <div class="payment_mode-error"></div>
            </div>
          </div>

          <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="payment_terms">Payment Terms <span class="required"></span></label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input type="text" 
                name="payment_terms" 
                id="payment_terms"
                value="<?= (!empty($invoices)) ? $invoices['payment_terms'] : ''; ?>" 
                class="form-control col-md-7 col-xs-12" />
              <div class="payment_terms-error"></div>
            </div>
          </div>

          <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="description">Description <span class="required"></span></label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <textarea 
                name="description" 
                id="description"
                class="form-control col-md-7 col-xs-12" rows="4"><?php if(isset($item) && !empty($item)){ $item_detail = json_decode($item['detail']); echo$item_detail->english ; } ?></textarea>
              <!-- <input type="text" 
                 /> -->
              <div class="description-error"></div>
            </div>
          </div>

          <div class="item form-group" id="bank">
          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="make_idd">Choose Bank <span class="required"></span></label>
          <div class="col-md-6 col-sm-6 col-xs-12">
            <select class="form-control select_withoutcross" id="" name="bank">
              <option  value="">Select Bank</option>
              <?php foreach ($bank_info as $key => $value) {  ?>

                <option <?php if(count($bank_info[0]) >0){ if($value['id']==$invoices['bank_id']){echo "selected";} }?>
                  value="<?php echo $value['id']; ?>"> 
                    <?php echo $value['bank_name']; ?>
                </option>
              <?php } ?>
            </select>
            <div class="valuation_make_id-error text-danger"></div>
          </div>
        </div>

          <div class="ln_solid"></div>
          <div class="form-group">
            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
              <a href="<?= base_url('user-payments/'.$buyer['id']); ?>" class="btn btn-primary">Back</a>
              <?php 
              if (!$invoices) { ?>
                <input type="submit" class="btn btn-success" value="<?= $data['button_link']; ?>" id="generate_receipt" />
              <?php } elseif ($invoices['invoice_status'] == '0' && $invoices['receipt_status'] == '0' && $invoices['statement_status'] == '0' ) { ?>
                <input type="submit" class="btn btn-success" value="<?= $data['button_link']; ?>" id="generate_receipt" />
              <?php } ?>
              <?php if ($data['button_link'] == 'Update Payments') { ?> 
                <a href="<?= base_url().$data['invoice_link']; ?>" class="btn btn-success" id="generate_receipt" ><?= $data['receipt_button']; ?></a> 
              <?php } ?>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script>


</script>
<script>
  var info = "<?= $data['small_title'] == 'Payment Receipt' ;?>";
  var info2 = "<?= $data['small_title'] == 'Buyer Statement' ;?>";
  if (info) {
    $('#bank').hide();
  }
  if (info2) {
    $('#bank').hide();
  }

</script>