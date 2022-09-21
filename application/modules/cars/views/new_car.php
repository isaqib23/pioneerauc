
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
        <form method="post" novalidate="" name = "myForm"  id="demo-form2" action="<?php echo base_url(); ?>admin/cars/<?php if($title == 'Edit' ){  echo "edit_car"; } else { echo "save_car";}?>" enctype="multipart/form-data" class="form-horizontal form-label-left demo-form23">
            <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
            <?php if($title == 'Edit' ){  ?>
                <input type="hidden" name="id" value="<?php echo $this->uri->segment(3); ?>">
            <?php } ?>
                     
            <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Title <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input type="text"  maxlength="150" id="title" required title="please add something" name="title" value="<?php 
                    if(count($car_list) >0){ echo $car_list[0]['title']; }?>" class="form-control col-md-7 col-xs-12">   
                </div>
                <div id="error" style="color: red;"></div>
            </div>


            <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="slug">Slug <span class="required">*</span>
                </label>

                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input type="text"  maxlength="150" required id="slug" title="please add something" name="slug" value="<?php 
                    if(count($car_list) >0){ echo $car_list[0]['slug']; }?>" class="form-control col-md-7 col-xs-12">   
                </div>
                <div id="error" style="color: red;"></div>
            </div>


            <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="description">Description <span class="required"></span>
                </label>

                <div class="col-md-6 col-sm-6 col-xs-12">
                    <textarea type="text" rows="5"  maxlength="150" id="Description" title="please add something" name="description" value="<?php 
                    if(count($car_list) >0){ echo $car_list[0]['description']; }?>" class="form-control col-md-7 col-xs-12">   
                    </textarea>
                </div>
                <div id="error" style="color: red;"></div>
            </div>


            <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Images <span class="required">*</span>
                </label>

                <div class="col-md-6 col-sm-6 col-xs-12">
                    <button class="btn btn-info btn-xs"><input  type="file" maxlength="150"  name="image" value="Images"> </button> 
                </div>

                <div id="error" style="color: red;"></div>
            </div>

             <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="specialization_id">Make      <span class="required">*</span></label>
                
                <div class="col-md-6 col-sm-6 col-xs-12"><span class="errror">
                    <select required class="form-control select_withoutcross" title="aaa" id="make_id" name="valuation_make_id">
                    
                        <option  value="">Select Make</option>
                    
                        <?php foreach ($makes_list as $key => $value) { ?>
                        <option 
                        <?php if(count($car_list) >0){
                            if($car_list[0]['valuation_make_id'] == $value['id']){ echo 'selected';}
                        }?>
                            value="<?php echo $value['id']; ?>"><?php echo $value['title']; ?></option>
                            <?php  } ?>
                    </select>
                </div>
                </span>
            </div>

     
            <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="valuation_model_id">Model<span class="required">*</span></label>
                <div class="col-md-6 col-sm-6 col-xs-12">


                    <select required name="valuation_model_id" id="valuation_model_id" class="form-control col-md-7 col-xs-12"></select>
               </div>

                <div id="error_engine_size"></div>
            </div>
              

            <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="year">Year <span class="required">*</span>
                </label>

                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input type="text" required  maxlength="150" id="year" title="please add something" name="year" value="<?php 
                    if(count($car_list) >0){ echo $car_list[0]['year']; }?>" class="form-control col-md-7 col-xs-12">   
                </div>

                <div id="error" style="color: red;"></div>
            </div>

              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="specialization_id">Transmission<span class="required">*</span></label>
                
                <div class="col-md-6 col-sm-6 col-xs-12">
                     <select required name="transmission" data-field="transmission" name="transmission" data-provides="anomaly.field_type.select" class="custom-select form-control">

                        <option value="">Select Transmission</option>
                        <option value="Manual">Manual</option>
                        <option value="Automatic">Automatic</option>
            
                    </select>
                </div>
                <div id="error_make" style="color: red;"></div>
            </div>

             <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="specialization_id">Specs<span class="required">*</span></label>   
                <div class="col-md-6 col-sm-6 col-xs-12">
                     <select required name="specs" data-field="specs" name="specs" data-provides="anomaly.field_type.select" class="custom-select form-control">

                        <option value="">Select Specs</option>
                        <option value="GCC">GCC</option>
                        <option value="Non GCC">Non GCC</option>
                        <option value="I don't knoe">I don't know</option>
            
                    </select>
                </div>
                <div id="error_make" style="color: red;"></div>
            </div>

              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="specialization_id">Engine Cylinder<span class="required">*</span></label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                     <select required  data-field="cylinder" name="cylinder" data-provides="anomaly.field_type.select" class="custom-select form-control">

                            <option value="">Select Engine Cylinders</option>

                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                            <option value="11">11</option>
                            <option value="12">12</option>
                            <option value="13">13</option>
                            <option value="14">14</option>
            
                        </select>
                </div>
                <div id="error_make" style="color: red;"></div>
            </div>

            <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="specialization_id">Millage<span class="required">*</span></label>
                <div class="col-md-6 col-sm-6 col-xs-12"><span class="errror">
                    <select  class="form-control select_withoutcross" title="aaa" id="milleage_id" name="mileage_from">  
                        <option required value="">Select Milleage</option>
                    
                        <?php foreach ($     as $key => $value) { ?>
                        <option 
                        <?php if(count($milleage_list) >0){
                            if($milleage_list[0]['milleage'] == $value['id']){ echo 'selected';}
                        }?>
                            value="<?php echo $value['milleage']; ?>"><?php echo $value['milleage']; ?></option>
                            <?php  } ?>
                    </select>
                </div>
            </div>
                
                



             <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="meter reading">Meter Reading(Km) <span class="required">*</span>
                </label>

                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input required type="text"  maxlength="150" id="meter reading" title=" meter reading" name="meter_reading" value="<?php 
                    if(count($car_list) >0){ echo $car_list[0]['meter_reading']; }?>" class="form-control col-md-7 col-xs-12">   
                </div>

                <div id="error" style="color: red;"></div>
            </div>
              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="specialization_id">Option<span class="required">*</span></label>
                
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <select  required class="form-control select_withoutcross" title="aaa" id="option_id" name="title">  
                        <option  value="">Select Option</option>
                    
                        <?php foreach ($option_list as $key => $value) { ?>
                        <option 
                        <?php if(count($option_list) >0){
                            if($option_list[0]['title'] == $value['id']){ echo 'selected';}
                        }?>
                            value="<?php echo $value['title']; ?>"><?php echo $value['title']; ?></option>
                            <?php  } ?>
                    </select>
                </div>
                <div id="error_make" style="color: red;"></div>
            </div>


           <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="specialization_id">Paint<span class="required">*</span></label>
            
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <select required class="form-control select_withoutcross" id="models_id" name="paint">
                    
                        <option value="<?php if(count($car_list) >0){
                             echo $car_list[0]['paint'];
                        }?>" >Select paint</option>
                        <option value="Original">Original</option>
                        <option value="Partial">Partial</option>
                        <option value="Total Repaint">Total Repaint</option>
                    </select>
                </div>
                <div id="error_make" style="color: red;"></div>
            </div>
             
             <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="specialization_id">Vehicle History<span class="required">*</span></label>
                
                <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="checkbox">
                            <input type="checkbox"  id="title" title="please add something" name="accidental_report" value="accidental_report" >Accidental Reported</label>
                        </div>

                        <div class="checkbox">
                            <label><input type="checkbox" name="personal_car" value="Personal Car">Personal Car </label>
                        </div>

                        <div class="checkbox">
                            <label><input type="checkbox" name="business_car" value="Business Car">Business Car </label>
                        </div>
                </div>
                <div id="error_make" style="color: red;"></div>
                </div>
            


            <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="specialization_id">Accident History<span class="required">*</span></label>
                
                <div class="col-md-6 col-sm-6 col-xs-12">
                 <select  class="form-control select_withoutcross" id="models_id" name="accident_history">
                        <option  value="">Select one</option>
                      <?php foreach ($car_list as $key => $value) { ?>
                        <option <?php if(count($makes_list) >0){ echo $makes_list[0]['title']; }?>
                              value="<?php echo $value['title']; ?>"><?php echo $value['title']; ?></option>
                            <?php  } ?>
                    </select>
                </div>
                <div id="error_make" style="color: red;"></div>
            </div>


             <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="specialization_id">Service History<span class="required">*</span></label>
            
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <select required class="form-control select_withoutcross" id="models_id" name="service_history">
                    
                        <option  value="">Select Service History</option>
                        <option value="No Service History">No Service History</option>
                        <option value="Rutine Servicing">Rutine Servicing</option>
                        <option value="Regular Check">Regular Check</option>
                    </select>
                </div>
                <div id="error_make" style="color: red;"></div>
            </div>


            <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="specialization_id">Body Type<span class="required">*</span></label>
                
                <div class="col-md-6 col-sm-6 col-xs-12">
                   <select required name="body_type" data-field="body_type" data-field_name="body_type" data-provides="anomaly.field_type.select" class="custom-select form-control">

                        <option value="">Select Body Type</option>
                        <option value="Sedan">Sedan</option>
                        <option value="Coupe">Coupe</option>
                        <option value="Crossover">Crossover</option>
                        <option value="Hard Top Convertible">Hard Top Convertible</option>
                        <option value="Hatchback">Hatchback</option>
                        <option value="Pick Up Truck">Pick Up Truck</option>
                        <option value="Soft Top Convertible">Soft Top Convertible</option>
                        <option value="Sports Car">Sports Car</option>
                        <option value="SUV">SUV</option>
                        <option value="Utility Truck">Utility Truck</option>
                        <option value="Van">Van</option>
                        <option value="Wagon">Wagon</option>
        
                    </select>
                </div>
                <div id="error_make" style="color: red;"></div>
            </div>

            <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="specialization_id">Drive<span class="required">*</span></label>
            

                <div class="col-md-6 col-sm-6 col-xs-12">
                    <select required class="form-control select_withoutcross" id="models_id" name="drive">
                    
                        <option  value="">Select Drive</option>
                        <option value="All Wheel Drive">All Wheel Drive</option>
                        <option value="Front Wheel Drive">Front Wheel Drive</option>
                        <option value="Rear Wheel Drivek">Rear Wheel Drivek</option>
                    </select>
                </div>
                <div id="error_make" style="color: red;"></div>
            </div>


            <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="specialization_id">Color<span class="required">*</span></label>
                
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <select required name="color" data-field="color" data-field_name="color" data-provides="anomaly.field_type.select" class="custom-select form-control">

                                <option value="">Select Color</option>
                                <option value="White">White</option>
                                <option value="Silver">Silver</option>
                                <option value="Black">Black</option>
                                <option value="Grey">Grey</option>
                                <option value="Blue">Blue</option>
                                <option value="Red">Red</option>
                                <option value="Brown">Brown</option>
                                <option value="Green">Green</option>
                                <option value="Other">Other</option>
            
                    </select>
                </div>
                <div id="error_make" style="color: red;"></div>
            </div>
               <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="specialization_id">Fuel Type<span class="required">*</span></label>
                
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <select required name="fuel_type" data-field="fuel_type" data-field_name="fuel_type" data-provides="anomaly.field_type.select" class="custom-select form-control">

                                <option value="">Select Fuel Type</option>
                                <option value="Petrol">Petrol</option>
                                <option value="Diesel">Diesel</option>
                                <option value="CNG">CNG</option>
                                <option value="LPG">LPG</option>
                                <option value="Electric">Electric</option>
                        </select>
                </div>
                <div id="error_make" style="color: red;"></div>
            </div>
             <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="car number">Car Number <span class="required"></span>
                </label>

                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input type="text"  maxlength="150" id="paint" title=" car number" name="car_number" value="" class="form-control col-md-7 col-xs-12">   
                </div>

                <div id="error" style="color: red;"></div>
            </div>

             <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Engine Size <span class="required"></span>
                </label>

                <div class="col-md-6 col-sm-6 col-xs-12">
                    
                    <select  id="valuation_engine_size" name="engine_size_id" class="form-control col-md-7 col-xs-12"></select>
                    <!--<input type="text"  maxlength="150" class="form-control col-md-7 col-xs-12" id="engine_size"  name="engine_size_id" value="<?php 
                    if(count($price_list) >0){ echo $price_list[0]['engine_size_title']; }?>">-->
                </div>
                <div id="error" style="color: red;"></div>
            </div>

            <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="specialization_id">Chassi Damage<span class="required"></span></label>
                
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <select  class="form-control select_withoutcross" id="models_id" name="chassi_damage">
                        <option>YES</option>
                        <option>No</option>
                    </select>
                </div>
                <div id="error_make" style="color: red;"></div>
            </div>

              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="specialization_id">Chassi Repaired<span class="required"></span></label>
                
                <div class="col-md-6 col-sm-6 col-xs-12">
                <select  class="form-control select_withoutcross" id="models_id" name="chassi_repaired">
                        <option value="1">YES</option>
                        <option value="0">No</option>
                           
                    </select>
                </div>
                <div id="error_make" style="color: red;"></div>
            </div>

              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="specialization_id">Register In<span class="required">*</span></label>
                
                <div class="col-md-6 col-sm-6 col-xs-12">
               <select name="registered_in" data-field="registered_in" name="registered_in" data-provides="anomaly.field_type.country" class="custom-select form-control">

                                <option value="">Select Registered In</option>W
                                <option value="AE">United Arab Emirates</option>
                                <option value="AD">Andorra</option>
                                <option value="AF">Afghanistan</option>
                                <option value="AG">Antigua and Barbuda</option>
                                <option value="AI">Anguilla</option>
                                <option value="AL">Albania</option>
                                <option value="AM">Armenia</option>
                                <option value="AN">Netherlands Antilles</option>
                                <option value="AO">Angola</option>
                                <option value="AQ">Antarctica</option>
                                <option value="AR">Argentina</option>
                                <option value="AS">American Samoa</option>
                                <option value="AT">Austria</option>
                                <option value="AU">Australia</option>
                                <option value="AW">Aruba</option>
                                <option value="AX">Aland Islands</option>
                                <option value="AZ">Azerbaijan</option>
                                <option value="BA">Bosnia and Herzegovina</option>
                                <option value="BB">Barbados</option>
                                <option value="BD">Bangladesh</option>
                                <option value="BE">Belgium</option>
                                <option value="BF">Burkina Faso</option>
                                <option value="BG">Bulgaria</option>
                                <option value="BH">Bahrain</option>
                                <option value="BI">Burundi</option>
                                <option value="BJ">Benin</option>
                                <option value="BL">Saint Barthélemy</option>
                                <option value="BM">Bermuda</option>
                                <option value="BN">Brunei</option>
                                <option value="BO">Bolivia</option>
                                <option value="BR">Brazil</option>
                                <option value="BS">Bahamas</option>
                                <option value="BT">Bhutan</option>
                                <option value="BV">Bouvet Island</option>
                                <option value="BW">Botswana</option>
                                <option value="BY">Belarus</option>
                                <option value="BZ">Belize</option>
                                <option value="CA">Canada</option>
                                <option value="CC">Cocos (Keeling) Islands</option>
                                <option value="CD">Congo (Kinshasa)</option>
                                <option value="CF">Central African Republic</option>
                                <option value="CG">Congo (Brazzaville)</option>
                                <option value="CH">Switzerland</option>
                                <option value="CI">Ivory Coast</option>
                                <option value="CK">Cook Islands</option>
                                <option value="CL">Chile</option>
                                <option value="CM">Cameroon</option>
                                <option value="CN">China</option>
                                <option value="CO">Colombia</option>
                                <option value="CR">Costa Rica</option>
                                <option value="CU">Cuba</option>
                                <option value="CV">Cape Verde</option>
                                <option value="CW">Curaçao</option>
                                <option value="CX">Christmas Island</option>
                                <option value="CY">Cyprus</option>
                                <option value="CZ">Czech Republic</option>
                                <option value="DE">Germany</option>
                                <option value="DJ">Djibouti</option>
                                <option value="DK">Denmark</option>
                                <option value="DM">Dominica</option>
                                <option value="DO">Dominican Republic</option>
                                <option value="DZ">Algeria</option>
                                <option value="EC">Ecuador</option>
                                <option value="EE">Estonia</option>
                                <option value="EG">Egypt</option>
                                <option value="EH">Western Sahara</option>
                                <option value="ER">Eritrea</option>
                                <option value="ES">Spain</option>
                                <option value="ET">Ethiopia</option>
                                <option value="FI">Finland</option>
                                <option value="FJ">Fiji</option>
                                <option value="FK">Falkland Islands</option>
                                <option value="FM">Micronesia</option>
                                <option value="FO">Faroe Islands</option>
                                <option value="FR">France</option>
                                <option value="GA">Gabon</option>
                                <option value="GB">United Kingdom</option>
                                <option value="GD">Grenada</option>
                                <option value="GE">Georgia</option>
                                <option value="GF">French Guiana</option>
                                <option value="GG">Guernsey</option>
                                <option value="GH">Ghana</option>
                                <option value="GI">Gibraltar</option>
                                <option value="GL">Greenland</option>
                                <option value="GM">Gambia</option>
                                <option value="GN">Guinea</option>
                                <option value="GP">Guadeloupe</option>
                                <option value="GQ">Equatorial Guinea</option>
                                <option value="GR">Greece</option>
                                <option value="GS">South Georgia and the South Sandwich Islands</option>
                                <option value="GT">Guatemala</option>
                                <option value="GU">Guam</option>
                                <option value="GW">Guinea-Bissau</option>
                                <option value="GY">Guyana</option>
                                <option value="HK">Hong Kong S.A.R., China</option>
                                <option value="HM">Heard Island and McDonald Islands</option>
                                <option value="HN">Honduras</option>
                                <option value="HR">Croatia</option>
                                <option value="HT">Haiti</option>
                                <option value="HU">Hungary</option>
                                <option value="ID">Indonesia</option>
                                <option value="IE">Ireland</option>
                                <option value="IL">Israel</option>
                                <option value="IM">Isle of Man</option>
                                <option value="IN">India</option>
                                <option value="IO">British Indian Ocean Territory</option>
                                <option value="IQ">Iraq</option>
                                <option value="IR">Iran</option>
                                <option value="IS">Iceland</option>
                                <option value="IT">Italy</option>
                                <option value="JE">Jersey</option>
                                <option value="JM">Jamaica</option>
                                <option value="JO">Jordan</option>
                                <option value="JP">Japan</option>
                                <option value="KE">Kenya</option>
                                <option value="KG">Kyrgyzstan</option>
                                <option value="KH">Cambodia</option>
                                <option value="KI">Kiribati</option>
                                <option value="KM">Comoros</option>
                                <option value="KN">Saint Kitts and Nevis</option>
                                <option value="KP">North Korea</option>
                                <option value="KR">South Korea</option>
                                <option value="KW">Kuwait</option>
                                <option value="KY">Cayman Islands</option>
                                <option value="KZ">Kazakhstan</option>
                                <option value="LA">Laos</option>
                                <option value="LB">Lebanon</option>
                                <option value="LC">Saint Lucia</option>
                                <option value="LI">Liechtenstein</option>
                                <option value="LK">Sri Lanka</option>
                                <option value="LR">Liberia</option>
                                <option value="LS">Lesotho</option>
                                <option value="LT">Lithuania</option>
                                <option value="LU">Luxembourg</option>
                                <option value="LV">Latvia</option>
                                <option value="LY">Libya</option>
                                <option value="MA">Morocco</option>
                                <option value="MC">Monaco</option>
                                <option value="MD">Moldova</option>
                                <option value="ME">Montenegro</option>
                                <option value="MF">Saint Martin (French part)</option>
                                <option value="MG">Madagascar</option>
                                <option value="MH">Marshall Islands</option>
                                <option value="MK">Macedonia</option>
                                <option value="ML">Mali</option>
                                <option value="MM">Myanmar</option>
                                <option value="MN">Mongolia</option>
                                <option value="MO">Macao S.A.R., China</option>
                                <option value="MP">Northern Mariana Islands</option>
                                <option value="MQ">Martinique</option>
                                <option value="MR">Mauritania</option>
                                <option value="MS">Montserrat</option>
                                <option value="MT">Malta</option>
                                <option value="MU">Mauritius</option>
                                <option value="MV">Maldives</option>
                                <option value="MW">Malawi</option>
                                <option value="MX">Mexico</option>
                                <option value="MY">Malaysia</option>
                                <option value="MZ">Mozambique</option>
                                <option value="NA">Namibia</option>
                                <option value="NC">New Caledonia</option>
                                <option value="NE">Niger</option>
                                <option value="NF">Norfolk Island</option>
                                <option value="NG">Nigeria</option>
                                <option value="NI">Nicaragua</option>
                                <option value="NL">Netherlands</option>
                                <option value="NO">Norway</option>
                                <option value="NP">Nepal</option>
                                <option value="NR">Nauru</option>
                                <option value="NU">Niue</option>
                                <option value="NZ">New Zealand</option>
                                <option value="OM">Oman</option>
                                <option value="PA">Panama</option>
                                <option value="PE">Peru</option>
                                <option value="PF">French Polynesia</option>
                                <option value="PG">Papua New Guinea</option>
                                <option value="PH">Philippines</option>
                                <option value="PK">Pakistan</option>
                                <option value="PL">Poland</option>
                                <option value="PM">Saint Pierre and Miquelon</option>
                                <option value="PN">Pitcairn</option>
                                <option value="PR">Puerto Rico</option>
                                <option value="PS">Palestinian Territory</option>
                                <option value="PT">Portugal</option>
                                <option value="PW">Palau</option>
                                <option value="PY">Paraguay</option>
                                <option value="QA">Qatar</option>
                                <option value="RE">Reunion</option>
                                <option value="RO">Romania</option>
                                <option value="RS">Serbia</option>
                                <option value="RU">Russia</option>
                                <option value="RW">Rwanda</option>
                                <option value="SA">Saudi Arabia</option>
                                <option value="SB">Solomon Islands</option>
                                <option value="SC">Seychelles</option>
                                <option value="SD">Sudan</option>
                                <option value="SE">Sweden</option>
                                <option value="SG">Singapore</option>
                                <option value="SH">Saint Helena</option>
                                <option value="SI">Slovenia</option>
                                <option value="SJ">Svalbard and Jan Mayen</option>
                                <option value="SK">Slovakia</option>
                                <option value="SL">Sierra Leone</option>
                                <option value="SM">San Marino</option>
                                <option value="SN">Senegal</option>
                                <option value="SO">Somalia</option>
                                <option value="SR">Suriname</option>
                                <option value="ST">Sao Tome and Principe</option>
                                <option value="SV">El Salvador</option>
                                <option value="SY">Syria</option>
                                <option value="SZ">Swaziland</option>
                                <option value="TC">Turks and Caicos Islands</option>
                                <option value="TD">Chad</option>
                                <option value="TF">French Southern Territories</option>
                                <option value="TG">Togo</option>
                                <option value="TH">Thailand</option>
                                <option value="TJ">Tajikistan</option>
                                <option value="TK">Tokelau</option>
                                <option value="TL">Timor-Leste</option>
                                <option value="TM">Turkmenistan</option>
                                <option value="TN">Tunisia</option>
                                <option value="TO">Tonga</option>
                                <option value="TR">Turkey</option>
                                <option value="TT">Trinidad and Tobago</option>
                                <option value="TV">Tuvalu</option>
                                <option value="TW">Taiwan</option>
                                <option value="TZ">Tanzania</option>
                                <option value="UA">Ukraine</option>
                                <option value="UG">Uganda</option>
                                <option value="UM">United States Minor Outlying Islands</option>
                                <option value="US">United States</option>
                                <option value="UY">Uruguay</option>
                                <option value="UZ">Uzbekistan</option>
                                <option value="VA">Vatican</option>
                                <option value="VC">Saint Vincent and the Grenadines</option>
                                <option value="VE">Venezuela</option>
                                <option value="VG">British Virgin Islands</option>
                                <option value="VI">U.S. Virgin Islands</option>
                                <option value="VN">Vietnam</option>
                                <option value="VU">Vanuatu</option>
                                <option value="WF">Wallis and Futuna</option>
                                <option value="WS">Samoa</option>
                                <option value="YE">Yemen</option>
                                <option value="YT">Mayotte</option>
                                <option value="ZA">South Africa</option>
                                <option value="ZM">Zambia</option>
                                <option value="ZW">Zimbabwe</option>
                        
                            </select>
                    </div>
                    <div id="error_make" style="color: red;"></div>
            </div>



               <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="specialization_id">Bank Loan<span class="required"></span></label>
                
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <select  class="form-control select_withoutcross" id="models_id" name="bank_loan">
                        <option value="1">YES</option>
                        <option value="0">No</option>
                           
                    </select>
                </div>
                <div id="error_make" style="color: red;"></div>
            </div>
             <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="specialization_id">Navigation System<span class="required"></span></label>
                
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <select  class="form-control select_withoutcross" id="models_id" name="navigation_system">  
                        <option value="1">YES</option>
                        <option value="0">No</option>>                         
                    </select>
                </div>
                <div id="error_make" style="color: red;"></div>
            </div>

              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="specialization_id">Number Of Keys<span class="required"></span></label>
                
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <select name="fuel_type" data-field="fuel_type" data-field_name="fuel_type" data-provides="anomaly.field_type.select" class="custom-select form-control">

                                <option value="">Select Number of Keys</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
            
                        </select>
                </div>
                <div id="error_make" style="color: red;"></div>
            </div>
              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="specialization_id">Roof Type<span class="required"></span></label>
                
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <select name="roof" data-field="roof" data-field_name="roof" data-provides="anomaly.field_type.select" class="custom-select form-control">

                            <option value="">Select Roof</option>
                            <option value="Regular">Regular</option>
                            <option value="Open Roof">Open Roof</option>
                            <option value="Ventilated Roof">Ventilated Roof</option>
            
                    </select>
                </div>
                <div id="error_make" style="color: red;"></div>
            </div>

             <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="specialization_id">Rim Type<span class="required"></span></label>
                
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <select name="rim_type" data-field="rim_type" data-field_name="rim_type" data-provides="anomaly.field_type.select" class="custom-select form-control">
                        <option value="">Select Rim Tipe</option>
                        <option value="1">Steel</option>
                        <option value="2">Alloy Wheel</option>         
                    </select>
                </div>
                <div id="error_make" style="color: red;"></div>
            </div>

            <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="specialization_id">Rim Tipe<span class="required"></span></label>
                
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <select name="rim_type" data-field="rim_type" data-field_name="rim_type" data-provides="anomaly.field_type.select" class="custom-select form-control">

                        <option value="">Select Rim Tipe</option>
                        <option value="Steel">Steel</option>
                        <option value="Alloy Wheel">Alloy Wheel</option>
            
                    </select>
                </div>
                <div id="error_make" style="color: red;"></div>
            </div>


             <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="specialization_id">Rim Condition<span class="required"></span></label>
                
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <select name="rim_condition" data-field="rim_condition" data-field_name="rim_condition" data-provides="anomaly.field_type.select" class="custom-select form-control">

                        <option value="">Select Rim Condition</option>
                        <option value="Small Scratches">Small Scratches</option>
                        <option value="Brand New">Brand New</option>
                        <option value="Broken">Broken</option>
                        <option value="In Working Condition">In Working Condition</option>
    
                    </select>
                </div>
                <div id="error_make" style="color: red;"></div>
            </div>


             <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="specialization_id">Seat Color<span class="required"></span></label>
                
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <select name="seat_color" data-field="seat_color" data-field_name="seat_color" data-provides="anomaly.field_type.select" class="custom-select form-control">

                        <option value="">Select Seat Color</option>
    
                        <option value="White">White</option>
                        <option value="Silver">Silver</option>
                        <option value="Black">Black</option>
                        <option value="Grey">Grey</option>
                        <option value="Blue">Blue</option>
                        <option value="Red">Red</option>
                        <option value="Brown">Brown</option>
                        <option value="Green">Green</option>
                        <option value="Other">Other</option>
            
                    </select>
                </div>
                <div id="error_make" style="color: red;"></div>
            </div>


            <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="price">Price<span class="required"></span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input type="text"  maxlength="150" id="price" title="price" name="price" value="<?php 
                    if(count($car_list) >0){ echo $car_list[0]['price']; }?>" class="form-control col-md-7 col-xs-12">   
                </div>

                <div id="error" style="color: red;"></div>
            </div>


            <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="evaluation price">Evaluation Price <span class="required"></span>
                </label>

                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input type="text"  maxlength="150" id="evaluation price" title=" evaluation price" name="evaluation_price" value="<?php 
                    if(count($car_list) >0){ echo $car_list[0]['evaluation_price']; }?>" class="form-control col-md-7 col-xs-12">   
                </div>

                <div id="error" style="color: red;"></div>
            </div>
              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="seller price">Seller Price <span class="required"></span>
                </label>

                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input type="text"  maxlength="150" id="seller price" title="seller price" name="seller_price" value="<?php 
                    if(count($car_list) >0){ echo $car_list[0]['seller_price']; }?>" class="form-control col-md-7 col-xs-12">   
                </div>
                <div id="error" style="color: red;"></div>
            </div>


            <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="min bid amount">Min Bid Amount <span class="required"></span>
                </label>

                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input type="text"  maxlength="150" id="min bid amount" title=" min bid amount" name="min_amount" value="<?php 
                    if(count($car_list) >0){ echo $car_list[0]['min_amount']; }?>" class="form-control col-md-7 col-xs-12">   
                </div>
                <div id="error" style="color: red;"></div>
            </div>


             <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="max bid amount">Max Bid Amount <span class="required"></span>
                </label>

                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input type="text"  maxlength="150" id="max bid amount" title=" max bid amount" name="max_amount" value="<?php 
                    if(count($car_list) >0){ echo $car_list[0]['max_amount']; }?>" class="form-control col-md-7 col-xs-12">   
                </div>
                <div id="error" style="color: red;"></div>
            </div>


            <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="reject amount">Rejected Amount <span class="required"></span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input type="text"  maxlength="150" id="reject amount" title=" reject amount" name="reject_amount" value="<?php 
                    if(count($car_list) >0){ echo $car_list[0]['reject_amount']; }?>" class="form-control col-md-7 col-xs-12">   
                </div>
                <div id="error" style="color: red;"></div>
            </div>

            
            <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="exp date">Bid Expire Date<span class="required">*</span>
                </label>

                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input type="date" required maxlength="150" id="exp_date" title=" exp date" name="exp_date" value="<?php 
                    if(count($car_list) >0){ echo $car_list[0]['exp_date']; }?>" class="form-control col-md-7 col-xs-12">
                </div>
                <div id="error" style="color: red;"></div>
            </div>


           <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="specialization_id">Status<span class="required">*</span></label>
                
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <select required name="status" data-field="status" data-field_name="status" data-provides="anomaly.field_type.select" class="custom-select form-control">

                        <option value="">Select Status</option>
                        <option value="Evaluation">Evaluation</option>
                        <option value="Offer">Offer</option>
                        <option value="Purchase">Purchase</option>
                        <option value="Bidding">Bidding</option>
                        <option value="Sold">Sold</option>
            
                    </select>
                </div>
                <div id="error_make" style="color: red;"></div>
            </div>


            <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="specialization_id">Location<span class="required"></span></label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <select required class="form-control select_withoutcross" id="models_id" name="location">                    
                        <option  value="">Select location</option>
                        <option  value="">Dubai</option>
                        <option  value="">UAE</option>
                    </select>
                </div>
                <div id="error_make" style="color: red;"></div>
            </div>


            <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="f name">First Name<span class="required"></span>
                </label>

                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input type="text" required  maxlength="150" id="fname" title="fname" name="first_name" value="<?php 
                    if(count($car_list) >0){ echo $car_list[0]['first_name']; }?>" class="form-control col-md-7 col-xs-12">   
                </div>
                <div id="error" style="color: red;"></div>
            </div>


            <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="location">Last Name<span class="required">*</span>
                </label>

                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input type="text" required  maxlength="150" id="location" title="location" name="last_name" value="<?php 
                    if(count($car_list) >0){ echo $car_list[0]['last_name']; }?>" class="form-control col-md-7 col-xs-12">   
                </div>
                <div id="error" style="color: red;"></div>
            </div>


            <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="location">Date<span class="required">*</span>
                </label>

                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input type="date" required  maxlength="150" id="date" title="date" name="date" value="<?php 
                    if(count($car_list) >0){ echo $car_list[0]['date']; }?>" class="form-control col-md-7 col-xs-12">   
                </div>
                <div id="error" style="color: red;"></div>
            </div>


            <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="mobile number">Mobile Number<span class="required">*</span>
                </label>

                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input type="text" required  maxlength="150" id="location" title="mobile number" name="mobile_number" value="<?php 
                    if(count($car_list) >0){ echo $car_list[0]['mobile_number']; }?>" class="form-control col-md-7 col-xs-12">   
                </div>
                <div id="error" style="color: red;"></div>
            </div>


            <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="Alternative Contact">Alternative Contact<span class="required"></span>
                </label>

                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input type="text"  maxlength="150" id="location" title="location" name="alternative_contact" value="<?php 
                    if(count($car_list) >0){ echo $car_list[0]['alternative_contact']; }?>" class="form-control col-md-7 col-xs-12">   
                </div>
                <div id="error" style="color: red;"></div>
            </div>


            <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Email<span class="required">*</span>
                </label>

                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input type="text" required placeholder="example@gmail.com"  maxlength="150" id="email" title="email" name="email" value="<?php 
                    if(count($car_list) >0){ echo $car_list[0]['email']; }?>" class="form-control col-md-7 col-xs-12">   
                </div>
                <div id="error" style="color: red;"></div>
            </div>
            
          
            <div class="ln_solid"></div>
            <div class="form-group">
                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                    <?php if($title == 'Edit' ){  ?>
                    <button type="submit" id="send" class="btn btn-success">update</button>
                    <a href="<?php echo base_url().'admin/cars/car_list'; ?>" class="btn btn-primary" type="submit">Cancel</a>
                    <?php } else{ ?>

                    <button type="submit" id="send" class="btn btn-success">Submit</button>
                    <a href="<?php echo base_url().'admin/cars/car_list'; ?>" class="btn btn-primary" type="submit">Cancel</a>
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





            $(function() {
            $("#send").click(function() {
            $("#myForm input[type=text]").each(function() {
                if((this.value)=="") {
                    var aa= (this.title);
                    // var aa=document.myForm.(this.id).title;
                    // document.myForm.title.focus();
                    console.log(aa);
                    document.getElementById("error_make").innerHTML=document.myForm.(this.id).title;
                     this.focus();
                     return false;
                 
                }
            });
            return false;
        });
                
        });





            // $.validator.setDefaults({
            //     debug: true,
            //     success: "valid"
            //     });
                
            //     $( "#myForm" ).validate({
            //         rules: {
            //         valuation_make_id: {
            //         required: true
            //         }
            //     }
            // });
     



         $('#make_id').on('change', function() {
            var make_id=document.myForm.valuation_make_id.value;
               $.ajax({    //create an ajax request to display.php
                        type: "post",
                        url: "<?php echo base_url(); ?>" + "admin/cars/get_make_data/" + make_id,    
                        data:{ make_id:make_id},
                        success: function(data) {
                         console.log(data);
                        $('select#valuation_model_id').html('');
                        $.each(JSON.parse(data), function(i,v) {
                          $("<option / >").val(v.id).text(v.title).appendTo('select#valuation_model_id');
                          // body...
                        });
                    }
                  });
                });

          $('#valuation_model_id').on('change', function() {
            var model_id=document.myForm.valuation_model_id.value;
               $.ajax({    //create an ajax request to display.php
                        type: "post",
                        url: "<?php echo base_url(); ?>" + "admin/cars/get_engineBymodel/" + model_id,    
                        success: function(data) {
                        $('select#valuation_engine_size').html('');
                        $.each(JSON.parse(data), function(i,v) {
                          $("<option / >").val(v.id).text(v.title).appendTo('select#valuation_engine_size');
                        });
                    }
                  });
                }); 

           var make_id = $("#make_id option:selected").val();
                console.log(make_id);
                 $.ajax({    //create an ajax request to display.php
                        type: "post",
                        url: "<?php echo base_url(); ?>" + "admin/cars/get_make_data/" + make_id,    
                        data:{ make_id:make_id},
                        success: function(data) {
                         console.log(data);
                        $('select#valuation_model_id').html('');
                        $.each(JSON.parse(data), function(i,v) {
                          $("<option / >").val(v.id).text(v.title).appendTo('select#valuation_model_id');
                          // body...
                        });
                    }
                  });


                    $('#demo-form2').parsley().on('field:validated', function() {
            var ok = $('.parsley-error').length === 0;
        }) .on('form:submit', function() {
        return false; // Don't submit form for this demo
    });
    </script>

