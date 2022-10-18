<script src="<?php echo base_url();?>assets_admin/js/formBuilder/jquery-ui.min.js"></script>
<script src="<?php echo base_url();?>assets_admin/js/formBuilder/form-render.min.js"></script>
<style type="text/css">
    .dz-image img{
    max-width: 120px;
    max-height: 120px;
    }
</style>
<input type="hidden" id="base_url" value="<?php echo base_url(); ?>">

<div class="clearfix"></div>

<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>
                <?php 
                if (isset($item_info[0]['name'])) {
                    $item_name = json_decode($item_info[0]['name']);
                }
                // $item_name = json_decode($item_info[0]['name']);
                if(isset($item_info) && !empty($item_info)){
                    echo 'Update - '.$item_name->english; 
                }else{
                    echo 'Add Item';
                } 

                ?>
            </h2>

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
           
            <form method="post" novalidate="" id="demo-form2" action="" enctype="multipart/form-data" class="form-horizontal form-label-left">
                <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
                <?php if(isset($item_info) && !empty($item_info)){ ?>
                    <input type="hidden" name="item[id]" value="<?php echo $this->uri->segment(3); ?>">
                <?php } ?>  
                <span class="section">Basic Info</span>

                <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="category_id">Item Category  <span class="required">*</span></label>
                <?php
                $read_only = '';
                $has_id = $this->uri->segment(3);
                   if (!empty($has_id)) {
                       $if_available = $this->db->get_where('auction_items',['item_id' => $has_id])->row_array();
                       if ($if_available ) {
                           $read_only = 'pointer-events: none;';
                       }
                   }
                ?>
                <div class="col-md-6 col-sm-6 col-xs-12" style=" <?php echo $read_only;?> ">
                    <select class="form-control col-md-7 col-xs-12" id="category_id"  name="item[category_id]" required="required">
                         <option value="" data-make_model="no">Select Category</option>
                         <?php foreach ($category_list as $category_value) { ?>
                         <?php $title = json_decode($category_value['title']); ?>
                        <option data-make_model="<?= $category_value['include_make_model']; ?>"
                        <?php if(isset($item_info) && !empty($item_info)){
                            if($item_info[0]['category_id'] == $category_value['id']){ echo 'selected';}
                        }?>
                            value="<?php echo $category_value['id']; ?>"><?php echo $title->english; ?></option>
                            <?php  } ?> 
                    </select>
                </div>
                </div>

                <div class="item form-group categories_case">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="subcategory_id">Item Sub Category</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                <select class="form-control col-md-7 col-xs-12" id="subcategory_id" name="item[subcategory_id]">
                </select>
                </div>
                </div>
                <div class="vehicle_case">

                    <div class="item form-group make_case">    
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="make">Make <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="form-control col-md-7 col-xs-12" id="make" name="item[make]">
                                <option value="">Select Make</option>
                            </select>
                        </div>
                    </div> 
                    
                    <div class="item form-group">    
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="model">Model <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="form-control col-md-7 col-xs-12" id="model" name="item[model]">
                                <option value="">Select Model</option>
                            </select>
                        </div>
                    </div> 

                    <div class="item form-group">    
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="mileage">Mileage <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="number" min="0" required="required" oninput="this.value=this.value.replace(/[^0-9.]/g,'');" id="mileage" name="item[mileage]" value="<?php if(isset($item_info) && !empty($item_info)) { echo $item_info[0]['mileage']; } ?>" class="form-control col-md-7 col-xs-12">
                        </div>
                    </div>

                    <div class="item form-group">    
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="mileage_type">Mileage Type <span class="required">*</span>
                            </label>
                        <div class="col-md-6 col-sm-6 col-xs-12" >
                            <select id="mileage_type" name="item[mileage_type]" class="form-control col-md-7 col-xs-12">
                                <option value="km" <?php if(isset($item_info) && !empty($item_info[0]['mileage_type'] == 'km')) { echo 'selected="selected"'; } ?> >KM</option>
                                <option value="miles" <?php if(isset($item_info) && !empty($item_info[0]['mileage_type'] == 'miles')) { echo 'selected="selected"'; } ?>>Miles</option>
                            </select>
                        </div>
                    </div> 

                      <div class="item form-group">    
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="mileage_type">Specification <span class="required">*</span>
                            </label>
                        <div class="col-md-6 col-sm-6 col-xs-12" >
                            <select id="specification" name="item[specification]" class="form-control col-md-7 col-xs-12">
                                <option value="GCC" <?php if(isset($item_info) && !empty($item_info[0]['specification'] == 'GCC')) { echo 'selected="selected"'; } ?> >GCC</option>
                                <option value="IMPORTED" <?php if(isset($item_info) && !empty($item_info[0]['specification'] == 'IMPORTED')) { echo 'selected="selected"'; } ?>>IMPORTED</option>
                            </select>
                        </div>
                    </div>
                </div>
                    
                <div class="item form-group">    
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="vin_no">VIN / Information Number
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text"   id="vin_no" name="item[vin_no]" value="<?php if(isset($item_info) && !empty($item_info)) { echo $item_info[0]['vin_number']; } ?>" class="form-control col-md-7 col-xs-12">
                        <span id="vin_error" class="text-danger"></span>
                    </div>
                </div> 

                <div class="registration_no item form-group">    
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="registration_no">Registration Code <span class="required">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" id="registration_no"  data-parsley-trigger="focusin focusout" <?php if(isset($item_info) && !empty($item_info)) { echo ''; }else{ echo 'data-parsley-remote="'.base_url().'items/validate_check_registration_no/{value}" data-parsley-remote-validator="mycustom_code" data-parsley-remote-message="Code already exists"'; } ?>  required="required" name="item[registration_no]" value="<?php if(isset($item_info) && !empty($item_info)) { echo $item_info[0]['registration_no']; } ?>" class="form-control col-md-7 col-xs-12">
                    </div>
                </div> 

                <div class="item form-group">    
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Item Name English<span class="required">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text"   id="name" required="required" name="item[name_english]" value="<?php if(isset($item_info) && !empty($item_info)) {
                            $name = json_decode($item_info[0]['name']);
                         echo $name->english; } ?>" class="form-control col-md-7 col-xs-12">
                    </div>
                </div> 

                <div class="item form-group">    
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Item Name Arabic<span class="required">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" dir="rtl" required="required" name="item[name_arabic]" value="<?php if(isset($item_info) && !empty($item_info)) { echo $name->arabic; } ?>" class="form-control col-md-7 col-xs-12">
                    </div>
                </div> 

                <div class="item form-group">    
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Item Location <span class="required">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="lon" type="hidden" name="item[lng]">
                        <input id="lat" type="hidden" name="item[lat]">
                        <input type="text" id="location" required="required" name="item[location]" value="<?php if(isset($item_info) && !empty($item_info)) { echo $item_info[0]['location']; } ?>" class="form-control col-md-7 col-xs-12">
                    </div>
                </div> 

                <div class="item form-group">    
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="price">Reserve Price <span class="required">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="number" oninput="this.value=this.value.replace(/[^0-9.]/g,'');" min="0" id="price" required="required" name="item[price]" value="<?php if(isset($item_info) && !empty($item_info)) { echo $item_info[0]['price']; } ?>" class="form-control col-md-7 col-xs-12">
                    </div>
                </div>

                <div class="item form-group">    
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="year">Year 
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <select required="" class="form-control select2" id="year" name="item[year]">
                            <?php $year = date('Y'); ?>
                            <?php for ($i=0; $i <= 50 ; $i++) { ?>
                                <option <?php if(isset($item_info) && !empty($item_info) && $item_info[0]['year'] == $year-$i){ echo 'selected'; }?> value="<?= $year-$i ?>"><?= $year-$i ?></option>
                            <?php }; ?>
                        </select>
                    </div>
                </div>  

                <div class="item form-group">    
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="keyword">Keyword  
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" id="keyword" name="item[keyword]" value="<?php if(isset($item_info) && !empty($item_info)) { echo $item_info[0]['keyword']; } ?>" class="form-control col-md-7 col-xs-12">
                    </div>
                </div>
<!-- 
                <div class="item form-group">    
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="other_charges">Other Charges  
                        </label>

                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="number" id="other_charges" name="item[other_charges]" value="<?php //if(isset($item_info) && !empty($item_info)) { echo $item_info[0]['other_charges']; } ?>" class="form-control col-md-7 col-xs-12">
                    </div>
                </div> --> 
 
                <div class="item form-group">    
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="detail">Details English
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <textarea type="text" rows="5"   id="detail" title="please add something" name="item[detail_english]" class="form-control col-md-7 col-xs-12"><?php 
                        if(isset($item_info) && !empty($item_info)){ 
                            $detail = json_decode($item_info[0]['detail']);
                            echo $detail->english; }?></textarea>
                    </div>
                </div>  
 
                <div class="item form-group">    
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="detail">Details Arabic
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <textarea type="text" rows="5" dir="rtl" title="please add something" name="item[detail_arabic]" class="form-control col-md-7 col-xs-12"><?php 
                        if(isset($item_info) && !empty($item_info)){ echo $detail->arabic; }?></textarea>
                    </div>
                </div>  

                <div class="item form-group">    
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="detail">Terms & Conditions English
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <textarea type="text" rows="6"   id="terms" title="please add something" name="item[terms_english]" class="form-control col-md-7 col-xs-12"><?php 
                        if(isset($item_info) && !empty($item_info)){ 
                            $TandC = json_decode($item_info[0]['terms']);
                            echo (!empty($TandC)) ? $TandC->english : ''; }?></textarea>
                    </div>
                </div>

                <div class="item form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="detail">Terms & Conditions Arabic 
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <textarea type="text" rows="6" dir="rtl" id="terms" title="please add something" name="item[terms_arabic]" class="form-control col-md-7 col-xs-12"><?php 
                        if(isset($item_info) && !empty($item_info)){ echo (!empty($TandC)) ? $TandC->arabic : ''; }?></textarea>
                    </div>
                </div>

                

                        <div class="item form-group">    
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="additional_info">Additional Info English
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <textarea type="text" rows="5"   id="additional_info" title="please add something" name="item[additional_info_english]" class="form-control col-md-7 col-xs-12">
                        <?php 
                        if(isset($item_info) && !empty($item_info)){ 
                            $additional_info = json_decode($item_info[0]['additional_info']);
                            echo (!empty($additional_info)) ? $additional_info->english : ''; }?>

                        </textarea>
                    </div>
                </div>  
 
                <div class="item form-group">    
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="additional_info">Additional Info Arabic
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <textarea type="text" rows="5" id="additional_info" dir="rtl" title="please add something" name="item[additional_info_arabic]" class="form-control col-md-7 col-xs-12">
                        <?php 
                        if(isset($item_info) && !empty($item_info)){ echo (!empty($additional_info)) ? $additional_info->arabic : ''; }?>
                    </textarea>
                    </div>
                </div>





                <div class="item form-group">    
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="status">Status <span class="required">*</span>
                </label>       
                <div class="col-md-6 col-sm-6 col-xs-12"> 
                    <select required="" class="form-control select2" id="status" name="item[status]">
                        <option <?php if(isset($item_info) && !empty($item_info) && $item_info[0]['status'] == 'active'){ echo 'selected'; }?> value="active">Active</option>
                        <option <?php if(isset($item_info) && !empty($item_info) && $item_info[0]['status'] == 'inactive'){ echo 'selected'; }?> value="inactive">In Active</option>
                    </select>
                </div>
                </div>

                <div class="item form-group">    
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="item_status">Item Status <span class="required">*</span>
                </label>
                
                <div class="col-md-6 col-sm-6 col-xs-12"> 
                    <select required="" class="form-control select2" id="item_status" name="item[item_status]">
                        <option <?php if(isset($item_info) && !empty($item_info) && $item_info[0]['item_status'] == 'created'){ echo 'selected'; }?> value="created">Created</option>
                        <option <?php if(isset($item_info) && !empty($item_info) && $item_info[0]['item_status'] == 'completed'){ echo 'selected'; }?> value="completed">Completed</option>
                        <option <?php if(isset($item_info) && !empty($item_info) && $item_info[0]['item_status'] == 'cancelled'){ echo 'selected'; }?> value="cancelled">Cancelled</option>
                    </select>
                </div>
                </div>

                <div class="item form-group">    
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="feature">Feature Item <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12"> 
                    <select required="" class="form-control select2" id="feature" name="item[feature]">

                        <option <?php if(isset($item_info) && !empty($item_info) && $item_info[0]['feature'] == 'no'){ echo 'selected'; }?> value="no">No</option>

                        <option <?php if(isset($item_info) && !empty($item_info) && $item_info[0]['feature'] == 'yes'){ echo 'selected'; }?> value="yes">Yes</option>
                    </select>
                </div>
                </div>
                
                <!-- <div class="item form-group">    
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="auction_type">Auction Type <span class="required">*</span>
                </label>
                
                <div class="col-md-6 col-sm-6 col-xs-12"> 
                    <select required class="form-control select2" id="auction_type" multiple name="item[auction_type][]">
                        <?php //$auction_type_arr = explode(",",$item_info[0]['auction_type']); ?>
                        <option <?php //if(isset($item_info) && !empty($item_info) && in_array('online', $auction_type_arr)){ echo 'selected'; }?> value="online">Online</option>
                        <option <?php //if(isset($item_info) && !empty($item_info) && in_array('closed', $auction_type_arr)){ echo 'selected'; }?> value="closed">Closed</option>
                        <option <?php //if(isset($item_info) && !empty($item_info) && in_array('live', $auction_type_arr)){ echo 'selected'; }?> value="live">Live</option> 
                    </select>
                </div>
                </div> -->
                

                <div class="item form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="seller_id">Seller <span class="required">*</span></label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <?php 
                        $seller_name = 'Select Seller';
                        if(isset($item_info[0]['seller_id']) && !empty($item_info[0]['seller_id'])) {
                            $seller = $this->db->get_where('users', ['id' => $item_info[0]['seller_id']])->row_array();
                            if($seller['role'] == 4){
                                $seller_name = $seller['fname'].' '.$seller['lname'];
                            }else{
                                $seller_name = '';
                            }
                        }else{
                            $seller['id'] = 1;
                            $seller_name = '';
                        }
                        ?>
                        <input type="text" readonly id="selected_seller" placeholder="Select Seller" required="required" value="<?= $seller_name; ?>" class="form-control col-md-7 col-xs-12">
                        <input type="hidden" id="seller_id" name="item[seller_id]" value="<?= $seller['id']; ?>" >
                        <!-- <select class="form-control col-md-7 col-xs-12" id="selected_seller" name="item[seller_id]" >
                            <option readonly selected="" value="<?//= $seller['id']; ?>" ><?= $seller_name; ?></option>  
                        </select> -->
                    </div>
                    <!-- data-toggle="modal" data-target="#users" -->
                    <a type="button" id="btnid" class="btn btn-success btnid">Select</a>
                </div>
    
                <span class="section">Other Info</span>   
                <div style="margin: 0 auto; width: 60%;" class="other-info container-fluid">
                <div id="build-wrap" class="center"></div>
                </div>
                <div class="ln_solid"></div>
                <div class="form-group">
                    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                    <button type="submit" id="send" class="btn btn-success">Submit</button>
                     <?php 
                        $url = base_url('items');
                    if (isset($_GET['rurl'])){
                        $url = urldecode($_GET['rurl']);
                    }?>
                         <a href="<?php echo $url; ?>" class="custom-class" type="button"> Cancel    
                    </a>
                    </div>
                </div>
                
            </form>
        </div>
    </div>
    <div id="map"></div>
        
</div>

<script src="<?php echo base_url(); ?>assets_admin/js/jquery.geocomplete.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?libraries=places&key=<?= $this->config->item('google_geolocation_key'); ?>&callback=initMap" async defer></script>
<script>

	$('#make').on('change', function() {
		var optionSelected = $(this).find("option:selected");
		$("#name").val(optionSelected.text())
	});

	$('#model').on('change', function() {
		var makeSelected = $("#make").find("option:selected");
		var optionSelected = $(this).find("option:selected");
		$("#name").val(makeSelected.text()+" "+optionSelected.text())
	});
  // Call Geo Complete
 // $("#location").geocomplete({details:"form#demo-form2"});


function initMap() {
    var map = new google.maps.Map(document.getElementById('map'), {
      center: {lat: -33.8688, lng: 151.2195},
      zoom: 13
    });

    var input = document.getElementById('location');
    map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

    var autocomplete = new google.maps.places.Autocomplete(input);
    autocomplete.bindTo('bounds', map);

    var infowindow = new google.maps.InfoWindow();
    var marker = new google.maps.Marker({
        map: map,
        anchorPoint: new google.maps.Point(0, -29)
    });

    autocomplete.addListener('place_changed', function() {
        infowindow.close();
        marker.setVisible(false);
        var place = autocomplete.getPlace();
        if (!place.geometry) {
            window.alert("Autocomplete's returned place contains no geometry");
            return;
        }
  
        // If the place has a geometry, then present it on a map.
        if (place.geometry.viewport) {
            map.fitBounds(place.geometry.viewport);
        } else {
            map.setCenter(place.geometry.location);
            map.setZoom(17);
        }
        marker.setIcon(({
            url: place.icon,
            size: new google.maps.Size(71, 71),
            origin: new google.maps.Point(0, 0),
            anchor: new google.maps.Point(17, 34),
            scaledSize: new google.maps.Size(35, 35)
        }));
        marker.setPosition(place.geometry.location);
        marker.setVisible(true);
    
        var address = '';
        if (place.address_components) {
            address = [
              (place.address_components[0] && place.address_components[0].short_name || ''),
              (place.address_components[1] && place.address_components[1].short_name || ''),
              (place.address_components[2] && place.address_components[2].short_name || '')
            ].join(' ');
        }
    
        infowindow.setContent('<div><strong>' + place.name + '</strong><br>' + address);
        infowindow.open(map, marker);
      
        // Location details
        for (var i = 0; i < place.address_components.length; i++) {
            if(place.address_components[i].types[0] == 'postal_code'){
                document.getElementById('postal_code').innerHTML = place.address_components[i].long_name;
            }
            if(place.address_components[i].types[0] == 'country'){
                // document.getElementById('country').innerHTML = place.address_components[i].long_name;
            }
        }
        // document.getElementById('location').innerHTML = place.formatted_address;
        // document.getElementById('lat').innerHTML = place.geometry.location.lat();
        // document.getElementById('lon').innerHTML = place.geometry.location.lng();
        // document.getElementById('location').value = place.formatted_address;
        document.getElementById('lat').value = place.geometry.location.lat();
        document.getElementById('lon').value = place.geometry.location.lng();
    });
}

    var token_name = '<?= $this->security->get_csrf_token_name();?>';
    var token_value = '<?=$this->security->get_csrf_hash();?>';

    // $('.btnid').on('Click', function(event) {
    $('#btnid').on('click', function(event){
        event.stopImmediatePropagation();
        console.log('click');
          $.ajax({    //create an ajax request to display.php
            type: "post",
            url: "<?php echo base_url(); ?>" + "items/load_user_popup",    
            data:{[token_name]:token_value},
            success: function(data) {
                objdata = $.parseJSON(data);
                if(objdata.success == true)
                {
                    // $('.make_case').show();
                    // $('#model').attr('disabled',false);
                    // $('#model').html(objdata.data);
                    $('#data_table').html('');
                    $('#data_table').html(objdata.opup_data);
                    // $('#customers').modal({
                    //     backdrop: 'static'
                    // })
                    $('#users').modal();
                }
                else
                {
                    // $('.make_case').hide();
                    $('#model').attr('disabled',false);
                    $('#model').html('<option value="">Select Model</option>');
                }
            }
        });
        return false;
    }); 


$(document).ready(function(){
});
    
</script>
  
<script>   
    // $('.select2').select2();
    // $('#7-test').select2();

    <?php 
    if(isset($item_info[0]['seller_id']) && !empty($item_info[0]['seller_id'])){ 
        // for existed selected options
        foreach ($seller_list as $seller_value) { 
            if($item_info[0]['seller_id'] == $seller_value['id']){
    ?>
    var data = {
        id: <?php echo $item_info[0]['seller_id']; ?>,
        text: "<?= (isset($seller_value['fname'])) ? $seller_value['fname'].' '.$seller_value['lname'] : '' ; ?>"
    };
    var newOption = new Option(data.text, data.id, false, false);
    $('#seller_id').append(newOption).trigger('change');        
    <?php  
            }
        }   
    } 
    ?> 
    // var selectOz = remoteSelect2({ 
    //   'selectorId' : '#seller_id',
    //   'placeholder' : 'Select Seller',
    //   'table' : 'users',
    //   'values' : 'Name',
    //   'width' : '200px',
    //   'delay' : '250',
    //   'cache' : false,
    //   'minimumInputLength' : 1,
    //   'limit' : 5,
    //   'url' : '<?= base_url();?>items/seller_id_api'
    // });

    var url = '<?php echo base_url();?>';
    var formaction_path = '<?php echo $formaction_path;?>';
    var subcategory_id_selected = '<?php echo (isset($item_info[0]['subcategory_id']) && !empty($item_info[0]['subcategory_id'])) ? $item_info[0]['subcategory_id'] : '' ;?>';
    var make_id_selected = '<?php echo (isset($item_info[0]['make']) && !empty($item_info[0]['make'])) ? $item_info[0]['make'] : '' ;?>';
    var model_id_selected = '<?php echo (isset($item_info[0]['model']) && !empty($item_info[0]['model'])) ? $item_info[0]['model'] : '' ;?>';
    var data_ids_json = '<?php echo (isset($field_ids) && !empty($field_ids)) ? json_encode($field_ids) : ''; ?>';
    var data_values_json = '<?php echo (isset($field_values) && !empty($field_values)) ? addslashes(json_encode($field_values)) : ''; ?>';
    var if_code_ = '<?php  echo (isset($item_info[0]['registration_no']) && !empty($item_info[0]['registration_no'])) ? $item_info[0]['registration_no'] : ""; ?>';
    // console.log(data_values_json);
    // data_values_arr = $.parseJSON(data_values_json);

    $(document).ready(function() {
    if(if_code_ == '')
    {
        let value_option = '';
        window.Parsley.addAsyncValidator('mycustom_code', function (xhr) {
        // console.log(this.$element); // jQuery Object[ input[name="q"] ]
        var obj = this.$element;
        // console.log(obj.val());
        value_option = obj.val();

        console.log(value_option);
        var response = xhr.responseText;
        if (response === '200') {
            // success if not exists
        return 200 === xhr.status;
        } else {
            // error if already exists
        return 404 === xhr.status;
        }

        }); 
    }

    $('#vin_no').on('keyup', function(){
        var vin_number = $('#vin_no').val();
        console.log(vin_number);
        $.ajax({
            url: url + 'items/check_vin_number',
            type: 'POST',
            data: {vin_number : vin_number,'<?= $this->security->get_csrf_token_name();?>':"<?=$this->security->get_csrf_hash();?>"},
            success : function(data) {
                var objData = jQuery.parseJSON(data);
                if (objData.success == true) { 
                    $("#vin_error").show().html(objData.msg);
                }
                else{
                    $("#vin_error").hide();
                }  
            },
        });
    });

         // You can use the locally-scoped $ in here as an alias to jQuery.
        $('#demo-form2').parsley().on('field:validated', function() {
            var ok = $('.parsley-error').length === 0; 

          }) .on('form:submit', function() {
            // event.preventDefault();
            // alert('asdf');
            var formData = new FormData($("#demo-form2")[0]);
                    $.ajax({
                        url: url + 'items/' + formaction_path,
                        type: 'POST',
                        data: formData,
                        cache: false,
                        contentType: false,
                        processData: false,
                        beforeSend: function(){
                            $("#send").html('Loading..');
                            $("#send").attr('disabled', true);
                            // $('#username').html('<img src="'+url+'assets_admin/images/load.gif" />');
                        },
                        success : function(data) {
                            var objData = jQuery.parseJSON(data);
                           if (objData.msg == 'success') { 
                            
                            <?php
                                if(isset($_GET['rurl'])){
                                    $rurl = urldecode($_GET['rurl']);
                                }else{
                                    $rurl = base_url('items');
                                }
                                ?>
                                window.location = '<?= $rurl; ?>';
                            
                            } else {
                                $("#send").html('Submit');
                                $("#send").attr('disabled', false);
                                $('.msg-alert').css('display', 'block');
                                $("#result").html('<div class="alert" ><div class="alert alert-domain alert-danger alert-dismissible fade in  msg-alert" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: black"><span aria-hidden="true">×</span> </button>' + objData.msg + '</div></div>');
                            }  
                        },
                        complete: function (data,ddd,fff) {
                            console.log('completed'); 
                            // window.location = url + 'Items';
                         }
                        });
            return false; // Don't submit form for this demo
          });
  
        // You can use the locally-scoped $ in here as an alias to jQuery.
      
            // $("#send").on('click', function(e) { //e.preventDefault();            
            // });
            });
 
    

    getcategory_fields();
    function getcategory_fields() {
        var category_id = $('#category_id').val();
        var selectedText = $("#category_id").find("option:selected").attr('data-make_model');
        // if (selectedText.toLowerCase() == 'marine') {
        //     $('.vehicle_case').hide();
        //     $('#make').attr('disabled',true);
        //     $('#model').attr('disabled',true);
        //     $('#make').attr('required',false);
        //     $('#model').attr('required',false);
        // }
        // else 
        if(selectedText.toLowerCase() == 'yes')
        {
            console.log(category_id);
            $('.vehicle_case').show();
            $('#make').attr('disabled',false);
            $('#model').attr('disabled',false);
            $('#mileage').attr('disabled',false);

            $('#make').attr('required',true);
            $('#model').attr('required',true);
            $('#mileage').attr('required',true);

            $.ajax({    //create an ajax request to display.php
            type: "post",
            url: "<?php echo base_url(); ?>" + "items/get_makes_options",    
            data:{ category_id:category_id, [token_name] : token_value},
            success: function(data) 
            {
                objdata = $.parseJSON(data);
                if(objdata.msg == 'success')
                {
                    $('.make_case').show();
                    $('#make').attr('disabled',false);
                    $('#make').html(objdata.data);
                    if(make_id_selected != '')
                    {
                    $('#make option[value="'+make_id_selected+'"]').attr("selected", "selected");

                    $.ajax({    //create an ajax request to display.php
                        type: "post",
                        url: "<?php echo base_url(); ?>" + "items/get_model_options",    
                        data:{ make_id:make_id_selected, [token_name] : token_value},
                        success: function(data) {
                            objdata = $.parseJSON(data);
                            if(objdata.msg == 'success')
                            {
                                // $('.make_case').show();
                                $('#model').attr('disabled',false);
                                $('#model').html(objdata.data);
                                if(make_id_selected != '')
                                {
                                    $('#model option[value="'+model_id_selected+'"]').attr("selected", "selected");
                                }

                            }
                            else
                            {
                                // $('.make_case').hide();
                                $('#model').attr('disabled',false);
                                $('#model').html('<option value="">Select Model</option>');
                            }
                        }
                    });
                    }


                }
                else
                {
                    $('.make_case').hide();
                    $('#make').attr('disabled',true);
                    $('#make').html('');
                }
            }
            });

        }else{
            $('.vehicle_case').hide();
            $('#make').attr('disabled',true);
            $('#model').attr('disabled',true);
            $('#mileage').attr('disabled',true);

            $('#make').attr('required',false);
            $('#model').attr('required',false);
            $('#mileage').attr('required',false);
        }
       
         $.ajax({    //create an ajax request to display.php
                    type: "post",
                    url: "<?php echo base_url(); ?>" + "items/get_item_fields",    
                    data:{ category_id:category_id, [token_name] : token_value},
                    success: function(data) {
                     data = $.parseJSON(data);
                     console.log(data);
                     jQuery.each(data, function(indexs, item_value) {
                        data[indexs].name = data[indexs].id+'-'+data[indexs].name;
                     });

                     if(data_values_json != ''){ 
                     data_values_arr = $.parseJSON(data_values_json); 
                    
                     jQuery.each(data, function(indexs, item_value1) {
                   
                        jQuery.each(data_values_arr, function(index, item_value2) {
                                // console.log(index);
                               
                                if(index == item_value1.id)
                                {
                                        // alert(data[indexs].multiple);
                                    if(data[indexs].multiple == 'true'){
                                    jQuery.each(data[indexs].values, function(index_inner, item_value_inner){
                                        // console.log(item_value_inner.value+' - '+ item_value2.value);
                                        var hasApple = item_value2.value.indexOf(item_value_inner.value) != -1;
                                        if(hasApple)
                                        {
                                            // make selected if match
                                            item_value_inner.selected = 'true';
                                        }
                                    });

                                    }
                                    else
                                    {
                                        
                                    data[indexs].value = item_value2.value;
                                    }
                                }
                        });
                                // console.log(item_value1.id);
                    });

                     }
                     
                   
                    formData = data;
                    var formRenderOpts = {
                        formData,
                        dataType: 'json'
                    };
                    var frInstance = $('#build-wrap').formRender(formRenderOpts);
                   
                }
              });


           $.ajax({    //create an ajax request to display.php
                    type: "post",
                    url: "<?php echo base_url(); ?>" + "items/get_subcategories",    
                    data:{ category_id:category_id, [token_name] : token_value},
                    success: function(data) {
                     objdata = $.parseJSON(data);
                    if(objdata.msg == 'success')
                    {
                        $('.categories_case').show();
                        $('#subcategory_id').attr('disabled',false);
                        $('#subcategory_id').html(objdata.data);
                        if(subcategory_id_selected != '')
                        {
                        $('#subcategory_id option[value="'+subcategory_id_selected+'"]').attr("selected", "selected");
                        }

                    }
                    else
                    {
                        $('.categories_case').hide();
                        $('#subcategory_id').attr('disabled',true);
                        $('#subcategory_id').html('');
                    }
                }
              });
    }

    $('#make').on('change', function() {
        var make_id = $(this).val();
          $.ajax({    //create an ajax request to display.php
            type: "post",
            url: "<?php echo base_url(); ?>" + "items/get_model_options",    
            data:{ make_id:make_id, [token_name] : token_value},
            success: function(data) {
                objdata = $.parseJSON(data);
                if(objdata.msg == 'success')
                {
                    // $('.make_case').show();
                    $('#model').attr('disabled',false);
                    $('#model').html(objdata.data);
					var optionSelected = $("#make").find("option:selected");
					var modelSelected = $("#model").find("option:selected");
					$("#name").val(optionSelected.text()+" "+modelSelected.text())
                }
                else
                {
                    // $('.make_case').hide();
                    $('#model').attr('disabled',false);
                    $('#model').html('<option value="">Select Model</option>');
                }
            }
        });

     });   
    $('#category_id').on('change', function() {
        var category_id = $(this).val();
        var selectedText = $(this).find("option:selected").attr('data-make_model');
        // if (selectedText.toLowerCase() == 'marine') {
        //     $('.vehicle_case').hide();
        //     $('#make').attr('disabled',true);
        //     $('#model').attr('disabled',true);
        //     $('#make').attr('required',false);
        //     $('#model').attr('required',false);
        // }
        // else 
        if(selectedText.toLowerCase() == 'yes')
        {
            // console.log(category_id);
            $('.vehicle_case').show();
            $('#make').attr('disabled',false);
            $('#model').attr('disabled',false);
            $('#mileage').attr('disabled',false);

            $('#make').attr('required',true);
            $('#model').attr('required',true);
            $('#mileage').attr('required',true);

        $.ajax({    //create an ajax request to display.php
            type: "post",
            url: "<?php echo base_url(); ?>" + "items/get_makes_options",    
            data:{ category_id:category_id, [token_name] : token_value},
            success: function(data) {
                objdata = $.parseJSON(data);
                if(objdata.msg == 'success')
                {
                    $('.make_case').show();
                    $('#make').attr('disabled',false);
                    $('#make').html(objdata.data);

                }
                else
                {
                    $('.make_case').hide();
                    $('#make').attr('disabled',true);
                    $('#make').html('');
                }
            }
        });

        }
        else
        {
            $('.vehicle_case').hide();
            $('#make').attr('disabled',true);
            $('#model').attr('disabled',true);
            $('#mileage').attr('disabled',true);

            $('#make').attr('required',false);
            $('#model').attr('required',false);
            $('#mileage').attr('required',false);
        }    
           $.ajax({    //create an ajax request to display.php
                    type: "post",
                    url: "<?php echo base_url(); ?>" + "items/get_item_fields",    
                    data:{ category_id:category_id, [token_name] : token_value},
                    success: function(data) {
                     data = $.parseJSON(data);
                     var lim = data.length;
                    // console.log(lim);
                    for (var i = 0; i < lim; i++)
                    {
                       if (data[i].id != '')
                       { 
                            data[i].name = data[i].id+'-'+data[i].name;
                          
                       }
                    }
                
                    formData = data;
                    var formRenderOpts = {
                        formData,
                        dataType: 'json'

                    };
                    var frInstance = $('#build-wrap').formRender(formRenderOpts);
                  

                }
              });

            $.ajax({    //create an ajax request to display.php
                    type: "post",
                    url: "<?php echo base_url(); ?>" + "items/get_subcategories",    
                    data:{ category_id:category_id, [token_name] : token_value},
                    success: function(data) {
                     objdata = $.parseJSON(data);
                    if(objdata.msg == 'success')
                    {
                        $('.categories_case').show();
                        $('#subcategory_id').attr('disabled',false);
                        $('#subcategory_id').html(objdata.data);

                    }
                    else
                    {
                        $('.categories_case').hide();
                        $('#subcategory_id').attr('disabled',true);
                        $('#subcategory_id').html('');
                    }
                }
              });
            }); 

    
</script>
