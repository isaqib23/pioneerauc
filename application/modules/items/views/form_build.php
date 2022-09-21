<a type="button" href="<?php echo base_url().'items/categories'; ?>"  class="btn btn-info"><i class="fa fa-arrow-left"></i> Back categories List</a>
<div class="col-md-12">
  <p style="color: red">
    <label>Note: </label><br>
    <b>1: Field name should not contain empty spaces and dashes (-) </b><br>
    <b>2: After save field and on update do not change the existed field names </b><br>
    <b>3: Use only UTF-8 characters </b><br>
  </p>
</div>
<div id="build-wrap"></div>
<input type="hidden" id="category_id" value="<?php echo $category_id; ?>" />
<?php if(count($fields_data) >0) { ?>
<div><input type="button" value="Update Fields"  class="btn btn-primary" id="update_fields" name=""> </div>
 <?php } else{ ?>
<div><input type="button" value="Save Fields"  class="btn btn-primary" id="showData" name=""> </div>
 <?php } ?> 
      <script src="<?php echo base_url();?>assets_admin/js/formBuilder/jquery-ui.min.js"></script>
      <script src="<?php echo base_url();?>assets_admin/js/formBuilder/starRating.js"></script>
      <script src="<?php echo base_url();?>assets_admin/js/formBuilder/form-builder.min.js"></script>
      <script src="<?php echo base_url();?>assets_admin/js/formBuilder/textarea.trumbowyg.js"></script>
      
  <script>
    var token_name = '<?= $this->security->get_csrf_token_name();?>';
    var token_value = '<?=$this->security->get_csrf_hash();?>';
    var data_values_arr = [];
    var data_json_arr = '<?php echo (!empty($fields_data)) ? addslashes(json_encode($fields_data)) : ""; ?>';
    console.log(data_json_arr);
    if(data_json_arr){
      data_values_arr = $.parseJSON(data_json_arr);
      var lim = data_values_arr.length;
      for (var i = 0; i < lim; i++)
      {
        if (data_values_arr[i].id != '')
        { 
          data_values_arr[i].name = data_values_arr[i].id+'-'+data_values_arr[i].name;
        }
      }
    }
    var options = {
      // inputSets: [
      //   {
      //     label: 'User Details',
      //     name: 'user-details', // optional - one will be generated from the label if name not supplied
      //     showHeader: true, // optional - Use the label as the header for this set of inputs
      //     fields: [
      //       {
      //         type: 'text',
      //         name: 'qadeer',
      //         label: 'First Name',
      //         className: 'form-control'
      //       }
      //     ]
      //   },
      //   {
      //     label: 'User Agreement',
      //     fields: [
      //       {
      //         type: 'header',
      //         subtype: 'h2',
      //         label: 'Terms &amp; Conditions',
      //         className: 'header'
      //       }
      //     ]
      //   }
      // ],
      // fieldsInputs:'<div class="form-group arabic-label-wrap" style="display: block"><label for="arabic-label-frmb-1603980174485-fld-13">Label arabic</label><div class="input-wrap"><div name="label" placeholder="Label" class="fld-label form-control" id="arabic-label-frmb-1603980174485-fld-13" contenteditable="true">arabic Text Field</div></div></div>' ,
      // subtypes: { text: ['datetime-local'] },
      formData: data_values_arr,
      dataType: 'json',
      fieldRemoveWarn: true,
      disableFields: ['autocomplete','button','file','header','hidden','paragraph','starRating'],
      disabledActionButtons: ['data','save'],
    };
    
    var fbEditor = document.getElementById("build-wrap");
    // var formBuilder = $(fbEditor).formRender(options);
    var formBuilder = $(fbEditor).formBuilder(options);
    var save = document.getElementById('showData');

    if(save){
      save.addEventListener('click', function() {
        var url = '<?php echo base_url();?>';
        var data = formBuilder.actions.getData();
        $.ajax({
                url: url + 'items/save_form/?category_id='+$("#category_id").val(),
                type: 'POST',
                data: {'formData':data, [token_name]:token_value},
                success:function(resp){
                window.location = url + 'items/categories'
                }
              });
      });
    }
     
      var fields = document.getElementById('update_fields');
      if(fields){
        fields.addEventListener('click', function() {
          var url = '<?php echo base_url();?>';
          var data = "";
          var data = formBuilder.actions.getData();
          // console.log(window.sessionStorage);
          $("#fields_id").each(function(){
            var data = $('field_id').val();  
          });
          $.ajax({
              url: url + 'items/update_form?category_id='+$("#category_id").val(),
              type: 'POST',
              data: {'formData':data, [token_name]:token_value},
              success:function(resp){
                window.location = url + 'items/categories'
              }
          });
      });
     }
     
</script>
