
    <div class="x_title">
        <h2><?php  echo $small_title; ?> Category</h2>
        <div class="clearfix"></div>
        <?php if ($this->session->flashdata('success')) {?>
        <div class="alert">
            <div class="alert alert-domain alert-success alert-dismissible fade in" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true" style="color: black;">×</span>
                  </button>
                <?php echo $this->session->flashdata('success'); ?>
            </div>
        </div>
        <?php }?>                        
    </div>
        <div id="result"></div>

    

    <div class="container" style="padding-top: 10px">
        <form  method="post" novalidate="" id="demo-form2" action="" enctype="multipart/form-data" class="form-horizontal form-label-left">
            <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
                <?php if($small_title == 'Edit'){  ?>
            <input type="hidden" name="category_id" value="<?php echo $this->uri->segment(3); ?>">
                <?php } ?>
               
            <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Name <span class="required">*</span>
                </label>

                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input type="text" required maxlength="150" id="milleage" title="please add something" name="name" value="<?php if(!empty ($task_category_list)){echo $task_category_list['name'];}?>" class="form-control col-md-7 col-xs-12">
                </div>
            </div>

             <div class="item form-group">    
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Status <span class="required">*</span>
                </label>
                
                <div class="col-md-6 col-sm-6 col-xs-12"> 
                    <select required="" class="form-control select2" id="status" name="status">
                        <option <?php if(isset($task_category_list) && !empty($task_category_list) && $task_category_list['status'] == 'active'){ echo 'selected'; }?> value="active">Active</option>
                        <option <?php if(isset($task_category_list) && !empty($task_category_list) && $task_category_list['status'] == 'inactive'){ echo 'selected'; }?> value="inactive">Inactive</option>
                    </select>
                </div>
                </div>

            </div> 
            <div class="ln_solid"></div>
                
                 <div class="form-group">
                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                    <?php if($small_title== 'Add'){  ?>
                    <button type="submit" id="send" class="btn btn-success">Submit</button>
                    <a href="<?php echo base_url().'jobcard/task_category_list'; ?>" class="custom-class" type="button">Cancel</a>
                    <?php } else{ ?>

                    <button id="send" type="submit" class="btn btn-success">Update</button>
                    <a href="<?php echo base_url().'jobcard/task_category_list'; ?>" class="custom-class" type="button">Cancel</a>
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
                            window.location = url + 'jobcard/task_category_list';
                    });
                      return false; // Don't submit form for this demo
                });
            });


    </script>

