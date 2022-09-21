  
<input type="hidden" id="base_url" value="<?php echo base_url(); ?>">

<!-- <div class="clearfix"></div> -->

<div class="col-md-12 col-sm-12 col-xs-12">

      <!-- <div class="x_title"> -->
        <!-- <h2><?php echo $small_title; ?></h2> -->

        <!-- <div class="clearfix"></div> -->
    <!-- </div> -->
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
      <?php
       // print_r($user_monthly_report);die('aaaaaaaaaaaa');
       ?>
        <div class="x_content"> 
        <hr>
        </div>
      <div class="x_content">
       <?php if(!empty($user_monthly_report[0])){?>

        <table id="datatable-responsive2" class="table table-striped jambo_table bulk_action " cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>Task Name</th>
                    <th>Tasker Email</th>
                    <th>Phone</th>
                    <th>Category</th>
                    <th>Status</th>
                    <th>Created On</th>
                    <th>Attempt Date</th>
                    <th>Complete Date</th>
                    <th>Task assign by</th>

                </tr>
            </thead>
        

            <tbody id="user_listing">
                <?php 
                $j = 0;
                if(  !empty($user_monthly_report[0]) && isset($user_monthly_report[1])  && isset($user_monthly_report[2]) && isset($user_monthly_report[3])  ){
                    foreach ($user_monthly_report as $k) {
                      foreach ($k as $v ) {
                       $j++; ?>
                       <tr id="row-<?php echo  $v['id']; ?>">
                        <!-- <td><?php echo $j ?></td> -->
                        <td><?php $q = $this->db->get_where('task',['id' =>$v['task_id']])->row_array();
                              echo $q['title'] ; ?></td>
                        <td><?php $q = $this->db->get_where('users',['id' =>$v['user_id']])->row_array();
                              echo $q['email'] ; ?></td>
                        <td><?php $q = $this->db->get_where('users',['id' =>$v['user_id']])->row_array();
                              echo $q['mobile'] ; ?></td>
                        <td><?php $q = $this->db->get_where('task_category',['id' =>$v['category_id']])->row_array();
                              echo $q['name'] ; ?></td>
                        <td><?php if($v['status'] == "pending") { ?> <p style="color: red; font-size: 15px"> Pending </p> <?php } if($v['status'] == "in_process"){ ?><p  style="color:#5bc0de ; font-size: 15px"> In Process</p> <?php } if($v['status'] == "complete" ) { ?>  <i style="color: green" class="fa fa-check"></i> <?php  }   ?></td>
                        <td><?php echo $v['created_on']; ?></td>
                        <td><?php echo $v['atempt_date']; ?></td>
                        <td><?php echo $v['complete_date']; ?></td>
                        <td><?php $q = $this->db->get_where('users',['id' =>$v['assign_by']])->row_array();
                              echo $q['email'] ; ?></td>
                    </tr>

                   <?php
                    }
                    }
                  } 
                  elseif (!empty($user_monthly_report[0]) && isset($user_monthly_report[1])  && isset($user_monthly_report[2]) ) {

                    foreach ($user_monthly_report as $k) {
                      foreach ($k as $v ) {
                       $j++; ?>
                       <tr id="row-<?php echo  $v['id']; ?>">
                        <!-- <td><?php echo $j ?></td> -->
                        <td><?php $q = $this->db->get_where('task',['id' =>$v['task_id']])->row_array();
                              echo $q['title'] ; ?></td>
                        <td><?php $q = $this->db->get_where('users',['id' =>$v['user_id']])->row_array();
                              echo $q['email'] ; ?></td>
                        <td><?php $q = $this->db->get_where('users',['id' =>$v['user_id']])->row_array();
                              echo $q['mobile'] ; ?></td>
                        <td><?php $q = $this->db->get_where('task_category',['id' =>$v['category_id']])->row_array();
                              echo $q['name'] ; ?></td>
                        <td><?php if($v['status'] == "pending") { ?> <p style="color: red; font-size: 15px"> Pending </p> <?php } if($v['status'] == "in_process"){ ?><p  style="color:#5bc0de ; font-size: 15px"> In Process</p> <?php } if($v['status'] == "complete" ) { ?>  <p style="color: green; font-size: 15px"> Completed </p> <?php  }   ?></td>
                        <td><?php echo $v['created_on']; ?></td>
                        <td><?php echo $v['atempt_date']; ?></td>
                        <td><?php echo $v['complete_date']; ?></td>
                        <td><?php $q = $this->db->get_where('users',['id' =>$v['assign_by']])->row_array();
                              echo $q['email'] ; ?></td>
                    </tr>

                   <?php
                        }
                      }
                    } 
                     elseif ( !empty($user_monthly_report[0]) && isset($user_monthly_report[1]) ) {

                    foreach ($user_monthly_report as $k) {
                      foreach ($k as $v ) {
                       $j++; ?>
                       <tr id="row-<?php echo  $v['id']; ?>">
                        <!-- <td><?php echo $j ?></td> -->
                        <td><?php $q = $this->db->get_where('task',['id' =>$v['task_id']])->row_array();
                              echo $q['title'] ; ?></td>
                        <td><?php $q = $this->db->get_where('users',['id' =>$v['user_id']])->row_array();
                              echo $q['email'] ; ?></td>
                        <td><?php $q = $this->db->get_where('users',['id' =>$v['user_id']])->row_array();
                              echo $q['mobile'] ; ?></td>
                        <td><?php $q = $this->db->get_where('task_category',['id' =>$v['category_id']])->row_array();
                              echo $q['name'] ; ?></td>
                        <td><?php if($v['status'] == "pending") { ?> <p style="color: red; font-size: 15px"> Pending </p> <?php } if($v['status'] == "in_process"){ ?><p  style="color:#5bc0de ; font-size: 15px"> In Process</p> <?php } if($v['status'] == "complete" ) { ?>  <i style="color: green" class="fa fa-check"></i> <?php  }   ?></td>
                        <td><?php echo $v['created_on']; ?></td>
                        <td><?php echo $v['atempt_date']; ?></td>
                        <td><?php echo $v['complete_date']; ?></td>
                        <td><?php $q = $this->db->get_where('users',['id' =>$v['assign_by']])->row_array();
                              echo $q['email'] ; ?></td>
                    </tr>

                   <?php
                        }
                      }
                    } 
             
                   else{
                    foreach($user_monthly_report[0] as $value){    
                  $j++;
                  ?>
                    <tr id="row-<?php echo  $value['id']; ?>">
                        <!-- <td><?php echo $j ?></td> -->
                        <td><?php $q = $this->db->get_where('task',['id' =>$value['task_id']])->row_array();
                              echo $q['title'] ; ?></td>
                        <td><?php $q = $this->db->get_where('users',['id' =>$value['user_id']])->row_array();
                              echo $q['email'] ; ?></td>
                        <td><?php $q = $this->db->get_where('users',['id' =>$value['user_id']])->row_array();
                              echo $q['mobile'] ; ?></td>
                        <td><?php $q = $this->db->get_where('task_category',['id' =>$value['category_id']])->row_array();
                              echo $q['name'] ; ?></td>
                        <td><?php if($value['status'] == "pending") { ?> <p style="color: red; font-size: 15px"> Pending </p> <?php } if($value['status'] == "in_process"){ ?><p  style="color:#5bc0de ; font-size: 15px"> In Process</p> <?php } if($value['status'] == "complete" ) { ?>  <p style="color: green; font-size: 15px"> Completed </p> <?php  }   ?></td>
                        <td><?php echo $value['created_on']; ?></td>
                        <td><?php echo $value['atempt_date']; ?></td>
                        <td><?php echo $value['complete_date']; ?></td>
                        <td><?php $q = $this->db->get_where('users',['id' =>$value['assign_by']])->row_array();
                              echo $q['email'] ; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <tfoot>
                <th colspan="50%" style="padding: 10px;">
                    <div class="pull-left actions">        
                             
                </tfoot>
            </div>                
            <?php 
             }  }else{
                echo "<h1>No Record Found</h1>";
                }
              ?>
          </div>
        </div>
     
    </div>
  </div>
  
     <div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">

            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
              </button>
              <h4 class="modal-title" id="myModalLabel">Assigned Tasks</h4>
            </div>
            <div class="modal-body">
              
            </div>

          </div>
        </div>
      </div>


      <script>

        
        $("#datatable-responsive2").DataTable({
            dom: "lBfrtip",
            "scrollX": false,
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
            responsive: true
          });


 

          </script>
