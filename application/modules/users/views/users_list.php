  
  <style type="text/css">
  div.dataTables_wrapper div.dataTables_processing{
    height: auto;
  }
  .dataTables_filter{
    margin-right: 22px;
  }
  span.select2-selection.select2-selection--multiple {padding: 0 10px !important;}
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
              <input type="hidden" id="<?= $this->security->get_csrf_token_name();?>" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />

              <div class="form-group ">

                <div class="col-md-4">
                  <input type="text" placeholder="User Name" oninput="this.value=this.value.replace(/[^0-9a-zA-Z. ]/g,'');" class="form-control col-md-7 col-xs-12 username" multiple="" id="username" name="username">
                            
                </div>

                <div class="col-md-4">
                  <select  class="form-control col-md-7 col-xs-12" id="role" name="role">
                    <option value="">Select Role</option>
                    <option value="2">Sales Manager</option>
                    <option value="3">Sales Person</option>
                    <option value="5">Operational Manager</option>
                    <option value="8">Live Auction Cashier</option>
                    <option value="7">Live Auction Controller</option>
                    <option value="6">Tasker</option>
                    <option value="9">Appraiser</option>
                    <option value="10">Marketing</option>
                  </select>
                </div>


                <div class="col-md-4">
                  <input type="text" placeholder="Email" oninput="this.value=this.value.replace(/[^0-9a-zA-Z.@ ]/g,'');" class="form-control col-md-7 col-xs-12" multiple="" id="email" name="email">
                </div>
              </div>


              <div class="form-group "> 
                <div class="col-md-4">
                  <input type="text" placeholder="Mobile Number" oninput="this.value=this.value.replace(/[^0-9a-zA-Z.+ ]/g,'');" class="form-control col-md-7 col-xs-12 mobile" multiple="" id="mobile" name="mobile[]">
                </div>

                <!-- <div class="col-md-4">
                  <select class="form-control col-md-7 col-xs-12 company_name" multiple="" id="company_name" name="company_name[]">
                    <?php //foreach ($code as $type_value) { ?>
                      <option value="<?php //echo $type_value['company_name']; ?>">
                        <?php //echo $type_value['company_name']; ?>
                      </option>
                    <?php  //} ?>
                  </select>
                </div> -->

                <!-- <div class="col-md-4">
                  <select disabled="" class="form-control col-md-7 col-xs-12 item-code" multiple="" id="item_code" name="item_code[]">
                    <?php //foreach ($code as $type_value) { ?>
                      <option value="<?php //echo $type_value['company_name']; ?>">
                        <?php //echo $type_value['company_name']; ?>
                      </option>
                    <?php  //} ?>
                  </select>
                </div> -->
                <div class="col-md-4">
                  <div class='input-group date' id='datefrompic'>
                    <input type='text' class="form-control datefrom"  id='datefrom' name="datefrom" placeholder="From" />
                    <span class="input-group-addon">
                       <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class='input-group date' id='datetopic'>
                    <input type='text' class="form-control dateto" id='dateto' name="dateto" placeholder="To" />
                    <span class="input-group-addon">
                       <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                  </div>
                </div>

                <div class="col-md-4">
                  <select  class="form-control col-md-7 col-xs-12" id="status" name="status">
                    <option value="">Select Status</option>
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                  </select>
                </div>
              </div>


              <div class="form-group ">   
             
              </div>

              <div id="loading"></div>
          
              <div style="float: right" class=" col-md-offset-1">
                  <button type="button" id="bfilter" class="btn btn-success">Filter</button>
              </div>
          </form>
        </div>
        <div class="x_content">
          <hr>
          <div Mileage Depreciation="x_content"> 
            <a type="button" href="<?php echo base_url().'users/save_user'; ?>"   class="btn btn-md btn-success  "><i class="fa fa-plus"></i> New User Detail</a>
            <button onclick="deleteRecord_Bulk(this)" style="display: none;" id="delete_bulk" type="button" data-obj="users" data-url="<?php echo base_url(); ?>users/delete_bulk" class="btn btn-danger btn-xs" title="Delete"><i class="fa fa-trash"></i> Delete</button>
          </div>

        </div>
        <div id="users_filter_listing">
          <div class="x_content">
           <?php if(!empty($users_list)){
            ?>
            <table id="datatable-responsive3" class="table table-striped jambo_table bulk_action" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Created On</th>
                        <th data-orderable="false" class="notexport">Status</th>
                        <!-- <th>Approved</th> -->
                        <th data-orderable="false" class="notexport tablet mobile">Actions</th>

                    </tr>
                </thead>   
            </table>
          
          </div>                
          <?php }else{
            echo "<h1>No Record Found</h1>";
          } ?>
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

    $('#datefrompic').datetimepicker({
      format: 'YYYY-MM-DD'
    });

    $('#datetopic').datetimepicker({
      format: 'YYYY-MM-DD'
    });

  </script>

      <script>
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

    var url = '<?php echo base_url();?>';
    var formaction_path = '<?php echo $formaction_path;?>';
    $("#send").on('click', function(e) { //e.preventDefault();
      var formData = new FormData($("#demo-form2")[0]);
        $.ajax({
          url: url + 'users/' + formaction_path,
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
              $('#user_filter_listing').html(objData.data);
               $('#datatable-responsive2').dataTable().fnDestroy();
                  // datatable.data(objData.data);
                  // datatable.draw();
              // });
                // $("#datatable-responsive").reload();
                // $('#datatable-responsive').DataTable().ajax.reload();
                // refreshTable();
           
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


  let exportOptions = {'rows': {selected: true} ,columns: ['0,1,2,3,4,5,6,7']};
  let dtButtons = [{ extend : 'excel',exportOptions: exportOptions},{ extend : 'csv',exportOptions: exportOptions},{ extend : 'pdf',exportOptions: exportOptions},{ extend : 'print',exportOptions: exportOptions},];
  let dt1 = customeDatatable({
    // index zero must be csrf token
    '<?= $this->security->get_csrf_token_name();?>':'<?= $this->security->get_csrf_hash();?>',
    'div' : '#datatable-responsive3',
    'url' : '<?=base_url()?>users/application_users_list',
    'orderColumn' : 0,
    'iDisplayLength' : 5,
    'dom' : '<"pull-left top"B><"pull-right"fl><"clear">r<t><"bottom"ip><"clear">',
    'columnCustomeTypeTarget' : 7,
    'defaultSearching': true,
    'responsive': true,
    'rowId': 'id',
    'stateSave': true,
    'lengthMenu': [[ 5, 10, 25, 50, 100, 150 ,-1], [5, 10, 25, 50, 100, 150,"All"]],
     // 'paging': false,
    'order' : 'desc',
    'selectStyle' : 'multi',
    'collectionButtons' : dtButtons,
    'processing' : true,
    'serverSide' : true,
    'processingGif' : '<span style="display:inline-block; margin-top:-105px;">Loading.. <img  src="<?=base_url()?>/assets_admin/images/load.gif" /></span>',
    'buttonClassName' : 'btn-sm',
    'columnCustomType' : 'date-eu',
    'customFieldsArray': ['username','email','mobile','role','status','datefrom','dateto'],
    'dataColumnArray': [{'data':'code'},{'data':'username'},{'data':'mobile'},{'data':'email'},{'data':'role'},{'data':'created_on'},{'data':'status'},{'data':'action'}]
  });

  // ,'mobile','datefrom','dateto'
  $(document).ready(function(){
    $('#datatable-responsive3 tbody').on( 'click', 'tr', function () {
      $(this).toggleClass('selected');
    });
    $('.buttons-select-none').on( 'click', function () {
      $('.bulk_actions').css('display','none');
    } );
    $('.buttons-select-all').on( 'click', function () {
      $('.bulk_actions').css('display','block');
    });
      
      //  filter options 
       // $('#username,#code,#mobile,#email').change(function(){
       //   dt1.draw();
       // });
      //  filter options 
    $('#bfilter').click(function(){
      dt1.draw();
    });
    $('#datetopic').on('dp.change', function(e){ 
      if($(".datefrom").find("input").val() != ''){
        console.log($(".datefrom").val());
        dt1.draw();
      }
    });
    $('#datefrompic').on('dp.change', function(e){ 
      if($(".dateto").find("input").val() != ''){
        console.log($(".dateto").val());
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
