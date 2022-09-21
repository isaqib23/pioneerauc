<table cellpadding="0" id="qa_payments" cellspacing="0" class="table_id" width="100%" >
  <thead>
    <tr>
      <th>Expense</th>
      <th>Detail</th>
      <th>Amount</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>Sale Price</td>
      <td>Heighest Bid Price</td>
      <td><?= $invoices['win_price']; ?></td>
    </tr>
    <?php 
    $general_expenses = json_decode($invoices['general_expenses'], true); 
    if ($general_expenses) { 
      $general_description = json_decode($invoices['general_description'], true); 
      $general_expenses_amount = json_decode($invoices['general_expenses_amount'], true); 
      foreach ($general_expenses as $key => $value) {
        if ($value == 'yes') {
          $title = ucwords(str_replace('_', ' ', $key));?>
          <tr>
            <td><?= $title; ?></td>
            <td><?= $general_description[$key]; ?></td>
            <td><?= $general_expenses_amount[$key]; ?></td>
          </tr>
    <?php } } } ?>
    <?php 
    $item_expenses = json_decode($invoices['item_expenses'], true); 
    if ($item_expenses) { 
      $item_description = json_decode($invoices['item_description'], true); 
      $item_expenses_amount = json_decode($invoices['item_expenses_amount'], true); 
      foreach ($item_expenses as $key => $value) {
        if ($value == 'yes') {
          $title = ucwords(str_replace('_', ' ', $key));?>
          <tr>
            <td><?= $title; ?></td>
            <td><?= $item_description[$key]; ?></td>
            <td><?= $item_expenses_amount[$key]; ?></td>
          </tr>
    <?php } } } ?>
    <?php if ($invoices['discount'] > 0) { ?>
      <tr>
        <td>Discount </td>
        <td></td>
        <td><?= $invoices['discount']; ?></td>
      </tr>
    <?php } ?>
    <tr>
      <td><b>Total Price </b></td>
      <td><b>Price after subtract expenses </b></td>
      <td><b><?= $invoices['payable_amount']; ?></b></td>
    </tr>
  </tbody>
</table>


<!-- 
<div class="row">
  <div class="col-sm-12">
    <label>Heighest Bid Price *</label><span><?php $invoices['win_price']; ?></span>
    <?php $general_expenses = json_decode($invoices['general_expenses']); 
    if ($general_expenses) { 
      foreach ($general_expenses as $key => $value) {
        $title = strtolower(str_replace('_', ' ', $value));?>
          <label><?//= $title ?>:  </label><span></span>
    <?php } } ?>
  </div>
  <div class="col-sm-12">
    <label>PASSWORD *</label>
    <input type="passowrd" class="form-control" name="">
  </div>
  <div class="col-sm-12">
    <div class="button-row">
      <button class="btn btn-default">LOGIN</button>
    </div>
  </div>
</div> -->

<script>
  
  $('#qa_payments').dataTable({
    "searching": false,
    "bPaginate": false,
    "ordering": false,
    "info":     false
  });
</script>