
    <div class="x_title">
        <h2><?php  echo $small_title; ?></h2>
        <div class="clearfix"></div>
        <?php if ($this->session->flashdata('msg')) {?>
        <div class="alert">
            <div class="alert alert-domain alert-success alert-dismissible fade in" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true" style="color: black;">×</span>
                  </button>
                <?php echo $this->session->flashdata('msg'); ?>
            </div>
        </div>
        <?php }?>                        
    </div>
        <div id="result"></div>

    

    <div class="container" style="padding-top: 10px">
        <form  method="post" novalidate="" id="demo-form2" action="" enctype="multipart/form-data" class="form-horizontal form-label-left">
            <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
                <?php if($small_title == 'Edit'){  ?>
            <input type="hidden" name="id" value="<?php echo $this->uri->segment(3); ?>">
                <?php } ?>
                     
            <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Title <span class="required">*</span>
                </label>

                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input type="text" required maxlength="150" id="milleage" title="please add something" name="title" value="<?php if(!empty ($task_detail)){echo $task_detail[0]['title'];}?>" class="form-control col-md-7 col-xs-12">
                </div>
            </div>

             <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">description <span class="required">*</span>
                </label>

                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input type="text" required maxlength="150" id="milleage" title="please add something" name="description" value="<?php if(!empty ($task_detail)){echo $task_detail[0]['description'];}?>" class="form-control col-md-7 col-xs-12">
                </div>
            </div>
            <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="status">Status</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                        <select required="" class="form-control select_withoutcross" id="status" name="status">
                            <!-- <option value="pending">pending</option>
                            <option value="Complete">Complete</option> -->
                             <option value="completed" <?php if($task_info == "completed") { echo "SELECTED"; } ?>>completed</option>
                              <option value="pending" <?php if($task_info == "pending") { echo "SELECTED"; } ?>>pending</option>
                        </select>
                </div>
            </div>
          
            <div class="ln_solid"></div>
                
                 <div class="form-group">
                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                    <?php if($small_title == 'Edit' ){  ?>
                    <button type="submit" id="send" class="btn btn-success">update</button>
                    <!-- <a href="<?php echo base_url().'jobcard/insert_edit_task'; ?>" class="btn btn-primary" type="button">Cancel</a> -->
                    <?php } else{ ?>

                    <button id="send" type="submit" class="btn btn-success">Submit</button>
                    <!-- <a href="<?php echo base_url().'cars/mileage'; ?>" class="btn btn-primary" type="button">Cancel</a> -->
                     <?php }?>
                </div>
            </div>
            </form>
        </div>

    <script>    
      var url = '<?php echo base_url();?>';
    var formaction_path = '<?php echo $formaction_path;?>';
$(document).ready(function() {
        // You can use the locally-scoped $ in here as an alias to jQuery.
        
            $('#demo-form2').parsley().on('field:validated', function() {
    var ok = $('.parsley-error').length === 0;
  }) .on('form:submit', function() {
                var formData = new FormData($("#demo-form2")[0]);
                    $.ajax({
                        url: url + 'jobcard/' + formaction_path,
                        type: 'POST',
                        data: formData,
                        cache: false,
                        contentType: false,
                        processData: false
                    }).then(function(data) {
                        var objData = jQuery.parseJSON(data);
                        if (objData.msg == 'success') {
                            window.location = url + 'jobcard';
                        } 
                        else {
                            $('.msg-alert').css('display', 'block');
                            $("#result").html('<div class="alert" ><div class="alert alert-domain alert-danger alert-dismissible fade in  msg-alert" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: black"><span aria-hidden="true">×</span> </button>' + objData.response + '</div></div>');

                    }
                 });
            return false; // Don't submit form for this demo
        });
    });

    </script>

