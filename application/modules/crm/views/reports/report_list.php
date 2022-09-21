<style type="text/css">
  div.dataTables_wrapper div.dataTables_processing{
    height: auto;
  }
  .dataTables_filter{
    margin-right: 22px;
  }
</style>
<input type="hidden" id="base_url" value="<?php echo base_url(); ?>">
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
    
    <h4>Filter Record: </h4>

    <form method="post" id="demo-form2" action="" enctype="multipart/form-data" class="form-horizontal form-label-left ">
      <input type="hidden" id="<?= $this->security->get_csrf_token_name();?>" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
        <div class="form-group ">   
            <div class="col-md-3 col-sm-3 col-xs-12  col-md-offset-1">
                 <select class="form-control col-md-7 col-xs-12 customer_type_id" multiple="" id="customer_type_id" name="customer_type_id[]">
                         <?php foreach ($customer_type_list as $type_value) { ?>
                        <option 
                        <?php if(isset($crm_info) && !empty($crm_info)){
                            if($crm_info[0]['customer_type_id'] == $type_value['id']){ echo 'selected';}
                        }?>
                            value="<?php echo $type_value['id']; ?>"><?php echo $type_value['title']; ?></option>
                            <?php  } ?>
                  </select>
            </div>
            <div class="col-md-3 col-sm-3 col-xs-12  col-md-offset-1">
                <select class="form-control col-md-7 col-xs-12 assigned_to" multiple="" id="assigned_to" name="assigned_to[]">
                  <?php foreach ($assigned_to_list as $type_value) { ?>
                  <option value="<?php echo $type_value['id']; ?>">
                    <?php echo $type_value['fname'].' '.$type_value['lname']; ?>
                  </option>
                  <?php  } ?>
                </select>
            </div>
        </div>
        
        <div class="form-group ">   
          <div class="col-md-3 col-sm-3 col-xs-12  col-md-offset-1">
            <div class='input-group date datefrom'>
              <input type='text' class="form-control" id='datefrom' name="datefrom" placeholder="From" />
              <span class="input-group-addon">
                 <span class="glyphicon glyphicon-calendar"></span>
              </span>
            </div>
          </div>
          <div class="col-md-3 col-sm-3 col-xs-12  col-md-offset-1">
            <div class='input-group date dateto'>
              <input type='text' class="form-control" id='dateto' name="dateto" placeholder="To" />
              <span class="input-group-addon">
                 <span class="glyphicon glyphicon-calendar"></span>
              </span>
            </div>
          </div>
        </div>
        <div class="ln_solid"></div>
       <!--  <div class="form-group">
            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-1">
                <button type="button" id="send" class="btn btn-success">Filter</button>
            </div>
        </div> -->
      </form>
      <div id="result"></div>
       <div class="clearfix"></div>
      <div class="x_content">
       <?php if(!empty($crm_list)){?>
        <table id="datatable-reports-list" class="table table-striped jambo_table" cellspacing="0" width="100%">
          <thead>
              <tr>
                  <!-- <th data-visible="false" data-orderable="false" class="notexport"></th> -->
                  <th >#</th>
                  <th>Customer Name</th>
                  <th>Created Date</th>
                  <th>Updated Date</th>
                  <th>Email</th>
                  <th>Customer Type</th>
                  <th>Assigned By</th>
                  <th>Assign To</th>
              </tr>
          </thead>
             
        </table>
        <tfoot>
                <!-- <th colspan="50%" style="padding: 10px;"> -->
                  <div class="pull-left actions">        
                             
            </div>                
        </tfoot>
            <?php 
              }else{
                echo "<h1>No Record Found</h1>";
                }
              ?>
          </div>
        </div>
      </div>
    </div>
 
<script type="text/javascript">
   var url = '<?php echo base_url();?>';
  var formaction_path = '<?php echo $formaction_path;?>';

  window.setTimeout(function() {
    $(".alert").fadeTo(500, 0).slideUp(500, function(){
      $(this).remove(); 
    });
  }, 3000);

  // $(document).ready(function() {
  //   $('#engine_sizes').DataTable();
  // });

  // multiple select select2 JQuery 
  $(".customer_type_id").select2({
    placeholder: "Select Customer Type", 
    width: '200px',
  });
  // multiple select select2 JQuery 
  $(".assigned_to").select2({
    placeholder: "Select Sale Person", 
    width: '200px',
  });

  // multiple select select2 JQuery 
  // $(".assigned_to").select2({
  //   placeholder: "Select Sale Person", 
  //   width: '200px'
  // });


    // var select2Oz = remoteSelect2({
    //   // index zero must be csrf token
    //   '<?= $this->security->get_csrf_token_name();?>' : '<?= $this->security->get_csrf_hash();?>',
    //   'selectorId' : '.assigned_to',
    //   'placeholder' : 'Select Sale Person',
    //   'table' : 'users',
    //   'values' : 'Name,',
    //   'width' : '200px',
    //   'delay' : '250',
    //   'cache' : false,
    //   'minimumInputLength' : 1,
    //   'limit' : 5,
    //   'url' : '<?= base_url();?>crm/assigned_to_api'
    // });

  $('.datefrom').datetimepicker({
      format: 'YYYY-MM-DD'
  });

  $('.dateto').datetimepicker({
      format: 'YYYY-MM-DD'
  });

  $(".datefrom").on("dp.change", function (e) {
    $('.dateto').data("DateTimePicker").minDate(e.date);
  });
  $(".dateto").on("dp.change", function (e) {
    $('.datefrom').data("DateTimePicker").maxDate(e.date);
  });

   // $("#send").on('click', function(e) { //e.preventDefault();
   //    var formData = new FormData($("#demo-form2")[0]);
   //      $.ajax({
   //        url: url + 'crm/' + formaction_path,
   //        type: 'POST',
   //        data: formData,
   //        cache: false,
   //        contentType: false,
   //        processData: false
   //        }).then(function(data) {
   //        var objData = jQuery.parseJSON(data);
   //        if (objData.msg == 'success') 
   //        { 
   //                // datatable.clear();
   //            // var datatable = $('#datatable-responsive').DataTable();
   //            // $.get('myUrl', function(newDataArray) {
   //            $('#customer_listing').html(objData.data);
   //                // datatable.data(objData.data);
   //                // datatable.draw();
   //            // });
   //              // $("#datatable-responsive").reload();
   //              // $('#datatable-responsive').DataTable().ajax.reload();
   //              // refreshTable();
           
   //        } 
   //        else 
   //        {
   //            $('.msg-alert').css('display', 'block');
   //            $("#result").html('<div class="alert" ><div class="alert alert-domain alert-danger alert-dismissible fade in  msg-alert" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: black"><span aria-hidden="true">×</span> </button>' + objData.msg + '</div></div>');

   //               window.setTimeout(function() {
   //            $(".alert").fadeTo(500, 0).slideUp(500, function(){
   //                $(this).remove(); 
   //            });
   //        }, 3000);
   //        }
   //        });
   //          });    

   // function refreshTable() {
   //  $('#datatable-responsive').each(function() {
   //    dt = $(this).dataTable();
   //    dt.fnDraw();
   //  })
   //  }

  
  let exportOptions = {'rows': {selected: true} ,columns: ['1,2,3,4,5,6,7']};
  let dtButtons = [{ extend : 'excel',exportOptions: exportOptions},{ extend : 'csv',exportOptions: exportOptions},{ extend : 'pdf',exportOptions: exportOptions},{ extend : 'print',exportOptions: exportOptions},];
  let dt1 = customeDatatable({
    // index zero must be csrf token
    '<?= $this->security->get_csrf_token_name();?>':'<?= $this->security->get_csrf_hash();?>',
    'div' : '#datatable-reports-list',
    'url' : '<?= base_url() ?>crm/filterCRMReport',
    'orderColumn' : 4,
    'iDisplayLength' : 10,
    'dom' : '<"pull-left top"B><"pull-right" fl><"clear">r<t><"bottom"ip><"clear">',
    'columnCustomeTypeTarget' : [3,4],
    'defaultSearching': true,
    'responsive': true,
    'rowId': 'id',
    'stateSave': true,
    'lengthMenu': [[10, 25, 50, 100, 150,200], [ 10, 25, 50, 100, 150,200]],
    'order' : 'desc',
    'selectStyle' : 'multi',
    'collectionButtons' : dtButtons,
    'processing' : true,
    'serverSide' : true,
    'processingGif' : '<span style="display:inline-block; margin-top:-105px;">Loading.. <img  src="<?=base_url()?>/assets_admin/images/load.gif" /></span>',
    'buttonClassName' : 'btn-sm',
    'columnCustomType' : 'date-eu',
    'customFieldsArray': ['customer_type_id','assigned_to','datefrom','dateto'],
    'dataColumnArray': [{'data':'id'},{'data':'name'},{'data':'date'},{'data':'updated_on'},{'data':'email'},{'data':'customer_type'},{'data':'assigned_by'},{'data':'assigned_to'}]
  });

$(document).ready(function(){
  $('#datatable-reports-list tbody').on( 'click', 'tr', function () {
    $(this).toggleClass('selected');
  });
  $('.buttons-select-none').on( 'click', function () {
    $('.bulk_actions').css('display','none');
    });
  $('.buttons-select-all').on( 'click', function () {
    $('.bulk_actions').css('display','block');
  });


  $('#customer_type_id,#assigned_to').change(function(){
    dt1.draw();
  });

  $('.dateto').on('dp.change', function(e){ 
    if($(".datefrom").find("input").val() != ''){
      dt1.draw();
    }
  });

  $('.datefrom').on('dp.change', function(e){ 
    if($(".dateto").find("input").val() != ''){
      dt1.draw();
    }
  });

  $('#datatable-reports-list tbody').on( 'click', 'tr', function (e) {
    var rows_selected = dt1.rows('.selected').data(); 
  });

});  

   // $("#datatable-reports-list").DataTable({
   //          dom: "Bfrtip",
   //          buttons: [
   //          // {
   //          //   extend: "copy",
   //          //   className: "btn-sm"
   //          // },
   //          {
   //            extend: "csv",
   //            className: "btn-sm"
   //          },
   //          {
   //            extend: "excel",
   //            className: "btn-sm"
   //          },
   //          {
   //            extend: "pdfHtml5",
   //            className: "btn-sm"
   //          },
   //          // {
   //          //   extend: "print",
   //          //   className: "btn-sm"
   //          // },
   //          ],
   //          responsive: true
   //        });
 
</script>
