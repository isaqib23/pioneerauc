
     <div class="x_title">
        <h2><?php  echo $title; ?></h2>
        <div class="clearfix"></div>
    </div>

    <div></div>
    <div class="container" style="padding-top: 10px">
    	<form method="post" novalidate="" id="demo-form2" name="myForm" action="" enctype="multipart/form-data" class="form-horizontal form-label-left">
            <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
               <?php if(isset($price_list) && !empty($price_list)){ ?>
                <input type="hidden" name="id" value="<?php echo $this->uri->segment(3); ?>">
            <?php } ?>
                       
            <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="specialization_id"> Make <span class="required">*</span></label>
                
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <select required="" class="form-control select_withoutcross" id="make_id" name="valuation_make_id">
                        <option  value="">Select Make</option>
                        <?php foreach ($makes_list as $key => $value) { 
                            $title = json_decode($value['title']);
                            ?>
                        <option 
                        <?php if(count($price_list) > 0){
                            if($price_list[0]['valuation_make_id'] == $value['id']){ echo 'selected';}
                        }?>
                            value="<?php echo $value['id']; ?>"><?php echo $title->english; ?></option>
                            <?php  } ?>
                    </select>
                </div>
            </div>

     
            <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="valuation_model_id">Model<span class="required">*</span></label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                <select required=""  onchange="model_change()" name="valuation_model_id" id="valuation_model_id" class="form-control col-md-7 col-xs-12">valuation_model_id
                    <!-- <option  value="">Select Model</option> -->
                </select>
               </div>

                <div id="error_engine_size"></div>
            </div>
                <div id="result"></div>

            

                <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Year <span class="required">*</span>
                </label>

                    <div class="col-md-6 col-sm-6 col-xs-12">
                        
                        <select required="" id="valuation_years" name="year" class="form-control col-md-7 col-xs-12"> 
                        </select>
                    </div>
                </div>
 


            <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Price <span class="required">*</span>
                </label>

                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input type="text" required="" maxlength="150" class="form-control col-md-7 col-xs-12" id="price"  name="price" value="<?php 
                    if(count($price_list) >0){ echo $price_list[0]['price']; }?>">
                </div>
                <div id="error" style="color: red;"></div>
            </div>
 
            

            <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="valuation_engine_size">Engine Size  <span class="required">*</span></label>
                <div id="myModal" class="col-md-6 col-sm-6 col-xs-12">
                    <select   class="form-control select_withoutcross js-example-basic-multiple" id="valuation_engine_size" required  name="engine_size_id">
                        <?php $engin_arr = $price_list[0]['engine_size_id'];  ?>
                        <option value="">Select Engine Size</option>     
                        <?php foreach ($eng_size_list as $key => $value) { ?>
                         <option <?php if(count($price_list) >0){ if($value['id'] == $engin_arr){echo "selected";} }?>
                          value="<?php echo $value['id']; ?>"><?php echo $value['title']; ?></option>
                         <?php  } ?>
                    </select>
                </div>
                <div id="error_engine_size"></div>
            </div>

           <!--  <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="status">Option</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <select class="form-control col-md-7 col-xs-12" id="option_id" name="option_id">
                         <option disabled="" selected="" >Select Option</option>
                         <?php //foreach ($option_list as $list) { ?>
                        <option 
                        <?php //if(count($price_list) >0 && $list['id'] == $price_list[0]['option_id']){echo 'selected';}?>
                            value="<?php //echo $list['id']; ?>"><?php //echo $list['title']; ?></option>
                            <?php  //} ?> 
                          
                    </select>
                </div>
            </div> -->



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
                    <button type="submit" id="send" class="btn btn-success">Submit</button>
                    <a href="<?php echo base_url().'cars/prices'; ?>" class="custom-class" type="button">Cancel</a>
                </div>
            </div>
        </form>
    </div>

    <script >
      
                  
    $('.js-example-basic-multiple').select2();

          $('#make_id').on('change', function() {
            var make_id = document.myForm.valuation_make_id.value;
               $.ajax({    //create an ajax request to display.php
                        type: "post",
                        url: "<?php echo base_url(); ?>" + "cars/get_make_data/" + make_id,    
                        data:{ make_id:make_id,'<?= $this->security->get_csrf_token_name();?>':"<?=$this->security->get_csrf_hash();?>"},
                        success: function(data) {
                         console.log(data);
                        $('select#valuation_model_id').html('');
                        $('#valuation_model_id').html('<option value=""> Select Model </option>')
                        $.each(JSON.parse(data), function(i,v) {
                            var title = $.parseJSON(v.title);
                          $("<option / >").val(v.id).text(title.english).appendTo('select#valuation_model_id');
                          // body...
                        });
                    }
                  });
                });
                
               //$('#valuation_model_id').on('change', function() {
                function model_change(){ 
                var val = $('#valuation_model_id option:selected').val();   
                var model_id=$('#valuation_model_id option:selected').val();
               $.ajax({    //create an ajax request to display.php
                        type: "post",
                        url: "<?php echo base_url(); ?>" + "cars/get_engineBymodel/" + model_id,
                        data:{'<?= $this->security->get_csrf_token_name();?>':"<?=$this->security->get_csrf_hash();?>"},    
                        success: function(data) {
                        // $('select#valuation_engine_size').html('');
                        $('select#valuation_years').html('<option value=""> Select Year </option>');
                        //$('select#valuation_engine_size').html('<option value=""> Select Engine Size </option>');
                        // console.log(data.engine_sizes);alert('a');
                        objData = jQuery.parseJSON(data);
                       
                        $.each(objData.engine_sizes, function(i,v) {
                        //  $("<option / >").val(v.id).text(v.title).appendTo('select#valuation_engine_size');
                        });
                        $.each(objData.years, function(i,v) {
                          $("<option / >").val(v.year).text(v.year).appendTo('select#valuation_years');
                        });

                    }
                  });
                }
                //}); 

                var make_id = $("#make_id option:selected").val();
                 $.ajax({    //create an ajax request to display.php
                        type: "post",
                        url: "<?php echo base_url(); ?>" + "cars/get_make_data/" + make_id,    
                        data:{ make_id:make_id,'<?= $this->security->get_csrf_token_name();?>':"<?=$this->security->get_csrf_hash();?>"},
                        success: function(data) {
                         console.log(data);
                        $('select#valuation_model_id').html('<option value="" > Select Model </option>');
                        $.each(JSON.parse(data), function(i,v) {
                          $("<option / >").val(v.id).text(v.title).appendTo('select#valuation_model_id');
                        });
                        // model_change();
                    }
                  });

    var ok;
    var url = '<?php echo base_url();?>';
    var formaction_path = '<?php echo $formaction_path;?>';
    $(document).ready(function() {
        // You can use the locally-scoped $ in here as an alias to jQuery.
        
        $('#demo-form2').parsley().on('field:validated', function()  {
        var ok = $('.parsley-error').length === 0;
        }).on('form:submit', function(){
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
                            window.location = url + 'cars/prices';
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

