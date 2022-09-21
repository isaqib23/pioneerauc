  
<input type="hidden" id="base_url" value="<?php echo base_url(); ?>">

 <style type="text/css">
  

  div.dataTables_wrapper div.dataTables_processing{
    height: auto;
  }
  .dataTables_filter{
    margin-right: 22px;
  }
  </style>

<div class="clearfix"></div>

<div class="col-md-12 col-sm-12 col-xs-12">
  <div class="x_panel">
        <div class="x_title">
          <h2><?php echo $small_title; ?></h2>

          <div class="clearfix"></div>
      </div>
      <?php if($this->session->flashdata('msg')){ ?>
        <div class="alert">
          <div class="alert alert-success alert-dismissible fade in" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: black;"><span aria-hidden="true">×</span>
            </button>
            <?php  echo $this->session->flashdata('msg');   ?>
          </div>
        </div>
    <?php }?>
    <div Mileage To="x_content"> 
      <div Mileage From="x_content"> 

        <form method="post" id="demo-form2" action="" enctype="multipart/form-data" class="form-horizontal form-label-left ">
          <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
          <div class="form-group ">   
              <div class="col-md-3 col-sm-3 col-xs-12  col-md-offset-1">
                <select class="form-control col-md-7 col-xs-12 username"  id="username" multiple="" name="username_for_report[]">
                  <?php foreach ($tasker_list as $type_value) { ?>
                    <option 
                    <?php 
                    //if(isset($crm_info) && !empty($crm_info)){
                       // if($crm_info[0]['customer_type_id'] == $type_value['id']){ echo 'selected';}
                   // }?>
                      value="<?php echo $type_value['username']; ?>"><?php echo $type_value['username']; ?></option>
                  <?php  } ?>
                </select>
              </div>


            <div class="col-md-3 col-sm-3 col-xs-12  col-md-offset-1">
              <select class="form-control col-md-7 col-xs-12 code"  id="code" name="category_id">
                <option value="" selected="">Select Category</option>
                <?php foreach ($task_category as $type_value) { ?>
                <option value="<?php echo $type_value['id']; ?>">
                  <?php echo $type_value['name']; ?>
                </option>
                <?php  } ?>
              </select>
            </div>


            <div class="col-md-3 col-sm-3 col-xs-12  col-md-offset-1">
              <select class="form-control col-md-7 col-xs-12 email" multiple=""  id="email" name="email_for_report[]">
                <?php foreach ($tasker_list as $type_value) { ?>
                <option value="<?php echo $type_value['email']; ?>">
                  <?php echo $type_value['email']; ?>
                </option>
                <?php  } ?>
              </select>
            </div>
          </div>


          <div class="form-group "> 
            <div class="col-md-3 col-sm-3 col-xs-12  col-md-offset-1">
              <select class="form-control col-md-7 col-xs-12 mobile" multiple="" id="mobile" name="mobile_for_report[]">
                <?php foreach ($tasker_list as $type_value) { ?>
                <option value="<?php echo $type_value['mobile']; ?>">
                  <?php echo $type_value['mobile']; ?>
                </option>
                <?php  } ?>
              </select>
            </div>


            <div class="col-md-3 col-sm-3 col-xs-12  col-md-offset-1">
              <div class='input-group date' id='datefrom'>
                <input type='text' class="form-control" name="datefrom_for_report" placeholder="From" />
                <span class="input-group-addon">
                   <span class="glyphicon glyphicon-calendar"></span>
                </span>
              </div>
            </div>

            <div class="col-md-3 col-sm-3 col-xs-12  col-md-offset-1">
              <div class='input-group date' id='dateto'>
                <input type='text' class="form-control" name="dateto_for_report" placeholder="To" />
                <span class="input-group-addon">
                   <span class="glyphicon glyphicon-calendar"></span>
                </span>
              </div>
            </div>

            <div class="form-group "> 
              <div class="col-md-3 col-sm-3 col-xs-12  col-md-offset-1">
                <select class="form-control col-md-7 col-xs-12 " id="status" name="status">
                  <option value="" selected="" >Select Status</option>
                  <option value="complete">Completed</option>
                  <option value="in_process">In Process</option>
                  <option value="pending">Pending</option>
                </select>
              </div>

              <input type="hidden" name="monthly_report_user" value="monthly_report_user">
              <div class="form-group ">   
               
              </div>
            </div>
            <div id="loading"></div>
        
            <div style="float: right" class=" col-md-offset-1">
              <button type="button" id="send" class="btn btn-success">Filter</button>
            </div>
          </div>
        </form>
      </div>
           <div  id="results">
            </div>
    </div>
  </div>
</div>
  
<script>

  $('#datefrom').datetimepicker({
  format: 'YYYY-MM-DD'
    });

  $('#dateto').datetimepicker({
      format: 'YYYY-MM-DD'
  });

  // $('#datatable-responsive2').DataTable( {
    // "scrollX": true
  // });
  $('#delete_button').click(function(){
    var url = '<?php echo base_url();?>';
    var selected = "";
      $('#customer_lead_listing input:checked').each(function() {
        selected+=$(this).attr('value')+","; 
      });

    $.ajax({
    url: url + 'crm/delete_customer_type/?ids='+selected,
    type: 'POST',
    }).then(function(data) {
      var objData = jQuery.parseJSON(data);
      console.log(objData);
      if (objData.msg == 'success') {
        window.location = url + 'crm/crm';
      }
    }); 
  });
            





  var checkboxes = $("input[type='checkbox']");
  $('input').on('ifChecked', function () {
      if(('checked').length > 0)
      { 
          $('#delete_bulk').show();
      }
  })
  $('input').on('ifUnchecked', function (){
      if(!checkboxes.is(":checked"))
      {
      $('#delete_bulk').hide();
      }
  })

 
 

  $(".username").select2({
    placeholder: "Select Name", 
    width: '200px',
    // allowClear: true,
  });

  $(".email").select2({
    placeholder: "Select Email", 
    width: '200px',
     // allowClear: true,
  });
  $(".mobile").select2({
    placeholder: "Select mobile", 
    width: '200px',
     // allowClear: true,
  });
 
  var select2Oz = remoteSelect2({
    'selectorId' : '.username',
    'placeholder' : 'Tasker Name',
    'table' : 'users',
    'values' : 'Name',
    'width' : '200px',
    'delay' : '250',
    'cache' : false,
    'minimumInputLength' : 1,
    'limit' : 5,
    'url' : '<?= base_url();?>jobcard/username_api'
  });
  var select2Oz = remoteSelect2({
    'selectorId' : '#email',
    'placeholder' : 'Tasker Email',
    'table' : 'users',
    'values' : 'Name',
    'width' : '200px',
    'delay' : '250',
    'cache' : false,
    'minimumInputLength' : 1,
    'limit' : 5,
    'url' : '<?= base_url();?>jobcard/email_api'
  });  
  var select2Oz = remoteSelect2({
    'selectorId' : '#mobile',
    'placeholder' : 'Tasker Mobile',
    'table' : 'users',
    'values' : 'Name',
    'width' : '200px',
    'delay' : '250',
    'cache' : false,
    'minimumInputLength' : 1,
    'limit' : 5,
    'url' : '<?= base_url();?>jobcard/number_api'
  });

  $("#datatable-responsive2").DataTable({
    dom: "lfrtip",
    "scrollX": true,
    "aoColumnDefs": [ 
        {"bSortable": false, "aTargets": 'no-sort'},
    ],
    buttons: [
    // {
    //   extend: "copy",
    //   className: "btn-sm"
    // },
    {
      extend: "csv",
      className: "btn-sm"
    },
    {
      extend: "excel",
      className: "btn-sm"
    },
    {
      extend: "pdfHtml5",
      className: "btn-sm"
    },
    // {
    //   extend: "print",
    //   className: "btn-sm"
    // },
    ],
    // responsive: true
});





  $('.model_tasks_list').click(function(){
    var url = '<?php echo base_url();?>';
    var id = $(this).attr("id");
    console.log(id);
    $.ajax({
      url: url + 'jobcard/get_assigned_tasks',
      type: 'POST',
      data: {id:id},
    }).then(function(data) {
      var objData = jQuery.parseJSON(data);
      console.log(objData.data);
      if (objData.msg == 'success') 
      {
        $('.modal-body').html(objData.data);
      }

    });
  });


  var url = '<?php echo base_url();?>';
  var formaction_path = '<?php echo $formaction_path;?>';
  $("#send").on('click', function(e) { 
  e.preventDefault();
  var formData = new FormData($("form")[0]);
  // var formData = new FormData();
  // var formData = $('form').serialize();
  $.ajax({
    url: url + 'jobcard/' + formaction_path,
    type: 'POST',
    data: formData,
    cache: false,
    contentType: false,
    processData: false
    }).then(function(data) {
      var objData = jQuery.parseJSON(data);
      if (objData.msg == 'success') 
      { 
    
              // datatable.clear();
          // var datatable = $('#datatable-responsive').DataTable();
          // $.get('myUrl', function(newDataArray) {
          $('#results').html(objData.data);
              // datatable.data(objData.data);
              // datatable.draw();
          // });
            // $("#datatable-responsive").reload();
            // $('#datatable-responsive').DataTable().ajax.reload();
            // refreshTable();
       
      } else {
        $('.msg-alert').css('display', 'block');
        $("#result").html('<div class="alert" ><div class="alert alert-domain alert-danger alert-dismissible fade in  msg-alert" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: black"><span aria-hidden="true">×</span> </button>' + objData.msg + '</div></div>');

        window.setTimeout(function() {
          $(".alert").fadeTo(500, 0).slideUp(500, function(){
            $(this).remove(); 
          });
        }, 3000);
      }
    });
  });


</script>
<script>
  $(document).ready(function(){
    $('footer').hide();
  });
</script>
