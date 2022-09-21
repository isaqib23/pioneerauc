<!-- Design Management start -->
<div class="page-title">
  <div class="title_left">
    <h3>Users <small></small></h3>
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
        <h2>Payment Details <small></small></h2>
        <div class="clearfix"></div>
      </div>
      <div class="x_content">
        <br>
        <form name="myform" method="post"
          action="<?= base_url('pay-payment'); ?>" 
          id="frm" 
          class="form-horizontal form-label-left">
          <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
          
          <input type="hidden" name="user_id" value="<?= $user['id']; ?>" />
          <input type="hidden" name="sold_item_id" value="<?= $sold_item['id']; ?>" />

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
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="payable_amount">Payable Amount <span class="required">*</span></label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input type="text" 
                name="payable_amount"
                id="payable_amount"
                value="<?= $sold_item['payable_amount']; ?>"
                oninput="this.value=this.value.replace(/[^0-9.]/g,'');"
                readonly=""
                class="form-control col-md-7 col-xs-12 parsley-success" />
              <div class="payable_amount-error text-danger"></div>
            </div>
          </div>

          <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="payment_mode">Payment Mode <span class="required">*</span></label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="text" readonly="" class="form-control col-md-7 col-xs-12 parsley-success" name="payment_mode" value="<?php echo $sold_item['payment_mode'];?>">
                  <div class="payment_mode-error text-danger"></div>
              </div>
          </div>
          <div id="mode_fields"><?php if(isset($fields) && !empty($fields)){echo $fields; } ?></div>
          <div class="ln_solid"></div>
          <div class="form-group">
            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
              <a href="<?= base_url('user-payments/'.$user['id']); ?>" class="btn btn-primary">Back</a>
              <!-- <input type="submit" class="btn btn-success" value="Pay" id="add_deposit"/> -->
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script>

selectedInputs = ['payment_mode'];
$('#add_deposit'.on('click', function(event){
  event.preventDefault();
  var validation = false;
  selectedInputs = arrayRemove(selectedInputs,'auction_id');
  selectedInputs = arrayRemove(selectedInputs,'auction_item_id');
  selectedInputs = arrayRemove(selectedInputs,'deposit');
  selectedInputs = arrayRemove(selectedInputs,'deposit_mode');
  console.log(selectedInputs);
  validation = validateFields(selectedInputs);
  if(validation == false){
    return false;
  }
  if(validation == true){
    $(this).closest("form").submit();
  }
});

$("#payment_mode").on("change", function(event){
  event.preventDefault();
  var mode = $(this).val();
  // console.log(mode);
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

