
<div class="clearfix"></div>
<div class="col-md-12 col-sm-12 col-xs-12">
  <div class="x_panel">
      <div class="x_title">
          <h2>
              <?php echo $small_title; ?>
          </h2>
          <div class="clearfix"></div>
      </div>
      <div class="x_content"></div>
        <?php if($this->session->flashdata('msg')){ ?>
      <div class="alert">
          <div class="alert alert-success alert-dismissible fade in" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: black;"><span aria-hidden="true">×</span>
            </button>
            <?php  echo $this->session->flashdata('msg');   ?>
        </div>
    </div>
  <?php }?>
  <div id="resultt"></div>
  
  <form method="post" data-parsley-validate="" id="demo-form2" action="" enctype="multipart/form-data" class="form-horizontal form-label-left">  
  <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />           

    <div class="item form-group">    
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="ac_name">Account Name
            <span class="required">*</span>
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <input type="text"  name="ac_name" required="required"  value="<?php if(isset($account_info) && !empty($account_info)) { echo $account_info['ac_name']; } ?>" class="form-control col-md-7 col-xs-12">
        </div>
    </div>

     <div class="item form-group">    
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="bank_name">Bank Name
            <span class="required">*</span>
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <input type="text"  name="bank_name"  required="required" value="<?php if(isset($account_info) && !empty($account_info)) { echo $account_info['bank_name']; } ?>" class="form-control col-md-7 col-xs-12">
        </div>
    </div>

     <div class="item form-group">    
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="iban">IBAN
            <span class="required">*</span>
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <input type="text"  name="iban" required="required" value="<?php if(isset($account_info) && !empty($account_info)) { echo $account_info['iban']; } ?>" class="form-control col-md-7 col-xs-12">
        </div>
    </div>

     <div class="item form-group">    
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title_arabic">Account Number
            <span class="required">*</span>
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <input type="text"  name="ac_number" required="required" value="<?php if(isset($account_info) && !empty($account_info)) { echo $account_info['ac_number']; } ?>" class="form-control col-md-7 col-xs-12">
        </div>
    </div>

    <div class="item form-group">    
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title_arabic">Swift Code
            <span class="required">*</span>
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <input type="text"  name="swift_code" required="required" value="<?php if(isset($account_info) && !empty($account_info)) { echo $account_info['swift_code']; } ?>" class="form-control col-md-7 col-xs-12">
        </div>
    </div>

    <div class="item form-group">    
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title_arabic">Routing Number
            <span class="required">*</span>
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <input type="text"  name="routing_number" required="required" value="<?php if(isset($account_info) && !empty($account_info)) { echo $account_info['routing_number']; } ?>" class="form-control col-md-7 col-xs-12">
        </div>
    </div>

    
    <div class="ln_solid"></div>
    <div class="form-group">
        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-2">
            <!-- <button type="submit" id="send" class="btn btn-success"><?php echo $status_btn; ?></button> -->
            <button type="submit" id="send" class="btn btn-success">Add</button>
        </div>
    </div>

</form>

</div>
</div>



<!-- listing view -->
<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_title">
        <h2>Added Banks List<small></small></h2>
        <!-- <div class="nav navbar-right">
          <h2>Total Package Power: <?= $total_package_power; ?> TH/s</h2>
        </div> -->
        <div class="clearfix"></div>
      </div>
      <div class="x_content">
        <table id="tableData" class="table table-striped table-bordered">
          <thead>
            <tr>
              <th>Account Name</th>
              <th>Bank Name</th>
              <th>IBAN</th>
              <th>Account Number</th>
              <th>Swift Code</th>
              <th>Routing Number</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php 
            if( (isset($user)) && !(empty($user)) ){
              foreach ($user as $key => $value) {
                // $auction_name = json_decode($value['auction_title']);
                ?>
                <tr>
                  <td><?= ucfirst($value['ac_name']); ?></td>
                  <td><?= ucfirst($value['bank_name']); ?></td>
                  <td><?= $value['iban']; ?></td>
                  <td><?= $value['ac_number']; ?></td>
                  <td><?= $value['swift_code']; ?></td>
                  <td><?= ucfirst($value['routing_number']); ?></td>
                  <td>
                    <button onclick="deleteRecord(this)" type="button" data-obj="bank_info" data-token-name="<?= $this->security->get_csrf_token_name();?>" data-token-value="<?=$this->security->get_csrf_hash();?>" data-id="<?= $value['id']; ?>" data-url="<?=base_url().'delete-bank-detail'; ?>" class="btn btn-danger btn-xs" title="Remove"><i class="fa fa-trash"></i> Remove </button>
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
<!-- End listing view -->

<script src="<?php echo base_url(); ?>assets_admin/js/jquery.geocomplete.js"></script>
<script type="text/javascript">
    $("#address").geocomplete({details:"form#demo-form2"});
    var ok;
    var url = '<?php echo base_url();?>';
    var formaction_path = '<?php echo $formaction_path;?>';
  
     // You can use the locally-scoped $ in here as an alias to jQuery.
  // $('#demo-form2').parsley().on('field:validated', function() {
  //   var ok = $('.parsley-error').length === 0;
  // })
  $('#demo-form2').on('form:submit', function() {

        var formData = new FormData($("#demo-form2")[0]);
          $.ajax({
              url: url + 'settings/' + formaction_path,
              type: 'POST',
              data: formData,
              cache: false,
              contentType: false,
              processData: false
          }).then(function(data) 
          {
            var objData = jQuery.parseJSON(data);
            console.log(objData);
            if (objData.error == false)
            {
              alert('dddd');
                // window.location = url + 'cms/terms';
                 $("#resultt").html('<div class="alert" ><div class="alert alert-domain alert-success alert-dismissible fade in  msg-alert" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: black"><span aria-hidden="true">×</span> </button>' + objData.msg + '</div></div>');
            } 
            else
            {
                $('.msg-alert').css('display', 'block');
                $("#resultt").html('<div class="alert" ><div class="alert alert-domain alert-danger alert-dismissible fade in  msg-alert" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: black"><span aria-hidden="true">×</span> </button>' + objData.msg + '</div></div>');
            }
          });
              return false; 

  });

</script>

<!-- Get the Bank Details For listing -->
<!-- <script>
selectedInputs = ['auction_id','auction_item_id','deposit','deposit_mode'];
$(document).ready(function(){
  $('#tableData').DataTable();
});

$('#auction_id').on('change', function(event){
  event.preventDefault();

  var id = $(this).val();
  var user_id = "<?= isset($value['user_id']) ? $value['user_id'] : ''; ?>";

  $.ajax({
    method: "POST",
    url: "<?= base_url('users/get_ai_list'); ?>",
    data: {'id':id, 'user_id':user_id},
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

</script> -->