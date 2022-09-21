<?php if(!empty($users_list)){?>
        <table id="datatable-responsive" class="table table-striped jambo_table bulk_action " cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th data-orderable="false" class="notexport no-sort"> <input type="checkbox" id="check-all" class="flat"> </th>
                    <th>User Name</th>
                    <th>Code</th>
                    <th>Role</th>
                    <!-- <th>City</th> -->
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Created On</th>
                    <th>Updated On</th>
                    <th data-orderable="false" class="notexport">Active/Inactive</th>
                    <!-- <th>Approved</th> -->
                    <th data-orderable="false" class="notexport">Actions</th>

                </tr>
            </thead>
        

            <tbody id="user_listing">
                <?php 
                $j = 0;
                foreach($users_list as $value){
                    $j++;
                    if(!empty($value['documents']))
                    {
                      $documents_exits = 'btn-success';
                    }
                    else
                    {
                      $documents_exits = 'btn-warning';
                    }
                    ?>
                    <tr id="row-<?php echo  $value['id']; ?>">
                        <td class="a-center ">
                        <input type="checkbox"  id="delete_bulk_button" value="<?php echo  $value['id']; ?>" class="flat" name="table_records">
                        </td>
                        <!-- <td><?php echo $j ?></td> -->
                        <td><?php echo $value['username']; ?></td>
                        <td><?php echo $value['code']; ?></td>
                        <td><?php if($value['role'] == 1){ echo " Admin"; } if($value['role'] == 2) { echo " Sales Manager"; } if($value['role'] == 3) { echo " Sales Person";}if($value['role'] == 5) { echo " Operational Department";}if($value['role'] == 6) { echo " Tasker";}?></td>
                        <!-- <td><?php echo $value['city']; ?></td> -->
                        <td><?php echo $value['mobile']; ?></td>
                        <td><?php echo $value['email']; ?></td>
                        <td><?php echo $value['created_on']; ?></td>
                        <td><?php echo $value['updated_on']; ?></td>
                        <td id="status">
                          <?php if($value['status'] == 0) { ?>
                            <a class="btn btn-success" href="<?php echo base_url().'users/chage_status?user_id='.$value['id'].'&status_id=1'?>">Active</a>


                         <!-- <button id="statuss" onclick="changeStatus(this)" value="1" data-id="<?php echo $value['id']; ?>" class="btn btn-success"> Acitve </button> -->
                        <?php } if($value['status'] == 1) { ?>

                          <a class="btn btn-warning" href="<?php echo base_url().'users/chage_status?user_id='.$value['id'].'&status_id=0'?>">InActive</a>
                          <!-- 
                          <button id="statuss" onclick="changeStatus(this)" value="0" data-id="<?php echo $value['id']; ?>" class="btn btn-warning">InActive</button> -->
                        <?php } ?> 
                      </td>

                      <!-- <td><?php //if($value['status'] == 0) { ?>
                            <a class="btn btn-success" href="<?php //echo base_url().'users/approve_user?user_id='.$value['id'].'&status_id=1'?>">Approve User </a> 

                             <?php //} if($value['status'] == 1) { ?>
                          <a class="btn btn-success" disabled >Approved</a>

                          <?php //} ?></td> -->
                        <td>
                            <a href="<?php echo base_url().'users/update_user/'.$value['id']; ?>" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> Edit</a>
                            <a href="<?php echo base_url().'users/documents/'.$value['id']; ?>" class="btn <?php echo $documents_exits; ?> btn-xs"><i class="fa fa-pencil"></i> Documents</a>
                            <button onclick="deleteRecord(this)" type="button" data-obj="users" data-id="<?php echo $value['id']; ?>" data-url="<?php echo base_url(); ?>users/delete" class="btn btn-danger btn-xs" title="Delete"><i class="fa fa-trash"></i> Delete</button>

                        </td>
                    </tr>
                <?php }?>
            </tbody>
        </table>
        <tfoot>
                <th colspan="50%" style="padding: 10px;">
                    <div class="pull-left actions">        
                             
                </tfoot>
            </div>                
            <?php 
              }else{
                echo "<h1>No Record Found</h1>";
                }
              ?>


             <script type="text/javascript">


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

          $("#datatable-responsive").DataTable({
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

              </script>