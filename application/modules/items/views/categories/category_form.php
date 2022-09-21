<link rel="stylesheet" href="<?= base_url(); ?>assets_admin/vendors/pnotify/dist/pnotify.css">
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
                
            <div class="result"></div> 
           
            <form method="post" novalidate="" id="demo-form2" action="" enctype="multipart/form-data" class="form-horizontal form-label-left">
                <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
               <?php if(isset($category_info) && !empty($category_info)){ 
                $title = json_decode($category_info['title']);  ?>
                <input type="hidden" name="id" value="<?php echo $this->uri->segment(3); ?>">
            <?php } ?>  
                
                <div class="item form-group">    
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title_english">English Title <span class="required">*</span>
                        </label>

                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" id="title_english" required="required" name="title_english" value="<?php if(isset($category_info) && !empty($category_info)) { echo $title->english; } ?>" class="form-control col-md-7 col-xs-12">
                    </div>
                </div>


                <div class="item form-group">    
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title_arabic"> Arabic Title
                        <span class="required">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" dir="rtl" id="title_arabic" required="required" name="title_arabic" value="<?php if(isset($category_info) && !empty($category_info)) { echo $title->arabic; } ?>" class="form-control col-md-7 col-xs-12">
                    </div>
                </div>

                <div class="item form-group">    
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="include_make_model">Include Make & Model <span class="required">*</span>
                    </label>
                    
                    <div class="col-md-6 col-sm-6 col-xs-12"> 
                        <select required="" class="form-control select2" id="include_make_model" name="include_make_model">
                            <option <?php if(isset($category_info) && !empty($category_info) && $category_info['include_make_model'] == 'no'){ echo 'selected'; }?> value="no">No</option>
                            <option <?php if(isset($category_info) && !empty($category_info) && $category_info['include_make_model'] == 'yes'){ echo 'selected'; }?> value="yes">Yes</option>
                        </select>
                    </div>
                </div>

                <?php
                if(isset($category_info) && !empty($category_info))
                { 
                    ?>
                    <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="profile_picture">Category Icon
                        </label>
                        <input type="hidden" name="old_files" value="<?= $category_info['category_icon']; ?>">
                        <?php 
                        if (isset($category_info['category_icon']) && !empty($category_info['category_icon'])) 
                        {  $file = $this->db->get_where('files', ['id' => $category_info['category_icon']])->row_array();  ?>
                            <div class="col-md-3 col-sm-3 col-xs-12">
                                <img  height="100" src="<?php echo base_url();?>uploads/category_icon/<?php echo (isset($file) && !empty($file['name'])) ? $file['name'] : 'default.png'; ?>">
                            </div>
                        <?php
                        }?>
                    </div>
                <?php
                }
                ?> 
                 
                <div class="item form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="profile_picture"> Icon
                        </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="category_icon" name="category_icon" onchange="Filevalidation()" type="file" accept=".jpg,.jpeg,.png" class="file" multiple>
                        <span>Max Image Size: 5Mb</span>
                    </div>
                </div> 

               <!--  <?php
                //if(isset($category_info) && !empty($category_info))
                { ?>
                    <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="profile_picture">Category Hover Icon
                        </label>
                        <input type="hidden" name="old_files" value="<?//= $category_info['category_hover_icon']; ?>">
                        <?php 
                        if (isset($category_info['category_hover_icon']) && !empty($category_info['category_hover_icon'])) {
                        $file = $this->db->get_where('files', ['id' => $category_info['category_hover_icon']])->row_array(); ?>
                            <div class="col-md-3 col-sm-3 col-xs-12">
                                <img  height="100" src="<?php echo base_url();?>uploads/category_icon/<?php echo (isset($file) && !empty($file['name'])) ? $file['name'] : 'default.png'; ?>">
                            </div>
                        <?php
                        }?>
                    </div>
                <?php
                }
                ?> 
                 
                <div class="item form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="profile_picture"> Hover Icon
                        </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="category_hover_icon" name="category_hover_icon" type="file" class="file" multiple>
                        <span>Max Image Size: 5Mb</span></div>
                </div>  -->

                <div class="item form-group">    
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="status">Status <span class="required">*</span>
                    </label>
                    
                    <div class="col-md-6 col-sm-6 col-xs-12"> 
                        <select required="" class="form-control select2" id="status" name="status">
                            <option <?php if(isset($category_info) && !empty($category_info) && $category_info['status'] == 'active'){ echo 'selected'; }?> value="active">Active</option>
                            <option <?php if(isset($category_info) && !empty($category_info) && $category_info['status'] == 'inactive'){ echo 'selected'; }?> value="inactive">In Active</option>
                        </select>
                    </div>
                </div>

                  <div class="item form-group">    
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="">Show On Web</label>
                    <div class="col-md-6 col-sm-6 col-xs-12"> 
                        <select  class="form-control"  name="show_web">
                            <option <?php if(isset($category_info) && !empty($category_info) && $category_info['show_web'] == 'yes'){ echo 'selected'; }?> value="yes">Yes</option>
                            <option <?php if(isset($category_info) && !empty($category_info) && $category_info['show_web'] == 'no'){ echo 'selected'; }?> value="no">No</option>
                        </select>
                    </div>
                </div>

                  <div class="item form-group">    
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="">Sort Order <span class="required">*</span>
                        </label>

                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" oninput="this.value=this.value.replace(/[^0-9]/g,'');" required="required" name="sort_order" value="<?php if(isset($category_info) && !empty($category_info)) { echo $category_info['sort_order']; } ?>" class="form-control col-md-7 col-xs-12">
                    </div>
                </div>

                <div class="item form-group">    
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="">Slug <span class="required"></span>
                        </label>

                  <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" id="slug"  name="slug" value="<?php if(isset($category_info) && !empty($category_info)) { echo $category_info['slug']; } ?>" class="form-control col-md-7 col-xs-12">
                    </div>
                </div>


                <div class="ln_solid"></div>
                <div class="form-group">
                    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                        <button type="button" id="send" class="btn btn-success">Submit</button>
                        <a href="<?php echo base_url().'items/categories'; ?>" class="custom-class" type="button">Cancel</a>
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
                            window.location = url + 'items/categories';

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
<script src="<?= base_url(); ?>assets_admin/vendors/pnotify/dist/pnotify.js"></script>
<script>
    Filevalidation = () => {
        const fi = document.getElementById('category_icon');
        // Check if any file is selected.
        const allowedSize = "<?= $this->config->item('image_size');?>";
        if (fi.files.length > 0) {
            const i =0;
            // for (const i = 0; i <= fi.files.length - 1; i++) {
                const fsize = fi.files.item(i).size;
                const file = Math.round((fsize / 1024));
                // The size of the file.
                if (file >= allowedSize) {
                    PNotify.removeAll();
                          new PNotify({
                              text: 'File too Big, please select a file less than 5mb',
                              type: 'error',
                              addclass: 'custom-error',
                              delay: 6000,
                              title: "Error!",
                              styling: 'bootstrap3'
                          });
                    $('#send').attr('disabled','disabled');
                } 
                else {
                    $('#send').removeAttr('disabled');
                }
            // }
        }
    }
</script>