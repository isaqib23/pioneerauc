
    <div class="x_title">
        <h2><?php  echo $small_title; ?></h2>
        <div class="clearfix"></div>
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
    </div>
        <div id="result"></div>
        <!-- <?php print_r($edit); ?> -->
    <div class="container" style="padding-top: 10px">
        <form  method="post" action="<?= ($small_title == 'Edit') ? base_url('jobcard/update_assigned_task'):base_url('jobcard/save_assigned_task'); ?>" class="form-horizontal form-label-left">
          <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
                <?php if($small_title == 'Edit'){  ?>
            <input type="hidden" name="id" value="<?php echo $this->uri->segment(3); ?>">
                <?php } ?>
                     

             <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Category <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <select class="form-control col-md-7 col-xs-12"  id="task_category" name="task_category_id"> 
                        <option  value=""> Select Category </option>
                        <?php foreach ($task_category as $value) {
                            ?>
                        <option <?php if(!empty($edit) && $edit['task_category_id'] == $value['id']) {
                           echo "SELECTED";
                        } ?> value="<?php echo $value['id'];?>">
                            <?php echo $value['name']; ?>
                        </option>
                        <?php  } ?>
                    </select>
                    <div class="task_category_id-error text-danger"></div>
                </div>
            </div>

            <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Tasks<span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <select class="form-control col-md-7 col-xs-12"  multiple="multiple" id="assigned_task_ids" name="assigned_task_ids[]"> 
                        
                             <?php foreach ($tasks_list as $k => $value) { prin
                                    ?>
                                    <option value="<?php echo $value['id']; ?>"
                                    <?php
                                        if(isset($edit['assign_to_ids'])){
                                            $selected_category = json_decode($edit['assign_to_ids']);
                                            foreach ($selected_task_ids  as $id) 
                                            {
                                              echo ($id == $value['id']) ? 'selected="selected"' : '';
                                            }
                                        }
                                    ?>
                                    ><?php echo $value['title']; ?></option>
                                    <?php
                                  } ?>
                      
                    </select>
                    <div class="assigned_task_ids-error text-danger"></div>
                </div>
            </div>
           
               <div class="item form-group">
                   <label class="control-label col-md-3 col-sm-3 col-xs-12" for="specialization_id">Assign to<span class="required"></span></label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <select  class="form-control select_withoutcross username"  multiple=""  id="assign_to" name="assign_to_ids[]">
                        <option  value="">Select tasker</option>
                            <?php
                              if(isset($tasker_list)){
                                  foreach ($tasker_list as $k => $value) {
                                    ?>
                         <option value="<?php echo $value['id']; ?>"
                              <?php
                                  if(isset($edit['assign_to_ids'])){
                                      $selected_category = json_decode($edit['assign_to_ids']);
                                      foreach ($selected_category as $id) 
                                      {
                                        echo ($id == $value['id']) ? 'selected="selected"' : '';
                                      }
                                  }
                              ?>
                              ><?php echo $value['email']; ?></option>
                              <?php
                                }
                                }
                               ?>
                      </select>
                      <div class="assign_to_ids-error text-danger"></div>
                  </div>
            </div>
          
            <div class="ln_solid"></div>

           <div class="form-group">
            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                <input type="submit" id="send" class="btn btn-success" value="<?= ($small_title == 'Edit') ? 'Update':'Submit'; ?>">
              <a href="<?php echo base_url().'jobcard/assigned_task'; ?>" class="btn btn-primary">Cancel</a>
            </div>
          </div>
         <!--  <div class="form-group">
                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                    <?php if($small_title == 'Add' ){  ?>
                    <button type="submit" id="send" class="btn btn-success">submit</button>
                    <a href="<?php echo base_url().'jobcard/assigned_task'; ?>" class="custom-class" type="button">Cancel</a>
                    <?php } else{ ?>

                    <button id="send" type="submit" class="btn btn-success">Submit</button>
                    <a href="<?php echo base_url().'jobcard/assigned_task'; ?>" class="custom-class" type="button">Cancel</a>
                     <?php }?>
                </div>
            </div> -->

            </form>
        </div>

<script>
  var token_name = '<?= $this->security->get_csrf_token_name();?>';
  var token_value = '<?=$this->security->get_csrf_hash();?>';

  var select2Oz = remoteSelect2({
    [token_name] : token_value,
    'selectorId' : '.username',
    'placeholder' : 'Tasker Name',
    'table' : 'users',
    'values' : 'Name',
    'width' : '200px',
    'delay' : '250',
    'cache' : false,
    'minimumInputLength' : 1,
    'limit' : 5,
    'url' : '<?= base_url();?>jobcard/taskerlist_api'
  });

  var url = '<?php echo base_url();?>';
  var formaction_path = '<?php echo $formaction_path;?>';
  $('#task_category').on('change', function() {
    var task_category_id= $(this).val();
    $.ajax({    //create an ajax request to display.php
          type: "post",
          url: "<?php echo base_url(); ?>" + "jobcard/get_category_tasks/" + task_category_id,    
          data:{ task_category_id:task_category_id, [token_name]:token_value},
          success: function(data) {
           // console.log(data);
          $('select#assigned_task_ids').html('');
          $.each(JSON.parse(data), function(i,v) {
            $("<option / >").val(v.id).text(v.title).appendTo('select#assigned_task_ids');
          });
      }
    });
  });

  $("#assigned_task_ids").select2({
    placeholder: "Select Tasks", 
    width: '200px',
    // allowClear: true,
  });

  $("#assign_to").select2({
    placeholder: "Select Users", 
    width: '200px',
    // allowClear: true,
  });

  // $(document).ready(function() {
  //       // You can use the locally-scoped $ in here as an alias to jQuery.
        
  //           $('#demo-form2').parsley().on('field:validated', function() {
  //   var ok = $('.parsley-error').length === 0;
  // }) .on('form:submit', function() {
  //               var formData = new FormData($("#demo-form2")[0]);
  //                   $.ajax({
  //                       url: url + 'jobcard/' + formaction_path,
  //                       type: 'POST',
  //                       data: formData,
  //                       cache: false,
  //                       contentType: false,
  //                       processData: false
  //                   }).then(function(data) {
  //                       var objData = jQuery.parseJSON(data);
  //                       if (objData.msg == 'success') {
  //                           window.location = url + 'jobcard/assigned_task';
  //                       } 
  //                       else {
  //                           $('.msg-alert').css('display', 'block');
  //                           $("#result").html('<div class="alert" ><div class="alert alert-domain alert-danger alert-dismissible fade in  msg-alert" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: black"><span aria-hidden="true">×</span> </button>' + objData.response + '</div></div>');

  //                       }
  //                     });
  //            return false; // Don't submit form for this demo
  //         });
  //     });

</script>

<script>
  $('#send').on('click', function(event){
    event.preventDefault();
    var validation = false;
    selectedInputs = ['task_category_id','"assigned_task_ids[]"','"assign_to_ids[]"'];
    validation = validateFields(selectedInputs);
    if(validation == false){
      return false;
    }
    if(validation == true){
      $(this).closest("form").submit();
      //console.log($(this).closest("form").serializeArray());
    }
  });
</script>