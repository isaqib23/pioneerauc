  <style type="text/css">
  

  div.dataTables_wrapper div.dataTables_processing{
    height: auto;
  }
  .dataTables_filter{
    margin-right: 22px;
  }
  </style>
<input type="hidden" id="base_url" value="<?php echo base_url(); ?>">

<div class="clearfix"></div>

<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_title">
        <h2><?php echo $small_title; ?></h2>

        <div class="clearfix"></div>
    </div>
     <?php if($this->session->flashdata('error')){ ?>
          <div class="alert alert-danger alert-dismissible">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <strong>Error!</strong> <?= $this->session->flashdata('error'); ?>
          </div>
        <?php } ?>

        <?php if($this->session->flashdata('success')){ ?>
          <div class="alert alert-success alert-dismissible">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <strong>Success!</strong> <?= $this->session->flashdata('success'); ?>
          </div>
        <?php } ?>  
  <div Mileage To="x_content"> 
    <div Mileage From="x_content"> 

    <form method="post" id="demo-form2" action="" enctype="multipart/form-data" class="form-horizontal form-label-left ">
      <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />


      <div class="form-group ">   

      
          <div class="col-md-3 col-sm-3 col-xs-12  col-md-offset-1">
           <select class="form-control col-md-7 col-xs-12 username"  id="username_for_report" multiple="" name="username_for_report[]">
              <!-- <?php foreach ($tasker_list as $type_value) { ?>
                <option value="<?php echo $type_value['username']; ?>"><?php echo $type_value['username']; ?></option>
              <?php  } ?> -->
            </select>
          </div>
          <input type="hidden" id="hidden_id" name="hidden_id">
            
    
          <div class="col-md-3 col-sm-3 col-xs-12  col-md-offset-1">
            <div class='input-group date datefrom' id=''>
              <input type='text' class="form-control" id='datefrom' name="datefrom" placeholder="From" />
              <span class="input-group-addon">
                 <span class="glyphicon glyphicon-calendar"></span>
              </span>
            </div>
          </div>
          <div class="col-md-3 col-sm-3 col-xs-12  col-md-offset-1">
            <div class='input-group date dateto' id=''>
              <input type='text' class="form-control" id='dateto' name="dateto" placeholder="To" />
              <span class="input-group-addon">
                 <span class="glyphicon glyphicon-calendar"></span>
              </span>
            </div>
          </div>


        <div class="form-group ">   
         
        </div>
      </div>

      <div id="loading"></div>
      <!--   <div  class=" col-md-offset-1">
            <button type="button" id="send" class="btn btn-success">Filter</button>
        </div> -->
    </form>

         
     
        <div class="x_content">
        <!-- <hr> -->
        

        </div>

      <div class="x_content bulk_actions" style="display: none;">
        <label>Bulk Actions:</label>
         <button onclick="deleteRows_Bulk(this)" id="delete_bulk" data-table="datatable-responsive3" type="button" data-obj="assigned_task" data-token-name="<?= $this->security->get_csrf_token_name();?>" data-token-value="<?=$this->security->get_csrf_hash();?>" data-url="<?php echo base_url(); ?>jobcard/delete_bulk" class="btn btn-danger btn-xs" title="Delete"><i class="fa fa-trash"></i> Delete Rows</button>

        <hr>
      </div>
      <div Mileage Depreciation="x_content"> 
          <a type="button" href="<?php echo base_url().'jobcard/assign_task_to_user'; ?>"   class="btn btn-md btn-success  "><i class="fa fa-plus"></i> Assign Task</a>
         <button onclick="deleteRecord_Bulk(this)" style="display: none;" id="delete_bulk" type="button" data-obj="assigned_task" data-url="<?php echo base_url(); ?>jobcard/delete_bulk" class="btn btn-danger btn-xs" title="Delete"><i class="fa fa-trash"></i> Delete</button>
        </div>
      
      <div class="x_content">
       <?php if(!empty($assigned_task_list)){?>
        <table id="datatable-responsive3" class="table table-striped jambo_table bulk_action" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th data-visible="false" data-orderable="false"></th>
                    <th>Tasker Name</th>
                    <th>Assigned Users</th>
                    <th>Assigned Tasks</th>
                    <th>Category</th>
                    <th>Assigned Date</th>
                    <th>Status</th>
                    <th>Completed Date</th>
                    <th  data-orderable="false" class="tablet mobile"> Action </th>

                </tr>
            </thead>
        </table>
            <?php 
              }else{
                echo "<h1>No Record Found</h1>";
                }
              ?>
            </div>                
          </div>
        </div>
      </div>
    </div>


     <div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" style="max-width:750px; ">
          <div class="modal-content">

            <div class="modal-header">
              <button type="button" data-dismiss="modal" class="close inner_bidding_model" title="close"><span aria-hidden="true">×</span>
              </button>
              <h3 class="modal-title" id="myModalLabel">Task Detail</h3>
              <div class="tasks_detail_list">
                
              </div>
            </div>

          </div>
        </div>
      </div>
  
<script type="text/javascript">

  window.setTimeout(function() {
    $(".alert").fadeTo(500, 0).slideUp(500, function(){
      $(this).remove(); 
    });
  }, 3000);

  $('.datefrom').datetimepicker({
    format: 'YYYY-MM-DD'
  });

  $('.dateto').datetimepicker({
    format: 'YYYY-MM-DD'
  });

</script>
<script>
  var token_name = '<?= $this->security->get_csrf_token_name();?>';
  var token_value = '<?=$this->security->get_csrf_hash();?>';
   // $('#datatable-responsive3').DataTable( {
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
      data: {id:id, [token_name]:token_value},
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


  function get_tasks_detail(e) {
    var url = '<?php echo base_url();?>';
    var id = e.id;
    $.ajax({
      url: url + 'jobcard/get_assigned_tasks_detail',
      type: 'POST',
      data: {id:id, [token_name]:token_value},
    }).then(function(data) {
      var objData = jQuery.parseJSON(data);
      console.log(objData);
      if (objData.msg == 'success') 
      {
        $('.tasks_detail_list').html(objData.data);
      }
    });
  }
  var url = '<?php echo base_url();?>';
  var formaction_path = '<?php echo $formaction_path;?>';
  $("#send").on('click', function(e) { //e.preventDefault();
    var formData = new FormData($("#demo-form2")[0]);
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
          $('#assigned_task_listing').html(objData.data);
      } 
      else 
      {
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
  function changeStatus(x){

    var url = '<?php echo base_url();?>';
    var user_id  = $(x).data('id');
    var statusId = $('#statuss').val();
    $.ajax({
      url: url + 'users/chage_status/?status_id='+statusId + '&user_id='+user_id, 
       // url: url + 'crm/delete_customer_type/?ids='+selected,
      type: 'POST',
      data: {[token_name]:token_value},        
    }).then(function(data) {
      // alert(data);
      $('#loading').html('loading...');
      // window.location = url + 'users/';
    });
  }


  var select2Oz = remoteSelect2({
    [token_name] : token_value,
    'selectorId' : '.username',
    'placeholder' : 'Tasker Name',
    'table' : 'users',
    'values' : 'Name',
    'width' : '200px',
    'delay' : '250',
    'cache' : false,
    'minimumInputLength' : 1,
    'limit' : 5,
    'url' : '<?= base_url();?>jobcard/assigned_task_username'
  });

  // let a = $('.username').val();
  let exportOptions = {'rows': {selected: true} ,columns: ['1,2,3,4,5,6,7']};
  let dtButtons = [{ extend : 'excel',exportOptions: exportOptions},{ extend : 'csv',exportOptions: exportOptions},{ extend : 'pdf',exportOptions: exportOptions},{ extend : 'print',exportOptions: exportOptions},];

  let dt1 = customeDatatable({
    // index zero must be csrf token
    '<?= $this->security->get_csrf_token_name();?>':'<?= $this->security->get_csrf_hash();?>',
    'div' : '#datatable-responsive3',
    // 'idss' : 5,
    'url' : '<?=base_url()?>jobcard/jobCardUsersAssignedTask',
    'orderColumn' : 7,
    'iDisplayLength' : 5,
    'dom' : '<"pull-left top"B><"pull-right"l><"clear">r<t><"bottom"ip><"clear">',
    'columnCustomeTypeTarget' : 7,
    'defaultSearching': true,
    'responsive': true,
    'rowId': 'id',
    'stateSave': true,
    'lengthMenu': [[ 5, 10, 25, 50, 100, 150], [5, 10, 25, 50, 100, 150]],
    'order' : 'desc',
    'selectStyle' : 'multi',
    'collectionButtons' : dtButtons,
    'processing' : true,
    'serverSide' : true,
    'processingGif' : '<span style="display:inline-block; margin-top:-105px;">Loading.. <img  src="<?=base_url()?>/assets_admin/images/load.gif" /></span>',
    'buttonClassName' : 'btn-sm',
    'columnCustomType' : 'date-eu',
    'customFieldsArray': ['username_for_report','datefrom','dateto'],
    'dataColumnArray': [{'data':'id'},{'data':'tasker_name'},{'data':'tasker_email'},{'data':'tasks_name'},{'data':'category_name'},{'data':'assigned_date'},{'data':'status'},{'data':'completed_date'},{'data':'action'}]
  });


  $(document).ready(function(){
    $('#datatable-responsive3 tbody').on( 'click', 'tr', function () {
      $(this).toggleClass('selected');
    });
    $('.buttons-select-none').on( 'click', function () {
      $('.bulk_actions').css('display','none');
    });
    $('.buttons-select-all').on( 'click', function () {
      $('.bulk_actions').css('display','block');
    });
    $('.dateto').on('dp.change', function(e){ 
      if($(".datefrom").find("input").val() != ''){
        console.log($("#datefrom").val());
        dt1.draw();
      }
    });
    $('.datefrom').on('dp.change', function(e){ 
      if($(".dateto").find("input").val() != ''){
        console.log($("#dateto").val());
        dt1.draw();
      }
    });

    $('.username').change(function(){
      if($(".username").find("input").val() != ''){
        console.log($(".username").val());
        dt1.draw();
      }
      // $('#datatable-responsive3').dataTable().fnDestroy();
    });
    // End filter options 
    $('#datatable-responsive3 tbody').on( 'click', 'tr', function (e) {
      var rows_selected = dt1.rows('.selected').data();
      if(dt1.rows('.selected').data().length > 0){
        $('.bulk_actions').css('display','block');
      }else{
        $('.bulk_actions').css('display','none');
      }
    });
  });

</script>
