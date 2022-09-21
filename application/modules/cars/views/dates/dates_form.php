
<input type="hidden" id="base_url" value="<?php echo base_url(); ?>">

<div class="clearfix"></div>

<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>
                <?php  echo $small_title; ?> Dates</h2>

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


            <?php 
            if(isset($dates_list[0]['times']))
            {
                $times_dates=json_decode($dates_list[0]['times']);
            }     
            
            ?>
         

           
          <form method="post" novalidate="" id="demo-form2" action="" enctype="multipart/form-data" class="form-horizontal form-label-left">
            <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
               <?php if(isset($dates_list) && !empty($dates_list)){ ?>
                <input type="hidden" name="id" value="<?php echo $this->uri->segment(3); ?>">
            <?php } ?>


             <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="description">Location <span class="required">*</span>
                </label>


                <div class="col-md-6 col-sm-6 col-xs-12"><span class="errror"></span>
                    <select required class="form-control select_withoutcross" title="aaa" id="location_id" name="location_id">
                    
                        <option  value="">Select location</option>
                    
                        <?php foreach ($location_list as $key => $value) { ?>
                        <option 
                        <?php if(count($dates_list) >0){
                            if($dates_list[0]['location_id'] == $value['id']){ echo "selected"; }
                        }?>
                            value="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></option>
                            <?php  } ?>
                    </select>
                </div>
            </div>
        
                
            <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="exp date">From Date<span class="required">*</span>
                </label>

                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input type="date" required maxlength="150" id="exp_date" title=" exp date" name="from_date" value="<?php 
                    if(count($dates_list) >0){ echo $dates_list[0]['from_date']; }?>" class="form-control col-md-7 col-xs-12">
                </div>
            </div>

             <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="exp date">To Date<span class="required">*</span>
                </label>

                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input type="date" required maxlength="150" id="to_date" title=" to_date" name="to_date" value="<?php 
                    if(count($dates_list) >0){ echo $dates_list[0]['to_date']; }?>" class="form-control col-md-7 col-xs-12">
                </div>
            </div>
                
               
            <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="exp date">Times
                </label>

                <div class="custom-inputs-stacked">
                    <div class=" col-md-6 col-sm-6 col-xs-12">
            
                    <label class=" custom-checkbox" style="display: block;">
                        <input type="checkbox" value="8:00 AM" name="times[] "<?php if(count($dates_list) >0){
                            if(in_array("8:00 AM", $times_dates)){echo "checked";}
                        }?>>

                        <span class=""></span>

                        8:00 AM
                    </label>
                
            
                    <label class=" custom-checkbox" style="display: block;">
                        <input type="checkbox" value="8:30 AM" name="times[]" <?php if(count($dates_list) >0){
                            if(in_array("8:30 AM", $times_dates)){echo "checked";}
                        }?>>

                        <span class=""></span>

                        8:30 AM
                    </label>
                
            
                    <label class=" custom-checkbox" style="display: block;">
                        <input type="checkbox" value="9:00 AM" name="times[]" <?php if(count($dates_list) >0){
                            if(in_array("9:00 AM", $times_dates)){echo "checked";}
                        }?>>

                        <span class=""></span>

                        9:00 AM
                    </label>
                
            
                    <label class=" custom-checkbox" style="display: block;">
                        <input type="checkbox" value="9:30 AM" name="times[]" <?php if(count($dates_list) >0){
                            if(in_array("9:30 AM", $times_dates)){echo "checked";}
                        }?>>

                        <span class=""></span>

                        9:30 AM
                    </label>
                
            
                    <label class=" custom-checkbox" style="display: block;">
                        <input type="checkbox" value="10:00 AM" name="times[]" <?php if(count($dates_list) >0){
                            if(in_array("10:00 AM", $times_dates)){echo "checked";}
                        }?>>

                        <span class=""></span>

                        10:00 AM
                    </label>
                
            
                    <label class=" custom-checkbox" style="display: block;">
                        <input type="checkbox" value="10:30 AM" name="times[]" <?php if(count($dates_list) >0){
                            if(in_array("10:30 AM", $times_dates)){echo "checked";}
                        }?>>

                        <span class=""></span>

                        10:30 AM
                    </label>
                
            
                    <label class=" custom-checkbox" style="display: block;">
                        <input type="checkbox" value="11:00 AM" name="times[]" <?php if(count($dates_list) >0){
                            if(in_array("11:00 AM", $times_dates)){echo "checked";}
                        }?>>

                        <span class=""></span>

                        11:00 AM
                    </label>
                
            
                    <label class=" custom-checkbox" style="display: block;">
                        <input type="checkbox" value="11:30 AM" name="times[]" <?php if(count($dates_list) >0){
                            if(in_array("11:30 AM", $times_dates)){echo "checked";}
                        }?>>

                        <span class=""></span>

                        11:30 AM
                    </label>
                
            
                    <label class=" custom-checkbox" style="display: block;">
                        <input type="checkbox" value="12:00 AM" name="times[]" <?php if(count($dates_list) >0){
                            if(in_array("12:00 AM", $times_dates)){echo "checked";}
                        }?>>

                        <span class=""></span>

                        12:00 AM
                    </label>
                
            
                    <label class=" custom-checkbox" style="display: block;">
                        <input type="checkbox" value="12:30 PM" name="times[]" <?php if(count($dates_list) >0){
                            if(in_array("12:30 PM", $times_dates)){echo "checked";}
                        }?>>

                        <span class=""></span>

                        12:30 PM
                    </label>
                
            
                    <label class=" custom-checkbox" style="display: block;">
                        <input type="checkbox" value="1:00 PM" name="times[]" <?php if(count($dates_list) >0){
                            if(in_array("1:00 PM", $times_dates)){echo "checked";}
                        }?>>

                        <span class=""></span>

                        1:00 PM
                    </label>
                
            
                    <label class=" custom-checkbox" style="display: block;">
                        <input type="checkbox" value="1:30 PM" name="times[]" <?php if(count($dates_list) >0){
                            if(in_array("1:30 PM", $times_dates)){echo "checked";}
                        }?>>

                        <span class=""></span>

                        1:30 PM
                    </label>
                
            
                    <label class=" custom-checkbox" style="display: block;">
                        <input type="checkbox" value="2:00 PM" name="times[]" <?php if(count($dates_list) >0){
                            if(in_array("2:00 PM", $times_dates)){echo "checked";}
                        }?>>

                        <span class=""></span>

                        2:00 PM
                    </label>
                
            
                    <label class=" custom-checkbox" style="display: block;">
                        <input type="checkbox" value="2:30 PM" name="times[]" <?php if(count($dates_list) >0){
                            if(in_array("2:30 PM", $times_dates)){echo "checked";}
                        }?>>

                        <span class=""></span>

                        2:30 PM
                    </label>
                
            
                    <label class=" custom-checkbox" style="display: block;">
                        <input type="checkbox" value="3:00 PM" name="times[]" <?php if(count($dates_list) >0){
                            if(in_array("3:00 PM", $times_dates)){echo "checked";}
                        }?>>

                        <span class=""></span>

                        3:00 PM
                    </label>
                
            
                    <label class=" custom-checkbox" style="display: block;">
                        <input type="checkbox" value="3:30 PM" name="times[]" <?php if(count($dates_list) >0){
                            if(in_array("3:30 PM", $times_dates)){echo "checked";}
                        }?>>

                        <span class=""></span>

                        3:30 PM
                    </label>
                
            
                    <label class=" custom-checkbox" style="display: block;">
                        <input type="checkbox" value="4:00 PM" name="times[]" <?php if(count($dates_list) >0){
                            if(in_array("4:00 PM", $times_dates)){echo "checked";}
                        }?>>

                        <span class=""></span>

                        4:00 PM
                    </label>
                
            
                    <label class=" custom-checkbox" style="display: block;">
                        <input type="checkbox" value="4:30 PM" name="times[]" <?php if(count($dates_list) >0){
                            if(in_array("4:30 PM", $times_dates)){echo "checked";}
                        }?>>

                        <span class=""></span>

                        4:30 PM
                    </label>
                
            
                    <label class=" custom-checkbox" style="display: block;">
                        <input type="checkbox" value="5:00 PM" name="times[]" <?php if(count($dates_list) >0){
                            if(in_array("5:00 PM", $times_dates)){echo "checked";}
                        }?>>

                        <span class=""></span>

                        5:00 PM
                    </label>
                
            
                    <label class=" custom-checkbox" style="display: block;">
                        <input type="checkbox" value="5:30 PM" name="times[]"<?php if(count($dates_list) >0){
                            if(in_array("5:30 PM", $times_dates)){echo "checked";}
                        }?>>

                        <span class=""></span>

                        5:30 PM
                    </label>
                
            
                    <label class=" custom-checkbox" style="display: block;">
                        <input type="checkbox" value="6:00 PM" name="times[]" <?php if(count($dates_list) >0){
                            if(in_array("6:00 PM", $times_dates)){echo "checked";}
                        }?>>

                        <span class=""></span>

                        6:00 PM
                    </label>
                
            
                    <label class=" custom-checkbox" style="display: block;">
                        <input type="checkbox" value="6:30 PM" name="times[]" <?php if(count($dates_list) >0){
                            if(in_array("6:30 PM", $times_dates)){echo "checked";}
                        }?>>

                        <span class=""></span>

                        6:30 PM
                    </label>
                
            
                    <label class=" custom-checkbox" style="display: block;">
                        <input type="checkbox" value="7:00 PM" name="times[]" <?php if(count($dates_list) >0){
                            if(in_array("7:00 PM", $times_dates)){echo "checked";}
                        }?>>

                        <span class=""></span>

                        7:00 PM
                    </label>
                
            
                    <label class=" custom-checkbox" style="display: block;">
                        <input type="checkbox" value="7:30 PM" name="times[]" <?php if(count($dates_list) >0){
                            if(in_array("7:30 PM", $times_dates)){echo "checked";}
                        }?>>

                        <span class=""></span>

                        7:30 PM
                    </label>
                
            
                    <label class=" custom-checkbox" style="display: block;">
                        <input type="checkbox" value="8:00 PM" name="times[]" <?php if(count($dates_list) >0){
                            if(in_array("8:00 PM", $times_dates)){echo "checked";}
                        }?>>

                        <span class=""></span>

                        8:00 PM
                    </label>
                
            
                    <label class=" custom-checkbox" style="display: block;">
                        <input type="checkbox" value="8:30 PM" name="times[]" <?php if(count($dates_list) >0){
                            if(in_array("8:30 PM", $times_dates)){echo "checked";}
                        }?>>

                        <span class=""></span>

                        8:30 PM
                    </label>
                
            
                    <label class=" custom-checkbox" style="display: block;">
                        <input type="checkbox" value="9:00 PM" name="times[]" <?php if(count($dates_list) >0){
                            if(in_array("9:00 PM", $times_dates)){echo "checked";}
                        }?>>

                        <span class=""></span>

                        9:00 PM
                    </label>
                </div>
            
        </div>
    </div>



                <div class="form-group">
                    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                        <?php if($small_title == 'Edit' ){  ?>
                        <button type="submit" id="send" class="btn btn-success">update</button>
                        <a href="<?php echo base_url().'cars/dates'; ?>" class="btn btn-primary" type="button">Cancel</a>
                        <?php } else{ ?>

                        <button id="send" type="submit" class="btn btn-success">Submit</button>
                        <a href="<?php echo base_url().'cars/dates'; ?>" class="btn btn-primary" type="button">Cancel</a>
                         <?php }?>
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
                            window.location = url + 'cars/dates';
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