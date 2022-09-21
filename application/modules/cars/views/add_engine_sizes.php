    <style type="text/css">
        #page-wrapper {
            margin-top: 100px;
        }

        .fr-wrapper a {
        display: none!important;
        }
    </style>
    
    <input type="hidden" id="base_url" value="<?php echo base_url(); ?>">

    <div class="clearfix"></div>
    <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>
                        <?php  echo $title_; ?></h2>
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

                       
                 <form  method="post" novalidate="" id="demo-form2" action="" enctype="multipart/form-data" class="form-horizontal form-label-left">
                    <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
                    <?php if($title_ == 'Edit Engine Size' ){  ?>
                        <input type="hidden" name="id" value="<?php echo $this->uri->segment(3); ?>">
                    <?php } ?>
                     
                        <div class="item form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Title  <span class="required">*</span>
                            </label>

                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" required="" maxlength="150" id="title" name="title" value="<?php 
                                 if(count($eng_size_list) >0){ echo $eng_size_list[0]['title']; }?>" class="form-control col-md-7 col-xs-12">
                            </div>
                            <div id="error" style="color: red;"></div>
                        </div>

                <div class="item form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="status">Status</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
               
                        <select required="" class="form-control select_withoutcross" id="status" name="status">

                            <option <?php if(count($eng_size_list) >0 && $eng_size_list[0]['status'] == 1){ echo 'selected'; }?> value="1">Active</option>
                            <option <?php if(count($eng_size_list) >0 && $eng_size_list[0]['status'] == 0){ echo 'selected'; }?> value="0">Inactive</option>
                        </select>
                    </div>
                </div>
          
                    <div class="ln_solid"></div>
                    <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                            <button type="submit" id="send" class="btn btn-success">Submit</button>
                            <a href="<?php echo base_url().'cars/engine_sizes'; ?>" class="custom-class" type="submit">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
      var ok;
    var url = '<?php echo base_url();?>';
    var formaction_path = '<?php echo $formaction_path;?>';
    $(document).ready(function() {
        // You can use the locally-scoped $ in here as an alias to jQuery.
        
        $('#demo-form2').parsley().on('field:validated', function() {
        var ok = $('.parsley-error').length === 0;
        }) .on('form:submit', function() {
                var formData = new FormData($("#demo-form2")[0]);
                    $.ajax({
                        url: url + 'cars/' + formaction_path,
                        type: 'POST',
                        data: formData,
                        cache: false,
                        contentType: false,
                        processData: false
                    }).then(function(data) {
                        var objData = jQuery.parseJSON(data);
                        if (objData.msg == 'success') {
                            window.location = url + 'cars/engine_sizes';
                        } 
                         else {
                            $('.msg-alert').css('display', 'block');
                            $("#result").html('<div class="alert" ><div class="alert alert-domain alert-danger alert-dismissible fade in  msg-alert" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: black"><span aria-hidden="true">×</span> </button>' + objData.msg + '</div></div>');

                        }
                    });
                      return false; // Don't submit form for this demo
                });
            });


      
    </script>