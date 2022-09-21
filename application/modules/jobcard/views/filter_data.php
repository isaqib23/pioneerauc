<?php 
$j = 0;
if(isset($task_list) && !empty($task_list)){
foreach($task_list as $value){
                        $j++;
                   ?>
                   <tr id="row-<?php echo  $value['id']; ?>">
                        <td class="a-center ">
                        <input type="checkbox"  id="delete_bulk_button" value="<?php echo  $value['id']; ?>" class="flat" name="table_records">
                        </td>
                        <!-- <td><?php echo $j ?></td> -->
                        <td><?php echo $value['title']; ?></td>
                        <td><?php echo $value['description']; ?></td>
                        <td><?php if($value['category_id'] =="" ){ $value['category_id'] = 1;  }  $q = $this->db->query('select name from task_category where id='.$value['category_id']); $query = $q->row_array(); if($query){ echo $query['name'];}else { ?> <span class="badge badge-default">No Category</span> <?php } ?></td>
                        <td><?php echo $value['created_on']; ?></td>
                        <td><?php echo $value['updated_on']; ?></td>
                        <!-- <td><?php echo $value['created_by']; ?></td> -->
                        <!-- <td><?php $q = $this->db->query('select code from users where id='.$value['assign_to']); $query = $q->result_array(); if($query){ echo $query[0]['code'];}else { ?> <span class="badge badge-default"> User Inactive</span> <?php } ?></td> -->
                         <td><?php if($value['completed_date'] || $value['status'] == "complete"){ echo $value['completed_date'];}else{ ?> N/A <?php } ?></td>
                        <td id="status">
                         <?php  if($value['status'] == "complete"){ ?> <span class="tag tag-success tag-sm"> Completed</span>
                       <?php  } else { ?>
                             <span class=" " id="pending"> Pending</span>
                        <?php  } ?>  
                      </td>
                        <td>
                            <a href="<?php echo base_url().'jobcard/edit_task/'.$value['id']; ?>" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> Edit</a> 
                            <button onclick="deleteRecord(this)" type="button" data-obj="task" data-id="<?php echo $value['id']; ?>" data-url="<?php echo base_url(); ?>jobcard/delete" class="btn btn-danger btn-xs" title="Delete"><i class="fa fa-trash"></i> Delete</button>

                        </td>
                    </tr>
<?php }}else{ echo '<tr><td colspan="7"><h3>No Record Found</h3></td></tr>'; }?>