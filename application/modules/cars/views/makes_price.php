<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>
                <?php echo $small_title; ?>
            </h2>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
        <div id="result"></div>
        <?php if ($this->session->flashdata('msg')) { ?>
            <div class="alert">
                <div class="alert alert-domain alert-success alert-dismissible fade in" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true" style="color: black;">×</span>
                      </button>
                    <?php echo $this->session->flashdata('msg'); ?>
                </div>
            </div>
            <?php } ?>
            <br />
            
             <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
               <form method="post"  id="" action="<?php echo base_url(); ?>cars/makesPrices">
                <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
                <table class="table" style="margin-top: 0px;">
                  <thead style="background-color: rgb(255, 255, 255); width: 1039px; position: relative; top: 0px; z-index: 1010;">
                    <tr>
                        <th style="width: 156px;">Model</th>
                        <th style="width: 835px;">Price</th>
                        <th></th>
                        <th></th>
                    </tr>
                  </thead>
                <tbody>
                
                <?php
                  $counter = 0;
                  foreach($get_models as $models){
                    ?>
                      
                      <tr>
                        <td style="width: 100px;">
                            <?php echo $models['title']; ?>
                        </td>
                        <td style="">
                            <input class="form-control " type="hidden" name="prices[<?php echo $counter; ?>][valuation_make_id]" value="<?php echo $make_id; ?>"/>
                            <input class="form-control model_id" type="hidden" name="prices[<?php echo $counter; ?>][valuation_model_id]" value="<?php echo $models['id']; ?>"/>
                            <div class="row">                            
                                <div class="col-md-2">Option</div>
                                <div class="col-md-4">Year Range</div>
                                <div class="col-md-2">Price</div>
                                <div class="col-md-2">Litres</div>
                                <div class="col-md-2">Depreciation</div>
                            </div>
                            <div class="prices-wrapper" id="model_push_<?php echo $counter; ?>"> 
                                 
                                                        
                                <?php
                                   $counter_op = 0;
                                   foreach($valuate_option as $option){
                                    ?>
                            <div class="row" style="margin-top: 10px;">
                             <div class="col-md-2">
                             <?php echo $option['title']; ?>

                               <input type="hidden" name="prices[<?php echo $counter; ?>][prices][<?php echo $counter_op; ?>][option_id]" value="<?php echo $option['id']; ?>">
                             </div>
                            <div class="col-md-2">
                                <div class="select-appearance"> 
                                    <select name="prices[<?php echo $counter; ?>][prices][<?php echo $counter_op; ?>][year_from]" class="form-control selectpicker from_year_value">
                                      <option selected="selected" disabled="disabled">From Year</option>
                          <?php
                            // Sets the top option to be the current year. (IE. the option that is chosen by default).
                            $currently_selected = 0;//date('Y');
                            // Year to start available options at
                            $earliest_year = 1980;
                            // Set your latest year you want in the range, in this case we use PHP to just set it to the current year.
                            $latest_year = date('Y');
                            // Loops over each int[year] from current year, back to the $earliest_year [1950]
                            foreach (range($latest_year, $earliest_year) as $i) {
                                // Prints the option with the next year in range.
                                print '<option  value="' . $i . '"' . ($i == $currently_selected ?
                                    ' selected="selected"' : '') . '>' . $i . '</option>';
                            }
                            ?>
                            </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="select-appearance"> 
                                                <select name="prices[<?php echo $counter; ?>][prices][<?php echo $counter_op; ?>][year_to]"  class="form-control selectpicker to_year_value">
                                                  <option selected="selected" disabled="disabled">To Year</option>
                          <?php
                        // Sets the top option to be the current year. (IE. the option that is chosen by default).
                        $currently_selected = 0;//date('Y');
                        // Year to start available options at
                        $earliest_year = 1980;
                        // Set your latest year you want in the range, in this case we use PHP to just set it to the current year.
                        $latest_year = date('Y');
                        // Loops over each int[year] from current year, back to the $earliest_year [1950]
                        foreach (range($latest_year, $earliest_year) as $i) {
                            // Prints the option with the next year in range.
                            print '<option  value="' . $i . '"' . ($i == $currently_selected ?
                                ' selected="selected"' : '') . '>' . $i . '</option>';
                        }
                        ?>
                        </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <input class="form-control price_value" type="number" name="prices[<?php echo $counter; ?>][prices][<?php echo $counter_op; ?>][price]" value="" placeholder="Price">
                                        </div>
                                        <div class="col-md-2">
                                            <select name="prices[<?php echo $counter; ?>][prices][<?php echo $counter_op; ?>][engine_size_id]" class="form-control" data-placeholder="Choose litres">
                                              <option disabled="disabled" selected="">Select Engine Sizes</option>
                                                 <?php
                                                  foreach($get_engin_size as $engSize){
                                                    ?>
                                                     <option value="<?php echo $engSize['id']; ?>"><?php echo $engSize['title']; ?></option>
                                                    <?php
                                                  }
                                                 ?>                                            
                                              </select>
                                        </div>
                                        <div class="col-md-2">
                                            <input class="form-control price_value" type="number" name="prices[<?php echo $counter; ?>][prices][<?php echo $counter_op; ?>][depreciation]" value="" placeholder="Price">
                                        </div>
                                    </div>
                                    <?php $counter_op++; ?>
                                    <?php
                                   }
                                ?> 
                                </div>
                            <div class="form-group add_more_price_wrap"> 
                                <button type="button" onclick="click_addmore(this)" data-pricecount="<?php echo $counter_op; ?>" class="add_price" data-count="<?php echo $counter; ?>"><i class="fa fa-plus"></i> Add More</button>
                            </div>
                        </td>
                        
                        
                        
                        
                    </tr>
                       
                    <?php
                    $counter++;
                  }
                ?>
                <input type="submit" id="click_submit" style="display: none;" />
                
                </tbody>
                </table>
                </form>
              <button onclick="submit_make_prices()" class="btn btn-success">Submit</button>
              </div>
            </div>
        </div>
    </div>
</div>
<script>
  function submit_make_prices(){
    $("#click_submit").click();
  }
  function click_addmore(obj){
    
    var counter = $(obj).data("count");
    var pricecount = $(obj).attr("data-pricecount");
    //pricecount  = Number(pricecount)+1;
    alert(pricecount);
    
    var option_arr = '<?php echo json_encode($valuate_option); ?>';
    //var js_json_string = JSON.stringify(option_arr);
var js_json_array = JSON.parse(option_arr);
var puthtml = "";
for (var key in js_json_array) {
    console.log(js_json_array[key]['title']);


    
    
     puthtml += ' <div>\
                            <div class="row" style="margin-top: 10px;">\
                             <div class="col-md-2">\
                             '+js_json_array[key]['title']+'\<input type="hidden" name="prices['+counter+'][prices]['+pricecount+'][option_id]" value="'+js_json_array[key]['id']+'">\
                             </div>\
                            <div class="col-md-2">\
                                <div class="select-appearance"> \
                                    <select name="prices['+counter+'][prices]['+pricecount+'][year_from]" class="form-control selectpicker from_year_value">\
                                      <option selected="selected" disabled="disabled">From Year</option>\
                          <?php
                            // Sets the top option to be the current year. (IE. the option that is chosen by default).
                            $currently_selected = 0;//date('Y');
                            // Year to start available options at
                            $earliest_year = 1980;
                            // Set your latest year you want in the range, in this case we use PHP to just set it to the current year.
                            $latest_year = date('Y');
                            // Loops over each int[year] from current year, back to the $earliest_year [1950]
                            foreach (range($latest_year, $earliest_year) as $i) {
                                // Prints the option with the next year in range.
                                print '<option  value="' . $i . '"' . ($i == $currently_selected ?
                                    ' selected="selected"' : '') . '>' . $i . '</option>';
                            }
                            ?>\
                            </select>\
                                            </div>\
                                        </div>\
                                        <div class="col-md-2">\
                                            <div class="select-appearance"> \
                                                <select name="prices['+counter+'][prices]['+pricecount+'][year_to]" class="form-control selectpicker to_year_value">\
                                                  <option selected="selected" disabled="disabled">To Year</option>\
                          <?php
                        // Sets the top option to be the current year. (IE. the option that is chosen by default).
                        $currently_selected = 0;//date('Y');
                        // Year to start available options at
                        $earliest_year = 1980;
                        // Set your latest year you want in the range, in this case we use PHP to just set it to the current year.
                        $latest_year = date('Y');
                        // Loops over each int[year] from current year, back to the $earliest_year [1950]
                        foreach (range($latest_year, $earliest_year) as $i) {
                            // Prints the option with the next year in range.
                            print '<option  value="' . $i . '"' . ($i == $currently_selected ?
                                ' selected="selected"' : '') . '>' . $i . '</option>';
                        }
                        ?>\
                        </select>\
                                            </div>\
                                        </div>\
                                        <div class="col-md-2">\
                                            <input class="form-control price_value" type="number" name="prices['+counter+'][prices]['+pricecount+'][price]" value="" placeholder="Price">\
                                        </div>\
                                        <div class="col-md-2">\
                                            <select name="prices['+counter+'][prices]['+pricecount+'][litre]" class="form-control" data-placeholder="Choose litres">\
                                              <option disabled="disabled" selected="">Select Engine Sizes</option><?php foreach($get_engin_size as $engSize){ ?><option value="<?php echo $engSize['id']; ?>"><?php echo $engSize['title']; ?></option><?php }?>\
                                              </select>\
                                        </div>\
                                        <div class="col-md-2">\
                                            <input class="form-control price_value" type="number" name="prices['+counter+'][prices]['+pricecount+'][depreciation]" value="" placeholder="Price">\
                                        </div>\
                                    </div>\
                                    <?php ?></div>';
                                    pricecount++;
                                    }
    
  $("#model_push_"+counter).append(puthtml);
  $(obj).attr("data-pricecount",pricecount);
  }
     $('#demo-form2').parsley().on('field:validated', function() {
            var ok = $('.parsley-error').length === 0;
        }) .on('form:submit', function() {
        return false; // Don't submit form for this demo
    });
</script>