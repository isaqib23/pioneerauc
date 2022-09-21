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
          <div class='input-group date' id='datefrom'>
            <input type='text' class="form-control" name="datefrom" placeholder="From" />
            <span class="input-group-addon">
               <span class="glyphicon glyphicon-calendar"></span>
            </span>
          </div>
        </div>
        <div class="col-md-3 col-sm-3 col-xs-12  col-md-offset-1">
          <div class='input-group date' id='dateto'>
            <input type='text' class="form-control" name="dateto" placeholder="To" />
            <span class="input-group-addon">
               <span class="glyphicon glyphicon-calendar"></span>
            </span>
          </div>
        </div>
      </div>

      <div class="form-group ">   
      </div>
      </div>

        <div id="loading"></div>
      
            <div  class=" col-md-offset-1">
                <button type="button" id="send" class="btn btn-success">Filter</button>
            </div>
          </form>

        
     
        <div class="x_content">
          <hr>
          <div Mileage Depreciation="x_content"> 
            <a type="button" href="<?php echo base_url().'jobcard/assign_task_to_user'; ?>"   class="btn btn-md btn-success  "><i class="fa fa-plus"></i> Assign Task</a>
            <button onclick="deleteRecord_Bulk(this)" style="display: none;" id="delete_bulk" type="button" data-obj="task" data-url="<?php echo base_url(); ?>jobcard/delete_bulk" class="btn btn-danger btn-xs" title="Delete"><i class="fa fa-trash"></i> Delete</button>
          </div>

        </div>
      
      <div class="x_content">
       <?php if(!empty($assigned_task_list)){?>
        <table id="datatable-responsive2" class="table table-striped jambo_table bulk_action " cellspacing="0" width="100%">
          <thead>
            <tr>
              <th data-orderable="false" class="notexport no-sort"> <input type="checkbox" id="check-all" class="flat"> </th>
              <th>Assigned Users</th>
              <th>Assigned Tasks</th>
              <th>Assigned Date</th>
              <th>Status</th>
              <th>Completed Date</th>
              <th> Action </th>
            </tr>
          </thead>

          <tbody id="user_listing">
            <?php 
            $j = 0;
            foreach($assigned_task_list as $value){
              $j++;
            
              ?>
              <tr id="row-<?php echo  $value['id']; ?>">
                <td class="a-center ">
                <input type="checkbox"  id="delete_bulk_button" value="<?php echo  $value['id']; ?>" class="flat" name="table_records">
                </td>
                <!-- <td><?php echo $j ?></td> -->
                <td><?php echo $value['user_id']; ?></td>
                <td><?php echo $value['task_id']; ?></td>
                <td><?php echo $value['assigned_date'] ?></td>
                <td><?php echo $value['status']; ?></td>
                <td><?php echo $value['completed_date']; ?></td>
                <td>
                  <a href="<?php echo base_url().'jobcard/edit_assigned_task/'.$value['id']; ?>" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> Edit</a> 
                  <button onclick="deleteRecord(this)" type="button" data-obj="task" data-id="<?php echo $value['id']; ?>" data-url="<?php echo base_url(); ?>jobcard/delete" class="btn btn-danger btn-xs" title="Delete"><i class="fa fa-trash"></i> Delete</button>
                </td>
              </tr>
            <?php }?>
          </tbody>
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
  
      <script type="text/javascript">
          window.setTimeout(function() {
              $(".alert").fadeTo(500, 0).slideUp(500, function(){
                  $(this).remove(); 
              });
          }, 3000);

          $('#datefrom').datetimepicker({
          format: 'YYYY-MM-DD'
            });

            $('#dateto').datetimepicker({
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





        $("#datatable-responsive2").DataTable({
            dom: "Bfrtip",
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

          </script>
