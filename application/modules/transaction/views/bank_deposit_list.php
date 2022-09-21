  
  <style type="text/css">
  div.dataTables_wrapper div.dataTables_processing{
    height: auto;
  }
  .dataTables_filter{
    margin-right: 22px;
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
          <div id="users_filter_listing">
            <div class="x_content">
             <?php if(!empty($list)){?>
              <table id="bank_datatable" class="table table-striped" cellspacing="0" width="100%">
                <thead>
                  <tr>
                    <th>User Name</th>
                    <th>Email</th>
                    <th>Deposit Amount</th>
                    <th>Deposit Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (isset($list) && !empty($list)) {
                    foreach ($list as $key => $value) { ?>
                    <tr>
                      <td><?= $value['username']; ?> </td>
                      <td><?= $value['email']; ?> </td>
                      <td><?= $value['deposit_amount']; ?> </td>
                      <td><?= date('Y-m-d', strtotime($value['deposit_date'])); ?> </td>
                      <td><?php switch ($value['status']) {
                        case '0':
                          echo "Pending";
                          break;

                          case '1':
                          echo "Approved";
                          break;
                          
                          case '2':
                          echo "Rejected";
                          break;
                        
                        default:
                          echo '';
                          break;
                      }; ?> </td>
                      <td><a href="<?= base_url('transaction/view_slip/').$value['id']; ?>" class="btn btn-primary" > View </a> </td>
                    </tr>
                  <?php  }
                  } ?>
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

  <script>
    $(document).ready(function() {
      $('#bank_datatable').DataTable({ "bSort" : false });
    });
  </script>
  
     