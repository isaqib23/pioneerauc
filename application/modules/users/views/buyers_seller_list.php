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

    <div Mileage From="x_content"> 
        
     <form method="post" id="demo-form2" action="" enctype="multipart/form-data" class="form-horizontal form-label-left ">
      <input type="hidden" id="<?= $this->security->get_csrf_token_name();?>" name="<?= $this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />

      <div class="form-group ">   

            <div class="col-md-4">
                 <input type="text" oninput="this.value=this.value.replace(/[^0-9a-zA-Z. ]/g,'');" class="form-control col-md-7 col-xs-12" placeholder="User Name" id="username" name="username">
                        
                  <!-- </select> -->
            </div>

            <div class="col-md-4">
                <input type="text" oninput="this.value=this.value.replace(/[^0-9. ]/g,'');" class="form-control col-md-7 col-xs-12 code" placeholder="Code" id="code" name="code">
                 
            </div>


            <div class="col-md-4">
                <input type="text" oninput="this.value=this.value.replace(/[^0-9a-zA-Z.@ ]/g,'');" class="form-control col-md-7 col-xs-12 email" placeholder="Email" id="email" name="email">
                   
            </div>
        </div>
        
       <div  class="form-group "> 
            <div class="col-md-4">
              <div class='input-group date datefrom' >
                <input type='text' class="form-control" name="datefrom" id='datefrom' placeholder="From" />
                <span class="input-group-addon">
                  <span class="glyphicon glyphicon-calendar"></span>
                </span>
              </div>
            </div>
         <div class="col-md-4">
              <div class='input-group date dateto'>
                <input type='text' class="form-control" id='dateto' name="dateto" placeholder="To" />
                <span class="input-group-addon">
                   <span class="glyphicon glyphicon-calendar"></span>
                </span>
              </div>
            </div>  
             <div class="col-md-4">
                <input type="text" oninput="this.value=this.value.replace(/[^0-9.+ ]/g,'');" class="form-control col-md-7 col-xs-12 mobile" placeholder="Mobile Number" id="mobile" name="mobile">
                  
            </div>
        </div>

        <div class="form-group">  
          <div class="col-md-4">
            <input type="text" oninput="this.value=this.value.replace(/[^0-9a-zA-Z. ]/g,'');" class="form-control col-md-7 col-xs-12 company_name" placeholder="Company Name" id="company_name" name="company_name">
                   
            </select>
          </div> 
        </div> 

        <div class="form-group">  
          <div class="col-md-4">
            <button type="button" id="bfilter" class="btn btn-success">Filter</button>
          </div>
        </div>  

      </div> 
          </form>
        <div class="x_content">
        <hr>
        
          <div Mileage Depreciation="x_content"> 
            <?php if($user['role'] == 1){ ?>
              <a type="button" href="<?php echo base_url().'users/save_user?user_type=customers'; ?>"   class="btn btn-md btn-success  "><i class="fa fa-plus"></i> New User Detail</a>
            <?php } ?>
         <button onclick="deleteRecord_Bulk(this)" style="display: none;" id="delete_bulk" type="button" data-obj="users" data-url="<?php echo base_url(); ?>users/delete_bulk" class="btn btn-danger btn-xs" title="Delete"><i class="fa fa-trash"></i> Delete</button>
      </div>

        </div>
      

      <div class="x_content">
       <?php if(!empty($users_sellers_buyers)){?>
        <table id="datatable-responsive2" class="table table-striped jambo_table bulk_action" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th data-visible="false" > </th>
                    <th>User Name</th>
                    <th>Code</th>
                    <th>type</th>
                    <th>Company Name</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Created On</th>
                    <th>Updated On</th>
                    <th data-orderable="false">User Status</th>
                    <th data-orderable="false" class="notexport tablet mobile">Actions</th>

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


   // var select2Oz = remoteSelect2({
   //    'selectorId' : '.username',
   //    'placeholder' : 'User Name',
   //    'table' : 'users',
   //    'values' : 'Name',
   //    'width' : '200px',
   //    'delay' : '250',
   //    'cache' : false,
   //    'minimumInputLength' : 1,
   //    'limit' : 5,
   //    'url' : '<?= base_url();?>users/customerusername_api'
   //  }); 

   // var select2Oz = remoteSelect2({
   //    'selectorId' : '#company_name',
   //    'placeholder' : 'Company Name',
   //    'table' : 'users',
   //    'values' : 'Name',
   //    'width' : '200px',
   //    'delay' : '250',
   //    'cache' : false,
   //    'minimumInputLength' : 1,
   //    'limit' : 5,
   //    'url' : '<?= base_url();?>users/customerCompanyNameApi'
   //  }); 

   // var select2Oz = remoteSelect2({
   //    'selectorId' : '#code',
   //    'placeholder' : 'User Code',
   //    'table' : 'users',
   //    'values' : 'Name',
   //    'width' : '200px',
   //    'delay' : '250',
   //    'cache' : false,
   //    'minimumInputLength' : 1,
   //    'limit' : 5,
   //    'url' : '<?= base_url();?>users/customercode_api'
   //  }); 
   // var select2Oz = remoteSelect2({
   //    'selectorId' : '#assigned_to',
   //    'placeholder' : 'User Email',
   //    'table' : 'users',
   //    'values' : 'Name',
   //    'width' : '200px',
   //    'delay' : '250',
   //    'cache' : false,
   //    'minimumInputLength' : 1,
   //    'limit' : 5,
   //    'url' : '<?= base_url();?>users/customeremail_api'
   //  });
   //  var select2Oz = remoteSelect2({
   //    'selectorId' : '.mobile',
   //    'placeholder' : 'User Mobile',
   //    'table' : 'users',
   //    'values' : 'Name',
   //    'width' : '200px',
   //    'delay' : '250',
   //    'cache' : false,
   //    'minimumInputLength' : 1,
   //    'limit' : 5,
   //    'url' : '<?= base_url();?>users/customermobile_api'
   //  }); 




  let exportOptions = {'rows': {selected: true} ,columns: ['1,2,3,4,5,6,7,8']};
  let dtButtons = [{ extend : 'excel',exportOptions: exportOptions},{ extend : 'csv',exportOptions: exportOptions},{ extend : 'pdf',exportOptions: exportOptions},{ extend : 'print',exportOptions: exportOptions},];
  let dt1 = customeDatatable({
    '<?= $this->security->get_csrf_token_name();?>':'<?= $this->security->get_csrf_hash();?>',
    'div' : '#datatable-responsive2',
    'url' : '<?=base_url()?>users/getSellerBuyer',
    'orderColumn' : 8,
    'iDisplayLength' : 10,
    'dom' : '<"pull-left top"B><"pull-right"fl><"clear">r<t><"bottom"ip><"clear">',
    'columnCustomeTypeTarget' : [7,8],
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
    'customFieldsArray': ['username','code','role','mobile','company_name','email','datefrom','dateto'],
    'dataColumnArray': [{'data':'user_id'},{'data':'username'},{'data':'id'},{'data':'reg_type'},{'data':'company_name'},{'data':'mobile'},{'data':'email'},{'data':'created_on'},{'data':'updated_on'},{'class':'dropdown','data':'status'},{'data':'action'}]
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
       // $('#username,#code,#assigned_to,#mobile,#company_name').change(function(){
       //   dt1.draw();
       // });
      //  filter options 
       $('#bfilter').click(function(){
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
      //   responsive: true

      // });
            // dom: "Bfrtip",
          //   "scrollX": true,
          //   "aoColumnDefs": [ 
          //       {"bSortable": false, "aTargets": 'no-sort'},
          //   ],
          //   buttons: [
          //   // {
          //   //   extend: "copy",
          //   //   className: "btn-sm"
          //   // },
          //   {
          //     extend: "csv",
          //     className: "btn-sm"
          //   },
          //   {
          //     extend: "excel",
          //     className: "btn-sm"
          //   },
          //   {
          //     extend: "pdfHtml5",
          //     className: "btn-sm"
          //   },
          //   // {
          //   //   extend: "print",
          //   //   className: "btn-sm"
          //   // },
          //   ],
          //   // responsive: true
          // });
             // $('#datatable-responsive2').DataTable( {
             // "scrollX": false
             //   });

      </script>

      <script>
              

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
              $('#user_listing').html(objData.data);
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
  // $(".username").select2({
  //   placeholder: "Select Name", 
  //   width: '200px',
  // });

  // multiple select select2 JQuery 
 //  $(".code").select2({
 //    placeholder: "Select code", 
 //    width: '200px'
 //  });
 //  $(".email").select2({
 //    placeholder: "Select Email", 
 //    width: '200px'
 //  });
 // $("#mobile").select2({
 //    placeholder: "Select mobile", 
 //    width: '200px'
 //  });
  //  $(".company_name").select2({
  //   placeholder: "Select Company Name", 
  //   width: '200px'
  // });
  //  $(".item-code").select2({
  //   placeholder: "Select Item Code", 
  //   width: '200px'
  // });
 

 


          </script>
