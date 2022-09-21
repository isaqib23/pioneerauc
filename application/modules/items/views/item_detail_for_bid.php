<div class="col-md-10">
	<div class="message"></div>
</div>
<div>
  <?php print_r($item_detail); ?>
  
</div>
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