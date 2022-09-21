

<style type="text/css">
    #page-wrapper {
        margin-top: 100px;
    }
</style>
<div class="clearfix"></div>
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
        <?php if ($this->session->flashdata('msg')) {?>
            <div class="alert">
                <div class="alert alert-domain alert-success alert-dismissible fade in" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true" style="color: black;">x</span>
                      </button>
                    <?php echo $this->session->flashdata('msg'); ?>
                </div>
            </div>
            <?php }?>
            <br />
            
             <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
              <form id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
                <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Mileage <small>Configure deprciation % on basis of mileage</small></h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    
                   <div  id="mileage_div">
                     <?php
                     foreach($valuation_milage as $millage){
                      ?>
                      <div class="col-md-6 col-sm-6 col-xs-6 form-group has-feedback">
                      <select name="mileage_from[]" id="mileage_from" class="form-control mileage_from">
                         <option selected="" disabled="" >Choose Mileage</option> 
                          <?php foreach($mileage_list as $mill){
                          ?>
                         <option value="<?php echo $mill['id']; ?>" <?php if($millage['mileage_id']==$mill['id']){echo "selected";} ?> ><?php echo $mill['mileage_label']; ?></option>
                      <?php
                        }
                      ?>
                      </select>
                    </div> 
                    <div class="col-md-6 col-sm-6 col-xs-6 form-group has-feedback">
                      <input type="number" name="mileage_depreciation[]" value="<?php echo $millage['millage_depreciation'] ?>" class="form-control" placeholder="Depreciation %">
                    </div> 
                      <?php
                      
                     }
                     ?> 
                  </div>
                      
                      <div class="col-md-4 col-sm-4 col-xs-4 form-group has-feedback">
                        <button type="button" onclick="add_more_mileage()" class="add_more_mileage add_button" ><i class="fa fa-plus"></i> Add more</button>
                      </div>
                     

                    
                  </div>
                </div>
                
                
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Paint <small>Configure depreciation % on basis of paint condition</small></h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    
                       <div>
                       <?php
                       $edit_id = 0;
                       if(isset($valuation_config[0])){
                       $valuation_config = $valuation_config[0];
                       $edit_id = 1;
                       }
                       if(isset($valuation_config['config_setting_paint'])){
                       $valuation_paint = json_decode($valuation_config['config_setting_paint'],1);
                       }
                       
                       if(isset($valuation_config['config_setting_specs'])){
                       $valuation_specs = json_decode($valuation_config['config_setting_specs'],1);
                       }
                       
                       if(isset($valuation_config['config_option'])){
                       $valuation_option = json_decode($valuation_config['config_option'],1);
                       }
                       
                       if(isset($valuation_config['litre'])){
                       $valuation_litre = json_decode($valuation_config['litre'],1);
                       }
                       
                       ?>
                       <input type="hidden" name="editid" value="<?php echo $edit_id; ?>" />
                       <div class="col-md-4 col-sm-4 col-xs-4 form-group has-feedback">
                        <label class="control-label" for="title">Original 
                        </label>
                        <input type="number" name="paint[Original]" value="<?php if(isset($valuation_paint)){echo $valuation_paint['Original'];} ?>" class="form-control" placeholder="Original">
                      </div>
                      <div class="col-md-4 col-sm-4 col-xs-4 form-group has-feedback">
                        <label class="control-label" for="title">Partial 
                        </label>
                        <input type="number" name="paint[Partial]" value="<?php if(isset($valuation_paint)){echo $valuation_paint['Partial'];} ?>" class="form-control" placeholder="Partial">
                      </div>
                      <div class="col-md-4 col-sm-4 col-xs-4 form-group has-feedback">
                        <label class="control-label" for="title">Total Repaint 
                        </label>
                        <input type="number" name="paint[Total_Repaint]" value="<?php if(isset($valuation_paint)){echo $valuation_paint['Total_Repaint'];} ?>" class="form-control" placeholder="Total Repaint">
                      </div>
                      </div>
                      
                     

                    
                  </div>
                </div>
                
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Specs <small>Configure deprciation % on basis of specs</small></h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    
                       <div >
                       <div class="col-md-4 col-sm-4 col-xs-4 form-group has-feedback">
                          <label class="control-label" for="title">GCC 
                          </label>
                        <input type="number" value="<?php if(isset($valuation_specs)){echo $valuation_specs['GCC'];} ?>" name="specs[GCC]" class="form-control" placeholder="GCC">
                      </div>
                        <div class="col-md-4 col-sm-4 col-xs-4 form-group has-feedback">
                          <label class="control-label" for="title">Non GCC 
                          </label>
                        <input type="number" value="<?php if(isset($valuation_specs)){echo $valuation_specs['Non_GCC'];} ?>" name="specs[Non_GCC]" class="form-control" placeholder="Non GCC">
                      </div>
                      </div>
                       
                  </div>
                </div>
                
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Option <small>Configure deprciation % on basis of options</small></h2>
                    <div class="clearfix"></div>
                  </div>
                  <!-- <div class="x_content">
                       <div>
                        <?php //foreach($option as $key => $value) { ?>
                       <div class="col-md-4 col-sm-4 col-xs-4 form-group has-feedback">
                          <label class="control-label" for="title"><?php //echo $value['title']; ?> 
                          </label>
                          <input type="text"  class="form-control" value=" <?php //if(isset($valuation_option[$value['title']])){echo $valuation_option[$value['title']] ;} ?>" class="flat" name="option[<?php //echo $value['title']; ?>]" placeholder="<?php //echo $value['title'] ?>" >
                        </div>
                            <?php

                        // }?> 
                      </div> 
                  </div> -->
                  <div class="x_content">
                    <div >
                     <div class="col-md-4 col-sm-4 col-xs-4 form-group has-feedback">
                        <label class="control-label" for="basic">Basic 
                        </label>

                      <input type="number" value="<?php if(isset($valuation_option)){ echo trim($valuation_option['Basic']);} ?>" name="option[Basic]" class="form-control" placeholder="Basic">
                    </div>
                      <div class="col-md-4 col-sm-4 col-xs-4 form-group has-feedback">
                        <label class="control-label" for="mid">Mid 
                        </label>
                      <input type="number" value="<?php if(isset($valuation_option)){echo trim($valuation_option['Mid']);} ?>" name="option[Mid]" class="form-control" placeholder="Mid">
                    </div>
                      <div class="col-md-4 col-sm-4 col-xs-4 form-group has-feedback">
                        <label class="control-label" for="full">Full
                        </label>
                      <input type="number" value="<?php if(isset($valuation_option)){echo trim($valuation_option['Full']);} ?>" name="option[Full]" class="form-control" placeholder="Full">
                    </div>
                  </div>
                </div>

                </div>
                
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Litre </h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    
                       <div >
                       <div class="col-md-4 col-sm-4 col-xs-4 form-group has-feedback">
                        <label class="control-label" for="title">Liter
                          </label>
                        <input type="text" value="<?php if(isset($valuation_litre)){echo $valuation_litre['Litre'];} ?>" name="litre[Litre]" class="form-control" placeholder="Liter">
                      </div>
                       
                      </div>
                      
                     

                    
                  </div>
                </div>
                
                <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                          <button type="button" id="sendbtn" class="btn btn-success">Submit</button>
                        </div>
                </div>
                
                </form>
              </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

var url = '<?php echo base_url(); ?>';
    var formaction_path = '<?php echo $formaction_path; ?>';
    $(document).ready(function() {
        // You can use the locally-scoped $ in here as an alias to jQuery.
        $(function() {
            $("#sendbtn").on('click', function(e) { //e.preventDefault();
                var formData = new FormData($("#demo-form2")[0]);
                    $.ajax({
                        url: url + 'cars/' + formaction_path,
                        type: 'POST',
                        data: formData,
                        cache: false,
                        contentType: false,
                        processData: false
                    }).then(function(data) {
                        console.log(data);
                        if (data == 'success') {
                            window.location = url + 'cars/evaluation_config';
                        } else {
                            $('.msg-alert').css('display', 'block');
                            $("#result").html('<div class="alert" ><div class="alert alert-domain alert-danger alert-dismissible fade in  msg-alert" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true" style="color: black;">×</span> </button>' + data + '</div></div>');
                        }
                    }); 
            });
        });
    });
 
function remove_more_years_luxury(obj){
    
    $(obj).parent('div').remove(); //Remove field html
                       
}
 
//add more year for economy

function add_more_years_economy(){
  var addButton = $('.add_more_mileage'); //Add button selector
    var wrapper = $('#year_div_economy'); //Input field wrapper
    var fieldHTML = '<div><div class="col-md-4 col-sm-4 col-xs-4 form-group has-feedback">\
    <select name="year_from_economy[]" class="form-control">\
        <option selected="" disabled="">Year range from</option>\
        <?php
          // Sets the top option to be the current year. (IE. the option that is chosen by default).
          $currently_selected = 0;//date('Y'); 
          // Year to start available options at
          $earliest_year = 1990; 
          // Set your latest year you want in the range, in this case we use PHP to just set it to the current year.
          $latest_year = date('Y'); 
          // Loops over each int[year] from current year, back to the $earliest_year [1950]
          foreach ( range( $latest_year, $earliest_year ) as $i ) {
            // Prints the option with the next year in range.
            print '<option  value="'.$i.'"'.($i == $currently_selected ? ' selected="selected"' : '').'>'.$i.'</option>';
          }
        ?>
       </select>\
  </div>\
    <div class="col-md-4 col-sm-4 col-xs-4 form-group has-feedback">\
    <select name="year_to_economy[]" class="form-control">\
        <option selected="" disabled="">Year range to</option>\
        <?php
          // Sets the top option to be the current year. (IE. the option that is chosen by default).
          $currently_selected = 0;//date('Y'); 
          // Year to start available options at
          $earliest_year = 1990; 
          // Set your latest year you want in the range, in this case we use PHP to just set it to the current year.
          $latest_year = date('Y'); 
          // Loops over each int[year] from current year, back to the $earliest_year [1950]
          foreach ( range( $latest_year, $earliest_year ) as $i ) {
            // Prints the option with the next year in range.
            print '<option  value="'.$i.'"'.($i == $currently_selected ? ' selected="selected"' : '').'>'.$i.'</option>';
          }
        ?>
    </select>\
  </div>\
  <div class="col-md-4 col-sm-4 col-xs-4 form-group has-feedback">\
    <input type="number" name="year_depreciation_economy[]" class="form-control" placeholder="Depreciation">\
  </div><a href="javascript:void(0);" onclick="remove_more_years_economy(this)" class="remove_button">Remove</a></div>'; 
    //New input field html 
  $(wrapper).append(fieldHTML); //Add field html
   }
  function remove_more_years_economy(obj){
    $(obj).parent('div').remove(); //Remove field html
  }



// add more millage functions 

function add_more_mileage(){
  var addButton = $('.add_more_mileage'); //Add button selector
    var wrapper = $('#mileage_div'); //Input field wrapper
    var fieldHTML = '<div><div class="col-md-6 col-sm-6 col-xs-6 form-group has-feedback">\
      <select name="mileage_from[]" id="mileage_from" class="form-control mileage_from">\
        <option selected="" disabled="">Mileage From</option>\
          <?php foreach($mileage_list as $mill){
              ?>
               <option value="<?php echo $mill['id']; ?>"><?php echo $mill['mileage_label']; ?></option>\
              <?php
            } ?>
      </select>\
    </div>\
    <div class="col-md-6 col-sm-6 col-xs-6 form-group has-feedback">\
      <input type="number" name="mileage_depreciation[]" class="form-control" placeholder="Depreciation %">\
    </div><a style="padding-left: 10px; margin-top:20px" href="javascript:void(0);" onclick="remove_more_mileage(this)" class="remove_button">Remove</a></div><br>'; 
      //New input field html 
    $(wrapper).append(fieldHTML); //Add field html
  }
  function remove_more_mileage(obj){
      $(obj).parent('div').remove(); //Remove field html
  }

</script>
