 <!-- PNotify -->
<link href="<?php echo base_url(); ?>assets_admin/vendors/pnotify/dist/pnotify.css" rel="stylesheet">
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
        <h2>Return Deposit <small></small></h2>
        <div class="clearfix"></div>
      </div>
      <div class="x_content">
        <br>
        <form name="myform" method="post"
          action="<?= base_url('user-deposits-general-return'); ?>" 
          id="frm" 
          class="form-horizontal form-label-left">
          <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
          
          <input type="hidden" name="user_id" value="<?= $user['id']; ?>" />

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

          <!-- <div class="form-group">
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
          </div> -->

          <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="deposit">Return Amount <span class="required">*</span></label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input type="text" 
                name="deposit" 
                id="deposit"
                value=""
                maxlength="7" 
                placeholder="Enter amount" 
                oninput="this.value=this.value.replace(/[^0-9.]/g,'');"
                class="form-control col-md-7 col-xs-12 parsley-success" />
              <div class="deposit-error text-danger"></div>
            </div>
          </div>


			<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12" for="deposit">Return Description <span class="required">*</span></label>
				<div class="col-md-6 col-sm-6 col-xs-12">
					<textarea type="text"
						   name="deposit_description"
						   id="deposit_description"
						   placeholder="Enter Description"
							  class="form-control col-md-7 col-xs-12 parsley-success" ></textarea>
					<div class="deposit-error text-danger"></div>
				</div>
			</div>

          <div id="mode_fields"></div>

          <div class="ln_solid"></div>
          <div class="form-group">
            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
              <a href="<?= base_url('user-deposits-general/'.$user['id']); ?>" class="btn btn-primary">Back</a>
              <input type="submit" class="btn btn-success" value="Return" id="return_deposit" />
            </div>
          </div>

        </form>
      </div>
    </div>
  </div>
</div>
<script src="<?php echo base_url(); ?>assets_admin/vendors/pnotify/dist/pnotify.js"></script>

<script>
selectedInputs = ['deposit'];

// $('#auction_id').on('change', function(event){
//   event.preventDefault();

//   var id = $(this).val();

//   $.ajax({
//     method: "POST",
//     url: "<?= base_url('users/get_ai_list'); ?>",
//     data: {'id':id},
//     beforeSend: function(){
//       $('#loading').show();
//     },
//     success: function(msg){
//       //console.log(msg);
//       $('#loading').hide();
//       var objData = $.parseJSON(msg);
//       if(objData.status){
//         $('#auction_item_id').html(objData.data);
//       }else{
//         $('#auction_item_id').html('<option value="">Items are not available</option>');
//         $('#deposit').val(0);
//         $('#item_id').val('');
//       }
//     }
//   });
// });

// $('#auction_item_id').on('change', function(event){
//   event.preventDefault();

//   var id = $(this).val();

//   $.ajax({
//     method: "POST",
//     url: "<?= base_url('users/get_ai_deposit'); ?>",
//     data: {'id':id},
//     beforeSend: function(){
//       $('#loading').show();
//     },
//     success: function(msg){
//       console.log(msg);
//       $('#loading').hide();
//       var objData = $.parseJSON(msg);
//       if(objData.status){
//         $('#deposit').val(objData.deposit);
//         $('#item_id').val(objData.item_id);
//       }
//     }
//   });
// });

$('#return_deposit').on('click', function(event){
  event.preventDefault();
  var validation = false;
  var returnAmount = $('#deposit').val();
  var currentBalance = '<?= ($currentBalance) ? $currentBalance['amount'] : 0; ?>';
  console.log(selectedInputs);
  validation = validateFields(selectedInputs);
  if(validation == false){
      return false;
  }
  if(validation == true){
    if (parseInt(currentBalance) < parseInt(returnAmount)) {
        PNotify.removeAll();
        new PNotify({
            text: 'Enter amount is greater than user balance. User current balance is '+currentBalance+' AED',
            type: 'error',
            addclass: 'custom-error',
            title: "<?= $this->lang->line('error'); ?>",
            styling: 'bootstrap3'
        });
    } else {
      if (parseInt(returnAmount) < 1) {
          PNotify.removeAll();
          new PNotify({
              text: 'Amount must be greater than 0 AED',
              type: 'error',
              addclass: 'custom-error',
              title: "<?= $this->lang->line('error'); ?>",
              styling: 'bootstrap3'
          });
      } else {
          $(this).closest("form").submit();
      }
    }
  }
});

$("#deposit_mode").on("change", function(event){
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
