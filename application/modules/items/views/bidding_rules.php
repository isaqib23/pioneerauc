<div class="col-md-10">
	<div class="message"></div>
</div>
  <form method="post" novalidate="" id="demo-form2" name="rule_form_data" action="" enctype="multipart/form-data" class="form-horizontal form-label-left rule_form_data">
    <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
               <?php if(isset($bidding_info) && !empty($bidding_info)){ ?>
                <input type="hidden" name="id" value="<?php if(isset($bidding_info) && !empty($bidding_info)) { echo $bidding_info[0]['id']; } ?>">
            <?php } ?>   
                 <input type="hidden" name="item_id" value="<?php if(isset($item_id) && !empty($item_id)) { echo $item_id; } ?>">
                <div class="item form-group">    
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="auction_type">Auction Type <span class="required">*</span>
                </label>
                
                <div class="col-md-6 col-sm-6 col-xs-12"> 
                    <select required class="form-control select2" id="auction_type" multiple name="auction_type[]">
                        <?php $auction_type_arr = explode(",",$bidding_info[0]['auction_type']); ?>
                        <option <?php if(isset($bidding_info) && !empty($bidding_info) && in_array('online', $auction_type_arr)){ echo 'selected'; }?> value="online">Online</option>
                        <option <?php if(isset($bidding_info) && !empty($bidding_info) && in_array('closed', $auction_type_arr)){ echo 'selected'; }?> value="closed">Closed</option>
                        <option <?php if(isset($bidding_info) && !empty($bidding_info) && in_array('live', $auction_type_arr)){ echo 'selected'; }?> value="live">Live</option> 
                    </select>
                </div>
                </div>

                
                <div class="item form-group">    
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="start_price">Start Price <span class="required">*</span>
                        </label>

                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="number" id="start_price" required="required" name="start_price" value="<?php if(isset($bidding_info) && !empty($bidding_info)) { echo $bidding_info[0]['start_price']; } ?>" class="form-control col-md-7 col-xs-12">
                    </div>
                </div>
                
                <div class="item form-group">    
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="minimum_price">Minimum Price <span class="required">*</span>
                        </label>

                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="number" id="minimum_price" required="required" name="minimum_price" value="<?php if(isset($bidding_info) && !empty($bidding_info)) { echo $bidding_info[0]['minimum_price']; } ?>" class="form-control col-md-7 col-xs-12">
                    </div>
                </div>

                <div class="item form-group">    
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="highest_price">Highest Price <span class="required">*</span>
                        </label>

                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="number" id="highest_price" required="required" name="highest_price" value="<?php if(isset($bidding_info) && !empty($bidding_info)) { echo $bidding_info[0]['highest_price']; } ?>" class="form-control col-md-7 col-xs-12">
                    </div>
                </div>

                <div class="item form-group">    
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="minimum_increment">Minimum Increment <span class="required">*</span>
                        </label>

                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="number" id="minimum_increment" required="required" name="minimum_increment" value="<?php if(isset($bidding_info) && !empty($bidding_info)) { echo $bidding_info[0]['minimum_increment']; } ?>" class="form-control col-md-7 col-xs-12">
                    </div>
                </div>

                <div class="item form-group">    
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="bidding_time">Bidding Time <span class="required">*</span>
                    </label>

                    <div class="col-md-6 col-sm-6 col-xs-12  ">
                        <div class='input-group date' id='bidding_time'>
                          <input type='text' class="form-control" name="bidding_time" placeholder="Bidding Date Time" value="<?php if(isset($bidding_info) && !empty($bidding_info)) { echo $bidding_info[0]['bidding_time']; } ?>" required />
                          <span class="input-group-addon">
                             <span class="glyphicon glyphicon-calendar"></span>
                          </span>
                        </div>
                    </div>
                </div>


                
                <div class="item form-group">    
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="status">Status <span class="required">*</span>
                </label>
                
                <div class="col-md-6 col-sm-6 col-xs-12"> 
                    <select required="" class="form-control select2" id="status" name="status">
                        <option <?php if(isset($bidding_info) && !empty($bidding_info) && $bidding_info[0]['status'] == 'active'){ echo 'selected'; }?> value="active">Active</option>
                        <option <?php if(isset($bidding_info) && !empty($bidding_info) && $bidding_info[0]['status'] == 'inactive'){ echo 'selected'; }?> value="inactive">In Active</option>
                    </select>
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

      $('#bidding_time').datetimepicker({
          format: 'YYYY-MM-DD hh:mm A'
      });

      // console.log('i m here');
     $('#send_rules').on('click',function(){
      // console.log('i m clicked');
              var url = '<?php echo base_url();?>';
              var formData2 = new FormData(rule_form_data);
              // console.log(id);
               $.ajax({
                url: url + 'items/update_bidding_rules',
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

                    // $('.msg-alert').css('display', 'block');
                    // $(".message").html('<div class="alert" ><div class="alert alert-domain alert-success alert-dismissible fade in  msg-alert" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: black"><span aria-hidden="true">×</span> </button>' + objData.data + '</div></div>');
                     window.location = url + 'items';
                  }
                  else
                  {
                  	$('.msg-alert').css('display', 'block');
                    $(".message").html('<div class="alert" ><div class="alert alert-domain alert-danger alert-dismissible fade in  msg-alert" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: black"><span aria-hidden="true">×</span> </button>' + objData.data + '</div></div>');
                  }

          });
        });

</script>