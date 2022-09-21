
<div class="col-md-12 col-sm-12 col-xs-12">
    <!-- <div class="x_panel"> -->
      <!-- <div class="x_title">

        <div class="clearfix"></div>
    </div> -->
  <div Mileage To="x_content"> 
    <div Mileage From="x_content"> 
      <div class="x_content">
       <?php if(!empty($assigned_tasks_detail)){?>
        <table id="datatable-responsive2" class="table table-striped jambo_table bulk_action " cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>Task</th>
                    <th>Description</th>

                    <th>Work by</th>
                    <th>Attempt Date</th>
                    <th>Completed Date</th>
                    <th>Status</th>
                </tr>
            </thead>
        

            <tbody id="user_listing">
                <?php 
                $j = 0;
                foreach($assigned_tasks_detail as $value){
                    $j++;
                    ?>
                    <tr id="row-<?php echo  $value['id']; ?>">
                        
                        <!-- <td><?php echo $j ?></td> -->
                        <td><?php  $a = $this->db->query('select * from task where id = "'.$value['task_id'].'"')->row_array(); echo $a['title']; ?></td>
                       
                        <td><?php  $a = $this->db->query('select * from task where id = "'.$value['task_id'].'"')->row_array(); echo $a['description']; ?></td>

                        <td><?php  if(!empty($value['user_id'])){  $a = $this->db->query('select * from users where id= "'.$value['user_id'].'"')->row_array(); echo $a['email'];} else { echo " N/A ";} ?></td>
                        <td><?php echo ($value['atempt_date']!= "")? $value["atempt_date"] : "N/A";?></td>
                        <td><?php echo ($value['complete_date']!= "")? $value["complete_date"] : "N/A";?></td>
                        <td><?php if($value['status'] == "pending") { ?> <p style="color: red; font-size: 15px"> Pending </p> <?php } if($value['status'] == "in_process"){ ?><p  style="color:#5bc0de ; font-size: 15px"> Working...</p> <?php } if($value['status'] == "complete" ) { ?> <p style="color: green; font-size: 15px"> Completed </p> <?php  }   ?></td>
                    </tr>
                <?php }?>
            </tbody>
        </table>
            </div>                
            <?php 
              }?>
          </div>
        </div>
      <!-- </div> -->
    </div>
<script type="text/javascript"></script>