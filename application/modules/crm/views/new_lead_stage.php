
<input type="hidden" id="base_url" value="<?php echo base_url(); ?>">

<div class="clearfix"></div>

<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>
                <?php  echo $small_title; ?></h2>

            <div class="clearfix"></div>
        </div>
        <div class="x_content">
                         <div id="result"></div>

            <?php if($this->session->flashdata('error')){ ?>
            <div class="alert">
                <div class="alert alert-domain alert-danger alert-dismissible fade in" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: black"><span aria-hidden="true">×</span>
                      </button>
                    <?php echo $this->session->flashdata('error'); ?>
                </div>
            </div>
            <?php }?>

           
            <form method="post" data-parsley-validate="" id="frm-stage" action="" enctype="multipart/form-data" class="form-horizontal form-label-left">
                <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
               <?php if(isset($lead_stage_list) && !empty($lead_stage_list)){ ?>
                    <input type="hidden" name="id" value="<?php echo $this->uri->segment(3); ?>">
                <?php } ?>  
                
                <div class="item form-group">    
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Title <span class="required">*</span>
                        </label>

                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" required="required"  id="title" name="title" value="<?php if(isset($lead_stage_list) && !empty($lead_stage_list)) { echo $lead_stage_list[0]['title']; } ?>" class="form-control col-md-7 col-xs-12">
                    </div>
                </div>


                <div class="item form-group">    
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Status <span class="required">*</span>
                </label>
                
                <div class="col-md-6 col-sm-6 col-xs-12"> 
                    <select required="" class="form-control select2" id="status" name="status">
                        <option <?php if(isset($lead_stage_list) && !empty($lead_stage_list) && $lead_stage_list[0]['status'] == 'active'){ echo 'selected'; }?> value="active">Active</option>
                        <option <?php if(isset($lead_stage_list) && !empty($lead_stage_list) && $lead_stage_list[0]['status'] == 'inactive'){ echo 'selected'; }?> value="inactive">In Active</option>
                    </select>
                </div>
                </div>

                <div class="ln_solid"></div>
                <div class="form-group">
                    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                        <!-- <input type="submit" class="btn btn-default" value="validate"> -->
                        <button type="submit"  class="btn btn-success">Submit</button>
                        <a href="<?php echo base_url().'crm/lead_stage'; ?>" class="custom-class" type="button">Cancel</a>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>


<script>
    var url = '<?php echo base_url();?>';
    var formaction_path = '<?php echo $formaction_path;?>';
    $(document).ready(function() {
        // You can use the locally-scoped $ in here as an alias to jQuery.
        
        $('#frm-stage').parsley().on('field:validated', function() {
            var ok = $('.parsley-error').length === 0;
         }) .on('form:submit', function() {
            var formData = new FormData($("#frm-stage")[0]);
            // alert(formaction_path);
            $.ajax({
                url: url + 'crm/' + formaction_path,
                type: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false
            }).then(function(data) {
                console.log(data);
                var objData = jQuery.parseJSON(data);
                console.log(objData);
                if (objData.msg == 'success') {

                    window.location = url + 'crm/lead_stage';

                } else {
                    $('.msg-alert').css('display', 'block');
                    $("#result").html('<div class="alert" ><div class="alert alert-domain alert-danger alert-dismissible fade in  msg-alert" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: black"><span aria-hidden="true">×</span> </button>' + objData.msg + '</div></div>');

                //        window.setTimeout(function() {
                //     $(".alert").fadeTo(500, 0).slideUp(500, function(){
                //         $(this).remove(); 
                //     });
                // }, 3000);

                }
            });
        });
    });
</script>