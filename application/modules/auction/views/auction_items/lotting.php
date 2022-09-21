<div class="col-md-10">
	<div class="message_lotting"></div>
</div>
  <form method="post" novalidate="" id="lotting-form2" name="rule_form_data" action="" enctype="multipart/form-data" class="form-horizontal form-label-left rule_form_data">
    <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
    <?php if(isset($bidding_info) && !empty($bidding_info)){ ?>
      <input type="hidden" name="id" value="<?php if(isset($bidding_info) && !empty($bidding_info)) { echo $bidding_info[0]['id']; } ?>">
    <?php } ?>   
    <input type="hidden" name="item_id" value="<?php if(isset($item_id) && !empty($item_id)) { echo $item_id; } ?>">
    <input type="hidden" name="auction_id" value="<?php if(isset($auction_id) && !empty($auction_id)) { echo $auction_id; } ?>">


    <div class="item form-group">    
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="order_lot_no">Lot No<span class="required">*</span>
      </label>
      
      <div class="col-md-6 col-sm-6 col-xs-12"> 
        <input type="text" name="order_lot_no" id="order_lot_no" class="form-control" required value="<?php if(isset($bidding_info[0]['order_lot_no']) && !empty($bidding_info[0]['order_lot_no'])) { echo $bidding_info[0]['order_lot_no']; } ?>">
      </div>
    </div>


    <div class="form-group">
      <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
        <button type="button" id="send_lot_no" class="btn btn-success">Submit</button> 
      </div>
    </div>

  </form>

<script type="text/javascript">
	
    $('.select2').select2();
 
     $('#send_lot_no').on('click',function(){
              var url = '<?php echo base_url();?>';
              var lottingformData = $('#lotting-form2').serializeArray();
              // var formData2 = new FormData(rule_form_data);
               $.ajax({
                url: url + 'auction/update_lotting',
                type: 'POST',
                data: lottingformData,
                }).then(function(data) {
                  var objData = jQuery.parseJSON(data);
                  console.log(objData);
                  if (objData.msg == 'success') 
                  {
                    
                    $(".rule_form_data").html('<div class="alert" style="width:100% !important;" ><div class="alert alert-domain alert-success alert-dismissible fade in  msg-alert" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: black"><span aria-hidden="true">×</span> </button>' + objData.data + '</div></div>');
                     setTimeout(function(){
                      $(".bs-example-modal-sm_lot").modal("hide");
                     },2500);
                     
                     window.setTimeout(function(){location.reload()},3000)
                  }
                  else
                  {
                    $(".message_lotting").html('<div class="alert" ><div class="alert alert-domain alert-danger alert-dismissible fade in  msg-alert" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: black"><span aria-hidden="true">×</span> </button>' + objData.data + '</div></div>');
                  }

          });

        });

</script>