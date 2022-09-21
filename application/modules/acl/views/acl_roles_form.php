<?php
  // global $TITLE;
  // $TITLE = "Acl Role Record";
?>
    <style type="text/css">
        #page-wrapper {
            margin-top: 100px;
        }
    </style>
    <input type="hidden" id="base_url" value="<?php echo base_url(); ?>">

    <div class="clearfix"></div>

    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>
                    <?php if(isset($small_title)){ echo $small_title;}?> Role </h2>

                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div id="result"></div>
                <?php if($this->session->flashdata('error')){ ?>
                <div class="alert">
                    <div class="alert alert-domain alert-danger alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                      </button>
                        <?php echo $this->session->flashdata('error'); ?>
                    </div>
                </div>
                <?php }?>
                <br />
                <form method="post" novalidate="" id="demo-form2" action="" enctype="multipart/form-data" class="form-horizontal form-label-left">
                  <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />

                    <?php if(count($all_roles) >0){ ?>
                    <input type="hidden" required name="id" value="<?php if(count($all_roles) >0){ echo $this->uri->segment(4); }?>">
                    <?php } ?>
                    <?php if($small_title == 'Add'){ ?>
                    <input type="hidden" required name="created_on" value="<?php echo date('Y-m-d');?>">
                    <input type="hidden" required name="created_by" value="<?php echo $user_id;?>">

                    <?php }else{ ?>
                    <input type="hidden" required name="updated_by" value="<?php echo $user_id;?>">

                    <?php } ?>


                    <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="role_name">Role Name <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" id="role_name" required="required" name="name" value="<?php 
                          if(count($all_roles) >0){ echo $all_roles[0]['name']; }?>" class="form-control col-md-7 col-xs-12">
                        </div>
                    </div>
                    <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Role Description <span class="required">*</span>
                      </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <textarea name="description" class="form-control" required rows="3"><?php if(isset($all_roles[0]['description'])){ echo $all_roles[0]['description']; } ?></textarea>
                        </div>
                    </div>

                    <div class="ln_solid"></div>
                    <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                            <button type="button" id="sendbtn" class="btn btn-success">Submit</button>
                            <a href="<?php echo base_url().'acl'; ?>" class="btn btn-primary" type="button">Cancel</a>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>


<script>
  //  $(document).ready(function() {
  //    var base_url = $('#base_url').val();
  //  $('.select_withoutcross').select2();
  // });
var url = '<?php echo base_url();?>';
var formaction_path = '<?php echo $formaction_path;?>';
$(document).ready(function() {
  // You can use the locally-scoped $ in here as an alias to jQuery.
  $(function() {
      $("#sendbtn").on('click', function(e) { //e.preventDefault();
          var formData = new FormData($("#demo-form2")[0]);
          if (!validator.checkAll($("#demo-form2")[0])) {} else {
              // alert(formaction_path);
              $.ajax({
                  url:  url + 'acl/Acl_roles/' + formaction_path,
                  type: 'POST',
                  data: formData,
                  cache: false,
                  contentType: false,
                  processData: false
              }).then(function(data) {
                  console.log(data);
                  if (data == 'success') {
                     window.location = url + 'acl/Acl_roles';
                  } else {
                      $('.msg-alert').css('display', 'block');
                      $("#result").html('<div class="alert" ><div class="alert alert-domain alert-danger alert-dismissible fade in  msg-alert" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span> </button>' + data + '</div></div>');
                  }
              });
          }
      });
  });
});

 // only ttext submit searilize 
 //$(function() {
  //     $("#send").on('click', function() {
  //         // alert('aaa');
  //         dataString = $("#demo-form2").serialize();
  //         // alert(dataString);
  //         $.ajax({
  //             type: "POST",
  //             url: url + 'acl/Acl_roles/' + formaction_path,
  //             data: dataString,
  //             success: function(data) {
  //                 console.log(data);
  //                 if (data == 'success') {
  //                     window.location = url + 'acl/Acl_roles';
  //                 } else {
  //                     $('.msg-alert').css('display', 'block');
  //                     $("#result").html('<div class="alert" ><div class="alert alert-domain alert-danger alert-dismissible fade in  msg-alert" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span> </button>' + data + '</div></div>');
  //                 }
  //             }
  //         });
  //         return false; //stop the actual form post !important!
  //     });
  // });
</script>