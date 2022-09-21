<?php 
                $j = 0;
                  if(isset($users_list) && !empty($users_list)){
                foreach($users_list as $value){
                    $j++;
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
                            <a class="btn btn-success  btn-xs" href="<?php echo base_url().'jobcard/change_status?user_id='.$value['id'].'&status_id=1'?>">Active</a>


                         <!-- <button id="statuss" onclick="changeStatus(this)" value="1" data-id="<?php echo $value['id']; ?>" class="btn btn-success"> Acitve </button> -->
                        <?php } if($value['status'] == 1) { ?>

                          <a class="btn btn-warning  btn-xs" href="<?php echo base_url().'jobcard/change_status?user_id='.$value['id'].'&status_id=0'?>">InActive</a>
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

                          

                            <button type="button" id="<?php echo $value['id']; ?>" data-toggle="modal" data-target=".bs-example-modal-sm" class="btn btn-info btn-xs model_tasks_list"><i class="fa fa-pencil-square-o"></i> View Assigned Tasks</button>

                            <a href="<?php echo base_url().'jobcard/update_tasker/'.$value['id']; ?>" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> Edit</a>
                      
                            <button onclick="deleteRecord(this)" type="button" data-obj="users" data-id="<?php echo $value['id']; ?>" data-url="<?php echo base_url(); ?>users/delete" class="btn btn-danger btn-xs" title="Delete"><i class="fa fa-trash"></i> Delete</button>

                        </td>
                    </tr>
                <?php }}else{ echo '<tr><td colspan="7"><h3>No Record Found</h3></td></tr>'; }?>