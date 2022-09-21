<?php 
  $j = 0;
  $CI =& get_instance();
  if(isset($users_list) && !empty($users_list)){
  foreach($users_list as $value){
                        $j++;
                        $bank_details_arr = $this->users_model->bank_detail_list($value['id']);
                        $deposit_details_arr = $this->users_model->deposit_detail_list($value['id']);
                    if(!empty($value['documents']))
                    {
                      $if_documents = 'btn-success';
                    }
                    else
                    {
                      $if_documents = 'btn-warning';
                    }

                    if(isset($bank_details_arr) && !empty($bank_details_arr))
                    {
                        $if_bank_detail = 'btn-success';
                    }else{
                        $if_bank_detail = 'btn-warning';
                    }
                     if(isset($deposit_details_arr) && !empty($deposit_details_arr))
                    {
                        $if_deposit_detail = 'btn-success';
                    }else{
                        $if_deposit_detail = 'btn-warning';
                    }

                    ?>
                     <tr id="row-<?php echo  $value['id']; ?>">
                        <td class="a-center ">
                        <input type="checkbox"  id="delete_bulk_button" value="<?php echo  $value['id']; ?>" class="flat" name="table_records">
                        </td>
                        <!-- <td><?php echo $j ?></td> -->
                        <td><?php echo $value['username']; ?></td>
                        <td><?php echo $value['code']; ?></td>
                        <!-- <td><?php //if($value['role'] == 1){ echo " Admin"; } if($value['role'] == 2) { echo " Sales Manager"; } if($value['role'] == 3) { echo " Sales Person";} if($value['role'] == 4) { echo " User"; } ?></td> -->

                        <td><?php echo $value['type'] ?> </td>
                        <!-- <td><?php $query = $this->db->query('select email from users where sales_id ='.$value['sales_id']); $q = $query->result_array(); echo $q[0]['email']; ?></td> -->
                        <!-- <td><?php echo $value['city']; ?></td> -->
                        <td><?php echo $value['mobile']; ?></td>
                        <td><?php echo $value['email']; ?></td>
                        <td><?php echo $value['created_on']; ?></td>
                        <td><?php echo $value['updated_on']; ?></td>
                        <td>Pending</td>
                        <td id="status" class="dropdown">
                                            
                          <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">
                          <span class="caret"></span></button>
                          <ul class="dropdown-menu">
                            <li> <?php if($value['status'] == 0) { ?>
                            <a class="" href="<?php echo base_url().'users/chage_status?user_id='.$value['id'].'&status_id=1'?>">Active</a>

                         <!-- <button id="statuss" onclick="changeStatus(this)" value="1" data-id="<?php echo $value['id']; ?>" class="btn btn-success"> Acitve </button> -->
                        <?php } if($value['status'] == 1) { ?>

                          <a class="" href="<?php echo base_url().'users/chage_status?user_id='.$value['id'].'&status_id=0'?>">InActive</a>
                          <!-- 
                          <button id="statuss" onclick="changeStatus(this)" value="0" data-id="<?php echo $value['id']; ?>" class="btn btn-warning">InActive</button> -->
                        <?php } ?></li>


                      <li><?php if($value['status'] == 0) { ?>
                            <a class="" href="<?php echo base_url().'users/approve_user?user_id='.$value['id'].'&status_id=1'?>">Approve User </a> 

                             <?php } if($value['status'] == 1) { ?>
                          <a class="" disabel >Approved</a>
                            <?php } ?></li>
                      </ul>
                          
                      </td>
                      <!-- <td></td> -->


                        <td>
                            <a href="<?php echo base_url().'users/update_user/'.$value['id'].'?user_type=customers'; ?>" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> Edit</a>
                            <a href="<?php echo base_url().'users/bank_detail/'.$value['id']; ?>" class="btn <?php echo  $if_bank_detail; ?> btn-xs"> Bank Detail</a>
                            <a href="<?php echo base_url().'users/documents/'.$value['id'].'/byr_sellr'; ?>" class="btn <?php echo $if_documents; ?>  btn-xs"><i class="fa fa-pencil"></i> Documents</a>
                            <a href="<?php echo base_url().'users/manage_deposite_acount/'.$value['id']; ?>" class="btn <?php echo $if_deposit_detail; ?> btn-xs"><i class="fa fa-pencil"></i> Manage Deposite Amount</a>
                            <button onclick="deleteRecord(this)" type="button" data-obj="users" data-id="<?php echo $value['id']; ?>" data-url="<?php echo base_url(); ?>users/delete" class="btn btn-danger btn-xs" title="Delete"><i class="fa fa-trash"></i> Delete</button>

                        </td>
                    </tr>
<?php }}else{ echo '<tr><td colspan="7"><h3>No Record Found</h3></td></tr>'; }?>