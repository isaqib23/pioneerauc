<div class="col-md-10">
	<div class="message_rule"></div>
</div>
<?php    $bidding_info[0]['bid_start_time']."<br>"; ?>
<?php  $auction_expiry_time = date('m/d/Y h:i:s A', strtotime("+1 day", strtotime($auction_expiry_time))); ?>
  <form method="post" novalidate="" id="demo-form2" name="rule_form_data" action="" enctype="multipart/form-data" class="form-horizontal form-label-left rule_form_data">
    <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
    <?php if(isset($bidding_info) && !empty($bidding_info)){ ?>
      <input type="hidden" name="id" value="<?php if(isset($bidding_info) && !empty($bidding_info)) { echo $bidding_info[0]['id']; } ?>">
    <?php } ?>   
    <input type="hidden" name="item_id" value="<?php if(isset($item_id) && !empty($item_id)) { echo $item_id; } ?>">
    <input type="hidden" name="auction_id" value="<?php if(isset($auction_id) && !empty($auction_id)) { echo $auction_id; } ?>">

    <div class="item form-group">    
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="bid_start_price">Start Price <span class="required">*</span>
            </label>

        <div class="col-md-6 col-sm-6 col-xs-12">
            <input type="number" id="bid_start_price"  required="required" name="bid_start_price" value="<?php if(isset($bidding_info) && !empty($bidding_info)) { echo $bidding_info[0]['bid_start_price']; } ?>" class="form-control col-md-7 col-xs-12">
          <span class="bid_start_price-error text-danger"></span>
        </div>
    </div> 
      
    <div class="item form-group">    
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="bid_start_price">Minimum Bid Price  <span class="required">*</span>
      </label>

      <div class="col-md-6 col-sm-6 col-xs-12">
        <input type="number" id="minimum_bid_price"  required="required" name="minimum_bid_price" value="<?php if(isset($bidding_info) && !empty($bidding_info)) { echo $bidding_info[0]['minimum_bid_price']; } ?>" class="form-control col-md-7 col-xs-12">
        <span class="minimum_bid_price-error text-danger"></span>
      </div>
    </div> 


    <!-- <div class="item form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="allowed_bids">Maximum Allowed Bid </label>
      <div class="col-md-6 col-sm-6 col-xs-12">
        <input type="number" id="allowed_bids"  required="required" name="allowed_bids" value="<?php if(isset($bidding_info) && !empty($bidding_info)) { echo $bidding_info[0]['allowed_bids']; } ?>" class="form-control col-md-7 col-xs-12">
        <span class="allowed_bids-error text-danger"></span>
      </div>
    </div> -->

    <div class="item form-group">    
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="bid_start_time">Start Time <span class="required">*</span>
      </label>

      <div class="col-md-6 col-sm-6 col-xs-12  ">
        <div class='input-group date' id='bid_start_time'>
          <input type='text' class="form-control" readonly name="bid_start_time" placeholder="Bidding Start Time" value="<?php if(isset($bidding_info[0]['bid_start_time']) && !empty($bidding_info[0]['bid_start_time'])) { echo date('m/d/Y H:i:s A',strtotime($bidding_info[0]['bid_start_time'])); } ?>" required="required" />
          <span class="bid_start_time-error text-danger"></span>
          <span class="input-group-addon">
             <span class="glyphicon glyphicon-calendar"></span>
          </span>
        </div>
      </div>
    </div>

    <div class="item form-group">    
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="bid_end_time">End Time <span class="required">*</span>
      </label>

      <div class="col-md-6 col-sm-6 col-xs-12  ">
        <div class='input-group date' id='bid_end_time'>
          <input type='text' class="form-control" readonly name="bid_end_time" placeholder="Bidding Expiry Time" value="<?php if(isset($bidding_info[0]['bid_end_time']) && !empty($bidding_info[0]['bid_end_time'])) { echo date('m/d/Y H:i:s A',strtotime($bidding_info[0]['bid_end_time'])); } ?>" required />
          <span class="input-group-addon">
            <span class="glyphicon glyphicon-calendar"></span>
            <span class="bid_end_time-error text-danger"></span>
          </span>
        </div>
      </div>
    </div>

    <div class="item form-group">    
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="security">Security Deposit<span class="required">*</span></label>
      <div class="col-md-6 col-sm-6 col-xs-12"> 
        <select required="" class="form-control" id="security" name="security">
          <option <?php if(isset($bidding_info) && !empty($bidding_info) && $bidding_info[0]['security'] == 'no'){ echo 'selected'; }?> value="no">No</option>
          <option <?php if(isset($bidding_info) && !empty($bidding_info) && $bidding_info[0]['security'] == 'yes'){ echo 'selected'; }?> value="yes">Yes</option>
        </select>
        <span class="security-error text-danger"></span>
      </div>
    </div>

    <div class="form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="deposit">Deposit Amount 
        <span class="required">*</span>
      </label>
      <div class="col-md-6 col-sm-6 col-xs-12">
        <input type="text" 
          name="deposit" 
          id="deposit"
          value="<?php if(isset($bidding_info)){ echo $bidding_info[0]['deposit'];} ?>" 
          oninput="this.value=this.value.replace(/[^0-9]/g,'');"
          class="form-control col-md-7 col-xs-12 parsley-success validThis" />
        <ul class="parsley-errors-list"></ul>
      </div>
    </div>

    <script>
      $(document).ready(function(){
        var val = $("#security").val();
        //alert(val);
        if(val == 'yes'){
          $("#deposit").prop("disabled", false);
        }

        if(val == 'no'){
          $("#deposit").prop("disabled", true);
        }
      });
      
      $("#security").on('change', function(event){
        event.preventDefault();
        var val = $(this).val();
        //alert(val);
        if(val == 'yes'){
          $("#deposit").prop("disabled", false);
        }

        if(val == 'no'){
          $("#deposit").prop("disabled", true);
        }

      });
    </script>

    <div class="item form-group">    
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="status">Status <span class="required">*</span>
      </label>
      
      <div class="col-md-6 col-sm-6 col-xs-12"> 
        <select required="" class="form-control" id="status" name="status">
          <option <?php if(isset($bidding_info) && !empty($bidding_info) && $bidding_info[0]['status'] == 'active'){ echo 'selected'; }?> value="active">Active</option>
          <option <?php if(isset($bidding_info) && !empty($bidding_info) && $bidding_info[0]['status'] == 'inactive'){ echo 'selected'; }?> value="inactive">In Active</option>
        </select>
        <span class="status-error text-danger"></span>
      </div>
    </div>

    <div class="form-group">
      <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
        <button type="button" id="send_rules" class="btn btn-success">Submit</button> 
      </div>
    </div>

  </form>

<script type="text/javascript">
	
    $('.select2').select2();

      // $('#bid_start_time').datetimepicker({
      //     format: 'YYYY-MM-DD hh:mm A'
      // });
      // $('#bid_end_time').datetimepicker({
      //     format: 'YYYY-MM-DD hh:mm A'
      // });




    $(function () {
        $('#bid_start_time').datetimepicker({ 
           maxDate: "<?php if(isset($bidding_info[0]['bid_end_time']) && !empty($bidding_info[0]['bid_end_time'])) { echo date('m/d/Y h:i:s A',strtotime($bidding_info[0]['bid_end_time'])); }else{ echo date('m/d/Y h:i:s A', strtotime('+2 month'));}?>",
          minDate: "<?php if(isset($bidding_info[0]['bid_start_time']) && !empty($bidding_info[0]['bid_start_time'])) { if((strtotime($bidding_info[0]['bid_start_time'])) > (strtotime(date('Y-m-d H:i')))) { echo date('m/d/Y h:i:s A'); } else { echo date('m/d/Y h:i:s A',strtotime($bidding_info[0]['bid_start_time'])); } }else{ echo date('m/d/Y h:i:s A', strtotime('-2 month'));} ?>",
          useCurrent: false,
           ignoreReadonly : true
        });
        $('#bid_end_time').datetimepicker({
            useCurrent: false, //Important! See issue #1075 
            maxDate: "<?php if(isset($auction_expiry_time) && !empty($auction_expiry_time)) { echo date('m/d/Y h:i:s A',strtotime($auction_expiry_time)); }else{ echo date('m/d/Y h:i:s A', strtotime('+2 month'));}?>",
           minDate: "<?php if(isset($bidding_info[0]['bid_start_time']) && !empty($bidding_info[0]['bid_start_time'])) { echo date('m/d/Y h:i:s A',strtotime($bidding_info[0]['bid_start_time'])); }else{ echo date('m/d/Y h:i:s A');} ?>",
            // maxDate: "<?php if(isset($auction_expiry_time) && !empty($auction_expiry_time)) { echo date('m/d/Y h:i:s A',strtotime($auction_expiry_time)); }?>",
            ignoreReadonly : true
           
        });
        $("#bid_start_time").on("dp.change", function (e) {
           $('#bid_end_time').data("DateTimePicker").minDate(e.date);
        });
        $("#bid_end_time").on("dp.change", function (e) {
            $('#bid_start_time').data("DateTimePicker").maxDate(e.date);
        });
    });


      // console.log('i m here');
     $('#send_rules').on('click',function(){
       // $('#demo-form2').parsley().on('field:validated', function() {
       //      var ok = $('.parsley-error').length === 0;
       //    }) .on('form:submit', function() {
      // console.log('i m clicked');
      var validation = false;
        selectedInputs = ['bid_start_price','minimum_bid_price', 'bid_start_time', 'bid_end_time','security','status'];
        validation = validateFields(selectedInputs);


        if(validation == false){
          return false;
        }

        if(validation == true){



          var url = '<?php echo base_url();?>';
          var formData2 = new FormData(rule_form_data);
          //console.log(formData2);
          //return false;
           $.ajax({
            url: url + 'auction/update_bidding_rules',
            type: 'POST',
            data: formData2,
            cache: false,
            contentType: false,
            processData: false
            }).then(function(data) {
              var objData = jQuery.parseJSON(data);
              console.log(objData);
              if (objData.msg == 'success') 
              {
                // $('.modal-body').html(objData.data);
                // $(".rule_form_data").hide();
                // $('.msg-alert').css('display', 'block');

                $(".rule_form_data").html('<div class="alert" style="width:100% !important;" ><div class="alert alert-domain alert-success alert-dismissible fade in  msg-alert" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: black"><span aria-hidden="true">×</span> </button>' + objData.data + '</div></div>');
                 setTimeout(function(){
                  $(".bs-example-modal-sm").modal("hide");
                 },2500);
                 
                 window.setTimeout(function(){location.reload()},3000)
                 // window.location = url + 'items';
              }
              else
              {
              	// $('.msg-alert').css('display', 'block');
                $(".message_rule").html('<div class="alert" ><div class="alert alert-domain alert-danger alert-dismissible fade in  msg-alert" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: black"><span aria-hidden="true">×</span> </button>' + objData.data + '</div></div>');
              }

          });
        }
                // return false;
        });

</script>