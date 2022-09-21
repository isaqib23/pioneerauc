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
    <?php if($this->session->flashdata('msg')){ ?>
        <div class="alert">
            <div class="alert alert-success alert-dismissible fade in" role="alert">
              <button type="button" class="close"  data-dismiss="alert" aria-label="Close" style="color: black;"><span aria-hidden="true">×</span>
              </button>
              <?php  echo $this->session->flashdata('msg');   ?>
          </div>
      </div>
  <?php }?>

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
                                <p><i class="fa fa-square blue"></i> Pending </p>
                              </td> 
                            </tr> 
                          </tbody>
                          </table>
                      </td>
                    </tr>
                  </tbody>
                </table>
      </div>

  <div Mileage To="x_content"> 
    <div Mileage From="x_content"> 

      <div class="x_content">
        <br>
        <a type="button" href="<?php echo base_url().'crm/crm_form'; ?>"   class="btn btn-md btn-success  "><i class="fa fa-plus"></i> New CRM Detail</a>
       <!--  <button onclick="deleteRecord_Bulk(this)" type="button" style="display: none;" id="delete_bulk" data-obj="crm_detail" data-url="<?php echo base_url(); ?>crm/delete_bulk" class="btn btn-danger btn-xs" title="Delete"><i class="fa fa-trash"></i> Delete</button> -->
      </div>
        <div class="x_content bulk_actions" style="display: none;">
        <label>Bulk Actions:</label> 
         <button onclick="deleteRows_Bulk(this)" id="delete_bulk" data-table="datatable-responsive2" type="button" data-obj="crm_detail" data-token-name="<?= $this->security->get_csrf_token_name();?>" data-token-value="<?=$this->security->get_csrf_hash();?>" data-url="<?php echo base_url(); ?>crm/delete_bulk" class="btn btn-danger btn-xs" title="Delete"><i class="fa fa-trash"></i> Delete Rows</button> 
        <hr>
      </div>
      
          <div class="x_content">
            <table id="datatable-responsive2" class="table table-striped jambo_table bulk_action">
                <thead>
                    <tr>
                        <th data-visible="false" data-orderable="false"></th>
                        <th>Customer Name</th>
                        <th>Created Date</th>
                        <th>Updated Date</th>
                        <th>Email</th>
                        <th>Customer Type</th>
                        <th>Assigned By</th>
                        <th>Lead Source</th>
                        <th>Lead Category</th>
                        <th data-orderable="false">CRM Status</th>
                        <th data-orderable="false" class=" mobile tablet">Actions</th>
                    </tr>
                </thead> 
            </table>
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
      </script>

      <script>
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
                    window.location = url + 'crm/crm_sales_list';

             }
          }); 
      });       

                $('input').on('ifChecked', function () { 
                  $('#delete_bulk').show();
                   })
                  $('input').on('ifUnchecked', function (){
                   $('#delete_bulk').hide();
                  })

// $("#datatable-responsive2").DataTable({
//             dom: "Bfrtip",
//             buttons: [            
//             // {
//             //   extend: "copy",
//             //   className: "btn-sm",
//             //   exportOptions: 
//             //   {
//             //       columns: [1,2,3,4,5,6,7,8]
//             //   }
//             // },
//             {
//               extend: "csv",
//               className: "btn-sm",
//               exportOptions: 
//               {
//                   columns: [1,2,3,4,5,6,7,8]
//               }
//             },
//             {
//               extend: "excel",
//               className: "btn-sm",
//               exportOptions: 
//               {
//                   columns: [1,2,3,4,5,6,7,8]
//               }
//             },
//             {
//               extend: "pdfHtml5",
//               className: "btn-sm",
//               exportOptions: 
//               {
//                   columns: [1,2,3,4,5,6,7,8]
//               }
//             },
//             // {
//             //   extend: "print",
//             //   className: "btn-sm",
//             //   exportOptions: 
//             //   {
//             //       columns: [1,2,3,4,5,6,7,8]
//             //   }
//             // },
//             ],
//             responsive: true
//           });

  
  let exportOptions = {'rows': {selected: true} ,columns: ['1,2,3,4,5,6,7,8,9']};
  let dtButtons = [{ extend : 'excel',exportOptions: exportOptions},{ extend : 'csv',exportOptions: exportOptions},{ extend : 'pdf',exportOptions: exportOptions},{ extend : 'print',exportOptions: exportOptions},];
  let dt1 = customeDatatable({
    // index zero must be csrf token
    '<?= $this->security->get_csrf_token_name();?>':'<?= $this->security->get_csrf_hash();?>',
    'div' : '#datatable-responsive2',
    'url' : '<?= base_url() ?>crm/crmSaleList',
    'orderColumn' : [2],
    'iDisplayLength' : 10,
    'dom' : '<"pull-left top"B><"pull-right" fl><"clear">r<t><"bottom"ip><"clear">',
    'columnCustomeTypeTarget' : [2,3],
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
    'dataColumnArray': [{'data':'id'},{'data':'name'},{'data':'date'},{'data':'updated_on'},{'data':'email'},{'data':'customer_type'},{'data':'assigned_by'},{'data':'lead_source_name'},{'data':'lead_category_name'},{'data':'crm_status'},{'data':'action'}]
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

     $('#datatable-responsive2 tbody').on( 'click', 'tr', function (e) {
      var rows_selected = dt1.rows('.selected').data();
      if(dt1.rows('.selected').data().length > 0){
        $('.bulk_actions').css('display','block');
      }else{
        $('.bulk_actions').css('display','none');
      }
    });  
});  

          </script>
