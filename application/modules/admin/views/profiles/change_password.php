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
                <?php  echo $small_title; ?>
            </h2>
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
            <?php if($this->session->flashdata('users_error')){ ?>
            <div class="alert">
                <div class="alert alert-domain alert-danger alert-dismissible fade in" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                      </button>
                    <?php echo $this->session->flashdata('users_error'); ?>
                </div>
            </div>
            <?php }?>
            <?php if($this->session->flashdata('msg')){ ?>
            <div class="alert">
                <div class="alert alert-domain alert-success alert-dismissible fade in" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                      </button>
                    <?php echo $this->session->flashdata('msg'); ?>
                </div>
            </div>
            <?php }?>
            <br />
            <form method="post" novalidate="" id="demo-form2" action="" enctype="multipart/form-data" class="form-horizontal form-label-left">
                <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
               <?php if(count($all_users) >0){ ?>
                <div class="item form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="old_password">Old Password <span class="required">*</span>
                        </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="password" id="old_password" required="" name="old_password" value="" class="form-control col-md-7 col-xs-12">
                    </div>
                </div>
                <?php }?>
                <div class="item form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="new_password">New Password <span class="required">*</span>
                      <?php if(count($all_users) >0){ }else{ echo ' <span class="required">*</span>';}?>
                        </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="password" required="" id="new_password" <?php if(count($all_users)>0){ }else{ echo 'required';}?> name="new_password" value="" class="form-control col-md-7 col-xs-12">
                    </div>
                </div>
                <div class="item form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="conf_password">Confirm Password <span class="required">*</span>
                          <?php if(count($all_users) >0){ }else{ echo ' <span class="required">*</span>';}?>
                        </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="password" data-validate-linked="#new_password" id="conf_password" <?php if(count($all_users)>0){ }else{ echo 'required';}?> required name="conf_password" value="" class="form-control col-md-7 col-xs-12">
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
    var url = '<?php echo base_url();?>';
    var formaction_path = '<?php echo $formaction_path;?>';
    // alert(formaction_path);
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

                            window.location = url + 'admin/Dashboard/update_password';

                        } else {
                            $('.msg-alert').css('display', 'block');
                            $("#result").html('<div class="alert" ><div class="alert alert-domain alert-danger alert-dismissible fade in  msg-alert" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span> </button>' + data + '</div></div>');
                        }
                    });
                }
            });
        });
    });
</script>