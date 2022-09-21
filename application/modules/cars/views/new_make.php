      <div id="result"></div>

    <div class="x_title">
        <h2><?php  echo $title; ?></h2>
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
	

    <div class="container" style="padding-top: 10px">
    	   <form  method="post" novalidate="" id="demo-form2" action="" enctype="multipart/form-data" class="form-horizontal form-label-left">
            <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
                <?php if($title == 'Edit Make' ){ 
                    $title_english = json_decode($makes_list[0]['title']);
                    // $title_arabic = json_decode($makes_list['title']);
                 ?>
            <input type="hidden" name="id" value="<?php echo $this->uri->segment(3); ?>">
                <?php } ?>
                     
            <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">English Title<span class="required">*</span>
                </label>

                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input type="text" required="" maxlength="150" id="title" title="please add something" name="title" value="<?php 
                    if(count($makes_list) >0){ echo $title_english->english; }?>" class="form-control col-md-7 col-xs-12">
                </div>
                <div id="error" style="color: red;"></div>
            </div>

             <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Arabic Title<span class="required">*</span>
                </label>

                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input type="text" dir="rtl" required="" maxlength="150" id="title" title="please add something" name="title_arabic" value="<?php 
                    if(count($makes_list) >0){ echo $title_english->arabic; }?>" class="form-control col-md-7 col-xs-12">
                </div>
                <div id="error" style="color: red;"></div>
            </div>


            <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Slug <span class="required">*</span>
                    </label>

                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input type="text" readonly="" required=""  maxlength="150" id="slug" title="slug is required"  name="slug" value="<?php 
                     if(count($makes_list) >0){ echo $makes_list[0]['slug']; }?>" class="form-control col-md-7 col-xs-12">
                </div>
            </div>

            <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="status">Status</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
       
                    <select required="" class="form-control select_withoutcross" id="status" name="status">

                        <option <?php if(count($makes_list) >0 && $makes_list[0]['status'] == 1){ echo 'selected'; }?> value="1">Active</option>
                        <option <?php if(count($makes_list) >0 && $makes_list[0]['status'] == 0){ echo 'selected'; }?> value="0">Inactive</option>
                    </select>
                </div>
            </div>
          
            <div class="ln_solid"></div>
                
                 <div class="form-group">
                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                    <?php if($title == 'Edit Makes' ){  ?>
                    <button type="submit" id="send" class="btn btn-success">update</button>
                    <a href="<?php echo base_url().'cars/makes'; ?>" class="custom-class">Cancel</a>
                    <?php } else{ ?>

                    <button id="send" type="submit" class="btn btn-success">Submit</button>
                    <a href="<?php echo base_url().'cars/makes'; ?>" class="custom-class">Cancel</a>
                     <?php }?>
                </div>
            </div>
            </form>
        </div>

    <script>	
        $("#title").keyup(function(){
            var Text = $(this).val();
            Text = Text.toLowerCase();
            Text = Text.replace(/[^a-zA-Z0-9]+/g,'-');
            $("#slug").val(Text);        
        });
     var ok;
    var url = '<?php echo base_url();?>';
    var formaction_path = '<?php echo $formaction_path;?>';
    $(document).ready(function() {
        // You can use the locally-scoped $ in here as an alias to jQuery.
         $('#demo-form2').parsley().on('field:validated', function() {
    var ok = $('.parsley-error').length === 0;
  }) .on('form:submit', function() {
            var formData = new FormData($("#demo-form2")[0]);
            // console.log(formData);
            // return;
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

                            window.location = url + 'cars/makes';
                        } 
                        else if(objData.msg == 'duplicate')
                        {
                            // $('.msg-alert').css('display', 'block');
                            $("#result").html('<div class="alert" ><div class="alert alert-domain alert-danger alert-dismissible fade in  msg-alert" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: black"><span aria-hidden="true">×</span> </button>' + objData.response + '</div></div>');
                        }
                        else {
                            // $('.msg-alert').css('display', 'block');
                            $("#result").html('<div class="alert" ><div class="alert alert-domain alert-danger alert-dismissible fade in  msg-alert" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: black"><span aria-hidden="true">×</span> </button>' + objData.response + '</div></div>');

                        }
                    });
                      return false; // Don't submit form for this demo
                });
            });
    // });

    </script>

