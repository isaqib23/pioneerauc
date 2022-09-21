<script src="<?php echo base_url();?>assets_admin/js/formBuilder/jquery-ui.min.js"></script>
<script src="<?php echo base_url();?>assets_admin/js/formBuilder/form-render.min.js"></script>


<input type="hidden" id="base_url" value="<?php echo base_url(); ?>">

<div class="clearfix"></div>

<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>
                <?php  echo $small_title; ?> Item</h2>

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
           
            <form method="post" novalidate="" id="demo-form2" name="ozform" action="" enctype="multipart/form-data" class="form-horizontal form-label-left">
                <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
                <?php if(isset($item_info) && !empty($item_info)){ ?>
                    <input type="hidden" name="item[id]" value="<?php echo $this->uri->segment(3); ?>">
                <?php } ?>  
                <span class="section">Basic Info</span>
                <div class="item form-group">    
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Name <span class="required">*</span>
                        </label>

                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text"   id="name" required="required" name="item[name]" value="<?php if(isset($item_info) && !empty($item_info)) { echo $item_info[0]['name']; } ?>" class="form-control col-md-7 col-xs-12">
                    </div>
                </div> 

                <div class="item form-group">    
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="price">Price <span class="required">*</span>
                        </label>

                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="number" id="price" required="required" name="item[price]" value="<?php if(isset($item_info) && !empty($item_info)) { echo $item_info[0]['price']; } ?>" class="form-control col-md-7 col-xs-12">
                    </div>
                </div> 

                <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="category_id">Item Category</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <select class="form-control col-md-7 col-xs-12" id="category_id" name="item[category_id]" required>
                         <option disabled="" selected="" >Select Category</option>
                         <?php foreach ($category_list as $category_value) { ?>
                        <option 
                        <?php if(isset($item_info) && !empty($item_info)){
                            if($item_info[0]['category_id'] == $category_value['id']){ echo 'selected';}
                        }?>
                            value="<?php echo $category_value['id']; ?>"><?php echo $category_value['title']; ?></option>
                            <?php  } ?> 
                          
                    </select>
                </div>
                </div>


                <div class="item form-group">    
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="lot_id">Lot Id <span class="required">*</span>
                        </label>

                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" id="lot_id" required="required" name="item[lot_id]" value="<?php if(isset($item_info) && !empty($item_info)) { echo $item_info[0]['lot_id']; } ?>" class="form-control col-md-7 col-xs-12">
                    </div>
                </div> 

                
                <div class="item form-group">    
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="detail">Details <span class="required">*</span>
                        </label>

                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <textarea type="text" rows="5"  maxlength="150" id="detail" title="please add something" name="item[detail]" class="form-control col-md-7 col-xs-12"><?php 
                        if(isset($item_info) && !empty($item_info)){ echo $item_info[0]['detail']; }?></textarea>
                    </div>
                </div>
                
                <div class="item form-group">    
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Status <span class="required">*</span>
                </label>
                
                <div class="col-md-6 col-sm-6 col-xs-12"> 
                    <select required="" class="form-control select2" id="status" name="item[status]">
                        <option <?php if(isset($category_info) && !empty($category_info) && $category_info[0]['status'] == 'active'){ echo 'selected'; }?> value="active">Active</option>
                        <option <?php if(isset($category_info) && !empty($category_info) && $category_info[0]['status'] == 'inactive'){ echo 'selected'; }?> value="inactive">In Active</option>
                    </select>
                </div>
                </div>
             

                <input type="hidden" name="item[seller_id]" value="<?php echo $this->session->userdata('logged_in')->id; ?>">
                <input type="hidden" name="item[auction_type]" value="1">
                   
                <span class="section">Images</span>   
                <div class="image-gallery">  
                <div id="drop-zone" class="dropzone"></div>
                </div>
                <div class="ln_solid"></div>

                <span class="section">Documents</span>   
                <div class="image-gallery">  
                <div id="drop-zone"></div>
                </div>
                <div class="ln_solid"></div>

                <span class="section">Other Info</span>   
                <div class="other-info">
                <div id="build-wrap"></div>
                </div>
                <div class="ln_solid"></div>

                <div class="form-group">
                    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                        <button type="button" id="send" class="btn btn-success">Submit</button>
                        <a href="<?php echo base_url().'Items'; ?>" class="custom-class" type="button">Cancel</a>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>
<?php //echo '<pre>'; print_r($field_ids); echo '</pre>';?>

    <!-- Dropzone.js -->
    <script src="<?php echo base_url();?>assets_admin/vendors/dropzone/dist/dropzone.js"></script>
<script>
    var url = '<?php echo base_url();?>';
    var formaction_path = '<?php echo $formaction_path;?>';
    var data_ids_json = '<?php echo json_encode($field_ids); ?>';
    var data_values_json = '<?php echo json_encode($field_values); ?>';
    $(document).ready(function() {
        // You can use the locally-scoped $ in here as an alias to jQuery.
        // $(function() {
            $("#send").on('click', function(e) { //e.preventDefault();
                
                var formData = new FormData($("#demo-form2")[0]);
       
                    $.ajax({
                        url: url + 'items/' + formaction_path,
                        type: 'POST',
                        data: formData,
                        cache: false,
                        contentType: false,
                        processData: false
                    }).then(function(data) {
                        var objData = jQuery.parseJSON(data);
                        if (objData.msg == 'success') { 
                            window.location = url + 'Items';

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
        $('#demo-form2').parsley().on('field:validated', function() {
            var ok = $('.parsley-error').length === 0;
            }) .on('form:submit', function() {
         return false; // Don't submit form for this demo
        });
});
    getcategory_fields();
    function getcategory_fields() {
        var category_id = $('#category_id').val();
         $.ajax({    //create an ajax request to display.php
                    type: "post",
                    url: "<?php echo base_url(); ?>" + "items/get_item_fields",    
                    data:{ category_id:category_id},
                    success: function(data) {
                     data = $.parseJSON(data);
                     data_values_arr = $.parseJSON(data_values_json);
                     var lim = data.length;
                    console.log(lim);
                    for (var i = 0; i < lim; i++)
                    {

                       if (data[i].id != '')
                       { 
                            data[i].name = data[i].id+'-'+data[i].name;
                            data[i].value = data_values_arr[i];

                            // break;
                       }
                    }

                    console.log(data);

                    formData = data;
                    var formRenderOpts = {
                        formData,
                        dataType: 'json'

                    };
                    var frInstance = $('#build-wrap').formRender(formRenderOpts);
                    console.log(frInstance);

                }
              });
    }

    $('#category_id').on('change', function() {
        var category_id = $(this).val();
        // console.log(category_id);

           $.ajax({    //create an ajax request to display.php
                    type: "post",
                    url: "<?php echo base_url(); ?>" + "items/get_item_fields",    
                    data:{ category_id:category_id},
                    success: function(data) {
                     data = $.parseJSON(data);
                     var lim = data.length;
                    console.log(lim);
                    for (var i = 0; i < lim; i++)
                    {
                       if (data[i].id != '')
                       { 
                            data[i].name = data[i].id+'-'+data[i].name;
                            // break;
                       }
                    }

                    console.log(data);

                    formData = data;
                    var formRenderOpts = {
                        formData,
                        dataType: 'json'

                    };
                    var frInstance = $('#build-wrap').formRender(formRenderOpts);
                    console.log(frInstance);

                }
              });
            }); 
</script>