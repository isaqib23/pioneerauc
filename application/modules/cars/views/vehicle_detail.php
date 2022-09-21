
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
        <form method="post" novalidate=""name = "myForm"  id="make_form" action="<?php echo base_url(); ?>admin/cars/<?php if($title == 'vehicles Detail' ){  echo "save_detail"; } else { echo "vehicles";}?>" enctype="multipart/form-data" class="form-horizontal form-label-left">
            <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
                <?php if($title == 'Edit' ){  ?>
            <input type="hidden" name="id" value="<?php echo $this->uri->segment(4); ?>">
                <?php } ?>
                     
             <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="specialization_id">Car<span class="required">*</span></label>
                
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <select  class="form-control select_withoutcross" id="car_id" name="car_id">
                    
                        <option  value="">Select Car</option>
                    
                            <?php foreach ($cars_data as $key => $value) { ?>
                        <option <?php if(count($cars_data) >0){ echo $cars_data[0]['title']; }?>
                              value="<?php echo $value['id']; ?>"><?php echo $value['title']; ?></option>
                            <?php  } ?>
                    </select>
                </div>
                <div id="error_make" style="color: red;"></div>
            </div>

            <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="plate number">Plate Number <span class="required">*</span>
                </label>

                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input type="text"  maxlength="150" id="plate number" title="please add something" name="plate_number" value="" class="form-control col-md-7 col-xs-12">   
                </div>

                <div id="error" style="color: red;"></div>
            </div>

            <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="model_type">Model Type <span class="required">*</span>
                </label>

                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input type="text"  maxlength="150" id="model_type" title="please add something" name="model_type" value="" class="form-control col-md-7 col-xs-12">   
                </div>

                <div id="error" style="color: red;"></div>
            </div>

              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="engine">Engine<span class="required">*</span></label>
                
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <select  class="form-control select_withoutcross" id="engine" name="engine_id">
                    
                        <option  value="">engine size</option>
                    
                            <?php foreach ($eng_list as $key => $value) { ?>
                        <option <?php if(count($eng_list) >0){ echo $eng_list[0]['title']; }?>
                              value="<?php echo $value['id']; ?>"><?php echo $value['title']; ?></option>
                            <?php  } ?>
                    </select>
                </div>
                <div id="error_make" style="color: red;"></div>
            </div>
            <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="year">Body <span class="required">*</span>
                </label>

                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input type="text"  maxlength="150" id="year" title="please add something" name="body" value="" class="form-control col-md-7 col-xs-12">   
                </div>

                <div id="error" style="color: red;"></div>
            </div>
                
            <div class="ln_solid"></div>
                <div class="form-group">
                    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                        <button type="submit" id="send" class="btn btn-success">Submit</button>
                        <a href="<?php echo base_url().'admin/cars/vehicles'; ?>" class="btn btn-primary" type="button">Cancel</a>
                    </div>
                </div>
            </form>
        </div>

    <!-- <script>    
        $("#title").keyup(function(){
            var Text = $(this).val();
            Text = Text.toLowerCase();
            Text = Text.replace(/[^a-zA-Z0-9]+/g,'-');
            $("#slug").val(Text);        
        });



        document.getElementById("send").addEventListener("click", function(event){
            event.preventDefault();
            var re = /^[\w ]+$/;
            if( document.myForm.title.value == "" || document.myForm.title.value.trim() == "" ) {
                document.getElementById("error").innerHTML = "Please provide title!";
                document.myForm.title.focus() ;
                return false;
             }
             else{
                 document.getElementById("error").innerHTML ="";
             }             
             var form=document.getElementsByTagName("form")[0];
             // console.log(form);
             form.submit();
        });
    </script> -->

