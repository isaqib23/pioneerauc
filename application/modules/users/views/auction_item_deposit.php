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
        <h2>Auction Item Security <small></small></h2>
        <div class="clearfix"></div>
      </div>
      <div class="x_content">
        <br>
        <form name="myform" method="post"
          action="<?= base_url('users/add_auction_item_deposit'); ?>" 
          id="frm" 
          class="form-horizontal form-label-left">
          <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
          
          <input type="hidden" name="user_id" value="<?= $user['id']; ?>" />
          <input type="hidden" name="item_id" id="item_id" />

          <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="username">Customer Name <span class="required"></span></label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input type="text" 
                name="username" 
                id="username"
                value="<?php if(isset($user)){ echo $user['username'];} ?>"
                disabled="" 
                class="form-control col-md-7 col-xs-12" />
              <div class="username-error text-danger"></div>
            </div>
          </div>

          <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="userEmail">Customer Email <span class="required"></span></label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input type="text" 
                name="userEmail" 
                id="userEmail"
                disabled=""
                value="<?php if(isset($user)){ echo $user['email'];} ?>"
                class="form-control col-md-7 col-xs-12" />
              <div class="userEmail-error" id="slots-error"></div>
            </div>
          </div>

          <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="auction_id">Available Auctions <span class="required">*</span></label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <select name="auction_id" id="auction_id" class="select2_single form-control validThis" tabindex="-1">
                <option value="" selected="selected" disabled="disabled">Choose</option>
                <?php
                if(isset($auctions)){
                  foreach ($auctions as $key => $auction) {
                    $title = json_decode($auction['title']);
                    ?>
                    <option value="<?= $auction['id']; ?>"><?= $title->english.' ('.$auction['access_type'].')'; ?></option>
                    <?php
                  }
                }
                ?>
              </select>
              <div class="auction_id-error text-danger"></div>
            </div>
          </div>

          <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="auction_item_id">Auction Items <span class="required">*</span></label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <select name="auction_item_id" id="auction_item_id" class="select2_single form-control" tabindex="-1">
                <option value="" selected="selected" disabled="disabled">Choose</option>
                
              </select>
              <div class="auction_item_id-error text-danger"></div>
            </div>
          </div>

          <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="deposit">Deposit Amount <span class="required">*</span></label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input type="text" 
                name="deposit"
                id="deposit"
                readonly=""
                value="0" 
                oninput="this.value=this.value.replace(/[^0-9.]/g,'');"
                class="form-control col-md-7 col-xs-12 parsley-success validThis" />
              <div class="deposit-error text-danger"></div>
            </div>
          </div>

          <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="deposit_mode">Deposit Mode <span class="required">*</span></label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <select name="deposit_mode" id="deposit_mode" class="select2_single form-control validThis" tabindex="-1">
                <option value="" selected="selected" disabled="disabled">Choose</option>
                <option value="cheque">Cheque</option>
                <option value="manual_deposit">Manual Deposit</option>
                <option value="cash">Cash</option>
              </select>
              <div class="deposit_mode-error text-danger"></div>
            </div>
          </div>

          <div id="mode_fields"></div>

          <div class="ln_solid"></div>
          <div class="form-group">
            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
              <a href="<?= base_url('customers'); ?>" class="btn btn-primary">Back</a>
              <input type="submit" class="btn btn-success" value="Add" id="add_deposit" />
            </div>
          </div>

        </form>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_title">
        <h2>Security Detail <small>Security history for selected user</small></h2>
        <!-- <div class="nav navbar-right">
          <h2>Total Package Power: <?= $total_package_power; ?> TH/s</h2>
        </div> -->
        <div class="clearfix"></div>
      </div>
      <div class="x_content">
        <table id="datatable" class="table table-striped table-bordered">
          <thead>
            <tr>
              <th>Auction Name</th>
              <th>Item Name</th>
              <th>Deposit Amount</th>
              <th>Deposit Mode</th>
              <th>Created On</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php 
            if( (isset($history)) && !(empty($history)) ){
              foreach ($history as $key => $value) {
                $auction_name = json_decode($value['auction_title']);
                ?>
                <tr>
                  <td><?= $auction_name->english; ?></td>
                  <?php $item_names = json_decode($value['item_name']); ?>
                  <td><?= $item_names->english; ?></td>
                  <td><?= $value['deposit']; ?></td>
                  <td><?php switch ($value['deposit_mode']) {
                    case 'cheque':
                      echo "Cheque";
                      break;
                    case 'manual_deposit':
                      echo "Manual Deposit";
                      break;
                    case 'card':
                      echo "Cradit Card";
                      break;
                    case 'cash':
                      echo "Cash";
                      break;
                    case 'bank_transfer':
                      echo "Bank Transfer";
                      break;
                    
                    default:
                      echo "Not Available";
                      break;
                  } ($value['deposit_mode'] == 'cheque') ? 'Cheque' : 'Manual Deposit'; ?></td>
                  <td><?= $value['created_on']; ?></td>
                  <td><?= ucfirst($value['status']); ?></td>
                  <td>
                    <!-- <button onclick="deleteRecord(this)" type="button" data-obj="auction_item_deposits" data-id="<?//= $value['id']; ?>" data-url="<?//=base_url().'user-remove-deposits'; ?>" data-token-name="<?//= $this->security->get_csrf_token_name();?>" data-token-value="<?//=$this->security->get_csrf_hash();?>" class="btn btn-danger btn-xs" title="Remove"><i class="fa fa-trash"></i> Remove </button> -->
                    <!-- <a href="<?//= base_url('user-remove-deposits/') . $value['user_id'] . '/' . $value['auction_id']. '/' .$value['auction_item_id']; ?>" 
                      class="btn btn-danger btn-xs"
                    ><i class="fa fa-trash"></i> Remove</a> -->
                    <?php if ($value['status'] == 'approved') : ?>
                      <button onclick="Refund(this)" type="button" data-obj="auction_item_deposits" data-field="refund" data-token-name="<?= $this->security->get_csrf_token_name();?>" data-token-value="<?=$this->security->get_csrf_hash();?>" data-id="<?= $value['id']; ?>" data-amount="<?= $value['deposit']; ?>" data-url="<?=base_url().'auction/refund'?>" class="btn btn-primary btn-xs" title="Refund"><i class="fa fa-money"></i> Refund </button>
                    <?php endif; ?>
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
  var token_name = '<?= $this->security->get_csrf_token_name();?>';
  var token_value = '<?=$this->security->get_csrf_hash();?>';
selectedInputs = ['auction_id','auction_item_id','deposit','deposit_mode'];
$(document).ready(function(){
  $('#datatable').DataTable();
});

$('#auction_id').on('change', function(event){
  event.preventDefault();

  var id = $(this).val();
  var user_id = "<?= isset($value['user_id']) ? $value['user_id'] : ''; ?>";

  $.ajax({
    method: "POST",
    url: "<?= base_url('users/get_ai_list'); ?>",
    data: {'id':id, 'user_id':user_id, [token_name]:token_value},
    beforeSend: function(){
      $('#loading').show();
    },
    success: function(msg){
      //console.log(msg);
      $('#loading').hide();
      var objData = $.parseJSON(msg);
      if(objData.status){
        $('#auction_item_id').html(objData.data);
      }else{
        $('#auction_item_id').html('<option value="">Items are not available</option>');
        $('#deposit').val(0);
        $('#item_id').val('');
      }
    }
  });
});

$('#auction_item_id').on('change', function(event){
  event.preventDefault();

  var id = $(this).val();

  $.ajax({
    method: "POST",
    url: "<?= base_url('users/get_ai_deposit'); ?>",
    data: {'id':id, [token_name]:token_value},
    beforeSend: function(){
      $('#loading').show();
    },
    success: function(msg){
      console.log(msg);
      $('#loading').hide();
      var objData = $.parseJSON(msg);
      if(objData.status){
        $('#deposit').val(objData.deposit);
        $('#item_id').val(objData.item_id);
      }
    }
  });
});

$('#add_deposit').on('click', function(event){
  event.preventDefault();
  var validation = false;
  //selectedInputs = ['auction_id','auction_item_id','deposit'];
  validation = validateFields(selectedInputs);
  if(validation == false){
    return false;
  }
  if(validation == true){
    $(this).closest("form").submit();
  }
});

$("#deposit_mode").on("change", function(event){
  event.preventDefault();
  var mode = $(this).val();
  console.log(mode);
  $.ajax({
    method: "POST",
    url: "<?= base_url('users/get_deposit_mode_fields'); ?>",
    data: {'mode':mode, [token_name]:token_value},
    beforeSend: function(){
      $('#loading').show();
    },
    success: function(msg){
      console.log(msg);
      $('#loading').hide();
      var objData = $.parseJSON(msg);
      if(objData.status){
        $('#mode_fields').html(objData.fields);
      }
    }
  });
});
</script>