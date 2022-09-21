
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

            <?php if($this->session->flashdata('error')){ ?>
            <div class="alert">
                <div class="alert alert-domain alert-danger alert-dismissible fade in" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: black"><span aria-hidden="true">×</span>
                      </button>
                    <?php echo $this->session->flashdata('error'); ?>
                </div>
            </div>
            <?php }?>
           
            <form method="post" novalidate="" id="demo-form2" enctype="multipart/form-data" class="form-horizontal form-label-left">
                <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />

               <?php if(isset($charges_info) && !empty($charges_info)){ 
                $title = json_decode($charges_info['title']);
                ?>
                    <input type="hidden" name="id" value="<?php echo $this->uri->segment(3); ?>">
                <?php } ?>  
                
                <div class="item form-group">    
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Title <span class="required">*</span>
                        </label>

                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" id="title" name="title" value="<?php if(isset($charges_info) && !empty($charges_info)) { echo $title->english; } ?>" class="form-control col-md-7 col-xs-12">
                        <span class="valid-error text-danger title-error"></span>
                    </div>
                </div> 
                <div class="item form-group">    
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Arabic Title <span class="required">*</span>
                        </label>

                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" id="title" dir="rtl" name="arabic_title" value="<?php if(isset($charges_info) && !empty($charges_info)) { echo $title->arabic; } ?>" class="form-control col-md-7 col-xs-12">
                        <span class="valid-error text-danger arabic_title-error"></span>
                    </div>
                </div>

                <div class="item form-group">    
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="description">Description <span class="required"></span>
                        </label>

                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <textarea id="description" rows="4" name="description" class="form-control col-md-7 col-xs-12"><?php if(isset($charges_info) && !empty($charges_info)) { echo $charges_info['description']; } ?></textarea>
                    </div>
                </div> 
                

                <div class="item form-group">    
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="commission">Amount <span class="required">*</span>
                        </label>

                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input min="1" type="number" id="commission" name="commission" value="<?php if(isset($charges_info) && !empty($charges_info)) { echo $charges_info['commission']; } ?>" class="form-control col-md-7 col-xs-12">
                        <span class="valid-error text-danger commission-error"></span>
                    </div>
                </div> 

                <div class="item form-group">    
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="type">Type <span class="required">*</span>
                </label>
                
                <div class="col-md-6 col-sm-6 col-xs-12"> 
                    <select required="" class="form-control select2" id="type" name="type">
                        <option <?php if(isset($charges_info) && !empty($charges_info) && $charges_info['type'] == 'amount'){ echo 'selected'; }?> value="amount">Amount</option>
                        <option <?php if(isset($charges_info) && !empty($charges_info) && $charges_info['type'] == 'percent'){ echo 'selected'; }?> value="percent">Percent (%)</option>
                    </select>
                </div>
                </div>

                <div class="item form-group">    
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="apply_vat">Apply Vat <span class="required">*</span>
                </label>
                
                <div class="col-md-6 col-sm-6 col-xs-12"> 
                    <select required="" class="form-control select2" id="apply_vat" name="apply_vat">
                        <option <?php if(isset($charges_info) && !empty($charges_info) && $charges_info['apply_vat'] == 'yes'){ echo 'selected'; }?> value="yes">Yes</option>
                        <option <?php if(isset($charges_info) && !empty($charges_info) && $charges_info['apply_vat'] == 'no'){ echo 'selected'; }?> value="no">No</option>
                    </select>
                </div>
                </div>

                <div class="item form-group">    
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="status">Status <span class="required">*</span>
                </label>
                
                <div class="col-md-6 col-sm-6 col-xs-12"> 
                    <select required="" class="form-control select2" id="status" name="status">
                        <option <?php if(isset($charges_info) && !empty($charges_info) && $charges_info['status'] == 'active'){ echo 'selected'; }?> value="active">Active</option>
                        <option <?php if(isset($charges_info) && !empty($charges_info) && $charges_info['status'] == 'inactive'){ echo 'selected'; }?> value="inactive">In Active</option>
                    </select>
                </div>
                </div>

                <div class="ln_solid"></div>
                <div class="form-group">
                    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                        <button type="button" id="sendbtnn" class="btn btn-success">Submit</button>
                        <a href="<?php echo base_url().'settings/seller'; ?>" class="custom-class" type="button">Cancel</a>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>


<script>
    var url = '<?php echo base_url();?>';
    var formaction_path = '<?php echo $formaction_path;?>';
    $(document).ready(function() {
        // You can use the locally-scoped $ in here as an alias to jQuery.
        // $(function() {
            $("#sendbtnn").on('click', function(e) { 
            e.preventDefault();
            var validation = false;
                selectedInputs = ['title', 'arabic_title', 'commission'];
                validation = validateFields(selectedInputs);
                if (validation == true) {
                    // var formData = new FormData($("#demo-form2")[0]);
                    var formData = $("#demo-form2").serializeArray();
                    // alert(formaction_path);
                    // alert('in');
                    $.ajax({
                        url: url + 'settings/' + formaction_path+'?type=seller',
                        type: 'POST',
                        data: formData,
                    }).then(function(data) {
                        var objData = jQuery.parseJSON(data);
                        // console.log(objData);
                        if (objData.error == false) { 
                            window.location = url + 'settings/seller';

                        } else {
                            $('.msg-alert').css('display', 'block');
                            $("#result").html('<div class="alert" ><div class="alert alert-domain alert-danger alert-dismissible fade in  msg-alert" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: black"><span aria-hidden="true">×</span> </button>' + objData.message + '</div></div>');
                        }
                    });
                }
            });
        // });
        // $('#demo-form2').parsley().on('field:validated', function() {
            
        // var ok = $('.parsley-error').length === 0;
        // }) .on('form:submit', function() {
        //     // return false; // Don't submit form for this demo
            
        // });
    });
</script>