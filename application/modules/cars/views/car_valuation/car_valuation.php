

  <div class="x_title">
        <h2>Car Valuation</h2>
        <div class="clearfix"></div>
    </div>
  
  <style>
  .box
  {
   width:800px;
   margin:0 auto;
  }
  .active_tab1
  {
   background-color:#fff;
   color:#333;
   font-weight: 600;
  }
  .inactive_tab1
  {
   background-color: #f5f5f5;
   color: #333;
   cursor: not-allowed;
  }
  .has-error
  {
   border-color:#cc0000;
   background-color:#ffff99;
  }
  </style>
     <?php if ($this->session->flashdata('msg')) {?>
        <div class="alert">
            <div class="alert alert-domain alert-success alert-dismissible fade in  msg-alert" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true" style="color: black;">x</span>
                  </button>
                <?php echo $this->session->flashdata('msg'); ?>
            </div>
        </div>
        <?php }?>  

   <form method="post" name="myForm" action="<?php echo base_url(); ?>cars/car_valuation_results" id="register_form" class="form-horizontal form-label-left">
    <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
    <ul class="nav nav-tabs">
     <li class="nav-item">
      <a class="nav-link active_tab1" style="border:1px solid #ccc" id="list_login_details">Select Your Car</a>
     </li>
     <li class="nav-item">
      <a class="nav-link inactive_tab1" id="list_personal_details" style="border:1px solid #ccc">Model Condition</a>
     </li>
    </ul>
    <div class="tab-content" style="margin-top:16px;">
     <div class="tab-pane active" id="login_details">
      <div class="panel panel-default">
       <div class="panel-heading">Car Valuation</div>
       <div class="panel-body">
      

        <div class="form-group-result">
       <!-- <div class="alert" id="show_alert" style="display: none;">
            <div class="alert alert-domain alert-danger alert-dismissible fade in" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: black"><span aria-hidden="true">×</span>
                  </button>
                All Fields Are Required
            </div>
        </div> -->
          </div>
          <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="specialization_id">Make <span class="required">*</span></label>
                
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <select required="" class="form-control select_withoutcross" id="make_id" name="valuation_make_id">
                    
                        <option  value="">Select Make</option>
                    
                        <?php foreach ($makes_list as $key => $value) { ?>
                        <option value="<?php echo $value['id']; ?>"><?php echo $value['title']; ?></option>
                            <?php  } ?>
                    </select>
                </div>
            </div>

            <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="valuation_model_id">Model <span class="required">*</span></label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                <select required="" required="required" onchange="model_change()" name="valuation_model_id" id="valuation_model_id" class="form-control col-md-7 col-xs-12"></select>
               </div>
            </div>
           

            <div class="item form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="years">Year <span class="required">*</span></label>
            <!-- <div class="col-md-4 col-sm-4 col-xs-4 form-group has-feedback"> -->
            <div class="col-md-6 col-sm-6 col-xs-12 has-feedback">
                        <select name="year_to" id="year_to" class="form-control">
                            <option selected=""  value="">Year range to</option>
                            <?php
                              // Sets the top option to be the current year. (IE. the option that is chosen by default).
                              $currently_selected = '';//date('Y');//$years['year_to'];//; 
                              // Year to start available options at
                              $earliest_year = 1980; 
                              // Set your latest year you want in the range, in this case we use PHP to just set it to the current year.
                              $latest_year = date('Y'); 
                              // Loops over each int[year] from current year, back to the $earliest_year [1950]
                              foreach ( range( $latest_year, $earliest_year ) as $i ) {
                                // Prints the option with the next year in range.
                                print '<option  value="'.$i.'"'.($i == $currently_selected ? ' selected="selected"' : '').'>'.$i.'</option>';
                              }
                            ?>
                        </select>
                      </div>
                  </div>
              </div>


        
        <br />
        <div align="center">
         <button type="button" style="display: none;"  name="btn_login_details" id="btn_login_details" class="btn btn-info btn-lg">Next</button>
         <button type="button" onclick="check_validate()"class="btn btn-info btn-lg">Next</button>
        </div>
        <script>
          function check_validate(){
           if($("#make_id").val()!="" && $("#valuation_model_id").val()!="" && $("#year_to").val()!=""){
            //alert($("#year_to").val());
            $("#btn_login_details").click();
            // $("#show_alert").hide();
            // $('.form-group-result').html();
            $(".form-group-result").html('<div class="alert" ><div class="alert alert-domain alert-danger alert-dismissible fade in  msg-alert" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: black"><span aria-hidden="true">x</span> </button>All Fields Are Required</div></div>');
           }
           else{
            // $("#show_alert").show();
            $(".form-group-result").html('<div class="alert" ><div class="alert alert-domain alert-danger alert-dismissible fade in  msg-alert" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: black"><span aria-hidden="true">x</span> </button>All Fields Are Required</div></div>');
           } 
          }
        </script>
        <br />
       </div>
      </div>
     </div>
     <div class="tab-pane fade" id="personal_details">
      <div class="panel panel-default">
       <div class="panel-heading">
            Please enter the following information to see your car valuation.</div>
       <div class="panel-body">
        
          
         <span id="error_makes" class="text-danger"></span>
        </div>


        <div class="form-group">

          <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="specialization_id">Milleage      <span class="required">*</span></label>
                
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <select required="" class="form-control select_withoutcross" id="milleage_id" name="valuation_milleage_id">
                    
                        <option  value="">Select Milleage</option>
                    
                        <?php foreach ($milleage_list as $key => $value) { ?>
                        <option 
                            value="<?php echo $value['id']; ?>"><?php echo $value['milleage']; ?></option>
                            <?php  } ?>
                    </select>
                </div>
            </div>

            <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Engine Size <span class="required"></span>
                </label>

                <div class="col-md-6 col-sm-6 col-xs-12">
                    
                    <select required="required" id="valuation_engine_size" name="engine_size_id" class="form-control col-md-7 col-xs-12"></select>
                </div>
            </div>

          <div class="item form-group">
          <label class="control-label col-md-3 col-sm-3 col-xs-12"></span>
                </label>
              <div class="col-sm-6">
                  <div class="btn-group">
                  <input type="hidden" name="valuate_option" id="set_option" />
                  <!-- ngRepeat: option in dropdowns.option -->
                      <?php foreach($option_list as $option){
                        ?>
                         <button onclick="change_opt(<?php echo $option['id']; ?>)" type="button" class="btn btn-default ng-pristine ng-valid ng-binding ng-scope ng-touched">
                         <?php echo $option['title']; ?>
                         </button>  
                        <?php
                        
                      } ?>
                      
                   </div>
               </div>
             </div>

              <div class="item form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12"></span>
                </label>
              <input type="hidden" name="valuate_paint" id="set_paint" />
                  <div class="col-sm-6">
                      <div class="btn-group" >
                        <button type="button"  onclick="change_paint(this.id)" id='Original' class="btn btn-default ng-pristine ng-valid ng-binding ng-scope ng-touched  " >
                          Original paint
                        </button>
                        <button type="button" onclick="change_paint(this.id)" id='Partial' class="btn btn-default ng-pristine ng-untouched ng-valid ng-binding ng-scope " >
                          Partial Repaint
                        </button>
                        <button type="button" onclick="change_paint(this.id)" id="Total_Repaint" class="btn btn-default ng-pristine ng-untouched ng-valid ng-binding ng-scope " >
                          Total Repaint
                        </button>
                      </div>
                  </div>
              </div>




<div class="item form-group">
                  <label class="control-label col-md-3 col-sm-3 col-xs-12"></span>
                </label>
                 <input type="hidden" name="valuate_gc" id="set_gc" />
              <div class="col-sm-6">
                  
                    <!-- ngRepeat: specs in dropdowns.gcc_specs --><button onclick="change_gc(this.id)" type="button" id="GCC" class="btn btn-default ng-pristine ng-untouched ng-valid ng-binding ng-scope" >
                      GCC Specs
                    </button><!-- end ngRepeat: specs in dropdowns.gcc_specs --><button onclick="change_gc(this.id)" id="Non_GCC" type="button" class="btn btn-default ng-pristine ng-untouched ng-valid ng-binding ng-scope" >
                      Non GCC Specs
                    </button><!-- end ngRepeat: specs in dropdowns.gcc_specs --><button onclick="change_gc(this.id)" id="other" type="button" class="btn btn-default ng-pristine ng-untouched ng-valid ng-binding ng-scope" >
                      I don't know
                    </button><!-- end ngRepeat: specs in dropdowns.gcc_specs -->
                  </div>
               </div>

               <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Email <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input type="email"  maxlength="150" id="title" required title="please add something" name="email" class="form-control col-md-7 col-xs-12"> 
                </div>
            </div>


                <br />
                <div align="center">
                    
                    <button type="submit" name="car_valuation" style="background-color:orange;" id="car_valuation" class="btn btn-orange btn-lg ">Evaluate Your Car</button>
                </div>
                
            </div>
      </div>
     </div>
   </form>
  </div>
 </body>


<script>

function change_gc(valgc){
    $("#GCC").removeClass("btn-success");  
    $("#Non_GCC").removeClass("btn-success");  
    $("#other").removeClass("btn-success");  
  if(valgc == 'GCC'){
  $("#"+valgc).addClass("btn-success"); 
   $("#valuate_gc").val(valgc); 
  }
  else if(valgc == 'Non_GCC')
  {
  $("#"+valgc).addClass("btn-success");  
   $("#valuate_gc").val(valgc); 
  }else if(valgc == 'other')
  {
  $("#"+valgc).addClass("btn-success");  
   $("#valuate_gc").val(valgc); 
  }else
  {
  $("#"+valgc).removeClass("btn-success");  
  }
}

function change_paint(valgc){
  $("#Original").removeClass("btn-success");  
  $("#Partial").removeClass("btn-success");  
  $("#Total_Repaint").removeClass("btn-success");
  
  if(valgc == 'Original'){
  $("#"+valgc).addClass("btn-success"); 
   $("#set_paint").val(valgc); 
  }
  else if(valgc == 'Partial')
  {
  $("#"+valgc).addClass("btn-success");  
  $("#set_paint").val(valgc);
  }
  else if(valgc == 'Total_Repaint')
  {
  $("#"+valgc).addClass("btn-success");  
  $("#set_paint").val(valgc);
  }
  else
  {
  $("#"+valgc).removeClass("btn-success");  
  } 
}

function change_opt(valgc){
  $("#set_option").val(valgc);  
}

$(document).ready(function(){
 
 $('#btn_login_details').click(function(){
  
 
   $('#list_login_details').removeClass('active active_tab1');
   $('#list_login_details').removeAttr('href data-toggle');
   $('#login_details').removeClass('active');
   $('#list_login_details').addClass('inactive_tab1');
   $('#list_personal_details').removeClass('inactive_tab1');
   $('#list_personal_details').addClass('active_tab1 active');
   $('#list_personal_details').attr('href', '#personal_details');
   $('#list_personal_details').attr('data-toggle', 'tab');
   $('#personal_details').addClass('active in');
 });

 
 $('#previous_btn_personal_details').click(function(){
  $('#list_personal_details').removeClass('active active_tab1');
  $('#list_personal_details').removeAttr('href data-toggle');
  $('#personal_details').removeClass('active in');
  $('#list_personal_details').addClass('inactive_tab1');
  $('#list_login_details').removeClass('inactive_tab1');
  $('#list_login_details').addClass('active_tab1 active');
  $('#list_login_details').attr('href', '#login_details');
  $('#list_login_details').attr('data-toggle', 'tab');
  $('#login_details').addClass('active in');
 });
 
 $('#btn_personal_details').click(function(){
  
   $('#list_personal_details').removeClass('active active_tab1');
   $('#list_personal_details').removeAttr('href data-toggle');
   $('#personal_details').removeClass('active');
   $('#list_personal_details').addClass('inactive_tab1');
   $('#list_contact_details').removeClass('inactive_tab1');
   $('#list_contact_details').addClass('active_tab1 active');
   $('#list_contact_details').attr('href', '#contact_details');
   $('#list_contact_details').attr('data-toggle', 'tab');
   $('#contact_details').addClass('active in');
  
 });
 
 $('#previous_btn_contact_details').click(function(){
  $('#list_contact_details').removeClass('active active_tab1');
  $('#list_contact_details').removeAttr('href data-toggle');
  $('#contact_details').removeClass('active in');
  $('#list_contact_details').addClass('inactive_tab1');
  $('#list_personal_details').removeClass('inactive_tab1');
  $('#list_personal_details').addClass('active_tab1 active');
  $('#list_personal_details').attr('href', '#personal_details');
  $('#list_personal_details').attr('data-toggle', 'tab');
  $('#personal_details').addClass('active in');
 });
 
 $('#btn_contact_details').click(function(){
  var error_address = '';
  var error_mobile_no = '';
  var mobile_validation = /^\d{10}$/;
  if($.trim($('#address').val()).length == 0)
  {
   error_address = 'Address is required';
   $('#error_address').text(error_address);
   $('#address').addClass('has-error');
  }
  else
  {
   error_address = '';
   $('#error_address').text(error_address);
   $('#address').removeClass('has-error');
  }
  
  if($.trim($('#mobile_no').val()).length == 0)
  {
   error_mobile_no = 'Mobile Number is required';
   $('#error_mobile_no').text(error_mobile_no);
   $('#mobile_no').addClass('has-error');
  }
  else
  {
   if (!mobile_validation.test($('#mobile_no').val()))
   {
    error_mobile_no = 'Invalid Mobile Number';
    $('#error_mobile_no').text(error_mobile_no);
    $('#mobile_no').addClass('has-error');
   }
   else
   {
    error_mobile_no = '';
    $('#error_mobile_no').text(error_mobile_no);
    $('#mobile_no').removeClass('has-error');
   }
  }
  if(error_address != '' || error_mobile_no != '')
  {
   return false;
  }
  else
  {
   $('#btn_contact_details').attr("disabled", "disabled");
   $(document).css('cursor', 'prgress');
   $("#register_form").submit();
  }
  
 });
 
});

    $('#make_id').on('change', function() {
        var make_id=document.myForm.valuation_make_id.value;
           $.ajax({    //create an ajax request to display.php
                    type: "post",
                    url: "<?php echo base_url(); ?>" + "cars/get_make_data/" + make_id,    
                    data:{ make_id:make_id},
                    success: function(data) {
                     console.log(data);
                    $('select#valuation_model_id').html('');
                    $.each(JSON.parse(data), function(i,v) {
                      $("<option / >").val(v.id).text(v.title).appendTo('select#valuation_model_id');
                      // body...
                    });
                    model_change();
                }
              });
            });

               function model_change(){   
                var model_id=$('#valuation_model_id option:selected').val();
               $.ajax({    //create an ajax request to display.php
                        type: "post",
                        url: "<?php echo base_url(); ?>" + "cars/get_engineBymodel/" + model_id,    
                        success: function(data) {
                        $('select#valuation_engine_size').html('');
                        $.each(JSON.parse(data), function(i,v) {
                          $("<option / >").val(v.id).text(v.title).appendTo('select#valuation_engine_size');
                        });
                    }
                  });
                }
</script>
