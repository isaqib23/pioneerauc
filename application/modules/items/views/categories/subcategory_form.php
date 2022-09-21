
<input type="hidden" id="base_url" value="<?php echo base_url(); ?>">

<div class="clearfix"></div>

<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>

                <?php  echo $small_title; ?> Category</h2>

            <div class="clearfix"></div>
        </div>
        <div class="x_content">
        <!-- <div id="result"></div> -->

            <?php if($this->session->flashdata('error')){ ?>
            <div class="alert">
                <div class="alert alert-domain alert-danger alert-dismissible fade in" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: black"><span aria-hidden="true">×</span>
                      </button>
                    <?php echo $this->session->flashdata('error'); ?>
                </div>
            </div>
            <?php }?>
           
            <form method="post" novalidate="" id="demo-form2" action="" enctype="multipart/form-data" class="form-horizontal form-label-left">
                <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
               <?php if(isset($subcategory_info) && !empty($subcategory_info)){ ?>
                <input type="hidden" name="id" value="<?php echo $this->uri->segment(3); ?>">
            <?php } ?>  
                <input type="hidden" name="category_id" value="<?php echo $category_id; ?>">
                <div class="item form-group">    
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">English Title <span class="required">*</span>
                        </label>
                       
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" id="title" required="required" name="title" value="<?php if(isset($subcategory_info) && !empty($subcategory_info)) {
                             $title_english = json_decode($subcategory_info[0]['title']);
                         echo $title_english->english; } ?>" class="form-control col-md-7 col-xs-12">
                    </div>
                </div>

                <div class="item form-group">    
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Arabic Title <span class="required">*</span>
                        </label>
                        
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" dir="rtl" id="title" required="required" name="title_arabic" value="<?php if(isset($subcategory_info) && !empty($subcategory_info)) {
                            $title_arabic = json_decode($subcategory_info[0]['title']);
                         echo $title_arabic->arabic; } ?>" class="form-control col-md-7 col-xs-12">
                    </div>
                </div>
               
                <div class="item form-group">    
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="description">Description  
                </label>
                
                <div class="col-md-6 col-sm-6 col-xs-12"> 
                     <textarea rows="5"  maxlength="150" id="description" title="please add something" name="description" class="form-control col-md-7 col-xs-12"><?php if(isset($subcategory_info) && !empty($subcategory_info)) { echo $subcategory_info[0]['description']; } ?></textarea>
                </div>
                </div>

                <div class="item form-group">    
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="status">Status <span class="required">*</span>
                </label>
                
                <div class="col-md-6 col-sm-6 col-xs-12"> 
                    <select required="" class="form-control select2" id="status" name="status">
                        <option <?php if(isset($subcategory_info) && !empty($subcategory_info) && $subcategory_info[0]['status'] == 'active'){ echo 'selected'; }?> value="active">Active</option>
                        <option <?php if(isset($subcategory_info) && !empty($subcategory_info) && $subcategory_info[0]['status'] == 'inactive'){ echo 'selected'; }?> value="inactive">Inactive</option>
                    </select>
                </div>
                </div>

                <div class="ln_solid"></div>
                <div class="form-group">
                    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                        <button type="button" id="send" class="btn btn-success">Submit</button>
                        <a href="<?php echo base_url().'items/subcategories/'.$category_id; ?>" class="custom-class" type="button">Cancel</a>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>


<script>
    var url = '<?php echo base_url();?>';
    var formaction_path = '<?php echo $formaction_path;?>';
    var category_id = '<?php echo $category_id;?>';
    $(document).ready(function() {
        // You can use the locally-scoped $ in here as an alias to jQuery.
        // $(function() {
            $("#send").on('click', function(e) { //e.preventDefault();
                
                var formData = new FormData($("#demo-form2")[0]);
                    // alert(formaction_path);
                    $.ajax({
                        url: url + 'items/' + formaction_path,
                        type: 'POST',
                        data: formData,
                        cache: false,
                        contentType: false,
                        processData: false
                    }).then(function(data) {
                        var objData = jQuery.parseJSON(data);
                        // console.log(objData);
                        if (objData.msg == 'success') { 
                            window.location = url + 'items/subcategories/'+category_id;

                        } else {
                            $('.msg-alert').css('display', 'block');
                            $("#result").html('<div class="alert" ><div class="alert alert-domain alert-danger alert-dismissible fade in  msg-alert" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: black"><span aria-hidden="true">×</span> </button>' + objData.msg + '</div></div>');

                               window.setTimeout(function() {
                            $(".alert").fadeTo(500, 0).slideUp(500, function(){
                                $(this).remove(); 
                            });
                        }, 3000);

                        }
                    });
            });
        // });
        
        // $('#demo-form2').parsley().on('field:validated', function() {
        //     var ok = $('.parsley-error').length === 0;
        //     }) .on('form:submit', function() {
        //  return false; // Don't submit form for this demo
        // });
});
</script>