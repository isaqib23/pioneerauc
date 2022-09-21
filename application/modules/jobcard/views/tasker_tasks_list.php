  
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
        <?php //echo"<pre>"; print_r($task_detail); ?>
        <div class="x_content">
          <?php if(!empty($task_detail)){ ?>
            <table id="datatable-responsive2" class="table table-striped jambo_table bulk_action " cellspacing="0" width="100%">
              <thead>
                <tr>
                  <th data-orderable="false" class="notexport no-sort"> </th>
                  <th>Title</th>
                  <th>Description</th>
                  <th>Category</th>
                  <th>created_on</th>
                  <!-- <th>City</th> -->
                  <!-- <th>updated_on</th> -->
                  <th>created_by</th>
                  <!-- <th>assign_to</th> -->
                  <!-- <th>Updated On</th> -->
                  <th data-orderable="false" class="notexport">Status</th>
                  <th>Action</th>

                </tr>
              </thead>
              <?php  //ksort($task_detail); echo "<pre>"; print_r($task_detail); ?>

              <tbody id="user_listing">
                <?php 
                $j = 0;
                $i = 0;
                $inventory = array();
                foreach($task_detail as $value) {
                    $j++; 

                  foreach ($value as $val) {
                  // echo "<pre>";
                  // print_r($val);
                  
                  $count = count($task_detail);
                  // print_r($count);
                  // if($i == $count )
                  // {
                  //   break;
                  // }
                  // echo "<pre>";print_r($task_detail);
                  // print_r($taskers_ids[$k]['id']);
                    ?>
                    <tr id="row-<?php //echo  $value['id']; ?>">
                      <td class="a-center ">
                      <!-- <input type="checkbox"  id="delete_bulk_button" value="<?php echo  $value['id']; ?>" class="flat" name="table_records"> -->
                      </td>
                      <!-- <td><?php echo $j ?></td> -->
                      <td><?php $task_id =  $val['task_id']; $task_name = $this->db->query('select title from task where id = "'.$task_id.'"')->row_array(); echo $task_name['title']; ?></td>
                      <td><?php $task_name = $this->db->query('select description from task where id = "'.$task_id.'"')->row_array(); echo $task_name['description']; ?></td>
                      
                      <td><?php $task_id =  $val['task_id']; $category_id = $this->db->query('select category_id from task where id= "'.$task_id.'"')->row_array(); 
                       $category_name = $this->db->query('select name from task_category where id= "'.$category_id['category_id'].'"')->row_array(); 
                      echo $category_name['name'];  ?></td>
                      <td><?php echo $val['created_on']; ?></td>
                      <td>
                        <?php if(!empty($val['assign_by'])) { $q = $this->db->query('select email from users where id='.$val['assign_by']); $query = $q->result_array(); if($query){ echo $query[0]['email'];} }?>
                      </td>
            
                         
                      <td id="status">
                          <?php 
                   
                          if($val['status'] == "pending"){ ?> <p style="color: red; font-size: 15px"> Pending </p>
                       <?php  } 

                       if($val['status'] =="in_process"){ ?> <p  style="color:#5bc0de ; font-size: 15px"> In Process</p> <?php } if($val['status'] == "complete"){ ?> <p style="color: green; font-size: 15px"> Completed </p> <?php } ?>
                     
                      </td>
                       <td id="status">
                          <?php  if($val['status'] == "pending"){ ?> 
                            <a class="btn btn-warning btn-xs" href="<?php echo base_url().'jobcard/atempt_to_work?task_detail_id='.$val['id'];?>">  Start Working </a>
                       <?php  }
                         
                        if($val['status'] =="in_process"  && $val['user_id'] == $id ){ ?> <a class="btn btn-info btn-xs" href="<?php echo base_url().'jobcard/task_complete?task_detail_id='.$val['id'].'&task_id='.$val['task_id'];?>">  Complete </a> <?php }if($val['status'] == "in_process" && $val['user_id'] != $id ){ ?> <p style="color: red; font-style: italic;"  >Working...</p>  <?php }  ?>     

                       <?php

                       if($val['status'] =="complete"){ ?>  
                        <i style="color: green" class="fa fa-check"></i>
                        
                       <?php  } 

                        ?>
                      </td>
    
                    </tr>
                <?php  $i++; 
                } } ?>
              </tbody>
            </table>
            <tfoot>
              <th colspan="50%" style="padding: 10px;">
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
</div>

    
  
  
<script type="text/javascript">
  var token_name = '<?= $this->security->get_csrf_token_name();?>';
  var token_value = '<?=$this->security->get_csrf_hash();?>';

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
  //   "scrollX": true
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
    data: {[token_name]:token_value},
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
      data: {[token_name]:token_value},       
    }).then(function(data) {
      // alert(data);
      $('#loading').html('loading...');
      // window.location = url + 'users/';

    });
  }

  $(".username").select2({
    placeholder: "Select Name", 
    width: '200px',
    // allowClear: true,
  });

  // multiple select select2 JQuery 
  $(".code").select2({
    placeholder: "Select code", 
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
