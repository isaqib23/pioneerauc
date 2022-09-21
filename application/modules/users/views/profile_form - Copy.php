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
            <form method="post" novalidate="" id="demo-form2" action="" enctype="multipart/form-data" class="form-horizontal form-label-left">
                <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
            

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

                <?php if(isset($all_users[0]['picture']) && !empty($all_users[0]['picture'])){?>
                    <div class="item form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="profile_picture">Profile Picture
                                    </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                          
                                            <img  height="100" src="<?php echo base_url();?>uploads/profile_picture/<?php echo $all_users[0]['picture']; ?>">
                                        </div>
                                    </div>


             <input type="hidden" name="old_profile_picture" value="<?php echo (isset($all_users[0]['picture']) && !empty($all_users[0]['picture'])) ? $all_users[0]['picture'] : ''; ?>">
                   
                <?php } ?>

                <div class="item form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="profile_picture"> <?php if(isset($all_users[0]['picture']) && !empty($all_users[0]['picture'])){ echo 'New '; }?>Profile Picture
                        </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="profile_picture" name="profile_picture" type="file" class="file">
                        <span>Max Image Size: 5Mb</span></div>
                </div>
                <div class="item form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="city">Address
                        </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" id="city" name="city" value="<?php
if (count($all_users) > 0) {echo $all_users[0]['city'];}?>" class="form-control col-md-7 col-xs-12">
                    </div>
                </div>

                <input type="hidden" name="lat" id="lat" > 
           <input type="hidden" name="lng" id="lng" >



                <div class="item form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="phone">Phone
                        </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" id="phone" name="phone" value="<?php
if (count($all_users) > 0) {echo $all_users[0]['phone'];}?>" class="form-control col-md-7 col-xs-12">
                    </div>
                </div>
                <div class="item form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Email <span class="required">*</span>
                        </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="email" id="email"  required="required" name="email" value="<?php
if (count($all_users) > 0) {echo $all_users[0]['email'];}?>" class="form-control col-md-7 col-xs-12">
                    </div>
                </div>
                <div class="ln_solid"></div>
                <div class="form-group">
                    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                        <button type="button" id="sendbtn" class="btn btn-success">Submit</button>
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
        $(function() {
            $("#sendbtn").on('click', function(e) { //e.preventDefault();
                var formData = new FormData($("#demo-form2")[0]);
                if (!validator.checkAll($("#demo-form2")[0])) {} else {
                    // alert(formaction_path);
                    $.ajax({
                        url: url + 'admin/Dashboard/' + formaction_path,
                        type: 'POST',
                        data: formData,
                        cache: false,
                        contentType: false,
                        processData: false
                    }).then(function(data) {
                        console.log(data);
                        if (data == 'success') {

                            window.location = url + 'admin/Dashboard/update_profile';

                        } else {
                            $('.msg-alert').css('display', 'block');
                            $("#result").html('<div class="alert" ><div class="alert alert-domain alert-danger alert-dismissible fade in  msg-alert" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true" style="color: black;">×</span> </button>' + data + '</div></div>');
                              $("#result").fadeTo(2000, 500).slideUp(500, function(){
                                $("#result").slideUp(500);
                                });
                        }
                    });
                }
            });
        });
    });

    $(".alert").fadeTo(2000, 500).slideUp(500, function(){
    $(".alert").slideUp(500);
    });
  
</script>