<style type="text/css">
  #depriciation{line-height: 40px; position: absolute; right: 120px; top: 0; bottom: 0;
  -moz-transition: 0.3s right ease;
  -ms-transition: 0.3s right ease;
  -o-transition: 0.3s right ease;
  -webkit-transition: 0.3s right ease;
  transition: 0.3s right ease;
  z-index: 0}
</style>
<!-- PNotify -->
    <link href="<?php echo base_url(); ?>assets_admin/vendors/pnotify/dist/pnotify.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets_admin/vendors/pnotify/dist/pnotify.buttons.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets_admin/vendors/pnotify/dist/pnotify.nonblock.css" rel="stylesheet">

<div class="x_title">
  <h2><?php  echo $title; ?></h2>
  <div class="clearfix"></div>
</div>

<div id="result"></div>
<div class="container" style="padding-top: 10px">
  <form method="post" action="<?=  ($title == 'Edit Models') ? base_url('cars/update_models'):base_url('cars/save_models');?>" class="form-horizontal form-label-left">
    <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
               
    <?php if($title == 'Edit Models' ){  
      $model_title = json_decode($models_list[0]['title']);
      ?>
      <input type="hidden" name="id" id="" value="<?php echo $this->uri->segment(3); ?>">
    <?php } ?>
    <?php if ($this->session->flashdata('msg')) {?>
      <div class="alert">
        <div class="alert alert-domain alert-danger alert-dismissible fade in" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true" style="color: black;">×</span>
          </button>
          <?php echo $this->session->flashdata('msg'); ?>
        </div>
      </div>
    <?php }?>

    <div class="item form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="make_idd">Make <span class="required">*</span></label>
      <div class="col-md-6 col-sm-6 col-xs-12">
        <select class="form-control select_withoutcross" id="make_idd" name="valuation_make_id">
          <option  value="">Select Make</option>
          <?php foreach ($makes_list as $key => $value) { ?>
            <option <?php if(count($models_list) >0){ if($value['id']==$models_list[0]['valuation_make_id']){echo "selected";}
            // print_r($models_list);die();
            $title = json_decode($models_list[0]['title']);
             }?>
              value="<?php echo $value['id']; ?>">
                <?php
                $title = json_decode($value['title']);
                 echo $title->english; ?>
            </option>
          <?php } ?>
        </select>
        <div class="valuation_make_id-error text-danger"></div>
      </div>
    </div>
    
          
    <div class="item form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">English Title <span class="required">*</span></label>
      <div class="col-md-6 col-sm-6 col-xs-12">
        <input type="text"  
          id="title" 
          name="title" 
          maxlength="150" 
          value="<?php if(count($models_list) >0){ if(isset($model_title) && !empty($model_title)){echo $model_title->english;}}?>" 
          class="form-control col-md-7 col-xs-12" />
        <div class="title-error text-danger"></div>
      </div>
    </div>

    <div class="item form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Arabic Title <span class="required">*</span></label>
      <div class="col-md-6 col-sm-6 col-xs-12">
        <input type="text"  
          id="title" 
          dir="rtl"
          name="title_arabic" 
          maxlength="150" 
          value="<?php if(count($models_list) >0){ if(isset($model_title) && !empty($model_title)){echo $model_title->arabic;} }?>" 
          class="form-control col-md-7 col-xs-12" />
        <div class="title-error text-danger"></div>
      </div>
    </div>

    <div class="item form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="valuation_engine_size">Engine Size  <span class="required">*</span></label>
      <div id="myModal" class="col-md-6 col-sm-6 col-xs-12">
          <select class="form-control select_withoutcross js-example-basic-multiple" id="valuation_engine_size" name="valuation_engine_size_id[]" multiple>
              <?php 
               $engin_arr = explode(",",$models_list[0]['valuation_engine_size_id']); ?>
              <option value="">Select Engine Size</option>     
              <?php foreach ($eng_size_list as $key => $value) { ?>
               <option <?php if(count($models_list) >0){ if(in_array($value['id'], $engin_arr)){echo "selected";} }?>
                value="<?php echo $value['id']; ?>"><?php echo $value['title']; ?></option>
               <?php  } ?>
          </select>
          <div class="valuation_engine_size_id-error text-danger"></div>
      </div>
    </div>
          
    <div class="item form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="valuation_engine_size"> Year Depreciation  <span class="required">*</span></label>
      <div id="myModal" class="col-md-6 col-sm-6 col-xs-12">
        
        <div  id="year_div">
          <?php 
          if(count($valuation_year)==0){
            $valuation_year = array(array('year_from'=>0,'year_to'=>0,'year_depreciation'=>"")); 
          }
          
          foreach($valuation_year as $years){
            ?>
            <div class="item form-group">
              <div class="col-md-6 col-sm-4 col-xs-4 form-group has-feedback">
                <select name="year[]" class="form-control" required="">
                  <option selected="" disabled="" value="">Select Year</option>
                  <?php
                    // Sets the top option to be the current year. (IE. the option that is chosen by default).
                    $currently_selected = $years['year'];//date('Y');
                    // Year to start available options at
                    $earliest_year = 1990; 
                    // Set your latest year you want in the range, in this case we use PHP to just set it to the current year.
                    $latest_year = date('Y'); 
                    // Loops over each int[year] from current year, back to the $earliest_year [1950]
                    foreach ( range( $latest_year, $earliest_year ) as $i ) {
                      // Prints the option with the next year in range.
                      echo '<option  value="'.$i.'"'.($i == $currently_selected ? ' selected="selected"' : '').'>'.$i.'</option>';
                    }
                  ?>
                </select>
                <div class="year-error text-danger"></div>
              </div>

                  
              <div class="col-md-6 col-sm-4 col-xs-4 form-group has-feedback">
                <input type="number" 
                  name="year_depreciation[]" 
                  value="<?php echo $years['year_depreciation']; ?>" 
                  class="form-control" 
                  id="percentage" 
                  placeholder="Type only number" />

                <?php if(isset($years['id']) && !empty($years['id'])){ ?>
                  <a href="javascript:void(0);" 
                    style="margin-left: -260px;" 
                    id="<?= (isset($years['id']) && !empty($years['id'])) ? $years['id'] : '';?>" 
                    onclick="remove_existed_years(this)" 
                    class="remove_button">Remove
                  </a>
                <?php } ?>
                <div class="year_depreciation-error text-danger"></div>
              </div>
            </div>
          <?php } ?>
        </div>
          
        <div class="col-md-4 col-sm-4 col-xs-4 form-group has-feedback">
          <button type="button" onclick="add_more_years()" class="add_more_year add_button" ><i class="fa fa-plus"></i> Add more</button>
        </div>
      </div>
    </div>

    <div class="item form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="status">Status</label>
      <div class="col-md-6 col-sm-6 col-xs-12">
        <select class="form-control select_withoutcross" id="status" name="status">
          <option <?php if(count($models_list) >0 && $models_list[0]['status'] == 1){ echo 'selected'; }?>  value="1">Active</option>
          <option <?php if(count($models_list) >0 && $models_list[0]['status'] == 0){ echo 'selected'; }?> value="0">Inactive</option>
        </select>
        <div id="status-error"></div>
      </div>
    </div>
          
    <div class="ln_solid"></div>
    
    <div class="form-group">
      <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
        <input type="submit" id="send" class="btn btn-success" value="<?= ($title == 'Edit Models') ? 'Update':'Submit'; ?>">
        <a href="<?php echo base_url().'cars/models'; ?>" class="btn btn-primary">Cancel</a>
      </div>
    </div>

  </form>
</div>

<!-- ///// PNotify  -->
<script src="<?php echo base_url(); ?>assets_admin/vendors/pnotify/dist/pnotify.js"></script>
<script src="<?php echo base_url(); ?>assets_admin/vendors/pnotify/dist/pnotify.buttons.js"></script>
<script src="<?php echo base_url(); ?>assets_admin/vendors/pnotify/dist/pnotify.nonblock.js"></script>

<script>
$('#send').on('click', function(event){
  event.preventDefault();
  var validation = false;
  selectedInputs = ['valuation_make_id','title','"valuation_engine_size_id[]"','"year[]"','"year_depreciation[]"','status'];
  validation = validateFields(selectedInputs);
  if(validation == false){
    return false;
  }
  if(validation == true){
    $(this).closest("form").submit();
    //console.log($(this).closest("form").serializeArray());
  }
});

var select2Oz = remoteSelect2({
  'selectorId' : '#make_id',
  'placeholder' : 'Makes',
  'table' : 'valuation_make',
  'values' : 'Name',
  'width' : '200px',
  'delay' : '250',
  'cache' : false,
  'minimumInputLength' : 1,
  'limit' : 5,
  'url' : '<?= base_url();?>cars/make_list_api'
});
         
$('.js-example-basic-multiple').select2();

//var ok;
//var url = '<?php echo base_url();?>';
//var formaction_path = '<?php echo $formaction_path;?>';

/*$(document).ready(function() {
  $('#demo-form2').parsley().on('field:validated', function() {
    var ok = $('.parsley -error').length === 0;
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
                            window.location = url + 'cars/models';
                        }else{
                            $('.msg-alert').css('display', 'block');
                            $("#result").html('<div class="alert" ><div class="alert alert-domain alert-danger alert-dismissible fade in  msg-alert" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: black"><span aria-hidden="true">×</span> </button>' + objData.msg + '</div></div>');
                            window.scrollTo({top: 0, behavior: "smooth"});
 
                        }
                    });



    return false; // Don't submit form for this demo
  });
    });*/




 function add_more_years(){
  var addButton = $('.add_more_mileage');
    var wrapper = $('#year_div');
    var fieldHTML = '<div class="item form-group"><div class="col-md-6 col-sm-4 col-xs-4 form-group has-feedback">\
                        <select name="year[]" class="form-control">\
                            <option selected="" disabled="">Select Year</option>\
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
                      <div class="col-md-6 col-sm-4 col-xs-4 form-group has-feedback">\
                        <input type="number" name="year_depreciation[]" class="form-control" placeholder="Type only number">\
                      <a href="javascript:void(0);" style="margin-left: -260px;" onclick="remove_more_years(this)" class="remove_button">Remove</a></div></div>'; 
                        //New input field html 
                      $(wrapper).append(fieldHTML); //Add field html
  }

function remove_more_years(obj){
    $(obj).parent('div').parent('div').remove(); //Remove field html
}


function remove_existed_years(obj){
  var year_id = $(obj).attr('id');
  console.log(year_id);
    $.ajax({    //create an ajax request to display.php
      type: "post",
      url: "<?php echo base_url(); ?>" + "cars/remove_existed_years/",    
      data:{ year_id:year_id, "<?= $this->security->get_csrf_token_name();?>":"<?=$this->security->get_csrf_hash();?>" },
      beforeSend: function(e){
      },
      success: function(data) {
          var objData = jQuery.parseJSON(data);
      if (objData.error == false) 
      {
        
        $(obj).parent('div').parent('div').remove(); //Remove field html
        new PNotify({
            title: 'Success',
            text: ""+objData.response+"",
            type: 'success',
            addclass: 'custom-success',
            styling: 'bootstrap3'
        });
      }else{
         if(objData.response != ''){
          new PNotify({
              title: 'Error!',
              text: ""+objData.response+"",
              type: 'error',
              addclass: 'custom-error',
              styling: 'bootstrap3'
          }); 
         }
         
      }
      }
    }); 

  }
        
</script>