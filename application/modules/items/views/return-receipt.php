<?php 
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
 ?>

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
          action="<?= base_url('items/').$data['generate_link']; ?>" 
          id="frm" 
          class="form-horizontal form-label-left">
          <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
          
          <input type="hidden" name="user_id" value="<?= $seller['id']; ?>" />
          <input type="hidden" name="user_po_box" value="<?= $seller['po_box']; ?>" />
          <input type="hidden" name="user_address" value="<?= $seller['address']; ?>" />
          <input type="hidden" name="item_id" id="item_id" value="<?= $item['id']; ?>" />
          <input type="hidden" name="vin_number" id="vin_number" value="<?= $item['vin_number']; ?>" />
          <input type="hidden" name="vat" id="vat" value="<?= $vat_row['value']; ?>" />

          <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="payment_date">Item Enter Date <span class="required"></span></label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input type="text" 
                id="payment_date"
                value="<?php if(isset($item)){ echo date('Y-m-d', strtotime($item['created_on'])); } ?>"
                readonly="" 
                class="form-control col-md-7 col-xs-12" />
              <div class="payment_date-error text-danger"></div>
            </div>
          </div>

          <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="customer_name">Customer Name <span class="required"></span></label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input type="text" 
                name="customer_name" 
                id="customer_name"
                value="<?php if(isset($seller)){ echo $seller['username'];} ?>"
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
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="payment_terms">Payment Terms <span class="required"></span></label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input type="text" 
                name="payment_terms" 
                id="payment_terms"
                value="<?= (isset($invoices) && !empty($invoices['payment_terms'])) ? $invoices['payment_terms'] : '' ?>"
                class="form-control col-md-7 col-xs-12" />
              <div class="payment_terms-error"></div>
            </div>
          </div>

          <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="trn_no">TRN No <span class="required"></span></label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input type="number" 
                name="trn_no" 
                id="trn_no"
                value="<?= (isset($invoices) && !empty($invoices['trn_no'])) ? $invoices['trn_no'] : '' ?>"
                class="form-control col-md-7 col-xs-12" />
              <div class="trn_no-error"></div>
            </div>
          </div>

          <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="description">Description <span class="required"></span></label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <textarea 
                name="description" 
                id="description"
                class="form-control col-md-7 col-xs-12" rows="4"><?php if(isset($invoices) && !empty($invoices['description'])){ echo $invoices['description']; }elseif(isset($item) && !empty($item)){
                   $diss =json_decode($item['detail']);
                  echo $diss->english; } ?></textarea>
              <!-- <input type="text" 
                 /> -->
              <div class="description-error"></div>
            </div>
          </div>
          <?php 
            // if ($data['small_title'] == 'Payment Receipt') && $data['small_title'] == 'seller Statement'?>
          <div class="item form-group" id="bank">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="make_idd">Choose Bank <span class="required"></span></label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <select class="form-control select_withoutcross" id="" name="bank">
                <option  value="">Select Bank</option>
                <?php foreach ($bank_info as $key => $value) {  ?>

                  <option <?php if(count($bank_info[0]) >0){ if($value['id']==$bank_info[0]['bank_name']){echo "selected";} }?>
                    value="<?php echo $value['id']; ?>"> 
                      <?php echo $value['bank_name']; ?>
                  </option>
                <?php } ?>
              </select>
              <div class="valuation_make_id-error text-danger"></div>
            </div>
          </div>

          <!-- sections 1 -->

         <!--  <div class="col-md-12 col-sm-12 text-center">
            <div class="x_panel tile">
              <?php 
                // $comission = 0;
                // $item_expenses = $this->db->select_sum('amount')->get_where('item_expencses', ['item_id' => $item['id'], 'apply_vat' => 'yes'])->row_array();
                // if ($item_expenses) {
                //   $comission = $comission + $item_expenses['amount'];
                // }
                // $payable_price_without_discount = $sold_item['price'] - $comission;
                // if (isset($invoices) && !empty($invoices['discount'])) {
                //   $comission = $comission + $invoices['discount'];
                // }
                // $payable_price = $sold_item['price'] - $comission; 
             ?>

              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="amount">Amount in figure <span class="required"></span></label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="text" 
                    name="amount" 
                    id="amount"
                    readonly=""
                    value="<?php //if(isset($sold_item)){ echo $payable_price;} ?>"
                    class="form-control col-md-7 col-xs-12" />
                  <div class="amount-error"></div>
                  <input type="hidden" id="hidden_amount" value="<?php //if(isset($sold_item)){ echo $payable_price_without_discount;} ?>" />
                </div>
              </div>

            </div>
          </div> -->

          <!-- sections 2 -->

          <div class="col-md-12 col-sm-12 text-center">
            <div class="x_panel tile">
              <div class="x_title">
                <h2>Item Expense</h2>

                <div class="clearfix"></div>
              </div>
              <?php         
              $item_expenses = $this->db->select('*')->get_where('item_expencses', ['item_id' => $item['id']])->result_array();
              // print_r($item_expenses);die();
              ?>
              <?php 
              $payable_amount = 0;
              if ($item_expenses) {
                foreach ($item_expenses as $key => $value) { 
                  $title = $value['title'];
                  $field_title = strtolower(str_replace(' ', '_', $title));
                  if(!empty($invoices)){
                    $item_description = json_decode($invoices['item_description'], true); 
                  }  ?>
                    <?php $input_val = (int)$value['amount'];
                     $payable_amount += $input_val; ?>
                      <input type="hidden" name="item_expenses_amount[<?= $field_title; ?>]" value="<?= $input_val; ?>">
                      <input type="hidden" name="item_expenses[<?= $field_title; ?>]" value="<?= $value['apply_vat']; ?>">
                  <div class="item form-group" id="bank" >
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="item_expenses"><?= ucfirst($value['title']); ?> <span class="required"><small>(AED)</small></span></label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <input type="text"
                      readonly=""
                      value="<?php if(isset($value['amount'])){ echo $value['amount'];} ?>"
                      class="form-control col-md-7 col-xs-12" />
                    </div>
                  </div>

                  <div class="item form-group">    
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="item_description"><?= ucwords($title); ?> Description <span class="required"></span>
                          </label>

                      <div class="col-md-6 col-sm-6 col-xs-12">
                          <textarea id="description" rows="2" name="item_description[<?= $field_title; ?>]" class="form-control col-md-7 col-xs-12"><?= (isset($item_description[$field_title])) ? $item_description[$field_title] : $value['description']; ?></textarea>
                      </div>
                  </div> 
                <?php } 
              } else { ?>
                <label class="col-md-12 col-sm-12 col-xs-12" for="item_expenses">No Item Expense</label>
              <?php }  ?>
              <input type="hidden" name="payable_amount" value="<?= $payable_amount; ?>">

            </div>
          </div>

          <div class="form-group">
            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
              <a href="<?= $data['back_url']; ?>" class="btn btn-primary">Back</a>
              <?php if (!isset($invoices) || $invoices['invoice_status'] == '0') { ?>
                <input type="submit" class="btn btn-success" value="<?= $data['button_link']; ?>" id="generate_payments" />
              <?php } ?>
              <?php if ($data['button_link'] == 'Update Payments') { ?>
                <?php if ($invoices['invoice_status'] == '0') { ?>
                  <a href="<?= base_url().$data['invoice_link']; ?>" class="btn btn-success" id="generate_receipt" >Generate Invoice</a> 
                <?php }else{ ?>
                  <a href="<?= base_url().$data['invoice_link']; ?>" class="btn btn-success" id="generate_receipt" >View Invoice</a> 
                <?php } ?>
              <?php } ?>
            </div>
          </div>

        </form>
      </div>
    </div>
  </div>
</div>

<script>

  $('.comission-class').on('change', function(){
    var amount = $('#amount').val();
    var win_price = $('#win_price').val();
    var field_value= $(this).val();
    var comission = $(this).data('comission');
    var type = $(this).data('type');
    if (type == 'percent') {
      var price = win_price*comission/100;
    } else {
      var price = comission;
    }
    var discount = "<?=(isset($invoices) && !empty($invoices['discount'])) ? $invoices['discount'] : 0; ?>";
    // alert(price);

    if (field_value == 'no') {
      $('#amount').val(Number(amount)+Number(price));
      if (discount != 0) {
        var amount_with_discount = Number(amount)+Number(price);
        var amount_without_discount = Number(amount_with_discount) + Number(discount);
      } else {
        var amount_without_discount = Number(amount) + Number(price);
      }
      $('#hidden_amount').val(amount_without_discount);
    }
    if (field_value == 'yes') {
      $('#amount').val(Number(amount)-Number(price));
      if (discount != 0) {
        var amount_with_discount = Number(amount)-Number(price);
        var amount_without_discount = Number(amount_with_discount) + Number(discount);
      } else {
        var amount_without_discount = Number(amount) - Number(price);
      }
      $('#hidden_amount').val(amount_without_discount);
    }

  });

  $('#discount').on('keyup', function(){
    var hidden_amount = $('#hidden_amount').val();
    var discount = $(this).val();
    var price = discount;
    $('#amount').val(Number(hidden_amount)-Number(price));
    // if (field_value == 'no') {
    //   $('#amount').val(Number(amount)+Number(price));
    // }
    // if (field_value == 'yes') {
    // }

  });

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