 <script src="https://maps.googleapis.com/maps/api/js?key=<?= $this->config->item('google_map_key'); ?>&libraries=places"></script> 

<style type="text/css">
    #page-wrapper {
        margin-top: 100px;
    }
    .dz-image img{
    max-width: 120px;
    max-height: 120px;
    }
   
</style>
<input type="hidden" id="base_url" value="<?php echo base_url(); ?>">
<div class="clearfix"></div>
<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>
                <?php echo $small_title; ?>
            </h2>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <div id="result"></div>         
            <?php if ($this->session->flashdata('msg')) {?>
            <div class="alert">
                <div class="alert alert-domain alert-success alert-dismissible fade in" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true" style="color: black;">×</span>
                      </button>
                    <?php echo $this->session->flashdata('msg'); ?>
                </div>
            </div>
            <?php }?>


            <br />

            
            <form method="post"  novalidate="" name="myForm" id="demo-form2" action="" enctype="multipart/form-data" class="form-horizontal form-label-left" >
                <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
                <?php
                if(count($all_users) > 0){?>
                    <input type="hidden" name="post_id" value="<?= $all_users[0]['id']; ?>">
                    <input type="hidden" name="user_id" value="<?php echo  $this->uri->segment(3);?>">
                <?php
                }
                ?>

                <div class="item form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="fname">First Name<span class="required">*</span>
                        </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" id="fname" required="required" name="fname" value="<?php
if (count($all_users) > 0) {echo $all_users[0]['fname'];}?>" class="form-control col-md-7 col-xs-12">
                    </div>
                </div>
                <div class="item form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="lname">Last Name<span class="required">*</span>
                        </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" id="lname" required="required" name="lname" value="<?php
if (count($all_users) > 0) {echo $all_users[0]['lname'];}?>" class="form-control col-md-7 col-xs-12">
                    </div>
                </div>
                <div class="item form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Email <span class="required">*</span>
                        </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="email" id="email"  required="required" name="email"  <?php if (isset($all_users) &&  !empty($all_users)) {echo '';}else{ echo ' data-parsley-remote data-parsley-remote-validator="mycustom_email" data-parsley-remote-message="Email already exists." data-parsley-trigger="focusin focusout" '; }?>  value="<?php
if (count($all_users) > 0) {echo $all_users[0]['email'];}?>" class="form-control col-md-7 col-xs-12">
                    </div>
                </div>


                <div class="item form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="mobile">Mobile <span class="required">*</span>
                        </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" data-parsley-type="number" <?php if (isset($all_users) &&  count($all_users) > 0) {echo '';}else{ echo ' data-parsley-remote data-parsley-remote-validator="mycustom" data-parsley-trigger="focusin focusout" data-parsley-remote-message="Mobile number already exists." '; }?>  pattern="[0-9]*" required="" id="mobile" name="mobile" value="<?php
if (count($all_users) > 0) {echo $all_users[0]['mobile'];}?>" class="form-control col-md-7 col-xs-12">
                    </div>
                </div>


                <?php if(empty($all_users)){ ?>
                <div class="item form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="password">Password <span class="required">*</span>
                        </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="password" id="password" required="required" name="password" value="<?php
if (count($all_users) > 0) {echo $all_users[0]['password'];}?>" class="form-control col-md-7 col-xs-12">
                    </div>
                    <?php $get_users_list_by_id ?>
                </div>
                <?php } ?>


                <div class="item form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="role_id">User Role <span class="required">*</span></label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                
                      
                    <select class="form-control col-md-7 col-xs-12" id="role_id" name="role_id" required >
                        
                            <option disabled="" value="" >Select User Role</option>
                            <option 
                            <?php if(isset($all_users) && !empty($all_users)){
                                if($all_users[0]['role'] == $all_roles['id']){ echo 'selected';}
                            }?>
                                value="6">Tasker</option>
                    </select>
                    </div>
                </div>



             



                 <?php
                if(count($all_users)>0)
                { ?>
                    <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="profile_picture">Profile Picture
                        </label>
                        <input type="hidden" name="old_files" value="<?= $all_users[0]['picture']; ?>">
                        <?php 
                        if (strpos($all_users[0]['picture'], ',') !== false) {
                            $images = explode(',', $all_users[0]['picture']);

                            
                            foreach ($images as $key => $value) { ?>
                                <div class="col-md-2 col-sm-2 col-xs-12">
                                    <img  height="100" width="150" src="<?php echo base_url();?>uploads/profile_picture/<?php echo $value; ?>">
                                </div>
                            <?php
                             }
                        }else
                        {    ?>
                            <div class="col-md-3 col-sm-3 col-xs-12">
                                <?php if(!empty($all_users[0]['picture']))
                                { ?>
                                <img  height="100" src="<?php echo base_url();?>uploads/profile_picture/<?php echo $all_users[0]['id']."/".$all_users[0]['picture']; ?>">
                                <?php 
                                }
                                else
                                { ?>    
                                <img  height="100" src="<?php echo base_url().'uploads/profile_picture/default.png';?>">
                                <?php } ?>    
                            </div>
                        <?php
                        }?>                    
                        
                    </div>
                <?php
                }
                ?> 

                <div class="item form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="profile_picture"> Profile Picture
                        </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                         <!-- <input type="file" multiple="" name="images[]"> -->
                        <input id="profile_picture" name="images" type="file" class="file" accept="image/x-png,image/gif,image/jpeg" >
                        <span>Max Image Size: 5Mb</span>
                    </div>
                </div>         
                 <div style="display:none;" id="buyer_commission_div" class="item form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="lname">Buyer Commission<span class="required"></span>
                        </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" id="buyer_commission" name="buyer_commission" value="<?php
                            if (count($all_users) > 0) {echo $all_users[0]['buyer_commission'];}?>" class="form-control col-md-7 col-xs-12">
                    </div>
                </div>

                

              

                <input type="hidden" name="status" value="1">
                <div class="ln_solid"></div>
                <div class="form-group">
                    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                        <button type="submit" id="sendbtn" class="btn btn-success">Submit</button>
                        <a id="cancel" class="custom-class" href="<?php echo base_url().'jobcard'; ?>"  >Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>
<script>


     $(document).ready(function(){
  // Call Geo Complete
  // $("#city").geocomplete({details:"form#demo-form2"});
});

    var url = '<?php echo base_url(); ?>';
    var formaction_path = '<?php echo $formaction_path; ?>';
  
    $(document).ready(function() {

       // You can use the locally-scoped $ in here as an alias to jQuery.
        $('#demo-form2').parsley().on('field:validated', function() {
            var ok = $('.parsley-error').length === 0;
          }) .on('form:submit', function() {
            
                  var formData = new FormData($("#demo-form2")[0]);
                
                    // alert(formaction_path);
                    $.ajax({
                        url: url + 'jobcard/' + formaction_path,
                        type: 'POST',
                        data: formData,
                        cache: false,
                        contentType: false,
                        processData: false
                    }).then(function(data) {
                        var objData = jQuery.parseJSON(data);
                       if(objData.msg == 'success')
                        {
                            window.location = url + 'jobcard';
                        }
                         else {
                            $('.msg-alert').css('display', 'block');
                            $("#result").html('<div class="alert" ><div class="alert alert-domain alert-danger alert-dismissible fade in  msg-alert" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true" style="color: black;">×</span> </button>' + objData.mid + '</div></div>');
                              $("#result").fadeTo(2000, 500).slideUp(500, function(){
                                $("#result").slideUp(500);
                                });
                        }
                    });
                    return false;
          });


            // $("#sendbtn").on('click', function(e) { //e.preventDefault();
          
                       
                
            // });
        });
    

    $(".alert").fadeTo(2000, 500).slideUp(500, function(){
    $(".alert").slideUp(500);
    });

    function deleteImage(xp){
        var id       = $(xp).data('id');
        var file      = $(xp).data('file');
        var type      = $(xp).data('type');
        var obj      = 'task_testing';
        var link     = base_url+"tasks/deleteFileForTaskTesting";

        if(id!='' && obj!=''){

            swal({
                    title: "Are you sure?",
                    text: "You cannot recover it later.",
                    type: "error",
                    showCancelButton: true,
                    cancelButtonClass: 'btn-default btn-md waves-effect',
                    confirmButtonClass: 'btn-danger btn-md waves-effect waves-light',
                    confirmButtonText: 'Confirm!'
                },
                function(isConfirm) {
                    if (isConfirm) {
                        var parent = $(xp).parents('.file_parent');
                        $("#loading").show();

                        $.post(link, {id: id, obj: obj, file: file, type:type}, function(result){
                                //console.log(result);
                                if(result!='0'){
                                    var data = JSON.parse(result);

                                    if(data.type == 'success'){
                                        //hide gallery image
                                        swal("Success!", data.msg, "success");
                                        $(parent).fadeOut("slow");
                                        $(parent).remove();
                                    }

                                    if(data.type == 'error'){
                                        swal("Error!", data.msg, "error");
                                    }

                                }else{
                                    swal("Error!", "Something went wrong.", "error");
                                }
                                $('#loading').hide();
                            }

                        );

                    } else {
                        swal("Cancelled", "Your action has been cancelled!", "error");
                    }
                }

            );

        }else{
            swal("Error!", "Information Missing. Please reload the page and try again.", "error");
        }
    }






  
</script>