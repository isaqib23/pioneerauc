<div class="col-md-10">
	<div class="message"></div>
</div>
<form method="post" data-parsley-validate="" id="demo-form2" action="" enctype="multipart/form-data" class="form-horizontal form-label-left" >
  <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
	
	<div class="item form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="assigned_to">Assign To</label>
        <div class="col-md-6 col-sm-6 col-xs-12">
        	<input type="hidden" name="id" value="<?php echo (isset($crm_list) && !empty($crm_list)) ? $crm_info : '' ?>">
            <select class="form-control col-md-7 col-xs-12" id="assigned_to" name="assigned_to" required>
                 <option disabled="" selected="" >Select Assigned To</option>
                 <?php foreach ($assigned_to_list as $assigned_to_value) { ?>
                <option 
                <?php if(isset($crm_info) && !empty($crm_info)){
                    if($crm_list[0]['assigned_to'] == $assigned_to_value['id']){ echo 'selected';}
                }?>
                    value="<?php echo $assigned_to_value['id']; ?>"><?php echo $assigned_to_value['fname'].' '.$assigned_to_value['lname'].' - ('.ucwords($assigned_to_value['role_name']).')'; ?></option>
                    <?php  } ?> 
                  
            </select>
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
            <button type="button" id="send" class="btn btn-success">Submit</button>
        </div>
    </div>

</form>

<script type="text/javascript">
	
	   $('#send').on('click',function(){
              var url = '<?php echo base_url();?>';
              var formData2 = new FormData($("#demo-form2")[0]);
              // console.log(id);
               $.ajax({
                url: url + 'crm/update_assign_to',
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
                     window.location = url + 'crm';
                  }
                  else
                  {
                  	$('.msg-alert').css('display', 'block');
                    $(".message").html('<div class="alert" ><div class="alert alert-domain alert-danger alert-dismissible fade in  msg-alert" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: black"><span aria-hidden="true">×</span> </button>' + objData.data + '</div></div>');
                  }

          });
        });

</script>