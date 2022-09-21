<style type="text/css">
  .yellow{
    color: rgba(243,156,18,.88);
  }
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
        
      <div Mileage Depreciation="x_content"> 
          <a type="button" href="<?php echo base_url().'crm/save_crm'; ?>"   class="btn btn-md btn-success  "><i class="fa fa-plus"></i> New CRM Detail</a>
          <div class="clearfix"></div>
      </div>
        
      <div>
        <table class="" style="width:100%">
                    <tbody><tr> 
                      <th>
                        <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7">
                          <p class="">Final Status</p>
                        </div> 
                      </th>
                    </tr>
                    <tr> 
                      <td>
                        <table class="tile_info">
                          <tbody>
                            <tr>
                              <td>
                                <p><i class="fa fa-square green"></i> Won </p>
                              </td> 
                            </tr>
                            <tr>
                              <td>
                                <p><i class="fa fa-square red"></i> Loss </p>
                              </td> 
                            </tr>
                            <tr>
                              <td>
                                <p><i class="fa fa-square blue yellow"></i> Pending </p>
                              </td> 
                            </tr> 
                          </tbody>
                          </table>
                      </td>
                    </tr>
                  </tbody>
                </table>
      </div>
 
        <div class="x_content bulk_actions" style="display: none;">
        <label>Bulk Actions:</label>
        
         <button onclick="deleteRows_Bulk(this)" id="delete_bulk" data-table="datatable-crm-list" type="button" data-obj="crm_detail"  data-token-name="<?= $this->security->get_csrf_token_name();?>" data-token-value="<?=$this->security->get_csrf_hash();?>"data-url="<?php echo base_url(); ?>crm/delete_bulk" class="btn btn-danger btn-xs" title="Delete"><i class="fa fa-trash"></i> Delete Rows</button>

        <hr>
      </div>
        <br>
        <input type="hidden" id="<?= $this->security->get_csrf_token_name();?>" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
      <div class="x_content">
       <?php if(!empty($crm_list)){?>
        <table id="datatable-crm-list" class="table table-striped jambo_table bulk_action" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th data-visible="false" data-orderable="false" class="notexport"></th>
                    <th data-orderable="false" class="notexport">CRM Status</th>
                    <th>Customer Name</th>
                    <th>Created Date</th>
                    <th>Updated Date</th>
                    <th>Email</th>
                    <th>Customer Type</th>
                    <th>Assigned By</th>
                    <th>Assigned To</th>
                    <th data-orderable="false" class="notexport mobile tablet">Actions</th>
                </tr>
            </thead>
            
        </table> 
            </div>                
            <?php 
              }else{
                echo "<h1>No Record Found</h1>";
                }
              ?>
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
              <h4 class="modal-title" id="myModalLabel">Assign To</h4>
            </div>
            <div class="modal-body">
              
            </div>

          </div>
        </div>
      </div>

       <div class="modal fade bs-example-modal-sm2" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">

            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
              </button>
              <h4 class="modal-title" id="myModalLabel2">User Information</h4>
            </div>
            <div class="modal-body2">
              
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
      </script>

<script>

  var token_name = '<?= $this->security->get_csrf_token_name();?>';
  var token_value = '<?=$this->security->get_csrf_hash();?>';
  
  $(document).ready(function() {
  $('#engine_sizes').DataTable();
  });
                
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

  $('input').on('ifChecked', function () { 
    $('#delete_bulk').show();
  })
  $('input').on('ifUnchecked', function (){
    $('#delete_bulk').hide();
  })

  function get_assign_to(e){
    var url = '<?php echo base_url();?>';
    var id = $(e).attr("id");
    console.log(id);
    $.ajax({
      url: url + 'crm/get_assign_to',
      type: 'POST',
      data: {id:id, [token_name]:token_value},
    }).then(function(data) {
      var objData = jQuery.parseJSON(data);
      if (objData.msg == 'success'){
        $('.modal-body').html(objData.data);
      }
    });
  }
  function matureStatus(e){
    var url = '<?php echo base_url();?>';
    var id = $(e).attr("id");
    $.ajax({
      url: url + 'crm/get_user_info_form',
      type: 'POST',
      data: {id:id, [token_name]:token_value},
    }).then(function(data){
      var objData = jQuery.parseJSON(data);
      if (objData.msg == 'success'){
        $('.modal-body2').html(objData.data);
      }
    });
  }


  let exportOptions = {'rows': {selected: true} ,columns: ['1,2,3,4,5,6,7,8']};
  let dtButtons = [{ extend : 'excel',exportOptions: exportOptions},{ extend : 'csv',exportOptions: exportOptions},{ extend : 'pdf',exportOptions: exportOptions},{ extend : 'print',exportOptions: exportOptions},];
  let dt1 = customeDatatable({
    // index zero must be csrf token
    '<?= $this->security->get_csrf_token_name();?>':'<?= $this->security->get_csrf_hash();?>',
    'div' : '#datatable-crm-list',
    'url' : '<?= base_url() ?>crm/crmList',
    'orderColumn' : 4,
    'iDisplayLength' : 10,
    'dom' : '<"pull-left top"B><"pull-right" fl><"clear">r<t><"bottom"ip><"clear">',
    'columnCustomeTypeTarget' : 3,
    'defaultSearching': true,
    'responsive': true,
    'rowId': 'id',
    'stateSave': true,
    'lengthMenu': [[10, 25, 50, 100, 150], [ 10, 25, 50, 100, 150]], // [5, 10, 25, 50, 100, -1], [5, 10, 25, 50, 100, "All"]
    'order' : 'desc',
    'selectStyle' : 'multi',
    'collectionButtons' : dtButtons,
    'processing' : true,
    'serverSide' : true,
    'processingGif' : '<span style="display:inline-block; margin-top:-105px;">Loading.. <img  src="<?=base_url()?>/assets_admin/images/load.gif" /></span>',
    'buttonClassName' : 'btn-sm',
    'columnCustomType' : 'date-eu',
    'customFieldsArray': [],
    'dataColumnArray': [{'data':'id'},{'data':'crm_status'},{'data':'name'},{'data':'date'},{'data':'updated_on'},{'data':'email'},{'data':'customer_type'},{'data':'assigned_by'},{'data':'assigned_to'},{'data':'action'}]
  });

  $(document).ready(function(){
    $('#datatable-crm-list tbody').on( 'click', 'tr', function () {
      $(this).toggleClass('selected');
    });
    $('.buttons-select-none').on( 'click', function () {
      $('.bulk_actions').css('display','none');
    });
    $('.buttons-select-all').on( 'click', function () {
      $('.bulk_actions').css('display','block');
    });

    $('#datatable-crm-list tbody').on( 'click', 'tr', function (e) {
    var rows_selected = dt1.rows('.selected').data();
      if(dt1.rows('.selected').data().length > 0){
        $('.bulk_actions').css('display','block');
      }else{
        $('.bulk_actions').css('display','none');
      }
    });  
  });  
   // $("#datatable-crm-list").DataTable({
   //          dom: "Bfrtip",
   //          buttons: [            
   //          // {
   //          //   extend: "copy",
   //          //   className: "btn-sm",
   //          //   exportOptions: 
   //          //   {
   //          //       columns: [1,2,3,4,5,6,7]
   //          //   }
   //          // },
   //          {
   //            extend: "csv",
   //            className: "btn-sm",
   //            exportOptions: 
   //            {
   //                columns: [1,2,3,4,5,6,7]
   //            }
   //          },
   //          {
   //            extend: "excel",
   //            className: "btn-sm",
   //            exportOptions: 
   //            {
   //                columns: [1,2,3,4,5,6,7]
   //            }
   //          },
   //          {
   //            extend: "pdfHtml5",
   //            className: "btn-sm",
   //            exportOptions: 
   //            {
   //                columns: [1,2,3,4,5,6,7]
   //            }
   //          },
   //          // {
   //          //   extend: "print",
   //          //   className: "btn-sm",
   //          //   exportOptions: 
   //          //   {
   //          //       columns: [1,2,3,4,5,6,7]
   //          //   }
   //          // },
   //          ],
   //          responsive: true,

   //          columnDefs: [ 
   //            { targets:"_all", orderable: false },
   //            { targets:[9], className: "tablet, mobile" }
   //          ]
   //        });

</script>
