<style type="text/css"> 
  div.dataTables_wrapper div.dataTables_processing{
    height: auto;
  }
  .select2-container--default .select2-selection--single, 
  .select2-container--default .select2-selection--multiple {padding: 0 8px !important;}
  .select2-container--default .select2-selection--multiple .select2-selection__rendered {padding-left: 0;}
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
                 <div class="col-md-4 col-xs-12 ">
                 <select class="form-control col-md-7 col-xs-12 username" multiple="" id="username" name="username[]">
                         <?php foreach ($tasker_list as $type_value) { ?>
                        <option 
                        <?php //if(isset($crm_info) && !empty($crm_info)){
                           // if($crm_info[0]['customer_type_id'] == $type_value['id']){ echo 'selected';}
                       // }?>
                            value="<?php echo $type_value['username']; ?>"><?php echo $type_value['username']; ?></option>
                           // <?php  } ?>
                  </select>
            </div>


            <div class="col-md-4 col-xs-12 ">
                <select class="form-control col-md-7 col-xs-12 code" multiple="" id="code" name="code[]">
                  <?php foreach ($tasker_list as $type_value) { ?>
                  <option value="<?php echo $type_value['code']; ?>">
                    <?php echo $type_value['code']; ?>
                  </option>
                  <?php  } ?>
                </select>
            </div>


            <div class="col-md-4 col-xs-12 ">
                <select class="form-control col-md-7 col-xs-12 email" multiple="" id="assigned_to" name="email[]">
                  <?php foreach ($tasker_list as $type_value) { ?>
                  <option value="<?php echo $type_value['email']; ?>">
                    <?php echo $type_value['email']; ?>
                  </option>
                  <?php  } ?>
                </select>
            </div>
        </div>


        <div class="form-group "> 
             <div class="col-md-4 col-xs-12 ">
                <select class="form-control col-md-7 col-xs-12 " multiple="" id="mobile" name="mobile[]">
                  <?php foreach ($tasker_list as $type_value) { ?>
                  <option value="<?php echo $type_value['mobile']; ?>">
                    <?php echo $type_value['mobile']; ?>
                  </option>
                  <?php  } ?>
                </select>
            </div>

             <!-- <div class="col-md-4 col-xs-12 ">
                  <select class="form-control col-md-7 col-xs-12 company_name" multiple="" id="company_name" name="company_name[]">
                    <?php //foreach ($code as $type_value) { ?>
                      <option value="<?php //echo $type_value['company_name']; ?>">
                        <?php //echo $type_value['company_name']; ?>
                      </option>
                    <?php  //} ?>
                  </select>
              </div> -->

               <!-- <div class="col-md-4 col-xs-12">
                  <select disabled="" class="form-control col-md-7 col-xs-12 item-code" multiple="" id="item_code" name="item_code[]">
                    <?php //foreach ($code as $type_value) { ?>
                      <option value="<?php //echo $type_value['company_name']; ?>">
                        <?php //echo $type_value['company_name']; ?>
                      </option>
                    <?php  //} ?>
                  </select>
              </div> -->
               <div class="col-md-4 col-xs-12">
            <div class='input-group date datefrom' id=''>
              <input type='text' class="form-control" id='datefrom' name="datefrom" placeholder="From" />
              <span class="input-group-addon">
                 <span class="glyphicon glyphicon-calendar"></span>
              </span>
            </div>
          </div>
          <div class="col-md-4 col-xs-12">
            <div class='input-group date dateto' id=''>
              <input type='text' class="form-control" id='dateto' name="dateto" placeholder="To" />
              <span class="input-group-addon">
                 <span class="glyphicon glyphicon-calendar"></span>
              </span>
            </div>
          </div>
            </div>

      </div>

        <div id="loading"></div>
      <!-- 
            <div style="float: right" class=" col-md-offset-1">
                <button type="button" id="send" class="btn btn-success">Filter</button>
            </div> -->
          </form>
         
        <div class="x_content">
        <hr>
        <div Mileage Depreciation="x_content"> 
          <a type="button" href="<?php echo base_url().'jobcard/save_user'; ?>"   class="btn btn-md btn-success  "><i class="fa fa-plus"></i> Create Tasker</a>
        </div>

        </div>
        <div class="x_content bulk_actions" style="display: none;">
          <label>Bulk Actions:</label>
          <button onclick="deleteRows_Bulk(this)" id="delete_bulk" type="button" data-table="datatable-responsive2" data-obj="users" data-url="<?php echo base_url(); ?>jobcard/delete_bulk" class="btn btn-danger btn-xs" title="Delete"><i class="fa fa-trash"></i> Delete</button>
          <hr>
      </div>
      

      <div class="x_content">
       <?php if(!empty($tasker_list)){?>
        <table id="datatable-responsive2" class="table table-striped jambo_table bulk_action ">
            <thead>
                <tr>
                    <th data-visible="false" data-orderable="false" ></th>
                    <th>User Name</th>
                    <th>Code</th>
                    <!-- <th>Role</th> -->
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Created On</th>
                    <th>Updated On</th>
                    <th data-orderable="false" class="sorting_disabled">Active/Inactive</th>
                    <th data-orderable="false" class="tablet mobile sorting_disabled">Actions</th>

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
  </div>
  
     <div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">

            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
              </button>
              <h4 class="modal-title" id="myModalLabel">Assigned Tasks</h4>
            </div>
            <div class="modal-body">
              
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


// $(".username").select2({
//     placeholder: "Select Name", 
//     width: '200px',
//     // allowClear: true,
//   });

  // multiple select select2 JQuery 
 //  $(".code").select2({
 //    placeholder: "Select code", 
 //    width: '200px',
 //     // allowClear: true,
 //  });
 //  $(".email").select2({
 //    placeholder: "Select Email", 
 //    width: '200px',
 //     // allowClear: true,
 //  });
 // $("#mobile").select2({
 //    placeholder: "Select mobile", 
 //    width: '200px',
 //     // allowClear: true,
 //  });
  $(".company_name").select2({
    placeholder: "Select Company Name", 
    width: '200px',
    // allowClear: true,

  });
  $(".item-code").select2({
    placeholder: "Select Item Code", 
    width: '200px',
    // allowClear: true,
  });

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
    'url' : '<?= base_url();?>jobcard/taskerlist_api'
  });
  var select2Oz = remoteSelect2({
    [token_name] : token_value,
    'selectorId' : '.email',
    'placeholder' : 'Tasker Email',
    'table' : 'users',
    'values' : 'Name',
    'width' : '200px',
    'delay' : '50',
    'cache' : false,
    'minimumInputLength' : 1,
    'limit' : 5,
    'url' : '<?= base_url();?>jobcard/taskeremail_api'
  });  
  //    var select2Oz = remoteSelect2({
  //   'selectorId' : '#mobile',
  //   'placeholder' : 'Tasker Mobile',
  //   'table' : 'users',
  //   'values' : 'Name',
  //   'width' : '200px',
  //   'delay' : '250',
  //   'cache' : false,
  //   'minimumInputLength' : 1,
  //   'limit' : 5,
  //   'url' : '<?= base_url();?>jobcard/taskernumber_api'
  // }); 
  var select2Oz = remoteSelect2({
    [token_name] : token_value,
    'selectorId' : '#mobile',
    'placeholder' : 'Tasker Mobile',
    'table' : 'users',
    'values' : 'Name',
    'width' : '200px',
    'delay' : '250',
    'cache' : false,
    'minimumInputLength' : 1,
    'limit' : 5,
    'url' : '<?= base_url();?>jobcard/taskernumber_api'
  });   
  var select2Oz = remoteSelect2({
    [token_name] : token_value,
    'selectorId' : '#code',
    'placeholder' : 'Tasker Code',
    'table' : 'users',
    'values' : 'Name',
    'width' : '200px',
    'delay' : '250',
    'cache' : false,
    'minimumInputLength' : 1,
    'limit' : 5,
    'url' : '<?= base_url();?>jobcard/taskercode_api'
  });


 
    // DataTable view JS 

  let exportOptions = {'rows': {selected: true} ,columns: ['1,2,3,4,5,6,7']};
  let dtButtons = [{ extend : 'excel',exportOptions: exportOptions},{ extend : 'csv',exportOptions: exportOptions},{ extend : 'pdf',exportOptions: exportOptions},{ extend : 'print',exportOptions: exportOptions},];
  let dt1 = customeDatatable({
    // index zero must be csrf token
    '<?= $this->security->get_csrf_token_name();?>':'<?= $this->security->get_csrf_hash();?>',
    'div' : '#datatable-responsive2',
    'url' : '<?=base_url()?>jobcard/jobCardUsersList',
    'orderColumn' : 6,
    'iDisplayLength' : 10,
    'dom' : '<"pull-left top"B><"pull-right"l><"clear">fr<t><"bottom"ip><"clear">',
    'columnCustomeTypeTarget' : 6,
    'defaultSearching': false,
    'responsive': true,
    'rowId': 'id',
    'stateSave': false,
    'lengthMenu': [[ 10, 25, 50, 100, 150], [10, 25, 50, 100, 150]],
    'order' : 'desc',
    'selectStyle' : 'multi',
    'collectionButtons' : dtButtons,
    'processing' : true,
    'serverSide' : true,
    'processingGif' : '<span style="display:inline-block; margin-top:-105px;">Loading.. <img  src="<?=base_url()?>/assets_admin/images/load.gif" /></span>',
    'buttonClassName' : 'btn-sm',
    'columnCustomType' : 'date-eu',
    'customFieldsArray': ['username','code','assigned_to','mobile','datefrom','dateto'],
    'dataColumnArray': [{'data':'id'},{'data':'username'},{'data':'code'},{'data':'mobile'},{'data':'email'},{'data':'created_on'},{'data':'updated_on'},{'data':'user_status'},{'data':'action'}]
  });

  $(document).ready(function(){
      $('#datatable-responsive2 tbody').on( 'click', 'tr', function () {
        $(this).toggleClass('selected');
        } );
      $('.buttons-select-none').on( 'click', function () {
        $('.bulk_actions').css('display','none');
        } );
      $('.buttons-select-all').on( 'click', function () {
        $('.bulk_actions').css('display','block');
        } );
      
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

     $('#datatable-responsive2 tbody').on( 'click', 'tr', function (e) {
      var rows_selected = dt1.rows('.selected').data();
      if(dt1.rows('.selected').data().length > 0){
        $('.bulk_actions').css('display','block');
      }else{
        $('.bulk_actions').css('display','none');
      }
    });  
      
      // end 

  });

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
        //     responsive: true
        //   });



        function viewAssignedList(e){
          var url = '<?php echo base_url();?>';
          var id = $(e).attr("id");
          console.log(id);
          $.ajax({
            url: url + 'jobcard/get_assigned_tasks',
            type: 'POST',
            data: {id:id, [token_name]:token_value},
          }).then(function(data) {
            var objData = jQuery.parseJSON(data);
            console.log(objData.data);
            if (objData.msg == 'success'){
              $('.modal-body').html(objData.data);
            }
          });
        }

        //     $('.model_tasks_list').click(function(){
        //       var url = '<?php //echo base_url();?>';
        //       var id = $(this).attr("id");
        //       console.log(id);
        //        $.ajax({
        //         url: url + 'jobcard/get_assigned_tasks',
        //         type: 'POST',
        //         data: {id:id},
        //         }).then(function(data) {
        //           var objData = jQuery.parseJSON(data);
        //           console.log(objData.data);
        //           if (objData.msg == 'success') 
        //           {
        //             $('.modal-body').html(objData.data);
        //           }

        //   });
        // });


             // var url = '<?php //echo base_url();?>';
    // var formaction_path = '<?php //echo $formaction_path;?>';
      //   $("#send").on('click', function(e) { //e.preventDefault();
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


          </script>
