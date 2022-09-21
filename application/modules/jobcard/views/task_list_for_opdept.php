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

  <?php if(!empty($tasker_tasks_list)){ ?>
    <table id="datatable-responsive" class="table table-striped jambo_table bulk_action" cellspacing="0" width="100%">
      <thead>
        <tr>
          <th>Title</th>
          <th>Description</th>
          <th>created_on</th>
          <th>updated_on</th>
          <th>Completed Date</th>
          <th data-orderable="false" class="notexport">Status</th>
        </tr>
      </thead>
      <tbody id="user_listing">
        <?php 
        $j = 0;
        foreach($tasker_tasks_list as $value){
          $j++;
        
          ?>
          <tr id="row-<?php echo  $value['id']; ?>">
           
            <td><?php echo $value['title']; ?></td>
            <td><?php echo $value['description']; ?></td>
            <td><?php echo $value['created_on']; ?></td>
            <td><?php  echo $value['updated_on']; ?></td>
            <td><?php if($value['completed_date']){ echo $value['completed_date'];}else{ ?>N/A <?php } ?></td>

            <td>
              <?php if($value['status'] == "complete"){ ?>
                <i class="fa fa-check"></i>
              <?php  } else { ?>
                <span id="pending" class="">  Pending</span> 
              <?php  } ?>
            </td>
             
          </tr>
        <?php } ?>
      </tbody>
    </table>
            
  <?php }else{
    echo "<h1>No Record Found</h1>";
  } ?>
         

    
  
  
  <script type="text/javascript">
    window.setTimeout(function() {
      $(".alert").fadeTo(500, 0).slideUp(500, function(){
        $(this).remove(); 
      });
    }, 3000);

    

  </script>

<script>
  $("#datatable-responsive3").DataTable({
    dom: "Bfrtip",
    "scrollX": true,
    "aoColumnDefs": [ 
        {"bSortable": false, "aTargets": 'no-sort'},
    ],
    buttons: [
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
    ],
    responsive: true
  });

</script>
