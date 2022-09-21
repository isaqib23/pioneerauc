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
        <h2>Deposit Detail <small>for <?= $user['username'].' - '.$user['email']; ?></small></h2>
        <div class="nav navbar-right">
          <a href="<?= base_url('user-deposits-general-form/'.$user['id']); ?>" class="btn btn-primary">Add Deposit</a>
          <a href="<?= base_url('user-deposits-return-form/'.$user['id']); ?>" class="btn btn-primary">Return Deposit</a>
        </div>
        <div class="clearfix"></div>
      </div>
      <div class="x_content">
        <h2>User Balance: <?= $currentBalance['amount']; ?></h2>
        <table id="datatable" class="table table-striped table-bordered">
          <thead>
            <tr>
              <th>ID</th>
              <th>Deposit Type</th>
              <th>Deposit Mode</th>
              <th>Deposit Amount</th>
              <th>Account</th>
              <th>Created On</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php
            if( (isset($history)) && !(empty($history)) ){
              foreach ($history as $key => $value) {
                //$auction_name = json_decode($value['auction_title']);
                ?>
                <tr>
                  <td><?= $value['id']; ?></td>
                  <td><?= ucfirst($value['deposit_type']); ?></td>
                  <td><?= ucwords(str_replace('_', ' ', $value['payment_type'])); ?></td>
                  <td><?= ucfirst($value['amount']); ?></td>
                  <td><?= $value['account']; ?></td>
                  <td><?= $value['created_on']; ?></td>
                  <td><?= ucfirst($value['status']); ?></td>
                  <td>
                    <?php if ($value['account'] == 'DR') : ?>
                        <a href="<?= base_url('auction/user_payment_receipt/').$value['id']; ?>"  class="btn btn-info btn-xs" target="_blank"><i class="fa fa-print"></i>Receipt</a>
                    <?php endif; ?>
                    <!-- <?php //if($value['account'] == 'DR'): ?> -->
                      <!-- <button onclick="deleteRecord(this)" type="button" data-obj="auction_deposit" data-token-name="<?//= $this->security->get_csrf_token_name();?>" data-token-value="<?//=$this->security->get_csrf_hash();?>" data-id="<?//= $value['id']; ?>" data-url="<?//=base_url().'user-remove-general-deposits'?>" class="btn btn-danger btn-xs" title="Remove"><i class="fa fa-trash"></i> Remove </button> -->
                    <!-- <?php //endif; ?> -->

                    <!--  <a href="<?//= base_url('user-remove-general-deposits/').$value['id'].'/'.$value['user_id']; ?>" 
                      class="btn btn-danger btn-xs"
                    ><i class="fa fa-trash"></i> Remove</a> -->
                      <!-- <?php //if ($value['status'] == 'approved') : ?> -->
                      <!-- <button onclick="Refund(this)" type="button" data-obj="auction_deposit" data-field="refund" data-id="<?//= $value['id']; ?>" data-amount="<?//= $value['amount']; ?>" data-url="<?//=base_url().'auction/refund'?>" class="btn btn-primary btn-xs" title="Refund"><i class="fa fa-money"></i> Refund </button> -->
                    <!-- <?php //endif; ?> -->
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
selectedInputs = ['auction_id','auction_item_id','deposit','deposit_mode'];
$(document).ready(function(){
  $('#datatable').DataTable({
    "order": [[ 0, "desc" ]]
  });
});

$('#auction_id').on('change', function(event){
  event.preventDefault();

  var id = $(this).val();

  $.ajax({
    method: "POST",
    url: "<?= base_url('users/get_ai_list'); ?>",
    data: {'id':id},
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
    data: {'id':id},
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
    data: {'mode':mode},
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