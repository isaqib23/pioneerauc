  <style type="text/css">
    #pending {
    webkit-border-radius: 2px;
    display: block;
    float: left;
    padding: 5px 9px;
    text-decoration: none;
    background: #d58512;
    color: #F1F6F7;
    margin-right: 5px;
    font-weight: 500;
    margin-bottom: 5px;
    font-family: helvetica;
  }

  .pull-left.top {margin-bottom: 10px;}

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
            </div>


            <div class="form-group ">   
             
            </div>
                <div id="loading"></div>
              
                    <!-- <div  class=" col-md-offset-1">
                        <button type="button" id="send" class="btn btn-success">Filter</button>
                    </div> -->
        </form>
      </div>

      <div class="x_content">
        <hr>
        <div Mileage Depreciation="x_content"> 
          <a type="button" href="<?php echo base_url().'jobcard/add_task'; ?>"   class="btn btn-md btn-success  "><i class="fa fa-plus"></i> Add Task</a>
         <!-- <button onclick="deleteRecord_Bulk(this)" style="display: none;" id="delete_bulk" type="button" data-obj="task" data-url="<?php echo base_url(); ?>jobcard/delete_bulk" class="btn btn-danger btn-xs" title="Delete"><i class="fa fa-trash"></i> Delete</buttons> -->
          </div>
      </div>
          




      <div class="x_content bulk_actions" style="display: none;">
        <label>Bulk Actions:</label>
         <button onclick="deleteRows_Bulk(this)" id="delete_bulk" data-table="datatable-responsive3" type="button" data-obj="task" data-url="<?php echo base_url(); ?>jobcard/delete_bulk" class="btn btn-danger btn-xs" title="Delete"><i class="fa fa-trash"></i> Delete Rows</button>

        <hr>
      </div>
        
      <div class="x_content">
       <?php if(!empty($task_list)){?>
        <table id="datatable-responsive3" class="table table-striped jambo_table bulk_action" cellspacing="0" width="100%" >
          <thead>
            <tr>
              <th data-visible="false" data-orderable="false"></th>
              <th>Title</th>
              <th>Description</th>
              <th>Category</th>
              <th>Created on</th>
              <!-- <th>City</th> -->
              <!-- <th>Updated on</th> -->
              <!-- <th>created_by</th> -->
              <!-- <th>Assign to</th> -->
              <!-- <th>Complete Date</th> -->
              <!-- <th data-orderable="false" class="notexport">Status</th> -->
              <!-- <th>Approved</th> -->
              <th  data-orderable="false" class="tablet mobile">Actions</th>
            </tr>
          </thead>
          
        </table>
      </div>                
      <?php 
        }else{
          echo "<h1>No Record Found</h1>";
        } ?>
    </div>
  </div>
</div>
  
<script type="text/javascript">

  $('.datefrom').datetimepicker({
    format: 'YYYY-MM-DD'
  });

  $('.dateto').datetimepicker({
    format: 'YYYY-MM-DD'
  });

  window.setTimeout(function() {
    $(".alert").fadeTo(500, 0).slideUp(500, function(){
        $(this).remove(); 
    });
  }, 3000);

</script>

<script>
  // $('#datatable-responsive2').DataTable( {
  //  "scrollX": true
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

  var url = '<?php echo base_url();?>';
  var formaction_path = '<?php echo $formaction_path;?>';
    //  $("#send").on('click', function(e) { //e.preventDefault();
    // var formData = new FormData($("#demo-form2")[0]);
    //   $.ajax({
    //     url: url + 'jobcard/' + formaction_path,
    //     type: 'POST',
    //     data: formData,
    //     cache: false,
    //     contentType: false,
    //     processData: false
    //     }).then(function(data) {
    //     var objData = jQuery.parseJSON(data);
    //     if (objData.msg == 'success') 
    //     { 
      
    //             // datatable.clear();
    //         // var datatable = $('#datatable-responsive').DataTable();
    //         // $.get('myUrl', function(newDataArray) {
    //         $('#user_listing').html(objData.data);
    //             // datatable.data(objData.data);
    //             // datatable.draw();
    //         // });
    //           // $("#datatable-responsive").reload();
    //           // $('#datatable-responsive').DataTable().ajax.reload();
    //           // refreshTable();
         
    //     } 
    //     else 
    //     {
    //         $('.msg-alert').css('display', 'block');
    //         $("#result").html('<div class="alert" ><div class="alert alert-domain alert-danger alert-dismissible fade in  msg-alert" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: black"><span aria-hidden="true">×</span> </button>' + objData.msg + '</div></div>');

    //            window.setTimeout(function() {
    //         $(".alert").fadeTo(500, 0).slideUp(500, function(){
    //             $(this).remove(); 
    //         });
    //        }, 3000);
    //      }
    //   });
    // });
  function changeStatus(x){

    var url = '<?php echo base_url();?>';
    var user_id  = $(x).data('id');
    var statusId = $('#statuss').val();
    $.ajax({
      url: url + 'users/chage_status/?status_id='+statusId + '&user_id='+user_id, 
       // url: url + 'crm/delete_customer_type/?ids='+selected,
      type: 'POST',            
    }).then(function(data) {
      // alert(data);
      $('#loading').html('loading...');
      // window.location = url + 'users/';

    });
  }

  // $("#datatable-responsive2").DataTable({
  //     dom: "Bfrtip",
  //     "scrollX": true,
  //     "aoColumnDefs": [ 
  //         {"bSortable": false, "aTargets": 'no-sort'},
  //     ],
  //     buttons: [
  //     // {
  //     //   extend: "copy",
  //     //   className: "btn-sm"
  //     // },
  //     {
  //       extend: "csv",
  //       className: "btn-sm"
  //     },
  //     {
  //       extend: "excel",
  //       className: "btn-sm"
  //     },
  //     {
  //       extend: "pdfHtml5",
  //       className: "btn-sm"
  //     },
  //     // {
  //     //   extend: "print",
  //     //   className: "btn-sm"
  //     // },
  //     ],
  //     // responsive: true
  //   });

  let exportOptions = {'rows': {selected: true} ,columns: ['1,2,3,4,5']};
  let dtButtons = [{ extend : 'excel',exportOptions: exportOptions},{ extend : 'csv',exportOptions: exportOptions},{ extend : 'pdf',exportOptions: exportOptions},{ extend : 'print',exportOptions: exportOptions},];
  let dt1 = customeDatatable({
    // index zero must be csrf token
    '<?= $this->security->get_csrf_token_name();?>':'<?= $this->security->get_csrf_hash();?>',
    'div' : '#datatable-responsive3',
    'url' : '<?=base_url()?>jobcard/jobCardUsersTask',
    'orderColumn' : 5,
    'iDisplayLength' : 10,
    'dom' : '<"pull-left top"B><"pull-right"l>fr<t><"bottom"ip>',
    'columnCustomeTypeTarget' : 5,
    'defaultSearching': true,
    'responsive': true,
    'rowId': 'id',
    'stateSave': true,
    'lengthMenu': [[ 10, 25, 50, 100, 150], [10, 25, 50, 100, 150]],
    'order' : 'desc',
    'selectStyle' : 'multi',
    'collectionButtons' : dtButtons,
    'processing' : true,
    'serverSide' : true,
    'processingGif' : '<span style="display:inline-block; margin-top:-105px;">Loading.. <img  src="<?=base_url()?>/assets_admin/images/load.gif" /></span>',
    'buttonClassName' : 'btn-sm',
    'columnCustomType' : 'date-eu',
    'customFieldsArray': ['title','description','category_id','created_on','datefrom','dateto'],
    'dataColumnArray': [{'data':'id'},{'data':'title'},{'data':'description'},{'data':'category_id'},{'data':'created_on'},{'data':'action'}]
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
      
    //  filter options 
    $('#username,#code,#assigned_to,#mobile').change(function(){
      dt1.draw();
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
    // End filter options 

    $('#datatable-responsive3 tbody').on( 'click', 'tr', function (e) {
      var rows_selected = dt1.rows('.selected').data();
      if(dt1.rows('.selected').data().length > 0){
        $('.bulk_actions').css('display','block');
      }else{
        $('.bulk_actions').css('display','none');
      }
    });  
      
    // end 

  });
</script>
