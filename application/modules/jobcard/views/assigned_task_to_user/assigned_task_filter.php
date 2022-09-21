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
                        <td><?php  $a = json_decode($value['assign_to_ids']); foreach ($a as $key ) {
                          $b = $this->db->query('select * from users where id ="'.$key.'"' )->row_array();
                          echo $b['email']; echo "<br>";
                        } ?></td>
                        <td><?php  $a = json_decode($value['assigned_task_ids']); foreach ($a as $key ) {
                          $b = $this->db->query('select * from task where id ="'.$key.'"' )->row_array();
                           echo "-"; echo $b['title']; echo "<br>";
                        } ?></td>
                        <td><?php 
                          $b = $this->db->query('select * from task_category where id ="'.$value['task_category_id'].'"' )->row_array();
                           echo $b['name'];  ?></td>
                        <td><?php echo $value['assigned_date'] ?></td>

                        <td><?php $query = $this->db->query('select status from assigned_tasks_detail where assigned_task_id = "'.$value['id'].'"')->result_array(); 
                        // print_r($query);
                          $flag = false;
                          foreach ($query as $key ) {
                            if($key['status'] == "pending" || $key['status'] == "in_process" ) 
                            {
                              $flag = false;
                              break;
                            }else{  $flag = true;  }
                            }

                            if($flag == true)
                            { ?>
                              <p style="color: green; font-size: 15px"> Completed </p>
                             <?php $this->db->query('update assigned_task set status= "completed" where id = "'.$value['id'].'" ');
                            }
                            else 
                            { ?>
                               <p style="color: red; font-size: 15px"> Pending </p>
                            <?php }
                           ?>
                           
                         </td>
                        <td><?php if(!empty($value['completed_date'])){ echo $value['completed_date']; }else{ echo "N/A"; } ?></td>
                        <td>
                          <?php if($flag == false) {  ?>
                            <a href="<?php echo base_url().'jobcard/edit_assigned_task/'.$value['id']; ?>" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> Edit</a> <?php }else {?> <button class="btn btn-info btn-xs" disabled=""><i class="fa fa-pencil"></i> Edit
                            </button> <?php  } ?>
                             
                             <button type="button" id="<?php echo $value['id']; ?>" data-id="<?php echo $value['id']; ?>" data-toggle="modal" data-target=".bs-example-modal-sm" data-backdrop="static" data-keyboard="false" class="get_tasks_detail"><i class="fa fa-tasks"></i> &nbsp;Task Detail</button>

                            <button onclick="deleteRecord(this)" type="button" data-obj="assigned_task" data-id="<?php echo $value['id']; ?>" data-url="<?php echo base_url(); ?>jobcard/delete" class="btn btn-danger btn-xs" title="Delete"><i class="fa fa-trash"></i> Delete</button>
                        </td>
                    </tr>
                <?php }?>