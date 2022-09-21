
   <div class="x_title">
        <h2><?php  echo $small_title; ?></h2>
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
        <form method="post" novalidate=""name = "myForm"  id="make_form" action="<?php echo base_url(); ?>admin/cars/<?php if($title == 'Add Vehicle Specification' ){  echo "add_vehicle_specs"; } else { echo "edit_vehicles";}?>" enctype="multipart/form-data" class="form-horizontal form-label-left demo-form23">
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
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="status">Power Option</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                        <select required="" class="form-control select_withoutcross" id="power_option_status" name="power_option_status">
                            <option <?php if(count($cars_list) >0 && $cars_list[0]['status'] == 1){ echo 'selected'; }?> value="1">Yes</option>
                            <option <?php if(count($cars_list) >0 && $cars_list[0]['status'] == 0){ echo 'selected'; }?> value="0">No</option>
                        </select>
                </div>
            </div>
              



            <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="specialization_id">Keys<span class="required">*</span></label>   
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <select name="keys" data-field="keys" data-provides="anomaly.field_type.select" class="custom-select form-control">
                        <option value="">Choose an option...</option>
                        <option value="1">Remote key</option>
                        <option value="2">Manual Key</option>
                    </select>
                </div>
                <div id="error_make" style="color: red;"></div>
            </div>


            <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="specialization_id">Iterior<span class="required">*</span></label>   
                <div class="col-md-6 col-sm-6 col-xs-12">
                   <select name="iterior" data-field="iterior" data-field_name="iterior" data-provides="anomaly.field_type.select" class="custom-select form-control">
                        <option value="">Choose an option...</option>
                        <option value="1">L/S</option>
                        <option value="2">F/S</option>
                        <option value="3">Semi Leather</option>
                    </select>
                </div>
                <div id="error_make" style="color: red;"></div>
            </div>



              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="status">Sun Proof</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                        <select required="" class="form-control select_withoutcross" id="status" name="sun_proof_status">
                            <option <?php if(count($cars_list) >0 && $cars_listcars_list[0]['status'] == 1){ echo 'selected'; }?> value="1">Yes</option>
                            <option <?php if(count($cars_list) >0 && $cars_list[0]['status'] == 0){ echo 'selected'; }?> value="0">No</option>
                        </select>
                </div>
            </div>


            <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="specialization_id">Wheel<span class="required">*</span></label>   
                <div class="col-md-6 col-sm-6 col-xs-12">
                   <select name="wheel" data-field="wheel" data-field_name="wheel" data-provides="anomaly.field_type.select" class="custom-select form-control">

                        <option value="">Choose an option...</option>

                        <option value="1">Alloy Wheels</option>
                        <option value="2">steel wheel</option>
                        <option value="3">wheel Caps</option>
            
                    </select>
                </div>
                <div id="error_make" style="color: red;"></div>
            </div>


            <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="status">Fog Lamp</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                        <select required="" class="form-control select_withoutcross" id="status" name="fog_lamp_status">
                            <option <?php if(count($cars_list) >0 && $cars_list[0]['status'] == 1){ echo 'selected'; }?> value="1">Yes</option>
                            <option <?php if(count($cars_list) >0 && $cars_list[0]['status'] == 0){ echo 'selected'; }?> value="0">No</option>
                        </select>
                </div>
            </div>

            <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="status">Zenon Lights</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                        <select required="" class="form-control select_withoutcross" id="status" name="zenon_light_status">
                            <option <?php if(count($cars_list) >0 && $cars_list[0]['status'] == 1){ echo 'selected'; }?> value="1">Yes</option>
                            <option <?php if(count($cars_list) >0 && $cars_list[0]['status'] == 0){ echo 'selected'; }?> value="0">No</option>
                        </select>
                </div>
            </div>


            <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="status">Cruise Control</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                        <select required="" class="form-control select_withoutcross" id="status" name="cruise_control_status">
                            <option <?php if(count($cars_list) >0 && $cars_list[0]['status'] == 1){ echo 'selected'; }?> value="1">Yes</option>
                            <option <?php if(count($cars_list) >0 && $cars_list[0]['status'] == 0){ echo 'selected'; }?> value="0">No</option>
                        </select>
                </div>
            </div>


            <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="status">Bluetooth</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                        <select required="" class="form-control select_withoutcross" id="status" name="Bluetooth_status">
                            <option <?php if(count($cars_list) >0 && $cars_list[0]['status'] == 1){ echo 'selected'; }?> value="1">Yes</option>
                            <option <?php if(count($cars_list) >0 && $cars_list[0]['status'] == 0){ echo 'selected'; }?> value="0">No</option>
                        </select>
                </div>
            </div>

            <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="status">CD DVD Player</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                        <select required="" class="form-control select_withoutcross" id="status" name="cd_dvd_status">
                            <option <?php if(count($cars_list) >0 && $cars_list[0]['status'] == 1){ echo 'selected'; }?> value="1">Yes</option>
                            <option <?php if(count($cars_list) >0 && $cars_list[0]['status'] == 0){ echo 'selected'; }?> value="0">No</option>
                        </select>
                </div>
            </div>


            <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="status">Rear Camera</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                        <select required="" class="form-control select_withoutcross" id="status" name="rear_camera_status">
                            <option <?php if(count($cars_list) >0 && $cars_list[0]['status'] == 1){ echo 'selected'; }?> value="1">Yes</option>
                            <option <?php if(count($cars_list) >0 && $cars_list[0]['status'] == 0){ echo 'selected'; }?> value="0">No</option>
                        </select>
                </div>
            </div>   


            <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="status">Air Bag</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                        <select required="" class="form-control select_withoutcross" id="status" name="air_bag_status">
                            <option <?php if(count($cars_list) >0 && $cars_list[0]['status'] == 1){ echo 'selected'; }?> value="1">Yes</option>
                            <option <?php if(count($cars_list) >0 && $cars_list[0]['status'] == 0){ echo 'selected'; }?> value="0">No</option>
                        </select>
                </div>
            </div> 


            <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="status">Parking Sensor</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                        <select required="" class="form-control select_withoutcross" id="status" name="parking_sensor_status">
                            <option <?php if(count($cars_list) >0 && $cars_list[0]['status'] == 1){ echo 'selected'; }?> value="1">Yes</option>
                            <option <?php if(count($cars_list) >0 && $cars_list[0]['status'] == 0){ echo 'selected'; }?> value="0">No</option>
                        </select>
                </div>
            </div>

            <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="status">Diffrentiial Lock</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                        <select required="" class="form-control select_withoutcross" id="status" name="differential_lock_status">
                            <option <?php if(count($cars_list) >0 && $cars_list[0]['status'] == 1){ echo 'selected'; }?> value="1">Yes</option>
                            <option <?php if(count($cars_list) >0 && $cars_list[0]['status'] == 0){ echo 'selected'; }?> value="0">No</option>
                        </select>
                </div>
            </div>
                
            < <div class="ln_solid"></div>
            <div class="form-group">
                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                    <?php if($title == ' Edit Vehicle Specs' ){  ?>
                    <button type="submit" id="send" class="btn btn-success">update</button>
                    <a href="<?php echo base_url().'admin/cars/vehicle_specs'; ?>" class="btn btn-primary" type="button">Cancel</a>
                    <?php } else{ ?>

                    <button type="submit" id="send" class="btn btn-success">Submit</button>
                    <a href="<?php echo base_url().'admin/cars/vehicle_specs'; ?>" class="btn btn-primary" type="button">Cancel</a>
                     <?php }?>
                </div>
            </div>
            </form>
        </div>

    <script>    

           $('#demo-form2').parsley().on('field:validated', function() {
            var ok = $('.parsley-error').length === 0;
        }) .on('form:submit', function() {
        return false; // Don't submit form for this demo
    });
        // $("#title").keyup(function(){
            // var Text = $(this).val();
            // Text = Text.toLowerCase();
            // Text = Text.replace(/[^a-zA-Z0-9]+/g,'-');
            // $("#slug").val(Text);        
        // });



        // document.getElementById("send").addEventListener("click", function(event){
            // event.preventDefault();
            // var re = /^[\w ]+$/;
            // if( document.myForm.title.value == "" || document.myForm.title.value.trim() == "" ) {
        //         document.getElementById("error").innerHTML = "Please provide title!";
        //         document.myForm.title.focus() ;
        //         return false;
        //      }
        //      else{
        //          document.getElementById("error").innerHTML ="";
        //      }             
        //      var form=document.getElementsByTagName("form")[0];
        //      // console.log(form);
        //      form.submit();
        // });
    </script>

