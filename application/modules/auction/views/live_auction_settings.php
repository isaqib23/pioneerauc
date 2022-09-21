<style type="text/css">
    .dz-image img{
    max-width: 120px;
    max-height: 120px;
    }
</style>

<script src="<?php echo base_url()?>assets_admin/vendors/jquery.tagsinput/src/jquery.tagsinput.js"></script>

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

            <?php if($this->session->flashdata('success')){ ?>
            <div class="alert">
                <div class="alert alert-domain alert-success alert-dismissible in" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: black"><span aria-hidden="true">×</span>
                      </button>
                    <?php echo $this->session->flashdata('success'); ?>
                </div>
            </div>
            <?php }?>
            <?php if($this->session->flashdata('error')){ ?>
            <div class="alert">
                <div class="alert alert-domain alert-danger alert-dismissible in" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: black"><span aria-hidden="true">×</span>
                      </button>
                    <?php echo $this->session->flashdata('error'); ?>
                </div>
            </div>
            <?php }?>
           
            <form method="post" novalidate="" id="demo-form2" name="ozform" action="" enctype="multipart/form-data" class="form-horizontal form-label-left">
                <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
                <?php if(isset($auction_live_setting) && !empty($auction_live_setting)){ ?>
                    <input type="hidden" name="id" value="<?php echo $auction_live_setting['id']; ?>">
                <?php } ?>  
                 

                <div class="item form-group">    
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="buttons">Bid Options Buttons <span class="required">*</span>
                    </label>

                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="buttons" name="buttons" required type="text" class="tags form-control" value="<?php if(isset($auction_live_setting) && !empty($auction_live_setting)) { echo $auction_live_setting['buttons']; }else{ echo '100,500,1000'; } ?>" />
                          <div id="suggestions-container" style="position: relative; float: left; width: 250px; margin: 10px;"></div>
                    </div>
                </div> 

                <div class="item form-group">    
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="buttons">Minimum increment amount <span class="required">*</span>
                    </label>

                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input name="min_increment" required type="number" class="tags form-control" value="<?php if(isset($auction_live_setting) && !empty($auction_live_setting)) { echo $auction_live_setting['min_increment']; } ?>" />
                          <!-- <div id="suggestions-container" style="position: relative; float: left; width: 250px; margin: 10px;"></div> -->
                    </div>
                </div> 

                <div class="item form-group">    
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="description">
                        Description
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <textarea type="text" rows="5"  maxlength="150" id="description" title="please add something" name="description" class="form-control col-md-7 col-xs-12"><?php 
                        if(isset($auction_live_setting) && !empty($auction_live_setting)){ echo $auction_live_setting['description']; }?></textarea>
                    </div>
                </div> 

                <div class="item form-group">    
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="description">
                        Live Video Link
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <textarea type="text" rows="5" title="please add link" name="live_video_link" class="form-control col-md-7 col-xs-12"><?php if(isset($auction_live_setting) && !empty($auction_live_setting)){ echo $auction_live_setting['live_video_link']; } ?></textarea>
                    </div>
                </div> 

                <!-- <div class="item form-group">    
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="allow_online">Allow Online <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12"> 
                    <select required="" class="form-control select2" id="allow_online" name="allow_online">
                        <option <?php //if(isset($auction_info) && !empty($auction_info) && $auction_info[0]['allow_online'] == 'yes'){ echo 'selected'; }?> value="yes">Yes</option>
                        <option <?php //if(isset($auction_info) && !empty($auction_info) && $auction_info[0]['allow_online'] == 'no'){ echo 'selected'; }?> value="no">No</option>
                    </select>
                </div>
                </div> -->

                <div class="form-group">
                    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                    <button type="submit" id="send" class="btn btn-success"><?= $button_status;?></button> 
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>


<script>
    
    function init_TagsInput2() 
    {
        if(typeof $.fn.tagsInput !== 'undefined'){  
            $('#buttons').tagsInput({
              width: 'auto',
              defaultText:'add button',
            });
        }     
    } 

    var url = '<?php echo base_url();?>';
    var formaction_path = '<?php echo $formaction_path;?>';

    $(document).ready(function() {
        init_TagsInput2();
         // You can use the locally-scoped $ in here as an alias to jQuery.
        $('#demo-form2').parsley().on('field:validated', function() {
            var ok = $('.parsley-error').length === 0;
          }) .on('form:submit', function() {
             var formData = new FormData($("#demo-form2")[0]);
            
            $.ajax({
                url: url + 'auction/' + formaction_path,
                type: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success : function(data) {
                    var objData = jQuery.parseJSON(data);
                   if (objData.error == false) { 
                        window.location = url + 'auction/live_auction_controller';
                    }else{
                        $('.msg-alert').css('display', 'block');
                        $("#result").html('<div class="alert" ><div class="alert alert-domain alert-danger alert-dismissible fade in  msg-alert" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: black"><span aria-hidden="true">×</span> </button>' + objData.msg + '</div></div>');
                    }  
                }

            });
            return false; // Don't submit form for this demo
          });
  
    
    });
     
</script>